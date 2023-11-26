<?php

class LeavesController extends Controller{
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
				'actions'=>array('create','update','view','streamline'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
      	switch(Navigation::checkIfAuthorized(187)){
	  		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view leave details.");
	  	 	$this->redirect(array('dashboard/default'));
	  		break;

	  		case 1:
			$model        = $this->loadModel($id);
			$arrayConfirm = array($model->user_id);
			$user         = Profiles::model()->findByPk($model->user_id);
			$applications = leavesManager::getAllLeaveApplications($id);
			$admins       = leavesManager::LeaveApproversList();
			$handovers    = leavesManager::getHandoverStaffList();
			$this->render('view',array('model'=>$model,'user'=>$user,'applications'=>$applications,'admins'=>$admins,'handovers'=>$handovers));
			break;
    	}
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
      switch(Navigation::checkIfAuthorized(185)){
		case 0:
		CommonFunctions::setFlashMessage('danger',"Not Authorized to create leave details.");
		$this->redirect(array('dashboard/default'));
		break;

		case 1:
		$model=new Leaves;
		if(isset($_POST['Leaves'])){
			$userID=$_POST['Leaves']['user_id'];
			$leaveDays=$_POST['Leaves']['leave_days'];
			$carryOver=$_POST['Leaves']['carry_over'];
			switch(leavesManager::createStaffLeaveRecord($userID,$leaveDays,$carryOver)){
				case 0:
				$type='danger';
				$message="Record Not Created.";
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('create'));
				break;

				case 1:
				$type='success';
				$message="Record successfully created.";
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('admin'));
				break;

				case 2:
				$type='warning';
				$message="Duplicate Record Exists for this user.";
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('create'));
				break;
			}
			$model->attributes=$_POST['Leaves'];
			if($model->save()){
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		$this->render('create',array('model'=>$model));
		break;
		}
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
      switch(Navigation::checkIfAuthorized(184)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update leave details.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Leaves'])){
				$model->attributes=$_POST['Leaves'];
				if($model->save()){
					$type='info';
					$message="Record successfully updated.";
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
       switch(Navigation::checkIfAuthorized(186)){
	  		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete leave details.");
	  	 	$this->redirect(array('dashboard/default'));
	  		break;

	  		case 1:
	      	Yii::app()->db->createCommand("DELETE FROM leave_applications WHERE leave_id=$id")->execute();
			$this->loadModel($id)->delete();
			$type='success';
			$message="Leave record and all associated leave requests successfully deleted.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
       switch(Navigation::checkIfAuthorized(187)){
	  		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view leave details.");
	  	 	$this->redirect(array('dashboard/default'));
	  		break;

	  		case 1:
			$model=new Leaves('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Leaves'])){
				$model->attributes=$_GET['Leaves'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}

	public function actionStreamline(){
		$element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$applications=LeaveApplications::model()->findAll();
			if(!empty($applications)){
				foreach($applications AS $application){
					$branchID=Profiles::model()->findByPk($application->user_id)->branchId;
					$application->branch_id=$branchID;
					$application->save();
				}
			}
			$leaves=Leaves::model()->findAll();
			if(!empty($leaves)){
				foreach($leaves AS $leave){
					$branchID=Profiles::model()->findByPk($leave->user_id)->branchId;
					$leave->branch_id=$branchID;
					$leave->save();
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
	 * @return Leaves the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Leaves::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Leaves $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='leaves-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
