<?php

class OrganizationController extends Controller{
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','logo'),
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
		$element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$model=new Organization;
			if(isset($_POST['Organization'])){
				$model->attributes=$_POST['Organization'];
				if($model->save()){
					$activity="Added Microfinance Details";
					$severity='normal';
					Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Organization details successfully created.";
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('admin'));
				}
			}
			$this->render('create',array('model'=>$model));
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
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate(){
		$element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$model=$this->loadModel(1);
			if(isset($_POST['Organization'])){
				$model->attributes=$_POST['Organization'];
				$model->automated_payroll=$_POST['Organization']['automated_payroll'];
				$model->enable_mpesa_b2c=$_POST['Organization']['enable_mpesa_b2c'];
				if($model->save()){
					$activity="Updated Microfinance Details";
					$severity='urgent';
					Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Organization details updated.";
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('admin'));
				}
			}
			$this->render('update',array('model'=>$model));
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
	 * Manages all models.
	 */
	public function actionAdmin(){
	  $element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$model=$this->loadModel(1);
			$this->render('admin',array('model'=>$model));
			break;

			case 1:
			$type='danger';
			$message="Restricted Area. You are not allowed to access this resource.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionLogo(){
	$element=Yii::app()->user->user_level;
    $array=array('1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
			$uploaded_logo    = CUploadedFile::getInstanceByName('logo');
			if(empty($uploaded_logo) || $uploaded_logo === ''){
				$type    ='danger';
				$message ="No Logo Uploaded";
			}else{
				$fileTempName  = $uploaded_logo->getTempName();
				$fileSize      = $uploaded_logo->getSize();
				$extension     = pathinfo($uploaded_logo, PATHINFO_EXTENSION);
  			if(!in_array($extension, ['jpg','jpeg','png'])){
			    $type    = 'danger';
					$message = " Please upload an image of type: jpg or png";
			  }else{
			  	if($fileSize > 2097152){
							$type    = 'danger';
							$message = "Please upload an image less than 2 MB.";
			  	}else{
	  				$hashedName= CommonFunctions::generateToken(7).''.date('YmdHis',time()).mt_rand().'.'.$extension;
						$organization       = $this->loadModel(1);
						$organization->logo = $hashedName;
						if($organization->save()){
							$destination = Yii::app()->basePath."/../images/site/".$hashedName;
							move_uploaded_file($fileTempName,$destination);
							$activity = "Updated Microfinance Logo Image";
					    $severity = 'normal';
					    Logger::logUserActivity($activity,$severity);
							$type    = 'success';
							$message = "Logo successfully uploaded.";
						}else{
							$type='danger';
							$message="Uploading logo Failed.";
						}	
			  	}
			  }
			}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
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
	 * @return Organization the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Organization::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Organization $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='organization-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
