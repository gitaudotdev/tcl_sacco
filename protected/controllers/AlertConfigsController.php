<?php

class AlertConfigsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/templates/pages';
	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
				'actions'=>array('admin','activate','disable'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(111)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view alert settings.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=new AlertConfigs('search');
			$model->unsetAttributes();
			if(isset($_GET['AlertConfigs'])){
				$model->attributes=$_GET['AlertConfigs'];
			}
			$this->render('admin',array('model'=>$model,));
    		break;
    	}
	}

	public function actionActivate($id){
		$element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$model=$this->loadModel($id);
			$model->is_active='1';
			if($model->save()){
				$alertName=$model->name;
	      		Logger::logUserActivity("Activated SMS ALERTS: $alertName",'normal');
				$type='success';
				$message="Alerts activated successfully.";
			}else{
				$type='warning';
				$message="Alerts not activated.";
			}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
  	 		$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionDisable($id){
		$element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$model=$this->loadModel($id);
			$model->is_active='0';
			if($model->save()){
				$alertName=$model->name;
	      		Logger::logUserActivity("Deactivated SMS ALERTS: $alertName",'normal');
				$type='success';
				$message="Alerts deactivated successfully.";
			}else{
				$type='warning';
				$message="Alerts not deactivated.";
			}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
  	 		$this->redirect(array('dashboard/default'));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AlertConfigs the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=AlertConfigs::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AlertConfigs $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='alert-configs-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
