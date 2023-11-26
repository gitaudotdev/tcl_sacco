<?php

class AssetTypeController extends Controller{
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
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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
		switch(Navigation::checkIfAuthorized(266)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to view asset type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$this->render('view',array('model'=>$this->loadModel($id)));
			break;
		}
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
		switch(Navigation::checkIfAuthorized(265)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed tocreate asset type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=new AssetType;
			if(isset($_POST['AssetType'])){
				$model->attributes=$_POST['AssetType'];
				$model->created_by=Yii::app()->user->user_id;
				if($model->save()){
					$activity="Added Asset Type";
					$severity='normal';
					Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Asset type successfully created.";
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('admin'));
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
		switch(Navigation::checkIfAuthorized(267)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to update asset type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['AssetType'])){
				$model->attributes=$_POST['AssetType'];
				if($model->save()){
					$activity="Updated Asset Type";
					$severity='normal';
					Logger::logUserActivity($activity,$severity);
					$type='info';
					$message="Asset type successfully updated.";
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
		switch(Navigation::checkIfAuthorized(268)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to delete asset type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$this->loadModel($id)->delete();
			$activity="Deleted Asset Type";
			$severity='urgent';
			Logger::logUserActivity($activity,$severity);
			$type='success';
			$message="Asset type successfully deleted.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(266)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to view asset types.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=new AssetType('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['AssetType'])){
				$model->attributes=$_GET['AssetType'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AssetType the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=AssetType::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param AssetType $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='asset-type-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
