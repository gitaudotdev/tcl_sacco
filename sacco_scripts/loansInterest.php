<?php

include_once('config.php');
include_once('Utilities.php');

function accrueLoanInterest(){
	$conn = SaccoDB();
	accrueDailyLoanInterest($conn);
}

function accrueDailyLoanInterest($conn){
	$currentDate = date('Y-m-d');
	$loanSQL     = "SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10') AND is_frozen='0'";
	$result      = $conn->query($loanSQL);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$dateDisbursed = getAccountDateDisbursed($row['loanaccount_id'],$conn);
			$holder = getAccountHolder($conn,$row['user_id']);
			if($holder !== 0){
				foreach($holder AS $user){
					$profileId = $user['id'];
					$branchId  = $user['branchId'];
					$managerId = $user['managerId'];
				}
			}else{
				$profileId = null;
				$branchId  = null;
				$managerId = null;
			}
			if($dateDisbursed != 0){
			   accrueDailyInterest($row['loanaccount_id'],$row['interest_rate'],$dateDisbursed,$currentDate,$conn,$profileId,$branchId,$managerId);
			}else{
			  echo "Loan account not yet disbursed \n";
			}
		}
	}else{
		echo "No Account Found. \n";
	}
}

function getAccountDateDisbursed($accountID,$conn){
	$loanQuery = "SELECT * FROM disbursed_loans WHERE loanaccount_id=$accountID";
	$loan      = $conn->query($loanQuery);
	if($loan->num_rows > 0){
		while($row = $loan->fetch_assoc()){
			$dateDisbursed = $row['disbursed_at'];
		}
	}else{
		$dateDisbursed = 0;
	}
	return $dateDisbursed;
}

function accrueDailyInterest($loanaccountID,$interestRate,$applicationDate,$currentDate,$conn,$profileId,$branchId,$managerId){
	$dailyInterestAccrued = calculateDailyAccrualInterest($loanaccountID,$interestRate,$conn);
	if($dailyInterestAccrued > 0){
		$accrueQuery = "INSERT INTO loaninterests (loanaccount_id,interest_accrued,profileId,branchId,managerId)
		 VALUES('$loanaccountID',$dailyInterestAccrued,'$profileId','$branchId','$managerId')";
		echo $conn->query($accrueQuery) ? "Interest accrued successfully. \n" : "No interest accrued. \n";
	}else{
		echo "Amount inadequate to accrue as interest. \n";
	}
}
/********************************
Invoke Main Method
************************************/
accrueLoanInterest();