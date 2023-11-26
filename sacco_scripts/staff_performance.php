<?php

include_once('config.php');
include_once('Utilities.php');

function broadcastStaffPerformance(){
	$startDate = date("Y-m-01");
	$endDate   = date("Y-m-t");
	$alertType = '8';
	$conn      = SaccoDB();
	$staffQuery = "SELECT profiles.id AS user_id, profiles.firstName AS firstName, profiles.branchId AS branchId FROM profiles,auths
	WHERE profiles.id=auths.profileId AND auths.authStatus IN('ACTIVE') AND auths.level IN('STAFF')";
	$member     = $conn->query($staffQuery);
	if($member->num_rows > 0) {
		while($row = $member->fetch_assoc()){
			$staff    = $row['user_id'];
			$branch   = $row['branchId'];
			$phones   = getProfileContactByTypeOrderDesc($conn,$staff,'PHONE');
			foreach($phones AS $phone){
				$pNumber = $phone['contactValue'];
			}
			$phoneNumber = $pNumber ? "+254".substr($pNumber,-9) : 0;
			$firstName   = ucfirst($row['firstName']);
			$defaultSupervisor = getProfileRecentSettingByType($conn,$staff,'SUPERVISORIAL_ROLE');
			$supervisory = $defaultSupervisor ? $defaultSupervisor : 'DISABLED';
			switch($supervisory){
				case 'DISABLED':
				$defaultSalesTarget  = getProfileRecentSettingByType($conn,$staff,'SALES_TARGET');
				$salesTarget         = $defaultSalesTarget ? floatval($defaultSalesTarget) : 0;
				$totalSales          = getTotalStaffSales($staff,$startDate,$endDate,$conn);
				$defaultCollectionsTarget  = getProfileRecentSettingByType($conn,$staff,'COLLECTIONS_TARGET');
				$collectionsTarget    = $defaultCollectionsTarget ? floatval($defaultCollectionsTarget) : 0;
				$totalCollections     = getTotalStaffCollections($staff,$startDate,$endDate,$conn);
				break;

				default:
				if(getBranchDetails($branch,$conn) != 0){
					$branchArray = getBranchDetails($branch,$conn);
					foreach($branchArray AS $selectedBranch){
						$salesTarget      = $selectedBranch['sales_target'];
						$collectionsTarget= $selectedBranch['collections_target'];
					}
				}else{
					$salesTarget=0;
					$collectionsTarget=0;
				}
				$totalSales       = getTotalBranchSales($branch,$startDate,$endDate,$conn);
				$totalCollections = getTotalBranchCollections($branch,$startDate,$endDate,$conn);
				break;
			}
			$salesPercent             = getPerformancePercentage($salesTarget,$totalSales);
			$salesPercentMeaning      = determinePerformanceMeaning($salesPercent);
			$collectionsPercent       = getPerformancePercentage($collectionsTarget,$totalCollections);
			$collectionsPercentMeaning= determinePerformanceMeaning($collectionsPercent);
			constructAndBroadcastMessage($firstName,$salesPercent,$salesPercentMeaning,$collectionsPercent,$collectionsPercentMeaning,$phoneNumber,$conn,$row['is_supervisor'],$alertType,$staff);
		}
	}else{
		echo "No Staff to send SMS\n";
	}
}

function getTotalStaffSales($staff,$startDate,$endDate,$conn){
	$salesQuery  = "SELECT COALESCE(SUM(disbursed_loans.amount_disbursed),0) AS amount_disbursed FROM disbursed_loans,loanaccounts 
	WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$startDate' AND '$endDate')
	AND loanaccounts.rm=$staff";
	$transaction = $conn->query($salesQuery);
	if($transaction->num_rows > 0) {
		while($row = $transaction->fetch_assoc()){
			$periodicalSales = $row['amount_disbursed'];
		}
	}else{
		$periodicalSales=0;
	}
	return $periodicalSales;
}

function getTotalStaffCollections($staff,$startDate,$endDate,$conn){
	$transactionQuery = "SELECT COALESCE(SUM(loantransactions.amount),0) as amount FROM loantransactions,loanrepayments,loanaccounts
	WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND users.user_id=loanaccounts.user_id AND (DATE(loantransactions.transacted_at) BETWEEN '$startDate' AND '$endDate') 
	AND loantransactions.is_void IN('0','3','4') AND loanaccounts.rm=$staff";
	$transaction     = $conn->query($transactionQuery);
	if($transaction->num_rows > 0) {
		while($row = $transaction->fetch_assoc()){
			$periodicalCollections = $row['amount'];
		}
	}else{
		$periodicalCollections = 0;
	}
	return $periodicalCollections;
}

function getBranchDetails($branch,$conn){
	$branchQeury = "SELECT sales_target,collections_target FROM branch WHERE branch_id=$branch";
	$branch      = $conn->query($branchQeury);
	if(!empty($branch)) {
		while($row = $branch->fetch_assoc()){
			$branchArray[] = $row;
		}
		return $branchArray;
	}else{
		return 0;
	}
}

function getTotalBranchSales($branch,$startDate,$endDate,$conn){
	$salesQuery  = "SELECT COALESCE(SUM(disbursed_loans.amount_disbursed),0) AS amount_disbursed FROM disbursed_loans,loanaccounts,users WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.user_id=users.user_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$startDate' AND '$endDate') AND users.branch_id=$branch";
	$transaction = $conn->query($salesQuery);
	if($transaction->num_rows > 0) {
		while($row = $transaction->fetch_assoc()){
			$periodicalSales = $row['amount_disbursed'];
		}
	}else{
		$periodicalSales = 0;
	}
	return $periodicalSales;
}

function getTotalBranchCollections($branch,$startDate,$endDate,$conn){
	$transactionQuery = "SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanrepayments,loanaccounts
	WHERE loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
	AND (DATE(loantransactions.transacted_at) BETWEEN '$startDate' AND '$endDate') 
	AND loantransactions.is_void IN('0','3','4') AND loanaccounts.branch_id=$branch";
	$transaction  = $conn->query($transactionQuery);
	if($transaction->num_rows > 0) {
		while($row = $transaction->fetch_assoc()){
			$periodicalCollections = $row['amount'];
		}
	}else{
		$periodicalCollections = 0;
	}
	return $periodicalCollections;
}

function constructAndBroadcastMessage($firstName,$salesPercent,$salesPercentMeaning,$collectionsPercent,$collectionsPercentMeaning,$phoneNumber,$conn,$supervisor,$alertType,$userID){
	switch($supervisor){
		case '0':
		$message="Dear $firstName, Your Month-to-date Performance is\nSales : $salesPercent% = $salesPercentMeaning\nCollections: $collectionsPercent% = $collectionsPercentMeaning\nSee dashboard for more info.\nThank you!";
		sendNotification($message,$phoneNumber,$conn,$alertType,$userID);
		echo "$firstName: has received the notification : $message <br>";
		break;

		case '1':
		$message="Dear $firstName, Your Month-to-date Branch Performance is\nSales : $salesPercent% = $salesPercentMeaning\nCollections: $collectionsPercent% = $collectionsPercentMeaning\nSee dashboard for more info.\nThank you!";
		sendNotification($message,$phoneNumber,$conn,$alertType,$userID);
		echo "$firstName: has received the notification : $message <br>";
		break;
	}
}
/************
INVOKE MAIN METHOD
********************/
broadcastStaffPerformance();