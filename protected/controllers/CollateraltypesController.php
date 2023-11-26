<?php

class CollateraltypesController extends Controller
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
		switch(Navigation::checkIfAuthorized(269)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to create collateral type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=new Collateraltypes;
			if(isset($_POST['Collateraltypes'])){
				$status=CollateralFunctions::createCollateralType($_POST);
				switch($status){
					case 0:
					$type='danger';
					$message="Type not created. Try again later.";
					break;

					case 1:
					$activity="Added Collateral Type";
					$severity='normal';
					Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Type successfully created.";
					break;

					case 2:
					$type='warning';
					$message="Type not created. A type with the same name exists.";
					break;
				}
				CommonFunctions::setFlashMessage($type,$message);
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
		switch(Navigation::checkIfAuthorized(271)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to update collateral type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Collateraltypes'])){
				$model->attributes=$_POST['Collateraltypes'];
				if($model->save()){
					$activity="Updated Collateral Type";
				$severity='normal';
				Logger::logUserActivity($activity,$severity);
					$type='info';
					$message="Type successfully updated.";
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
		switch(Navigation::checkIfAuthorized(272)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to delete collateral type.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$this->loadModel($id)->delete();
			$activity="Deleted Loan Collateral Type";
			$severity='urgent';
			Logger::logUserActivity($activity,$severity);
			$type='success';
			$message="Collateral type successfully deleted.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(270)){
			case 0:
			$type='danger';
			$message="Restricted Area. You are not allowed to view collateral types.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('dashboard/default'));
			break;
	
			case 1:
			$model=new Collateraltypes('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Collateraltypes'])){
				$model->attributes=$_GET['Collateraltypes'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Collateraltypes the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Collateraltypes::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Collateraltypes $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='collateraltypes-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
