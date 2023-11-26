<?php

class NoticesController extends Controller{
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
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','activate','deactivate'),
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
		switch(Navigation::checkIfAuthorized(129)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to publish notices.");
	  		$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model=new Notices;
			if(isset($_POST['Notices'])){
				$model->attributes=$_POST['Notices'];
				$model->created_by=Yii::app()->user->user_id;
				if($model->save()){
					Logger::logUserActivity("Published System Notice",'high');
					$type='success';
					$message="Notice successfully published.";
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
		switch(Navigation::checkIfAuthorized(130)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update notices.");
	  		$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Notices'])){
				$model->attributes=$_POST['Notices'];
				if($model->save()){
					Logger::logUserActivity("Updated System Notice",'high');
					$type='info';
					$message="Notice successfully updated.";
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('admin'));
				}
			}
			$this->render('update',array('model'=>$model));
			break;
		}
	}

	public function actionActivate($id){
		switch(Navigation::checkIfAuthorized(131)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to activate notices.");
	  		$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model=$this->loadModel($id);
			if(!empty($model)){
				$model->is_active='1';
				if($model->save()){
					Logger::logUserActivity("Activated System Notice",'high');
					$type='info';
					$message="Notice successfully activated.";
				}else{
					$type='warning';
					$message="Notice activation failed.";
				}
			}else{
				$type='warning';
				$message="No Notice to activate.";
			}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;
		}
	}

	public function actionDeactivate($id){
		switch(Navigation::checkIfAuthorized(132)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to deactivate notices.");
	  		$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model=$this->loadModel($id);
			if(!empty($model)){
				$model->is_active='0';
				if($model->save()){
					Logger::logUserActivity("Deactivated System Notice",'high');
					$type='success';
					$message="Notice successfully deactivated.";
				}else{
					$type='warning';
					$message="Notice deactivation failed.";
				}
			}else{
				$type='warning';
				$message="No Notice to deactivate.";
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
		switch(Navigation::checkIfAuthorized(128)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view notices.");
	  		$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model=new Notices('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Notices'])){
				$model->attributes=$_GET['Notices'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}

	public function actionDelete($id){
		switch(Navigation::checkIfAuthorized(167)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete notices.");
	  		$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted System Notice",'high');
			$type='success';
			$message="Notice successfully deleted.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
			break;
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Notices the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Notices::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Notices $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='notices-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
