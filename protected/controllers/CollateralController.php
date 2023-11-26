<?php

class CollateralController extends Controller
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
			array('allow',
				'actions'=>array('admin','delete','create','update','collateralReport'),
				'users'=>array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
    	switch(Navigation::checkIfAuthorized(139)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create collateral.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=new Collateral;
			if(isset($_POST['Collateral'])){
				$model->attributes=$_POST['Collateral'];
				$model->uploaded_by=Yii::app()->user->user_id;
				if($model->save()){
					$activity="Added Loan Account Collateral";
					$severity='high';
					Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Collateral successfully created.";
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
    	switch(Navigation::checkIfAuthorized(140)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update collateral details.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=$this->loadModel($id);
				if(isset($_POST['Collateral'])){
					$model->attributes=$_POST['Collateral'];
					if($model->save()){
						$type='info';
						$message="Collateral successfully updated.";
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
    	switch(Navigation::checkIfAuthorized(142)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete collateral.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->loadModel($id)->delete();
			$activity="Deleted Loan Collateral";
			$severity='urgent';
			Logger::logUserActivity($activity,$severity);
			$type='success';
			$message="Collateral successfully deleted.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(141)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view collateral.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=new Collateral('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Collateral'])){
				$model->attributes=$_GET['Collateral'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}

	public function actionCollateralReport(){
		switch(Navigation::checkIfAuthorized(194)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view collateral report.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Collateral('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Collateral'])){
				$model->attributes=$_GET['Collateral'];
				if(isset($_GET['export'])){
					$dataProvider = $model->search();
					$dataProvider->pagination = False;
					$excelWriter = ExportFunctions::getExcelCollateralRegisterReport($dataProvider->data);
					echo $excelWriter->save('php://output');
				}
			}
			$this->render('collateralReport',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Collateral the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Collateral::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Collateral $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='collateral-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
