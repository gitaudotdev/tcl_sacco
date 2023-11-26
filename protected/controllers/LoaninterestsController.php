<?php

class LoaninterestsController extends Controller
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
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'actions'=>array('create','update','bulkUpdates'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','void'),
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
		switch(Navigation::checkIfAuthorized(126)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
		  	$this->redirect(array('dashboard/index'));
			break;

			case 1:
			$model=new Loaninterests;
			if(isset($_POST['Loaninterests'])){
				$model->attributes=$_POST['Loaninterests'];
				$model->accrued_at=date('Y-m-d H:i:s');
				if($model->save()){
					$interestAmount= $model->interest_accrued;
    				$loanaccount   = Loanaccounts::model()->findByPk($_POST['Loaninterests']['loanaccount_id']);
					ProfileEngine::updateCommonDetails($model,$loanaccount->user_id);
					$accountNumber = $loanaccount->account_number;
      				$fullName      = $loanaccount->BorrowerFullName;
					Logger::logUserActivity("Created Accrued Interest:$interestAmount,Account:$accountNumber,Client:$fullName",'urgent');
					CommonFunctions::setFlashMessage('success',"Accrued Interest successfully created.");
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
		switch(Navigation::checkIfAuthorized(123)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
		  	$this->redirect(array('dashboard/index'));
			break;

			case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Loaninterests'])){
				$model->attributes=$_POST['Loaninterests'];
				if($model->save()){
					$interestAmount=$model->interest_accrued;
    				$loanaccount=Loanaccounts::model()->findByPk($model->loanaccount_id);
					ProfileEngine::updateCommonDetails($model,$loanaccount->user_id);
					$accountNumber=$loanaccount->account_number;
      				$fullName=$loanaccount->BorrowerFullName;
					Logger::logUserActivity("Updated Accrued Interest:$interestAmount,Account:$accountNumber,Client:$fullName",'urgent');
					CommonFunctions::setFlashMessage('info',"Accrued Interest successfully updated.");
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
		switch(Navigation::checkIfAuthorized(125)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
		  	$this->redirect(array('dashboard/index'));
			break;

			case 1:
			$model=new Loaninterests('search');
			$model->unsetAttributes();
			if(isset($_GET['Loaninterests'])){
				$model->attributes=$_GET['Loaninterests'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}

	public function actionVoid($id){
		switch(Navigation::checkIfAuthorized(124)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
			$redirection=array('dashboard/index');
			break;

			case 1:
			switch(LoanManager::voidInterest($id)){
				case 0:
				CommonFunctions::setFlashMessage('warning',"Voiding accrued interest failed.");
				break;

				case 1:
				CommonFunctions::setFlashMessage('info',"Accrued interest successfully voided.");
				break;
			}
			$redirection=array('admin');
			break;
		}
		$this->redirect($redirection);
	}

	public function actionBulkUpdates(){
		$interests  = Loaninterests::model()->findAll();
		foreach($interests AS $interest){
			$loanaccount   = Loanaccounts::model()->findByPk($interest->loanaccount_id);
			ProfileEngine::updateCommonDetails($interest,$loanaccount->user_id);
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Loaninterests the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Loaninterests::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Loaninterests $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='loaninterests-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
