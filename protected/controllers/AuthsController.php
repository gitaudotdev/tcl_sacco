<?php

class AuthsController extends Controller{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/templates/pages';
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
				'actions'=>array('update','updateAuths','admin'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionUpdateAuths(){
		$auths = Auths::model()->findAll();
		foreach($auths AS $auth){
			$profile = Profiles::model()->findByPk($auth->profileId);
			if(!empty($profile)){
				$auth->branchId  = $profile->branchId;
				$auth->managerId = $profile->managerId;
				$auth->update();
			}
		}
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
    	switch(Navigation::checkIfAuthorized(299)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Authorizations.");
			$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('view',array('model'=>$this->loadModel($id)));
			break;
		}
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
    	switch(Navigation::checkIfAuthorized(300)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update Authorizations.");
			$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model = $this->loadModel($id);
			$fullName = $model->AuthProfile->ProfileFullName;
			$currentAuth = $model->level;
			if(isset($_POST['Auths'])){
				$updatedAuth = $_POST['Auths']['level'];
				if($currentAuth === $updatedAuth){
					CommonFunctions::setFlashMessage('danger',"Operation Failed! Authorization level cannot be updated to the same value.");
				}else{
					$model->attributes = $_POST['Auths'];
					if($model->save()){
						CommonFunctions::setFlashMessage('success',"Authorization level updated sucessfully.");
						Logger::logUserActivity("Updated Authorization Level for : $fullName from $currentAuth to $updatedAuth",'normal');
					}
				}
				$this->redirect(array('admin'));
			}
			$this->render('update',array('model'=>$model));
			break;
		}
	}

	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(299)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Authorizations.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model = new Auths('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Auths'])){
				$model->attributes = $_GET['Auths'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Auths the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model = Auths::model()->findByPk($id);
		if($model === null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Auths $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='auths-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
