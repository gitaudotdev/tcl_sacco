<?php

include_once('config.php');
include_once('Utilities.php');

function createLoanMaturityRecords(){
	$conn=SaccoDB();
	$productSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('3')";
	$result=$conn->query($productSql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){		
			createMaturityRecord($row['loanaccount_id'],$conn);
		}
	}
}

function createMaturityRecord($loanaccount_id,$conn){
	$maturityStatus=checkIfMaturityRecordExists($loanaccount_id,$conn);
	switch($maturityStatus){
		case 0:
		$maturity_date=getLoanMaturityDate($loanaccount_id,$conn);
		createNewMaturityRecord($loanaccount_id,$conn,$maturity_date);
		break;

		case 1:
		echo "Maturity Record Already Available. \n";
		break;
	}
}

function getLoanMaturityDate($loanaccount_id,$conn){
	$loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id=$loanaccount_id";
	$result=$conn->query($loanSQL);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$repaymentStartDate=date("Y-m-d",strtotime($row['created_at']));
			$repaymentPeriods=$row['repayment_period'];
		}
		if($repaymentPeriods > 1){
			$durationTerm='months';
		}else{
			$durationTerm='month';
		}
		$maturityDate = date('Y-m-d',strtotime($repaymentStartDate. "+$repaymentPeriods $durationTerm"));
		return $maturityDate;
	}else{
		echo "NO LOAN ACCOUNT \n";
	}
}

function createNewMaturityRecord($loanaccount_id,$conn,$maturity_date){
	$maturitySQL = "INSERT INTO loan_maturities (loanaccount_id,maturity_date)
	VALUES('$loanaccount_id','$maturity_date')";
	echo $conn->query($maturitySQL) ? "New Maturity Record Created!!! \n" : "No Maturity Record Created. \n";
}

function checkIfMaturityRecordExists($loanaccount_id,$conn){
	$checkMaturitySql = "SELECT * FROM loan_maturities WHERE loanaccount_id=$loanaccount_id";
	$result = $conn->query($checkMaturitySql);
	return $result->num_rows > 0 ? 1 : 0;
}
/********************************
Invoke Main Method
************************************/
createLoanMaturityRecords();