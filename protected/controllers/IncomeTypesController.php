<?php

class IncomeTypesController extends Controller
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
		switch(Navigation::checkIfAuthorized(278)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to view income type.";
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
		switch(Navigation::checkIfAuthorized(277)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to create income type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=new IncomeTypes;
			if(isset($_POST['IncomeTypes'])){
				$model->attributes=$_POST['IncomeTypes'];
				$model->created_by=Yii::app()->user->user_id;
				if($model->save()){
					$activity="Added Income Type";
				$severity='normal';
				Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Income type successfully created.";
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
		switch(Navigation::checkIfAuthorized(279)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to update income type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['IncomeTypes'])){
				$model->attributes=$_POST['IncomeTypes'];
				if($model->save()){
					$activity="Updated Income Type";
				$severity='normal';
				Logger::logUserActivity($activity,$severity);
					$type='info';
					$message="Income type successfully updated.";
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
		switch(Navigation::checkIfAuthorized(280)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to delete income type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$this->loadModel($id)->delete();
			$activity="Deleted Income Type";
			$severity='urgent';
			Logger::logUserActivity($activity,$severity);
			$type='success';
			$message="Income type successfully deleted.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(278)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to view income types.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=new IncomeTypes('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['IncomeTypes'])){
				$model->attributes=$_GET['IncomeTypes'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return IncomeTypes the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=IncomeTypes::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param IncomeTypes $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='income-types-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
