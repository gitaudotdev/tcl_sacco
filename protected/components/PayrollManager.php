<?php

class PayrollManager{
	/********************
	 0: Failed
	 1: Successful
	 2: Duplicate
	 3: No Payroll Transaction 
	*************************************/
public static function processPayroll($userID,$staffID,$branchID,$salesCommision,$collectionsCommision,$totalLoan,$grossSalary,$netSalary,$payMonth,$payYear){
	$respectiveMonth=CommonFunctions::getMonthName((int)$payMonth);
	$payrollPeriod=$respectiveMonth.'-'.$payYear;
	$user=Profiles::model()->findByPk($userID);
	switch(PayrollManager::restrictDuplicatePayroll($userID,$payMonth,$payYear)){
		case 0:
		$fullName          =  strtoupper($user->ProfileFullName);
		$clientPhoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
		$phoneNumber       = '254'.substr($clientPhoneNumber,-9);
		//B2C MPESA
		$commandID='SalaryPayment';
		$remarks="Staff monthly salary payment for $fullName";
		$payStatus=LoanManager::B2CTransaction($commandID,$netSalary,$phoneNumber,$remarks);
		switch($payStatus){
			case 0:
			$processStatus=4;
			break;

			case 1:
			$transactionID=PayrollManager::recordPayrollTransaction($staffID,$netSalary);
			if($transactionID > 0){
				switch(PayrollManager::recordPayrollLog($userID,$branchID,$salesCommision,$collectionsCommision,$totalLoan,$grossSalary,$netSalary,$payMonth,$payYear,$transactionID)){
					case 0:
					PayrollManager::deleteTransaction($transactionID);
					$processStatus=0;
					break;

					case 1:
					if($totalLoan > 0){
						PayrollManager::checkOffLoan($userID,$totalLoan);
					}
					$message = " Your ".$payrollPeriod." salary is now processed and disbursed.\nLoan if any, has been deducted.\nThank you!";
					$textMessage = "Dear ".strtoupper($user->firstName).",".$message;
					$numbers=array();
					array_push($numbers,$clientPhoneNumber);
					SMS::broadcastSMS($numbers,$textMessage,'29',$user->id);
					$processStatus=1;
					break;
				}
			}else{
				$processStatus=3;
			}
			break;

			default:
			$processStatus=$payStatus;
			break;
		}
		break;

		case 1:
		$processStatus=2;
		break;
	}
	return $processStatus;
}

public static function checkBoundedPayrollPeriod($payMonth,$payYear){
	$currentMonth=(int)date('m');
	$currentYear=(int)date('Y');
	if($payYear > $currentYear){
		$boundStatus=5;
	}else if($payYear < $currentYear){
		$boundStatus=4;
	}else if($payYear == $currentYear){
		if($payMonth > $currentMonth){
			$boundStatus = 5;
		}else if($payMonth < $currentMonth){
			$boundStatus = 4;
		}else if($payMonth == $currentMonth){
			$boundStatus =1;
		}else{
			$boundStatus =5;
		}
	}else{
		$boundStatus=5;
	}
	return $boundStatus;
}

public static function restrictDuplicatePayroll($userID,$payMonth,$payYear){
	$duplicateQuery = "SELECT * FROM payroll WHERE user_id=$userID AND payroll_month=$payMonth
	 AND payroll_year=$payYear";
	$records=Payroll::model()->findAllBySql($duplicateQuery);
	return !empty($records) ? 1 : 0;
}

public static function recordPayrollTransaction($staffID,$netSalary){
	$transaction=new PayrollTransactions();
	$transaction->staff_id=$staffID;
	$transaction->amount=$netSalary;
	$transaction->processed_by=Yii::app()->user->user_id;
	return $transaction->save() ? $transaction->id : 0;
}

public static function recordPayrollLog($userID,$branchID,$salesCommision,$collectionsCommision,
	$totalLoan,$grossSalary,$netSalary,$payMonth,$payYear,$transactionID){
	$payLog=new Payroll;
	$payLog->user_id=$userID;
	$payLog->branch_id=$branchID;
	$payLog->transaction_id=$transactionID;
	$payLog->sales_commision=$salesCommision;
	$payLog->collections_commision=$collectionsCommision;
	$payLog->loan_paid=$totalLoan;
	$payLog->gross_salary=$grossSalary;
	$payLog->net_salary=$netSalary;
	$payLog->payroll_month=$payMonth;
	$payLog->payroll_year=$payYear;
	$payLog->processed_by=Yii::app()->user->user_id;
	$payLog->processed_at = date('Y-m-d H:i:s');
	return $payLog->save() ? 1 : 0;
}

public static function deleteTransaction($transactionID){
	$transaction = PayrollTransactions::model()->findByPk($transactionID);
	$transaction->is_complete = '1';
	$transaction->save();
}

public static function checkOffLoan($userID,$totalLoan){
	$profile   = Profiles::model()->findByPk($userID);
	$loanQuery = "SELECT loanaccount_id FROM loanaccounts WHERE user_id=$userID AND loan_status
	NOT IN('0','1','3','4','8','9','10') ORDER BY loanaccount_id DESC LIMIT 1";
	$loan      = Loanaccounts::model()->findBySql($loanQuery);
	if(!empty($loan)){
		LoanManager::repayLoanAccount($loan->loanaccount_id,$totalLoan,'0',$profile->ProfilePhoneNumber);
	}
}

}