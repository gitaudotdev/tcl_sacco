<?php

class FixedPaymentsController extends Controller{

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
	 * ALLOWED ACTIONS
	 */
	public function accessRules(){
		return array(
			array('allow',
				'actions'=>array('approve','reject','disburse','cancel'),
				'users'=>array('@'),
			),
			array('allow', 
				'actions'=>array('create','update','view','admin'),
				'users'=>array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
	/**
	 * VIEW FIXED PAYMENT
	 */
	public function actionView($id){
    	switch(Navigation::checkIfAuthorized(246)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to view fixed expense payments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$this->render('view',array('model'=>$this->loadModel($id)));
    		break;
    	}
	}
	/**
	* INITIATE FIXED PAYMENT
	*/
	public function actionCreate(){
    	switch(Navigation::checkIfAuthorized(244)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to initiate fixed expense payments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model = new FixedPayments;
				if(isset($_POST['FixedPayments'])){
					$batchNumber   = Expenditure::generateBatchNumber();
					$expenseTypeID = $_POST['FixedPayments']['expensetype_id'];
					$userID        = $_POST['FixedPayments']['user_id'];
					$amount        = $_POST['FixedPayments']['amount'];
					$selectedMonth = $_POST['FixedPayments']['expense_month'];
					$monthDate     = explode('-',$selectedMonth);
					$payMonth      = (int)$monthDate[0];
					$payYear       = (int)$monthDate[1];
					$boundStatus   = PayrollManager::checkBoundedPayrollPeriod($payMonth,$payYear);
					if($boundStatus === 1){
						$init=Expenditure::initiateFixedPayment($batchNumber,$expenseTypeID,$userID,$amount,$selectedMonth);
						switch($init){
							case 1001:
							CommonFunctions::setFlashMessage('danger',"No supplier selected.");
							break;

							case 1003:
							CommonFunctions::setFlashMessage('danger',"Failed to initiate fixed payment.");
							break;

							case 1005:
							CommonFunctions::setFlashMessage('danger',"Duplicate fixed payment record for this month.");
							break;

							case 1000:
							CommonFunctions::setFlashMessage('success',"Fixed payment successfully initiated.");
							break;
						}
					}elseif($boundStatus === 4){
						CommonFunctions::setFlashMessage('danger',"Operation failed. Cannot initiate payment for a month in the past.");
					}elseif($boundStatus === 5){
						CommonFunctions::setFlashMessage('danger',"Operation failed. Cannot initiate payment for a month in the future.");
					}else{
						CommonFunctions::setFlashMessage('danger',"Operation failed. Cannot initiate payment for a month in the future.");
					}
			  	$this->redirect(array('admin'));
				}
				$this->render('create',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * UPDATE FIXED PAYMENT
	 */
	public function actionUpdate($id){
    	switch(Navigation::checkIfAuthorized(245)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to update fixed expense payments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model         = $this->loadModel($id);
    		$currentStatus = $model->status;
    		$ineligible    = array('2','3','4');
    		if(CommonFunctions::searchElementInArray($currentStatus,$ineligible) == 0){
				if(isset($_POST['FixedPayments'])){
					$model->attributes=$_POST['FixedPayments'];
					if($model->save()){
						CommonFunctions::setFlashMessage('success',"Fixed payment successfully updated.");
					$this->redirect(array('admin'));
					}
				}
			$this->render('update',array('model'=>$model));
    		}else{
				CommonFunctions::setFlashMessage('danger',"Operation failed. You cannot update a disbursed/rejected/cancelled payment");
		  	 	$this->redirect(array('admin'));
    		}
    		break;
    	}
	}
	/**
	 * APPROVE FIXED PAYMENT
	 */
	public function actionApprove(){
    	switch(Navigation::checkIfAuthorized(247)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to approve fixed expense payments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
    		if(isset($_POST['approve_cmd'])){
	    			$paymentID = $_POST['fixed_payment'];
	    			$model     = $this->loadModel($paymentID);
	    			$typeID    = $model->expensetype_id;
	    			$amount    = $model->amount;
	    			$reason    = $_POST['reason'].' | '.date('YmdHis');
	    			$status    = '1';
		    		$currentStatus = $model->status;
		    		$ineligible    = array('2','3','4');
		    		if(CommonFunctions::searchElementInArray($currentStatus,$ineligible) == 0){
		    			$auth     = Expenditure::authorizeFixedExpensePayment($status,$paymentID,$typeID,$amount,$reason);
		    			switch($auth){
		    				case 1000:
		    				$alertType = 'success';
		    				$message   = 'The payment has been successfully approved.';
		    				break;

		    				case 2025:
		    				$alertType = 'danger';
		    				$message   = 'Failed to approve the payment since it would exceed the supplier maximum limit.';
		    				break;

		    				case 1003:
		    				$alertType = 'danger';
		    				$message   = 'Failed to approve the payment. Please ensure all payment details are provided.';
		    				break;

		    				case 1007:
		    				$alertType = 'danger';
		    				$message   = 'Operation failed. Kindly perform an approval operation.';
		    				break;
		    			}
							CommonFunctions::setFlashMessage($alertType,$message);
							$this->redirect(array('fixedPayments/'.$paymentID));
		    		}else{
							CommonFunctions::setFlashMessage('danger',"Operation failed. You cannot approve a disbursed/rejected/cancelled payment");
				  	 	$this->redirect(array('admin'));
		    		}
	    	}
    		break;
    	}
	}
	/**
	 * REJECT FIXED PAYMENT
	 */
	public function actionReject(){
    	switch(Navigation::checkIfAuthorized(248)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to reject fixed expense payments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
    		if(isset($_POST['reject_cmd'])){
    			$paymentID = $_POST['fixed_payment'];
    			$model     = $this->loadModel($paymentID);
    			$typeID    = $model->expensetype_id;
    			$amount    = $model->amount;
    			$reason    = $_POST['reason'].' | '.date('YmdHis');
    			$status    = '3';
	    		$currentStatus = $model->status;
	    		$ineligible    = array('2','3','4');
	    		if(CommonFunctions::searchElementInArray($currentStatus,$ineligible) == 0){
	    			$auth      = Expenditure::authorizeFixedExpensePayment($status,$paymentID,$typeID,$amount,$reason);
	    			switch($auth){
	    				case 1000:
	    				$alertType = 'success';
	    				$message   = 'The payment has been successfully rejected.';
	    				break;

	    				case 1001:
	    				$alertType = 'danger';
	    				$message   = 'Failed to reject the payment since the payment does not exist.';
	    				break;

	    				case 1003:
	    				$alertType = 'danger';
	    				$message   = 'Failed to reject the payment. Please ensure all payment details are provided.';
	    				break;

	    				case 1007:
	    				$alertType = 'danger';
	    				$message   = 'Operation failed. Kindly perform a rejection operation.';
	    				break;
	    			}
						CommonFunctions::setFlashMessage($alertType,$message);
						$this->redirect(array('admin'));
	    		}else{
					CommonFunctions::setFlashMessage('danger',"Operation failed. You cannot reject a disbursed/rejected/cancelled payment");
			  	 	$this->redirect(array('admin'));
	    		}
    		}
    		break;
    	}
	}
	/**
	* CANCEL FIXED PAYMENT
	*/
	public function actionCancel(){
    	switch(Navigation::checkIfAuthorized(250)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not authorized to cancel fixed expense payments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
    		if(isset($_POST['cancel_cmd'])){
    			$paymentID = $_POST['fixed_payment'];
    			$model     = $this->loadModel($paymentID);
    			$typeID    = $model->expensetype_id;
    			$amount    = $model->amount;
    			$reason    = $_POST['reason'].' | '.date('YmdHis');
    			$status    = '4';
	    		$currentStatus = $model->status;
	    		$ineligible    = array('2','3','4');
	    		if(CommonFunctions::searchElementInArray($currentStatus,$ineligible) == 0){
	    			$auth      = Expenditure::authorizeFixedExpensePayment($status,$paymentID,$typeID,$amount,$reason);
	    			switch($auth){
	    				case 1000:
	    				$alertType = 'success';
	    				$message   = 'The payment has been successfully cancelled.';
	    				break;

	    				case 1001:
	    				$alertType = 'danger';
	    				$message   = 'Failed to cancel the payment since it does not exist.';
	    				break;

	    				case 1003:
	    				$alertType = 'danger';
	    				$message   = 'Failed to cancel the payment. Please ensure all payment details are provided.';
	    				break;

	    				case 1007:
	    				$alertType = 'danger';
	    				$message   = 'Operation failed. Kindly perform a cancellation operation.';
	    				break;

	    				default:
	    				$alertType = 'danger';
	    				$message   = 'Operation failed. Kindly perform an cancellation operation.';
	    				break;
	    			}
						CommonFunctions::setFlashMessage($alertType,$message);
						$this->redirect(array('admin'));
	    		}else{
						CommonFunctions::setFlashMessage('danger',"Operation failed. You cannot cancel a disbursed/rejected/cancelled payment");
			  	 	$this->redirect(array('admin'));
	    		}
    		}
    		break;
    	}
	}
	/**
	*	DISBURSE FIXED PAYMENT
	*/
	public function actionDisburse(){
    	switch(Navigation::checkIfAuthorized(249)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not authorized to disburse fixed expense payments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
    		if(isset($_POST['disburse_cmd'])){
    			$paymentID = $_POST['fixed_payment'];
    			$model     = $this->loadModel($paymentID);
    			$typeID    = $model->expensetype_id;
    			$amount    = $model->amount;
    			$reason    = $_POST['reason'].' | '.date('YmdHis');
    			$status    = '2';
    			$currentStatus = $model->status;
	    		$ineligible    = array('2','3','4');
	    		if(CommonFunctions::searchElementInArray($currentStatus,$ineligible) == 0){
	    			$auth   = Expenditure::authorizeFixedExpensePayment($status,$paymentID,$typeID,$amount,$reason);
	    			switch($auth){
	    				case 1000:
	    				$alertType = 'success';
	    				$message   = 'The payment has been successfully disbursed.';
	    				break;

	    				case 2025:
	    				$alertType = 'danger';
	    				$message   = 'Failed to disburse the payment since it would exceed the supplier maximum limit.';
	    				break;

	    				case 1003:
	    				$alertType = 'danger';
	    				$message   = 'Failed to disburse the payment. Please ensure all payment details are provided.';
	    				break;

	    				case 1007:
	    				$alertType = 'danger';
	    				$message   = 'Operation failed. Kindly perform an disbursal operation.';
	    				break;

	    				default:
	    				$alertType = 'danger';
	    				$message   = 'Operation failed. Kindly perform an disbursal operation.';
	    				break;
	    			}
						CommonFunctions::setFlashMessage($alertType,$message);
						$this->redirect(array('admin'));
	    		}else{
						CommonFunctions::setFlashMessage('danger',"Operation failed. You cannot disburse a disbursed/rejected/cancelled payment");
			  	 	$this->redirect(array('admin'));
	    		}
    		}
    		break;
    	}
	}
	/**
	 *  MANAGE FIXED PAYMENTS
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(243)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not authorized to manage fixed expense payments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=new FixedPayments('search');
				$model->unsetAttributes();
				if(isset($_GET['FixedPayments'])){
					$model->attributes=$_GET['FixedPayments'];
				}
				$this->render('admin',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * SELECT FIXED PAYMENT
	 */
	public function loadModel($id){
		$model=FixedPayments::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
}
