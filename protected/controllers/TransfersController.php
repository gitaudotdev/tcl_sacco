<?php

class TransfersController extends Controller{
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
			array('allow',
				'actions'=>array('view','update','admin','approve','reject'),
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
		$element=Yii::app()->user->user_level;
    $array=array('2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(154)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to view request details.");
	  	 	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
				$this->render('view',array('model'=>$this->loadModel($id)));
      	break;
      }
      break;


      case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
      break;
    }
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
		$element=Yii::app()->user->user_level;
    $array=array('1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(155)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to update requests.");
	  	 	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
				$model=$this->loadModel($id);
				if($model->is_approved === '0'){
					if(isset($_POST['Withdrawals'])){
						$model->attributes=$_POST['Withdrawals'];
						if($model->save()){
							$amount=$model->amount;
							$amountFormatted=CommonFunctions::asMoney($amount);
							$accountID=$model->savingaccount_id;
							$savingAccount=Savingaccounts::model()->findByPk($accountID);
							$accountHolder=$savingAccount->SavingAccountHolderName;
						 	Logger::logUserActivity("Updated withdrawal request of Ksh. $amountFormatted for $accountHolder",'high');
							CommonFunctions::setFlashMessage('info',"Request updated successfully.");
							$this->redirect(array('admin'));
						}
					}
					$this->render('update',array('model'=>$model));
				}else{
					CommonFunctions::setFlashMessage('danger',"Request cannot be updated since it has either been approved or rejected.");
      		$this->redirect(Yii::app()->request->urlReferrer);
				}
      	break;
      }
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
		$element=Yii::app()->user->user_level;
    $array=array('2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(154)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to view transfer requests.");
	  	 	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
				$model=new Transfers('search');
				$model->unsetAttributes(); 
				if(isset($_GET['Transfers'])){
					$model->attributes=$_GET['Transfers'];
				}
				$this->render('admin',array('model'=>$model));
      	break;
      }
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
      break;
    }
	}

	public function actionApprove(){
		$element=Yii::app()->user->user_level;
    $array=array('2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(156)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to approve transfer requests.");
	  	 	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
      	$model=$this->loadModel($_POST['request']);
      	$model->authorization_reason=$_POST['reason'];
      	$model->is_approved='1';
      	$model->date_authorized=date('Y-m-d');
      	if($model->save()){
      		$accountID=$model->savingaccount_id;
	      	$amount=$model->amount;
	      	$loanAccountID=$model->loanaccount_id;
	      	$savingAccount=Savingaccounts::model()->findByPk($accountID);
	      	if($savingAccount->is_approved =='1'){
		      	if($loanAccountID > 0){
			      	switch(SavingFunctions::transferFundsToLoanAccount($accountID,$loanAccountID,$amount)){
			      		case 0:
			      		CommonFunctions::setFlashMessage('danger',"Operation failed. Funds could not be transferred.");
			      		break;

			      		case 1:
			      		CommonFunctions::setFlashMessage('success',"Funds transferred successfully.");
			      		break;

			      		case 2:
			      		CommonFunctions::setFlashMessage('danger',"Operation failed. Insufficient savings account balance.");
			      		break;

			      		case 3:
			      		CommonFunctions::setFlashMessage('danger',"Operation failed. Repayment of loan failed.");
			      		break;
			      	}
		      	}else{
			      	CommonFunctions::setFlashMessage('danger',"Operation failed. No active loan account associated with the savings account holder.");
		      	}
	      	}else{
			      CommonFunctions::setFlashMessage('danger',"Failed Operation. Account not approved to transact.");
	      	}
      	}else{
      		CommonFunctions::setFlashMessage('danger',"Failed to approve transfer request.");
      	}
      	$this->redirect(Yii::app()->request->urlReferrer);
      	break;
      }
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
      break;
    }
	}

	public function actionReject(){
		$element=Yii::app()->user->user_level;
    $array=array('2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(157)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to reject transfer requests.");
	  	 	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
      	$model=$this->loadModel($_POST['request']);
      	$model->authorization_reason=$_POST['reason'];
      	$model->is_approved='2';
      	$model->date_authorized=date('Y-m-d');
      	if($model->save()){
      		CommonFunctions::setFlashMessage('success',"Request rejected successfully.");
      	}else{
      		CommonFunctions::setFlashMessage('danger',"Failed to reject transfer request.");
      	}
      	$this->redirect(Yii::app()->request->urlReferrer);
      	break;
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
	 * @return Transfers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Transfers::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Transfers $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='transfers-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
