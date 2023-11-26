<?php

class ChamaLocationsController extends Controller{
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
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions'=>array('create','update','admin'),
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
		switch(Navigation::checkIfAuthorized(290)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create location");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:	
				$model = new ChamaLocations;
				if(isset($_POST['ChamaLocations'])){
					$location = $_POST['ChamaLocations']['name'];
					$town     = $_POST['ChamaLocations']['town'];
					switch(Chama::checkIfDuplicateChamaLocation($location,$town)){
						case 1000:
						CommonFunctions::setFlashMessage('danger',"A location with the same details exists. Try again with different details.");
						$this->render('create',array('model'=>$model));
						break;

						case 1001:
							$model->attributes = $_POST['ChamaLocations'];
							$model->created_by = Yii::app()->user->user_id;
							$model->created_at = date("Y-m-d H:i:s");
							if($model->save()){
								$locationName = $model->name;
								Logger::logUserActivity("Added Chama Location: $locationName",'normal');
								CommonFunctions::setFlashMessage('success',"Location successfully created.");
								$this->redirect(array('admin'));
							}
						break;
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
		switch(Navigation::checkIfAuthorized(291)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update location.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model = $this->loadModel($id);
				if(isset($_POST['ChamaLocations'])){
					$model->attributes = $_POST['ChamaLocations'];
					if($model->save()){
						$locationName = $model->name;
						Logger::logUserActivity("Updated chama location : $locationName",'normal');
						CommonFunctions::setFlashMessage('success',"Location details updated.");
						$this->redirect(array('admin'));
					}
				}
				$this->render('update',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(292)){
			case 0:
				CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
				$this->redirect(array('dashboard/default'));
			break;

			case 1:
				$model = new ChamaLocations('search');
				$model->unsetAttributes();
				if(isset($_GET['ChamaLocations'])){
					$model->attributes = $_GET['ChamaLocations'];
				}
				$this->render('admin',array('model'=>$model));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ChamaLocations the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model = ChamaLocations::model()->findByPk($id);
		if($model === null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param ChamaLocations $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'chama-locations-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
