<?php

class AssetsController extends Controller{
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
				'actions'=>array('create','update','view','assetsReport'),
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
    	switch(Navigation::checkIfAuthorized(145)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view asset details.");
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
    	switch(Navigation::checkIfAuthorized(143)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create any assets.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=new Assets;
				if(isset($_POST['Assets'])){
					$model->attributes=$_POST['Assets'];
					$model->created_by=Yii::app()->user->user_id;
					if($model->save()){
						Logger::logUserActivity("Added Asset Record",'normal');
						CommonFunctions::setFlashMessage('success',"Asset successfully created.");
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
    	switch(Navigation::checkIfAuthorized(144)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update any assets.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Assets'])){
				$model->attributes=$_POST['Assets'];
				if($model->save()){
					Logger::logUserActivity("Updated Asset Record",'normal');
					CommonFunctions::setFlashMessage('info',"Asset type successfully updated.");
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
    	switch(Navigation::checkIfAuthorized(146)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete any assets.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted Asset",'urgent');
			CommonFunctions::setFlashMessage('success',"Asset successfully deleted.");
			$this->redirect(array('admin'));
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(145)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view assets.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=new Assets('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Assets'])){
				$model->attributes=$_GET['Assets'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}

	public function actionAssetsReport(){
		switch(Navigation::checkIfAuthorized(194)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view assets report.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Assets('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Assets'])){
				$model->attributes=$_GET['Assets'];
				if(isset($_GET['export'])){
					$dataProvider = $model->search();
					$dataProvider->pagination = False;
					$excelWriter = ExportFunctions::getExcelAssetsReport($dataProvider->data);
					echo $excelWriter->save('php://output');
				}
			}
			$this->render('assetsReport',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Assets the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Assets::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Assets $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='assets-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
