<?php

class User{

	public static function createUserRecord($first_name,$last_name,$username,$email,$password,$branch_id){
		$duplicate_status=User::checkDuplicateUsers($email,$username);
		if($duplicate_status === 0){
				$user=new Users;
				$user->first_name=$first_name;
				$user->last_name=$last_name;
				$user->username=$username;
				$user->email=$email;
				$user->branch_id=$branch_id;
				$user->password=password_hash($password,PASSWORD_DEFAULT);
				if($user->save()){
					$branch=Branch::model()->findByPk($branch_id);
					$branchName=$branch->name;
					$borrower=new Borrower;
					$borrower->user_id=$user->user_id;
					$borrower->first_name=$first_name;
					$borrower->last_name=$last_name;
					$borrower->email=$email;
					$borrower->birth_date='1989-01-01';
					$borrower->branch_id=$branch_id;
					$borrower->employer='SELF EMPLOYED';
					$borrower->date_employed='2003-01-01';
					$borrower->city=$branchName;
					$borrower->created_by=0;
					$borrower->save();
					$status=1;
				}else{
					$status=0;
				}
		}else{
			$status=2;
		}
		return $status;
	}

	public static function checkDuplicateUsers($email,$username){
		$users_sql="SELECT * FROM users WHERE email='$email' OR username='$username' LIMIT 5";
		$users=Users::model()->findAllBySql($users_sql);
		$users_count=count($users);
		if($users_count > 0){
			$status = 1;
		}else{
			$status = 0;
		}
		return $status;
	}

	public static function updateUserLastLogin($user_id){
		$user=Users::model()->findByPk($user_id);
		$user->last_login=date('Y-m-d H:i:s');
		$user->save();
	}
	/*INVOKE ONLY WHEN: PASSWORD IS CHANGED || PASSWORD HAS BEEN RESET*/
	public static function updateUserDateUpdated($user_id){
		$user=Users::model()->findByPk($user_id);
		$user->updated_at=date('Y-m-d H:i:s');
		$user->save();
	}
	/*ENFORCE PASSWORD CHANGE POLICY: AFTER THREE MONTHS*/
	public static function checkIfPasswordHasExpired($user_id){
		$difference= User::calculateDaysToPasswordExpiry($user_id);
		return $difference < 3 ? 1 : 0;
	}

	public static function calculateDaysToPasswordExpiry($userId){
		$auth            =  Auths::model()->find('profileId=:a',array(':a'=>$userId));
		$today           =  date('Y-m-d');
		$fromDate        =  $auth->updatedAt;
		$days_difference =  abs(strtotime($today) - strtotime($fromDate));
		$days            =  $days_difference/(60 * 60 * 24);
		$difference      =  round(45 - $days);
		return $difference < 0 ? 0: $difference;
	}

	public static function activateUserAccount($userID){
		$user=Users::model()->findByPk($userID);
		$user->is_active='1';
		if($user->save()){
			$status=1;
		}else{
			$status=0;
		}
		return $status;
	}

	public static function deactivateUserAccount($userID){
		$user=Users::model()->findByPk($userID);
		$user->is_active='0';
		if($user->save()){
			$status=1;
		}else{
			$status=0;
		}
		return $status;
	}

	public static function commitDrillDownUpdate($model){
		User::updateLoanAccountFromUser($model,$model->user_id);
		User::updateLoanRepaymentsFromUser($model,$model->user_id);
		User::updateSavingAccountsFromUser($model,$model->user_id);
		switch($model->level){
			case '3':
			User::updateMemberFromUser($model,$model->user_id);
			break;

			default:
			User::updateStaffFromUser($model,$model->user_id);
			break;
		}
	}

	public static function updateLoanAccountFromUser($model,$userID){
		$loanQuery="SELECT * FROM loanaccounts WHERE user_id=$userID";
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanQuery);
		if(!empty($loanaccounts)){
			foreach($loanaccounts AS $loanaccount){
				$loan=Loanaccounts::model()->findByPk($loanaccount->loanaccount_id);
				$loan->account_number=$model->id_number;
				$loan->rm=$model->rm;
				$loan->save();
			}
		}
	}

	public static function updateLoanRepaymentsFromUser($model,$userID){
		$repaymentsQuery="SELECT * FROM loanrepayments,loanaccounts WHERE
		 loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.user_id=$userID";
		$payments=Loanrepayments::model()->findAllBySql($repaymentsQuery);
		if(!empty($payments)){
			foreach($payments AS $payment){
				$repayment=Loanrepayments::model()->findByPk($payment->loanrepayment_id);
				$repayment->branch_id=$model->branch_id;
				$repayment->rm=$model->rm;
				$repayment->save();
			}
		}
	}

	public static function updateSavingAccountsFromUser($model,$userID){
		$savingAccountsQuery="SELECT * FROM savingaccounts WHERE user_id=$userID LIMIT 1";
		$savingaccount=Savingaccounts::model()->findBySql($savingAccountsQuery);
		if(!empty($savingaccount)){
			$savingaccount->branch_id=$model->branch_id;
			$savingaccount->rm=$model->rm;
			$savingaccount->save();
		}
	}

	public static function updateStaffFromUser($model,$userID){
		$staffQuery="SELECT * FROM staff WHERE user_id=$userID LIMIT 1";
		$staff=Staff::model()->findBySql($staffQuery);
		if(!empty($staff)){
			$staff->id_number=$model->id_number;
			$staff->branch_id=$model->branch_id;
			$staff->first_name=$model->first_name;
			$staff->last_name=$model->last_name;
			$staff->phone=$model->phone;
			$staff->email=$model->email;
			$staff->save();
		}
	}

	public static function updateMemberFromUser($model,$userID){
		$memberQuery="SELECT * FROM borrower WHERE user_id=$userID LIMIT 1";
		$member=Borrower::model()->findBySql($memberQuery);
		if(!empty($member)){
			$member->id_number=$model->id_number;
			$member->branch_id=$model->branch_id;
			$member->first_name=$model->first_name;
			$member->last_name=$model->last_name;
			$member->phone=$model->phone;
			$member->email=$model->email;
			$member->rm=$model->rm;
			$member->save();
		}
	}
	/*************
	OTPs
	********************/
	public static function insertOTP($hashedOtp){
		$otp = new Otps;
		$otp->user_id = Yii::app()->user->user_id;
		$otp->otp     = md5($hashedOtp);
		$otp->save();
	}
	public static function checkIfOTPExpired($otp){
		$userID        = Yii::app()->user->user_id;
		$obfuscatedOTP = md5($otp);
		$otpQuery      = "SELECT * FROM otps WHERE user_id=$userID AND otp='$obfuscatedOTP' ORDER BY id DESC LIMIT 1";
		$otp           = Otps::model()->findBySql($otpQuery);
		if(count($otp) > 0){
			$createdAt=$otp->created_at;
			$expiringAt = strtotime("+10 minutes", strtotime($createdAt));
			$cenvertedTime = date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime($createdAt)));
			$currentTime=date('Y-m-d H:i:s');
			if($currentTime > $cenvertedTime){
				$otpExpired=1;
			}else{
				$otpExpired=0;
			}
		}else{
			$otpExpired=1;
		}
		return $otpExpired;
	}

	public static function verifyAccount($otp){
		
		switch(User::checkIfOTPExpired($otp)){
			case 0:
			ProfileEngine::persistLastLogInDate(Yii::app()->user->user_id);
			$verificationStatus=1;
			break;

			case 1:
			$verificationStatus=0;
			break;
		}
		return $verificationStatus;
	}

}