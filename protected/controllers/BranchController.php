<?php

class BranchController extends Controller{
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
			'accessControl', // perform access control for CRUD operations
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
				'actions'=>array('create','update','admin','delete','merge'),
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
    	switch(Navigation::checkIfAuthorized(1)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create Branch");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=new Branch;
			if(isset($_POST['Branch'])){
				$model->attributes=$_POST['Branch'];
				$model->created_by=Yii::app()->user->user_id;
				if($model->save()){
					$branchName=$model->name;
					Logger::logUserActivity("Added Branch: $branchName",'normal');
					CommonFunctions::setFlashMessage('success',"Branch successfully created.");
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
    	switch(Navigation::checkIfAuthorized(2)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update Branch.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Branch'])){
				$model->attributes=$_POST['Branch'];
				if($model->save()){
					$branchName=$model->name;
					Logger::logUserActivity("Updated Branch : $branchName",'normal');
					CommonFunctions::setFlashMessage('info',"Branch details updated.");
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
    	switch(Navigation::checkIfAuthorized(3)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete Branch.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted SACCO Branch: $branchName",'urgent');
			CommonFunctions::setFlashMessage('success',"Branch successfully deleted.");
			$this->redirect(array('admin'));
    		break;
    	}
	}

	public function actionMerge(){
    	switch(Navigation::checkIfAuthorized(4)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to Merge Branches.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
	    	switch(SaccoBranch::mergeBranches($_POST)){
	    		case 0:
				$type='danger';
				$message="Merging Failed!! Branches could not be merged.";
	    		break;

	    		case 1:
		   		Logger::logUserActivity("Merged SACCO branches",'urgent');
				$type='success';
				$message="Branches successfully merged.";
	    		break;

	    		case 2:
				$type='warning';
				$message="Merging Failed!! A branch exists with the same name. Try again with a unique name.";
	    		break;
	    	}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		$element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$model=new Branch('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Branch'])){
				$model->attributes=$_GET['Branch'];
			}
			$this->render('admin',array(
				'model'=>$model,
			));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Branch the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Branch::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Branch $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='branch-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}