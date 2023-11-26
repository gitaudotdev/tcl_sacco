<?php

class StaffFunctions{

	public static function checkIfStaffCanBeDeleted($user_id){
		$checkQuery = "SELECT * FROM profiles,user_role WHERE profiles.id=$user_id AND profiles.id=user_role.user_id LIMIT 1";
		$check      = Profiles::model()->findAllBySql($checkQuery);
		return count($check) > 0 ? 0 : 1;
	}

	public static function displayStaffHTMLContent(){
		$profiles = ProfileEngine::getProfilesByType('STAFF');
		foreach($profiles as $profile){
			echo '<div class="col-md-3 col-lg-3 col-sm-12">
					<div class="form-check">
						<label class="form-check-label">
					<input class="form-check-input" type="checkbox" name="staff[]" value="';echo $profile->id;
						echo'">
						<span class="form-check-sign"></span>';
						echo $profile->ProfileFullName; echo '
						</label>
					</div>
				</div>';
		}
	}

	public static function getSaccoStaff(){
		return ProfileEngine::getProfilesByType('STAFF');
	}

	public static function getCommentDashboardStaffMembers(){
		$staffQuery  = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND
		id IN(SELECT profileId FROM account_settings WHERE configType='COMMENTS_DASHBOARD_LISTED' AND configValue='ACTIVE') ORDER BY firstName,lastName";
		return Profiles::model()->findAllBySql($staffQuery);
	}

	public static function getBranchCommentDashboardStaffMembers($branch){
		$staffQuery  = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND branchId=$branch AND
		id IN(SELECT profileId FROM account_settings WHERE configType='COMMENTS_DASHBOARD_LISTED' AND configValue='ACTIVE') ORDER BY firstName,lastName";
		return Profiles::model()->findAllBySql($staffQuery);
	}

	public static function processStaffPayroll($userID,$amount){
		switch(StaffFunctions::checkIfStaffHasBeenPaid($userID)){
			case 0:
			$profile = Profiles::model()->findByPk($userID);
			$payroll = new PayrollTransactions();
			$payroll->staff_id = $userID;
			$payroll->amount   = $amount;
			$payroll->processed_by = Yii::app()->user->user_id;
			$payroll->processed_at = date('Y-m-d H:i:s');
			if($payroll->save()){
				$loanQuery = "SELECT loanaccount_id FROM loanaccounts WHERE user_id=$userID AND loan_status NOT IN('0','1','3','4','8','9','10') 
				ORDER BY loanaccount_id DESC LIMIT 1";
				$loan      = Loanaccounts::model()->findBySql($loanQuery);
				if(!empty($loan)){
					LoanManager::repayLoanAccount($loan->loanaccount_id,$profile->getTotalRepayment(),'0',$profile->ProfilePhoneNumber);
				}
				$status=1;
			}else{
				$status=0;
			}
			break;

			case 1:
			$status=2;
			break;
		}
		return $status;
	}

	public static function checkIfStaffHasBeenPaid($staff_id){
		$currentMonth=date('m');
		$currentYear=date('Y');
		$checkSQL   = "SELECT * FROM payroll_transactions WHERE staff_id=$staff_id AND MONTH(processed_at)='$currentMonth' AND YEAR(processed_at)='$currentYear'";
		$checked    = PayrollTransactions::model()->findAllBySql($checkSQL);
		return !empty($checked) ? 1 : 0;
	}

	public static function LoadFilteredStaffPayroll($branch,$staff,$month_date){
		$userBranch = Yii::app()->user->user_branch;
		$userID     = Yii::app()->user->user_id;
		if(Navigation::checkIfAuthorized(160) === 1){
			$staffQuery  = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND id=$userID AND
			id IN(SELECT profileId FROM account_settings WHERE configType='PAYROLL_LISTED' AND configValue='ACTIVE')";
		}else{
			$staffQuery  = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND
			id IN(SELECT profileId FROM account_settings WHERE configType='PAYROLL_LISTED' AND configValue='ACTIVE')";
			switch($branch){
				case 0:
				if($staff === 0){
					switch(Yii::app()->user->user_level){
						case '0':
						$staffQuery.= "";
						break;

						case '1':
						$staffQuery.= " AND branchId=$userBranch";
						break;

						default:
						$staffQuery.="  AND id=$userID";
						break;
					}
				}else{
					$staffQuery.=" AND id=$staff";
				}
				break;

				default:
				if($staff === 0){
					switch(Yii::app()->user->user_level){
						case '0':
						$staffQuery.= " AND branchId=$branch";
						break;

						case '1':
						$staffQuery.= " AND branchId=$branch";
						break;

						default:
						$staffQuery.="  AND id=$userID";
						break;
					}
				}else{
					$staffQuery.=" AND id=$staff";
				}
				break;
			}
		}
		$staffQuery.= " ORDER BY firstName,lastName ASC";
		$staffs     = Profiles::model()->findAllBySql($staffQuery);
		echo Tabulate::createStaffMembersPayrollTabulation($staffs,$month_date);
	}

	public static function getTotalLoanAmountSold($user_id,$month_date){
		$profile      = Profiles::model()->findByPk($user_id);
		$branchID     = $profile->branchId;
		$monthDate    = explode('-', $month_date);
		$payrollMonth = $monthDate[0];
		$payrollYear  = $monthDate[1];
		$supervisor   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'SUPERVISORIAL_ROLE');
		$supervisory  = $supervisor === 'NOT SET' ? 'DISABLED' : $supervisor;
		$principalQuery = $supervisory === 'DISABLED' ? "SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND MONTH(disbursed_at)='$payrollMonth' AND YEAR(disbursed_at)='$payrollYear' AND loanaccounts.rm=$user_id"
													  : "SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND MONTH(disbursed_at)='$payrollMonth' AND YEAR(disbursed_at)='$payrollYear' AND loanaccounts.branch_id=$branchID";
		$totalPrincipal = DisbursedLoans::model()->findBySql($principalQuery);
		return !empty($totalPrincipal) ? $totalPrincipal->amount_disbursed : 0;
	}


	public static function getTotalLoanCollections($user_id,$month_date){
		$profile      = Profiles::model()->findByPk($user_id);
		$branchID     = $profile->branchId;
		$monthDate    = explode('-', $month_date);
		$payrollMonth = $monthDate[0];
		$payrollYear  = $monthDate[1];
		$supervisor   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'SUPERVISORIAL_ROLE');
		$supervisory  = $supervisor === 'NOT SET' ? 'DISABLED' : $supervisor;
		$repaymentQuery = $supervisory === 'DISABLED' ? "SELECT SUM(amount) AS amount FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.rm=$user_id AND MONTH(loantransactions.transacted_at)='$payrollMonth' AND YEAR(loantransactions.transacted_at)='$payrollYear' AND loantransactions.is_void IN('0','3','4')"
													  : "SELECT SUM(amount) AS amount FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.branch_id=$branchID AND MONTH(loantransactions.transacted_at)='$payrollMonth' AND YEAR(loantransactions.transacted_at)='$payrollYear' AND loantransactions.is_void IN('0','3','4')";

		$repayment    = Loantransactions::model()->findBySql($repaymentQuery);
		return !empty($repayment) ? $repayment->amount : 0;
	}

	public static function getTotalLoanAccountsProfits($user_id,$month_date){
		$monthDate    = explode('-', $month_date);
		$payrollMonth = $monthDate[0];
		$payrollYear  = $monthDate[1];
		$repaymentSQL = "SELECT SUM(loanrepayments.interest_paid) AS interest_paid, SUM(loanrepayments.fee_paid) AS fee_paid,
		SUM(loanrepayments.penalty_paid) AS penalty_paid FROM loanrepayments,loanaccounts WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id
		AND loanaccounts.rm=$user_id AND MONTH(loanrepayments.repaid_at)='$payrollMonth' AND YEAR(loanrepayments.repaid_at)='$payrollYear'
		AND loanrepayments.is_void IN('0','3','4')";
		$repayments   = Loanrepayments::model()->findBySql($repaymentSQL);
		return !empty($repayments) ? $repayments->interest_paid + $repayments->fee_paid + $repayments->penalty_paid : 0;
	}

	public static function getMultiplierFactor($derivedPercent){
		$formatPercent = floor($derivedPercent);
		if($formatPercent > 0){
			$multiplierQuery = "SELECT * FROM performance_settings WHERE $formatPercent BETWEEN minimum AND maximum";
			$multiplier      = PerformanceSettings::model()->findBySql($multiplierQuery);
			$multipleFactor  = !empty($multiplier) ? $multiplier->percent_multiplier : 0;
		}else{
			$multipleFactor=0;
		}
		return $multipleFactor;
	}


	public static function getDeterminedPerformanceColor($derivedPercent){
		$formatPercent = floor($derivedPercent);
		if($formatPercent > 0){
			$colorQuery = "SELECT * FROM performance_settings WHERE $formatPercent BETWEEN minimum AND maximum";
			$color      = PerformanceSettings::model()->findBySql($colorQuery);
			$performanceColor = !empty($color) ? $color->colour : 0;
		}else{
			$performanceColor = 0;
		}
		return $performanceColor;
	}


	public static function getMemberBonus($user_id,$month_date){
		$profile      = Profiles::model()->findByPk($user_id);
		$monthDate    = explode('-', $month_date);
		$payrollMonth = $monthDate[0];
		$payrollYear  = $monthDate[1];
		$supervisor   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'SUPERVISORIAL_ROLE');
		$supervisory  = $supervisor === 'NOT SET' ? 'DISABLED' : $supervisor;
		switch($supervisory){
			case 'DISABLED':
			$defaultTarget = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'SALES_TARGET');
			$salesTarget   = $defaultTarget === 'NOT SET' ? 1 : floatval($defaultTarget);
			$sTarget       = $salesTarget <= 0 ? 1 : $salesTarget;
			break;

			case 'ACTIVE':
			$bTarget = Branch::model()->findByPk($profile->branchId)->sales_target;
			$sTarget = $bTarget <= 0 ? 1 : $bTarget;
			break;
		}
		$defaultBonus = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'BONUS_PERCENT');
		$bonusPercent = $defaultBonus === 'NOT SET' ? 0 : floatval($defaultBonus);
		$amountSold   = StaffFunctions::getTotalLoanAmountSold($user_id,$month_date);
		$salesPercent = ($amountSold/$sTarget) * 100;
		$multiplier   = StaffFunctions::getMultiplierFactor($salesPercent);
		return ($bonusPercent * $amountSold) * 0.01 * ($multiplier * 0.01);
	}

	public static function getMemberCommission($user_id,$month_date){
		$profile      = Profiles::model()->findByPk($user_id);
		$monthDate    = explode('-', $month_date);
		$payrollMonth = $monthDate[0];
		$payrollYear  = $monthDate[1];
		$supervisor   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'SUPERVISORIAL_ROLE');
		$supervisory  = $supervisor === 'NOT SET' ? 'DISABLED' : $supervisor;
		switch($supervisory){
			case 'DISABLED':
			$defaultTarget     = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'COLLECTIONS_TARGET');
			$collectionsTarget = $defaultTarget === 'NOT SET' ? 1 : floatval($defaultTarget);
			$cTarget = $collectionsTarget <= 0 ? 1 : $collectionsTarget;
			break;

			case 'ACTIVE':
			$collectionsTarget = Branch::model()->findByPk($profile->branchId)->collections_target;
			$cTarget = $collectionsTarget <= 0 ? 1 : $collectionsTarget;
			break;
		}
		$defaultCommission  = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'COMMISSION_PERCENT');
		$commisionPercent   = $defaultCommission === 'NOT SET' ? 0 : floatval($defaultCommission);
		$amountCollected    = StaffFunctions::getTotalLoanCollections($user_id,$month_date);
		$collectionsPercent = ($amountCollected/$cTarget) * 100;
		$multiplierFactor   = StaffFunctions::getMultiplierFactor($collectionsPercent);
		return ($commisionPercent * $amountCollected) * 0.01 * ($multiplierFactor * 0.01);
	}

	public static function getTotalProfitBonus($user_id,$month_date){
		$profile      = Profiles::model()->findByPk($user_id);
		$monthDate    = explode('-', $month_date);
		$payrollMonth = $monthDate[0];
		$payrollYear  = $monthDate[1];
		$defaultProfit   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'PROFIT_PERCENT');
		$profitPercent   = $defaultProfit === 'NOT SET' ? 0 : floatval($defaultProfit);
		$amountCollected = StaffFunctions::getTotalLoanAccountsProfits($user_id,$month_date);
		$commission      = ($profitPercent * $amountCollected) * 0.01;
		return 0;
	}


	public static function getCurrentLoanRepayment($user_id){
		$loanAccountsQuery = "SELECT * FROM loanaccounts WHERE user_id=$user_id AND loan_status IN('2','5','6','7')";
		$loanAccounts = Loanaccounts::model()->findAllBySql($loanAccountsQuery);
		if(!empty($loanAccounts)){
			$amount=0;
			foreach($loanAccounts as $loan){
				$repayPeriod      = $loan->repayment_period;
				$periodRepayment  = $repayPeriod<=0 ? 1 : $repayPeriod;
				$equalInstallment = LoanApplication::getEMIAmount($loan->loanaccount_id)/$periodRepayment;
				$amount+= $equalInstallment + $loan->arrears;
			}
		}else{
			$amount=0;
		}
		return ceil($amount);
	}

	public static function getMemberNetSalaryPay($user_id,$month_date){
		$profile = Profiles::model()->findByPk($user_id);
		$defaultSalary = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'SALARY');
		$monthlySalary = $defaultSalary === 'NOT SET' ? 0 : floatval($defaultSalary);
		$accruedBonus  = StaffFunctions::getMemberBonus($user_id,$month_date);
		$accruedCommission = StaffFunctions::getMemberCommission($user_id,$month_date);
		$accruedProfit     = StaffFunctions::getTotalProfitBonus($user_id,$month_date);
		$loanRepayment     = StaffFunctions::getCurrentLoanRepayment($user_id);
		$totalnetPay       = ($monthlySalary + $accruedBonus + $accruedCommission + $accruedProfit) - $loanRepayment;
		return $totalnetPay<= 0 ? 0: round($totalnetPay);
	}
	/*************************
		TRANSFER STAFF ACCOUNT

		1000 = Invalid Current Staff Member
		1001 = Invalid Current Staff User Account
		1002 = Could not transfer staff Account
		1003 = Could not deactivate staff user login Account
		1004 = Invalid new user account to transfer current staff member
		1111 = Successfully transferred
	*******************************************************************/
	public static function transferStaffAccount($staffID,$newUserID){
		$newUser= Profiles::model()->findByPk($newUserID);
		if(!empty($newUser)){
			$newBranchID=$newUser->branch_id;
			$userFullName=$newUser->UserFullName;
			$staff=Staff::model()->findByPk($staffID);
			$staffName=$staff->StaffFullName;
			if(!empty($staff)){
				$staff->is_active='2';
				if($staff->save()){
					$user=Users::model()->findByPk($staff->user_id);
					if(!empty($user)){
						$user->is_active='0';
						if($user->save()){
							$userID=$user->user_id;
							StaffFunctions::transferStaffUserAccounts($userID,$newUserID,$newBranchID);
							StaffFunctions::transferStaffMemberAccounts($userID,$newUserID,$newBranchID);
							StaffFunctions::transferStaffLoanAccounts($userID,$newUserID,$newBranchID);
							StaffFunctions::transferStaffLoanRepaymentsAccounts($userID,$newUserID,$newBranchID);
							StaffFunctions::transferStaffSavingAccounts($userID,$newUserID,$newBranchID);		
							Logger::logUserActivity("Transferred Staff Member Account for : $staffName to $userFullName. $staffName was deactivated. All users, clients, loan accounts, loan repayments and savings accounts under $staffName transferred under $userFullName.",'high');
							$transferStatus=1111;		
						}else{
							$transferStatus=1003;
						}
					}else{
						$transferStatus=1001;
					}
				}else{
					$transferStatus=1002;
				}
			}else{
				$transferStatus=1000;
			}
		}else{
			$transferStatus=1004;
		}	
		return $transferStatus;
	}

	public static function transferStaffUserAccounts($userID,$newUserID,$newBranchID){
		$userQuery="SELECT * FROM users WHERE rm=$userID";
		$subordinatedUsers=Users::model()->findAllBySql($userQuery);
		if(!empty($subordinatedUsers)){
			foreach($subordinatedUsers AS $user){
				$user->rm=$newUserID;
				$user->branch_id=$newBranchID;
				$user->save();
			}
		}
	}

	public static function transferStaffMemberAccounts($userID,$newUserID,$newBranchID){
		$memberQuery="SELECT * FROM borrower WHERE rm=$userID";
		$subordinatedMembers=Borrower::model()->findAllBySql($memberQuery);
		if(!empty($subordinatedMembers)){
			foreach($subordinatedMembers AS $member){
				$member->rm=$newUserID;
				$member->branch_id=$newBranchID;
				$member->save();
			}
		}
	}

	public static function transferStaffLoanAccounts($userID,$newUserID,$newBranchID){
		$loansQuery="SELECT * FROM loanaccounts WHERE rm=$userID";
		$subordinatedLoans=Loanaccounts::model()->findAllBySql($loansQuery);
		if(!empty($subordinatedLoans)){
			foreach($subordinatedLoans AS $loan){
				$loan->rm=$newUserID;
				$loan->branch_id=$newBranchID;
				$loan->save();
			}
		}
	}

	public static function transferStaffLoanRepaymentsAccounts($userID,$newUserID,$newBranchID){
		$paymentsQuery="SELECT * FROM loanrepayments WHERE rm=$userID";
		$subordinatedPayments=Loanrepayments::model()->findAllBySql($paymentsQuery);
		if(!empty($subordinatedPayments)){
			foreach($subordinatedPayments AS $payment){
				$payment->rm=$newUserID;
				$payment->branch_id=$newBranchID;
				$payment->save();
			}
		}
	}

	public static function transferStaffSavingAccounts($userID,$newUserID,$newBranchID){
		$savingsQuery="SELECT * FROM savingaccounts WHERE rm=$userID";
		$subordinatedSavingAccounts=Savingaccounts::model()->findAllBySql($savingsQuery);
		if(!empty($subordinatedSavingAccounts)){
			foreach($subordinatedSavingAccounts AS $account){
				$account->rm=$newUserID;
				$account->branch_id=$newBranchID;
				$account->save();
			}
		}
	}
	/*************************************
	
		SUSPEND / REINSTATE ACCOUNT

		1000: Staff account not found
		1001: Staff account sucessfully suspended/reinstated
		1002: Staff user account not found
		1003: Staff account could not be suspended/reinstated
		1004: Staff user account could not be deactivated/activated

	************************************************************/
	public static function suspendStaffAccount($staffID){
		$staffMember=Staff::model()->findByPk($staffID);
		if(!empty($staffMember)){
			$staffMember->is_active='0';
			if($staffMember->save()){
				$staffName=$staffMember->StaffFullName;
				$user=Users::model()->findByPk($staffMember->user_id);
				if(!empty($user)){
					$user->is_active='0';
					if($user->save()){
						$suspendStatus=1001;
						Logger::logUserActivity("Suspended Staff Member Account for : $staffName. $staffName is deactivated and cannot access the system.",'high');
					}else{
						$suspendStatus=1004;
					}
				}else{
					$staffMember->is_active='1';
					$staffMember->save();
					$suspendStatus=1002;
				}
			}else{
				$suspendStatus=1003;
			}
		}else{
			$suspendStatus=1000;
		}
		return $suspendStatus;
	}

	public static function reinstateStaffAccount($staffID){
		$staffMember=Staff::model()->findByPk($staffID);
		if(!empty($staffMember)){
			$staffMember->is_active='1';
			if($staffMember->save()){
				$staffName=$staffMember->StaffFullName;
				$user=Users::model()->findByPk($staffMember->user_id);
				if(!empty($user)){
					$user->is_active='1';
					if($user->save()){
						$reinstateStatus=1001;
						Logger::logUserActivity("Reinstated Staff Member Account for : $staffName. $staffName is activated and can access the system.",'high');
					}else{
						$reinstateStatus=1004;
					}
				}else{
					$staffMember->is_active='0';
					$staffMember->save();
					$reinstateStatus=1002;
				}
			}else{
				$reinstateStatus=1003;
			}
		}else{
			$reinstateStatus=1000;
		}
		return $reinstateStatus;
	}
	/****************************

		UPDATE STAFF LEAVE STATUS

		1000: Staff Account not found
		1001: Staff leave status successfully updated
		1003: Staff leave status not updated

	**************************************************/
	public static function updateStaffOnLeaveStatus($staffID){
		$staffMember=Staff::model()->findByPk($staffID);
		if(!empty($staffMember)){
			$staffMember->is_active='3';
			if($staffMember->save()){
				$leaveStatus=1001;
			}else{
				$leaveStatus=1003;
			}
		}else{
			$leaveStatus=1000;
		}
		return $leaveStatus;
	}

	public static function getPayrollStaff(){
		$userBranch = Yii::app()->user->user_branch;
		$userID     = Yii::app()->user->user_id;
		$staffQuery = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND
		id IN(SELECT profileId FROM account_settings WHERE configType='COMMENTS_DASHBOARD_LISTED' AND configValue='ACTIVE')";
		switch(Yii::app()->user->user_level){
			case '0':
			$staffQuery.="";
			break;

			case '1':
			$staffQuery.=" AND branchId=$userBranch";
			break;

			case '2':
			$staffQuery.=" AND id=$userID";
			break;
		}
		$staffQuery.=" ORDER BY firstName,lastName ASC";
		return Profiles::model()->findAllBySql($staffQuery);
	}

	public static function displayStaffMembers($members){
  	if(!empty($members)){
  		foreach($members as $member){
  		echo '<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-check">
					<label class="form-check-label">
			        <input class="form-check-input" type="checkbox" name="staffMembers[]" value="';echo $member->id;
			         echo'">
			        <span class="form-check-sign"></span>';
		         	 echo $member->ProfileFullName; echo '
					</label>
				</div>
			</div>';
  		}
  	}
  }
}