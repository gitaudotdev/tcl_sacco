<?php

include_once('config.php');
include_once('Utilities.php');

function revertAccounts(){
	$conn      = SaccoDB();
	$loanQuery = "SELECT * FROM loanaccounts WHERE loan_status IN('4')";
	$result    = $conn->query($loanQuery);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){		
			$loanBalance= getLoanBalance($row['loanaccount_id'],$conn);
			$principalBalance=getLoanPrincipalBalance($conn,$row['loanaccount_id']);
			if($principalBalance > 0){
				revertLoanAccount($row['loanaccount_id'],$conn);
			}else{
				echo "Loan was properly cleared \t";
			}
		}
	}else{
		echo "No Loan Account \t";
	}
}

function revertLoanAccount($loanaccount_id,$conn){
	$revertQuery = "UPDATE loanaccounts SET loan_status='2', account_status='F' WHERE loanaccount_id=$loanaccount_id";
	echo $conn->query($revertQuery) ? "Loan Account Reverted Successfully. \n" : "Loan Account not reverted. \n";
}
/************
INVOKE MAIN METHOD
********************/
revertAccounts();