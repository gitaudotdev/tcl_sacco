<?php

class Logger{

	public static function logUserActivity($activity,$severity){
		$profileId = Yii::app()->user->user_id;
		$profile           = Profiles::model()->findByPk($profileId);
		$log               = new Logs;
		$log->user_id      = Yii::app()->user->user_id;
		$log->branch_id    = $profile->branchId;
		$log->activity     = $activity;
		$log->severity     = $severity;
		$log->logged_at    = date('Y-m-d H:i:s');
		$log->save();
		/**
		 * Uncomment to send email alert
		 * 
		 * $adminEmailId = '<ADMIN_EMAIL_ADDRESS>'
		 * 
		 */
		// sendAdminEmailAlert($profileId,$activity,$severity,$adminEmailId);
	}
	
	public static function getUserLogs($userID){
		$logQuery = "SELECT * FROM logs WHERE user_id=$userID ORDER BY log_id DESC";
		return Logs::model()->findAllBySql($logQuery);
	}

	public static function sendAdminEmailAlert($profileId,$activity,$severity,$adminEmailId){
		$profile           = Profiles::model()->findByPk($profileId);
		$profileFullName   = $profile->ProfileFullName;
		$profileBranchName = $profile->ProfileBranch;
		$userManager       = $profile->ProfileManager;
		$authLevel         = $profile->ProfileAuthStatus;
		$logTimestamp      = date('dmYHis');
		$uniqueID       = strtoupper(CommonFunctions::generateToken(8)).'_'.$logTimestamp;
		$level          = ucfirst($severity);
		$subject        = $uniqueID.'_'.$level;
		$name           = 'Audit Trail Notifications';
		$body           = "<p>Profile Name: $profileFullName | Profile Branch: $profileBranchName | Profile Manager: $userManager | Profile Authorization: $authLevel</p>
		                  <p>Severity: $level | Activity: $activity | Date Logged: $logTimestamp </p>";
		$message        = Mailer::Build($name,$subject,$body,'Admin');
		$emailed        = CommonFunctions::broadcastEmailNotification($adminEmailId,$subject,$message);
	}


}