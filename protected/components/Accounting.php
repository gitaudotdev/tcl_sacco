<?php

class Accounting{

	public static function getLoanPrincipalRepayments($branch,$staff,$start_date,$end_date){
		$principalQuery="SELECT SUM(loanrepayments.principal_paid) as principal_paid FROM loanrepayments,loantransactions,loanaccounts WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id
		AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND (DATE(loantransactions.transacted_at) BETWEEN '$start_date' AND '$end_date')
		 AND loantransactions.is_void IN('0','3','4')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$principalQuery.="";
			break;

			case '1':
			$principalQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$principalQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$principalQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}

		if($branch !=0){
			$principalQuery.=" AND loanaccounts.branch_id=$branch";
		}

		if($staff !=0){
			$principalQuery.=" AND loanaccounts.rm=$staff";
		}
		$repayment=Loanrepayments::model()->findBySql($principalQuery);
		if(!empty($repayment)){
			$principalRepaid=$repayment->principal_paid;
		}else{
			$principalRepaid=0;	
		}
		return $principalRepaid;
	}

	public static function getLoanInterestRepayments($branch,$staff,$start_date,$end_date){
		$interestQuery="SELECT SUM(loanrepayments.interest_paid) as interest_paid FROM loanrepayments,loantransactions,loanaccounts WHERE 
		loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id
		AND (DATE(loantransactions.transacted_at) BETWEEN '$start_date' AND '$end_date') AND loantransactions.is_void IN('0','3','4')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$interestQuery.="";
			break;

			case '1':
			$interestQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$interestQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$interestQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}

		if($branch !=0){
			$interestQuery.=" AND loanaccounts.branch_id=$branch";
		}

		if($staff !=0){
			$interestQuery.=" AND loanaccounts.rm=$staff";
		}
		$interest=Loanrepayments::model()->findBySql($interestQuery);
		if(!empty($interest)){
			$interestRepaid=$interest->interest_paid;
		}else{
			$interestRepaid=0;
		}
		return $interestRepaid;
	}
	
	
	
	 public static function getInsuranceFee($branch,$staff,$start_date,$end_date){
        $interestQuery="SELECT SUM(loanaccounts.insurance_fee) as insurance_fee FROM loanaccounts";
//        $interestQuery="SELECT SUM(loanaccounts.insurance_fee) as insurance_fee FROM loanaccounts,loanrepayments,loantransactions WHERE
//		loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id
//		AND (DATE(loantransactions.transacted_at) BETWEEN '$start_date' AND '$end_date') AND loantransactions.is_void IN('0','3','4')";

        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;

        switch(Yii::app()->user->user_level){
            case '0':
                $interestQuery.="";
                break;

            case '1':
                $interestQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $interestQuery.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $interestQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }

        if($branch !=0){
            $interestQuery.=" AND loanaccounts.branch_id=$branch";
        }

        if($staff !=0){
            $interestQuery.=" AND loanaccounts.rm=$staff";
        }
        $interest=Loanaccounts::model()->findBySql($interestQuery);
        //var_dump($interest);exit;
        if(!empty($interest)){
            $interestRepaid=$interest->insurance_fee;
        }else{
            $interestRepaid=0;
        }
        return $interestRepaid;
    }

    public static function getProcessingFee($branch,$staff,$start_date,$end_date){
//        $interestQuery="SELECT SUM(loanaccounts.processing_fee) as processing_fee FROM loanaccounts,loanrepayments,loantransactions WHERE
//		loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id
//		AND (DATE(loantransactions.transacted_at) BETWEEN '$start_date' AND '$end_date') AND loantransactions.is_void IN('0','3','4')";
//
        $interestQuery="SELECT SUM(loanaccounts.processing_fee) as processing_fee FROM loanaccounts";
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        switch(Yii::app()->user->user_level){
            case '0':
                $interestQuery.="";
                break;

            case '1':
                $interestQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $interestQuery.=" AND loanaccounts.rm=$userID";
                break;

            case '3':
                $interestQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }

        if($branch !=0){
            $interestQuery.=" AND loanaccounts.branch_id=$branch";
        }

        if($staff !=0){
            $interestQuery.=" AND loanaccounts.rm=$staff";
        }
        $interest=Loanaccounts::model()->findBySql($interestQuery);
        if(!empty($interest)){
            $interestRepaid=$interest->processing_fee;
        }else{
            $interestRepaid=0;
        }
        return $interestRepaid;
    }

	public static function getLoanPenaltyRepayments($branch,$staff,$start_date,$end_date){
		$penaltyQuery="SELECT SUM(loanrepayments.penalty_paid) as penalty_paid FROM loanrepayments,loantransactions,loanaccounts
		WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id
		AND (DATE(loantransactions.transacted_at) BETWEEN '$start_date' AND '$end_date') AND loantransactions.is_void IN('0','3','4')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$penaltyQuery.="";
			break;

			case '1':
			$penaltyQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$penaltyQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$penaltyQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}

		if($branch !=0){
			$penaltyQuery.=" AND loanaccounts.branch_id=$branch";
		}

		if($staff !=0){
			$penaltyQuery.=" AND loanaccounts.rm=$staff";
		}
		$penalty=Loanrepayments::model()->findBySql($penaltyQuery);
		if(!empty($penalty)){
			$penaltypaid=$penalty->penalty_paid;
		}else{
			$penaltypaid=0;	
		}
		return $penaltypaid;
	}

	public static function getLoanFeesRepayments($branch,$staff,$start_date,$end_date){
		$arrearsQuery="SELECT SUM(loanrepayments.fee_paid) as fee_paid FROM loanrepayments,loantransactions,loanaccounts
		WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id
		AND (DATE(loantransactions.transacted_at) BETWEEN '$start_date' AND '$end_date') AND loantransactions.is_void IN('0','3','4')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$arrearsQuery.="";
			break;

			case '1':
			$arrearsQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$arrearsQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$arrearsQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}

		if($branch !=0){
			$arrearsQuery.=" AND loanaccounts.branch_id=$branch";
		}

		if($staff !=0){
			$arrearsQuery.=" AND loanaccounts.rm=$staff";
		}
		$fee=Loanrepayments::model()->findBySql($arrearsQuery);
		if(!empty($fee)){
			$feeRepaid=$fee->fee_paid;
		}else{
			$feeRepaid=0;		
		}
		return $feeRepaid;
	}

	public static function getOtherIncome($branch,$staff,$start_date,$end_date){
		$incomeQuery="SELECT SUM(incomes.amount) as amount FROM incomes,profiles WHERE profiles.id=incomes.created_by AND
		 incomes.transaction_date BETWEEN '$start_date' AND '$end_date'";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$incomeQuery.="";
			break;

			case '1':
			$incomeQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$incomeQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$incomeQuery.=" AND profiles.id=$userID";
			break;
		}

		if($branch !=0){
			$incomeQuery.=" AND profiles.branchId=$branch";
		}

		if($staff !=0){
			$incomeQuery.=" AND profiles.managerId=$staff";
		}
		$income=Incomes::model()->findBySql($incomeQuery);
		if(!empty($income)){
			$incomeRepaid=$income->amount;
		}else{
			$incomeRepaid=0;		
		}
		return $incomeRepaid;
	}

	public static function getTotalReceipts($branch,$staff,$start_date,$end_date){
		$totalPrincipalPaid=Accounting::getLoanPrincipalRepayments($branch,$staff,$start_date,$end_date);
		$totalInterestPaid=Accounting::getLoanInterestRepayments($branch,$staff,$start_date,$end_date);
		$totalPenaltyPaid=Accounting::getLoanPenaltyRepayments($branch,$staff,$start_date,$end_date);
		$totalIncome=Accounting::getOtherIncome($branch,$staff,$start_date,$end_date);
		
		$insuranceFee=Accounting::getInsuranceFee($branch,$staff,$start_date,$end_date);
		$processingFee=Accounting::getProcessingFee($branch,$staff,$start_date,$end_date);
        $totalProcessingInsuranceFees=$insuranceFee+$processingFee;

                 //$totalProcessingInsuranceFees=Accounting::getProcessingInsuranceFees($branch,$staff,$start_date,$end_date);
                 //var_dump($totalProcessingInsuranceFees);exit;
		//$totalReceipts=$totalPrincipalPaid+$totalInterestPaid+$totalPenaltyPaid+$totalIncome;
		$totalReceipts=$totalPrincipalPaid+$totalInterestPaid+$totalPenaltyPaid+$totalIncome+$totalProcessingInsuranceFees;
		//$totalReceipts=$totalPrincipalPaid+$totalInterestPaid+$totalPenaltyPaid+$totalIncome;
		return $totalReceipts;
	}

	public static function getTotalMonthlyReceipts($branch,$staff,$start_date,$end_date){
		$totalInterestPaid=Accounting::getLoanInterestRepayments($branch,$staff,$start_date,$end_date);
		$totalPenaltyPaid=Accounting::getLoanPenaltyRepayments($branch,$staff,$start_date,$end_date);
		$totalIncome=Accounting::getOtherIncome($branch,$staff,$start_date,$end_date);
		//$totalReceipts=$totalInterestPaid+$totalPenaltyPaid+$totalIncome;
		$insuranceFee=Accounting::getInsuranceFee($branch,$staff,$start_date,$end_date);
                $processingFee=Accounting::getProcessingFee($branch,$staff,$start_date,$end_date);
                $totalProcessingInsuranceFees=$insuranceFee+$processingFee;

                $totalReceipts=$totalInterestPaid+$totalPenaltyPaid+$totalIncome+$totalProcessingInsuranceFees;
		return $totalReceipts;
	}

	public static function getTotalExpenses($branch,$staff,$start_date,$end_date){
		$expenseQuery="SELECT SUM(expenses.amount) as amount FROM expenses,profiles
		WHERE profiles.id=expenses.created_by AND expenses.expense_date BETWEEN '$start_date' AND '$end_date'";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$expenseQuery.="";
			break;

			case '1':
			$expenseQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$expenseQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$expenseQuery.=" AND profiles.id=$userID";
			break;
		}

		if($branch !=0){
			$expenseQuery.=" AND profiles.branchId=$branch";
		}

		if($staff !=0){
			$expenseQuery.=" AND profiles.managerId=$staff";
		}
		$expense=Expenses::model()->findBySql($expenseQuery);
		if(!empty($expense)){
			$expenseRepaid=$expense->amount;
		}else{
			$expenseRepaid=0;		
		}
		return $expenseRepaid;
	}

	public static function getTotalPayrollReleased($branch,$staff,$start_date,$end_date){
		$payrollStartMonth=date('m',strtotime($start_date));
		$payrollStartYear=date('Y',strtotime($start_date));
		$payrollEndMonth=date('m',strtotime($end_date));
		$payrollEndYear=date('Y',strtotime($end_date));
		$startAmount=Accounting::getTotalPayrollReleasedStartDate($branch,$staff,$start_date);
		if(($payrollStartMonth == $payrollEndMonth) && ($payrollStartYear == $payrollEndYear)){
			$endAmount=0;
		}else{
			$endAmount=Accounting::getTotalPayrollReleasedEndDate($branch,$staff,$end_date);
		}
		$payrollamount=$startAmount + $endAmount;
		return $payrollamount;
	}

	public static function getTotalPayrollReleasedStartDate($branch,$staff,$start_date){
		$payrollMonth=date('m',strtotime($start_date));
		$payrollYear=date('Y',strtotime($start_date));
		$startPayrollQuery="SELECT SUM(payroll.net_salary) AS net_salary FROM payroll,profiles
		 WHERE payroll.payroll_month=$payrollMonth AND payroll.payroll_year=$payrollYear AND payroll.user_id=profiles.id AND profiles.profileType IN('STAFF')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$startPayrollQuery.="";
			break;

			case '1':
			$startPayrollQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$startPayrollQuery.=" AND profiles.id=$userID";
			break;
		}

		if($branch !=0){
			$startPayrollQuery.=" AND profiles.branchId=$branch";
		}

		if($staff !=0){
			$startPayrollQuery.=" AND profiles.id=$staff";
		}
		$transaction=Payroll::model()->findBySql($startPayrollQuery);
		if(!empty($transaction)){
			$payrollamount=$transaction->net_salary;
		}else{
			$payrollamount=0;
		}
		return $payrollamount;
	}

	public static function getTotalPayrollReleasedEndDate($branch,$staff,$end_date){
		$payrollMonth=date('m',strtotime($end_date));
		$payrollYear=date('Y',strtotime($end_date));
		$startPayrollQuery="SELECT SUM(payroll.net_salary) AS net_salary FROM payroll,profiles 
		WHERE payroll.payroll_month=$payrollMonth AND payroll.payroll_year=$payrollYear AND payroll.user_id=profile.id AND profiles.profileType IN('STAFF')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$startPayrollQuery.="";
			break;

			case '1':
			$startPayrollQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$startPayrollQuery.=" AND profiles.id=$userID";
			break;
		}

		if($branch !=0){
			$startPayrollQuery.=" AND profiles.branchId=$branch";
		}

		if($staff !=0){
			$startPayrollQuery.=" AND profiles.id=$staff";
		}
		$transaction=Payroll::model()->findBySql($startPayrollQuery);
		if(!empty($transaction)){
			$payrollamount=$transaction->net_salary;
		}else{
			$payrollamount=0;
		}
		return $payrollamount;
	}

	public static function getTotalLoanAmountReleased($branch,$staff,$start_date,$end_date){
		$accountsQuery="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
		WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND DATE(disbursed_loans.disbursed_at) BETWEEN '$start_date' AND '$end_date'";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$accountsQuery.="";
			break;

			case '1':
			$accountsQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$accountsQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$accountsQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}

		if($branch !=0){
			$accountsQuery.=" AND loanaccounts.branch_id=$branch";
		}

		if($staff !=0){
			$accountsQuery.=" AND loanaccounts.rm=$staff";
		}
		$loan=DisbursedLoans::model()->findBySql($accountsQuery);
		if(!empty($loan)){
			$loanAmountReleased=$loan->amount_disbursed;
		}else{
			$loanAmountReleased=0;
		}
		return $loanAmountReleased;
	}

	public static function getTotalLoanAmountWrittenOff($branch,$staff,$start_date,$end_date){
		$accountsQuery="SELECT *  FROM loanaccounts WHERE loanaccounts.loan_status NOT IN('0','1','3')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$accountsQuery.="";
			break;

			case '1':
			$accountsQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$accountsQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$accountsQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}

		if($branch !=0){
			$accountsQuery.=" AND loanaccounts.branch_id=$branch";
		}

		if($staff !=0){
			$accountsQuery.=" AND loanaccounts.rm=$staff";
		}

		$loanaccounts=Loanaccounts::model()->findAllBySql($accountsQuery);
		$totalAmountWrittenOff=0;
		if(!empty($loanaccounts)){
			foreach($loanaccounts AS $loan){
				$totalAmountWrittenOff+=LoanApplication::getAccountTotalWrittenOff($loan->loanaccount_id,$start_date,$end_date);
			}
			$writtenOffAmount=$totalAmountWrittenOff;
		}else{
			$writtenOffAmount=0;		
		}
		return $writtenOffAmount;
	}

	public static function getTotalPayments($branch,$staff,$start_date,$end_date){
		$totalExpenses=Accounting::getTotalExpenses($branch,$staff,$start_date,$end_date);
		$totalPayroll=Accounting::getTotalPayrollReleased($branch,$staff,$start_date,$end_date);
		$totalWrittenOff=Accounting::getTotalLoanAmountWrittenOff($branch,$staff,$start_date,$end_date);
		$totalLoanReleased=Accounting::getTotalLoanAmountReleased($branch,$staff,$start_date,$end_date);
		$totalPayments=$totalExpenses + $totalPayroll + $totalWrittenOff + $totalLoanReleased;
		return $totalPayments;
	}

	public static function getTotalMonthlyPayments($branch,$staff,$start_date,$end_date){
		$totalExpenses=Accounting::getTotalExpenses($branch,$staff,$start_date,$end_date);
		$totalPayroll=Accounting::getTotalPayrollReleased($branch,$staff,$start_date,$end_date);
		$totalPayments=$totalExpenses + $totalPayroll;
		return $totalPayments;
	}

	public static function getTotalCashBalance($branch,$staff,$start_date,$end_date){
		$totalReceipts=Accounting::getTotalReceipts($branch,$staff,$start_date,$end_date);
		$totalPayments=Accounting::getTotalPayments($branch,$staff,$start_date,$end_date);
		$balance=$totalReceipts-$totalPayments;
		return $balance;
	}

	public static function getTotalMonthlyCashBalance($branch,$staff,$start_date,$end_date){
		$totalReceipts=Accounting::getTotalMonthlyReceipts($branch,$staff,$start_date,$end_date);
		$totalPayments=Accounting::getTotalMonthlyPayments($branch,$staff,$start_date,$end_date);
		$balance=$totalReceipts-$totalPayments;
		return $balance;
	}

	public static function getCashFlowAccumulatedTable($branch,$staff,$start_date,$end_date){
    Logger::logUserActivity("Viewed Cash Flow Accumulated Report",'normal');
		echo '<table class="table table-bordered" style="font-size:1.95em;">
				<tr style="background:#ddd;font-weight:bold;"><td></td><td>BALANCE (Kshs.)</td></tr>
				<tr><td style="font-weight:bold;">RECEIPTS</td><td></td></tr>
				<tr><td>Loan Principal Repayments</td><td>';echo CommonFunctions::asMoney(Accounting::getLoanPrincipalRepayments($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Loan Interest Repayments</td><td>';echo CommonFunctions::asMoney(Accounting::getLoanInterestRepayments($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Loan Penalty Repayments</td><td>';echo CommonFunctions::asMoney(Accounting::getLoanPenaltyRepayments($branch,$staff,$start_date,$end_date));echo'</td></tr>
				
					<tr><td>Insurance Fee</td><td>';echo CommonFunctions::asMoney(Accounting::getInsuranceFee($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Processing Fee</td><td>';echo CommonFunctions::asMoney(Accounting::getProcessingFee($branch,$staff,$start_date,$end_date));echo'</td></tr>
				
				<tr><td>Other Income</td><td>';echo CommonFunctions::asMoney(Accounting::getOtherIncome($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr style="font-weight:bold;background-color:cyan !important;"><td>TOTAL RECEIPTS</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalReceipts($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr style="font-weight:bold;"><td>PAYMENTS</td><td></td></tr>
				<tr><td>Expenses</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalExpenses($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Payroll</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalPayrollReleased($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Loans Released</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalLoanAmountReleased($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Loans Written Off</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalLoanAmountWrittenOff($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr style="font-weight:bold;"><td>TOTAL PAYMENTS</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalPayments($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr style="font-weight:bold;background:cyan !important;"><td>TOTAL CASH BALANCE</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalCashBalance($branch,$staff,$start_date,$end_date));echo'</td></tr>
			  </table>';
	}

	public static function getMonthlyCashFlowTable($branch,$staff,$start_date,$end_date){
        Logger::logUserActivity("Viewed Cash Flow Accumulated Report",'normal');
		echo '<table class="table table-bordered" style="font-size:1.95em;">
				<tr style="background:#ddd;font-weight:bold;"><td></td><td>BALANCE (Kshs.)</td></tr>
				<tr><td style="font-weight:bold;">RECEIPTS</td><td></td></tr>
				<tr><td>Interest Repayments</td><td>';echo CommonFunctions::asMoney(Accounting::getLoanInterestRepayments($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Penalty Repayments</td><td>';echo CommonFunctions::asMoney(Accounting::getLoanPenaltyRepayments($branch,$staff,$start_date,$end_date));echo'</td></tr>
				
				<tr><td>Insurance Fee</td><td>';echo CommonFunctions::asMoney(Accounting::getInsuranceFee($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Processing Fee</td><td>';echo CommonFunctions::asMoney(Accounting::getProcessingFee($branch,$staff,$start_date,$end_date));echo'</td></tr>
				
				
				<tr><td>Other Income</td><td>';echo CommonFunctions::asMoney(Accounting::getOtherIncome($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr style="font-weight:bold;background:cyan;"><td>TOTAL RECEIPTS</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalMonthlyReceipts($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr style="font-weight:bold;"><td>PAYMENTS</td><td></td></tr>
				<tr><td>Expenses</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalExpenses($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr><td>Payroll</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalPayrollReleased($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr style="font-weight:bold;"><td>TOTAL PAYMENTS</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalMonthlyPayments($branch,$staff,$start_date,$end_date));echo'</td></tr>
				<tr style="font-weight:bold;background:cyan !important;"><td>TOTAL CASH BALANCE</td><td>';echo CommonFunctions::asMoney(Accounting::getTotalMonthlyCashBalance($branch,$staff,$start_date,$end_date));echo'</td></tr>
			  </table>';
	}

	public static function LoadFilteredProfitAndLossReport($start_date,$end_date,$branch,$staff,$borrower){
		$profitQuery="SELECT * FROM loanaccounts,loantransactions,loanrepayments,profiles WHERE loanaccounts.loanaccount_id=loantransactions.loanaccount_id
		AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id AND loanaccounts.loan_status NOT IN('0','1','3')
		AND (DATE(loantransactions.transacted_at) BETWEEN '$start_date' AND '$end_date') AND profiles.id=loanaccounts.user_id";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$profitQuery.="";
			break;

			case '1':
			$profitQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$profitQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$profitQuery.=" AND profiles.managerId=$userID";
			break;
		}
		echo Accounting::getProfitReport($branch,$staff,$borrower,$profitQuery,$start_date,$end_date);
	}

	public static function getProfitReport($branch,$staff,$borrower,$profitQuery,$start_date,$end_date){
		if($branch !=0){
			$profitQuery.=" AND profiles.branchId=$branch";
		}
		if($staff !=0){
			$profitQuery.=" AND profiles.managerId=$staff";
		}
		if($borrower !=0){
			$profitQuery.=" AND profiles.id=$borrower";
		}
		$profitQuery.=" GROUP BY loanaccounts.loanaccount_id ORDER BY profiles.firstName ASC";
		$loanaccounts=Loanaccounts::model()->findAllBySql($profitQuery);
		$htmlTable=Tabulate::createMemberProfitandLossTable($loanaccounts,$start_date,$end_date);
		echo $htmlTable;
	}

}
