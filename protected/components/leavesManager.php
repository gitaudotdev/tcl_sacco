<?php
/*****************
	 Method Status
	 0= Failed
	 1= Success
	 2= Duplicate
********************/
class leavesManager{

	public static function LeaveApproversList(){
		$userID=Yii::app()->user->user_id;
		$managerQuery = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND id NOT IN($userID)
		AND id IN(SELECT profileId from auths WHERE authStatus IN('ACTIVE')) ORDER BY firstName,lastName ASC";
		return Profiles::model()->findAllBySql($managerQuery);
	}

	public static function  getProfileLeaveStaff(){
		$managerQuery = "SELECT * FROM profiles WHERE profileType IN('STAFF')
		AND id IN(SELECT profileId from auths WHERE authStatus IN('ACTIVE')) AND id IN(SELECT user_id FROM leaves)";
		switch(Yii::app()->user->user_level){
			case '0':
			$managerQuery .= "";
			break;

			case '1':
			$managerQuery .= " AND branchId=".Yii::app()->user->user_branch;
			break;

			case '2':
			$managerQuery .= " AND id=".Yii::app()->user->user_id;
			break;
		}
		$managerQuery .= " ORDER BY firstName, lastName ASC";
		return Profiles::model()->findAllBySql($managerQuery);
	}


	public static function getHandoverStaffList(){
		$userID=Yii::app()->user->user_id;
		$managerQuery = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND id NOT IN($userID)
		AND id IN(SELECT profileId from auths WHERE authStatus IN('ACTIVE')) ORDER BY firstName,lastName ASC";
		return Profiles::model()->findAllBySql($managerQuery);
	}

	public static function createStaffLeaveRecord($userID,$leaveDays,$carryOver){
		switch(leavesManager::restrictDuplicateLeaveRecords($userID)){
			case 0:
			$profile = Profiles::model()->findByPk($userID);
			if(!empty($profile)){
				$leave = new Leaves;
				$leave->user_id=$profile->id;
				$leave->branch_id=$profile->branchId;
				$leave->leave_days=$leaveDays;
				$leave->carry_over=$carryOver;
				$leave->created_by=Yii::app()->user->user_id;
				if($leave->save()){
					$today    = date('jS M Y');
					$profile  = Profiles::model()->findByPk($userID);
					$staffName=$profile->ProfileFullName;
					Logger::logUserActivity("Defined $leaveDays Leave Days for $staffName on $today","high");
					$recordedStatus=1;
				}else{
					$recordedStatus=0;
				}
			}else{
				$recordedStatus=0;
			}
			break;

			case 1:
			$recordedStatus=2;
			break;
		}
		return $recordedStatus;
	}

	public static function restrictDuplicateLeaveRecords($userID){
		$checkSql = "SELECT * FROM leaves WHERE user_id=$userID";
		$leaveRecord = Leaves::model()->findAllBySql($checkSql);
		return !empty($leaveRecord) ? 1 : 0;
	}

	public static function getprofileLeaveRecords($userID){
		$recordQuery = "SELECT * FROM leaves WHERE user_id=$userID";
		return Leaves::model()->findBySql($recordQuery);
	}

	/*3=Days Applied More than Remaining Leave Days*/
	public static function createStaffLeaveApplication($userID,$startDate,$endDate,$directTo,
		$branchID,$handoverTo,$handoverNotes){
		$checkSql = "SELECT * FROM leaves WHERE user_id=$userID";
		$leaveRecord = Leaves::model()->findBySql($checkSql);
		if(!empty($leaveRecord)){
			$leaveID = $leaveRecord->id;
			$totalDaysTaken=leavesManager::calculateTotalLeaveDaysTaken($leaveID);
			$daysApplied=leavesManager::calculateLeaveDuration($startDate,$endDate);
			$remainingLeaveDays=leavesManager::calculateRemainingLeaveDays($leaveRecord->leave_days,$totalDaysTaken);
			if($daysApplied <= $remainingLeaveDays){
				switch(leavesManager::restrictDuplicateApplication($leaveID)){
					case 0:
					$application = new LeaveApplications;
					$application->leave_id=$leaveID;
					$application->start_date=$startDate;
					$application->end_date=$endDate;
					$application->directed_to=$directTo;
					$application->branch_id=$branchID;
					$application->user_id=$userID;
					$application->handover_to=$handoverTo;
					$application->created_at=date('Y-m-d H:i:s');
					$application->authorized_at=date('Y-m-d H:i:s');
					if($application->save()){
						$handover=new Handover;
						$handover->branch_id=$branchID;
						$handover->user_id=$userID;
						$handover->leave_application_id=$application->id;
						$handover->handover_to=$handoverTo;
						$handover->notes=$handoverNotes;
						$handover->created_by=$userID;
						$handover->created_at=date('Y-m-d H:i:s');
						$handover->save();
						$leave=Leaves::model()->findByPk($application->leave_id);
						$user=Profiles::model()->findByPk($leave->user_id);
						$alertType='24';
						$firstName=$user->ProfileFullName;
						$phoneNumber=ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
						$leavePeriod=date('M jS',strtotime($startDate)).' - '.date('M jS',strtotime($endDate));
						$leaveMessage="Dear $firstName, Your leave request for $leavePeriod is received.\nThank you!";
						$numbers=array();
					  	array_push($numbers,$phoneNumber);
						SMS::broadcastSMS($numbers,$leaveMessage,$alertType,$userID);
						$adminNumbers=array();
						$admin=Profiles::model()->findByPk($application->directed_to);
						$adminPhoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($admin->id,'PHONE');
						array_push($adminNumbers,$adminPhoneNumber);
					  	$adminMessage="Leave Application for $leavePeriod by $firstName is submitted.\nThank you!";
						SMS::broadcastSMS($adminNumbers,$adminMessage,$alertType,$userID);
						$leaveDays = leavesManager::calculateLeaveDuration($startDate,$endDate);
						$today=date('jS M Y');
						$user= Profiles::model()->findByPk($userID);
						$staffName=$user->ProfileFullName;
			    		Logger::logUserActivity("Applied for $leaveDays leave days for $staffName on $today","high");
						$leaveStatus = 1;
					}else{
						$leaveStatus = 0;
					}
					break;

					case 1:
					$leaveStatus = 2;
					break;
				}
			}else{
				$leaveStatus = 3;
			}
		}else{
			$leaveStatus = 0;
		}
		return $leaveStatus;
	}

	public static function restrictDuplicateApplication($leaveID){
		$applicationSql="SELECT * FROM leave_applications WHERE leave_id=$leaveID AND status='0'";
		$applications = LeaveApplications::model()->findAllBySql($applicationSql);
		return !empty($applications) ? 1 : 0;
	}

	public static function getHandoverNotes($requestID){
		$applicationSql="SELECT * FROM handover WHERE leave_application_id=$requestID ORDER BY id DESC LIMIT 1";
		$application = Handover::model()->findBySql($applicationSql);
		return !empty($application) ? $application->notes : "No handover notes provided or the leave request has been rejected or is yet to be authorized(approved/rejected).";
	}

	public static function authorizeStaffLeaveApplication($applicationID,$authType,$authReason){
		$application = LeaveApplications::model()->findByPk($applicationID);
		if(!empty($application)){
			switch($application->status){
				case 0:
				$application->status=$authType;
				$application->auth_reason=$authReason;
				$application->authorized_by=Yii::app()->user->user_id;
				$application->authorized_at=date('Y-m-d H:i:s');
				if($application->save()){
					$branchID=$application->branch_id;
					$userID=$application->user_id;
					$message=leavesManager::getHandoverNotes($application->id);
					$sentTo=$application->handover_to;
					$createdBy=0;
					$today=date('jS M Y');
					$startDate=$application->start_date;
					$endDate=$application->end_date;
					$leavePeriod=date('M jS',strtotime($startDate)).' - '.date('M jS',strtotime($endDate));
					$leave = Leaves::model()->findByPk($application->leave_id);
					$staffName = $leave->LeaveStaffName;
					$user=Profiles::model()->findByPk($leave->user_id);
					$handoverUser=Profiles::model()->findByPk($application->handover_to);
					$firstName=$user->ProfileFullName;
					$handoverFirstName=$handoverUser->ProfileFullName;
					$phoneNumber=ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
					$handoverPhoneNumber=ProfileEngine::getProfileContactByTypeOrderDesc($handoverUser->id,'PHONE');
					$numbers=array();
					$handovernumbers=array();
					array_push($handovernumbers,$handoverPhoneNumber);
					array_push($numbers,$phoneNumber);
					switch($authType){
						case '1':
						$alertType='25';
						$leaveMessage="Dear $firstName, Your leave request for $leavePeriod has been approved.\nThank you!";
						$handoverMessage="Dear $handoverFirstName, $firstName will be starting leave for $leavePeriod.\nYou will handle all their taks.\nThank you!";
						$activityName="Approved Leave Application for $staffName on $today";
						$notification=new Notifications;
						$notification->branch_id=$branchID;
						$notification->user_id=$userID;
						$notification->message=$message;
						$notification->sent_to=$sentTo;
						$notification->created_by=$createdBy;
						if($notification->save()){
							SMS::broadcastSMS($handovernumbers,$handoverMessage,$alertType,$userID);
						}
						break;

						case '2':
						$alertType='26';
						$leaveMessage="Dear $firstName, Your leave request for $leavePeriod has been rejected.\nThank you!";
						$activityName="Rejected Leave Application for $staffName on $today";
						break;
					}
					SMS::broadcastSMS($numbers,$leaveMessage,$alertType,$userID);
					Logger::logUserActivity($activityName,"high");
					$authStatus = 1;
				}else{
					$authStatus = 0;
				}
				break;

				default:
				$authStatus = 3;
				break;
			}
		}else{
			$authStatus = 2;
		}
		return $authStatus;
	}

	public static function calculateLeaveDuration($startDate,$endDate,$workSat=FALSE){
	  if (!defined('SATURDAY')) define('SATURDAY', 6);
	  if (!defined('SUNDAY')) define('SUNDAY', 0);

	  $publicHolidays = array('01-01','12-12', '12-25', '12-26');

	  $start = strtotime($startDate);
	  $end   = strtotime($endDate);
	  $workdays = 0;
	  if($start !== $end){
		  for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
		    $day = date("w", $i); 
		    $mmgg = date('m-d', $i);
		    if($day != SUNDAY && !in_array($mmgg, $publicHolidays) && !($day == SATURDAY && $workSat == FALSE)) {
		      $workdays++;
		    }
		  }
	  }else{
	  	$startDay=date("w", $start);
		  $startMmg = date('m-d', $start);
	  	if($startDay != SUNDAY && !in_array($startMmg, $publicHolidays) && !($startDay == SATURDAY && $workSat == FALSE)){
	  		$workdays = 1;
	  	}else{
	  		$workdays = 0;
	  	}
	  }
	  return intval($workdays);
	}

	public static function calculateTotalLeaveDaysTaken($leaveID){
		$applicationSql="SELECT * FROM leave_applications WHERE leave_id=$leaveID AND status='1'";
		$applications = LeaveApplications::model()->findAllBySql($applicationSql);
		if(!empty($applications)){
			$totalLeaveDaysTaken = 0;
			foreach($applications AS $application){
				$totalLeaveDaysTaken+=leavesManager::calculateLeaveDuration($application->start_date,$application->end_date);
			}
		}else{
			$totalLeaveDaysTaken = 0;
		}
		return $totalLeaveDaysTaken;
	}

	public static function calculateRemainingLeaveDays($totalLeaveDays,$totalDaysTaken){
		$difference = $totalLeaveDays - $totalDaysTaken;
		return $difference;
	}

	public static function getLoggedStaffLeaveID(){
		$userID=Yii::app()->user->user_id;
		$leaveSql="SELECT * FROM leaves WHERE user_id=$userID";
		$leaves=Leaves::model()->findBySql($leaveSql);
		if(!empty($leaves)){
			$leaveID=$leaves->id;
		}else{
			$leaveID=0;
		}
		return $leaveID;
	}

	public static function getAllLeaveApplications($leaveID){
		$applicationSql="SELECT * FROM leave_applications WHERE leave_id=$leaveID";
		$applications = LeaveApplications::model()->findAllBySql($applicationSql);
		if(!empty($applications)){
			$leaveApplied = $applications;
		}else{
			$leaveApplied = 0;
		}
		return $leaveApplied;
	}
}