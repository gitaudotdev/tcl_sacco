<?php

class OutPaymentsController extends Controller{
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
			'actions'=>array('update','approve','reject','disburse','cancel','initiate','uploadReceipt'),
			'users'=>array('@'),
		),
		array('allow', // allow admin user to perform 'admin' and 'delete' actions
			'actions'=>array('admin','view'),
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
	switch(Navigation::checkIfAuthorized(210)){
		case 0:
		CommonFunctions::setFlashMessage('danger',"Not authorized to view supplier payment.");
		$this->redirect(array('dashboard/default'));
		break;

		case 1:
		$files=Expenditure::getOutpaymentFiles($id);
		$this->render('view',array('model'=>$this->loadModel($id),'files'=>$files));
		break;
	}
}
/**
 * Creates a new model.
 * If creation is successful, the browser will be redirected to the 'view' page.
 */
public function actionInitiate(){
    $suppliers=Expenditure::getExpenditureSupplierList();
    $types=Expenditure::getExpenditureExpenseTypeList();
	switch(Navigation::checkIfAuthorized(203)){
		case 0:
		CommonFunctions::setFlashMessage('danger',"Not Authorized to initiate supplier payments.");
		$this->redirect(array('dashboard/default'));
		break;

		case 1:
		if(isset($_POST['supplier_outpayment_cmd'])){
			if(isset($_FILES['payment_resource']) && !is_null($_FILES['payment_resource'])){
				$attachedFile=$_FILES['payment_resource'];
			}else{
				$attachedFile=12345;
			}
			$init_Status=Expenditure::initiateOutPayment($_POST['supplier'],$_POST['expense_type'],$_POST['payment_amount'],$_POST['payment_reason'],$_POST['payment_date'],$_POST['payment_recurring'],$_POST['payment_recurring_date'],$attachedFile);
			switch($init_Status){
				case '0':
				$type='danger';
				$message='Operation Failed. An error occurred while initiating supplier payment. Try again later.';
				break;

				case '1':
				$type='success';
				$message='Supplier payment successfully initiated.';
				break;

				case '2':
				$type='danger';
				$message='Operation Failed. There is no supplier provided to initiate the payment.';
				break;
			}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('admin'));
		}
		$this->render('initiate',array('suppliers'=>$suppliers,'types'=>$types));
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
	$model=$this->loadModel($id);
	$modelStatus=$model->status;
	$modelsStatusHay=array('2','3','4');
	$supplierAmountBefore=CommonFunctions::asMoney($model->amount);
	if(CommonFunctions::searchElementInArray($modelStatus,$modelsStatusHay) === 0){
        switch(CommonFunctions::searchElementInArray($element,$array)){
          case 0:
          switch(Navigation::checkIfAuthorized(209)){
        		case 0:
    			CommonFunctions::setFlashMessage('danger',"Not Authorized to update payments to suppliers.");
    	  	 	$this->redirect(array('dashboard/default'));
        		break;

        		case 1:
				if(isset($_POST['OutPayments'])){
					$model->attributes=$_POST['OutPayments'];
					if($model->save()){
						$supplierFullName=$model->OutPaymentSupplier;
						$supplierTotalAmount=CommonFunctions::asMoney($model->amount);
						Logger::logUserActivity("Updated supplier payment for <strong>$supplierFullName</strong> from amount: <strong>$supplierAmountBefore</strong> to amount: <strong>$supplierTotalAmount</strong> ",'high');
						$type='success';
						$message='Supplier payment updated successfully.';
					}else{
						$type='danger';
						$message='Updating supplier payment failed.';
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('admin'));
				}
				$this->render('update',array('model'=>$model,));
        		break;
        	}
          break;

          case 1:
    	  CommonFunctions::setFlashMessage('danger',"Access Denied.");
      	  $this->redirect(array('dashboard/default'));
          break;
        }
	}else{
    	$restrictType='danger';
    	$restrictMessage='Updating a supplier payment that is rejected/cancelled/disbursed is Denied.';
    	CommonFunctions::setFlashMessage($restrictType,$restrictMessage);
    	$this->redirect(array('admin'));
	}
}
/**
/**
 * Manages all models.
 */
public function actionAdmin(){
	switch(Navigation::checkIfAuthorized(208)){
		case 0:
		CommonFunctions::setFlashMessage('danger',"Not Authorized to manage supplier payments.");
		$this->redirect(array('dashboard/default'));
		break;

		case 1:
		$model=new OutPayments('search');
		$model->unsetAttributes();
		if(isset($_GET['OutPayments'])){
			$model->attributes=$_GET['OutPayments'];
		}
		$this->render('admin',array('model'=>$model));
		break;
	}
}

public function actionApprove($id){
      switch(Navigation::checkIfAuthorized(204)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to approve payments to suppliers.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
    		if(isset($_POST['approve_top_up_cmd']) && isset($_POST['approvalReason'])){
    			switch(Expenditure::approveOutPayment($id,$_POST['approvalReason'])){
    				case 0:
    				$type="danger";
    				$message="Failed to approve the supplier payment.";
    				break;

    				case 1:
    				$type="success";
    				$message="Supplier payment approved successfully.";
    				break;

    				case 2:
    				$type="danger";
    				$message="The supplier payment record does not exist.";
    				break;

    				case 3:
    				$type="danger";
    				$message="The supplier payment has already been approved.";
    				break;

    				case 4:
    				$type="danger";
    				$message="The supplier payment has already been disbursed.";
    				break;

    				case 5:
    				$type="danger";
    				$message="The supplier payment has already been rejected.";
    				break;

    				case 6:
    				$type="danger";
    				$message="The supplier payment has already been cancelled.";
    				break;
    			}
    		}else{
    			$type="danger";
    			$message="The supplier payment cannot be approved since no reason for approval has been provided.";
    		}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('outPayments/'.$id));
    		break;
    	}
}

public function actionReject($id){
      switch(Navigation::checkIfAuthorized(205)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to reject supplier payments.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
    		if(isset($_POST['reject_top_up_cmd']) && isset($_POST['rejectionReason'])){
    			switch(Expenditure::rejectOutPayment($id,$_POST['rejectionReason'])){
    				case 0:
    				$type="danger";
    				$message="Failed to reject the supplier payment.";
    				break;

    				case 1:
    				$type="success";
    				$message="Supplier payment rejected successfully.";
    				break;

    				case 2:
    				$type="danger";
    				$message="The supplier payment record does not exist.";
    				break;

    				case 3:
    				$type="danger";
    				$message="The supplier payment has already been approved.";
    				break;

    				case 4:
    				$type="danger";
    				$message="The supplier payment has already been disbursed.";
    				break;

    				case 5:
    				$type="danger";
    				$message="The supplier payment has already been rejected.";
    				break;

    				case 6:
    				$type="danger";
    				$message="The supplier payment has already been cancelled.";
    				break;
    			}
    		}else{
    			$type="danger";
    			$message="The supplier payment cannot be rejected since no reason for rejection has been provided.";
    		}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('outPayments/'.$id));
    		break;
    	}
}

public function actionDisburse($id){
      switch(Navigation::checkIfAuthorized(207)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to disburse supplier payments.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
    		if(isset($_POST['disburse_top_up_cmd']) && isset($_POST['disbursalReason'])){
    			$disbursalStatus=Expenditure::disburseOutPayment($id,$_POST['disbursalReason']);
    			switch($disbursalStatus){
    				case 0:
    				$type="danger";
    				$message="Failed to disburse the supplier payment.";
    				break;

    				case 1:
    				$type="success";
    				$message="Supplier payment disbursed successfully.";
    				break;

    				case 2:
    				$type="danger";
    				$message="The supplier payment record does not exist.";
    				break;

    				case 3:
    				$type="danger";
    				$message="The supplier payment has not yet been approved.";
    				break;

    				case 4:
    				$type="danger";
    				$message="The supplier payment has already been disbursed.";
    				break;

    				case 5:
    				$type="danger";
    				$message="The supplier payment has already been rejected.";
    				break;

    				case 6:
    				$type="danger";
    				$message="The supplier payment has already been cancelled.";
    				break;

    				case 7:
    				$type="danger";
    				$message="Failed record the payment receipt,but payment successfully disbursed.";
    				break;

    				case 8:
    				$type="danger";
    				$message="Failed. Please try again later";
    				break;

    				default:
    				$type="danger";
    				$message="Failed. $disbursalStatus";
    				break;
    			}
    		}else{
			$type="danger";
			$message="The supplier payment cannot be disbursed since no disbursal reason has been provided.";
    		}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('outPayments/'.$id));
    		break;
    	}
}

public function actionCancel($id){
      switch(Navigation::checkIfAuthorized(206)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to cancel supplier payments.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
    		if(isset($_POST['cancel_top_up_cmd']) && isset($_POST['cancellationReason'])){
    			switch(Expenditure::cancelOutPayment($id,$_POST['cancellationReason'])){
    				case 0:
    				$type="danger";
    				$message="Failed to cancel the supplier payment.";
    				break;

    				case 1:
    				$type="success";
    				$message="Supplier payment cancelled successfully.";
    				break;

    				case 2:
    				$type="danger";
    				$message="The supplier payment record does not exist.";
    				break;

    				case 3:
    				$type="danger";
    				$message="The supplier payment has already been disbursed.";
    				break;

    				case 4:
    				$type="danger";
    				$message="The supplier payment has already been rejected.";
    				break;

    				case 5:
    				$type="danger";
    				$message="The supplier payment has already been cancelled.";
    				break;
    			}
    		}else{
    		$type="danger";
    		$message="The supplier payment cannot be cancelled since no cancellation reason has been provided.";
    		}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('outPayments/'.$id));
    		break;
    	}
}

public function actionUploadReceipt(){
      switch(Navigation::checkIfAuthorized(217)){
        case 0:
        CommonFunctions::setFlashMessage('danger',"Not authorized to upload payment support files.");
        $this->redirect(Yii::app()->request->urlReferrer);
        break;

        case 1:
        if(isset($_POST['upload_file_cmd'])){
            $outpaymentID=filter_var($_POST['outpayment_ID'], FILTER_SANITIZE_STRING);
            $activity=filter_var($_POST['outpayment_activity'], FILTER_SANITIZE_STRING);
            if(isset($_FILES['payment_resource']) && !is_null($_FILES['payment_resource'])){
              $outPaymentReceipt=$_FILES['payment_resource'];
            }else{
             $outPaymentReceipt=12345;
            }
            switch(Expenditure::uploadOutpaymentFile($outpaymentID,$outPaymentReceipt,$activity)){
                case 0:
                $type="danger";
                $message="Failed to upload the supplier payment support file.";
                break;

                case 1:
                $type="success";
                $message="Supplier payment support file uploaded successfully.";
                break;
            }
          }else{
            $type="danger";
            $message="Failed to upload payment support document. Please provide the file and brief comment";
          }
        CommonFunctions::setFlashMessage($type,$message);
        $this->redirect(Yii::app()->request->urlReferrer);
        break;
      }
}
/**
 * Returns the data model based on the primary key given in the GET variable.
 * If the data model is not found, an HTTP exception will be raised.
 * @param integer $id the ID of the model to be loaded
 * @return OutPayments the loaded model
 * @throws CHttpException
 */
public function loadModel($id){
	$model=OutPayments::model()->findByPk($id);
	if($model===null){
		throw new CHttpException(404,'The requested page does not exist.');
	}
	return $model;
}
/**
 * Performs the AJAX validation.
 * @param OutPayments $model the model to be validated
 */
protected function performAjaxValidation($model){
	if(isset($_POST['ajax']) && $_POST['ajax']==='out-payments-form'){
		echo CActiveForm::validate($model);
		Yii::app()->end();
	}
}

}
