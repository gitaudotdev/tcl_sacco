<?php

class AccountSettingsController extends Controller{
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
				'actions'=>array('update'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
		$model = $this->loadModel($id);
		$priorValue = $model->configValue;
		if(isset($_POST['AccountSettings'])){
			$model->attributes = $_POST['AccountSettings'];
			if($model->save()){
				$updatedValue = $model->configValue;
				$profile      = Profiles::model()->findByPk($model->profileId);
				$firstName    = $profile->firstName;
				$phoneNumber  = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
				if($updatedValue != $priorValue){
					switch($model->configType){
						case 'LOAN_LIMIT':
						$maxLimit    = CommonFunctions::asMoney($updatedValue);
						$textMessage = "Dear $firstName, your new loan limit is $maxLimit/-.Thank you!";
						$numbers     = array();
						array_push($numbers,$phoneNumber);
						SMS::broadcastSMS($numbers,$textMessage,'37',$profile->id);
						break;

						case 'LOAN_INTEREST_RATE':
							$textMessage = "Dear $firstName, your new loan rate is $updatedValue%.Thank you!";
							$numbers     = array();
							array_push($numbers,$phoneNumber);
							SMS::broadcastSMS($numbers,$textMessage,'38',$profile->id);
						break;

						case 'SAVINGS_INTEREST_RATE':
							$textMessage = "Dear $firstName, your new savings rate is $updatedValue%.Thank you!";
							$numbers     = array();
							array_push($numbers,$phoneNumber);
							SMS::broadcastSMS($numbers,$textMessage,'39',$profile->id);
						break;
					}
				}
				$this->redirect(array('profiles/view','id'=>$model->profileId));
			}
		}
		$this->render('update',array('model'=>$model));
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AccountSettings the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=AccountSettings::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param AccountSettings $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='account-settings-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
