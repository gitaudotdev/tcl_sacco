<?php

class SmsAlertsController extends Controller{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/templates/pages';
	/**
	 * @return array action filters
	 */
	public function filters(){
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules(){
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('balanceUpdate','loansDue','reminders','repo','loadFilteredNotifications'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('loansOverdue','admin'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('weeklyPerformance'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
		$element = Yii::app()->user->user_level;
		$array  = array('2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$this->render('view',array('model'=>$this->loadModel($id)));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionLoansDue(){
		$loanaccounts=SMS::getRunningLoans();
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			if(isset($_POST['send_sms_cmd'])){
				foreach($_POST['loanaccounts_borrower'] as $loanaccount){
					$loan=Loanaccounts::model()->findByPk($loanaccount);
					$user=Profiles::model()->findByPk($loan->user_id);
					$phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
					$userFullName=$user->ProfileFullName;
					$smsAlerts  = ProfileEngine::getActiveProfileAccountSettingByType($user->id,'SMS_ALERTS');
					$smsAllowed = $smsAlerts === 'NOT SET' ? 'DISABLED' : $smsAlerts;
					if($smsAllowed === 'ACTIVE'){
						$accountNumber=$user->idNumber;
						$loanBalance=LoanManager::getActualLoanBalance($loanaccount);
						$balanceFormatted=CommonFunctions::asMoney($loanBalance);
						$message = " Your loan payment is now due. Please pay through;\nPaybill=754298\nAccount=$accountNumber\nBalance=$balanceFormatted\nCall your Account Manager for any assistance";
						$textMessage = "Dear ".$user->firstName.",".  $message;
						$numbers=array();
						array_push($numbers,$phoneNumber);
						$alertType='11';
						if(SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id) === 3){
							$type='danger';
							$message="SMS alert not sent since it has been disabled.";
							CommonFunctions::setFlashMessage($type,$message);
							$this->redirect(array('loansDue'));
						}else{
							$type='success';
							$message="SMS Sent successfully";
							CommonFunctions::setFlashMessage($type,$message);
							$this->redirect(array('loansDue'));
						}
					}else{
						$type='info';
						$message="SMS alert not sent since it has been disabled for user: $userFullName";
						CommonFunctions::setFlashMessage($type,$message);
					}
				}
			}
			$this->render('loansDue',array('loanaccounts'=>$loanaccounts));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionLoansOverdue(){
		$loanaccounts=SMS::getLoansOverDue();
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
				if(isset($_POST['send_sms_cmd'])){
					foreach($_POST['loanaccounts_borrower'] as $loanaccount){
						$penaltySQL="SELECT SUM(penalty_amount) as penalty_amount FROM penaltyaccrued WHERE is_paid='0' 
						AND loanaccount_id=$loanaccount";
						$penalty=Penaltyaccrued::model()->findBySql($penaltySQL);
						if(!empty($penalty)){
							$loan=Loanaccounts::model()->findByPk($loanaccount);
							$user=Profiles::model()->findByPk($loan->user_id);
							$phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
							$userFullName=$user->ProfileFullName;
							$smsAlerts  = ProfileEngine::getActiveProfileAccountSettingByType($user->id,'SMS_ALERTS');
							$smsAllowed = $smsAlerts === 'NOT SET' ? 'DISABLED' : $smsAlerts;
							if($smsAllowed === 'ACTIVE'){
								$accountNumber=$user->idNumber;
								$loanPenalties=$penalty->penalty_amount;
								$balanceFormatted=CommonFunctions::asMoney($loanPenalties);
								$message = " Your account update is :\nPrinciple=$principalBalance/-\nRate=$interestRate% \nInterest=$interestBalance/-\nPenalty=$loanPenalty/-\nTotal Bal=$balanceFormatted/-\nThank You!";
								$textMessage = "Dear ".$user->firstName.",".  $message;
								$numbers=array();
								array_push($numbers,$phoneNumber);
								$alertType='4';
								$status=SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id);
								$type='success';
								$message="SMS Sent successfully";
								CommonFunctions::setFlashMessage($type,$message);
								$this->redirect(array('loansOverdue'));
							}else{
								$type='info';
								$message="SMS alert not sent since it has been disabled for user: $userFullName";
								CommonFunctions::setFlashMessage($type,$message);
							}
						}
					}
				}
				$this->render('loansOverdue',array('loanaccounts'=>$loanaccounts));
			break;

			case 1:
				CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionBalanceUpdate(){
		$loanaccounts=SMS::getRunningLoans();
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			if(isset($_POST['send_sms_cmd'])){
				foreach($_POST['loanaccounts_borrower'] as $loanaccount){
					$loan = Loanaccounts::model()->findByPk($loanaccount);
					$user = Profiles::model()->findByPk($loan->user_id);
					$phoneNumber  = ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
					$userFullName = $user->ProfileFullName;
					$smsAlerts  = ProfileEngine::getActiveProfileAccountSettingByType($user->id,'SMS_ALERTS');
					$smsAllowed = $smsAlerts === 'NOT SET' ? 'DISABLED' : $smsAlerts;
					if($smsAllowed === 'ACTIVE'){
						$accountNumber=$user->idNumber;
						$loanBalance=LoanManager::getActualLoanBalance($loanaccount);
						$balanceFormatted=CommonFunctions::asMoney($loanBalance);
						$principalBalance=number_format(LoanManager::getPrincipalBalance($loan->loanaccount_id),2);
						$interestRate=$loan->interest_rate;
						$loanPenalty=number_format(LoanManager::getUnpaidAccruedPenalty($loan->loanaccount_id),2);
						$interestBalance=number_format(LoanManager::getUnpaidAccruedInterest($loan->loanaccount_id),2);
						$message = " Your account update is :\nPrinciple=$principalBalance/-\nRate=$interestRate% \nInterest=$interestBalance/-\nPenalty=$loanPenalty/-\nTotal Bal=$balanceFormatted/-\nThank You!";
						$textMessage = "Dear ".$user->firstName.",".  $message;
						$numbers=array();
						array_push($numbers,$phoneNumber);
						$alertType='0';
						if(SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id) != 1){
							$type='danger';
							$message="SMS alert not sent since it has been disabled.";
						}else{
							$type='success';
							$message="SMS Sent successfully";
						}
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('balanceUpdate'));
					}else{
						$type='info';
						$message="SMS alert not sent since it has been disabled for user: $userFullName";
						CommonFunctions::setFlashMessage($type,$message);
					}
				}
			}
			$this->render('balance_update',array('loanaccounts'=>$loanaccounts));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionReminders(){
		$loanaccounts=SMS::getRunningLoans();
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			if(isset($_POST['send_sms_cmd'])){
				foreach($_POST['loanaccounts_borrower'] as $loanaccount){
					$loan=Loanaccounts::model()->findByPk($loanaccount);
					$user=Profiles::model()->findByPk($loan->user_id);
					$phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
					$userFullName=$user->ProfileFullName;
					$smsAlerts  = ProfileEngine::getActiveProfileAccountSettingByType($user->id,'SMS_ALERTS');
					$smsAllowed = $smsAlerts === 'NOT SET' ? 'DISABLED' : $smsAlerts;
					if($smsAllowed === 'ACTIVE'){
						$accountNumber=$user->idNumber;
						$loanBalance=LoanManager::getActualLoanBalance($loanaccount);
						$balanceFormatted=CommonFunctions::asMoney($loanBalance);
						$message = " Kindly make your loan payment through\nPaybill=754298\nAccount=$accountNumber\n Your Balance is=$balanceFormatted\nThank you!";
						$textMessage = "Dear ".$user->firstName.",".  $message;
						$numbers=array();
						array_push($numbers,$phoneNumber);
						$alertType='11';
						if(SMS::broadcastSMS($numbers,$textMessage,'11',$user->id) != 1){
							$type    = 'danger';
							$message = "SMS alert not sent since it has been disabled.";
						}else{
							$type='success';
							$message="SMS Sent successfully";
						}
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('reminders'));
					}else{
						$type='info';
						$message="SMS alert not sent since it has been disabled for user: $userFullName";
						CommonFunctions::setFlashMessage($type,$message);
					}
				}
			}
			$this->render('reminders',array('loanaccounts'=>$loanaccounts));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionWeeklyPerformance(){
		$startDate = date('Y-m-01');
		$endDate   = date('Y-m-t');
		switch(Navigation::checkIfAuthorized(133)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
  	 		$this->redirect(array('dashboard/default'));
			break;

			case 1:
    		if(isset($_POST['send_sms_cmd'])){
				foreach($_POST['staff_members'] as $member){
					$staff=Profiles::model()->findByPk($member);
					$phoneNumber=ProfileEngine::getProfileContactByTypeOrderDesc($staff->id,'PHONE');
					$firstName=$staff->firstName;
					$defaultCTarget     = ProfileEngine::getActiveProfileAccountSettingByType($staff->id,'COLLECTIONS_TARGET');
					$collectionsTarget  = $defaultCTarget === 'NOT SET' ? 0 : floatval($defaultCTarget);
					$defaultSTarget     = ProfileEngine::getActiveProfileAccountSettingByType($staff->id,'SALES_TARGET');
					$salesTarget        = $defaultSTarget === 'NOT SET' ? 0 : floatval($defaultSTarget);
					$formatSalesTarget=number_format($salesTarget,2);
					$salesAchieved=Performance::getTotalStaffSales($staff->id,$startDate,$endDate);
					$formatSalesAchieved=number_format($salesAchieved,2);
					$salesPercent=Performance::getPerformancePercentage($salesTarget,$salesAchieved);
					$formatSalesPercent=number_format($salesPercent,2);
					$formatCollectionsTarget=number_format($collectionsTarget,2);
					$collectionsAchieved=Performance::getTotalStaffCollections($staff->id,$startDate,$endDate);
					$formatCollectionsAchieved=number_format($collectionsAchieved,2);
					$collectionsPercent=Performance::getPerformancePercentage($collectionsTarget,$collectionsAchieved);
					$formatCollectionsPercent=number_format($collectionsPercent,2);
					$performanceMessage="Dear $firstName, Your Performance is\nST = $formatSalesTarget, Achieved = $formatSalesAchieved/- ($formatSalesPercent%)\nCT = $formatCollectionsTarget, Achieved = $formatCollectionsAchieved/- ($formatCollectionsPercent%)\nSee dashboard for more info.\nThank you!";
					$numbers=array();
					array_push($numbers,$phoneNumber);
					$alertType='8';
					if(SMS::broadcastSMS($numbers,$performanceMessage,$alertType,$staff->id) === 3){
						$type='danger';
						$message="SMS alert not sent since it has been disabled.";
					}else{
						$type='success';
						$message="SMS Sent successfully";
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('weeklyPerformance'));
				}
			}
			$this->render('weeklyPerformance',array('staff_members'=>ProfileEngine::getProfilesByType('STAFF')));
			break;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$model=new SmsAlerts('search');
			$model->unsetAttributes();
			if(isset($_GET['SmsAlerts'])){
				$model->attributes=$_GET['SmsAlerts'];
			}
			$this->render('admin',array('model'=>$model));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionRepo(){
		$element=Yii::app()->user->user_level;
		$array=array('2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$this->render('repo',array('branches'=>Reports::getAllBranches()));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
  	 		$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionLoadFilteredNotifications(){
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
			$branch=$_POST['branch'];
			$staff=$_POST['staff'];
			$phoneNumber=$_POST['phoneNumber'];
			echo SMS::LoadFilteredNotifications($phoneNumber,$staff,$branch,$start_date,$end_date);
			break;

			case 1:
			echo "NOT FOUND";
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SmsAlerts the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=SmsAlerts::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SmsAlerts $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='sms-alerts-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
