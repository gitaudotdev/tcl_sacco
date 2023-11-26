<?php

class PerformanceSettingsController extends Controller{
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
				'actions'=>array('update','admin'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
    	switch(Navigation::checkIfAuthorized(163)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update performance settings");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=$this->loadModel($id);
				$perfomanceName=$model->name;
				if(isset($_POST['PerformanceSettings'])){
					$model->attributes=$_POST['PerformanceSettings'];
					if($model->save()){
						Logger::logUserActivity("Update perfomance setting for $perfomanceName",'high');
						CommonFunctions::setFlashMessage('success',"Performance successfully updated.");
						$this->redirect(array('admin'));
					}
				}
				$this->render('update',array('model'=>$model));
    		break;
    	}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(162)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view performance settings.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new PerformanceSettings('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['PerformanceSettings'])){
				$model->attributes=$_GET['PerformanceSettings'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PerformanceSettings the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=PerformanceSettings::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PerformanceSettings $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='performance-settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
