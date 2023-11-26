<?php

class StrayRepaymentsController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','pay','commitPayment','savings','commitSavings'),
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
		$model=$this->loadModel($id);
    $element=Yii::app()->user->user_level;
    $array=array('0','1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
		$this->render('view',array('model'=>$model,));
      break;

      case 1:
		CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/index'));
      break;
    }
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
    $element=Yii::app()->user->user_level;
    $array=array('0','1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
			$model=new StrayRepayments;			
			if(isset($_POST['StrayRepayments'])){
				$model->attributes=$_POST['StrayRepayments'];
				if($model->save()){
					$this->redirect(array('view','id'=>$model->id));
				}
			}
			$this->render('create',array('model'=>$model));
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/index'));	
      break;
    }
	}

	public function actionPay($id){
      switch(Navigation::checkIfAuthorized(65)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to move stray repayments.");
			$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model        = $this->loadModel($id);
			$loanaccounts = SMS::getRunningLoans();
			$this->render('pay',array('model'=>$model,'loanaccounts'=>$loanaccounts));
    		break;
    	}
	}

	public function actionCommitPayment(){
		$loanaccount_id = $_POST['loanaccount'];
		$loanaccount    = Loanaccounts::model()->findByPk($loanaccount_id);
		$accountNumber  = $loanaccount->account_number;
		$memberName     = $loanaccount->BorrowerFullName;
		$amount         = $_POST['repayment_amount'];
		$modelID        = $_POST['stray'];
		$model          = $this->loadModel($modelID);
        switch(Navigation::checkIfAuthorized(65)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to pay stray repayments to correct loan account.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			switch(LoanManager::repayLoanAccount($loanaccount_id,$amount,'0',$model->source)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Stray repayment not submitted. Please try again.");
				$this->redirect(array('strayRepayments/pay/'.$modelID));
				break;

				case 1:
				$repayment = StrayRepayments::model()->findByPk($modelID);
				$repayment->is_paid = '1';
				$repayment->save();
				Logger::logUserActivity("Submitted Stray repayment for Account No. :$accountNumber for $memberName",'urgent');
				CommonFunctions::setFlashMessage('success'," Stray Repayment successfully submitted.");
				$this->redirect(array('admin'));
				break;
			}
    		break;
    	}
	}

	public function actionSavings($id){
        switch(Navigation::checkIfAuthorized(65)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to pay stray repayments to correct savings account.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=$this->loadModel($id);
			$savingsaccounts=Savingaccounts::model()->findAll();
			$this->render('savings',array('model'=>$model,'accounts'=>$savingsaccounts));
    		break;
    	}
	}

	public function actionCommitSavings(){
		$savingaccountID=$_POST['savingaccount'];
		$savingaccount=Savingaccounts::model()->findByPk($savingaccountID);
		$accountNumber=$savingaccount->SavingAccountNumber;
		$memberName=$savingaccount->SavingAccountHolderName;
		$amount=$_POST['repayment_amount'];
		$modelID=$_POST['stray'];
		switch(Navigation::checkIfAuthorized(65)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to pay stray repayments to correct saving account.");
			$this->redirect(Yii::app()->request->urlReferrer);
			break;

			case 1:
			$transaction=new Savingtransactions;
			$transaction->savingaccount_id=$savingaccountID;
			$transaction->amount=$amount;
			$transaction->type='credit';
			$transaction->description="Stray Saving Transaction Payment";
			$transaction->transacted_by=Yii::app()->user->user_id;
			$transaction->transacted_at=date('Y-m-d H:i:s');
			if($transaction->save()){
				$repayment=StrayRepayments::model()->findByPk($modelID);
				$repayment->is_paid='1';
				$repayment->save();
				Logger::logUserActivity("Submitted Stray repayment for Account No. :$accountNumber for $memberName",'urgent');
				CommonFunctions::setFlashMessage('success'," Stray Repayment successfully submitted.");
				$this->redirect(array('admin'));
			}else{
				CommonFunctions::setFlashMessage('danger',"Stray repayment not submitted. Please try again.");
				$this->redirect(array('strayRepayments/pay/'.$modelID));
			}
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
    $array=array('0','1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
			$model=$this->loadModel($id);
			if(isset($_POST['StrayRepayments'])){
				$model->attributes=$_POST['StrayRepayments'];
				if($model->save()){
					$this->redirect(array('view','id'=>$model->id));
				}
			}
			$this->render('update',array(
				'model'=>$model,
			));
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/index'));
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
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
			$this->loadModel($id)->delete();
			if(!isset($_GET['ajax'])){
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/index'));
      break;
    }
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
	  switch(Navigation::checkIfAuthorized(66)){
		case 0:
		CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access the stray payments");
		$this->redirect(array('dashboard/default'));
		break;
  
		case 1:
		$model=new StrayRepayments('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StrayRepayments'])){
			$model->attributes=$_GET['StrayRepayments'];
		}
		$this->render('admin',array('model'=>$model));
		break;
	  }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return StrayRepayments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=StrayRepayments::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param StrayRepayments $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='stray-repayments-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
