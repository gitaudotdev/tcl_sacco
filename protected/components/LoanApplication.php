<?php

class LoanApplication{

    public static function createNewApplication($data){
        $profile = Profiles::model()->findByPk($data['user']);
        switch($profile->profileStatus){
            case 'SUSPENDED':
                return 3;
                break;

            default:
                $defaultRate  = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_INTEREST_RATE');
                $interestRate = $defaultRate === 'NOT SET' ? Yii::app()->params['DEFAULTLOANSINTEREST'] : floatval($defaultRate);
                $defaultLimit = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
                $loanLimit    = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
                $bad_symbols  = array(",", ".");
                $amountValue  = str_replace($bad_symbols,"",$data['amount']);

                $receivableAmountValue  = str_replace($bad_symbols,"",$data['receivable_amount']);
                $insuranceAmountValue  = $data['insurance_fee'];
                $processingAmountValue  = $data['processing_fee'];
                $deductionsAmountValue  = $data['deduction_fee'];

                $insuranceValueRate  = $data['insurance_fee_value'];
                $processingValueRate  = $data['processing_fee_value'];

                $loanPeriod = $data['repayment_period'];
                $repaymentFrequency = $data['repayment_frequency'];

                if($amountValue <= $loanLimit){
                    $loanaccount  = new Loanaccounts;
                    $loanaccount->loanproduct_id       = 1;
                    $loanaccount->interest_rate        = $interestRate;
                    $loanaccount->user_id              = $profile->id;
                    $loanaccount->amount_applied       = $amountValue;

                    $loanaccount->amount_receivable   = $receivableAmountValue;
                    $loanaccount->insurance_fee       = $insuranceAmountValue;
                    $loanaccount->processing_fee      = $processingAmountValue;
                    $loanaccount->deduction_fee       = $deductionsAmountValue;

                    $loanaccount->insurance_fee_value   = $insuranceValueRate;
                    $loanaccount->processing_fee_value  = $processingValueRate;
                    $loanaccount->member_type = $profile->clientCategoryClass;


                    $loanaccount->repayment_cycle      = Yii::app()->params['DEFAULTREPAYMENTCYCLE'];
                    $loanaccount->repayment_period     = $loanPeriod != null ? $loanPeriod : Yii::app()->params['DEFAULTREPAYMENTPERIOD'];
                    if ($loanPeriod > 0) {
                        $newDate = date('Y-m-d', strtotime("+".$loanPeriod." days"));
                        $loanaccount->repayment_start_date = $newDate;
                    } else {
                        $loanaccount->repayment_start_date = Yii::app()->params['DEFAULTREPAYMENTSTARTDATE'];
                    }
                    $loanaccount->repayments_count = self::calculateRepaymentCycle($loanPeriod, $repaymentFrequency);
                    $loanaccount->freezing_period      = $loanPeriod;
                    $loanaccount->pay_mode      = $repaymentFrequency;
                    $loanaccount->rm                   = $profile->managerId;
                    $loanaccount->branch_id            = $profile->branchId;
                    $loanaccount->direct_to            = $data['direct_to'];
                    $loanaccount->special_comment      = $data['special_comment'];
                    $loanaccount->account_number       = $profile->idNumber;
                    $loanaccount->created_by           = Yii::app()->user->user_id;
                    $loanaccount->created_at           = date('Y-m-d H:i:s');
                    if($loanaccount->save()){
                        $data['loanaccount_id'] = $loanaccount->loanaccount_id;
                        $data['comment']        = $loanaccount->special_comment;
                        $data['activity']       = "Loan Application";
                        $data['commented_by']   = Yii::app()->user->user_id;
                        LoanApplication::recordLoanComment($data);
                        $accountNumber  = $loanaccount->account_number;
                        $profile        = Profiles::model()->findByPk($loanaccount->direct_to);
                        $emailAddress   = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'EMAIL');
                        $phoneNumber    = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
                        $FullName       = $profile->ProfileFullName;
                        $user_full_name = $profile->firstName;
                        $client     = Profiles::model()->findByPk($loanaccount->user_id);
                        $clientName = $client->ProfileFullName;
                        $clientLoanAmount = CommonFunctions::asMoney($loanaccount->amount_applied);
                        $initiator = Profiles::model()->findByPk($loanaccount->created_by);
                        $initiatorFullName = $initiator->firstName;
                        $initiatorBranchComment = $initiator->ProfileBranch === 'UNDEFINED' ? ". "
                            : " by $initiatorFullName, ".$initiator->ProfileBranch." Branch.";
                        $initiationMessage = "Dear $user_full_name, a loan request for $clientName of $clientLoanAmount /- has been initiated$initiatorBranchComment Thank you!";
                        $numbers     = array();
                        array_push($numbers,$phoneNumber);
                        SMS::broadcastSMS($numbers,$initiationMessage,'36',$profile->id);
                        /*
                                            $name       = 'Loan Service Desk';
                                            $subject    =   'Loan Application Submitted';
                                            $body       =   "<p>Greetings from Treasure Capital Limited.</p>
                                            <p>A loan application has been submitted and directed to you for approval.</p>
                                            <p>Please login into the system and approve or reject the application.</p>
                                            <p>Please do not hesitate to contact us if you have any queries.</p>
                                            <p>Thank you for trusting and doing business with us.</p>";
                                            $message     = Mailer::Build($name,$subject,$body, $user_full_name);
                                            $emailStatus = CommonFunctions::broadcastEmailNotification($emailAddress,$subject,$message);
                        */
                        Logger::logUserActivity("Created Loan Account: $accountNumber for $FullName",'normal');
                        if(!empty($data['filesPath'])){
                            $filePath = $data['filesPath'];
                            LoanApplication::uploadApplicationSupportFiles($filePath,$loanaccount);
                        }
                        if($loanPeriod > 0){
                            LoanManager::freezeInterestAccrual($loanaccount->loanaccount_id,$loanPeriod,"Loan Application");
                        }

                        $status = 1;
                    }else{
                        $status = 0;
                    }
                }else{
                    $status = 2;
                }
                return $status;
                break;
        }
    }

    public static function calculateRepaymentCycle($loanPeriod, $repaymentFrequency) {
        $repaymentCycle = 0;

        switch($repaymentFrequency){
            case 'daily':
                $repaymentCycle = $loanPeriod;
                break;
            case 'weekly':
                $repaymentCycle = ceil($loanPeriod / 7);
                break;
            case 'bi-weekly':
                $repaymentCycle = ceil($loanPeriod / 14);
                break;
            case 'monthly':
                $repaymentCycle = ceil($loanPeriod / 30);
                break;
            case 'quarterly':
                $repaymentCycle = ceil($loanPeriod / 90);
                break;
        }

        return $repaymentCycle;
    }

    public static function uploadApplicationSupportFiles($filesPath,$model){
        $documentURL = Yii::app()->params['loanDocs'];
        if(!isset($filesPath)){
            $uploadStatus = 0;
        }else{
            $img = $filesPath;
            if(!empty($img)){
                $img_desc = CommonFunctions::reArrayFiles($img);
                foreach($img_desc as $val){
                    $ext         = pathinfo($val['name'], PATHINFO_EXTENSION);
                    $fileUpload  = new LoanFiles;
                    $fileUpload->loanaccount_id = $model->loanaccount_id;
                    $fileUpload->name           = $model->account_number.'-'.$model->BorrowerFullName;
                    $fileUpload->filename       = date('YmdHis',time()).mt_rand().'.'.$ext;
                    $fileUpload->created_by     = Yii::app()->user->user_id;
                    $fileUpload->created_at     = date('Y-m-d H:i:s');
                    if($fileUpload->save()){
                        move_uploaded_file($val['tmp_name'],$documentURL."/".$fileUpload->filename);
                    }
                }
                $uploadStatus = 1;
            }else{
                $uploadStatus = 0;
            }
        }
        return $uploadStatus;
    }

    public static function restrictMultipleRunningAccounts($userID){
        $accountSql = "SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status IN('0','1','2','5','6','7','8','9','10')";
        $accounts   = Loanaccounts::model()->findAllBySql($accountSql);
        return !empty($accounts) ? 1 : 0;
    }

    public static function restrictMultipleActiveAccounts($userID){
        $accountSql = "SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status IN('2','5','6','7')";
        $accounts   = Loanaccounts::model()->findAllBySql($accountSql);
        return !empty($accounts) ? 1 : 0;
    }

    public static function LoadFilteredLoanApplications($branch,$rm,$memberID,$startDate,$endDate,$loanStatus){
        $applicationQuery="SELECT * FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        switch(Yii::app()->user->user_level){
            case '0':
                $applicationQuery.="";
                break;

            case '1':
                $applicationQuery.=" AND branch_id=$userBranch";
                break;

            case '2':
                $applicationQuery.=" AND rm=$userID";
                break;

            case '3':
                $applicationQuery.=" AND user_id=$userID";
                break;

            case '5':
                $applicationQuery.="";
                break;
        }
        return LoanApplication::getLoanApplications($branch,$rm,$memberID,$loanStatus,$applicationQuery);
    }

    public static function getLoanApplications($branch,$rm,$memberID,$loanStatus,$applicationQuery){
        if($branch !=0){
            $applicationQuery.=" AND branch_id=$branch";
        }

        if($rm != 0){
            $applicationQuery.=" AND rm=$rm";
        }

        if($memberID != 0){
            $applicationQuery.=" AND user_id=$memberID";
        }

        if($loanStatus != 'niemals'){
            $formattedStatus=(int)$loanStatus;
            $applicationQuery.=" AND loan_status='$formattedStatus'";
        }
        $applicationQuery.=" ORDER BY loanaccount_id DESC";
        return Loanaccounts::model()->findAllBySql($applicationQuery);
    }

    public static function getEMIAmount($loanaccount_id){
        return LoanManager::getActualLoanBalance($loanaccount_id);
    }

    public static function getTotalBorrowerLoanAccounts($user_id){
        $member_loanaccounts=Loanaccounts::model()->findAllBySql("SELECT * FROM loanaccounts WHERE user_id=$user_id");
        return count($member_loanaccounts);
    }

    public static function getLoanAccount($loanaccount_id){
        return Loanaccounts::model()->findByPk($loanaccount_id);
    }

    public static function getLoanDisbursementDetails($loanaccount_id){
        return DisbursedLoans::model()->findAllBySql("SELECT * FROM disbursed_loans WHERE loanaccount_id=$loanaccount_id");
    }

    public static function approveLoanAccount($loanaccount_id,$amount,$repayment_period,$repayment_start_date,$penalty_amount,$approval_reason,$insuranceAmount,$processingAmount,$deductions,$finalApprovedAmount,$pay_frequency){
        $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
        $loanaccount->loan_status='1';
        $loanaccount->date_approved=date('Y-m-d');
        $loanaccount->penalty_amount=$penalty_amount;
        $loanaccount->approval_reason=$approval_reason;
        $loanaccount->repayment_period=$repayment_period;
        $loanaccount->repayment_start_date=$repayment_start_date;
        //$loanaccount->amount_approved=$amount;
        $loanaccount->amount_approved=$finalApprovedAmount;
        $loanaccount->approved_by=Yii::app()->user->user_id;

        $loanaccount->amount_receivable       = $amount;
        $loanaccount->insurance_fee       = $insuranceAmount;
        $loanaccount->processing_fee       = $processingAmount;
        $loanaccount->deduction_fee       = $deductions;
        $loanaccount->pay_mode       = $pay_frequency;

        if($loanaccount->save()){
            $data['loanaccount_id']=$loanaccount->loanaccount_id;
            $data['comment']=$approval_reason;
            $data['activity']="Loan Approval";
            $data['commented_by']=Yii::app()->user->user_id;
            LoanApplication::recordLoanComment($data);
            $accountNumber=$loanaccount->account_number;
            $profile=Profiles::model()->findByPk($loanaccount->user_id);
            $phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
            $fullName=$profile->ProfileFullName;
            Logger::logUserActivity("Approved Loan Account: $accountNumber for $fullName",'high');
            //SMS Notifications
            $amountFormatted=CommonFunctions::asMoney($amount);
            $message        = " Your loan request has been considered. Approved Amount: KES $amountFormatted.\nDisbursement shall be done in 2 hours.\nThank you!";
            $textMessage = "Dear ".$profile->firstName.",".  $message;
            $numbers=array();
            array_push($numbers,substr($phoneNumber,-9));
            SMS::broadcastSMS($numbers,$textMessage,'1',$profile->id);
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }

    public static function rejectLoanApplication($loanaccount_id,$reason){
        $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
        $loanaccount->loan_status='3';
        $loanaccount->date_approved=date('Y-m-d');
        $loanaccount->approved_by=Yii::app()->user->user_id;
        if($loanaccount->save()){
            $data['loanaccount_id']=$loanaccount->loanaccount_id;
            $data['comment']=$reason;
            $data['activity']="Loan Rejection";
            $data['commented_by']=Yii::app()->user->user_id;
            LoanApplication::recordLoanComment($data);
            $reject=new RejectedLoans;
            $reject->loanaccount_id=$loanaccount_id;
            $reject->type='0';
            $reject->reason=$reason;
            $reject->rejected_by=Yii::app()->user->user_id;
            $reject->rejected_at = date('Y-m-d H:i:s');
            $reject->save();
            $accountNumber=$loanaccount->account_number;
            $profile=Profiles::model()->findByPk($loanaccount->user_id);
            $phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
            $fullName=$profile->ProfileFullName;
            Logger::logUserActivity("Rejected Loan Account: $accountNumber for $fullName",'high');
            $formatReason=ucfirst($reason);
            //SMS Notifications
            $amountFormatted=CommonFunctions::asMoney($loanaccount->amount_applied);
            $message = " Your loan request of KES $amountFormatted has been rejected.\nFor more info contact your account manager.\nThank you!";
            $textMessage = "Dear ".$profile->firstName.",".  $message;
            $numbers=array();
            array_push($numbers,$phoneNumber);
            $alertType='2';
            SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }

    public static function disburseLoanApplication($loanaccount_id,$amount,$disbursal_reason,$amountSentToClient,$insuranceFee,$processingFee,$payFrequency){

        $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
        switch(LoanApplication::restrictDoubleDisbursement($loanaccount_id)){
            case 0:
                //Essential Details
                $amountFormatted=CommonFunctions::asMoney($amount);
                $accountNumber=$loanaccount->account_number;
                $profile=Profiles::model()->findByPk($loanaccount->user_id);
                $pNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
                $clientFirstName=strtoupper($profile->firstName);
                $fullName=$profile->ProfileFullName;
                $phoneNumber='254'.$pNumber;
                //B2C MPESA
                $commandID='BusinessPayment';
                $remarks='Client Loan Disbursement: '.$disbursal_reason;
                $transtatus=LoanManager::B2CTransaction($commandID,$amount,$phoneNumber,$remarks);
                switch($transtatus){
                    case 0:
                        $status=3;
                        break;

                    case 1:
                        $loanaccount->loan_status='2';
                        $loanaccount->disbursal_reason=$disbursal_reason;
                        $loanaccount->pay_frequency = $payFrequency;
                        $loanaccount->save();
                        $disburse=new DisbursedLoans;
                        $disburse->loanaccount_id=$loanaccount_id;
                        $disburse->amount_disbursed=$amount;
                        $disburse->disbursed_by=Yii::app()->user->user_id;
                        $disburse->disbursed_at = date('Y-m-d H:i:s');
                        if($disburse->save()){
                            //calculate interest payable for days frozen
//                            $freezingPeriod = $loanaccount->freezing_period;
//                            if($freezingPeriod > 0){
//                                $interestRate = $loanaccount->interest_rate;
//                                $interestPayable      = LoanManager::getInterestAmount($interestRate,$freezingPeriod,$loanaccount->amount_applied);
//                                $bad_symbols = array(",");
//                                $interestPayable =  str_replace($bad_symbols,"",$interestPayable);
//                                if($interestPayable > 0){
//                                    LoanManager::recordAccruedInterest($loanaccount->loanaccount_id,$interestPayable,'debit','0');
//                                }
//                            }

                            //calculate total loan interest and record Accrued Interest
                            $interestRate = $loanaccount->interest_rate;
                            $loanInterest = LoanManager::getTotalLoanInterestAmount($interestRate,$loanaccount->amount_applied);
                            if($loanInterest > 0){
                                LoanManager::recordAccruedInterest($loanaccount->loanaccount_id,$loanInterest,'debit','0');
                            }


                            $data['loanaccount_id']=$loanaccount_id;
                            $data['comment']=$disbursal_reason;
                            $data['activity']="Loan Disbursement";
                            $data['commented_by']=Yii::app()->user->user_id;
                            LoanApplication::recordLoanComment($data);
                            Logger::logUserActivity("Disbursed amount KES $amountFormatted for loan Acc.:$accountNumber for $fullName",'high');
                            //$msg="Your loan request of KES $amountFormatted has been disbursed.\nThank you!";
                            $msg="Your approved loan of $amountFormatted has been processed.Deductions insurance fee $insuranceFee /- & Processing fee $processingFee /-.Net disbursed $amountSentToClient /-. \nThank you!";

                            $textMessage = "Dear ".$clientFirstName.", ". $msg;
                            $numbers=array();
                            array_push($numbers,$pNumber);
                            $alertType='3';
                            SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
                            $status=1;


                        }

                        break;

                    case 2020:
                        $loanaccount->loan_status='2';
                        $loanaccount->disbursal_reason=$disbursal_reason;
                        $loanaccount->save();
                        $disburse=new DisbursedLoans;
                        $disburse->loanaccount_id=$loanaccount_id;
                        $disburse->amount_disbursed=$amount;
                        $disburse->disbursed_by=Yii::app()->user->user_id;
                        $disburse->disbursed_at = date('Y-m-d H:i:s');
                        if($disburse->save()){
                            //calculate interest payable for days frozen
                            $freezingPeriod = $loanaccount->freezing_period;
                            if($freezingPeriod > 0){
                                $interestRate = $loanaccount->interest_rate;
                                $interestPayable      = LoanManager::getInterestAmount($interestRate,$freezingPeriod,$loanaccount->amount_applied);
                                $bad_symbols = array(",");
                                $interestPayable =  str_replace($bad_symbols,"",$interestPayable);
                                if($interestPayable > 0){
                                    LoanManager::recordAccruedInterest($loanaccount->loanaccount_id,$interestPayable,'debit','0');
                                }
                            }
                            $data['loanaccount_id']=$loanaccount_id;
                            $data['comment']=$disbursal_reason;
                            $data['activity']="Loan Disbursement";
                            $data['commented_by']=Yii::app()->user->user_id;
                            LoanApplication::recordLoanComment($data);
                            Logger::logUserActivity("Disbursed amount KES $amountFormatted for loan Acc.:$accountNumber for $fullName",'high');

                            $msg="Your approved loan of $amountFormatted has been processed.Deductions insurance fee $insuranceFee /- & Processing fee $processingFee /-.Net disbursed $amountSentToClient /-. \nThank you!";

                            $textMessage = "Dear ".$clientFirstName.", ". $msg;
                            $numbers=array();
                            array_push($numbers,$pNumber);
                            $alertType='3';
                            SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);

                            $status=2020;
                        }

                        break;


                    default:
                        $status=$transtatus;
                        break;
                }
                break;

            case 1:
                $status=2;
                break;
        }
        return $status;
    }

    public static function restrictDoubleDisbursement($loanaccountID){
        $restrictSQL="SELECT * FROM disbursed_loans WHERE loanaccount_id=$loanaccountID";
        $restricted= DisbursedLoans::model()->findAllBySql($restrictSQL);
        return !empty($restricted) ? 1 : 0;
    }

    public static function getLoanReleasedAmount($loanaccount_id){
        $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
        return $loanaccount->NotFormattedExactAmountDisbursed;
    }

    public static function topUpLoanApplication($loanaccount_id,$topupamount,$amountDisburse,$interestRate,$repaymentPeriod,$comment){
        $topupSQL="SELECT * FROM loan_topup WHERE loanaccount_id=$loanaccount_id AND is_approved='0'
		 ORDER BY id LIMIT 1";
        $topup = LoanTopup::model()->findBySql($topupSQL);
        if(!empty($topup)){
            $status=2;
        }else{
            $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
            $loanaccount->loan_status='6';
            if($loanaccount->save()){
                $bad_symbols = array(",");
                $amountValue = str_replace($bad_symbols, "", $amountDisburse);
                $topped=new LoanTopup;
                $topped->loanaccount_id=$loanaccount_id;
                $topped->topup_amount=$topupamount;
                $topped->disbursement_amount=$amountValue;
                $topped->interest_rate=$interestRate;
                $topped->repayment_period=$repaymentPeriod;
                $topped->comment=$comment;
                $topped->is_approved='0';
                $topped->topped_by=Yii::app()->user->user_id;
                $topped->topped_at = date('Y-m-d H:i:s');
                $topped->save();
                $data['loanaccount_id']=$loanaccount_id;
                $data['comment']=$comment;
                $data['activity']="Requesting Loan Top Up";
                $data['commented_by']=Yii::app()->user->user_id;
                LoanApplication::recordLoanComment($data);
                $profile=Profiles::model()->findByPk($loanaccount->user_id);
                $phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
                $accountNumber=$loanaccount->account_number;
                $fullName=$loanaccount->BorrowerFullName;
                $topupFormatted=CommonFunctions::asMoney($topped->topup_amount);
                $directed = Profiles::model()->findByPk($loanaccount->direct_to);
                $loanDirectedName = ucfirst($directed->firstName);
                $loanDirectedPhone = ProfileEngine::getProfileContactByTypeOrderDesc($directed->id,'PHONE');;
                $accountHolderBranch = $profile->ProfileBranch;
                $topUpAppliedBy= ucfirst(Profiles::model()->findByPk(Yii::app()->user->user_id)->firstName);
                $topUpMessage = "Dear ".$loanDirectedName.", a top up request of KES ".$topupFormatted." for ".$fullName." of ".$accountHolderBranch." has been applied by".$topUpAppliedBy;
                Logger::logUserActivity("Initiated Account Top Up of KES $topupFormatted: $accountNumber for $fullName",'high');
                $textMessage="Dear ".$profile->firstName.", your loan top up of KES ".$topupFormatted." for account ". $profile->idNumber." has been received.\nThank you!";
                $numbers=array();
                $directs = array();
                array_push($numbers,$phoneNumber);
                array_push($directs,$loanDirectedPhone);
                $alertType   = '9';
                $alertStatus = SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
                $directedStatus = SMS::broadcastSMS($directs,$topUpMessage,$alertType,$profile->id);
                $status =1;
            }else{
                $status=0;
            }
        }
        return $status;
    }

    public static function approveLoanTopUp($topup_id,$loanaccountID,$approvalReason){
        $topup=LoanTopup::model()->findByPk($topup_id);
        if($topup->is_approved == '0'){
            $topup->is_approved='1';
            $topup->approval_reason=$approvalReason;
            if($topup->save()){
                $loanaccount=LoanApplication::getLoanAccount($loanaccountID);
                $profile=Profiles::model()->findByPk($loanaccount->user_id);
                $phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
                $accountNumber=$loanaccount->account_number;
                $fullName=$loanaccount->BorrowerFullName;
                $topupFormatted=CommonFunctions::asMoney($topup->topup_amount);
                //Comment
                $data['loanaccount_id']=$loanaccountID;
                $data['comment']=$approvalReason;
                $data['activity']="Approve Loan Top Up";
                $data['commented_by']=Yii::app()->user->user_id;
                LoanApplication::recordLoanComment($data);
                Logger::logUserActivity("Approved Top Up of KES $topupFormatted Acc: $accountNumber for $fullName",'high');
                $textMessage="Dear ".$profile->firstName.", your loan top up of KES ".$topupFormatted." for account ". $profile->idNumber." has been approved.\nThank you!";
                $numbers=array();
                array_push($numbers,$phoneNumber);
                $alertType='21';
                $alertStatus=SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
                $status=1;
            }else{
                $status=0;
            }
        }else{
            $status=2;
        }
        return $status;
    }

    public static function disburseLoanTopUp($topup_id,$loanaccountID,$disbursalReason){
        $loanaccount=LoanApplication::getLoanAccount($loanaccountID);
        $topup=LoanTopup::model()->findByPk($topup_id);
        if($topup->is_approved == '1'){
            //Essential Details
            $accountNumber=$loanaccount->account_number;
            $profile=Profiles::model()->findByPk($loanaccount->user_id);
            $pNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
            $clientFirstName=strtoupper($profile->firstName);
            $fullName=$profile->ProfileFullName;
            $clientPhoneNumber=$pNumber;
            $phoneNumber='254'.$pNumber;
            //B2C
            $commandID='BusinessPayment';
            $amountPaid=$topup->topup_amount;
            $amountFormatted=CommonFunctions::asMoney($amountPaid);
            $remarks='Member Loan Top Up '.$disbursalReason;
            $topStatus=LoanManager::B2CTransaction($commandID,$amountPaid,$phoneNumber,$remarks);
            switch($topStatus){
                case 0:
                    $status=3;
                    break;

                case 1:
                    $topup->is_approved='3';
                    $topup->disbursement_reason=$disbursalReason;
                    $topup->save();
                    //Top Up The Account
                    LoanManager::freezeLoanRepayments($loanaccountID,'4');
                    $loanaccount=LoanApplication::getLoanAccount($loanaccountID);
                    $loanaccount->interest_rate=$topup->interest_rate;
                    $loanaccount->repayment_period=$topup->repayment_period;
                    $loanaccount->crb_status='a';
                    $loanaccount->performance_level='A';
                    $loanaccount->save();
                    //Top Up The Account
                    $disburse=new DisbursedLoans;
                    $disburse->loanaccount_id=$loanaccountID;
                    $disburse->amount_disbursed=$amountPaid;
                    $disburse->type='top_up';
                    $disburse->disbursed_by=Yii::app()->user->user_id;
                    $disburse->disbursed_at = date('Y-m-d H:i:s');
                    $disburse->save();
                    //Comment
                    $data['loanaccount_id']=$loanaccountID;
                    $data['comment']=$disbursalReason;
                    $data['activity']="Disburse Loan Top Up";
                    $data['commented_by']=Yii::app()->user->user_id;
                    LoanApplication::recordLoanComment($data);
                    $loanBalance=LoanManager::getActualLoanBalance($loanaccountID);
                    $balanceFormatted=CommonFunctions::asMoney($loanBalance);
                    Logger::logUserActivity("Disbursed top up of KES $amountFormatted for $accountNumber for $fullName",'high');
                    $textMessage="Dear ".$clientFirstName.", Your top up request of ".$amountFormatted."/- is disbursed.\nNew Loan Balance = ". $balanceFormatted."/-\nThank you!";
                    $numbers=array();
                    array_push($numbers,$clientPhoneNumber);
                    $alertType='23';
                    $alertStatus=SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
                    $status=1;
                    break;

                default:
                    $status=$topStatus;
                    break;
            }
        }else{
            $status=2;
        }
        return $status;
    }

    public static function rejectLoanTopUp($topup_id,$loanaccountID,$rejectionReason){
        $topup=LoanTopup::model()->findByPk($topup_id);
        if($topup->is_approved == '0'){
            $topup->is_approved='2';
            $topup->rejection_reason=$rejectionReason;
            if($topup->save()){
                $loanaccount=LoanApplication::getLoanAccount($loanaccountID);
                $loanaccount->loan_status='2';
                $loanaccount->save();
                $profile=Profiles::model()->findByPk($loanaccount->user_id);
                $phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
                $accountNumber=$loanaccount->account_number;
                $fullName=$loanaccount->BorrowerFullName;
                //Comment
                $data['loanaccount_id']=$loanaccountID;
                $data['comment']=$rejectionReason;
                $data['activity']="Reject Loan Top Up";
                $data['commented_by']=Yii::app()->user->user_id;
                LoanApplication::recordLoanComment($data);
                Logger::logUserActivity("Rejected Account Top Up: $accountNumber for $fullName",'high');
                $topupFormatted=CommonFunctions::asMoney($topup->topup_amount);
                $textMessage="Dear ".$profile->firstName.", your loan top up of KES ".$topupFormatted." for account ". $profile->idNumber." has been rejected. Please contact your account manager for any enquiries!";
                $numbers=array();
                array_push($numbers,$phoneNumber);
                $alertType='22';
                $alertStatus=SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
                $status=1;
            }else{
                $status=0;
            }
        }else{
            $status=2;
        }
        return $status;
    }

    public static function restructureLoanApplication($loanaccount_id){
        $loanaccount = LoanApplication::getLoanAccount($loanaccount_id);
        $loanaccount->loan_status ='5';
        if($loanaccount->save()){
            $profile=Profiles::model()->findByPk($loanaccount->user_id);
            $status = 1;
        }else{
            $status = 0;
        }
        return $status;
    }

    public static function recordLoanComment($data){
        $loanaccount = Loanaccounts::model()->findByPk($data['loanaccount_id']);
        if(!empty($loanaccount)){
            $branchID = $loanaccount->branch_id;
            $userID   = $loanaccount->user_id;
            $rmID     = $loanaccount->rm;
            $comment  = new Loancomments;
            $comment->loanaccount_id = $data['loanaccount_id'];
            if(isset($data['type'])){
                $comment->type_id      = $data['type'];
            }
            $comment->branch_id      = $branchID;
            $comment->user_id        = $userID;
            $comment->rm             = $rmID;
            $comment->comment        = $data['comment'];
            $comment->activity       = $data['activity'];
            $comment->commented_by   = $data['commented_by'];
            $comment->commented_at   = date('Y-m-d H:i:s');
            if($comment->save()){
                $loanComment   = $comment->comment;
                $loanaccount   = LoanApplication::getLoanAccount($comment->loanaccount_id);
                $profile       = Profiles::model()->findByPk($loanaccount->user_id);
                $accountNumber = $loanaccount->account_number;
                $fullName      = $profile->ProfileFullName;
                $activityLog   = "Submitted Account Comment| $accountNumber for Client| $fullName | {$loanComment}";
                Logger::logUserActivity($activityLog,'high');
                $status = 1;
            }else{
                $status = 0;
            }
        }else{
            $status = 0;
        }
        return $status;
    }

    public static function getLoanComments($loanaccount_id){
        $commentSql = "SELECT * FROM loancomments WHERE loanaccount_id=$loanaccount_id ORDER BY comment_id DESC";
        $comments   =  Loancomments::model()->findAllBySql($commentSql);
        return $comments;
    }

    public static function getAllCommentTypes(){
        $typeQuery = "SELECT * FROM comment_types WHERE is_active='1' ORDER BY name ASC";
        $types     = CommentTypes::model()->findAllBySql($typeQuery);
        return $types;
    }

    public static function getAccountTotalWrittenOff($loanaccount_id,$start_date,$end_date){
        $writeOffQuery="SELECT COALESCE(SUM(amount),0) AS amount FROM write_offs WHERE loanaccount_id=$loanaccount_id AND (DATE(created_at) BETWEEN '$start_date' AND '$end_date')";
        $repayments=WriteOffs::model()->findBySql($writeOffQuery);
        if(!empty($repayments)){
            $totalProfit=$repayments->amount;
        }else{
            $totalProfit=0;
        }
        return $totalProfit;
    }

    public static function getAccountTotalWrittenOffEver($loanaccount_id){
        $writeOffQuery="SELECT COALESCE(SUM(amount),0) AS amount FROM write_offs WHERE loanaccount_id=$loanaccount_id";
        $repayments=WriteOffs::model()->findBySql($writeOffQuery);
        if(!empty($repayments)){
            $totalProfit=$repayments->amount;
        }else{
            $totalProfit=0;
        }
        return $totalProfit;
    }

    public static function getAccountTotalProfit($loanaccount_id,$start_date,$end_date){
        $amountWrittenOff=LoanApplication::getAccountTotalWrittenOff($loanaccount_id,$start_date,$end_date);
        $profitQuery="SELECT SUM(interest_paid) AS interest_paid,SUM(fee_paid) AS fee_paid,SUM(penalty_paid) AS penalty_paid FROM loanrepayments WHERE loanaccount_id=$loanaccount_id AND is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$start_date' AND '$end_date')";
        $repayments=Loanrepayments::model()->findBySql($profitQuery);
        if(!empty($repayments)){
            $totalProfit=$repayments->interest_paid + $repayments->fee_paid + $repayments->penalty_paid;
        }else{
            $totalProfit=0;
        }
        $subProfit=$totalProfit - $amountWrittenOff;
        return $subProfit;
    }

    public static function getAccountTotalProfitOrLoss($loanaccount_id){
        $amountWrittenOff=LoanApplication::getAccountTotalWrittenOffEver($loanaccount_id);
        $profitQuery="SELECT SUM(interest_paid) AS interest_paid,SUM(fee_paid) AS fee_paid,SUM(penalty_paid) AS penalty_paid FROM loanrepayments WHERE loanaccount_id=$loanaccount_id AND is_void IN('0','3','4')";
        $repayments=Loanrepayments::model()->findBySql($profitQuery);
        if(!empty($repayments)){
            $totalProfit=$repayments->interest_paid + $repayments->fee_paid + $repayments->penalty_paid;
        }else{
            $totalProfit=0;
        }
        $subProfit=$totalProfit - $amountWrittenOff;
        return $subProfit;
    }

    public static function LoadFilteredDueLoansReport($start_date,$end_date,$month,$year,$branch,$rm,$status){
        $searchQuery="SELECT * FROM loanaccounts WHERE loan_status IN('2','5','6','7') AND (DATE_FORMAT(repayment_start_date,'%d') BETWEEN $start_date AND $end_date) ";
        switch(Yii::app()->user->user_level){
            case '0':
                $searchQuery.="";
                break;

            case '1':
                $userBranch=Yii::app()->user->user_branch;
                $searchQuery.=" AND branch_id=$userBranch";
                break;

            case '2':
                $userID=Yii::app()->user->user_id;
                $searchQuery.=" AND rm=$userID";
                break;

            case '3':
                $userID=Yii::app()->user->user_id;
                $searchQuery.=" AND user_id=$userID";
                break;
        }
        echo LoanApplication::getFilteredDueLoans($branch,$rm,$status,$start_date,$end_date,$month,$year,$searchQuery);
    }

    public static function getFilteredDueLoans($branch,$rm,$status,$start_date,$end_date,$month,$year,$searchQuery){
        if($branch != 0){
            $searchQuery.=" AND branch_id=$branch";
        }

        if($rm != 0){
            $searchQuery.=" AND rm=$rm";
        }

        if($month != 0){
            $searchQuery.=" AND MONTH(repayment_start_date)=$month";
        }

        if($year != 0){
            $searchQuery.=" AND YEAR(repayment_start_date)=$year";
        }

        switch($status){
            case '0':
                $searchQuery.="";
                break;

            case 'unpaid':
                $searchQuery.=" AND loanaccount_id NOT IN(SELECT loanaccount_id FROM loantransactions
			 WHERE (DATE_FORMAT(transacted_at,'%d') BETWEEN $start_date AND $end_date))";
                break;

            case 'paid':
                $searchQuery.=" AND loanaccount_id IN(SELECT loanaccount_id FROM loantransactions
			 WHERE (DATE_FORMAT(transacted_at,'%d') BETWEEN $start_date AND $end_date))";
                break;

            case 'cleared':
                $searchQuery.="";
                break;
        }

        $loanaccounts=Loanaccounts::model()->findAllBySql($searchQuery);
        $dueLoans=array();
        $count=1;
        foreach($loanaccounts as $loanaccount){
            $repaymentStartDate=$loanaccount->repayment_start_date;
            $dueLoans[$count]['loanaccount_id']=$loanaccount->loanaccount_id;
            $dueLoans[$count]['repayment_date']=$repaymentStartDate;
            $count++;
        }
        $htmlTable=Tabulate::createdFilteredDueLoansTable($dueLoans);
        echo $htmlTable;
    }


    public static function displayDueLoansDetails($loans){
        $i=1;
        foreach($loans as $loan){
            echo '<tr>
					<td>'; echo $i; echo'</td>
					<td>'; echo $loan->account_number; echo'</td>
					<td>'; echo $loan->getBorrowerFullName();echo'</td>
					<td>'; echo CommonFunctions::asMoney(LoanRepayment::getTotalAmountPaid($loan->loanaccount_id));echo'</td>
					<td>'; echo CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalLoanBalance($loan->loanaccount_id));echo'</td>
					<td>'; echo LoanTransactionsFunctions::getLoanLastRepayment($loan->loanaccount_id);echo'</td>
					<td>'; echo $loan->getCurrentLoanAccountStatus();echo'</td>
			     </tr>';
            $i++;
        }
    }

    public static function getDisbursedLoansDetails($loans){
        $i=1;
        foreach($loans as $loan){
            echo '<tr>
					<td>'; echo $i; echo'</td>
					<td>'; echo $loan->getBorrowerFullName();echo'</td>
					<td>'; echo $loan->account_number; echo'</td>
					<td>'; echo $loan->interest_rate;echo'</td>
					<td>'; echo $loan->repayment_period;echo'</td>
					<td>'; echo CommonFunctions::asMoney($loan->NotFormattedExactAmountDisbursed);echo'</td>
					<td>'; echo $loan->getFormattedDisbursedDate();echo'</td>
					<td>'; echo CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalLoanBalance($loan->loanaccount_id));echo'</td>
					<td>'; echo $loan->getCurrentLoanAccountStatus();echo'</td>
			     </tr>';
            $i++;
        }
    }

    public static function getLoansWithoutRepayments(){
        $userID=Yii::app()->user->user_id;
        $userBranch=Yii::app()->user->user_branch;
        switch(Yii::app()->user->user_level){
            case '0':
                $loanWithNoRepaymentSQL="SELECT * FROM loanaccounts WHERE loan_status<>'3' AND loanaccount_id NOT IN(SELECT loanaccount_id FROM loantransactions)";
                break;

            case '1':
                $loanWithNoRepaymentSQL="SELECT * FROM loanaccounts,users WHERE loanaccounts.user_id=users.user_id AND users.branch_id=$userBranch AND loanaccounts.loan_status<>'3' AND loanaccounts.loanaccount_id NOT IN(SELECT loanaccount_id FROM loantransactions)";
                break;

            case '2':
                $loanWithNoRepaymentSQL="SELECT * FROM loanaccounts WHERE loan_status<>'3' AND created_by=$userID AND loanaccount_id NOT IN(SELECT loanaccount_id FROM loantransactions)";
                break;

            case '3':
                $loanWithNoRepaymentSQL="SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status<>'3' AND loanaccount_id NOT IN(SELECT loanaccount_id FROM loantransactions)";
                break;
        }
        $notRepaidLoans=Loanaccounts::model()->findAllBySql($loanWithNoRepaymentSQL);
        return $notRepaidLoans;
    }

    public static function getLoansPastMaturityDate(){
        $today=date('Y-m-d');
        $userID=Yii::app()->user->user_id;
        $userBranch=Yii::app()->user->user_branch;
        $loanSQL="SELECT * FROM loanaccounts,loan_maturities WHERE loanaccounts.loanaccount_id=loan_maturities.loanaccount_id
		 AND loan_maturities.maturity_date < '$today'";
        switch(Yii::app()->user->user_level){
            case '0':
                $loanSQL.="";
                break;

            case '1':
                $loanSQL.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $loanSQL.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $loanSQL.=" AND loanaccounts.user_id=$userID";
                break;
        }
        $loans=Loanaccounts::model()->findAllBySql($loanSQL);
        return $loans;
    }

    public static function getLoansWithoutRepaymentsDate(){
        $today=date('Y-m-d');
        $userID=Yii::app()->user->user_id;
        $userBranch=Yii::app()->user->user_branch;
        $loanSQL="SELECT * FROM loanaccounts,penaltyaccrued WHERE loanaccounts.loanaccount_id=penaltyaccrued.loanaccount_id AND penaltyaccrued.date_defaulted < '$today'";
        switch(Yii::app()->user->user_level){
            case '0':
                $loanSQL.="";
                break;

            case '1':
                $loanSQL.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $loanSQL.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $loanSQL.=" AND loanaccounts.user_id=$userID";
                break;
        }
        $loans=Loanaccounts::model()->findAllBySql($loanSQL);
        return $loans;
    }

    public static function LoadFilteredMissedRepayments($branch,$startDate,$endDate,$staff,$borrower){
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        $loanQuery="SELECT * FROM loanaccounts,penaltyaccrued WHERE loanaccounts.loanaccount_id=penaltyaccrued.loanaccount_id AND (penaltyaccrued.date_defaulted BETWEEN '$startDate' AND '$endDate')";
        switch(Yii::app()->user->user_level){
            case '0':
                $loanQuery.=" ";
                break;

            case '1':
                $loanQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $loanQuery.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $loanQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }
        echo LoanApplication::getFilteredMissedRepayments($branch,$staff,$borrower,$loanQuery);
    }

    public static function getFilteredMissedRepayments($branch,$staff,$borrower,$loanQuery){
        if($branch != 0){
            $loanQuery.=" AND loanaccounts.branch_id=$branch";
        }

        if($staff != 0){
            $loanQuery.=" AND loanaccounts.rm=$staff";
        }

        if($borrower != 0){
            $loanQuery.=" AND loanaccounts.user_id=$borrower";
        }

        $loanQuery.=" ORDER BY penaltyaccrued.is_paid ASC, penaltyaccrued.loanaccount_id DESC";
        $loans=Loanaccounts::model()->findAllBySql($loanQuery);
        $htmlTable=Tabulate::createFilteredMissedRepaymentsTable($loans);
        echo $htmlTable;
    }

    public static function getOpenLoansUsers(){
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        $loanSQL="SELECT * FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
        switch(Yii::app()->user->user_level){
            case '0':
                $loanSQL.="";
                break;

            case '1':
                $loanSQL.=" AND branch_id=$userBranch";
                break;

            case '2':
                $loanSQL.=" AND rm=$userID";
                break;

            case '2':
                $loanSQL.=" AND user_id=$userID";
                break;
        }
        $loanaccounts=Loanaccounts::model()->findAllBySql($loanSQL);
        return $loanaccounts;
    }

    /**************************************************
    LOAN STATEMENT FUNCTIONALITIES
     ******************************************************/
    public static function getPdfDownloadableStatement($loanaccountID,$start_date,$end_date){
        $repayments=LoanRepayment::getRepaymentsWithinPeriod($loanaccountID,$start_date,$end_date);
        $pdfLink=ExportFunctions::exportClientLoanStatementAsPdf($repayments,$loanaccountID);
        echo $pdfLink;
    }

    public static function emailClientLoanStatement($loanaccountID,$start_date,$end_date){
        $repayments=LoanRepayment::getRepaymentsWithinPeriod($loanaccountID,$start_date,$end_date);
    }

    /*******************************************************
    RETURN, FORWARD, RECALL, RESUBMIT LOAN FUNCTIONALITIES
     ***********************************************************/
    public static function checkIfApplicationReturned($loanID){
        $returnSQL="SELECT * FROM loan_redirects WHERE loanaccount_id=$loanID AND resubmitted='0' LIMIT 1";
        $returned=LoanRedirects::model()->findBySql($returnSQL);
        if(!empty($returned)){
            $returnedStatus=$returned;
        }else{
            $returnedStatus=0;
        }
        return $returnedStatus;
    }

    public static function checkIfApplicationForwarded($loanID){
        $forwardSQL="SELECT * FROM loan_forwards WHERE loanaccount_id=$loanID AND resolved='0' LIMIT 1";
        $forwarded=LoanForwards::model()->findBySql($forwardSQL);
        if(!empty($forwarded)){
            $forwardStatus=$forwarded;
        }else{
            $forwardStatus=0;
        }
        return $forwardStatus;
    }

    public static function topUpApprovedOrRejected($accountID){
        $topupSQL="SELECT * FROM loan_topup WHERE loanaccount_id=$accountID AND is_approved IN('0','1') ORDER BY id DESC LIMIT 1";
        $toppedup=LoanTopup::model()->findAllBySql($topupSQL);
        if(!empty($toppedup)){
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }

    public static function getLoanAccountFiles($loanaccountID){
        $filesQuery="SELECT * FROM loan_files WHERE loanaccount_id=$loanaccountID";
        $loanfiles=LoanFiles::model()->findAllBySql($filesQuery);
        return $loanfiles;
    }

    public static function getUserSavingAccountBalance($userID){
        $savingQuery="SELECT savingaccount_id FROM savingaccounts WHERE user_id=$userID";
        $account=Savingaccounts::model()->findBySql($savingQuery);
        if(!empty($account)){
            $accountBalance=SavingFunctions::getTotalSavingAccountBalance($account->savingaccount_id);
        }else{
            $accountBalance=0;
        }
        return $accountBalance;
    }

    /*************
    Guarantors
     *************************/
    public static function createGuarantorRecord($accountID,$name,$idNumber,$phoneNumber){
        $guarantor=new Guarantors;
        $loanaccount=Loanaccounts::model()->findByPk($accountID);
        $accountNumber=$loanaccount->account_number;
        $accountHolder=$loanaccount->getFullMemberName();
        $guarantor->loanaccount_id=$accountID;
        $guarantor->user_id=$loanaccount->user_id;
        $guarantor->branch_id=$loanaccount->branch_id;
        $guarantor->name=$name;
        $guarantor->id_number=$idNumber;
        $guarantor->phone=$phoneNumber;
        $guarantor->rm=$loanaccount->rm;
        $guarantor->created_by=Yii::app()->user->user_id;
        $guarantor->created_at= date('Y-m-d H:i:s');
        if($guarantor->save()){
            $fDetails=$guarantor->name."-".$guarantor->id_number;
            Logger::logUserActivity("Added Guarantor Record: $fDetails for A/C: $accountNumber for $accountHolder",'normal');
            $guarantorStatus=1;
        }else{
            $guarantorStatus=0;
        }
        return $guarantorStatus;
    }

    public static function getAccountGuarantors($accountID){
        $guarantorQuery="SELECT * FROM guarantors WHERE loanaccount_id=$accountID ORDER BY guarantor_id DESC";
        $guarantors=Guarantors::model()->findAllBySql($guarantorQuery);
        return $guarantors;
    }

}
