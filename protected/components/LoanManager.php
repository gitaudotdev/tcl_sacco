<?php

class LoanManager{
    /*********************************************
    Account Number 
    e.g.Branch Number + ID + Last Digits 
     *************************************************/
    public static function determineAccountNumber($accountID){
        $account=Loanaccounts::model()->findByPk($accountID);
        if(!empty($account)){
            $profile = Profiles::model()->findByPk($account->user_id);
            if(!empty($profile)){
                $loanCounter = LoanManager::determineUserAccountCount($profile->id,$accountID);
                $determinedAccountNumber = $profile->branchId."".$profile->idNumber."/".$loanCounter;
            }else{
                $determinedAccountNumber="";
            }
        }else{
            $determinedAccountNumber="";
        }
        return $determinedAccountNumber;
    }

    public static function determineUserAccountCount($userID,$accountID){
        $loanDetails  = array();
        $acountsQuery = "SELECT * FROM loanaccounts WHERE user_id=$userID";
        $accounts     = Loanaccounts::model()->findAllBySql($acountsQuery);
        if(!empty($accounts)){
            foreach($accounts AS $account){
                array_push($loanDetails, $account->loanaccount_id);
            }
            $counter=array_search($accountID, $loanDetails) + 1;
        }else{
            $counter=0;
        }
        return $counter;
    }

    public static function getOtherProfileLoanAccounts($profileId,$accountId){
        return Loanaccounts::model()->findAllBySql("SELECT * FROM loanaccounts WHERE user_id=$profileId AND loanaccount_id !=$accountId ORDER BY loanaccount_id DESC");
    }
    /*********************************************

    Loan Account Principal

     *************************************************/
    public static function getPrincipalDisbursed($accountID){
        $account=Loanaccounts::model()->findByPk($accountID);
        return !empty($account) ? $account->NotFormattedExactAmountDisbursed : 0;
    }

    public static function getPrincipalPaid($accountID){
        $principalQuery="SELECT COALESCE(SUM(principal_paid),0) as principal_paid FROM loanrepayments WHERE
		 loanaccount_id=$accountID AND is_void IN('0','4')";
        $principal=Loanrepayments::model()->findBySql($principalQuery);
        return !empty($principal) ? $principal->principal_paid : 0;
    }

    public static function getPrincipalBalance($accountID){
        $principalDisbursed = LoanManager::getPrincipalDisbursed($accountID);
        $principalPaid      = LoanManager::getPrincipalPaid($accountID);
        $principalBalance   = $principalDisbursed - $principalPaid;
        return $principalBalance <= 0 ? 0: $principalBalance;
    }


    public static function getInterestAmount($interest_rate,$period,$amount_applied)
    {
        $principal = $amount_applied;
        $dailyInterest = ($interest_rate/30) / 100;

        $interest = $principal * $dailyInterest * $period; // Calculate interest for freeze period
        return round($interest);
    }

    public static function getTotalLoanInterestAmount($interest_rate,$amount_applied)
    {
        $principal = $amount_applied;
        $percentInterest = $interest_rate / 100;

        $interest = $principal * $percentInterest;
        return round($interest);
    }

    /*********************************************
    Loan Account Accrued Interest
     *************************************************/
    public static function getUnpaidLoanInterestBalance($accountID){
        $debitInterestAmount = LoanManager::getDebitInterestAmount($accountID);
        $creditInterestAmount= LoanManager::getCreditInterestAmount($accountID);
        $balance             = $debitInterestAmount-$creditInterestAmount;
        return $balance <= 0 ? 0 : $balance;
    }

    public static function getDebitInterestAmount($accountID){
        $debitQuery = "SELECT COALESCE(SUM(interest_accrued),0) AS interest_accrued FROM loaninterests WHERE loanaccount_id=$accountID
    AND transaction_type='debit' AND is_paid='0'";
        $interest   = Loaninterests::model()->findBySql($debitQuery);
        return !empty($interest) ? $interest->interest_accrued : 0;
    }

    public static function getCreditInterestAmount($accountID){
        $creditQuery = "SELECT COALESCE(SUM(interest_accrued),0) AS interest_accrued FROM loaninterests WHERE loanaccount_id=$accountID
    AND transaction_type='credit' AND is_paid='1'";
        $interest    = Loaninterests::model()->findBySql($creditQuery);
        return !empty($interest) ? $interest->interest_accrued : 0;
    }

    public static function getPaidAccruedInterest($accountID){
        return LoanRepayment::getAccountTotalInterestPaid($accountID);
    }

    public static function getUnpaidAccruedInterest($accountID){
        return LoanManager::getUnpaidLoanInterestBalance($accountID);
    }

    public static function getUnpaidAccruedInterestPrior($accountID,$endDate){
        $interestQuery = "SELECT COALESCE(SUM(interest_accrued),0) AS interest_accrued FROM loaninterests WHERE loanaccount_id=$accountID
    AND is_paid='0' AND transaction_type='debit' AND (DATE(accrued_at) <= '$endDate')";
        $interest      = Loaninterests::model()->findBySql($interestQuery);
        return !empty($interest) ? $interest->interest_accrued : 0;
    }

    public static function getAccruedInterestPrior($accountID,$endDate){
        $interestQuery = "SELECT COALESCE(SUM(interest_accrued),0) AS interest_accrued FROM loaninterests WHERE loanaccount_id=$accountID
    AND is_paid='0' AND transaction_type='debit' AND (DATE(accrued_at) <= '$endDate')";
        $interest      = Loaninterests::model()->findBySql($interestQuery);
        return !empty($interest) ? $interest->interest_accrued : 0;
    }

    public static function voidAccruedInterest($accountID){
        $interestQuery= "SELECT * FROM loaninterests WHERE loanaccount_id=$accountID AND is_paid='0'";
        $interests    = Loaninterests::model()->findAllBySql($interestQuery);
        if(!empty($interests)){
            foreach($interests AS $interest){
                $interest->is_paid = '1';
                $interest->update();
            }
            $voided = 1;
        }else{
            $voided = 0;
        }
        return $voided;
    }

    public static function recordAccruedInterest($accountID,$amount,$transactionType,$paymentStatus){
        $accrueInterest = new Loaninterests;
        $accrueInterest->loanaccount_id = $accountID;
        $accrueInterest->interest_accrued = $amount;
        $accrueInterest->transaction_type = $transactionType;
        $accrueInterest->is_paid = $paymentStatus;
        $accrueInterest->accrued_at = date('Y-m-d H:i:s');
        $accrueInterest->save();
    }

    public static function voidInterest($accruedInterestID){
        $accrued     = Loaninterests::model()->findByPk($accruedInterestID);
        $loanaccount = Loanaccounts::model()->findByPk($accrued->loanaccount_id);
        $interestAmount = $accrued->interest_accrued;
        $accrued->is_paid = '1';
        if($accrued->save()){
            $accountNumber=$loanaccount->account_number;
            $fullName=$loanaccount->BorrowerFullName;
            Logger::logUserActivity("Voided Accrued Interest:$interestAmount,Account:$accountNumber,Client:$fullName",'urgent');
            $voided=1;
        }else{
            $voided=0;
        }
        return $voided;
    }

    /*********************************************

    Loan Account Accrued Penalty

     *************************************************/
    public static function getPaidAccruedPenalty($accountID){
        $penaltyQuery = "SELECT COALESCE(SUM(penalty_amount),0) AS penalty_amount FROM penaltyaccrued WHERE loanaccount_id=$accountID AND is_paid ='1'";
        $penalty      = Penaltyaccrued::model()->findBySql($penaltyQuery);
        return !empty($penalty) ?  $penalty->penalty_amount :  0;
    }

    public static function getUnpaidAccruedPenalty($accountID){
        $penaltyQuery = "SELECT COALESCE(SUM(penalty_amount),0) AS penalty_amount FROM penaltyaccrued WHERE
     loanaccount_id=$accountID AND is_paid='0'";
        $penalty      = Penaltyaccrued::model()->findBySql($penaltyQuery);
        return !empty($penalty) ? $penalty->penalty_amount : 0;
    }

    public static function voidCurrentPenaltyRecords($accountID){
        $voidQuery="SELECT * FROM penaltyaccrued WHERE loanaccount_id=$accountID AND is_paid='0'";
        $penalties=Penaltyaccrued::model()->findAllBySql($voidQuery);
        if(!empty($penalties)){
            foreach($penalties AS $penalty){
                $penalty->is_paid='1';
                $penalty->save();
            }
            $voided=1;
        }else{
            $voided=0;
        }
        return $voided;
    }

    public static function createPenaltyRecord($accountID,$penaltyAmount){
        $penalty=new Penaltyaccrued;
        $penalty->loanaccount_id=$accountID;
        $penalty->date_defaulted=date('Y-m-d');
        $penalty->penalty_amount=$penaltyAmount;
        $penalty->created_at = date('Y-m-d H:i:s');
        $penalty->save();
    }

    public static function freezePenaltyAccrual($accountID,$period,$reason){
        $loanaccount=Loanaccounts::model()->findByPk($accountID);
        switch(LoanManager::checkIfAccountAlreadyFrozen($accountID)) {
            case 0:
                if (LoanManager::freezeLoanAccount($accountID) === 1) {
                    $penalty = new PenaltyFreezes;
                    $penalty->loanaccount_id = $accountID;
                    $penalty->branch_id = $loanaccount->branch_id;
                    $penalty->user_id = $loanaccount->user_id;
                    $penalty->rm = $loanaccount->rm;
                    $penalty->date_frozen = date('Y-m-d H:i:s');
                    $penalty->period_frozen = $period;
                    $penalty->freezing_reason = $reason;
                    $penalty->frozen_by = Yii::app()->user->user_id;
                    $penalty->created_by = Yii::app()->user->user_id;
                    $penalty->created_at = date('Y-m-d H:i:s');
                    if ($penalty->save()) {
                        $data['loanaccount_id'] = $accountID;
                        $data['comment'] = $reason;
                        $data['activity'] = "Freezing Penalty Accrual";
                        $data['commented_by'] = Yii::app()->user->user_id;
                        LoanApplication::recordLoanComment($data);
                        if ($period === 575) {
                            $periodRecordable = 'Indefinite';
                            $expiryMessage = "indefinitely";
                        } else {
                            $currentDate = date('Y-m-d');
                            $periodRecordable = $period . ' days';
                            $expiryDate = date("d/m/Y", strtotime($currentDate . "+ $period days"));
                            $expiryMessage = "for $periodRecordable, Expiry $expiryDate";
                        }
                        $accountNumber = $loanaccount->account_number;
                        $fullName = $loanaccount->BorrowerFullName;
                        Logger::logUserActivity("Froze Accruing Penalty, Account: $accountNumber, period: $periodRecordable, Client: $fullName", 'urgent');
                        $amountBalance = CommonFunctions::asMoney(LoanManager::getActualLoanBalance($accountID));
                        $profile = Profiles::model()->findByPk($loanaccount->user_id);
                        $userPhone = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id, 'PHONE');
                        $userFirstName = $profile->firstName;
                        $numbers = array();
                        array_push($numbers, $userPhone);
                        $textMessage = "Dear " . $userFirstName . ", your Penalty Accrual Frozen" . $expiryMessage ."For queries please contact your Account Manager . Thank you!";
                        $alertType = '15';
                        SMS::broadcastSMS($numbers, $textMessage, $alertType, $profile->id);

                        /* Penlaty Freeze Successful  */
                        $freezeSuccessful = 1;
                    }else{
                        $freezeSuccessful = 0;
                    }
                }else{
                    $freezeSuccessful = 0;
                }
                break;

                /* Account Already Frozen */
            case 1:
                $freezeSuccessful = 2;
                break;

                /* Account Not Found */
            case 2:
                $freezeSuccessful = 3;
                break;

        }
        return $freezeSuccessful;

    }

    /*********************************************

    Loan Account Balance

     *************************************************/
    public static function getActualLoanBalance($accountID){
        return LoanTransactionsFunctions::getCurrentLoanBalance($accountID);
    }

    public static function getAmountWrittenOff($accountID){
        $totalQuery = "SELECT SUM(amount) AS amount FROM write_offs WHERE loanaccount_id=$accountID
    AND loanaccount_id NOT IN(SELECT loanaccount_id FROM restructuredloans)";
        $total      = WriteOffs::model()->findBySql($totalQuery);
        return !empty($total) ? $total->amount : 0;
    }

    /*******************************************************

    Loan Account Repayments

    0: Normal, 1: Voided, 2: Written Off, 3: Frozen

     ***********************************************************/

    public static function getCurrentMonthLoanPayment($accountID){
        $totalAmount = 0;
        $startDate = date('Y-m-01');
        $endDate   = date('Y-m-t');
        $transactionQuery="SELECT COALESCE(SUM(loantransactions.amount),0) as amount FROM loantransactions,loanrepayments,loanaccounts
     WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
    AND loantransactions.loanaccount_id=$accountID AND DATE(loantransactions.transacted_at) BETWEEN '$startDate' AND '$endDate'
    AND loantransactions.is_void IN('0','3','4')";
        $payment = Loantransactions::model()->findBySql($transactionQuery);
        if(!empty($payment)){
            $totalAmount += $payment->amount;
        }else{
            $totalAmount = 0;
        }
        return $totalAmount;
    }


    public static function repayLoanAccount($accountID,$amountPaid,$voidType,$phoneNumber){
        $transactionID = LoanManager::recordPaymentTransaction($accountID,$amountPaid,$voidType);
        return $transactionID > 0 ? LoanManager::recordLoanRepayment($accountID,$transactionID,$amountPaid,$voidType,$phoneNumber) : 0;
    }

    public static function recordPaymentTransaction($accountID,$amountPaid,$voidType){
        //Loan Details
        $loanaccount = Loanaccounts::model()->findByPk($accountID);
        $profile     = Profiles::model()->findByPk($loanaccount->user_id);
        //Record Transaction
        $transaction = new Loantransactions;
        $transaction->loanaccount_id = $accountID;
        $transaction->date           = date('Y-m-d');
        $transaction->amount         = $amountPaid;
        $transaction->type           = '1';
        $transaction->is_void        = $voidType;
        $transaction->transacted_by  = $profile->managerId;
        $transaction->transacted_at  = date('Y-m-d H:i:s');
        return $transaction->save() ? $transaction->loantransaction_id :  0;
    }

    public static function recordLoanRepayment($accountID,$transactionID,$amountPaid,$voidType,$phoneNumber){
        //Pay Accrued Interest
        $accruedInterest = LoanManager::getUnpaidLoanInterestBalance($accountID);
        switch($accruedInterest){
            case 0:
                $interestPayable = 0;
                break;

            default:
                $interestPayable = $accruedInterest >= $amountPaid ? $amountPaid : $accruedInterest;
                break;
        }
        //Record Interest Paid
        if($interestPayable > 0){
            LoanManager::recordAccruedInterest($accountID,$interestPayable,'credit','1');
        }

        $amountLessInterest = $amountPaid - $interestPayable;
        //Pay Accrued Penalty
        $accruedPenalty     = LoanManager::getUnpaidAccruedPenalty($accountID);
        if($accruedPenalty >= $amountLessInterest){
            $penaltyDifference= $accruedPenalty - $amountLessInterest;
            if($penaltyDifference > 0){
                if(LoanManager::voidCurrentPenaltyRecords($accountID) == 1){
                    LoanManager::createPenaltyRecord($accountID,$penaltyDifference);
                }
            }
            $penaltyPayable = $amountLessInterest;
        }else{
            LoanManager::voidCurrentPenaltyRecords($accountID);
            $penaltyPayable = $accruedPenalty;
        }
        $amountFurtherLessPenalty = $amountLessInterest - $penaltyPayable;
        //Pay Loan Principal
        $principalPayable = $amountFurtherLessPenalty > 0 ? $amountFurtherLessPenalty :  0;

        return LoanManager::createRepaymentRecord($accountID,$transactionID,$penaltyPayable,
            $interestPayable,$principalPayable,$voidType,$amountPaid,$phoneNumber);
    }

    public static function calculateInterestPayable($accountID,$amountPaid){
        $accruedInterest = LoanManager::getUnpaidLoanInterestBalance($accountID);
        switch($accruedInterest){
            case 0:
                $interestPayable = 0;
                break;

            default:
                $interestPayable = $accruedInterest >= $amountPaid ? $amountPaid : $accruedInterest;
                break;
        }
        return $interestPayable;
    }

    public static function createRepaymentRecord($accountID,$transactionID,$penaltyPayable,$interestPayable,$principalPayable,$voidType,$amountPaid,$phoneNumber){
        $loanaccount = Loanaccounts::model()->findByPk($accountID);
        $profile     = Profiles::model()->findByPk($loanaccount->user_id);
        $repayment   = new Loanrepayments;
        $repayment->loanaccount_id     = $accountID;
        $repayment->branch_id          = $profile->branchId;
        $repayment->loantransaction_id = $transactionID;
        $repayment->date               = date('Y-m-d');
        $repayment->interest_paid      = $interestPayable;
        $repayment->penalty_paid       = $penaltyPayable;
        $repayment->principal_paid     = $principalPayable;
        $repayment->is_void            = $voidType;
        $repayment->repaid_by          = $profile->managerId;
        $repayment->rm                 = $profile->managerId;
        $repayment->user_id            = $profile->id;
        $repayment->phone_transacted   = $phoneNumber;
        $repayment->repaid_at          = date('Y-m-d H:i:s');
        if($repayment->save()){
            $amountFormatted = CommonFunctions::asMoney($amountPaid);
            LoanManager::sendNotification($accountID,$amountPaid,$voidType);
            $accountNumber   = $loanaccount->account_number;
            $fullName        = $loanaccount->BorrowerFullName;
            Logger::logUserActivity("Manually submitted repayment worth KES $amountFormatted for A/C #: $accountNumber of client: $fullName",'urgent');
            $successfulPayment = 1;
        }else{
            $successfulPayment = 0;
        }
        return $successfulPayment;
    }

    /*************************

    RESTRUCTURING / TOP UPS

    TYPE 3: RESTRUCTURING
    TYPE 4: TOP UP

     *****************************/
    public static function freezeLoanRepayments($accountID,$type){
        LoanManager::freezeTransactions($accountID,$type);
        LoanManager::freezeRepayments($accountID,$type);
    }

    public static function freezeTransactions($accountID,$type){
        $freezeQuery="SELECT * FROM loantransactions WHERE loanaccount_id=$accountID AND is_void='0'";
        $transactions=Loantransactions::model()->findAllBySql($freezeQuery);
        if(!empty($transactions)){
            foreach($transactions as $transaction){
                $transaction->is_void=$type;
                $transaction->save();
            }
            $frozen = 1;
        }else{
            $frozen = 0;
        }
        return $frozen;
    }

    public static function freezeRepayments($accountID,$type){
        $freezeQuery="SELECT * FROM loanrepayments WHERE loanaccount_id=$accountID AND is_void='0'";
        $repayments=Loanrepayments::model()->findAllBySql($freezeQuery);
        if(!empty($repayments)){
            foreach($repayments as $repayment){
                $repayment->is_void=$type;
                $repayment->save();
            }
            $frozen = 1;
        }else{
            $frozen = 0;
        }
        return $frozen;
    }

    public static function recordWriteOff($accountID,$amountPaid,$writeOffType,$writeOffReason){
        $loanaccount=Loanaccounts::model()->findByPk($accountID);
        $branchID=$loanaccount->branch_id;
        $userID=$loanaccount->user_id;
        $rmID=$loanaccount->rm;
        $repayment=new WriteOffs;
        $repayment->branch_id=$branchID;
        $repayment->user_id=$userID;
        $repayment->rm=$rmID;
        $repayment->amount=$amountPaid;
        $repayment->type=$writeOffType;
        $repayment->reason=$writeOffReason;
        $repayment->created_by=Yii::app()->user->user_id;
        $repayment->created_at = date('Y-m-d H:i:s');
        if($repayment->save()){
            $loanaccount->account_status = 'C';
            $loanaccount->update();
            $voidType='2';
            LoanManager::sendNotification($accountID,$amountPaid,$voidType);
            $data['loanaccount_id']=$accountID;
            $data['comment']=$writeOffReason;
            $data['activity']="$writeOffType Write Off";
            $data['commented_by']=Yii::app()->user->user_id;
            LoanApplication::recordLoanComment($data);
        }
    }

    public static function sendNotification($accountID,$amountPaid,$voidType){
        $account       = Loanaccounts::model()->findByPk($accountID);
        $organization  = Organization::model()->findByPk(1);
        $orgPhone      = $organization->phone;
        $amountBalance = CommonFunctions::asMoney(LoanManager::getActualLoanBalance($accountID));
        $profile=Profiles::model()->findByPk($account->user_id);
        $userPhone= ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
        $userFirstName=$profile->firstName;
        $userIDNumber=$profile->idNumber;
        $numbers=array();
        array_push($numbers,$userPhone);
        $amountFormatted=CommonFunctions::asMoney($amountPaid);
        $balanceMessage = "Dear ".$userFirstName.", your loan account is now updated. New balance is Kshs ".$amountBalance."/-. For queries please contact your Account Manager or call ".$orgPhone.". Thank you!";
        $balanceType='15';
        if($voidType == '2'){
            $textMessage="Dear ".$userFirstName.", an amount of ".$amountFormatted."/- has been written off from your loan. New balance is Kshs ".$amountBalance."/-Thank you!";
            $alertType='10';
            SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
        }else{
            $textMessage="Thank you ".$userFirstName.", your loan payment of KES ".$amountFormatted." to account ".$userIDNumber." is received. Your account is now updated. Please contact your manager for more info.";
            $alertType='12';
            SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
            SMS::broadcastSMS($numbers,$balanceMessage,$balanceType,$profile->id);
        }
    }
    /************************

    FREEZING LOAN INTERESTS

     ********************************/
    public static function freezeInterestAccrual($accountID,$period,$reason){
        $loanaccount=Loanaccounts::model()->findByPk($accountID);
        switch(LoanManager::checkIfAccountAlreadyFrozen($accountID)){
            case 0:
                if(LoanManager::freezeLoanAccount($accountID) === 1){
                    $interest= new InterestFreezes;
                    $interest->loanaccount_id=$accountID;
                    $interest->branch_id=$loanaccount->branch_id;
                    $interest->user_id=$loanaccount->user_id;
                    $interest->rm=$loanaccount->rm;
                    $interest->date_frozen=date('Y-m-d H:i:s');
                    $interest->period_frozen=$period;
                    $interest->freezing_reason=$reason;
                    $interest->frozen_by=Yii::app()->user->user_id;
                    $interest->created_by=Yii::app()->user->user_id;
                    $interest->created_at=date('Y-m-d H:i:s');
                    if($interest->save()){
                        $data['loanaccount_id']=$accountID;
                        $data['comment']=$reason;
                        $data['activity']="Freezing Interest Accrual";
                        $data['commented_by']=Yii::app()->user->user_id;
                        LoanApplication::recordLoanComment($data);
                        if($period === 575){
                            $periodRecordable='Indefinite';
                            $expiryMessage="indefinitely";
                        }else{
                            $currentDate=date('Y-m-d');
                            $periodRecordable=$period .' days';
                            $expiryDate=date("d/m/Y",strtotime($currentDate. "+ $period days"));
                            $expiryMessage="for $periodRecordable, Expiry $expiryDate";
                        }
                        $accountNumber=$loanaccount->account_number;
                        $fullName=$loanaccount->BorrowerFullName;
                        Logger::logUserActivity("Froze Accruing Interest, Account: $accountNumber, period: $periodRecordable, Client: $fullName",'urgent');
                        $amountBalance=CommonFunctions::asMoney(LoanManager::getActualLoanBalance($accountID));
                        $profile=Profiles::model()->findByPk($loanaccount->user_id);
                        $userPhone= ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
                        $userFirstName=$profile->firstName;
                        $numbers=array();
                        array_push($numbers,$userPhone);
                        $freezeMessage = "Dear ".$userFirstName.", your loan interest is now frozen ".$expiryMessage.". Loan bal=".$amountBalance."/-.Please pay through paybill 754298, Account ".$accountNumber.".\nThank you!";
                        $freezeType='27';
                        SMS::broadcastSMS($numbers,$freezeMessage,$freezeType,$profile->id);
                        /*Freezing Successful*/
                        $frozenStatus=1;
                    }else{
                        /*Failed Freezing*/
                        $frozenStatus=0;
                    }
                }else{
                    /*Failed Freezing*/
                    $frozenStatus=0;
                }
                break;
            /*Already Frozen*/
            case 1:
                $frozenStatus=2;
                break;
            /*No Account*/
            case 2:
                $frozenStatus=3;
                break;
        }
        return $frozenStatus;
    }


    public static function checkIfAccountAlreadyFrozen($accountID){
        $loanaccount=Loanaccounts::model()->findByPk($accountID);
        if(!empty($loanaccount)){
            switch($loanaccount->is_frozen){
                case '0':
                    $frozen=0;
                    break;

                case '1':
                    $frozen=1;
                    break;
            }
        }else{
            $frozen=2;
        }
        return $frozen;
    }

    public static function freezeLoanAccount($accountID){
        $loanaccount=Loanaccounts::model()->findByPk($accountID);
        if(!empty($loanaccount)){
            $loanaccount->is_frozen='1';
            if($loanaccount->save()){
                $frozen=1;
            }else{
                $frozen=0;
            }
        }else{
            $frozen=0;
        }
        return $frozen;
    }

    public static function unfreezeInterestAccrual($accountID,$reason){
        switch(LoanManager::unfreezeLoanAccount($accountID,$reason)){
            case 0:
                $unfrozen=0;
                break;

            case 1:
                $loanaccount=Loanaccounts::model()->findByPk($accountID);
                if(!empty($loanaccount)){
                    $loanaccount->is_frozen='0';
                    if($loanaccount->save()){
                        $accountNumber=$loanaccount->account_number;
                        $fullName=$loanaccount->BorrowerFullName;
                        Logger::logUserActivity("Unfroze Accruing Interest,Account:$accountNumber,Client:$fullName",'urgent');
                        $unfrozen=1;
                    }else{
                        $unfrozen=0;
                    }
                }else{
                    $unfrozen=0;
                }
                break;
        }
        return $unfrozen;
    }

    public static function unfreezeLoanAccount($accountID,$reason){
        $accruedQuery="SELECT * FROM interest_freezes WHERE loanaccount_id=$accountID ORDER BY id DESC LIMIT 1";
        $accrued=InterestFreezes::model()->findBySql($accruedQuery);
        if(!empty($accrued)){
            $accrued->unfrozen_by=Yii::app()->user->user_id;
            $accrued->date_unfrozen=date('Y-m-d H:i:s');
            $accrued->unfrozen_type='2';
            $accrued->unfrozen_reason=$reason;
            if($accrued->save()){
                $data['loanaccount_id']=$accountID;
                $data['comment']=$reason;
                $data['activity']="Unfreezing Interest Accrual";
                $data['commented_by']=Yii::app()->user->user_id;
                LoanApplication::recordLoanComment($data);
                $unfrozen=1;
            }else{
                $unfrozen=0;
            }
        }else{
            $unfrozen=0;
        }
        return $unfrozen;
    }
    /***********
     * Loan Penalties
     */



    /*******************

    LOAN RESTRUCTURING

     **************************/
    public static function restructureLoanAccount($model,$id,$interestRate,$amountApproved,$repaymentPeriods){
        $loan        = Loanaccounts::model()->findByPk($id);
        $loanStatus  = $loan->loan_status;
        $arrayStatus = array('2','4','5','6','7');
        switch(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus)){
            case 0:
                $status=0;
                break;

            case 1:
                $arrayCheckStatus = array('0','1');
                if(CommonFunctions::searchElementInArray($loanStatus,$arrayCheckStatus) == 0){
                    LoanManager::freezeLoanRepayments($id,'3');
                    LoanManager::updateBeforeRestructure($loan,$model);
                    $disbursedSQL="SELECT * FROM disbursed_loans WHERE loanaccount_id=$id";
                    $disbursedRecord=DisbursedLoans::model()->findBySql($disbursedSQL);
                    if(!empty($disbursedRecord)){
                        $record=DisbursedLoans::model()->findByPk($disbursedRecord->id);
                        $record->amount_disbursed=$loan->amount_approved;
                        $record->save();
                        LoanManager::commitAccountRestructure($loan,$interestRate,$amountApproved,$repaymentPeriods);
                        LoanManager::sendRestructureMessage($loan);
                        Yii::app()->db->createCommand("DELETE FROM write_offs WHERE loanaccount_id=$id")->execute();
                        $status=1;
                    }else{
                        $status=2;
                    }
                }else{
                    $status=3;
                }
                break;
        }
        return $status;
    }

    public static function updateBeforeRestructure($loan,$model){
        $loan->loan_status='5';
        $loan->date_restructured=date('Y-m-d');
        $loan->amount_approved=$model->amount_applied;
        $loan->save();
    }

    public static function commitAccountRestructure($loan,$interestRate,$amountApproved,$repaymentPeriods){
        $restructured=new Restructuredloans;
        $restructured->loanaccount_id=$loan->loanaccount_id;
        $restructured->previous_amount=$amountApproved;
        $restructured->restructured_amount=$loan->amount_approved;
        $restructured->previous_rate=$interestRate;
        $restructured->restructured_rate=$loan->interest_rate;
        $restructured->previous_period=$repaymentPeriods;
        $restructured->restructured_period=$loan->repayment_period;
        $restructured->restructured_by=Yii::app()->user->user_id;
        $restructured->restructured_at=date('Y-m-d H:i:s');
        $restructured->save();
    }

    public static function sendRestructureMessage($loan){
        $profile=Profiles::model()->findByPk($loan->user_id);
        $userPhone= ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
        $newAmount=CommonFunctions::asMoney($loan->amount_approved);
        $newInterest=number_format($loan->interest_rate,1);
        $loanBalance= CommonFunctions::asMoney(LoanManager::getActualLoanBalance($loan->loanaccount_id));
        $principalBalance=CommonFunctions::asMoney(LoanManager::getPrincipalBalance($loan->loanaccount_id));
        $message = "Your account is now restructured.\nNew Loan Balance= $loanBalance /-\nContact your account Manager for more info.\nThank you!";
        $textMessage = "Dear ".ucfirst($profile->firstName).", ".$message;
        $numbers=array();
        array_push($numbers,$userPhone);
        $alertType='5';
        $status=SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
    }

    /*****************************************

    Recovery(3),CRB Listing(0)

     ************************************************/
    public static function initiateAccountListing($performanceLevel,$model){
        switch($performanceLevel){
            case '0':
                $status=LoanManager::commitCRBListing($model);
                break;

            case '3':
                $status=LoanManager::commitRecoveryListing($model);
                break;
        }
        return $status;
    }

    public static function commitRecoveryListing($model){
        $accountStatus=$model->loan_status;
        $statusArray=array('0','1','3','4');
        switch(LoanManager::restrictDuplicateRecovery($model->loanaccount_id)){
            case 0:
                if(CommonFunctions::searchElementInArray($accountStatus,$statusArray) == 0){
                    $profile=Profiles::model()->findByPk($model->user_id);
                    $balance = LoanManager::getActualLoanBalance($model->loanaccount_id);
                    $recover=new Recovery;
                    $recover->loanaccount_id=$model->loanaccount_id;
                    $recover->user_id=$userID;
                    $recover->branch_id=$profile->branchId;
                    $recover->amount=$balance;
                    $recover->save();
                    $status=1;
                }else{
                    $status=0;
                }
                break;

            case 1:
                $status=0;
                break;
        }
        return $status;
    }

    public static function restrictDuplicateRecovery($accountID){
        $recoverQuery="SELECT * FROM recovery WHERE loanaccount_id=$accountID AND status IN('0','2')";
        $recover=Recovery::model()->findAllBySql($recoverQuery);
        if(!empty($recover)){
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }

    public static function commitCRBListing($model){
        $accountStatus=$model->loan_status;
        $statusArray=array('0','1','3','4');
        switch(LoanManager::restrictDuplicateCRB($model->loanaccount_id)){
            case 0:
                if(CommonFunctions::searchElementInArray($accountStatus,$statusArray) == 0){
                    $profile=Profiles::model()->findByPk($model->user_id);
                    $balance = LoanManager::getActualLoanBalance($model->loanaccount_id);
                    $crb=new CrbListings;
                    $crb->loanaccount_id=$model->loanaccount_id;
                    $crb->user_id=Yii::app()->user->user_id;
                    $crb->branch_id=$profile->branchId;
                    $crb->amount=$balance;
                    $crb->save();
                    $status=1;
                }else{
                    $status=0;
                }
                break;

            case 1:
                $status=0;
                break;
        }
        return $status;
    }

    public static function restrictDuplicateCRB($accountID){
        $crbQuery="SELECT * FROM crb_listings WHERE loanaccount_id=$accountID";
        $crb=CrbListings::model()->findAllBySql($crbQuery);
        if(!empty($crb)){
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }
    /****************************

    ###### LOAN STATEMENT #####

     *******************************/
    public static function getInterestAccrued($accountID,$txnDate){
        $interestQuery="SELECT * FROM loaninterests WHERE loanaccount_id=$accountID AND DATE('accrued_at')='$txnDate'";
        $interest=Loaninterests::model()->findAllBySql($interestQuery);
        return $interest;
    }

    public static function getPaymentRemitted($accountID,$txnDate){
        $paymentQuery="SELECT * FROM loantransactions WHERE is_void IN('0','3','4') AND loanaccount_id=$accountID AND DATE(transacted_at)='$txnDate'";
        $payment=Loantransactions::model()->findAllBySql($paymentQuery);
        return $payment;
    }

    public static function getWrittenOffAmount($accountID,$txnDate){
        $writtenOffQuery="SELECT * FROM loantransactions WHERE is_void IN('2') AND loanaccount_id=$accountID
     AND DATE(transacted_at)='$txnDate'";
        $payment=Loantransactions::model()->findAllBySql($writtenOffQuery);
        return $payment;
    }

    public static function getPenaltyAmountGotten($accountID,$txnDate){
        $voidQuery="SELECT * FROM penaltyaccrued WHERE loanaccount_id=$accountID
     AND DATE(created_at)='$txnDate' ";
        $penalties=Penaltyaccrued::model()->findAllBySql($voidQuery);
        return $penalties;
    }

    public static function getAccountStatementTransactions($accountID,$startDate,$endDate){
        $accountStatementQuery="SELECT disbursed_at AS transactionDate, CONCAT(UCASE(LEFT(type, 1)), SUBSTRING(type, 2),' ',' Disbursed') AS description, amount_disbursed AS moneyOut, 0 AS moneyIn FROM disbursed_loans WHERE type IN('top_up','principal') AND loanaccount_id=$accountID AND (DATE(disbursed_at) BETWEEN '$startDate' AND '$endDate') UNION ALL SELECT transacted_at AS transactionDate,'Payment' AS description,0 AS moneyOut, amount AS moneyIn FROM loantransactions WHERE is_void IN('0','3','4') AND loanaccount_id=$accountID AND (DATE(transacted_at) BETWEEN '$startDate' AND '$endDate') UNION ALL SELECT transacted_at AS transactionDate,'Waiver' AS description,0 AS moneyOut, amount AS moneyIn FROM loantransactions WHERE is_void IN('2') AND loanaccount_id=$accountID AND (DATE(transacted_at) BETWEEN '$startDate' AND '$endDate') UNION ALL SELECT accrued_at AS transactionDate,'Interest Charged' AS description,interest_accrued AS moneyOut, 0 AS moneyIn FROM loaninterests WHERE loanaccount_id=$accountID AND transaction_type='debit' AND (DATE(accrued_at) BETWEEN '$startDate' AND '$endDate') UNION ALL SELECT created_at AS transactionDate,'Penalty' AS description,penalty_amount AS moneyOut, 0 AS moneyIn FROM penaltyaccrued WHERE loanaccount_id=$accountID AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate') ORDER BY transactionDate ASC";
        $transactions=Yii::app()->db->createCommand($accountStatementQuery)->queryAll();
        return $transactions;
    }

    /******************************************

    Write Off Loan Principal Balance

     ********************************************/

    public static function deleteAccountPrincipal($accountID,$amountToDelete,$deletionReason,$deletionType){
        switch(LoanManager::isAccountPrincipalDeletable($accountID)){
            case 0:
                $deleteStatus = 0;
                break;

            case 1:
                $loanaccount   = Loanaccounts::model()->findByPk($accountID);
                $profile       = Profiles::model()->findByPk($loanaccount->user_id);
                $transactionID = LoanManager::recordPaymentTransaction($accountID,$amountToDelete,'4');
                if($transactionID > 0){
                    $formatBalance = CommonFunctions::asMoney($amountToDelete);
                    $accountNumber = $loanaccount->account_number;
                    $fullName      = $loanaccount->BorrowerFullName;
                    $phoneNumber   = '0'.substr($profile->ProfilePhoneNumber,-9);
                    if(LoanManager::createRepaymentRecord($accountID,$transactionID,0,0,$amountToDelete,'4',$amountToDelete,$phoneNumber) == 1){
                        $data['loanaccount_id'] = $accountID;
                        $data['comment']        = $deletionReason;
                        $data['activity']       = $deletionType;
                        $data['commented_by']   = Yii::app()->user->user_id;
                        LoanApplication::recordLoanComment($data);
                        Logger::logUserActivity("Deleted loan principal of KSH. $formatBalance, Account: $accountNumber,Client: $fullName",'urgent');
                    }
                    $deleteStatus = 1;
                }
                break;
        }
        return $deleteStatus;
    }

    public static function isAccountPrincipalDeletable($accountID){
        if(Navigation::checkIfAuthorized(200) == 1){
            $loanaccount=Loanaccounts::model()->findByPk($accountID);
            $element=$loanaccount->loan_status;
            $array=array('0','1','3');
            switch(CommonFunctions::searchElementInArray($element,$array)){
                case 0:
                    $accountPrincipalBalance=LoanManager::getPrincipalBalance($accountID);
                    if($accountPrincipalBalance > 0){
                        $deletable=1;
                    }else{
                        $deletable=0;
                    }
                    break;

                case 1:
                    $deletable=0;
                    break;
            }
        }else{
            $deletable=0;
        }
        return $deletable;
    }

    /************************************************

    PAYMENTS - MPESA APIS

     *******************************************************/
    public static function getMostRecentAuthToken(){
        $authQuery="SELECT * FROM apitokens WHERE status=1 ORDER BY id DESC LIMIT 1";
        return Apitokens::model()->findBySql($authQuery);
    }


    public static function B2CTransaction($commandID,$amountPaid,$phoneNumber,$remarks){
        $organization = Organization::model()->findByPk(1);
        switch($organization->enable_mpesa_b2c){
            case 'ENABLED':
                $apiToken = LoanManager::getMostRecentAuthToken();
                if(!empty($apiToken)){
                    $authToken=$apiToken->auth_token;
                    switch($authToken){
                        case 1250:
                            $paymentStatus=$authToken;
                            break;

                        default:
                            $url = Yii::app()->params['X-BUSINESSCONSUMER-URL'];
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $url);
                            curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-Type:application/json","Authorization:Bearer $authToken"));
                            $curl_post_data = array(
                                'InitiatorName'  => Yii::app()->params['X-BUSINESSCONSUMER-INITIATOR-NAME'],
                                'SecurityCredential' => Yii::app()->params['X-CONSUMERSECURITY-KEY'],
                                'CommandID' => $commandID,
                                'Amount' => $amountPaid,
                                'PartyA' => Yii::app()->params['X-BUSINESSCONSUMER-SHORTCODE'],
                                'PartyB' => self::reconstructPhoneNumber($phoneNumber),
                                'Remarks' => $remarks,
                                'QueueTimeOutURL' => Yii::app()->params['X-QUEUETIMEOUT-URL'],
                                'ResultURL' => Yii::app()->params['X-CONSUMERAPIRESULTS-URL'],
                                'Occasion' => ''
                            );
                            echo "<pre>"; print_r($curl_post_data); echo "</pre>";
                            $data_string = json_encode($curl_post_data);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                            $curl_response = curl_exec($curl);
                            $transactionResponse=json_decode($curl_response,true);
                            if(!empty($curl_response)){
                                if(isset($transactionResponse['ResponseCode'])){
                                    switch($transactionResponse['ResponseCode']){
                                        case '0':
                                            $paymentStatus=1;
                                            $amountFormatted=CommonFunctions::asMoney($amountPaid);
                                            $paymentActivity="Processed <strong>$commandID B2C M-PESA </strong>transaction of $amountFormatted, for phone number: $phoneNumber with remark: <strong>$remarks</strong>";
                                            $activitySeverity='urgent';
                                            Logger::logUserActivity($paymentActivity,$activitySeverity);
                                            break;

                                        default:
                                            $paymentStatus=$transactionResponse['ResponseDescription'];
                                            break;
                                    }
                                }else{
                                    $paymentStatus=4;
                                }

                            }else{
                                $paymentStatus=0;
                            }
                            break;
                    }
                }else{
                    $paymentStatus=0;
                }
                break;

            case 'DISABLED':
                $paymentStatus=2020;
                break;
        }
        return $paymentStatus;
    }


    public static  function reconstructPhoneNumber($number) {
        // Ensure the number is a string
        $number = strval($number);

        // Remove leading "+" or "0"
        if (strpos($number, '+') === 0) {
            $number = substr($number, 1);
        } elseif (strpos($number, '0') === 0) {
            $number = substr($number, 1);
        }

        // Remove the "0" between "254" and "7" if present
        if (strpos($number, '25407') === 0) {
            $number = '2547' . substr($number, 5);
        }

        // Return the reconstructed number
        return $number;
    }

    public static function convertMsisdnToHash($msisdn) {
        $msisdn = 254 . substr($msisdn, -9);
        return hash('sha256', $msisdn);
    }

    public static function generateSTKPushAccessToken(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Yii::app()->params['X-APIAUTHTOKEN-URL']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.Yii::app()->params['X-CUSTOMER-STKPUSH-ENCODED-SECRET-KEY']));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        $curl_response     = curl_exec($curl);
        $stepresponsevalue = @json_decode($curl_response,true);
        $accessToken       = $stepresponsevalue{'access_token'};
        curl_close($curl);
        return $accessToken;
    }

    public static function sendSTKPush($profileId,$transactionType,$amountPaid,$phoneNumber,$accountNumber){
        $organization = Organization::model()->findByPk(1);
        switch($organization->enable_mpesa_b2c){
            case 'ENABLED':
                $accessToken = LoanManager::generateSTKPushAccessToken();
                if(!empty($accessToken)){
                    $accountReference     = $transactionType === 'LOAN_PAYMENT' ? 'Loan Payment' : 'Deposit';
                    $formattedPhoneNumber = '254'.substr($phoneNumber,-9);
                    $businessShortCode    = Yii::app()->params['X-CUSTOMER-STKPUSH-SHORTCODE'];
                    $passkey              = Yii::app()->params['X-CUSTOMER-STKPUSH-PASSKEY'];
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, Yii::app()->params['X-CUSTOMER-STKPUSH-URL']);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$accessToken));
                    $timestamp = strval(date_format(date_create(), 'YmdHis'));
                    $curl_post_data = array(
                        'BusinessShortCode' => $businessShortCode,
                        'Password'          => base64_encode($businessShortCode.$passkey.$timestamp),
                        'Timestamp'         => $timestamp,
                        'TransactionType'   => 'CustomerPayBillOnline',
                        'Amount'            => $amountPaid,
                        'PartyA'            => $formattedPhoneNumber,
                        'PartyB'            => $businessShortCode,
                        'PhoneNumber'       => $formattedPhoneNumber,
                        'CallBackURL'       => Yii::app()->params['X-CUSTOMER-STKPUSH-CALLBACK'],
                        'AccountReference'  => $accountNumber,
                        'TransactionDesc'   => $accountReference
                    );
                    $data_string = json_encode($curl_post_data);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_VERBOSE, 0);
                    $curl_response = curl_exec($curl);
                    $stkResponse   = json_decode($curl_response,true);
                    if(!empty($stkResponse)){
                        if(isset($stkResponse['errorCode']) && $stkResponse['errorCode'] == '500.001.1001'){
                            return 1009;
                        }
                        $profile         = Profiles::model()->findByPk($profileId);
                        $profileFullName = $profile->ProfileFullName;
                        $activity        = "Sent a payment prompt <strong>(STK Push)</strong> to $profileFullName: ".$formattedPhoneNumber." to make <strong>".
                            $transactionType." </strong> of <strong>KES ".number_format($amountPaid,2)." </strong> for account Number : <strong>".$accountNumber."</strong>";
                        Logger::logUserActivity($activity,'urgent');
                        return LoanManager::persistSTKPushRecord($profileId,$transactionType,$accountNumber,$phoneNumber,$amountPaid,$stkResponse);
                    }else{
                        return 1003;
                    }
                }else{
                    return 1005;
                }
                break;

            case 'DISABLED':
                return 1007;
                break;
        }
    }

    public static function persistSTKPushRecord($profileId,$transactionType,$accountNumber,$phoneNumber,$amountRequested,$stkResponse){
        $profile = Profiles::model()->findByPk($profileId);
        $stkPush = new StkPush;
        $stkPush->profileId       = $profile->id;
        $stkPush->branchId        = $profile->branchId;
        $stkPush->managerId       = $profile->managerId;
        $stkPush->transactionType = $transactionType;
        $stkPush->accountNumber   = $accountNumber;
        $stkPush->phoneNumber     = $phoneNumber;
        $stkPush->amountRequested = $amountRequested;
        $stkPush->initiatedBy       = Yii::app()->user->user_id;
        $stkPush->createdAt         = date('Y-m-d H:i:s');
        $stkPush->updatedAt         = date('Y-m-d H:i:s');
        $stkPush->merchantRequestId = $stkResponse['MerchantRequestID'];
        $stkPush->checkoutRequestId = $stkResponse['CheckoutRequestID'];
        $stkPush->responseCode      = $stkResponse['ResponseCode'];
        $stkPush->customerMessage   = $stkResponse['CustomerMessage'];
        $stkPush->responseDescription = $stkResponse['ResponseDescription'];
        return $stkPush->save() ? 1000 : 1001;
    }

    public static function updateCallBacks(){
        $checkoutsQuery  = "SELECT * FROM stkPush WHERE resultCode IS NULL";
        $checkouts = StkPush::model()->findAllBySql($checkoutsQuery);
        if(!empty($checkouts)){
            foreach($checkouts AS $checkout){
                LoanManager::updateSTKCallback($checkout->checkoutRequestId);
            }
        }
    }

    public static function updateSTKCallback($checkoutRequestID){
        $accessToken = LoanManager::generateSTKPushAccessToken();
        if(!empty($accessToken)){
            $businessShortCode    = Yii::app()->params['X-CUSTOMER-STKPUSH-SHORTCODE'];
            $passkey              = Yii::app()->params['X-CUSTOMER-STKPUSH-PASSKEY'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, Yii::app()->params['X-CUSTOMER-STKPUSH-QUERY-URL']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$accessToken));
            $timestamp = strval(date_format(date_create(), 'YmdHis'));
            $curl_post_data = array(
                'BusinessShortCode' => $businessShortCode,
                'Password'          => base64_encode($businessShortCode.$passkey.$timestamp),
                'Timestamp'         => $timestamp,
                'CheckoutRequestID' => $checkoutRequestID
            );
            $data_string = json_encode($curl_post_data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_VERBOSE, 0);
            $curl_response = curl_exec($curl);
            $stkResponse   = json_decode($curl_response,true);
            if(!empty($stkResponse)){
                $updatedAt = date('Y-m-d H:i:s');
                $resultCode = $stkResponse['ResultCode'];
                $resultDesc = $stkResponse['ResultDesc'];
                $updateQuery = "UPDATE stkPush SET resultCode='$resultCode',resultDesc='$resultDesc',updatedAt='$updatedAt' WHERE checkoutRequestId='$checkoutRequestID'";
                Yii::app()->db->createCommand($updateQuery)->execute();
            }
        }
    }

    public static function getTransactionStatus($transactionID){
        $apiToken=LoanManager::getMostRecentAuthToken();
        if(!empty($apiToken)){
            $authToken=$apiToken->auth_token;
            $url = Yii::app()->params['X-TRANSACTIONSTATUS-URL'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type:application/json","Authorization:Bearer $authToken"));
            $curl_post_data = array(
                'Initiator' => Yii::app()->params['X-BUSINESSCONSUMER-INITIATOR-NAME'],
                'SecurityCredential' => Yii::app()->params['X-CONSUMERSECURITY-KEY'],
                'CommandID' => 'TransactionStatusQuery',
                'TransactionID' => $transactionID,
                'PartyA' =>'254729983817',
                'IdentifierType' => '1',
                'QueueTimeOutURL' => Yii::app()->params['X-QUEUETIMEOUT-URL'],
                'ResultURL' => Yii::app()->params['X-CONSUMERAPIRESULTS-URL'],
                'Remarks' => 'Query transaction status',
                'Occasion' => ' '
            );

            $data_string = json_encode($curl_post_data);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

            $curl_response = curl_exec($curl);
            print_r($curl_response);

            echo $curl_response;
        }else{
            echo "Error";
        }
    }
    /**********************

    ACCOUNT BALANCES

     ***************************/
    public static function getB2CAccountBalance(){
        $apiToken=LoanManager::getMostRecentAuthToken();
        if(!empty($apiToken)){
            $authToken=$apiToken->auth_token;
            $url = Yii::app()->params['X-ACCOUNTBALANCE-URL'];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-Type:application/json","Authorization:Bearer $authToken"));
            $curl_post_data = array(
                'Initiator' => Yii::app()->params['X-BUSINESSCONSUMER-INITIATOR-NAME'],
                'SecurityCredential' => Yii::app()->params['X-CONSUMERSECURITY-KEY'],
                'CommandID' => 'AccountBalance',
                'PartyA' => Yii::app()->params['X-BUSINESSCONSUMER-SHORTCODE'],
                'IdentifierType' => '4',
                'Remarks' => 'Querying Account Balance ',
                'QueueTimeOutURL' => Yii::app()->params['X-QUEUETIMEOUT-URL'],
                'ResultURL' => Yii::app()->params['X-CONSUMERAPIRESULTS-URL']
            );
            $data_string = json_encode($curl_post_data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            $curl_response = curl_exec($curl);
            $balanceResponse=json_decode($curl_response,true);
            if(!empty($balanceResponse)){
                $balResponseCode=(int)$balanceResponse['ResponseCode'];
                $balRequestID=$balanceResponse['ConversationID'];
                $balOrigConverID=$balanceResponse['OriginatorConversationID'];
                $balResponseDesc=$balanceResponse['ResponseDescription'];
                if($balResponseCode === 0){
                    $balanceCheckStatus=LoanManager::saveCheckBalanceRecord($balRequestID,$balOrigConverID,
                        $balResponseCode,$balResponseDesc);
                }else{
                    $balanceCheckStatus=1001;//Technical response error from M-PESA
                }
            }else{
                $balanceCheckStatus=1003;//No response from M-PESA
            }
        }else{
            $balanceCheckStatus=1003;
        }
        return $balanceCheckStatus;
    }

    public static function saveCheckBalanceRecord($requestID,$origConverID,$responseCode,$responseDesc){
        $balance = new Balancechecks;
        $balance->user_id = Yii::app()->user->user_id;
        $balance->conversationID = $requestID;
        $balance->originatorConversationID = $origConverID;
        $balance->responseCode= $responseCode;
        $balance->responseDesc= $responseDesc;
        $balance->created_at  = date('Y-m-d H:i:s');
        return $balance->save() ? 1000 : 1005;
    }

    public static function updateCheckBalanceRecord($requestID,$resultType,$resultCode,
                                                    $resultDesc,$transactionID,$workingAccount,$utilityAccount,$chargeAccount){
        $balanceQuery="SELECT * FROM balancechecks WHERE conversationID='$requestID' LIMIT 1";
        $balance=Balancechecks::model()->findBySql($balanceQuery);
        if(!empty($balance)){
            $balance->resultType=$resultType;
            $balance->resultCode=$resultCode;
            $balance->resultDesc=$resultDesc;
            $balance->transactionID=$transactionID;
            $balance->workingAccount=$workingAccount;
            $balance->utilityAccount=$utilityAccount;
            $balance->chargeAccount=$chargeAccount;
            $balance->updated_at=date('Y-m-d H:i:s');
            if($balance->save()){
                $balanceUpdateStatus=1;
            }else{
                $balanceUpdateStatus=0;
            }
        }else{
            $balanceUpdateStatus=0;
        }
        return $balanceUpdateStatus;
    }

    public static function getLatestC2BBalance(){
        $fetchQuery = "SELECT * FROM balancechecks ORDER BY id DESC LIMIT 1";
        return Balancechecks::model()->findBySql($fetchQuery);
    }


}