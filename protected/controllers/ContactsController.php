<?php

class ContactsController extends Controller{
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
			array('allow',
				'actions'=>array('create','update','makePrimary'),
				'users'=>array('@'),
			),
			array('deny',
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
		$model=$this->loadModel($id);
		if(isset($_POST['Contacts'])){
			$model->attributes=$_POST['Contacts'];
			if($model->save()){
				CommonFunctions::setFlashMessage('success',"Profile contact has been updated successfully.");
				$this->redirect(array('profiles/'.$model->profileId));
			}
		}
		$this->render('update',array('model'=>$model));
	}

	public function actionMakePrimary($id){
		$model        = $this->loadModel($id);
		$toggleStatus = 1;
		$profileId    = $model->profileId;
		$contactValue = $model->contactValue;
		$contactType  = $model->contactType;
		ProfileEngine::makePreviousContactSecondary($profileId,$contactType);
		ProfileEngine::toggleContactPrimaryStatus($id,$toggleStatus);
		CommonFunctions::setFlashMessage('success',"Profile contact has been made primary successfully.");
		$this->redirect(array('profiles/'.$profileId));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Contacts the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Contacts::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Contacts $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='contacts-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
