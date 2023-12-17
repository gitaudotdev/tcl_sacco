<?php

class LoanRepayment{

    public static function getFilteredRepaymentsCount($loanaccountID,$rm,$branch,$startDate,$endDate){
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        $repaymentQuery="SELECT * FROM loanrepayments,loanaccounts WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id
	 AND (DATE(loanrepayments.repaid_at) BETWEEN '$startDate' AND '$endDate') AND loanrepayments.is_void NOT IN('1','2')";
        switch(Yii::app()->user->user_level){
            case '0':
                $repaymentQuery.="";
                break;

            case '1':
                $repaymentQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $repaymentQuery.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $repaymentQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }

        if($loanaccountID !=0){
            $repaymentQuery.=" AND loanrepayments.loanaccount_id=$loanaccountID";
        }
        if($rm !=0){
            $repaymentQuery.=" AND loanaccounts.rm=$rm";
        }
        if($branch !=0){
            $repaymentQuery.=" AND loanaccounts.branch_id=$branch";
        }
        $repayments=Loanrepayments::model()->findAllBySql($repaymentQuery);
        return count($repayments);
    }

    public static function LoadFilteredRepayments($loanaccountID,$rm,$branch,$startDate,$endDate){
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        $repaymentQuery="SELECT * FROM loanrepayments,loanaccounts WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id
	AND loanrepayments.is_void NOT IN('1','2') AND (DATE(loanrepayments.repaid_at) BETWEEN '$startDate' AND '$endDate')";
        switch(Yii::app()->user->user_level){
            case '0':
                $repaymentQuery.="";
                break;

            case '1':
                $repaymentQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $repaymentQuery.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $repaymentQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }
        return LoanRepayment::getFilteredRepayments($loanaccountID,$rm,$branch,$repaymentQuery,$startDate,$endDate);
    }

    public static function getFilteredRepayments($loanaccountID,$rm,$branch,$repaymentQuery,$startDate,$endDate){
        if($loanaccountID !=0){
            $repaymentQuery.=" AND loanrepayments.loanaccount_id=$loanaccountID";
        }
        if($rm !=0){
            $repaymentQuery.=" AND loanaccounts.rm=$rm";
        }
        if($branch !=0){
            $repaymentQuery.=" AND loanaccounts.branch_id=$branch";
        }
        $repaymentQuery.=" ORDER BY loanrepayments.loanrepayment_id DESC";
        return Loanrepayments::model()->findAllBySql($repaymentQuery);
    }

    public static function LoadFilteredDailyReport($loanaccountID,$rm,$branch,$startDate,$endDate){
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        $dailyQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3') AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
        switch(Yii::app()->user->user_level){
            case '0':
                $dailyQuery.="";
                break;

            case '1':
                $dailyQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $dailyQuery.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $dailyQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }
        echo LoanRepayment::getFilteredDailyReport($loanaccountID,$rm,$branch,$dailyQuery,$startDate,$endDate);
    }

    public static function getFilteredDailyReport($loanaccountID,$rm,$branch,$dailyQuery,$startDate,$endDate){
        if($loanaccountID !=0){
            $dailyQuery.=" AND loanaccounts.loanaccount_id=$loanaccountID";
        }
        if($rm !=0){
            $dailyQuery.=" AND loanaccounts.rm=$rm";
        }
        if($branch !=0){
            $dailyQuery.=" AND loanaccounts.branch_id=$branch";
        }
        $dailyQuery.=" ORDER BY loanaccounts.loanaccount_id DESC";
        $loanaccounts=Loanaccounts::model()->findAllBySql($dailyQuery);
        $loanTable=Tabulate::createLoanDailyReportTable($loanaccounts,$endDate);
        echo $loanTable;
    }

    public static function getRepaymentsWithinPeriod($loanaccountID,$start_date,$end_date){
        $repaymentSQL="SELECT * FROM loanrepayments WHERE is_void IN('0','3','4')  AND (date BETWEEN '$start_date' AND '$end_date') AND loanaccount_id=$loanaccountID";
        $repayments=Loanrepayments::model()->findAllBySql($repaymentSQL);
        return $repayments;
    }

    public static function getTotalAmountPaid($loanaccount_id){
        $loantransactionSql="SELECT SUM(amount) as amount FROM loantransactions WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','3','4')";
        $transaction=Loantransactions::model()->findBySql($loantransactionSql);
        if(!empty($transaction)){
            $amountRepaid=$transaction->amount;
        }else{
            $amountRepaid=0;
        }
        return $amountRepaid;
    }

    public static function getTotalPrincipalPaid($loanaccount_id){
        $principalSql="SELECT SUM(principal_paid) as principal_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','3','4')";
        $principal=Loanrepayments::model()->findBySql($principalSql);
        if(!empty($principal)){
            $principalRepaid=$principal->principal_paid;
        }else{
            $principalRepaid=0;
        }
        return $principalRepaid;
    }

    public static function getTotalPrincipalPaidFrom($loanaccount_id,$startDate){
        $principalSql="SELECT COALESCE(SUM(principal_paid),0) as principal_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','3','4') AND date <= '$startDate'";
        $principal=Loanrepayments::model()->findBySql($principalSql);
        if(!empty($principal)){
            $principalRepaid=$principal->principal_paid;
        }else{
            $principalRepaid=0;
        }
        return $principalRepaid;
    }

    public static function getTotalInterestPaid($loanaccount_id){
        $nowMonth=date('m');
        $nowYear=date('Y');
        $interestSql="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','3','4') AND MONTH(repaid_at)='$nowMonth' AND YEAR(repaid_at)='$nowYear'";
        $interest=Loanrepayments::model()->findBySql($interestSql);
        if(!empty($interest)){
            $interestRepaid=$interest->interest_paid;
        }else{
            $interestRepaid=0;
        }
        return $interestRepaid;
    }

    public static function getTotalInterestPaidFrom($loanaccount_id,$startDate){
        $interestSql="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','3','4') AND date<='$startDate'";
        $interest=Loanrepayments::model()->findBySql($interestSql);
        if(!empty($interest)){
            $interestRepaid=$interest->interest_paid;
        }else{
            $interestRepaid=0;
        }
        return $interestRepaid;
    }

    public static function getAccountTotalInterestPaid($loanaccount_id){
        $interestSql="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','3','4')";
        $interest=Loanrepayments::model()->findBySql($interestSql);
        if(!empty($interest)){
            $interestRepaid=$interest->interest_paid;
        }else{
            $interestRepaid=0;
        }
        return $interestRepaid;
    }

    public static function getAccountTotalPenaltyPaid($loanaccount_id){
        $interestSql="SELECT SUM(penalty_paid) as penalty_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','3','4')";
        $interest=Loanrepayments::model()->findBySql($interestSql);
        if(!empty($interest)){
            $penaltyPaid=$interest->penalty_paid;
        }else{
            $penaltyPaid=0;
        }
        return $penaltyPaid;
    }

    public static function getTotalInterestPaidSpecificDate($loanaccount_id,$startDate){
        $interestSql="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','3','4') AND date ='$startDate'";
        $interest=Loanrepayments::model()->findBySql($interestSql);
        if(!empty($interest)){
            $interestRepaid=$interest->interest_paid;
        }else{
            $interestRepaid=0;
        }
        return $interestRepaid;
    }

    public static function getTotalFeePaidFrom($startDate){
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        $feesQuery="SELECT SUM(loanrepayments.fee_paid) as fee_paid FROM loanrepayments,loanaccounts WHERE loanrepayments.is_void IN('0','3','4')
	 AND loanrepayments.date='$startDate' AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id";
        switch(Yii::app()->user->user_level){
            case '0':
                $feesQuery.="";
                break;

            case '1':
                $feesQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $feesQuery.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $feesQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }
        $fee=Loanrepayments::model()->findBySql($feesQuery);
        if(!empty($fee)){
            $feeRepaid=$fee->fee_paid;
        }else{
            $feeRepaid=0;
        }
        return (int)$feeRepaid;
    }

    public static function getTotalPenaltyPaidFrom($startDate){
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        $penaltyQuery="SELECT SUM(loanrepayments.penalty_paid) as penalty_paid FROM loanrepayments,loanaccounts WHERE loanrepayments.is_void IN('0','3','4')
	 AND loanrepayments.date='$startDate' AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id";
        switch(Yii::app()->user->user_level){
            case '0':
                $penaltyQuery.="";
                break;

            case '1':
                $penaltyQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $penaltyQuery.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $penaltyQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }
        $penalty=Loanrepayments::model()->findBySql($penaltyQuery);
        if(!empty($penalty)){
            $penaltyRepaid=$penalty->penalty_paid;
        }else{
            $penaltyRepaid=0;
        }
        return (int)$penaltyRepaid;
    }

    public static function checkIfLoanPenaltiesApply($loanaccount_id){
        $penaltySQL="SELECT SUM(penalty_amount) AS penalty_amount FROM penaltyaccrued WHERE loanaccount_id=$loanaccount_id AND is_paid='0'";
        $penalty=Penaltyaccrued::model()->findBySql($penaltySQL);
        if(!empty($penalty)){
            $status=$penalty->penalty_amount;
        }else{
            $status=0;
        }
        return $status;
    }

    public static function getAccruedPenalty($loanaccount_id){
        $penaltyQuery="SELECT SUM(penalty_amount) AS penalty_amount FROM penaltyaccrued WHERE loanaccount_id=$loanaccount_id AND is_paid='0'";
        $penalty=Penaltyaccrued::model()->findBySql($penaltyQuery);
        if(!empty($penalty)){
            $penaltyAmount=$penalty->penalty_amount;
        }else{
            $penaltyAmount=0;
        }
        return $penaltyAmount;
    }

    public static function checkIfLoanHasBeenCleared($loanaccount_id){
        $loanBalance=LoanManager::getActualLoanBalance($loanaccount_id);
        if($loanBalance < 1){
            LoanRepayment::loanAccountFullySettled($loanaccount_id);
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }

    public static function loanAccountFullySettled($loanaccount_id){
        $loanBalance=LoanTransactionsFunctions::getTotalLoanBalanceFrom($loanaccount_id,date('Y-m-d'));
        $balance=abs($loanBalance);
        $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
        $loanaccount->loan_status='4';
        $loanaccount->account_status='H';
        $loanaccount->performance_level='A';
        if($loanaccount->save()){
            if(LoanRepayment::checkIfLoanClearanceRecordExists($loanaccount_id) === 0){
                $clear= new Clearedloans;
                $clear->loanaccount_id=$loanaccount_id;
                $clear->date_cleared=date('Y-m-d');
                $clear->overpayment=$balance;
                $clear->save();
            }
        }
    }

    public static function checkIfLoanClearanceRecordExists($loanaccount_id){
        $checkQuery="SELECT * FROM clearedloans WHERE loanaccount_id=$loanaccount_id";
        $records=Clearedloans::model()->findAllBySql($checkQuery);
        if(!empty($records)){
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }

    public static function updateLoanRepayment($id,$data){
        $loanaccount_id=$data['Loanrepayments']['loanaccount_id'];
        $date=$data['Loanrepayments']['date'];
        $principalPaid=$data['Loanrepayments']['principal_paid'];
        $interestPaid=$data['Loanrepayments']['interest_paid'];
        $feePaid=$data['Loanrepayments']['fee_paid'];
        $penaltyPaid=$data['Loanrepayments']['penalty_paid'];
        $totalPaid=$principalPaid+$interestPaid+$feePaid+$penaltyPaid;
        $loanrepayment=Loanrepayments::model()->findByPk($id);
        $loanrepayment->loanaccount_id=$loanaccount_id;
        $loanrepayment->date=$date;
        $loanrepayment->principal_paid=$principalPaid;
        $loanrepayment->interest_paid=$interestPaid;
        $loanrepayment->fee_paid=$feePaid;
        $loanrepayment->penalty_paid=$penaltyPaid;
        if($loanrepayment->save()){
            $loantransact=Loantransactions::model()->findByPk($loanrepayment->loantransaction_id);
            $loantransact->amount=$totalPaid;
            $loantransact->save();
            $status=1;
        }else{
            $status=0;
        }
        return $status;
    }

    public static function getAllLoanRepayments($loanaccount_id){
        $repaymentsSql="SELECT * FROM loanrepayments WHERE loanaccount_id=$loanaccount_id
	 AND is_void IN('0','3','4')";
        $repayments=Loanrepayments::model()->findAllBySql($repaymentsSql);
        return $repayments;
    }

    public static function voidPenaltyPaid($penaltyPaid,$accountID){
        if($penaltyPaid > 0){
            LoanRepayment::createPenalty($accountID,$penaltyPaid);
        }
    }

    public static function voidLoanRepayment($loanrepayment_id){
        $loanrepayment=Loanrepayments::model()->findByPk($loanrepayment_id);
        if(!empty($loanrepayment)){
            $loanrepayment->is_void='1';
            if($loanrepayment->save()){
                $penaltyPaid=$loanrepayment->penalty_paid;
                $accruedInterestAmount=$loanrepayment->interest_paid;
                $accountID=$loanrepayment->loanaccount_id;
                LoanRepayment::voidPenaltyPaid($penaltyPaid,$accountID);
                LoanTransactionsFunctions::recordAccruedInterestNotPaid($accountID,$accruedInterestAmount);
                $voidedArrears=$loanrepayment->fee_paid;
                $loanaccount=Loanaccounts::model()->findByPk($loanrepayment->loanaccount_id);
                $currentArrears=$loanaccount->arrears;
                $newArrears=$currentArrears + $voidedArrears;
                $loanaccount->arrears=$newArrears;
                $loanaccount->save();
                $transaction=Loantransactions::model()->findByPk($loanrepayment->loantransaction_id);
                $transaction->is_void='1';
                $transaction->save();
                //Check Loan Balance and Revert Status
                $balance=LoanTransactionsFunctions::getTotalLoanBalance($loanrepayment->loanaccount_id);
                if($balance > 0){
                    $loanaccount=LoanApplication::getLoanAccount($loanrepayment->loanaccount_id);
                    $loanaccount->loan_status='2';
                    $loanaccount->save();
                    Yii::app()->db->createCommand("DELETE FROM clearedloans WHERE loanaccount_id={$loanrepayment->loanaccount_id}")->execute();
                }
                $status=1;
            }else{
                $loanrepayment->is_void='0';
                $loanrepayment->save();
                $status=2;
            }
        }else{
            $status=0;
        }
        return $status;
    }

    public static function getDailyCollectionSheet($start_date,$end_date,$created_by){
        $userBranch=Yii::app()->user->user_branch;
        $userLevel=Yii::app()->user->user_level;
        switch($userLevel){
            case '0':
                if($created_by === '0'){
                    $collectionSQL="SELECT * FROM loanrepayments,loantransactions,loanaccounts WHERE
				loanrepayments.date BETWEEN '$start_date' AND '$end_date' AND loanrepayments.is_void IN('0','3','4')
				AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanrepayments.loantransaction_id=loantransactions.loantransaction_id
				AND loanaccounts.loan_status NOT IN('3')";
                    $collections=Loanrepayments::model()->findAllBySql($collectionSQL);
                    return $collections;
                }else{
                    $collectionSQL="SELECT * FROM loanrepayments,loantransactions,loanaccounts
				WHERE loanrepayments.date BETWEEN '$start_date' AND '$end_date'
				AND loanrepayments.is_void IN('0','3','4') AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id
				AND loanrepayments.loantransaction_id=loantransactions.loantransaction_id  AND loanrepayments.repaid_by=$created_by
				AND loanaccounts.loan_status NOT IN('3')";
                    $collections=Loanrepayments::model()->findAllBySql($collectionSQL);
                    return $collections;
                }
                break;

            case '1':
                if($created_by === '0'){
                    $collectionSQL="SELECT * FROM loanrepayments,loantransactions,loanaccounts
				WHERE loanrepayments.date BETWEEN '$start_date' AND '$end_date' AND loanrepayments.is_void IN('0','3','4')
				AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanrepayments.loantransaction_id=loantransactions.loantransaction_id
				AND loanaccounts.loan_status NOT IN('3') AND loanaccounts.branch_id=$userBranch";
                    $collections=Loanrepayments::model()->findAllBySql($collectionSQL);
                    return $collections;
                }else{
                    $collectionSQL="SELECT * FROM loanrepayments,loantransactions,loanaccounts
				WHERE loanrepayments.date BETWEEN '$start_date' AND '$end_date' AND loanrepayments.is_void IN('0','3','4')
				AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanrepayments.loantransaction_id=loantransactions.loantransaction_id
				AND loanrepayments.repaid_by=$created_by AND loanaccounts.loan_status NOT IN('3') AND loanaccounts.branch_id=$userBranch";
                    $collections=Loanrepayments::model()->findAllBySql($collectionSQL);
                    return $collections;
                }
                break;

            case '2':
                if($created_by === '0'){
                    $collectionSQL="SELECT * FROM loanrepayments,loantransactions,loanaccounts
				WHERE loanrepayments.date BETWEEN '$start_date' AND '$end_date' AND loanrepayments.is_void IN('0','3','4')
				AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanrepayments.loantransaction_id=loantransactions.loantransaction_id
				AND loanaccounts.loan_status NOT IN('3') AND loanaccounts.branch_id=$userBranch";
                    $collections=Loanrepayments::model()->findAllBySql($collectionSQL);
                    return $collections;
                }else{
                    $collectionSQL="SELECT * FROM loanrepayments,loantransactions,loanaccounts
				WHERE loanrepayments.date BETWEEN '$start_date' AND '$end_date' AND loanrepayments.is_void IN('0','3','4')
				AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanrepayments.loantransaction_id=loantransactions.loantransaction_id 
				AND loanrepayments.repaid_by=$created_by AND loanaccounts.loan_status NOT IN('3') AND loanaccounts.branch_id=$userBranch";
                    $collections=Loanrepayments::model()->findAllBySql($collectionSQL);
                    return $collections;
                }
                break;
        }
    }

    public static function getLoansPastMaturityCollectionSheet($start_date,$end_date,$created_by){
        if($created_by === '0'){
            $collectionSQL="SELECT * FROM loanrepayments,loantransactions,loanaccounts,loan_maturities WHERE loanrepayments.date BETWEEN '$start_date' AND '$end_date' AND loanrepayments.is_void IN('0','3','4')AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanrepayments.loantransaction_id=loantransactions.loantransaction_id  AND loanaccounts.loan_status NOT IN('3') AND loan_maturities.maturity_date < '$end_date'";
        }else{
            $collectionSQL="SELECT * FROM loanrepayments,loantransactions,loanaccounts,loan_maturities WHERE loanrepayments.date BETWEEN '$start_date' AND '$end_date' AND loanrepayments.is_void IN('0','3','4')AND loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanrepayments.loantransaction_id=loantransactions.loantransaction_id AND loanrepayments.repaid_by=$created_by AND loanaccounts.loan_status NOT IN('3') AND loan_maturities.maturity_date < '$end_date'";
        }
        $collections=Loanrepayments::model()->findAllBySql($collectionSQL);
        return $collections;
    }

    public static function freezeRepayments($loanaccount_id,$type){
        $repaymentSQL="SELECT * FROM loanrepayments WHERE loanaccount_id=$loanaccount_id AND is_void='0'";
        $repayments=Loanrepayments::model()->findAllBySql($repaymentSQL);
        if(!empty($repayments)){
            foreach($repayments as $repayment){
                $repayment->is_void=$type;
                $repayment->save();
            }
        }
    }

}