<?php

class Reports{

	public static function getCollectionsReport(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowers=BorrowerFunctions::getAllSaccoBorrowers();
			break;

			case '1':
			$borrowers=BorrowerFunctions::getSaccoBranchBorrowers();
			break;
		}
	}

	public static function getDisbursementReport(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowers=BorrowerFunctions::getAllSaccoBorrowers();
			break;

			case '1':
			$borrowers=BorrowerFunctions::getSaccoBranchBorrowers();
			break;
		}
	}

	public static function getFeesReport(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowers=BorrowerFunctions::getAllSaccoBorrowers();
			break;

			case '1':
			$borrowers=BorrowerFunctions::getSaccoBranchBorrowers();
			break;
		}
	}

	public static function getLoanOfficersReport(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowers=BorrowerFunctions::getAllSaccoBorrowers();
			break;

			case '1':
			$borrowers=BorrowerFunctions::getSaccoBranchBorrowers();
			break;
		}
	}

	public static function getMonthlyReport(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowers=BorrowerFunctions::getAllSaccoBorrowers();
			break;

			case '1':
			$borrowers=BorrowerFunctions::getSaccoBranchBorrowers();
			break;
		}
	}

	public static function getAtGlanceReport(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowers=BorrowerFunctions::getAllSaccoBorrowers();
			break;

			case '1':
			$borrowers=BorrowerFunctions::getSaccoBranchBorrowers();
			break;
		}
	}

	/**************************
	Loading Some Required Data
	***************************/
	public static function getRelationManagers(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$staffQuery = "SELECT * from profiles WHERE profileType IN('STAFF') ";
		switch(Yii::app()->user->user_level){
			case '0':
			$staffQuery .= "";
			break;

			case '1':
			$staffQuery .= " AND branchId=$userBranch";
			break;

			case '2':
			$staffQuery .= " AND id=$userID";
			break;

			case '3':
			$staffQuery .= "";
			break;
		}
		return Profiles::model()->findAllBySql($staffQuery);
	}

	public static function getAllSaccoBranches(){
		$branchQuery = "SELECT * from branch WHERE is_merged='0' ORDER BY name ASC";
		return Branch::model()->findAllBySql($branchQuery);
	}

	public static function getAllBranches(){
		$userBranch=Yii::app()->user->user_branch;
		$branchQuery = "SELECT * from branch WHERE is_merged='0'";
		switch(Yii::app()->user->user_level){
			case '0':
			$branchQuery .= "";
			break;

			default:
			$branchQuery .= " AND branch_id=$userBranch";
			break;
		}
		$branchQuery .= " ORDER BY name ASC";
		return Branch::model()->findAllBySql($branchQuery);
	}

	public static function LoadRelationManagers(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$managerQuery="SELECT * FROM profiles WHERE profileType IN('STAFF')";
		switch(Yii::app()->user->user_level){
			case '0':
			$managerQuery.="";
			break;

			case '1':
			$managerQuery.=" AND branchId=$userBranch";
			break;

			case '2':
			$managerQuery.=" AND id=$userID";
			break;
		}
		$managerQuery.=" ORDER BY firstName ASC";
		return Profiles::model()->findAllBySql($managerQuery);
	}

	public static function LoadEmployers(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$employerSQL="SELECT DISTINCT(employments.employer) AS employer FROM employments,profiles WHERE employments.profileId=profiles.id";
		switch(Yii::app()->user->user_level){
			case '0':
			$employerSQL.=" ";
			break;

			case '1':
			$employerSQL.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$employerSQL.=" AND profiles.managerId=$userID";
			break;
		}
		$employerSQL.=" ORDER BY employments.employer ASC";
		return Employments::model()->findAllBySql($employerSQL);
	}

	public static function LoadBranchEmployers($branchID){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$employerSQL="SELECT DISTINCT(employments.employer) AS employer FROM employments,profiles WHERE profiles.id=employments.profileId 
		AND profiles.branchId=$branchID";
		switch(Yii::app()->user->user_level){
			case '0':
			$employerSQL.=" ";
			break;

			case '1':
			$employerSQL.=" ";
			break;

			case '2':
			$employerSQL.=" AND profiles.managerId=$userID";
			break;
		}
		$employerSQL.=" ORDER BY employments.employer ASC";
		return Employments::model()->findAllBySql($employerSQL);
	}

	public static function LoadRelationManagerEmployers($staffID){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$employerSQL="SELECT DISTINCT(employments.employer) AS employer FROM employments,profiles WHERE employments.profileId=profiles.id 
		AND profiles.managerId=$staffID";
		switch(Yii::app()->user->user_level){
			case '0':
			$employerSQL.=" ";
			break;

			case '1':
			$employerSQL.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$employerSQL.=" ";
			break;
		}
		$employerSQL.=" ORDER BY employments.employer ASC";
		return Employments::model()->findAllBySql($employerSQL);
	}
	

	public static function LoadBranchRelationManagers($branchID){
		$managersQuery="SELECT * FROM profiles WHERE profileType IN('STAFF') AND branchId=$branchID";
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$managersQuery.=" ";
			break;

			case '1':
			$managersQuery.=" ";
			break;

			case '2':
			$managersQuery.=" AND id=$userID";
			break;
		}
		return Profiles::model()->findAllBySql($managersQuery);
	}

	public static function LoadBorrowers(){
		$userBranch=Yii::app()->user->user_branch;
		$userIUD=Yii::app()->user->user_id;
		$borrowerQuery="SELECT DISTINCT(profiles.idNumber),profiles.firstName,profiles.lastName,profiles.id FROM profiles,loanaccounts
		 WHERE profiles.id=loanaccounts.user_id";
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowerQuery.="";
			break;

			case '1':
			$borrowerQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$borrowerQuery.=" AND profiles.managerId=$userIUD";
			break;

			case '3':
			$borrowerQuery.=" AND profiles.id=$userIUD";
			break;
		}
		$borrowerQuery.=" ORDER BY profiles.firstName ASC";
		return Profiles::model()->findAllBySql($borrowerQuery);
	}

	public static function LoadAccounts(){
		$accountQuery="SELECT * FROM loanaccounts,profiles WHERE loanaccounts.user_id=profiles.id 
		AND loanaccounts.loan_status NOT IN('0','1','3','8','9','10')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$accountQuery.="";
			break;

			case '1':
			$accountQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$accountQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$accountQuery.=" AND profiles.id=$userID";
			break;
		}
		$accountQuery.=" ORDER BY profiles.firstName,profiles.lastName ASC";
		return Loanaccounts::model()->findAllBySql($accountQuery);
	}

	public static function LoadBranchAccounts($branchID){
		$accountQuery="SELECT * FROM loanaccounts,profiles WHERE loanaccounts.user_id=profiles.id 
		AND loanaccounts.loan_status NOT IN('0','1','3','8','9','10') AND profiles.branchId=$branchID";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$accountQuery.="";
			break;

			case '1':
			$accountQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$accountQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$accountQuery.=" AND profiles.id=$userID";
			break;
		}
		$accountQuery.=" ORDER BY profiles.firstName,profiles.lastName ASC";
		return Loanaccounts::model()->findAllBySql($accountQuery);
	}

	public static function LoadRelationManagerAccounts($staff){
		$accountQuery="SELECT * FROM loanaccounts,profiles WHERE loanaccounts.user_id=profiles.user_id 
		AND loanaccounts.loan_status NOT IN('0','1','3','8','9','10') AND profiles.managerId=$staff";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$accountQuery.="";
			break;

			case '1':
			$accountQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$accountQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$accountQuery.=" AND profiles.id=$userID";
			break;
		}
		$accountQuery.=" ORDER BY profiles.firstName,profiles.lastName ASC";
		return Loanaccounts::model()->findAllBySql($accountQuery);
	}

	public static function LoadBranchBorrowers($branchID){
		$userIUD=Yii::app()->user->user_id;
		$borrowerQuery="SELECT DISTINCT(profiles.idNumber),profiles.firstName,profiles.lastName,profiles.id FROM profiles,loanaccounts
		WHERE profiles.id=loanaccounts.user_id AND profiles.branchId=$branchID";
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowerQuery.="";
			break;

			case '1':
			$borrowerQuery.="";
			break;

			case '2':
			$borrowerQuery.=" AND loanaccounts.rm=$userIUD";
			break;

			case '3':
			$borrowerQuery.=" AND loanaccounts.user_id=$userIUD";
			break;
		}
		$borrowerQuery.=" ORDER BY profiles.firstName ASC";
		return Profiles::model()->findAllBySql($borrowerQuery);
	}

	public static function LoadRelationManagerBorrowers($rm){
		$rmNum=(int)$rm;
		$borrowerSQL="SELECT DISTINCT(profiles.idNumber),profiles.firstName,profiles.lastName,profiles.id
		 FROM profiles,loanaccounts WHERE loanaccounts.user_id=profiles.id AND loanaccounts.rm=$rmNum
		 AND profiles.id NOT IN($rmNum) ORDER BY profiles.firstName,profiles.lastName ASC";
		return Profiles::model()->findAllBySql($borrowerSQL);
	}

	public static function LoadFilteredBorrowersReport($start_date,$end_date,$branch,$staff,$employer,$borrower){
		$borrowerQuery="SELECT * FROM profiles,employments WHERE employments.profileId=profiles.id AND profiles.profileType IN('MEMBER')
		AND (DATE(profiles.createdAt) BETWEEN '$start_date' AND '$end_date')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$borrowerQuery.="";
			break;

			case '1':
			$borrowerQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$borrowerQuery.=" AND profiles.id=$userID";
			break;
		}
		echo Reports::getFilteredBorrowers($branch,$staff,$borrower,$employer,$borrowerQuery);	
	}

	public static function getFilteredBorrowers($branch,$staff,$borrower,$employer,$borrowerQuery){
		if($branch !=0){
			$borrowerQuery.=" AND profiles.branchId=$branch";
		}
		if($staff !=0){
			$borrowerQuery.=" AND profiles.managerId=$staff";
		}
		if($borrower !=0){
			$borrowerQuery.=" AND profiles.id=$borrower";
		}
		if($employer != '0'){
			$borrowerQuery.=" AND employments.employer='$employer'";
		}
		$borrowers=Profiles::model()->findAllBySql($borrowerQuery);
		$htmlTable=Tabulate::createMemberDetailsTable($borrowers);
		echo $htmlTable;
	}

	public static function LoadFilteredCollectionsReport($start_date,$end_date,$branch,$staff,$borrower){
		$collectQuery="SELECT * FROM loanrepayments,loanaccounts WHERE loanaccounts.loanaccount_id=loanrepayments.loanaccount_id
		AND (DATE(loanrepayments.repaid_at) BETWEEN '$start_date' AND '$end_date') AND loanrepayments.is_void NOT IN('1')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$collectQuery.="";
			break;

			case '1':
			$collectQuery.=" AND loanaccounts.rm=$userID";	
			break;

			case '3':
			$collectQuery.=" AND loanaccounts.user_id=$userID";	
			break;
		}
		echo Reports::getAllCollections($branch,$staff,$borrower,$collectQuery);
	}

	public static function getAllCollections($branch,$staff,$borrower,$collectQuery){	
		if($branch != 0){
			$collectQuery.=" AND loanaccounts.branch_id=$branch";	
		}
		if($staff != 0){
			$collectQuery.=" AND loanaccounts.rm=$staff";	
		}
		if($borrower != 0){
			$collectQuery.=" AND loanaccounts.user_id=$borrower";	
		}
		$repayments=Loanrepayments::model()->findAllBySql($collectQuery);
		$htmlTable=Tabulate::createMemberCollectionsDetailsTable($repayments);
		echo $htmlTable;
	}

	public static function LoadFilteredArrearsReport($start_date,$end_date,$branch,$staff,$borrower){
		$arrearsQuery="SELECT * FROM loanaccounts WHERE (DATE(loanaccounts.created_at) BETWEEN '$start_date' AND '$end_date') AND loanaccounts.arrears > 0";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$arrearsQuery.="";
			break;

			case '1':
			$arrearsQuery.=" AND loanaccounts.rm=$userID";	
			break;

			case '3':
			$arrearsQuery.=" AND loanaccounts.user_id=$userID";	
			break;
		}
		echo Reports::getAllArrears($branch,$staff,$borrower,$arrearsQuery);
	}

	public static function getAllArrears($branch,$staff,$borrower,$arrearsQuery){	
		if($branch != 0){
			$arrearsQuery.=" AND loanaccounts.branch_id=$branch";	
		}
		if($staff != 0){
			$arrearsQuery.=" AND loanaccounts.rm=$staff";	
		}
		if($borrower != 0){
			$arrearsQuery.=" AND loanaccounts.user_id=$borrower";	
		}
		$arrearsQuery.=" ORDER BY loanaccounts.arrears DESC";
		$accounts=Loanaccounts::model()->findAllBySql($arrearsQuery);
		$htmlTable=Tabulate::createMemberArrearsDetailsTable($accounts);
		echo $htmlTable;
	}

	public static function LoadFilteredDisbursementReport($start_date,$end_date,$branch,$staff,$borrower){
		$disburseQuery="SELECT * FROM loanaccounts,disbursed_loans WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id
		AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$start_date' AND '$end_date') AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$disburseQuery.="";
			break;

			case '1':
			$disburseQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$disburseQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}
		echo Reports::getDisbursedLoans($branch,$staff,$borrower,$disburseQuery);
	}

	public static function getDisbursedLoans($branch,$staff,$borrower,$disburseQuery){
		if($branch != 0){
			$disburseQuery.=" AND loanaccounts.branch_id=$branch";
		}
		if($staff != 0){
			$disburseQuery.=" AND loanaccounts.rm=$staff";
		}
		if($borrower != 0){
			$disburseQuery.=" AND loanaccounts.user_id=$borrower";
		}
		$loanaccounts = Loanaccounts::model()->findAllBySql($disburseQuery);
		$htmlTable    = Tabulate::createMemberDisbursementDetailsTable($loanaccounts);
		echo $htmlTable;
	}

	public static function LoadFilteredExecutiveSummaryReport($startDate,$endDate,$branch,$staff,$defaultPeriod,$summary){
		switch($summary){
			case 2:
			if($staff == 0){
				if($branch == 0){
					$staffquery   = "SELECT * FROM profiles WHERE profileType IN('STAFF') ORDER BY firstName,lastName ASC";
					$staffMembers = Profiles::model()->findAllBySql($staffquery);
				}else{
					$staffquery   = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND branchId=$branch ORDER BY firstName,lastName ASC";
					$staffMembers = Profiles::model()->findAllBySql($staffquery);
				}
			}else{
				$staffquery   = "SELECT * FROM profiles WHERE id=$staff ORDER BY firstName,lastName ASC";
				$staffMembers = Profiles::model()->findBySql($staffquery);
			}
			$summaryTable=Tabulate::getStaffExecutiveSummaryContent($staffMembers,$startDate,$endDate,$defaultPeriod);
			break;

			default:
			if($branch == 0){
				$branches=Reports::getAllBranches();
			}else{
				$branches=Branch::model()->findByPk($branch);
			}
			$summaryTable=Tabulate::getBranchExecutiveSummaryContent($branches,$startDate,$endDate,$defaultPeriod);
			break;
		}
		echo $summaryTable;
	}
}