<?php

class SavingpostingsController extends Controller{
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
				'actions'=>array('create','update','view','admin','void'),
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
		$this->render('view',array('model'=>$this->loadModel($id)));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
    	switch(Navigation::checkIfAuthorized(188)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to initiate saving interest postings.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
    		$model=new Savingpostings;
				if(isset($_POST['Savingpostings'])){
					$accountID=$_POST['Savingpostings']['savingAccountID'];
					$savingAccount=Savingaccounts::model()->findByPk($accountID);
					$amount=$_POST['Savingpostings']['posted_interest'];
					$type='credit';
					$desc=$_POST['Savingpostings']['description'];
					$transactionID=SavingFunctions::createTransactionRecord($accountID,$amount,$type,$desc);
					switch($transactionID){
						case 0:
						CommonFunctions::setFlashMessage('danger',"Saving Interest transaction posting failed.");
						break;

						default:
						SavingFunctions::createTransactionPosting($transactionID,$amount,$type);
						$fullName=Profiles::model()->findByPk($savingAccount->user_id)->ProfileFullName;
						$postedAmount=CommonFunctions::asMoney($amount);
						$phoneNumber=$savingAccount->account_number;
						Logger::logUserActivity("Initiated saving interest transaction posting of $postedAmount as posted interest for $fullName to account number: $phoneNumber",'urgent');
						CommonFunctions::setFlashMessage('success',"Saving Interest transaction posted sucessfully.");
						break;
					}
			  	$this->redirect(array('admin'));
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
    	switch(Navigation::checkIfAuthorized(189)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to initiate saving interest postings.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=$this->loadModel($id);
				switch($model->is_void){
					case '0':
					$initialAmount=CommonFunctions::asMoney($model->posted_interest);
					if(isset($_POST['Savingpostings'])){
						$model->attributes=$_POST['Savingpostings'];
						if($model->save()){
							$transaction=Savingtransactions::model()->findByPk($model->savingtransaction_id);
							$transaction->amount=$model->posted_interest;
							if($transaction->save()){
								$accountID=$transaction->savingaccount_id;
								$savingAccount=Savingaccounts::model()->findByPk($accountID);
								$fullName=Profiles::model()->findByPk($savingAccount->user_id)->ProfileFullName;
								$postedAmount=CommonFunctions::asMoney($transaction->amount);
								$phoneNumber=$savingAccount->account_number;
								Logger::logUserActivity("Updated saving interest transaction posting from $initialAmount to $postedAmount as posted interest for $fullName to account number: $phoneNumber",'urgent');
								CommonFunctions::setFlashMessage('success',"Saving Interest transaction updated sucessfully.");
							}else{
								CommonFunctions::setFlashMessage('danger',"Updating saving transaction posting failed.");
							}
						}
				  	$this->redirect(array('admin'));
					}
					break;

					case '1':
					CommonFunctions::setFlashMessage('danger',"Updating saving transaction posting failed. A voided transaction cannot be updated.");
					$this->redirect(array('admin'));
					break;
				}
				$this->render('update',array('model'=>$model));
    		break;
    	}
	}

	public function actionVoid($id){
    	switch(Navigation::checkIfAuthorized(191)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to void saving interest postings.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=$this->loadModel($id);
				$initialAmount=CommonFunctions::asMoney($model->posted_interest);
				$model->is_void='1';
				if($model->save()){
					SavingFunctions::voidSavingTransaction($model->savingtransaction_id);
					$transaction=Savingtransactions::model()->findByPk($model->savingtransaction_id);
					$accountID=$transaction->savingaccount_id;
					$savingAccount=Savingaccounts::model()->findByPk($accountID);
					$fullName=Profiles::model()->findByPk($savingAccount->user_id)->ProfileFullName;
					$postedAmount=CommonFunctions::asMoney($transaction->amount);
					$phoneNumber=$savingAccount->account_number;
					Logger::logUserActivity("Voided saving interest transaction posting of $initialAmount for $fullName of account number: $phoneNumber",'urgent');
					CommonFunctions::setFlashMessage('success',"Saving Interest transaction voided sucessfully.");
				}
		  	$this->redirect(array('admin'));
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
      switch(Navigation::checkIfAuthorized(190)){
      	case 0:
		CommonFunctions::setFlashMessage('danger',"Not allowed to view saving interest posting details.");
		$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
		$model=new Savingpostings('search');
		$model->unsetAttributes();
		if(isset($_GET['Savingpostings'])){
			$model->attributes=$_GET['Savingpostings'];
		}
		$this->render('admin',array('model'=>$model));
      	break;
      }
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Savingpostings the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Savingpostings::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Savingpostings $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='savingpostings-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
