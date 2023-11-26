<?php

class ChamaOrganizationsController extends Controller{
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
		switch(Navigation::checkIfAuthorized(293)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create organization");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:	
				$model = new ChamaOrganizations;
				if(isset($_POST['ChamaOrganizations'])){
					$chamaOrganization = $_POST['ChamaOrganizations']['name'];
					switch(Chama::checkIfDuplicateChamaOrganization($chamaOrganization)){
						case 1000:
						CommonFunctions::setFlashMessage('danger',"An organization with the same details exists. Try again with different details.");
						$this->render('create',array('model'=>$model));
						break;

						case 1001:
							$model->attributes = $_POST['ChamaOrganizations'];
							$model->created_by = Yii::app()->user->user_id;
							$model->created_at = date("Y-m-d H:i:s");
							if($model->save()){
								$organizationName = $model->name;
								Logger::logUserActivity("Added Chama Organization: $organizationName",'normal');
								CommonFunctions::setFlashMessage('success',"Organization successfully created.");
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
		switch(Navigation::checkIfAuthorized(294)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update organization.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model = $this->loadModel($id);
				if(isset($_POST['ChamaOrganizations'])){
					$model->attributes = $_POST['ChamaOrganizations'];
					if($model->save()){
						$locationName = $model->name;
						Logger::logUserActivity("Updated chama organization : $locationName",'normal');
						CommonFunctions::setFlashMessage('success',"Organization details updated.");
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
		switch(Navigation::checkIfAuthorized(295)){
			case 0:
				CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
				$this->redirect(array('dashboard/default'));
			break;

			case 1:
				$model = new ChamaOrganizations('search');
				$model->unsetAttributes();
				if(isset($_GET['ChamaOrganizations'])){
					$model->attributes = $_GET['ChamaOrganizations'];
				}
				$this->render('admin',array('model'=>$model));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ChamaOrganizations the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=ChamaOrganizations::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param ChamaOrganizations $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='chama-organizations-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
