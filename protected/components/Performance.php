<?php 

class Performance{
	/*****************************

		BRANCH PERFORMANCE

	*************************************/
	public static function LoadFilteredBranchPerformance($branch,$startDate,$endDate){
		$branches = $branch ==0 ? Reports::getAllSaccoBranches() : Branch::model()->findByPk($branch);
		return Tabulate::getBranchPerformanceTable($branches,$startDate,$endDate);
	}

	public static function getTotalBranchSales($branch,$startDate,$endDate){
		$salesQuery = "SELECT COALESCE(SUM(amount_disbursed),0) AS amount_disbursed FROM disbursed_loans
		WHERE  (DATE(disbursed_at) BETWEEN '$startDate' AND '$endDate') AND loanaccount_id IN (SELECT loanaccount_id FROM loanaccounts WHERE branch_id=$branch)";
		$loan = DisbursedLoans::model()->findBySql($salesQuery);
		return !empty($loan) ? $loan->amount_disbursed : 0;
	}

	public static function getTotalBranchSalesCount($branch,$startDate,$endDate){
		$salesQuery = "SELECT COUNT(DISTINCT(id)) AS id  FROM disbursed_loans WHERE  (DATE(disbursed_at) BETWEEN '$startDate' AND '$endDate')
		AND loanaccount_id IN (SELECT loanaccount_id FROM loanaccounts WHERE branch_id=$branch)";
		$loan = DisbursedLoans::model()->findBySql($salesQuery);
		return !empty($loan) ? $loan->id : 0;	
	}

	public static function getTotalBranchCollections($branch,$startDate,$endDate){
		$transactionQuery = "SELECT COALESCE(SUM(amount),0) AS amount FROM loantransactions WHERE (DATE(transacted_at) BETWEEN '$startDate' AND '$endDate')
		AND is_void IN('0','3','4') AND loanaccount_id IN(SELECT loanaccount_id FROM loanaccounts WHERE branch_id=$branch)";
		$loan = Loantransactions::model()->findBySql($transactionQuery);
		return !empty($loan) ? $loan->amount : 0;		
	}

	public static function getTotalBranchCollectionsCount($branch,$startDate,$endDate){
		$transactionQuery = "SELECT COUNT(DISTINCT(loantransaction_id)) AS loantransaction_id FROM loantransactions WHERE (DATE(transacted_at) BETWEEN '$startDate' AND '$endDate')
		AND is_void IN('0','3','4') AND loanaccount_id IN(SELECT loanaccount_id FROM loanaccounts WHERE branch_id=$branch)";
		$loan = Loantransactions::model()->findBySql($transactionQuery);
		return !empty($loan) ? $loan->loantransaction_id : 0;
	}

	public static function getTotalBranchCollectionsCountPACC($branch,$startDate,$endDate){
		$transactionQuery = "SELECT COUNT(DISTINCT(loanaccount_id)) AS loanaccount_id FROM loantransactions WHERE (DATE(transacted_at) BETWEEN '$startDate' AND '$endDate')
		AND is_void IN('0','3','4') AND loanaccount_id IN(SELECT loanaccount_id FROM loanaccounts WHERE branch_id=$branch)";
		$loan = Loantransactions::model()->findBySql($transactionQuery);
		return !empty($loan) ? $loan->loanaccount_id : 0;	
	}

	public static function getActiveAccountsMonthStart($branch,$startDate,$endDate){
		$startMonth  = date('Y-m-01',strtotime($startDate));
		$filterQuery = "SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE loan_status IN('2','5','6','7')
		AND DATE(created_at) <= '$startMonth'";
		switch($branch){
			case 0:
			$filterQuery.="";
			break;

			default:
			$filterQuery.=" AND branch_id=$branch";
			break;
		}
		$accounts = Loanaccounts::model()->findBySql($filterQuery);
		return !empty($accounts) ? $accounts->loanaccount_id : 0;
	}
	/******************

		COMMON UTILITIES

	************************/
	public static function getPerformancePercentage($target,$achieved){
		if($target <= 0){
			$target=1;
		}
		$percent=($achieved/$target) * 100;
		return $percent;
	}

	public static function determinePerformanceColor($percentPerformance){
		switch(StaffFunctions::getDeterminedPerformanceColor($percentPerformance)){
			case 'red':
			$styling='background:#ff3636; color:#fff;';
			break;

			case 'amber':
			$styling='background:#ffb236; color:#fff;';
			break;

			case 'green':
			$styling='background:#18ce0f; color:#fff;';
			break;

			case 'purple':
			$styling='background:#b34cf3; color:#fff;';
			break;

			default:
			$styling='background:#ff3636; color:#fff;';
			break;
		}
		return $styling;
	}

	public static function determineActiveAccountsPerformanceColor($percentPerformance){
		switch(StaffFunctions::getDeterminedPerformanceColor($percentPerformance)){
			case 'red':
			$styling='background:#ff3636; color:#fff;';
			break;

			case 'amber':
			$styling='background:#ffb236; color:#fff;';
			break;

			case 'green':
			$styling='background:#32CD32; color:#fff;';
			break;

			case 'purple':
			$styling='background:#800080; color:#fff;';
			break;

			default:
			$styling='background:#ff3636; color:#fff;';
			break;
		}
		return $styling;
	}

	public static function determinePALPerformanceColor($percentPerformance){
		switch(StaffFunctions::getDeterminedPerformanceColor($percentPerformance)){
			case 'red':
			$styling='background:#ff3636; color:#fff;';
			break;

			case 'amber':
			$styling='background:#ffb236; color:#fff;';
			break;

			case 'green':
			$styling='background:#18ce0f; color:#fff;';
			break;

			case 'purple':
			$styling='background:#800080; color:#fff;';
			break;

			default:
			$styling='background:#ff3636; color:#fff;';
			break;
		}
		return $styling;
	}
	/*****************

		STAFF PERFORMANCE

	***************************/
	public static function LoadFilteredStaffPerformance($branch,$startDate,$endDate,$staff){
		$commonDenominator = "AND id IN(SELECT profileId FROM account_settings WHERE configType='COMMENTS_DASHBOARD_LISTED' AND configValue='ACTIVE') ORDER BY firstName,lastName ASC";
		$defaultQuery = "SELECT * FROM profiles WHERE profileType IN('STAFF') $commonDenominator";
		$branchQuery  = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND branchId=$branch $commonDenominator";
		$staffQuery   = "SELECT * FROM profiles WHERE id=$staff";
		switch($branch){
			case 0:
			$staffs = $staff == 0 ? Profiles::model()->findAllBySql($defaultQuery) : Profiles::model()->findBySql($staffQuery);
			break;

			default:
			$staffs = $staff == 0 ? Profiles::model()->findAllBySql($branchQuery) : Profiles::model()->findBySql($staffQuery);
			break;
		}
		return Tabulate::getStaffPerformanceTable($staffs,$startDate,$endDate);
	}

	public static function getTotalStaffSales($staff,$startDate,$endDate){
		$salesQuery="SELECT COALESCE(SUM(disbursed_loans.amount_disbursed),0) AS amount_disbursed FROM disbursed_loans,loanaccounts
		WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$startDate' AND '$endDate')
		AND loanaccounts.rm=$staff";
		$loan=DisbursedLoans::model()->findBySql($salesQuery);
		return !empty($loan) ? $loan->amount_disbursed : 0;	
	}

	public static function getTotalStaffSalesCount($staff,$startDate,$endDate){
		$salesQuery="SELECT COUNT(DISTINCT (disbursed_loans.id)) AS id FROM disbursed_loans,loanaccounts
		WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$startDate' AND '$endDate')
		AND loanaccounts.rm=$staff";
		$loan=DisbursedLoans::model()->findBySql($salesQuery);
		return !empty($loan) ? $loan->id : 0;	
	}

	public static function getTotalStaffCollections($staff,$startDate,$endDate){
		$transactionQuery="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts,loanrepayments
		WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND DATE(loantransactions.transacted_at) BETWEEN '$startDate' AND '$endDate'
		AND loantransactions.is_void IN('0','3','4') AND loanaccounts.rm=$staff";
		$loan=Loantransactions::model()->findBySql($transactionQuery);
		return !empty($loan) ? $loan->amount : 0;	
	}

	public static function getTotalStaffCollectionsCount($staff,$startDate,$endDate){
		$transactionQuery="SELECT COUNT(DISTINCT (loantransactions.loantransaction_id)) as loantransaction_id FROM loantransactions,loanrepayments,loanaccounts
		WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(loantransactions.transacted_at) BETWEEN '$startDate' AND '$endDate')
		AND loantransactions.is_void IN('0','3','4') AND loanaccounts.rm=$staff";
		$loan=Loantransactions::model()->findBySql($transactionQuery);
		return !empty($loan) ? $loan->loantransaction_id : 0;	
	}

	public static function getTotalStaffCollectionsCountPACC($staff,$startDate,$endDate){
		$transactionQuery="SELECT COUNT(DISTINCT(loantransactions.loanaccount_id)) as loanaccount_id FROM loantransactions,loanrepayments,loanaccounts
		WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
		AND DATE(loantransactions.transacted_at) BETWEEN '$startDate' AND '$endDate' AND loantransactions.is_void IN('0','3','4') AND loanaccounts.rm=$staff";
		$loan=Loantransactions::model()->findBySql($transactionQuery);
		return !empty($loan) ? $loan->loanaccount_id : 0;	
	}

	public static function getStaffActiveAccountsMonthStart($staff,$startDate,$endDate){
		$startMonth  = date('Y-m-01',strtotime($startDate));
		$filterQuery = "SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE loan_status IN('2','5','6','7')
		AND DATE(created_at) <= '$startMonth'";
		switch($staff){
			case 0:
			$filterQuery.="";
			break;

			default:
			$filterQuery.=" AND rm=$staff";
			break;
		}
		$accounts=Loanaccounts::model()->findBySql($filterQuery);
		return !empty($accounts) ? $accounts->loanaccount_id : 0;
	}

	/*******************************

		EXECUTIVE SUMMARY STATS ~ BRANCH

	************************************************************/
	public static function getBranchZeroRatedPrincipalBalance($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE loan_status IN('2','5','6','7') AND interest_rate=0.00";
		}else{
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status IN('2','5','6','7')  AND interest_rate=0.00";
		}
		switch($branchID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$branchZeroRatedPrincipalBalance=0;
			foreach($accounts AS $account){
				$branchZeroRatedPrincipalBalance+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$branchZeroRatedPrincipalBalance=0;
		}
		return $branchZeroRatedPrincipalBalance;
	}

	public static function getBranchInterestRatedPrincipalBalance($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE loan_status  IN('2','5','6','7') AND interest_rate>0";
		}else{
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status  IN('2','5','6','7')  AND interest_rate>0";
		}
		switch($branchID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$interestRatedPrincipalBalance=0;
			foreach($accounts AS $account){
				$interestRatedPrincipalBalance+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$interestRatedPrincipalBalance=0;
		}
		return $interestRatedPrincipalBalance;
	}

	public static function getBranchInterestBalance($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE loan_status  IN('2','5','6','7') ";
		}else{
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status  IN('2','5','6','7')";
		}
		switch($branchID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$interestBalance=0;
			foreach($accounts AS $account){
				$interestBalance+=LoanManager::getUnpaidLoanInterestBalance($account['loanaccount_id']);
			}
		}else{
			$interestBalance=0;
		}
		return $interestBalance;
	}

	public static function getBranchPenaltyBalance($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
		}else{
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status IN('2','5','6','7')";
		}
		switch($branchID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$penaltyBalance=0;
			foreach($accounts AS $account){
				$penaltyBalance+=LoanManager::getUnpaidAccruedPenalty($account['loanaccount_id']);
			}
		}else{
			$penaltyBalance=0;
		}
		return $penaltyBalance;
	}

	public static function getBranchPrincipalPaid($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate= date('Y-m-01');
			$monthEndDate  = date('Y-m-t');
			$principalQuery= "SELECT SUM(principal_paid) as principal_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$monthStartDate' AND '$monthEndDate')"; 
		}else{
			$principalQuery="SELECT SUM(principal_paid) as principal_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$startDate' AND '$endDate')"; 
		}
		switch($branchID){
			case 0:
			$principalQuery.="";
			break;

			default:
			$principalQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($principalQuery)->queryRow();
		return !empty($accounts) ? $accounts['principal_paid'] : 0;
	}

	public static function getBranchInterestPaid($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$interestQuery="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$monthStartDate' AND '$monthEndDate')"; 
		}else{
			$interestQuery="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$startDate' AND '$endDate')"; 
		}
		switch($branchID){
			case 0:
			$interestQuery.="";
			break;

			default:
			$interestQuery.=" AND branch_id=$branchID";
			break;
		}
		$accounts=Yii::app()->db->createCommand($interestQuery)->queryRow();
		return !empty($accounts) ? $accounts['interest_paid'] : 0;
	}

	public static function getBranchPenaltyPaid($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$penaltyQuery="SELECT SUM(penalty_paid) as penalty_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$monthStartDate' AND '$monthEndDate')"; 
		}else{
			$penaltyQuery="SELECT SUM(penalty_paid) as penalty_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$startDate' AND '$endDate')"; 
		}
		switch($branchID){
			case 0:
			$penaltyQuery.="";
			break;

			default:
			$penaltyQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($penaltyQuery)->queryRow();
		return !empty($accounts) ? $accounts['penalty_paid'] : 0;
	}

	public static function getBranchTotalExpenses($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$branchExpenseQuery="SELECT SUM(amount) AS amount FROM expenses WHERE
			 (DATE(expense_date) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$branchExpenseQuery="SELECT SUM(amount) AS amount FROM expenses WHERE
			 (DATE(expense_date) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($branchID){
			case 0:
			$branchExpenseQuery.="";
			break;

			default:
			$branchExpenseQuery.=" AND branch_id=$branchID";
			break;
		}
   		 $expenses=Yii::app()->db->createCommand($branchExpenseQuery)->queryRow();
		if(!empty($expenses)){
			$totalBranchExpense=$expenses['amount'];
		}else{
			$totalBranchExpense=0;
		}
		$totalBranchAirtime=Performance::getBranchTotalAirtime($branchID,$startDate,$endDate,$defaultPeriod);
		$totalBranchSalaries=Performance::getBranchTotalSalaries($branchID,$startDate,$endDate,$defaultPeriod);
		$totalBranchExpenses=$totalBranchExpense+$totalBranchAirtime+$totalBranchSalaries;
		return $totalBranchExpenses;
	}

	public static function getBranchTotalAirtime($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$branchAirtimeQuery="SELECT SUM(amount) AS amount FROM airtime WHERE status='2'
			 AND (DATE(date_disbursed) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$branchAirtimeQuery="SELECT SUM(amount) AS amount FROM airtime WHERE status='2'
			 AND (DATE(date_disbursed) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($branchID){
			case 0:
			$branchAirtimeQuery.="";
			break;

			default:
			$branchAirtimeQuery.=" AND branch_id=$branchID";
			break;
		}
    	$airtimes=Yii::app()->db->createCommand($branchAirtimeQuery)->queryRow();
		return !empty($airtimes) ? $airtimes['amount'] : 0;
	}

	public static function getBranchTotalSalaries($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$branchSalariesQuery="SELECT SUM(net_salary) AS net_salary FROM payroll WHERE (DATE(processed_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$branchSalariesQuery="SELECT SUM(net_salary) AS net_salary FROM payroll WHERE (DATE(processed_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($branchID){
			case 0:
			$branchSalariesQuery.="";
			break;

			default:
			$branchSalariesQuery.=" AND branch_id=$branchID";
			break;
		}
    	$salaries=Yii::app()->db->createCommand($branchSalariesQuery)->queryRow();
		return !empty($salaries) ? $salaries['net_salary'] : 0;
	}

	public static function getBranchProfitAndLoss($branchID,$startDate,$endDate,$defaultPeriod){
		$monthStartDate=date('Y-m-01');
		$monthEndDate=date('Y-m-t');
		if($defaultPeriod === 0){
			$profitQuery="SELECT SUM(interest_paid) AS interest_paid,SUM(fee_paid) AS fee_paid,SUM(penalty_paid) AS penalty_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(date) BETWEEN '$monthStartDate' AND '$monthEndDate')";
			$lossQuery="SELECT COALESCE(SUM(amount),0) AS amount FROM write_offs WHERE (DATE(created_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$profitQuery="SELECT SUM(interest_paid) AS interest_paid,SUM(fee_paid) AS fee_paid,SUM(penalty_paid) AS penalty_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(date) BETWEEN '$startDate' AND '$endDate')";
			$lossQuery="SELECT COALESCE(SUM(amount),0) AS amount FROM write_offs WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($branchID){
			case 0:
			$profitQuery.=" ";
			$lossQuery.=" ";
			break;

			default:
			$profitQuery.=" AND branch_id=$branchID";
			$lossQuery.=" AND branch_id=$branchID";
			break;
		}
    	$profits=Yii::app()->db->createCommand($profitQuery)->queryRow();
		if(!empty($profits)){
			$branchTotalProfitAmount=$profits['interest_paid']+$profits['fee_paid']+$profits['penalty_paid'];
		}else{
			$branchTotalProfitAmount=0;
		}
		$losses=Yii::app()->db->createCommand($lossQuery)->queryRow();
		if(!empty($losses)){
			$branchTotalLoss=$losses['amount'];
		}else{
			$branchTotalLoss=0;
		}
		$branchTotalProfitOrLoss=$branchTotalProfitAmount-($branchTotalLoss+Performance::getBranchTotalExpenses($branchID,$startDate,$endDate,$defaultPeriod));
		return $branchTotalProfitOrLoss;
	}

	public static function getBranchDailyInterestAccrued($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$currentDate=date('Y-m-d');
			$interestQuery="SELECT SUM(loaninterests.interest_accrued) AS interest_accrued FROM loaninterests,loanaccounts WHERE loaninterests.loanaccount_id=loanaccounts.loanaccount_id AND DATE(loaninterests.accrued_at)='$currentDate' AND loaninterests.transaction_type='debit'";
		}else{
			$interestQuery="SELECT SUM(loaninterests.interest_accrued) AS interest_accrued FROM loaninterests,loanaccounts WHERE loaninterests.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(loaninterests.accrued_at) BETWEEN '$startDate' AND '$endDate') AND loaninterests.transaction_type='debit'";
		}
		switch($branchID){
			case 0:
			$interestQuery.="";
			break;

			default:
			$interestQuery.=" AND loanaccounts.branch_id=$branchID";
			break;
		}
		$interest=Yii::app()->db->createCommand($interestQuery)->queryRow();
		return !empty($interest)? $interest['interest_accrued'] : 0;
	}

	public static function getBranchDailyInterestPaid($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$currentDate=date('Y-m-d');
			$interestQuery="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND DATE(repaid_at)='$currentDate'"; 
		}else{
			$interestQuery="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$startDate' AND '$endDate')"; 
		}
		switch($branchID){
			case 0:
			$interestQuery.="";
			break;

			default:
			$interestQuery.=" AND branch_id=$branchID";
			break;
		}
   		$accounts=Yii::app()->db->createCommand($interestQuery)->queryRow();
		return !empty($accounts) ? $accounts['interest_paid'] : 0;
	}

	public static function getBranchTotalAmountDisbursed($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$accountsQuery="SELECT SUM(disbursed_loans.amount_disbursed) AS amount_disbursed FROM disbursed_loans,loanaccounts WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$accountsQuery="SELECT SUM(disbursed_loans.amount_disbursed) AS amount_disbursed FROM disbursed_loans,loanaccounts WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($branchID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND loanaccounts.branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['amount_disbursed'] : 0;
	}

	public static function getBranchTotalSavings($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$savingsQuery="SELECT savingaccount_id FROM savingaccounts WHERE is_approved='1'";
		}else{
			$savingsQuery="SELECT savingaccount_id FROM savingaccounts WHERE is_approved='1'
			AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($branchID){
			case 0:
			$savingsQuery.="";
			break;

			default:
			$savingsQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($savingsQuery)->queryAll();
		if(!empty($accounts)){
			$branchTotalSavings=0;
			foreach($accounts AS $account){
				$branchTotalSavings+=SavingFunctions::getTotalSavingAccountBalance($account['savingaccount_id']);
			}
		}else{
			$branchTotalSavings=0;
		}
		return $branchTotalSavings;
	}

	public static function getBranchTotalMembers($branchID,$startDate,$endDate,$defaultPeriod){
		$membersQuery = "SELECT COUNT(DISTINCT id) AS profileId FROM profiles WHERE profileType IN('MEMBER')";
		if($defaultPeriod === 0){
			$membersQuery.= $branchID ===0 ? "" : " AND branchId=$branchID";
		}else{
			$membersQuery .=" AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate') "; 
			$membersQuery.= $branchID === 0 ? "" : " AND branchId=$branchID";
		}
        $membersCount=Yii::app()->db->createCommand($membersQuery)->queryRow();
		return !empty($membersCount) ? $membersCount['profileId'] : 0;
	}

	public static function getBranchTotalActiveLoanAccounts($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE 
			loan_status IN('2','5','6','7')";
		}else{
			$accountsQuery="SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status IN('2','5','6','7')";
		}
		switch($branchID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['loanaccount_id'] : 0;
	}

	public static function getBranchAverageLoanAccountsInterestRate($branchID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT AVG(interest_rate) AS interest_rate FROM loanaccounts WHERE 
			loan_status IN('2','5','6','7')";
		}else{
			$accountsQuery="SELECT AVG(interest_rate) AS interest_rate FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status  IN('2','5','6','7')";
		}
		switch($branchID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND branch_id=$branchID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['interest_rate'] : 0;
	}

	/*******************************

		EXECUTIVE SUMMARY STATS ~ STAFF / RM

	************************************************************/
	public static function getStaffZeroRatedPrincipalBalance($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE 
			loan_status IN('2','5','6','7') AND interest_rate=0.00";
		}else{
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
			AND loan_status IN('2','5','6','7') AND interest_rate=0.00";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$staffZeroRatedPrincipalBalance=0;
			foreach($accounts AS $account){
				$staffZeroRatedPrincipalBalance+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$staffZeroRatedPrincipalBalance=0;
		}
		return $staffZeroRatedPrincipalBalance;
	}

	public static function getStaffInterestRatedPrincipalBalance($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE 
			loan_status IN('2','5','6','7') AND interest_rate>0";
		}else{
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE
			(DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status IN('2','5','6','7') AND interest_rate>0";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$staffInterestRatedPrincipalBalance=0;
			foreach($accounts AS $account){
				$staffInterestRatedPrincipalBalance+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$staffInterestRatedPrincipalBalance=0;
		}
		return $staffInterestRatedPrincipalBalance;
	}

	public static function getStaffInterestBalance($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
		}else{
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status IN('2','5','6','7')";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$staffInterestBalance=0;
			foreach($accounts AS $account){
				$staffInterestBalance+=LoanManager::getUnpaidLoanInterestBalance($account['loanaccount_id']);
			}
		}else{
			$staffInterestBalance=0;
		}
		return $staffInterestBalance;
	}

	public static function getStaffPenaltyBalance($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
		}else{
			$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate') AND loan_status IN('2','5','6','7')";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$staffPenaltyBalance=0;
			foreach($accounts AS $account){
				$staffPenaltyBalance+=LoanManager::getUnpaidAccruedPenalty($account['loanaccount_id']);
			}
		}else{
			$staffPenaltyBalance=0;
		}
		return $staffPenaltyBalance;
	}

	public static function getStaffPrincipalPaid($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$accountsQuery="SELECT SUM(principal_paid) as principal_paid FROM loanrepayments WHERE 
			is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$accountsQuery="SELECT SUM(principal_paid) as principal_paid FROM loanrepayments WHERE 
			is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
   		$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['principal_paid'] : 0;
	}

	public static function getStaffInterestPaid($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$accountsQuery="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE 
			is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$accountsQuery="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE 
			is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['interest_paid'] : 0;
	}

	public static function getStaffPenaltyPaid($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$accountsQuery="SELECT SUM(penalty_paid) as penalty_paid FROM loanrepayments WHERE 
			is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$accountsQuery="SELECT SUM(penalty_paid) as penalty_paid FROM loanrepayments WHERE 
			is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['penalty_paid'] : 0;
	}

	public static function getStaffTotalExpenses($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$staffExpenseQuery="SELECT SUM(amount) AS amount FROM expenses WHERE (DATE(expense_date) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$staffExpenseQuery="SELECT SUM(amount) AS amount FROM expenses WHERE (DATE(expense_date) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$staffExpenseQuery.="";
			break;

			default:
			$staffExpenseQuery.=" AND user_id=$staffUserID";
			break;
		}
    	$expenses=Yii::app()->db->createCommand($staffExpenseQuery)->queryRow();
		if(!empty($expenses)){
			$totalStaffExpense=$expenses['amount'];
		}else{
			$totalStaffExpense=0;
		}
		$totalAirtime=Performance::getStaffTotalAirtime($staffUserID,$startDate,$endDate,$defaultPeriod);
		$totalSalaries=Performance::getStaffTotalSalaries($staffUserID,$startDate,$endDate,$defaultPeriod);
		$totalStaffExpenses=$totalStaffExpense+$totalAirtime+$totalSalaries;
		return $totalStaffExpenses;
	}

	public static function getStaffTotalAirtime($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$staffAirtimeQuery="SELECT SUM(amount) AS amount FROM airtime WHERE status='2'
			 AND (DATE(date_disbursed) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$staffAirtimeQuery="SELECT SUM(amount) AS amount FROM airtime WHERE status='2'
			 AND (DATE(date_disbursed) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$staffAirtimeQuery.="";
			break;

			default:
			$staffAirtimeQuery.=" AND user_id=$staffUserID";
			break;
		}
    	$airtimes=Yii::app()->db->createCommand($staffAirtimeQuery)->queryRow();
		return !empty($airtimes) ? $airtimes['amount'] : 0;
	}

	public static function getStaffTotalSalaries($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$staffSalariesQuery="SELECT SUM(net_salary) AS net_salary FROM payroll WHERE (DATE(processed_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$staffSalariesQuery="SELECT SUM(net_salary) AS net_salary FROM payroll WHERE (DATE(processed_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$staffSalariesQuery.="";
			break;

			default:
			$staffSalariesQuery.=" AND user_id=$staffUserID";
			break;
		}
    	$salaries=Yii::app()->db->createCommand($staffSalariesQuery)->queryRow();
		return !empty($salaries) ? $salaries['net_salary'] : 0;
	}

	public static function getStaffProfitAndLoss($staffUserID,$startDate,$endDate,$defaultPeriod){
		$monthStartDate=date('Y-m-01');
		$monthEndDate=date('Y-m-t');
		if($defaultPeriod === 0){
			$profitQuery="SELECT SUM(interest_paid) AS interest_paid,SUM(fee_paid) AS fee_paid,SUM(penalty_paid) AS penalty_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(date) BETWEEN '$monthStartDate' AND '$monthEndDate')";
			$lossQuery="SELECT COALESCE(SUM(amount),0) AS amount FROM write_offs WHERE (DATE(created_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$profitQuery="SELECT SUM(interest_paid) AS interest_paid,SUM(fee_paid) AS fee_paid,SUM(penalty_paid) AS penalty_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(date) BETWEEN '$startDate' AND '$endDate')";
			$lossQuery="SELECT COALESCE(SUM(amount),0) AS amount FROM write_offs WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$profitQuery.=" ";
			$lossQuery.=" ";
			break;

			default:
			$profitQuery.=" AND rm=$staffUserID";
			$lossQuery.=" AND rm=$staffUserID";
			break;
		}
   		$profits=Yii::app()->db->createCommand($profitQuery)->queryRow();
		if(!empty($profits)){
			$staffTotalProfitAmount=$profits['interest_paid']+$profits['fee_paid']+$profits['penalty_paid'];
		}else{
			$staffTotalProfitAmount=0;
		}
		$losses=Yii::app()->db->createCommand($lossQuery)->queryRow();
		if(!empty($losses)){
			$staffTotalLoss=$losses['amount'];
		}else{
			$staffTotalLoss=0;
		}
		$staffTotalProfitOrLoss=$staffTotalProfitAmount-($staffTotalLoss+Performance::getStaffTotalExpenses($staffUserID,$startDate,$endDate,$defaultPeriod));
		return $staffTotalProfitOrLoss;
	}

	public static function getStaffDailyInterestAccrued($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$currentDate=date('Y-m-d');
    		$interestQuery="SELECT SUM(loaninterests.interest_accrued) AS interest_accrued FROM loaninterests,loanaccounts WHERE loaninterests.loanaccount_id=loanaccounts.loanaccount_id AND DATE(loaninterests.accrued_at)='$currentDate' AND transaction_type='debit'";
		}else{
    		$interestQuery="SELECT SUM(loaninterests.interest_accrued) AS interest_accrued FROM loaninterests,loanaccounts WHERE loaninterests.loanaccount_id=loanaccounts.loanaccount_id
    	  	AND (DATE(loaninterests.accrued_at) BETWEEN '$startDate' AND '$endDate') AND transaction_type='debit'";
		}
		switch($staffUserID){
			case 0:
			$interestQuery.="";
			break;

			default:
			$interestQuery.=" AND loanaccounts.rm=$staffUserID";
			break;
		}
        $interest=Yii::app()->db->createCommand($interestQuery)->queryRow();
		return !empty($interest) ? $interest['interest_accrued'] : 0;
	}

	public static function getStaffDailyInterestPaid($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$currentDate=date('Y-m-d');
			$interestQuery="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND DATE(repaid_at)='$currentDate'"; 
		}else{
			$interestQuery="SELECT SUM(interest_paid) as interest_paid FROM loanrepayments WHERE is_void IN('0','3','4') AND (DATE(repaid_at) BETWEEN '$startDate' AND '$endDate')"; 
		}
		switch($staffUserID){
			case 0:
			$interestQuery.="";
			break;

			default:
			$interestQuery.=" AND rm=$staffUserID";
			break;
		}
   		$accounts=Yii::app()->db->createCommand($interestQuery)->queryRow();
		return !empty($accounts) ? $accounts['interest_paid'] : 0;
	}

	public static function getStaffTotalAmountDisbursed($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$monthStartDate=date('Y-m-01');
			$monthEndDate=date('Y-m-t');
			$accountsQuery="SELECT SUM(disbursed_loans.amount_disbursed) AS amount_disbursed FROM disbursed_loans,loanaccounts WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$monthStartDate' AND '$monthEndDate')";
		}else{
			$accountsQuery="SELECT SUM(disbursed_loans.amount_disbursed) AS amount_disbursed FROM disbursed_loans,loanaccounts WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND loanaccounts.rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['amount_disbursed'] : 0;
	}

	public static function getStaffTotalSavings($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$savingsQuery="SELECT savingaccount_id FROM savingaccounts WHERE is_approved='1'";
		}else{
		  $savingsQuery="SELECT savingaccount_id FROM savingaccounts WHERE is_approved='1' 
		  AND (DATE(created_at) BETWEEN '$startDate' AND '$endDate')";
		}
		switch($staffUserID){
			case 0:
			$savingsQuery.="";
			break;

			default:
			$savingsQuery.=" AND rm=$staffUserID";
			break;
		}
   		$accounts=Yii::app()->db->createCommand($savingsQuery)->queryAll();
		if(!empty($accounts)){
			$staffTotalSavings=0;
			foreach($accounts AS $account){
				$staffTotalSavings+=SavingFunctions::getTotalSavingAccountBalance($account['savingaccount_id']);
			}
		}else{
			$staffTotalSavings=0;
		}
		return $staffTotalSavings;
	}

	public static function getStaffTotalMembers($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$membersQuery="SELECT COUNT(DISTINCT id) AS profileId FROM profiles WHERE profileType IN('STAFF') ";
			switch($staffUserID){
				case 0:
				$membersQuery.="";
				break;

				default:
				$membersQuery.=" AND managerId=$staffUserID";
				break;
			}
		}else{
			$membersQuery="SELECT COUNT(DISTINCT id) AS profileId FROM profiles WHERE profileType IN('STAFF') AND (DATE(createdAt) BETWEEN '$startDate' AND '$endDate')";
			switch($staffUserID){
				case 0:
				$membersQuery.="";
				break;

				default:
				$membersQuery.=" AND managerId=$staffUserID";
				break;
			}
		}
    	$membersCount=Yii::app()->db->createCommand($membersQuery)->queryRow();
		return !empty($membersCount) ? $membersCount['profileId'] : 0;
	}

	public static function getStaffTotalActiveLoanAccounts($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
		}else{
			$accountsQuery="SELECT COUNT(DISTINCT loanaccount_id) AS loanaccount_id FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
			 AND loan_status IN('2','5','6','7')";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['loanaccount_id'] : 0;
	}

	public static function getStaffAverageLoanAccountsInterestRate($staffUserID,$startDate,$endDate,$defaultPeriod){
		if($defaultPeriod === 0){
			$accountsQuery="SELECT AVG(interest_rate) AS interest_rate FROM loanaccounts WHERE 
			loan_status IN('2','5','6','7') ";
		}else{
			$accountsQuery="SELECT AVG(interest_rate) AS interest_rate FROM loanaccounts WHERE (DATE(created_at) BETWEEN '$startDate' AND '$endDate')
			 AND loan_status IN('2','5','6','7') ";
		}
		switch($staffUserID){
			case 0:
			$accountsQuery.="";
			break;

			default:
			$accountsQuery.=" AND rm=$staffUserID";
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($accounts) ? $accounts['interest_rate'] : 0;
	}
}