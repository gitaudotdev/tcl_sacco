<?php

class LeaveApplicationsController extends Controller{
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
				'actions'=>array('update','view','create'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','submitApplication','approve','reject'),
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
    	switch(Navigation::checkIfAuthorized(120)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view leave requests.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('view',array('model'=>$model,'leave'=>Leaves::model()->findByPk($model->leave_id),
			'user'=>Profiles::model()->findByPk($model->user_id)));
    		break;
    	}
	}

	public function actionCreate(){
    	switch(Navigation::checkIfAuthorized(182)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to submit leave requests.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$profiles = leavesManager::getProfileLeaveStaff();
			$admins   = leavesManager::LeaveApproversList();
			$handovers= leavesManager::getHandoverStaffList();
			$this->render('create',array('admins'=>$admins,'handovers'=>$handovers,'profiles'=>$profiles));
			break;
		}
	}

	public function actionSubmitApplication(){
    	switch(Navigation::checkIfAuthorized(182)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to submit leave requests.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=new LeaveApplications;
				if(isset($_POST['apply_cmd']) && isset($_POST['user']) && isset($_POST['start_date']) && isset($_POST['end_date'])){
					$profile   = Profiles::model()->findByPk($_POST['user']);
					$record    = leavesManager::getprofileLeaveRecords($profile->id);
					if(empty($record)){
						$type    = 'danger';
						$message = "Application Failed. The user does not have leave records defined.";
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('admin'));
					}else{
						$startDate    = $_POST['start_date'];
						$endDate      = $_POST['end_date'];
						$directedTo   = $_POST['directed_to'];
						$branchID     = $profile->branchId;
						$handoverTo   = $_POST['handover_to'];
						$handoverNotes= $_POST['handover_notes'];
						switch(leavesManager::createStaffLeaveApplication($profile->id,$startDate,$endDate,$directedTo,$branchID,$handoverTo,$handoverNotes)){
							case 0:
							$type='danger';
							$message="Application Failed.";
							break;
	
							case 1:
							$type='success';
							$message="Application successfully submitted.";
							break;
	
							case 2:
							$type='danger';
							$message="You already have an Application Awaiting Approval.";
							break;
	
							case 3:
							$type='danger';
							$message="Days Applied for are More than Remaining Leave Days.";
							break;
						}
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('admin'));
					}
				}else{
					$type='danger';
					$message="Application Failed. No application data provided.";
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('leaves/'.$leaveID));
				}
    		break;
    	}
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
    	switch(Navigation::checkIfAuthorized(181)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update leave request.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=$this->loadModel($id);
				if(isset($_POST['LeaveApplications'])){
					$model->attributes=$_POST['LeaveApplications'];
					if($model->save()){
						$type='info';
						$message="Application successfully updated.";
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('admin'));
					}
				}
				$this->render('update',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
    	switch(Navigation::checkIfAuthorized(183)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete leave request.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted leave request","high");
			$type='success';
			$message="Leave request successfully deleted.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
    		break;
    	}
	}

	public function actionApprove($id){
    	switch(Navigation::checkIfAuthorized(179)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to approve leave requests.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model   = $this->loadModel($id);
			$leave   = Leaves::model()->findByPk($model->leave_id);
			$profile = Profiles::model()->findByPk($leave->user_id);
    		if(isset($_POST['auth_leave_cmd'])){
				$authReason=$_POST['auth_reason'];
				switch(leavesManager::authorizeStaffLeaveApplication($id,'1',$authReason)){
					case 0:
					$type='danger';
					$message="Operation Failure, unable to approve application.";
					break;

					case 1:
					$type='success';
					$message="Application successfully approved.";
					break;

					case 2:
					$type='danger';
					$message="Unable to approve the application.";
					break;

					case 3:
					$type='danger';
					$message="The application has already either been approved or rejected.";
					break;
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('admin'));
    		}else{
				$this->render('approve',array('model'=>$model,'leave'=>$leave,'user'=>$profile));
    		}
    		break;
    	}
	}

	public function actionReject($id){
    	switch(Navigation::checkIfAuthorized(180)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to reject leave request.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=$this->loadModel($id);
			$leave=Leaves::model()->findByPk($model->leave_id);
			$user=Profiles::model()->findByPk($leave->user_id);
    		if(isset($_POST['auth_leave_cmd'])){
				$authReason=$_POST['auth_reason'];
				switch(leavesManager::authorizeStaffLeaveApplication($id,'2',$authReason)){
					case 0:
					$type='danger';
					$message="Operation Failure, unable to reject application.";
					break;

					case 1:
					$type='success';
					$message="Application successfully rejected.";
					break;

					case 2:
					$type='danger';
					$message="Unable to reject the application.";
					break;

					case 3:
					$type='danger';
					$message="The application has already either been approved or rejected.";
					break;
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('admin'));
    		}else{
				$this->render('reject',array('model'=>$model,'leave'=>$leave,'user'=>$user));
    		}
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(120)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view leave requests.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
    		$model=new LeaveApplications('search');
			$model->unsetAttributes(); 
			if(isset($_GET['LeaveApplications'])){
				$model->attributes=$_GET['LeaveApplications'];
			}
			$this->render('admin',array('model'=>$model,));
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LeaveApplications the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=LeaveApplications::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param LeaveApplications $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='leave-applications-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
