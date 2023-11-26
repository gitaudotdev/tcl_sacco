<?php

include_once('config.php');
include_once('Utilities.php');

function InitiateStatusUpdates(){
	$conn    = SaccoDB();
	$today   = date('Y-m-d');
	$loanSQL = "SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','8','9','10')";
	$result  = $conn->query($loanSQL);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$loanStatus = $row['loan_status'];
			$accountID  = $row['loanaccount_id'];
			switch($loanStatus){
				case '2':
				updateActiveAccountStatus($accountID,$conn);
				break;

				case '3':
				$accountStatus='A';
				updateAccountStatus($accountID,$conn,$accountStatus);
				break;

				case '4':
				$accountStatus='H';
				updateAccountStatus($accountID,$conn,$accountStatus);
				break;

				case '5':
				$accountStatus='G';
				updateAccountStatus($accountID,$conn,$accountStatus);
				break;

				case '6':
				$accountStatus='G';
				updateAccountStatus($accountID,$conn,$accountStatus);
				break;

				case '7':
				$accountStatus='B';
				updateAccountStatus($accountID,$conn,$accountStatus);
				break;
			}
		}
	}else{
		echo "No Loan Account Found. \n";
	}
}

function updateActiveAccountStatus($accountID,$conn){
	$today=date("Y-m-d");
	$installmentDueDate=getAccountInstallmentDueDate($accountID,$conn);
	if($installmentDueDate != ""){
		$arrearsDays=getDateDifference($installmentDueDate,$today);
		if($arrearsDays <= 0){
			$risk="A";
			$crbStatus="a";	
		}elseif($arrearsDays >= 0 && $arrearsDays <=30){
			updateDefaultedLoanAccount($accountID,$conn);
			$risk="A";
			$crbStatus="a";
		}elseif($arrearsDays >30 && $arrearsDays <=90 ){
			updateDefaultedLoanAccount($accountID,$conn);
			$risk="B";
			$crbStatus="a";
		}elseif($arrearsDays >90 && $arrearsDays <=180){
			updateDefaultedLoanAccount($accountID,$conn);
			$risk="C";
			$crbStatus="a";
		}elseif($arrearsDays >180 && $arrearsDays <=360){
			updateDefaultedLoanAccount($accountID,$conn);
			$risk="D";
			$crbStatus="b";
		}else{
			updateDefaultedLoanAccount($accountID,$conn);
			$risk="E";
			$crbStatus="b";
		}
	}else{
		$risk="A";
		$crbStatus="a";
	}
	updateAccountPerformanceStatus($accountID,$conn,$crbStatus,$risk);
}

function getAccountInstallmentDueDate($accountID,$conn){
	$matureQuery="SELECT * FROM loan_maturities WHERE loanaccount_id=$accountID";
	$result=$conn->query($matureQuery);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$dueDate=date("Y-m-d",strtotime($row['maturity_date']));
		}
	}else{
		$dueDate="";
	}
	return $dueDate;
}

function updateAccountPerformanceStatus($accountID,$conn,$crbStatus,$risk){
	$updateSql = "UPDATE loanaccounts SET crb_status='$crbStatus', performance_level='$risk' WHERE loanaccount_id='$accountID'";
	echo $conn->query($updateSql) ? "Account status updated. \n" : "Account status not updated. \n";
}

function updateAccountStatus($accountID,$conn,$accountStatus){
	$updateSql = "UPDATE loanaccounts SET account_status='$accountStatus' WHERE loanaccount_id='$accountID'";
	echo $conn->query($updateSql) ? "Account status updated. \n" : "Account status not updated. \n";
}

function updateDefaultedLoanAccount($accountID,$conn){
	$updateSql="UPDATE loanaccounts SET loan_status='7' WHERE loanaccount_id='$accountID'";
	echo $conn->query($updateSql) ? "Defaulted Account status updated. \n" : "Defaulted Account status not updated. \n";
}
/********************************
Invoke Main Method
************************************/
InitiateStatusUpdates();