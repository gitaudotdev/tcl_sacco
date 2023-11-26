<?php

class ExpenseTypesController extends Controller
{
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
		switch(Navigation::checkIfAuthorized(262)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to view expense type.";
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
		switch(Navigation::checkIfAuthorized(261)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to create expense type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=new ExpenseTypes;
			if(isset($_POST['ExpenseTypes'])){
				$model->attributes=$_POST['ExpenseTypes'];
				$model->created_by=Yii::app()->user->user_id;
				if($model->save()){
					$activity="Added Expense Type";
				$severity='normal';
				Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Expense type successfully created.";
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
		switch(Navigation::checkIfAuthorized(263)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to update expense type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['ExpenseTypes'])){
				$model->attributes=$_POST['ExpenseTypes'];
				if($model->save()){
					$activity="Updated Expense Type";
					$severity='normal';
					Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Expense type successfully updated.";
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
		switch(Navigation::checkIfAuthorized(264)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to delete expense type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$this->loadModel($id)->delete();
			$activity="Deleted Expense Type";
			$severity='urgent';
			Logger::logUserActivity($activity,$severity);
			$type='success';
			$message="Expense type successfully deleted.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(262)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to view expense types.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=new ExpenseTypes('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['ExpenseTypes'])){
				$model->attributes=$_GET['ExpenseTypes'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ExpenseTypes the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=ExpenseTypes::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ExpenseTypes $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='expense-types-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
