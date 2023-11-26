<?php

include_once ('africastalking/AfricasTalkingGateway.php');

class SMS{

	public static function broadcastSMS($numbers,$message,$type,$profileId){
		$profile = Profiles::model()->findByPk($profileId);
		if(!empty($profile)){
			$smsEnabled = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'SMS_ALERTS');
			if($smsEnabled == 'ACTIVE'){
				$g           = new AfricasTalkingGateway(Yii::app()->params['AfricaStalking_Username'],Yii::app()->params['AfricaStalking_Key']);
				$alertStatus = SMS::getSMSAlertStatus($type);
				switch($alertStatus){
					case 0:
					if(!empty($numbers)){
						foreach($numbers as $number){
						 try{
								$phoneNumber = "+254".substr($number,-9);
								$results     = $g->sendMessage($phoneNumber,$message,Yii::app()->params['AfricaStalking_From']);
								foreach($results as $result){
									$alert = new SmsAlerts;
									$alert->message_id   = $result->messageId;
									$alert->phone_number = $result->number;
									$alert->cost         = $result->cost;
									$alert->message      = $message;
									$alert->profileId    = $profile->id;
									$alert->branchId     = $profile->branchId;
									$alert->managerId    = $profile->managerId;
									$alert->sent_by      = Yii::app()->user->user_id;
									$alert->sent_at      = date('Y-m-d H:i:s');
									$alert->save();
								}
							}catch(AfricasTalkingGatewayException $e){
								$status = 2;
							}
						}
						$status = 1;
					}else{
						$status = 0;
					}
					break;
		
					case 1:
					$status = 3;
					break;
				}
			}else{
				$status = 6;
			}
		}else{
			$status = 5;
		}
		return $status;
	}

	public static function getSMSAlertStatus($type){
		$alertSQL = "SELECT * FROM alert_configs WHERE type='$type' AND is_active='0'";
		$alert    = AlertConfigs::model()->findAllBySql($alertSQL);
		return !empty($alert) ? 1 : 0;
	}

	public static function getLoansOverDue(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanSQL="SELECT * FROM loanaccounts,penaltyaccrued WHERE loanaccounts.loanaccount_id=penaltyaccrued.loanaccount_id
		AND penaltyaccrued.is_paid='0'";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanSQL.="";
			break;

			case '1':
			$loanSQL.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$loanSQL.=" AND loanaccounts.rm=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanSQL);
		return $loanaccounts;
	}

	public static function getLoansDue(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$today=date('d');
		$loanSQL="SELECT * FROM loanaccounts WHERE (DAY(repayment_start_date)=$today)  AND loan_status NOT IN('0','1','3','4','8','9','10')";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanSQL.="";
			break;

			case '1':
			$loanSQL.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanSQL=" AND rm=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanSQL);
		return $loanaccounts;
	}

	public static function getRunningLoans(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanSQL="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10')";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanSQL.="";
			break;

			case '1':
			$loanSQL.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanSQL.=" AND rm=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanSQL);
		return $loanaccounts;
	}

	public static function getAllRepaidAccounts(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanSQL="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10')
		AND loanaccount_id IN(SELECT loanaccount_id FROM loantransactions WHERE is_void IN('0','3','4') )";
		switch(Yii::app()->user->user_level){
			case '0':
			$loanSQL.="";
			break;

			case '1':
			$loanSQL.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanSQL.=" AND rm=$userID";
			break;
		}
		$loanaccounts=Loanaccounts::model()->findAllBySql($loanSQL);
		return $loanaccounts;
	}

	public static function LoadFilteredNotifications($phoneNumber,$staff,$branch,$start_date,$end_date){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$smsQuery="SELECT * FROM sms_alerts,profiles WHERE sms_alerts.sent_by=profiles.id AND (DATE(sms_alerts.sent_at) BETWEEN '$start_date' AND '$end_date')";
		switch(Yii::app()->user->user_level){
			case '0':
			$smsQuery.="";
			break;

			case '1':
			$smsQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$smsQuery.=" AND sms_alerts.sent_by=$userID";
			break;

			case '3':
			$smsQuery.=" AND sms_alerts.sent_by=$userID";
			break;
		}
		echo SMS::getFilteredNotifications($phoneNumber,$staff,$branch,$smsQuery);
	}

	public static function getFilteredNotifications($phoneNumber,$staff,$branch,$smsQuery){
		if($phoneNumber !=0){
			$smsQuery.=" AND sms_alerts.phone_number LIKE '%$phoneNumber%'";
		}
		
		if($staff !=0){
			$smsQuery.=" AND sms_alerts.sent_by=$staff";
		}

		if($branch !=0){
			$smsQuery.=" AND profiles.branchId=$branch";
		}
		$smsQuery.=" ORDER BY sms_alerts.id DESC";
		$notifications=SmsAlerts::model()->findAllBySql($smsQuery);
		$notificationsTabulation=Tabulate::createNotificationsDetailsTable($notifications);
		echo $notificationsTabulation;
	}

	public static function getProfileNotifications($profileID){
		$contactQuery = "SELECT * FROM sms_alerts WHERE profileId=$profileID ORDER BY id DESC";
        return SmsAlerts::model()->findAllBySql($contactQuery);
	}
	/*** 
	 * 
	 * GROUP SMS 
	 */
	public static function initiateGroupSMS($message,$groups,$identity){
	  $profile = Profiles::model()->findByPk(Yii::app()->user->user_id);
      $notifyGroup = new GroupSMS;
	  $notifyGroup->message   = $message;
	  $notifyGroup->createdBy = $profile->id;
	  $notifyGroup->branchId  = $profile->branchId;
	  $notifyGroup->managerId = $profile->managerId;
	  $notifyGroup->groupType = $identity === 0 ? 'AUTH_LEVEL' : 'CHAMA';
	  $notifyGroup->createdAt = date("Y-m-d H:i:s");
	  if($notifyGroup->save()){
		  foreach($groups AS $group){
			SMS::persistGroupSMS($notifyGroup->id,$group);
		  }
		  return 1000;
	  }else{
		  return 1001;
	  }
	}

	public static function persistGroupSMS($groupSMSId,$groupId){
		$notifySMS = new SMSGroup;
		$notifySMS->groupSMSId = $groupSMSId;
		$notifySMS->groupId    = $groupId;
		$notifySMS->createdBy  = Yii::app()->user->user_id;
		$notifySMS->createdAt  = date("Y-m-d H:i:s");
		$notifySMS->save();
	}

	public static function approveAndDispatchSMS($groupSMSId,$actionReason){
       $approved = SMS::approveGroupSMS($groupSMSId,$actionReason);
	   if($approved == 1000){
		   SMS::dispatchGroupSMS($groupSMSId);
		   Logger::logUserActivity("Approved Group SMS and sent SMS to chama members",'urgent');
           return 1000;
	   }else{
		   return 1003;
	   }
	}

	public static function determineAuthLevel($numericLevel){
		switch($numericLevel){
			case 0:
			$level = "SUPERADMIN";
			break;

			case 1:
			$level = "ADMIN";
			break;

			case 2:
			$level = "STAFF";
			break;

			case 3:
			$level = "USER";
			break;
		}
		return $level;
	}

	public static function getProfilesByAuthLevel($numericLevel){
		$level = SMS::determineAuthLevel($numericLevel);
		$profileQuery = "SELECT * FROM `profiles` WHERE id IN(SELECT profileId FROM auths WHERE level='$level' AND authStatus='ACTIVE')";
		return Profiles::model()->findAllBySql($profileQuery);
	}

	public static function dispatchGroupSMS($groupSMSId){
		$notifyGroup = GroupSMS::model()->findByPk($groupSMSId);
		if(!empty($notifyGroup)){
			$textMessage = $notifyGroup->message;
			switch($notifyGroup->groupType){
				case "AUTH_LEVEL":
				$members = SMS::fetchAuthLevelProfiles($groupSMSId);
				if(!empty($members)){
					foreach($members AS $member){
						$phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($member,'PHONE');
						$numbers     = array();
						array_push($numbers,$phoneNumber);
						SMS::broadcastSMS($numbers,$textMessage,'41',$member);
					}
					SMS::completeGroupSMS($groupSMSId);
				}
				break;

				default:
				$members = SMS::fetchGroupSMSMembers($groupSMSId);
				if(!empty($members)){
					foreach($members AS $member){
						$phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($member,'PHONE');
						$numbers     = array();
						array_push($numbers,$phoneNumber);
						SMS::broadcastSMS($numbers,$textMessage,'41',$member);
					}
					SMS::completeGroupSMS($groupSMSId);
				}
				break;
			}
		}
	}

	public static function fetchAuthLevelProfiles($groupSMSId){
		$members    = array();
		$fetchQuery = "SELECT groupId FROM SMSGroup WHERE groupSMSId=$groupSMSId";
		$fetches    = SMSGroup::model()->findAllBySql($fetchQuery);
		if(!empty($fetches)){
		   foreach($fetches AS $fetch){
			 $chamaId = $fetch->groupId;
			 $clients      = SMS::getProfilesByAuthLevel($chamaId);
			 foreach($clients AS $client){
			   array_push($members,$client->id);
			 }
		   }
		}
		return $members;
	}
	
	public static function fetchGroupSMSMembers($groupSMSId){
	   $members    = array();
       $fetchQuery = "SELECT groupId FROM SMSGroup WHERE groupSMSId=$groupSMSId";
	   $fetches    = SMSGroup::model()->findAllBySql($fetchQuery);
	   if(!empty($fetches)){
          foreach($fetches AS $fetch){
			$chamaId = $fetch->groupId;
			$membersQuery = "SELECT user_id FROM chama_members WHERE chama_id=$chamaId";
			$clients      = ChamaMembers::model()->findAllBySql($membersQuery);
			foreach($clients AS $client){
			  array_push($members,$client->user_id);
			}
		  }
	   }
	   return $members;
	}

	public static function getGroupSMSChamas($groupSMSId){
		$chamasQuery = "SELECT * FROM chamas WHERE id IN(SELECT groupId FROM SMSGroup WHERE groupSMSId=$groupSMSId)";
		return Chamas::model()->findAllBySql($chamasQuery);
	}

	public static function completeGroupSMS($groupSMSId){
        Yii::app()->db->createCommand("UPDATE SMSGroup SET status='COMPLETED' WHERE groupSMSId=$groupSMSId")->execute();
	}

	public static function approveGroupSMS($groupSMSId,$actionReason){
		$notifyGroup = GroupSMS::model()->findByPk($groupSMSId);
		$notifyGroup->status = "APPROVED";
		$notifyGroup->actionReason = $actionReason;
		$notifyGroup->actionedBy   = Yii::app()->user->user_id;
		$notifyGroup->actionedAt   = date("Y-m-d H:i:s");
		return $notifyGroup->save() ? 1000 : 1001;
	}
    
	public static function rejectGroupSMS($groupSMSId,$actionReason){
		$notifyGroup = GroupSMS::model()->findByPk($groupSMSId);
		$notifyGroup->status = "REJECTED";
		$notifyGroup->actionReason = $actionReason;
		$notifyGroup->actionedBy   = Yii::app()->user->user_id;
		$notifyGroup->actionedAt   = date("Y-m-d H:i:s");
		return $notifyGroup->save() ? 1000 : 1001;
	}

}