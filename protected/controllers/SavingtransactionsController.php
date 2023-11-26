<?php

class SavingtransactionsController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','void','bulkUpdates'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','bulk','commitBulk'),
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
    	switch(Navigation::checkIfAuthorized(59)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view saving account transactions.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('view',array('model'=>$this->loadModel($id)));
    		break;
    	}
	}

	public function actionVoid($id){
    	switch(Navigation::checkIfAuthorized(60)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to void saving account transactions.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
	    	$model=$this->loadModel($id);
	    	if($model->type=='debit'){
	    		CommonFunctions::setFlashMessage('danger',"Voiding of amounts withdrawn is forbidden.");
	    	}else{
		    	$model->is_void='1';
		    	$model->save();
		    	//Check if Transaction is in Posting
		    	SavingFunctions::voidaccruedInterest($id);
				$accountHolder=$model->SavingAccountHolderName;
				$amountVoided=$model->amount;
				Logger::logUserActivity("Voided Saving Transaction  worth $amountVoided for $accountHolder",'high');
				CommonFunctions::setFlashMessage('success',"Transaction successfully voided.");
	    	}
			$this->redirect(array('admin'));
    		break;
    	}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
    	switch(Navigation::checkIfAuthorized(61)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create saving account transactions.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=new Savingtransactions;
				if(isset($_POST['Savingtransactions'])){
					$model->attributes=$_POST['Savingtransactions'];
					$model->transacted_by=Yii::app()->user->user_id;
					if($model->save()){
						$savingaccount = Savingaccounts::model()->findByPk($model->savingaccount_id);
						ProfileEngine::updateCommonDetails($model,$savingaccount->user_id);
						$accountHolder=$model->SavingAccountHolderName;
						Logger::logUserActivity("Added Saving Transaction for $accountHolder",'high');
						CommonFunctions::setFlashMessage('success',"Transaction successfully recorded.");
						$this->redirect(array('admin'));
					}
				}
				$this->render('create',array('model'=>$model,));
    		break;
    	}
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
    	switch(Navigation::checkIfAuthorized(62)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update saving account transactions.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Savingtransactions'])){
				$model->attributes=$_POST['Savingtransactions'];
				if($model->save()){
					$savingaccount = Savingaccounts::model()->findByPk($model->savingaccount_id);
					ProfileEngine::updateCommonDetails($model,$savingaccount->user_id);
					$accountHolder=$model->SavingAccountHolderName;
					Logger::logUserActivity("Updated Saving Transaction for $accountHolder",'high');
					CommonFunctions::setFlashMessage('info',"Transaction successfully updated.");
					$this->redirect(array('savingaccounts/'.$model->savingaccount_id));
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
		$element=Yii::app()->user->user_level;
		$array=array('0','1','2','3','4');
		$model=$this->loadModel($id);
		$accountHolder=$model->SavingAccountHolderName;
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted Saving Transaction for $accountHolder",'urgent');
			CommonFunctions::setFlashMessage('success',"Saving Transaction successfully deleted.");
			$this->redirect(array('admin'));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(59)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view saving transactions.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model = new Savingtransactions('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Savingtransactions'])){
				$model->attributes=$_GET['Savingtransactions'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}

	public function actionBulk(){
	    $element=Yii::app()->user->user_level;
		$array=array('1','2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$savingproducts=Savingproducts::model()->findAll();
			$this->render('bulk',array('savingproducts'=>$savingproducts));
			break;

			case 1:
				CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionCommitBulk(){
	  $element=Yii::app()->user->user_level;
    $array=array('1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
		  $_POST['transacted_by']=Yii::app()->user->user_id;
			$status=SavingFunctions::recordBulkTransactions($_POST);
			switch($status){
				case 0:
				$type='danger';
				$message="Kindly select a savings account.";
				break;

				case 1:
	    	    Logger::logUserActivity("Added Bulk Saving Transactions",'high');
				$type='success';
				$message="Transaction successfully record.";
				break;
			}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
    	break;

    	case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionBulkUpdates(){
		$transactions  = Savingtransactions::model()->findAll();
		foreach($transactions AS $transaction){
			$savingaccount  = Savingaccounts::model()->findByPk($transaction->savingaccount_id);
			if(!empty($savingaccount)){
				ProfileEngine::updateCommonDetails($transaction,$savingaccount->user_id);
			}
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Savingtransactions the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Savingtransactions::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Savingtransactions $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='savingtransactions-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
