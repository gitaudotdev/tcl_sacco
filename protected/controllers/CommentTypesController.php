<?php

class CommentTypesController extends Controller{

	public $layout='//layouts/templates/pages';

	public function filters(){
		return array(
			'accessControl', 
		);
	}
	
	public function accessRules(){
		return array(
			array('allow',
				'actions'=>array('create','update','admin','activate','deactivate'),
				'users'=>array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
	
	public function actionCreate(){
    	switch(Navigation::checkIfAuthorized(252)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create comment types.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model = new CommentTypes;
			if(isset($_POST['CommentTypes'])){
				$model->attributes = $_POST['CommentTypes'];
				$model->user_id    = Yii::app()->user->user_id;
				if($model->save()){
					$commentName = $model->name;
					Logger::logUserActivity("Added Comment Type Record : $commentName",'normal');
					CommonFunctions::setFlashMessage('success',"Comment type successfully created.");
					$this->redirect(array('admin'));
				}
			}
			$this->render('create',array('model'=>$model));
    		break;
    	}
	}
	
	public function actionUpdate($id){
    	switch(Navigation::checkIfAuthorized(253)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update comment types.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=$this->loadModel($id);
			if(isset($_POST['CommentTypes'])){
				$model->attributes=$_POST['CommentTypes'];
				if($model->save()){
					$commentName = $model->name;
					Logger::logUserActivity("Updated Comment Type Record : $commentName",'normal');
					CommonFunctions::setFlashMessage('success',"Comment type successfully updated.");
					$this->redirect(array('admin'));
				}
			}
			$this->render('update',array('model'=>$model));
    		break;
    	}
	}
	
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(251)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to manage comment types.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=new CommentTypes('search');
			$model->unsetAttributes(); 
			if(isset($_GET['CommentTypes'])){
				$model->attributes=$_GET['CommentTypes'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}

	public function actionActivate($id){
    	switch(Navigation::checkIfAuthorized(254)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to activate comment types.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=$this->loadModel($id);
			$commentName = $model->name;
			$model->is_active='1';
			if($model->save()){
			Logger::logUserActivity("Activated Comment Type Record : $commentName",'high');
				CommonFunctions::setFlashMessage('success',"Comment type successfully activated.");
			}else{
				CommonFunctions::setFlashMessage('danger',"Failed to activate the comment type.");
			}
			$this->redirect(array('admin'));
    		break;
    	}
	}

	public function actionDeactivate($id){
    	switch(Navigation::checkIfAuthorized(255)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to deactivate comment types.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=$this->loadModel($id);
			$commentName = $model->name;
			$model->is_active='0';
			if($model->save()){
				Logger::logUserActivity("Deactivated Comment Type Record : $commentName",'high');
				CommonFunctions::setFlashMessage('success',"Comment type successfully deactivated.");
			}else{
				CommonFunctions::setFlashMessage('danger',"Failed to deactivate the comment type.");
			}
			$this->redirect(array('admin'));
    		break;
    	}
	}
	
	public function loadModel($id){
		$model=CommentTypes::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-types-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}