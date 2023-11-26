<?php

class Dashboard{
	/**********************
	 
	 DASHBOARD STATS 

	*************************/
	public static function LoadFilteredBorrowersCount($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$user=Yii::app()->user->user_id;
		if($showAll == 'all'){
		 $countingQuery="SELECT COUNT(DISTINCT id) as id FROM profiles WHERE profileType IN('MEMBER')";
		}else{
		 $countingQuery="SELECT COUNT(DISTINCT id) as id FROM profiles WHERE profileType IN('MEMBER') AND (DATE(createdAt) BETWEEN '$startDate' AND '$endDate')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$countingQuery.=" ";
			break;

			case '1':
			$countingQuery.=" AND branchId=$userBranch ";
			break;

			case '2':
			$countingQuery.=" AND managerId=$user";
			break;

			default:
			$countingQuery.=" AND id=$user";
			break;
		}
		return Dashboard::getTotalBorrowersCount($branch,$staff,$borrower,$countingQuery);
	}

	public static function getTotalBorrowersCount($branch,$staff,$borrower,$countingQuery){
		if($branch != 0){
			$countingQuery.=" AND branchId=$branch";
		}

		if($staff != 0){
			$countingQuery.=" AND managerId=$staff";
		}

		if($borrower != 0){
			$countingQuery.=" AND id=$borrower";
		}

		$borrowers = Profiles::model()->findBySql($countingQuery);
		return !empty($borrowers) ? $borrowers->id : 0;
	}

	public static function getTotalSavingTransactions(){
		$depositQuery   = "SELECT SUM(amount) as amount FROM savingtransactions WHERE type='credit' AND is_void='0'";
		$deposits       = Savingtransactions::model()->findBySql($depositQuery);
		$savingDeposits = !empty($deposits) ? $deposits->amount : 0;
		$withdrawQuery  = "SELECT SUM(amount) as amount FROM savingtransactions WHERE type='debit' AND is_void='0' ";
		$withdrawals      = Savingtransactions::model()->findBySql($withdrawQuery);
		$savingWithdrawals= !empty($withdrawals) ? $withdrawals->amount : 0;
		$totalSavings     = $savingDeposits-$savingWithdrawals;
		return CommonFunctions::asMoney($totalSavings);
	}

	public static function getTotalDisbursedLoansCount(){
		$userBranch = Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			if($userBranch === 0){
				$disbursedLoanSql="SELECT COUNT(DISTINCT loanaccount_id) as loanaccount_id FROM disbursed_loans";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedLoanSql);
				if(!empty($disbursedLoans)){
					$disbursedLoansCount=$disbursedLoans->loanaccount_id;
					return $disbursedLoansCount;
				}else{
					$disbursedLoansCount=0;
					return $disbursedLoansCount;
				}
			}else{
				$disbursedLoanSql="SELECT count(DISTINCT disbursed_loans.loanaccount_id) as loanaccount_id FROM disbursed_loans,loanaccounts
				WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.branch_id=$userBranch";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedLoanSql);
				if(!empty($disbursedLoans)){
					$disbursedLoansCount=$disbursedLoans->loanaccount_id;
					return $disbursedLoansCount;
				}else{
					$disbursedLoansCount=0;
					return $disbursedLoansCount;
				}
			}
			break;

			case '1':
			$disbursedLoanSql="SELECT count(DISTINCT disbursed_loans.loanaccount_id) AS loanaccount_id FROM disbursed_loans,loanaccounts
			WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.branch_id=$userBranch";
			$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedLoanSql);
			if(!empty($disbursedLoans)){
				$disbursedLoansCount=$disbursedLoans->loanaccount_id;
				return $disbursedLoansCount;
			}else{
				$disbursedLoansCount=0;
				return $disbursedLoansCount;
			}
			break;

			case '2':
			$disbursedLoanSql="SELECT count(DISTINCT disbursed_loans.loanaccount_id) AS loanaccount_id FROM disbursed_loans,loanaccounts
			WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.branch_id=$userBranch";
			$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedLoanSql);
			if(!empty($disbursedLoans)){
				$disbursedLoansCount=$disbursedLoans->loanaccount_id;
				return $disbursedLoansCount;
			}else{
				$disbursedLoansCount=0;
				return $disbursedLoansCount;
			}
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$disbursedLoanSql="SELECT count(disbursed_loans.loanaccount_id) AS loanaccount_id FROM disbursed_loans,loanaccounts
			WHERE loanaccounts.user_id=$userID AND disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id";
			$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedLoanSql);
			if(!empty($disbursedLoans)){
				$disbursedLoansCount=$disbursedLoans->loanaccount_id;
				return $disbursedLoansCount;
			}else{
				$disbursedLoansCount=0;
				return $disbursedLoansCount;
			}
			break;
		}
	}

	public static function getTotalFullyPaidLoans($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loans=Dashboard::getTotalFullyPaidLoansCount($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loans'] = $loans;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getTotalFullyPaidLoansCount($startDate){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			if($userBranch === 0){
				$clearedLoanSQL="SELECT count(loanaccount_id) as loanaccount_id FROM clearedloans WHERE
				 date_cleared='$startDate'";
				$clearedLoans=Clearedloans::model()->findBySql($clearedLoanSQL);
				if(!empty($clearedLoans)){
					$clearedLoansCount=$clearedLoans->loanaccount_id;
					return $clearedLoansCount;
				}else{
					$clearedLoansCount=0;
					return $clearedLoansCount;
				}
			}else{
				$clearedLoanSQL="SELECT count(clearedloans.loanaccount_id) as loanaccount_id FROM clearedloans,loanaccounts
				WHERE clearedloans.date_cleared='$startDate' AND loanaccounts.loanaccount_id= clearedloans.loanaccount_id AND loanaccounts.branch_id=$userBranch";
				$clearedLoans=Clearedloans::model()->findBySql($clearedLoanSQL);
				if(!empty($clearedLoans)){
					$clearedLoansCount=$clearedLoans->loanaccount_id;
					return $clearedLoansCount;
				}else{
					$clearedLoansCount=0;
					return $clearedLoansCount;
				}
			}
			break;

			case '1':
			$clearedLoanSQL="SELECT count(clearedloans.loanaccount_id) AS loanaccount_id FROM clearedloans,loanaccounts
			WHERE clearedloans.date_cleared='$startDate' AND loanaccounts.loanaccount_id=clearedloans.loanaccount_id AND loanaccounts.branch_id=$userBranch";
			$clearedLoans=Clearedloans::model()->findBySql($clearedLoanSQL);
			if(!empty($clearedLoans)){
				$clearedLoansCount=$clearedLoans->loanaccount_id;
				return $clearedLoansCount;
			}else{
				$clearedLoansCount=0;
				return $clearedLoansCount;
			}
			break;

			case '2':
			$clearedLoanSQL="SELECT count(clearedloans.loanaccount_id) AS loanaccount_id FROM clearedloans,loanaccounts
			WHERE clearedloans.date_cleared='$startDate' AND loanaccounts.loanaccount_id=clearedloans.loanaccount_id AND loanaccounts.branch_id=$userBranch";
			$clearedLoans=Clearedloans::model()->findBySql($clearedLoanSQL);
			if(!empty($clearedLoans)){
				$clearedLoansCount=$clearedLoans->loanaccount_id;
				return $clearedLoansCount;
			}else{
				$clearedLoansCount=0;
				return $clearedLoansCount;
			}
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$clearedLoanSQL="SELECT count(clearedloans.loanaccount_id) AS loanaccount_id FROM clearedloans,loanaccounts
			WHERE clearedloans.date_cleared='$startDate' AND loanaccounts.loanaccount_id=clearedloans.loanaccount_id AND loanaccounts.user_id=$userID";
			$clearedLoans=Clearedloans::model()->findBySql($clearedLoanSQL);
			if(!empty($clearedLoans)){
				$clearedLoansCount=$clearedLoans->loanaccount_id;
				return $clearedLoansCount;
			}else{
				$clearedLoansCount=0;
				return $clearedLoansCount;
			}
			break;
		}
	}

	public static function LoadFilteredTotalLoanRepaymentsTransactions($branch,$startDate,$endDate,$staff,
		$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		if($showAll =='all'){
			$transactionQuery="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanrepayments,loanaccounts
			WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
			AND loantransactions.is_void IN('0','3','4')";
		}else{
			$transactionQuery="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanrepayments,loanaccounts
			WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
			AND (DATE(loantransactions.transacted_at) BETWEEN '$startDate' AND '$endDate') AND loantransactions.is_void IN('0','3','4')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$transactionQuery.="";
			break;

			case '1':
			$transactionQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$transactionQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$transactionQuery.=" AND loanaccounts.user_id=$userID ";
			break;
		}
		return Dashboard::getTotalRepayments($branch,$staff,$borrower,$transactionQuery);
	}

	public static function getTotalRepayments($branch,$staff,$borrower,$transactionQuery){
		if($branch != 0){
			$transactionQuery.=" AND loanaccounts.branch_id=$branch";
		}

		if($staff != 0){
			$transactionQuery.=" AND loanaccounts.rm=$staff";
		}

		if($borrower != 0){
			$transactionQuery.=" AND loanaccounts.user_id=$borrower";
		}

		$transactions=Loantransactions::model()->findBySql($transactionQuery);
		if(!empty($transactions)){
			$totalRepayments=$transactions->amount;
		}else{
			$totalRepayments=0;
		}
		return CommonFunctions::asMoney($totalRepayments);
	}

	public static function LoadFilteredLoansInQueueCount($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch = Yii::app()->user->user_branch;
		$userID     = Yii::app()->user->user_branch;
		if($showAll =='all'){
			$queuedQuery = "SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE 
			loan_status IN('2','5','6','7')";
		}else{
			$queuedQuery = "SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE 
			loan_status IN('2','5','6','7') AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$queuedQuery.="";
			break;

			case '1':
			$queuedQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$queuedQuery.=" AND rm=$userID ";
			break;

			case '3':
			$queuedQuery.=" AND user_id=$userID";
			break;
		}
		return Dashboard::getLoansEnqueue($branch,$staff,$borrower,$queuedQuery);
	}

	public static function getLoansEnqueue($branch,$staff,$borrower,$queuedQuery){
		if($branch !=0){
			$queuedQuery.=" AND branch_id=$branch";
		}

		if($staff !=0){
			$queuedQuery.=" AND rm=$staff";
		}

		if($borrower !=0){
			$queuedQuery.=" AND user_id=$borrower";
		}

		$openLoans = Loanaccounts::model()->findBySql($queuedQuery);
		return !empty($openLoans) ? $openLoans->loanaccount_id : 0;
	}

	public static function LoadFilteredFullySettledLoansCount($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		if($showAll =='all'){
		$settledQuery="SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE loan_status IN('4')";
		}else{
		$settledQuery="SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
		AND loan_status IN('4')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$settledQuery.="";
			break;

			case '1':
			$userBranch=Yii::app()->user->user_branch;
			$settledQuery.=" AND branch_id=$userBranch ";
			break;

			case '2':
			$userID=Yii::app()->user->user_id;
			$settledQuery.=" AND rm=$userID ";
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$settledQuery.=" AND user_id=$userID";
			break;
		}
		return Dashboard::getSettledLoansCount($branch,$staff,$borrower,$settledQuery);
	}

	public static function getSettledLoansCount($branch,$staff,$borrower,$settledQuery){
		if($branch != 0){
			$settledQuery.=" AND branch_id=$branch";
		}

		if($staff != 0){
			$settledQuery.=" AND rm=$staff";
		}

		if($borrower != 0){
			$settledQuery.=" AND user_id=$borrower";
		}

		$settledLoans=Loanaccounts::model()->findBySql($settledQuery);
		if(!empty($settledLoans)){
			$settledLoansCount=$settledLoans->loanaccount_id;
		}else{
			$settledLoansCount=0;
		}
		return $settledLoansCount;
	}

	public static function LoadPaymentCounts($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		if($showAll =='all'){
			$transactionQuery="SELECT COUNT(loantransactions.loantransaction_id) as loantransaction_id FROM loantransactions,loanrepayments,loanaccounts
			WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
			AND loantransactions.is_void IN('0','3','4')";
		}else{
			$transactionQuery="SELECT COUNT(loantransactions.loantransaction_id) as loantransaction_id FROM loantransactions,loanrepayments,loanaccounts
			WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
			AND (DATE(loantransactions.transacted_at) BETWEEN '$startDate' AND '$endDate') AND loantransactions.is_void IN('0','3','4')";
		}
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$transactionQuery.="";
			break;

			case '1':
			$transactionQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$transactionQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$transactionQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}
		return Dashboard::getPaymentCounts($branch,$staff,$borrower,$transactionQuery);
	}

	public static function getPaymentCounts($branch,$staff,$borrower,$transactionQuery){
		if($branch != 0){
			$transactionQuery.=" AND loanaccounts.branch_id=$branch";
		}
		if($staff != 0){
			$transactionQuery.=" AND loanaccounts.rm=$staff";
		}
		if($borrower != 0){
			$transactionQuery.=" AND loanaccounts.user_id=$borrower";
		}
		$payments=Loantransactions::model()->findBySql($transactionQuery);
		if(!empty($payments)){
			$paymentsCount=$payments->loantransaction_id;
		}else{
			$paymentsCount=0;
		}
		return $paymentsCount;
	}

	public static function LoadFilteredTotalDefaultedLoans($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		if($showAll =='all'){
			$defaultQuery="SELECT * FROM loanaccounts WHERE loan_status IN('7')";
		}else{
			$defaultQuery="SELECT * FROM loanaccounts  WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status IN('7')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$defaultQuery.="";
			break;

			case '1':
			$userBranch=Yii::app()->user->user_branch;
			$defaultQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$userID=Yii::app()->user->user_id;
			$defaultQuery.=" AND rm=$userID";
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$defaultQuery.=" AND user_id=$userID ";
			break;
		}
		return Dashboard::getTotalDefaultedLoans($branch,$staff,$borrower,$defaultQuery);
	}

	public static function getTotalDefaultedLoans($branch,$staff,$borrower,$defaultQuery){
		if($branch != 0){
			$defaultQuery.=" AND branch_id=$branch";
		}
		if($staff != 0){
			$defaultQuery.=" AND rm=$staff";
		}
		if($borrower != 0){
			$defaultQuery.=" AND user_id=$borrower";
		}
		$defaultLoans=Loanaccounts::model()->findAllBySql($defaultQuery);
		if(!empty($defaultLoans)){
			$defaultLoansCount=count($defaultLoans);
		}else{
			$defaultLoansCount=0;
		}
		return $defaultLoansCount;
	}

	public static function LoadFilteredSavingsLessAccruedInterest($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$savingsBalance=Dashboard::LoadFilteredSavingsBalanceBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll);
		$accruedInterest=Dashboard::LoadFilteredAccruedSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll);
		$depositedSavings=$savingsBalance;
		if($depositedSavings<=0){
			$depositedSavings=0;
		}
		return $depositedSavings;
	}

	public static function LoadFilteredSavingsBalanceBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$deposits=Dashboard::LoadFilteredDepositedSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll);
		$withdrawals=Dashboard::LoadFilteredWithdrewSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll);
		$savingsBalance=$deposits - $withdrawals;
		if($savingsBalance<=0){
			$savingsBalance=0;
		}
		return $savingsBalance;
	}

	public static function LoadFilteredSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userID=Yii::app()->user->user_id;
		$userBranch=Yii::app()->user->user_branch;
		if($showAll =='all'){
		  $defaultQuery="SELECT COALESCE(SUM(savingtransactions.amount),0) AS amount FROM savingtransactions,savingaccounts
		  WHERE savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.is_void='0' AND savingtransactions.type='credit'";
		}else{
			$defaultQuery="SELECT COALESCE(SUM(savingtransactions.amount),0) AS amount FROM savingtransactions,savingaccounts
			WHERE savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.type='credit'
			AND (DATE(savingtransactions.transacted_at) BETWEEN '$startDate' AND '$endDate') AND savingtransactions.is_void='0'";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$defaultQuery.="";
			break;

			case '1':
			$defaultQuery.=" AND savingaccounts.branch_id=$userBranch";
			break;

			case '2':
			$defaultQuery.=" AND savingaccounts.rm=$userID";
			break;

			case '3':
			$defaultQuery.=" AND savingaccounts.user_id=$userID ";
			break;
		}
		return Dashboard::getTotalFilteredSavingsBranchDate($branch,$staff,$borrower,$defaultQuery);
	}

	public static function LoadFilteredDepositedSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userID=Yii::app()->user->user_id;
		$userBranch=Yii::app()->user->user_branch;
		if($showAll =='all'){
		  $defaultQuery="SELECT COALESCE(SUM(savingtransactions.amount),0) AS amount FROM savingtransactions,savingaccounts
		  WHERE savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.is_void IN('0') AND savingtransactions.type='credit'";
		}else{
			$defaultQuery="SELECT COALESCE(SUM(savingtransactions.amount),0) AS amount FROM savingtransactions,savingaccounts 
			WHERE savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.type='credit'
			AND (DATE(savingtransactions.transacted_at) BETWEEN '$startDate' AND '$endDate') AND savingtransactions.is_void IN('0')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$defaultQuery.="";
			break;

			case '1':
			$defaultQuery.=" AND savingaccounts.branch_id=$userBranch";
			break;

			case '2':
			$defaultQuery.=" AND savingaccounts.rm=$userID";
			break;

			case '3':
			$defaultQuery.=" AND savingaccounts.user_id=$userID ";
			break;
		}
		return Dashboard::getTotalFilteredDepositedSavingsBranchDate($branch,$staff,$borrower,$defaultQuery);
	}

	public static function getTotalFilteredDepositedSavingsBranchDate($branch,$staff,$borrower,$defaultQuery){
		if($branch != 0){
			$defaultQuery.=" AND savingaccounts.branch_id=$branch";
		}
		if($staff != 0){
			$defaultQuery.=" AND savingaccounts.rm=$staff";
		}
		if($borrower != 0){
			$defaultQuery.=" AND savingaccounts.user_id=$borrower";
		}
		$savingsAmount=Savingtransactions::model()->findBySql($defaultQuery);
		if(!empty($savingsAmount)){
			$savingsAmount=$savingsAmount->amount;
		}else{
			$savingsAmount=0;
		}
		return $savingsAmount;
	}

	public static function LoadFilteredWithdrewSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userID=Yii::app()->user->user_id;
		$userBranch=Yii::app()->user->user_branch;
		if($showAll =='all'){
		  $defaultQuery="SELECT COALESCE(SUM(savingtransactions.amount),0) AS amount FROM savingtransactions,savingaccounts
		   WHERE savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.is_void IN('0') AND savingtransactions.type='debit'";
		}else{
			$defaultQuery="SELECT COALESCE(SUM(savingtransactions.amount),0) AS amount FROM savingtransactions,savingaccounts
			WHERE savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.type='debit'
			AND (DATE(savingtransactions.transacted_at) BETWEEN '$startDate' AND '$endDate') AND savingtransactions.is_void IN('0')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$defaultQuery.="";
			break;

			case '1':
			$defaultQuery.=" AND savingaccounts.branch_id=$userBranch";
			break;

			case '2':
			$defaultQuery.=" AND savingaccounts.rm=$userID";
			break;

			case '3':
			$defaultQuery.=" AND savingaccounts.user_id=$userID ";
			break;
		}
		return Dashboard::getTotalFilteredWithdrewSavingsBranchDate($branch,$staff,$borrower,$defaultQuery);
	}

	public static function getTotalFilteredWithdrewSavingsBranchDate($branch,$staff,$borrower,$defaultQuery){
		if($branch != 0){
			$defaultQuery.=" AND savingaccounts.branch_id=$branch";
		}
		if($staff != 0){
			$defaultQuery.=" AND savingaccounts.rm=$staff";
		}
		if($borrower != 0){
			$defaultQuery.=" AND savingaccounts.user_id=$borrower";
		}
		$savingsAmount=Savingtransactions::model()->findBySql($defaultQuery);
		if(!empty($savingsAmount)){
			$savingsAmount=$savingsAmount->amount;
		}else{
			$savingsAmount=0;
		}
		return $savingsAmount;
	}

	public static function LoadFilteredAccruedDebitSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		if($showAll =='all'){
		  $defaultQuery="SELECT SUM(savingpostings.posted_interest) as posted_interest FROM savingpostings,savingtransactions,savingaccounts WHERE savingtransactions.savingtransaction_id=savingpostings.savingtransaction_id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingpostings.is_void='0' AND savingpostings.type='debit'";
		}else{
			$defaultQuery="SELECT SUM(savingpostings.posted_interest) as posted_interest FROM savingpostings,savingtransactions,savingaccounts WHERE savingtransactions.savingtransaction_id=savingpostings.savingtransaction_id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND (DATE(savingpostings.posted_at) BETWEEN '$startDate' AND '$endDate') AND savingpostings.is_void='0' AND savingpostings.type='debit'";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$defaultQuery.="";
			break;

			case '1':
			$defaultQuery.=" AND savingaccounts.branch_id=$userBranch";
			break;

			case '2':
			$defaultQuery.=" AND savingaccounts.rm=$userID";
			break;

			case '3':
			$defaultQuery.=" AND savingaccounts.user_id=$userID ";
			break;
		}
		return Dashboard::getTotalFilteredAccruedDebitSavingsBranchDate($branch,$staff,$borrower,$defaultQuery);
	}

	public static function getTotalFilteredAccruedDebitSavingsBranchDate($branch,$staff,$borrower,$defaultQuery){
		if($branch != 0){
			$defaultQuery.=" AND savingaccounts.branch_id=$branch";
		}
		if($staff != 0){
			$defaultQuery.=" AND savingaccounts.rm=$staff";
		}
		if($borrower != 0){
			$defaultQuery.=" AND savingaccounts.user_id=$borrower";
		}
		$savingsAmount=Savingpostings::model()->findBySql($defaultQuery);
		if(!empty($savingsAmount)){
			$savingsAmount=$savingsAmount->posted_interest;
		}else{
			$savingsAmount=0;
		}
		return $savingsAmount;
	}

	public static function LoadFilteredAccruedCreditSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		if($showAll =='all'){
		  $defaultQuery="SELECT SUM(savingpostings.posted_interest) as posted_interest FROM savingpostings,savingtransactions,savingaccounts WHERE savingtransactions.savingtransaction_id=savingpostings.savingtransaction_id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingpostings.is_void='0' AND savingpostings.type='credit'";
		}else{
			$defaultQuery="SELECT SUM(savingpostings.posted_interest) as posted_interest FROM savingpostings,savingtransactions,savingaccounts WHERE savingtransactions.savingtransaction_id=savingpostings.savingtransaction_id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND (DATE(savingpostings.posted_at) BETWEEN '$startDate' AND '$endDate') AND savingpostings.is_void='0' AND savingpostings.type='credit'";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$defaultQuery.="";
			break;

			case '1':
			$defaultQuery.=" AND savingaccounts.branch_id=$userBranch";
			break;

			case '2':
			$defaultQuery.=" AND savingaccounts.rm=$userID";
			break;

			case '3':
			$defaultQuery.=" AND savingaccounts.user_id=$userID ";
			break;
		}
		return Dashboard::getTotalFilteredAccruedCreditSavingsBranchDate($branch,$staff,$borrower,$defaultQuery);
	}

	public static function getTotalFilteredAccruedCreditSavingsBranchDate($branch,$staff,$borrower,$defaultQuery){
		if($branch != 0){
			$defaultQuery.=" AND savingaccounts.branch_id=$branch";
		}
		if($staff != 0){
			$defaultQuery.=" AND savingaccounts.rm=$staff";
		}
		if($borrower != 0){
			$defaultQuery.=" AND savingaccounts.user_id=$borrower";
		}
		$savingsAmount=Savingpostings::model()->findBySql($defaultQuery);
		if(!empty($savingsAmount)){
			$savingsAmount=$savingsAmount->posted_interest;
		}else{
			$savingsAmount=0;
		}
		return $savingsAmount;
	}

	public static function LoadFilteredAccruedSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$credits=Dashboard::LoadFilteredAccruedCreditSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll);
		$debits=Dashboard::LoadFilteredAccruedDebitSavingsBranchDate($branch,$startDate,$endDate,$staff,$borrower,$showAll);
		$accruedBalance=$credits-$debits;
		if($accruedBalance<= 0){
			$accruedBalance=0;
		}
		return $accruedBalance;
	}
	/**************************************************
		LOAN STATISTICS
	*********************************************/
	public static function LoadFilteredTotalPrincipalReleased($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		if($showAll =='all'){
		 $principalReleasedQuery="SELECT COALESCE(SUM(disbursed_loans.amount_disbursed),0) as amount_disbursed FROM disbursed_loans,loanaccounts
		 WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id";
		}else{
		 $principalReleasedQuery="SELECT COALESCE(SUM(disbursed_loans.amount_disbursed),0) as amount_disbursed FROM disbursed_loans,loanaccounts
		 WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$principalReleasedQuery.="";
			break;

			case '1':
			$userBranch=Yii::app()->user->user_branch;
			$principalReleasedQuery.=" AND loanaccounts.branch_id=$userBranch ";
			break;

			case '2':
			$userID=Yii::app()->user->user_id;
			$principalReleasedQuery.=" AND loanaccounts.rm=$userID ";
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$principalReleasedQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}
		return Dashboard::getTotalPrincipalReleased($branch,$staff,$borrower,$principalReleasedQuery);
	}

	public static function getTotalPrincipalReleased($branch,$staff,$borrower,$principalReleasedQuery){
		if($branch != 0){
			$principalReleasedQuery.=" AND loanaccounts.branch_id=$branch";
		}
		if($staff != 0){
			$principalReleasedQuery.=" AND loanaccounts.rm=$staff";
		}
		if($borrower != 0){
			$principalReleasedQuery.=" AND loanaccounts.user_id=$borrower";
		}
		$totalPrincipal=DisbursedLoans::model()->findBySql($principalReleasedQuery);
		if(!empty($totalPrincipal)){
			$totalPrincipalReleased=$totalPrincipal->amount_disbursed;
		}else{
			$totalPrincipalReleased=0;
		}
		return CommonFunctions::asMoney($totalPrincipalReleased);
	}

	public static function getTotalOutstandingOpenLoans(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			if($userBranch === 0){
				$loanaccountsSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10') ";
				$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
				if(!empty($loanaccounts)){
					$totalLoanBalance=0;
					foreach($loanaccounts as $loan){
						$totalLoanBalance+=LoanTransactionsFunctions::getTotalLoanBalance($loan->loanaccount_id);
					}
					return CommonFunctions::asMoney($totalLoanBalance);
				}else{
					$totalLoanBalance=0;
					return CommonFunctions::asMoney($totalLoanBalance);
				}
			}else{
				$loanaccountsSql="SELECT * FROM loanaccounts WHERE branch_id=$userBranch AND loan_status NOT IN('0','1','3','4','8','9','10') ";
				$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
				if(!empty($loanaccounts)){
					$totalLoanBalance=0;
					foreach($loanaccounts as $loan){
						$totalLoanBalance+=LoanTransactionsFunctions::getTotalLoanBalance($loan->loanaccount_id);
					}
					return CommonFunctions::asMoney($totalLoanBalance);
				}else{
					$totalLoanBalance=0;
					return CommonFunctions::asMoney($totalLoanBalance);
				}
			}
			break;

			case '1':
			$loanaccountsSql="SELECT * FROM loanaccounts WHERE branch_id=$userBranch AND loan_status NOT IN('0','1','3','4','8','9','10') ";
			$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
			if(!empty($loanaccounts)){
				$totalLoanBalance=0;
				foreach($loanaccounts as $loan){
					$totalLoanBalance+=LoanTransactionsFunctions::getTotalLoanBalance($loan->loanaccount_id);
				}
				return CommonFunctions::asMoney($totalLoanBalance);
			}else{
				$totalLoanBalance=0;
				return CommonFunctions::asMoney($totalLoanBalance);
			}
			break;

			case '2':
			$userID=Yii::app()->user->user_id;
			$loanaccountsSql="SELECT * FROM loanaccounts WHERE branch_id=$userBranch AND loan_status NOT IN('0','1','3','4')
			 AND rm=$userID";
			$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
			if(!empty($loanaccounts)){
				$totalLoanBalance=0;
				foreach($loanaccounts as $loan){
					$totalLoanBalance+=LoanTransactionsFunctions::getTotalLoanBalance($loan->loanaccount_id);
				}
				return CommonFunctions::asMoney($totalLoanBalance);
			}else{
				$totalLoanBalance=0;
				return CommonFunctions::asMoney($totalLoanBalance);
			}
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$loanaccountsSql="SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status
			 NOT IN('0','1','3','4','8','9','10') ";
			$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
			if(!empty($loanaccounts)){
				$totalLoanBalance=0;
				foreach($loanaccounts as $loan){
					$totalLoanBalance+=LoanTransactionsFunctions::getTotalLoanBalance($loan->loanaccount_id);
				}
				return CommonFunctions::asMoney($totalLoanBalance);
			}else{
				$totalLoanBalance=0;
				return CommonFunctions::asMoney($totalLoanBalance);
			}
			break;
		}
	}

	public static function LoadFilteredPrincipalOutstandingOpenLoans($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		if($showAll =='all'){
		 $principalOutstandingQuery="SELECT loanaccount_id FROM loanaccounts WHERE
		  loan_status NOT IN('0','1','3','8','9','10')";
		}else{
			$principalOutstandingQuery="SELECT loanaccount_id FROM loanaccounts WHERE
			 loan_status NOT IN('0','1','3','8','9','10') AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$principalOutstandingQuery.="";
			break;

			case '1':
			$principalOutstandingQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$principalOutstandingQuery.=" AND rm=$userID ";
			break;

			case '3':
			$principalOutstandingQuery.=" AND user_id=$userID";
			break;
		}
		return Dashboard::getTotalPrincipalOutStanding($branch,$staff,$borrower,$principalOutstandingQuery);
	}

	public static function getTotalPrincipalOutStanding($branch,$staff,$borrower,$principalOutstandingQuery){
		if($branch != 0){
			$principalOutstandingQuery.=" AND branch_id=$branch";
		}
		if($staff != 0){
			$principalOutstandingQuery.=" AND rm=$staff";
		}
		if($borrower != 0){
			$principalOutstandingQuery.=" AND user_id=$borrower";
		}
		$accounts=Yii::app()->db->createCommand($principalOutstandingQuery)->queryAll();
		if(!empty($accounts)){
			$totalPrincipalOutstanding=0;
			foreach($accounts AS $account){
				$totalPrincipalOutstanding+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$totalPrincipalOutstanding=0;
		}
		return $totalPrincipalOutstanding;
	}

	public static function LoadFilteredTotalLoanOutstanding($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		if($showAll =='all'){
		 	$principalOutstandingQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		}else{
			$principalOutstandingQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')
			 AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$principalOutstandingQuery.="";
			break;

			case '1':
			$principalOutstandingQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$principalOutstandingQuery.=" AND rm=$userID ";
			break;

			case '3':
			$principalOutstandingQuery.=" AND user_id=$userID";
			break;
		}
		return Dashboard::getTotalLoanOutStanding($branch,$staff,$borrower,$principalOutstandingQuery);
	}

	public static function getTotalLoanOutStanding($branch,$staff,$borrower,$principalOutstandingQuery){
		if($branch != 0){
			$principalOutstandingQuery.=" AND branch_id=$branch";
		}
		if($staff != 0){
			$principalOutstandingQuery.=" AND rm=$staff";
		}
		if($borrower != 0){
			$principalOutstandingQuery.=" AND user_id=$borrower";
		}
		$loanaccounts=Yii::app()->db->createCommand($principalOutstandingQuery)->queryAll();
		if(!empty($loanaccounts)){
			$totalPrincipalOutstanding=0;
			$totalAccruedInterest=0;
			$totalAccruedPenalty=0;
			foreach($loanaccounts as $loan){
				$totalPrincipalOutstanding+=LoanManager::getPrincipalBalance($loan['loanaccount_id']);
				$totalAccruedInterest+=LoanManager::getUnpaidLoanInterestBalance($loan['loanaccount_id']);
				$totalAccruedPenalty+=LoanManager::getUnpaidAccruedPenalty($loan['loanaccount_id']);
			}
			$totalOutstanding=$totalPrincipalOutstanding+$totalAccruedInterest+$totalAccruedPenalty;
		}else{
			$totalOutstanding=0;
		}
		return $totalOutstanding;
	}

	public static function LoadFilteredInterestOutstandingOpenLoans($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		if($showAll =='all'){
			$interestOutstandingQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		}else{
			$interestOutstandingQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')
			 AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$interestOutstandingQuery.="";
			break;

			case '1':
			$interestOutstandingQuery.=" AND branch_id=$userBranch ";
			break;

			case '2':
			$interestOutstandingQuery.=" AND rm=$userID";
			break;

			case '3':
			$interestOutstandingQuery.=" AND user_id=$userID";
			break;
		}
		return Dashboard::getTotalOutStandingInterest($branch,$staff,$borrower,$interestOutstandingQuery);
	}

	public static function getTotalOutStandingInterest($branch,$staff,$borrower,$interestOutstandingQuery){
		if($branch != 0){
			$interestOutstandingQuery.=" AND branch_id=$branch";
		}
		if($staff != 0){
			$interestOutstandingQuery.=" AND rm=$staff";
		}
		if($borrower != 0){
			$interestOutstandingQuery.=" AND user_id=$borrower";
		}
		$loanaccounts=Yii::app()->db->createCommand($interestOutstandingQuery)->queryAll();
		if(!empty($loanaccounts)){
			$totalInterestOutstanding=0;
			foreach($loanaccounts as $loan){
				$totalInterestOutstanding+=LoanManager::getUnpaidLoanInterestBalance($loan['loanaccount_id']);
			}
		}else{
			$totalInterestOutstanding=0;
		}
		return $totalInterestOutstanding;
	}

	public static function getFeesOutstandingOpenLoans(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			if($userBranch === 0){
					$loanaccountsSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10') ";
					$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
					if(!empty($loanaccounts)){
						$totalFeesOutstanding=0;
						foreach($loanaccounts as $loan){
							$totalFeesOutstanding+=LoanRepayment::repayLoanFee($loan->loanaccount_id);
						}
						return CommonFunctions::asMoney($totalFeesOutstanding);
					}else{
						$totalFeesOutstanding=0;
						return CommonFunctions::asMoney($totalFeesOutstanding);
					}
			}else{
					$loanaccountsSql="SELECT * FROM loanaccounts WHERE branch_id=$userBranch AND loan_status NOT IN('0','1','3','4','8','9','10') ";
				$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
				if(!empty($loanaccounts)){
					$totalFeesOutstanding=0;
					foreach($loanaccounts as $loan){
						$totalFeesOutstanding+=LoanRepayment::repayLoanFee($loan->loanaccount_id);
					}
					return CommonFunctions::asMoney($totalFeesOutstanding);
				}else{
					$totalFeesOutstanding=0;
					return CommonFunctions::asMoney($totalFeesOutstanding);
				}
			}
			break;

			case '1':
			$loanaccountsSql="SELECT * FROM loanaccounts WHERE branch_id=$userBranch AND loan_status NOT IN('0','1','3','4','8','9','10') ";
			$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
			if(!empty($loanaccounts)){
				$totalFeesOutstanding=0;
				foreach($loanaccounts as $loan){
					$totalFeesOutstanding+=LoanRepayment::repayLoanFee($loan->loanaccount_id);
				}
				return CommonFunctions::asMoney($totalFeesOutstanding);
			}else{
				$totalFeesOutstanding=0;
				return CommonFunctions::asMoney($totalFeesOutstanding);
			}
			break;

			case '2':
			$userID=Yii::app()->user->user_id;
			$loanaccountsSql="SELECT * FROM loanaccounts WHERE rm=$userID AND loan_status NOT IN('0','1','3','4','8','9','10') ";
			$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
			if(!empty($loanaccounts)){
				$totalFeesOutstanding=0;
				foreach($loanaccounts as $loan){
					$totalFeesOutstanding+=LoanRepayment::repayLoanFee($loan->loanaccount_id);
				}
				return CommonFunctions::asMoney($totalFeesOutstanding);
			}else{
				$totalFeesOutstanding=0;
				return CommonFunctions::asMoney($totalFeesOutstanding);
			}
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$loanaccountsSql="SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status NOT IN('0','1','3','4','8','9','10') ";
			$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountsSql);
			if(!empty($loanaccounts)){
				$totalFeesOutstanding=0;
				foreach($loanaccounts as $loan){
					$totalFeesOutstanding+=LoanRepayment::repayLoanFee($loan->loanaccount_id);
				}
				return CommonFunctions::asMoney($totalFeesOutstanding);
			}else{
				$totalFeesOutstanding=0;
				return CommonFunctions::asMoney($totalFeesOutstanding);
			}
			break;
		}
	}

	public static function LoadFilteredArrearsOutstandingOpenLoans($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		if($showAll =='all'){
			$arrearsQuery="SELECT SUM(arrears) AS arrears FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		}else{
			$arrearsQuery="SELECT SUM(arrears) AS arrears FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')
			 AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$arrearsQuery.="";
			break;

			case '1':
			$arrearsQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$arrearsQuery.=" AND rm=$userID";
			break;

			case '3':
			$arrearsQuery.=" AND user_id=$userID";
			break;
		}
		return Dashboard::getTotalArrearsOutStanding($branch,$staff,$borrower,$arrearsQuery);
	}

	public static function getTotalArrearsOutStanding($branch,$staff,$borrower,$arrearsQuery){
		if($branch != 0){
			$arrearsQuery.=" AND branch_id=$branch";
		}
		if($staff != 0){
			$arrearsQuery.=" AND rm=$staff";
		}
		if($borrower != 0){
			$arrearsQuery.=" AND user_id=$borrower";
		}
		$loanaccounts=Yii::app()->db->createCommand($arrearsQuery)->queryRow();
		if(!empty($loanaccounts)){
			$totalArrearsOutstanding=$loanaccounts['arrears'];
		}else{
			$totalArrearsOutstanding=0;
		}
		return 0;
	}
	public static function LoadFilteredPenaltyOutstandingOpenLoans($branch,$startDate,$endDate,$staff,$borrower,$showAll){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		if($showAll =='all'){
			$penaltyQuery="SELECT loanaccount_id FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		}else{
			$penaltyQuery="SELECT loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
			 AND loan_status NOT IN('0','1','3','8','9','10')";
		}
		switch(Yii::app()->user->user_level){
			case '0':
			$penaltyQuery.="";
			break;

			case '1':
			$penaltyQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$penaltyQuery.=" AND rm=$userID ";
			break;

			case '3':
			$penaltyQuery.=" AND user_id=$userID";
			break;
		}
		return Dashboard::getTotalPenaltyOutStanding($branch,$staff,$borrower,$penaltyQuery);
	}

	public static function getTotalPenaltyOutStanding($branch,$staff,$borrower,$penaltyQuery){
		if($branch != 0){
			$penaltyQuery.=" AND branch_id=$branch";
		}
		if($staff != 0){
			$penaltyQuery.=" AND rm=$staff";
		}
		if($borrower != 0){
			$penaltyQuery.=" AND user_id=$borrower";
		}
		$accounts=Yii::app()->db->createCommand($penaltyQuery)->queryAll();
		if(!empty($accounts)){
			$totalPenaltyOutstanding=0;
			foreach($accounts AS $account){
				$totalPenaltyOutstanding+=LoanManager::getUnpaidAccruedPenalty($account['loanaccount_id']);
			}
		}else{
			$totalPenaltyOutstanding=0;
		}
		return $totalPenaltyOutstanding;
	}
	/*****************************
		LOANS CHARTING DATA
	*********************************************/
	public static function getChartsTotalLoansReleased($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loans=Dashboard::getLoansFromCount($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loans'] = $loans;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getLoansFromCount($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanSql="SELECT * FROM loanaccounts,disbursed_loans where DATE(disbursed_loans.disbursed_at)='$startDate'
		AND loanaccounts.loan_status <>'3' AND loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.user_id=users.user_id";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanSql.="";
			break;

			case '1':
			$loanSql.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$loanSql.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$loanSql.=" AND loanaccounts.user_id=$userID";
			break;
		}
  		$loans= Loanaccounts::model()->findAllBySql($loanSql);
		$loans_count=count($loans);
		return $loans_count;
	}

	public static function getChartsTotalAmountLoansReleased($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loanAmount=Dashboard::getLoansAmountFromCount($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loanAmount'] = $loanAmount;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getLoansAmountFromCount($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanQuery="SELECT sum(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
		where loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND DATE(disbursed_loans.disbursed_at)='$startDate'";
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
  		$loans= DisbursedLoans::model()->findBySql($loanQuery);
		$amountDisbursed=(int)$loans->amount_disbursed;
		return $amountDisbursed;
	}

	public static function getChartsTotalAmountCollected($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loanAmount=Dashboard::getAmountCollectedFromCount($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loanAmount'] = $loanAmount;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getAmountCollectedFromCount($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanQuery="SELECT sum(loantransactions.amount) as amount FROM loantransactions,loanaccounts where loantransactions.date='$startDate'
		 AND loantransactions.is_void IN('0','3','4') AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id";
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
  		$loans= Loantransactions::model()->findBySql($loanQuery);
		$amountDisbursed=(int)$loans->amount;
		return $amountDisbursed;
	}

	public static function getChartsTotalCollectionsReceived($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loans=Dashboard::getCollectionsFromCount($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loans'] = $loans;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getCollectionsFromCount($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanSql="SELECT * FROM loantransactions,loanaccounts where loantransactions.date='$startDate' AND loantransactions.is_void IN('0','3','4')
		AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanSql.=" ";
			break;

			case '1':
			$loanSql.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$loanSql.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$loanSql.=" AND loanaccounts.user_id=$userID";
			break;
		}
  		$loans= Loantransactions::model()->findAllBySql($loanSql);
		$loans_count=count($loans);
		return $loans_count;
	}

	public static function getChartsTotalPrincipalOutstanding($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loanAmount=Dashboard::getTotalLoanPrincipalBalanceFrom($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loanAmount'] = $loanAmount;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getTotalLoanPrincipalBalanceFrom($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanaccountSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanaccountSql.=" ";
			break;

			case '1':
			$loanaccountSql.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanaccountSql.=" AND rm=$userID";
			break;

			case '3':
			$loanaccountSql.=" AND user_id=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountSql);
		$totalPrincipalBalance=0;
		foreach($loanaccounts as $loan){
			$totalPrincipalBalance+=LoanTransactionsFunctions::getLoanPrincipalBalanceFrom($loan->loanaccount_id,$startDate);
		}
		return (int)$totalPrincipalBalance;
	}

	public static function getPrincipalDueVersusCollections($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$principalDue=Dashboard::getTotalLoanPrincipalDueFrom($monthdate);
			$principalPaid=Dashboard::getTotalLoanPrincipalPaidFrom($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['principalDues'] = $principalDue;
			$statistics[$data_count]['principalCollections'] = $principalPaid;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getTotalLoanPrincipalDueFrom($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanaccountSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanaccountSql.=" ";
			break;

			case '1':
			$loanaccountSql.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanaccountSql.=" AND rm=$userID";
			break;

			case '3':
			$loanaccountSql.=" AND user_id=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountSql);
		$totalPrincipalBalance=0;
		foreach($loanaccounts as $loan){
			$totalPrincipalBalance+=LoanTransactionsFunctions::getLoanPrincipalBalanceFrom($loan->loanaccount_id,$startDate);
		}
		return (int)$totalPrincipalBalance;
	}

	public static function getTotalLoanPrincipalPaidFrom($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanaccountSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanaccountSql.=" ";
			break;

			case '1':
			$loanaccountSql.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanaccountSql.=" AND rm=$userID";
			break;

			case '3':
			$loanaccountSql.=" AND user_id=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountSql);
		$totalPrincipalBalance=0;
		foreach($loanaccounts as $loan){
			$totalPrincipalBalance+=LoanRepayment::getTotalPrincipalPaidFrom($loan->loanaccount_id,$startDate);
		}
		return (int)$totalPrincipalBalance;
	}

	public static function getInterestDueVersusCollections($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$interestDue=Dashboard::getTotalLoanInterestDueFrom($monthdate);
			$interestPaid=Dashboard::getTotalLoanInterestPaidFrom($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['interestDues'] = $interestDue;
			$statistics[$data_count]['interestCollections'] = $interestPaid;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getTotalLoanInterestDueFrom($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanaccountSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanaccountSql.=" ";
			break;

			case '1':
			$loanaccountSql.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanaccountSql.=" AND rm=$userID";
			break;

			case '3':
			$loanaccountSql.=" AND user_id=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountSql);
		$totalInterestBalance=0;
		foreach($loanaccounts as $loan){
			$totalInterestBalance+=LoanTransactionsFunctions::getLoanInterestBalanceFrom($loan->loanaccount_id,$startDate);
		}
		return (int)$totalInterestBalance;
	}

	public static function getTotalLoanInterestPaidFrom($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanaccountSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanaccountSql.=" ";
			break;

			case '1':
			$loanaccountSql.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanaccountSql.=" AND rm=$userID";
			break;

			case '3':
			$loanaccountSql.=" AND user_id=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountSql);
		$totalInterestBalance=0;
		foreach($loanaccounts as $loan){
			$totalInterestBalance+=LoanRepayment::getTotalInterestPaidFrom($loan->loanaccount_id,$startDate);
		}
		return (int)$totalInterestBalance;
	}

	public static function getLoanCollectionsVersusloansReleased($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loanCollections=Dashboard::getAmountCollectedFromCount($monthdate);
			$loansReleased=Dashboard::getLoansAmountFromCount($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loanCollections'] = $loanCollections;
			$statistics[$data_count]['loansReleased'] = $loansReleased;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getLoanCollectionsVersusloansDue($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loanCollections=Dashboard::getAmountCollectedFromCount($monthdate);
			$loansDue=Dashboard::getTotalLoanAmountDueFrom($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loanCollections'] = $loanCollections;
			$statistics[$data_count]['loansDue'] = $loansDue;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getTotalLoanAmountDueFrom($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanaccountSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanaccountSql.=" ";
			break;

			case '1':
			$loanaccountSql.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanaccountSql.=" AND rm=$userID";
			break;

			case '3':
			$loanaccountSql.=" AND user_id=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountSql);
		$totalLoanBalance=0;
		foreach($loanaccounts as $loan){
			$totalLoanBalance+=LoanTransactionsFunctions::getTotalLoanBalanceFrom($loan->loanaccount_id,$startDate);
		}
		return (int)$totalLoanBalance;
	}

	public static function getFeesDueVersusCollections($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$feesCollections=LoanRepayment::getTotalFeePaidFrom($monthdate);
			$feesDue=Dashboard::getTotalFeesAmountDueFrom($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['feesCollections'] = $feesCollections;
			$statistics[$data_count]['feesDue'] = $feesDue;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getTotalFeesAmountDueFrom($startDate){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanaccountSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10') AND repayment_start_date ='$startDate'";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanaccountSql.=" ";
			break;

			case '1':
			$loanaccountSql.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanaccountSql.=" AND rm=$userID";
			break;

			case '3':
			$loanaccountSql=" AND user_id=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanaccountSql);
		$totalFeeAmount=0;
		foreach($loanaccounts as $loan){
			$totalFeeAmount+=LoanRepayment::calculateTotalLoanFees($loan->loanaccount_id);
		}
		return (int)$totalFeeAmount;
	}

	public static function getChartsTotalLoansCumulativeReleased($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		$loans=0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$loans+=Dashboard::getLoansFromCount($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['loans'] = $loans;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}

	public static function getAverageLoanTenureTime(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$clearedLoanSQL="SELECT * FROM clearedloans,loanaccounts WHERE loanaccounts.loanaccount_id=clearedloans.loanaccount_id";
		switch(Yii::app()->user->user_level){
			case '0':
			$clearedLoanSQL.=" ";
			break;

			case '1':
			$clearedLoanSQL.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$clearedLoanSQL.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$clearedLoanSQL.=" AND loanaccounts.user_id=$userID";
			break;
		}
		$clearedLoans=Clearedloans::model()->findAllBySql($clearedLoanSQL);
		if(!empty($clearedLoans)){
			$totalClearedLoans=count($clearedLoans);
			$difference=0;
			foreach($clearedLoans as $loan){
				$dateCleared=$loan->date_cleared;
				if($dateCleared === 'N/A' || $dateCleared === ''){
					$dateCleared=date('Y-m-d');
				}
				$loanaccount=LoanApplication::getLoanAccount($loan->loanaccount_id);
				$repaymentStartDate=$loanaccount['repayment_start_date'];
				$difference+=CommonFunctions::getDatesDifference($repaymentStartDate,$dateCleared);
			}
			if($difference <= 0){
				$averageTenure=0;
			}else{
				$averageTenure=$difference/$totalClearedLoans;
			}
			return round($averageTenure);
		}else{
			$averageTenure=0;
			return round($averageTenure);
		}
	}

	public static function getPenaltyDueVersusPenaltyCollections($start_date,$end_date){
		$statistics = array();
		$data_count = 0;
		while (strtotime($start_date) <= strtotime($end_date)) {
			/* Actual Day to Track */
			$monthdate = $start_date;
			$penaltyCollections=LoanRepayment::getTotalPenaltyPaidFrom($monthdate);
			$penaltyDues=Dashboard::getTotalPenaltyAmountDueFrom($monthdate);
			/* Store Values in Array */
			$statistics[$data_count]['day'] = $monthdate;
			$statistics[$data_count]['penaltyCollections'] = $penaltyCollections;
			$statistics[$data_count]['penaltyDues'] = $penaltyDues;
			/* Add 1 day */
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$data_count++;
		}
		return $statistics;
	}


	public static function getTotalPenaltyAmountDueFrom($startDate){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$penaltySQL="SELECT SUM(penalty_amount) as penalty_amount FROM penaltyaccrued WHERE DATE(created_at) ='$startDate'";
			$penalty=Penaltyaccrued::model()->findBySql($penaltySQL);
			if(!empty($penalty)){
				$penaltyAmount=$penalty->penalty_amount;
				return $penaltyAmount;
			}else{
				$penaltyAmount=0;
				return $penaltyAmount;
			}
			break;

			case '1':
			$penaltySQL="SELECT SUM(penaltyaccrued.penalty_amount) as penalty_amount FROM penaltyaccrued,loanaccounts 
			WHERE  DATE(penaltyaccrued.created_at) ='$startDate' AND penaltyaccrued.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.branch_id=$userBranch";
			$penalty=Penaltyaccrued::model()->findBySql($penaltySQL);
			if(!empty($penalty)){
				$penaltyAmount=$penalty->penalty_amount;
				return $penaltyAmount;
			}else{
				$penaltyAmount=0;
				return $penaltyAmount;
			}
			break;

			case '2':
			$penaltySQL="SELECT SUM(penaltyaccrued.penalty_amount) as penalty_amount FROM penaltyaccrued,loanaccounts
			 WHERE DATE(penaltyaccrued.created_at) ='$startDate' AND penaltyaccrued.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.branch_id=$userBranch";
			$penalty=Penaltyaccrued::model()->findBySql($penaltySQL);
			if(!empty($penalty)){
				$penaltyAmount=$penalty->penalty_amount;
				return $penaltyAmount;
			}else{
				$penaltyAmount=0;
				return $penaltyAmount;
			}
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$penaltySQL="SELECT SUM(penaltyaccrued.penalty_amount) as penalty_amount FROM penaltyaccrued,loanaccounts
			WHERE DATE(penaltyaccrued.created_at) ='$startDate' AND penaltyaccrued.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.user_id=$userID";
			$penalty=Penaltyaccrued::model()->findBySql($penaltySQL);
			if(!empty($penalty)){
				$penaltyAmount=$penalty->penalty_amount;
				return $penaltyAmount;
			}else{
				$penaltyAmount=0;
				return $penaltyAmount;
			}
			break;
		}
	}

	public static function getLoanAccountsStatusCount(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanSQL="SELECT loan_status AS loanStatusName,COUNT(loan_status) AS loanStatusCount
		 FROM loanaccounts";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanSQL.=" ";
			break;

			case '1':
			$loanSQL.=" WHERE loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$loanSQL.=" WHERE loanaccounts.rm=$userID";
			break;

			case '3':
			$loanSQL.=" WHERE loanaccounts.user_id=$userID";
			break;
		}
		$loanSQL.=" GROUP BY loanaccounts.loan_status";
		$loans = Loanaccounts::model()->findAllBySql($loanSQL);
		return $loans;
	}

	public static function getBorrowerGenderCount(){
		$userBranch=Yii::app()->user->user_branch;
		$borrowerSQL="SELECT gender AS genderName,COUNT(gender) AS genderCount FROM profiles WHERE profileType IN('MEMBER')";
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowerSQL.=" ";
			break;

			default:
			$borrowerSQL.=" AND branchId=$userBranch";
			break;
		}
		$borrowerSQL.=" GROUP BY gender";
		$borrowers = Profiles::model()->findAllBySql($borrowerSQL);
		return $borrowers;
	}

	/*********************

		LANDING PAGE

	***************************/
	public static function getLandingPageNotices(){
		$noticeQuery="SELECT * FROM notices WHERE is_active='1'";
		switch(Yii::app()->user->user_level){
			case '0':
			$noticeQuery.="";
			break;

			case '1':
			$noticeQuery.=" AND level IN('0','2','3')";
			break;

			case '2':
			$noticeQuery.=" AND level IN('0','3')";
			break;

			case '3':
			$noticeQuery.=" AND level IN('0','4')";
			break;

			case '4':
			$noticeQuery.=" AND level IN('0')";
			break;

			case '5':
			$noticeQuery.=" AND level IN('0','2','6')";
			break;
		}
		$noticeQuery.=" ORDER BY id DESC";
		$notices=Notices::model()->findAllBySql($noticeQuery);
		return $notices;
	}

}