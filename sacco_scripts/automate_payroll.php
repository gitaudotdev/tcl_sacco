<?php

include_once('config.php');
include_once('Utilities.php');

function initiatePayments(){
	$conn         = SaccoDB();
	$organization = getOrganizationDetails($conn);
	if($organization !=0 ){
		foreach($organization AS $org){
			$automatedPayroll = $org['automated_payroll'];
		}
		switch($automatedPayroll){
			case 'enabled':
			$month_date  = date('m-Y');
			$payMonth    = (int)date('m');
			$payYear     = (int)date('Y');
			$boundStatus = checkBoundedPayrollPeriod($payMonth,$payYear);
			if($boundStatus === 1){
				$members = getProfilePayrollStaff($conn);
				if($members != 0){
					foreach($members AS $member){
						$userID              = $member['id'];
						$branchID            = $member['branchId'];
						$salesCommision      = getMemberBonus($userID,$month_date,$conn);
						$collectionsCommision= getMemberCommission($userID,$month_date,$conn);
						$totalLoan           = ceil(getCurrentLoanRepayment($userID,$conn));
						$defaults            = getProfileRecentSettingByType($conn,$userID,'SALARY');
						foreach($defaults AS $default){
							$configValue     = $default['configValue'];
						}
						$grossSalary         = $configValue ? floatval($configValue) : 0;
						$netSalary           = getMemberNetSalaryPay($userID,$month_date,$conn);
						if($netSalary > 0){
							//echo "UserID: ".$userID."\t Gross Salary: ".$grossSalary."\t Bonus: ".$salesCommision."\t Commission: ".$collectionsCommision."\t Loan Amount: ".$totalLoan."\t Net Salary: ".$netSalary."<br>";
							processPayroll($userID,$branchID,$salesCommision,$collectionsCommision,$totalLoan,$grossSalary,$netSalary,$payMonth,$payYear,$conn);
						}
					}
				}else{
					echo "NO RECORDS <br>";
				}
			}else{
				echo "ALREADY PAID <br>";
			}
			break;

			default:
			echo "AUTOMATED PAYROLL DEACTIVATED<br/>";
			break;
		}
	}
}
/***************
	
	INVOKE

*******************/
initiatePayments();