<?php

class LogsController extends Controller{
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','updateDetails'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
      switch(Navigation::checkIfAuthorized(121)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view audit trail.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=new Logs('search');
			$model->unsetAttributes();
			if(isset($_GET['Logs'])){
				$model->attributes=$_GET['Logs'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}

	public function actionUpdateDetails(){
		$element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$logs=Logs::model()->findAll();
			foreach($logs AS $log){
				if(!empty($log)){
					$user=Profiles::model()->findByPk($log->user_id);
					if(!empty($user)){
						$log->branch_id=$user->branchId;
						$log->save();
						echo "Log Updated";
					}else{
						echo "No User Found";
					}
				}else{
					echo "No Log Found";
				}
			}
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 		$this->redirect(array('dashboard/default'));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Logs the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Logs::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Logs $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='logs-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
