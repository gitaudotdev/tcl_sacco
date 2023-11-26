<?php

class WithdrawalsController extends Controller{
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
				'actions'=>array('update','view','approve','reject','admin'),
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
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(153)){
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
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(151)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to update.");
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
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(153)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to view withdrawal requests.");
	  	 	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
				$model=new Withdrawals('search');
				$model->unsetAttributes(); 
				if(isset($_GET['Withdrawals'])){
					$model->attributes=$_GET['Withdrawals'];
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
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(151)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to approve withdrawal requests.");
	  	 	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
      	$requestID=$_POST['request'];
      	$reason=$_POST['reason'];
      	$withdrawalStatus=SavingFunctions::approveWithdrawalRequest($requestID,$reason);
      	switch($withdrawalStatus){
					case 0:
					CommonFunctions::setFlashMessage('danger',"Savings withdrawal request was neither approved nor disbursed. Please try again or contact system admin");
					break;

					case 1:
					CommonFunctions::setFlashMessage('success',"Savings withdrawal request approved and disbursed successfully. M-PESA transaction processed successfully.");
					break;

					case 3:
					CommonFunctions::setFlashMessage('danger',"Operation failed. No response received from the M-PESA system.");
					break;

					case 1250:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Error occurred while generating the auth token. Please try again later...");
					break;

					case 2020:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Sorry this action cannot be completed at this time. Contact the system administrator");
					break;

					default:
					CommonFunctions::setFlashMessage('danger',"Operation failed. $withdrawalStatus.");
					break;
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
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(152)){
      	case 0:
				CommonFunctions::setFlashMessage('danger',"Not allowed to reject withdrawal requests.");
	  	 	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
      	$requestID=$_POST['request'];
      	$reason=$_POST['reason'];
				if(SavingFunctions::rejectWithdrawalRequest($requestID,$reason) == 1){
      		CommonFunctions::setFlashMessage('success',"Withdrawal request was rejected successfully.");
				}else{
      		CommonFunctions::setFlashMessage('danger',"Operation failed. Withdrawal request could not be rejected.");
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
	 * @return Withdrawals the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Withdrawals::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Withdrawals $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='withdrawals-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
