<?php
include_once('config.php');
include_once('Utilities.php');

function createLoanClearedRecord(){
	$conn       = SaccoDB();
	$productSql = "SELECT * FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
	$result     = $conn->query($productSql);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){		
			$loanBalance= getLoanBalance($row['loanaccount_id'],$conn);
			$principalBalance = getLoanPrincipalBalance($conn,$row['loanaccount_id']);
			if($principalBalance < 1 ){
				updateLoanAccount($row['loanaccount_id'],$conn);
				$overpayment  = abs($principalBalance);
				$date_cleared = getLastLoanTransactionDate($row['loanaccount_id'],$conn);
				newLoanClearanceRecord($row['loanaccount_id'],$date_cleared,$overpayment,$conn);
			}else{
				echo "Loan Account Still Running. \n"; 
			}
		}
	}
}

function updateLoanAccount($loanaccount_id,$conn){
	$updateSql = "UPDATE loanaccounts SET loan_status='4',account_status='H',performance_level='A' WHERE loanaccount_id=$loanaccount_id";
	echo $conn->query($updateSql) ? "Loan Account updated. \n" : "Loan Account not updated. \n";
}

function getLastLoanTransactionDate($loanaccount_id,$conn){
	$transactQuery = "SELECT * FROM loantransactions WHERE loanaccount_id=$loanaccount_id
	 AND is_void IN('0','2','3','4') ORDER BY loantransaction_id DESC LIMIT 1 ";
	$result = $conn->query($transactQuery);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$dateCleared = $row['date'];
		}
	}else{
		$dateCleared = date('Y-m-d');
	}
	return $dateCleared;
}

function newLoanClearanceRecord($loanaccount_id,$date_cleared,$overpayment,$conn){
	switch(checkIfClearanceRecordExists($loanaccount_id,$conn)){
		case 0:
		updateLoanAccount($loanaccount_id,$conn);
		$clearanceSQL = "INSERT INTO clearedloans (loanaccount_id,date_cleared,overpayment) VALUES('$loanaccount_id','$date_cleared','$overpayment')";
		echo $conn->query($clearanceSQL) ? "New Clearance Created!!! \n" : "No Clearance Created. \n";
		break;

		case 1:
		$updateSql = "UPDATE loanaccounts SET loan_status='4',account_status='H',performance_level='A' WHERE loanaccount_id=$loanaccount_id";
		$conn->query($updateSql);
		echo "No Clearance Created. Duplicate Records. \n";
		break;
	}
}

function checkIfClearanceRecordExists($loanaccount_id,$conn){
	$clearQuery = "SELECT * FROM clearedloans WHERE loanaccount_id=$loanaccount_id";
	$result = $conn->query($clearQuery);
	return $result->num_rows > 0 ? 1 : 0;
}
/********************************
Invoke Main Method
************************************/
createLoanClearedRecord();