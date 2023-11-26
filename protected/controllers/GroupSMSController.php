<?php

class GroupSMSController extends Controller{
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
			'accessControl',
		);
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules(){
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','authsUpdate','admin','approve','reject','view','authsView','updateDetails','auths','authsCreate'),
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
		$model = $this->loadModel($id);
		if(!empty($model)){
			switch(Navigation::checkIfAuthorized(288)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to view group SMS.");
				$this->redirect(array('dashboard/default'));
				break;
	
				case 1:
				$groups = SMS::getGroupSMSChamas($id);
				$this->render('view',array('model'=>$model,'groups'=>$groups));
				break;
			}
		}else{
			CommonFunctions::setFlashMessage('danger',"Group SMS record with specified ID does not exist.");
			$this->redirect(array('admin'));
		}
	}

	public function actionAuthsView($id){
		$model = $this->loadModel($id);
		if(!empty($model)){
			switch(Navigation::checkIfAuthorized(307)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to view auth level SMS.");
				$this->redirect(array('dashboard/default'));
				break;
	
				case 1:
				$groups = SMS::getGroupSMSChamas($id);
				$this->render('auths_view',array('model'=>$model,'groups'=>$groups));
				break;
			}
		}else{
			CommonFunctions::setFlashMessage('danger',"Auth Level SMS record with specified ID does not exist.");
			$this->redirect(array('auths'));
		}
	}

	public function actionApprove($id){
		$model = $this->loadModel($id);
		if(!empty($model)){
			switch(Navigation::checkIfAuthorized(286)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to approve group SMS.");
				$this->redirect(array('dashboard/default'));
				break;
	
				case 1:
				if(isset($_POST['approve_sms_cmd']) && isset($_POST['actionReason'])){
					$actionReason = htmlspecialchars($_POST['actionReason']);
					$sent = SMS::approveAndDispatchSMS($id,$actionReason);
					switch($sent){
						case 1000:
						CommonFunctions::setFlashMessage('success',"Group SMS approved and SMS sent successfully.");
						break;

						default:
						CommonFunctions::setFlashMessage('danger',"Failed to approve initiated group SMS. Please try again later.");
						break;
					}
					$this->redirect(array('admin'));
				}
				break;
			}
			$this->render('view',array('model'=>$model));
		}else{
			CommonFunctions::setFlashMessage('danger',"Group SMS record with specified ID does not exist.");
			$this->redirect(array('admin'));
		}
	}

	public function actionReject($id){
		$model = $this->loadModel($id);
		if(!empty($model)){
			switch(Navigation::checkIfAuthorized(287)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to reject group SMS.");
				$this->redirect(array('dashboard/default'));
				break;
	
				case 1:
				if(isset($_POST['reject_sms_cmd']) && isset($_POST['actionReason'])){
					$actionReason = htmlspecialchars($_POST['actionReason']);
					$sent = SMS::rejectGroupSMS($id,$actionReason);
					switch($sent){
						case 1000:
						CommonFunctions::setFlashMessage('danger',"Group SMS rejected successfully.");
						break;

						default:
						CommonFunctions::setFlashMessage('danger',"Failed to reject initiated group SMS. Please try again later.");
						break;
					}
					$this->redirect(array('admin'));
				}
				break;
			}
			$this->render('view',array('model'=>$model));
		}else{
			CommonFunctions::setFlashMessage('danger',"Group SMS record with specified ID does not exist.");
			$this->redirect(array('admin'));
		}
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
		switch(Navigation::checkIfAuthorized(285)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to initiate group SMS.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
				$chamas = Chama::getChamaLevelBased();
				if(isset($_POST['initiate_sms_cmd'])){
					$message = $_POST['textMessage'];
					$groups  = $_POST['chamas'];
					switch(SMS::initiateGroupSMS($message,$groups,1)){
						case 1000:
						Logger::logUserActivity("Initiated Group SMS",'normal');
						$type    = 'success';
						$message = "Group SMS initiated successfully.";
						break;
	
						case 1001:
						$type    = 'danger';
						$message = "Failed to initate group SMS. Check your details and retry.";
						break;
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('admin'));
				}
				$this->render('create',array('chamas'=>$chamas));
			break;
		}
	}

	public function actionAuthsCreate(){
		switch(Navigation::checkIfAuthorized(305)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to initiate auth level SMS.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
				$authQuery = "SELECT DISTINCT(level) AS level FROM `auths`";
				$auths = Auths::model()->findAllBySql($authQuery);
				if(isset($_POST['initiate_sms_cmd'])){
					$message = $_POST['textMessage'];
					$auths  = $_POST['auths'];
					switch(SMS::initiateGroupSMS($message,$auths,0)){
						case 1000:
						Logger::logUserActivity("Initiated Group SMS: Authorization Level",'normal');
						$type    = 'success';
						$message = "Group SMS initiated successfully.";
						break;
	
						case 1001:
						$type    = 'danger';
						$message = "Failed to initate group SMS. Check your details and retry.";
						break;
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('auths'));
				}
				$this->render('auths_create',array('auths'=>$auths));
			break;
		}
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
		$model=$this->loadModel($id);
		if(!empty($model)){
			switch(Navigation::checkIfAuthorized(289)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update group SMS.");
				$this->redirect(array('dashboard/default'));
				break;
	
				case 1:
				if($model->status === 'SUBMITTED'){
					if(isset($_POST['GroupSMS'])){
						$model->attributes=$_POST['GroupSMS'];
						if($model->save()){
							CommonFunctions::setFlashMessage('success',"Group SMS message updated successfully.");
							$this->redirect(array('admin'));
						}
					}
					$this->render('update',array('model'=>$model));
				}else{
					CommonFunctions::setFlashMessage('danger',"Group SMS cannot be updated since it has either been approved or rejected.");
					$this->redirect(array('admin'));
				}
				break;
			}
		}else{
			CommonFunctions::setFlashMessage('danger',"Group SMS record not found.");
			$this->redirect(array('admin'));
		}
	}

	public function actionAuthsUpdate($id){
		$model=$this->loadModel($id);
		if(!empty($model)){
			switch(Navigation::checkIfAuthorized(306)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update auth level SMS.");
				$this->redirect(array('dashboard/default'));
				break;
	
				case 1:
				if($model->status === 'SUBMITTED'){
					if(isset($_POST['GroupSMS'])){
						$model->attributes=$_POST['GroupSMS'];
						if($model->save()){
							CommonFunctions::setFlashMessage('success',"Auth level SMS message updated successfully.");
							$this->redirect(array('auths'));
						}
					}
					$this->render('auths_update',array('model'=>$model));
				}else{
					CommonFunctions::setFlashMessage('danger',"Auth level SMS cannot be updated since it has either been approved or rejected.");
					$this->redirect(array('auths'));
				}
				break;
			}
		}else{
			CommonFunctions::setFlashMessage('danger',"Auth level SMS record not found.");
			$this->redirect(array('auths'));
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(288)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view group SMS.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model = new GroupSMS('search');
			$model->unsetAttributes();
			if(isset($_GET['GroupSMS'])){
				$model->attributes = $_GET['GroupSMS'];
			}
			$this->render('admin',array('model' => $model));
			break;
		}
	}

	public function actionAuths(){
		switch(Navigation::checkIfAuthorized(304)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view auth level SMS.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model = new GroupSMS('searchAuths');
			$model->unsetAttributes();
			if(isset($_GET['GroupSMS'])){
				$model->attributes = $_GET['GroupSMS'];
			}
			$this->render('auths',array('model' => $model));
			break;
		}
	}

	public function actionUpdateDetails(){
		$element= Yii::app()->user->user_level;
		$array  = array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$groups=GroupSMS::model()->findAll();
			if(!empty($groups)){
				foreach($groups AS $group){
					$profile = Profiles::model()->findByPk($group->createdBy);
					$group->branchId  = $profile->branchId;
					$group->managerId = $profile->managerId;
					$group->update();
				}
			}
			break;

			case 1:
			$type='danger';
			$message="Restricted Area. You are not allowed to access this resource.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GroupSMS the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model = GroupSMS::model()->findByPk($id);
		if($model === null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param GroupSMS $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='group-sms-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
