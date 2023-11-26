<?php

class AirtimeController extends Controller{

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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','create','approve','reject','disburse','update'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
    	switch(Navigation::checkIfAuthorized(172)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to initiate Airtime Transactions.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Airtime;
			if(isset($_POST['Airtime'])){
				$branchID            = Profiles::model()->findByPk($_POST['Airtime']['user_id'])->branchId;
				$relationManager     = Profiles::model()->findByPk($_POST['Airtime']['user_id'])->managerId;
				$phoneNumber         = ProfileEngine::getProfileContactByTypeOrderDesc($_POST['Airtime']['user_id'],'PHONE');
				$model->attributes   = $_POST['Airtime'];
				$model->branch_id    = $branchID;
				$model->rm           = $relationManager;
				$model->phone_number = $phoneNumber;
				$model->created_at   = date('Y-m-d H:i:s');
				if($model->save()){
					$fullName     = Profiles::model()->findByPk($model->user_id)->ProfileFullName;
					$airtimeAmount= CommonFunctions::asMoney($model->amount);
					$phoneNumber  = $model->phone_number;
					Logger::logUserActivity("Initiated transaction of $airtimeAmount as airtime for $fullName to number: $phoneNumber",'normal');
					CommonFunctions::setFlashMessage('success',"Airtime transaction initiated sucessfully.");
				}else{
					CommonFunctions::setFlashMessage('warning',"Airtime transaction failed to initiate.");
				}
				$this->redirect(array('admin'));
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
    	switch(Navigation::checkIfAuthorized(173)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update Airtime Transactions.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=$this->loadModel($id);
			$elementStatus=$model->status;
			$arrayStatus=array('1','2','3');
			if(CommonFunctions::searchElementInArray($elementStatus,$arrayStatus) == 0){
				$model=$this->loadModel($id);
				if(isset($_POST['Airtime'])){
					$model->attributes=$_POST['Airtime'];
					if($model->save()){
						$fullName     = Profiles::model()->findByPk($model->user_id)->ProfileFullName;
						$airtimeAmount= CommonFunctions::asMoney($model->amount);
						$phoneNumber  = $model->phone_number;
						Logger::logUserActivity("Updated transaction of $airtimeAmount as airtime for $fullName to number: $phoneNumber",'normal');
						CommonFunctions::setFlashMessage('success',"Airtime transaction updated sucessfully.");
					$this->redirect(array('admin'));
					}
				}
				$this->render('update',array('model'=>$model));
			}else{
			CommonFunctions::setFlashMessage('danger',"Operation Forbidden. Transaction cannot be Updated.");
			$this->redirect(array('admin'));
			}
    		break;
    	}
	}

	public function actionApprove($id){
    	switch(Navigation::checkIfAuthorized(169)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to approve Airtime Transactions.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=$this->loadModel($id);
			$elementStatus=$model->status;
			$arrayStatus=array('1','2','3');
			if(CommonFunctions::searchElementInArray($elementStatus,$arrayStatus) == 0){
				switch(AirtimeManager::approveAirtimeTransaction($id)){
					case 0:
					CommonFunctions::setFlashMessage('warning',"Airtime transaction approval failed.");
					break;

					case 1:
					CommonFunctions::setFlashMessage('success',"Airtime transaction approved sucessfully.");
					break;
				}
			}else{
				CommonFunctions::setFlashMessage('danger',"Operation Forbidden. Transaction cannot be Approved.");
			}
			$this->redirect(array('admin'));
    		break;
    	}
	}

	public function actionReject($id){
    	switch(Navigation::checkIfAuthorized(170)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to reject Airtime Transactions.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model         = $this->loadModel($id);
			$elementStatus = $model->status;
			$arrayStatus   = array('1','2','3');
			if(CommonFunctions::searchElementInArray($elementStatus,$arrayStatus) == 0){
				switch(AirtimeManager::rejectAirtimeTransaction($id)){
					case 0:
					CommonFunctions::setFlashMessage('warning',"Airtime transaction rejection failed.");
					break;

					case 1:
					CommonFunctions::setFlashMessage('success',"Airtime transaction rejected sucessfully.");
					break;
				}
			}else{
				CommonFunctions::setFlashMessage('danger',"Operation Forbidden. Transaction cannot be Rejected.");
			}
		    $this->redirect(array('admin'));
    		break;
    	}
	}

	public function actionDisburse($id){
    	switch(Navigation::checkIfAuthorized(171)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to disburse Airtime Transactions.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model         = $this->loadModel($id);
			$elementStatus = $model->status;
			$arrayStatus   = array('0','2','3');
			if(CommonFunctions::searchElementInArray($elementStatus,$arrayStatus) == 0){
				switch(AirtimeManager::disburseAirtime($id)){
					case 0:
					CommonFunctions::setFlashMessage('warning',"Airtime transaction disbursal failed.");
					break;

					case 1:
					CommonFunctions::setFlashMessage('success',"Airtime transaction disbursed sucessfully.");
					break;
				}
			}else{
				CommonFunctions::setFlashMessage('danger',"Operation Forbidden. Transaction cannot be Disbursed.");
			}
			$this->redirect(array('admin'));
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(168)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Airtime Transactions.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Airtime('search');
			$model->unsetAttributes();
			if(isset($_GET['Airtime'])){
				$model->attributes=$_GET['Airtime'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Airtime the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Airtime::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Airtime $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='airtime-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
