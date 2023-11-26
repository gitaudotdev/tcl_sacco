<?php

class PayrollController extends Controller{
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
				'actions'=>array('admin','view'),
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
    	switch(Navigation::checkIfAuthorized(164)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to view payroll transaction logs.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$this->render('view',array('model'=>$this->loadModel($id)));
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(164)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view payroll transaction logs.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Payroll('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Payroll'])){
				$model->attributes=$_GET['Payroll'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Payroll the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Payroll::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Payroll $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='payroll-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
