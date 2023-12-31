<?php

include_once('config.php');
include_once('Utilities.php');

function recordLoanPenalties(){
	$conn    = SaccoDB();
	$today   = date('Y-m-d');
	$loanSQL = "SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10')";
	$result  = $conn->query($loanSQL);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			generatePenalty($row['loanaccount_id'],$conn,$row['repayment_start_date'],$today,date('m'),$row['penalty_amount'],$row['interest_rate'],$row['arrears'],$row['is_frozen']);
		}
	}else{
		echo "No Account Found. \n";
	}
}

function generatePenalty($loanaccountID,$conn,$repaymentDate,$today,$month,$penaltyAmount,$interestRate,$currentArrears,$isFrozen){
	$repaymentMonth = (int)date('m',strtotime($repaymentDate));
	$currentMonth   = (int)date('m',strtotime($today));
	if($currentMonth === $repaymentMonth){
		if(checkIfLoanPaidTimely($loanaccountID,$repaymentDate,$conn) === 0){
			$daysDifference = getDateDifference($repaymentDate,$today);
			switch($daysDifference){
				case 1:
				if(checkIfPenaltyRecorded($loanaccountID,$month,$isFrozen,$conn) === 0){
					createLoanPenalty($loanaccountID,$repaymentDate,$penaltyAmount,$isFrozen,$conn);
					updateLoanRepaymentDate($loanaccountID,$conn,$repaymentDate);
				}else{
					echo "Loan Penalty Already Recorded \n";
				}
				break;

				default:
				echo "Penalty Cannot be Created.\n";
				break;
			}
		}else{
			echo "Loan Paid on Time \n";
		}
	}else{
		echo "Current Month Greater than Repayment Start Month. \n";	
	}
}

function checkIfLoanPaidTimely($loanaccountID,$repaymentDate,$conn){
	$currentMonth = date('m');
	$repaymentSql = "SELECT * FROM loantransactions WHERE DATE(transacted_at) <= '$repaymentDate' AND MONTH(transacted_at)='$currentMonth'
	AND loanaccount_id=$loanaccountID AND is_void IN('0','3','4')";
	$result       = $conn->query($repaymentSql);
	return $result->num_rows > 0 ? 1 : 0;
}

function checkIfPenaltyRecorded($loanaccountID,$date,$isFrozen,$conn){
    $month = date('m', strtotime($date)); // Extract the month from the provided date
    $currentYear = date('Y', strtotime($date)); // Extract the year from the provided date
    if ($isFrozen) {
        $penaltySQL = "SELECT * FROM penaltyaccrued WHERE DATE(date_defaulted) = '$date' AND loanaccount_id = $loanaccountID";
    } else {
        $penaltySQL = "SELECT * FROM penaltyaccrued WHERE MONTH(date_defaulted)='$month' AND YEAR(date_defaulted)='$currentYear'
        AND loanaccount_id = $loanaccountID";
    }
    $result = $conn->query($penaltySQL);
    return $result->num_rows > 0 ? 1 : 0;
}

function createLoanPenalty($loanaccountID,$repaymentDate,$penaltyAmount,$isFrozen,$conn){
    if($isFrozen || $penaltyAmount > 0){
        $penaltySQL = "INSERT INTO penaltyaccrued (loanaccount_id, date_defaulted, penalty_amount)
        VALUES ('$loanaccountID', '$repaymentDate', $penaltyAmount)";
        echo $conn->query($penaltySQL) ? "New Penalty Record Created. \n" : "No Penalty Record Created. \n";
    }else{
        echo "Loan Account Penalty Amount Not Set. \n";
    }
}

function updateLoanRepaymentDate($loanaccountID,$conn,$repaymentDate){
	$nextRepaymentDate = date('Y-m-d',strtotime($repaymentDate.'+ 1 month'));
	$updateSql         = "UPDATE loanaccounts SET repayment_start_date='$nextRepaymentDate' WHERE loanaccount_id='$loanaccountID'";
	echo $conn->query($updateSql) ? "repayment date updated. \n" : "repayment date not updated. \n";
}
/********************************
Invoke Main Method
************************************/
recordLoanPenalties();