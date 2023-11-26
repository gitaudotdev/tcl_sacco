<?php

class LoanrepaymentsController extends Controller{
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
			array('allow',
				'actions'=>array('admin','update','void','repo','loadFilteredRepayments','accountCollections','updatePhoneTransacted'),
				'users'=>array('@'),
			),
			array('deny',
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
		switch(Navigation::checkIfAuthorized(63)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update loan repayments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=$this->loadModel($id);
			$accountHolder=$model->LoanBorrowerName;
			if(isset($_POST['Loanrepayments'])){
				$status=LoanRepayment::updateLoanRepayment($id,$_POST);
				switch($status){
					case 0:
					CommonFunctions::setFlashMessage('danger',"Repayment could not be updated. Try again later.");
					$this->redirect(array('admin'));
					break;

					case 1:
					Logger::logUserActivity("Updated Repayment for $accountHolder",'high');
					CommonFunctions::setFlashMessage('success',"Repayment successfully updated.");
					$this->redirect(array('admin'));
					break;
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
		switch(Navigation::checkIfAuthorized(147)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view repayments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Loanrepayments('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Loanrepayments'])){
				$model->attributes=$_GET['Loanrepayments'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}

	public function actionAccountCollections(){
		switch(Navigation::checkIfAuthorized(147)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view repayments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Loanrepayments('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Loanrepayments'])){
				$model->attributes=$_GET['Loanrepayments'];
				if(isset($_GET['export'])){
					$dataProvider = $model->search();
					$dataProvider->pagination = False;
					$excelWriter = ExportFunctions::getExcelDisbursedAccountsCollections($dataProvider->data);
					echo $excelWriter->save('php://output');
				}
			}
			$this->render('accountCollections',array('model'=>$model));
    		break;
    	}
	}

	public function actionRepo(){
		switch(Navigation::checkIfAuthorized(147)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update loan repayments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$this->render('repo',array('branches'=>Reports::getAllBranches()));
    		break;
    	}
	}

	public function actionLoadFilteredRepayments(){
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
			$branch=$_POST['branch'];
			$staff=$_POST['staff'];
			$borrower=$_POST['borrower'];
			$loanaccounts=LoanRepayment::LoadFilteredRepayments($borrower,$staff,$branch,$start_date,$end_date);
			if(!empty($loanaccounts)){
					$loanaccounts_array=[];
					$counter=0;
					foreach($loanaccounts as $account){
						$loanaccounts_array[$counter]['MemberName']=$account->getLoanBorrowerName();
						$loanaccounts_array[$counter]['AccountNumber']=$account->getLoanAccountNumber();
						$loanaccounts_array[$counter]['PaymentDate']=$account->getFormattedTransactionDate();
						$loanaccounts_array[$counter]['PrincipalPaid']=$account->getPrincipalPaid();
						$loanaccounts_array[$counter]['InterestPaid']=$account->getInterestPaid();
						$loanaccounts_array[$counter]['PenaltyPaid']=$account->getPenaltyPaid();
						$loanaccounts_array[$counter]['TotalPaid']=$account->getTotalAmountPaid();
						$loanaccounts_array[$counter]['PaymentActions']=$account->getAction();
						$counter++;
					}
					echo json_encode($loanaccounts_array);
			}else{
				echo "NOT FOUND";
			}
			break;

			case 1:
			echo "NOT FOUND";
			break;
		}
	}

	public function actionVoid($id){
		$model=$this->loadModel($id);
		$principalPaid=$model->principal_paid;
		$interestPaid=$model->interest_paid;
		$penaltyPaid=$model->penalty_paid;
		$totalPaid=$principalPaid + $interestPaid + $penaltyPaid;
		$amountVoided=number_format($totalPaid,2);
		$voidedDate=date('jS M Y');
		$accountHolder=$model->LoanBorrowerName;
		$accountNumber=$model->LoanAccountNumber;
		switch(Navigation::checkIfAuthorized(64)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to void loan repayments.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				switch(LoanRepayment::voidLoanRepayment($id)){
					case 0:
					CommonFunctions::setFlashMessage('danger',"Repayment could not be voided.");
					$this->redirect(array('admin'));
					break;

					case 1:
		      		Logger::logUserActivity("Voided Repayment Kshs. $amountVoided,client: $accountHolder, account: $accountNumber on $voidedDate",'urgent');
					CommonFunctions::setFlashMessage('success',"Repayment successfully voided.");
					$this->redirect(array('admin'));
					break;

					case 2:
					CommonFunctions::setFlashMessage('warning',"Voiding failed. Try again later.");
					$this->redirect(array('admin'));
					break;
				}
    		break;
    	}
	}

	public function actionUpdatePhoneTransacted(){
		$repayments = Loanrepayments::model()->findAll();
		foreach($repayments AS $repayment){
			$account = Loanaccounts::model()->findByPk($repayment->loanaccount_id);
			$profile = Profiles::model()->findByPk($account->user_id);
			$phoneNumber = $profile->ProfilePhoneNumber;
			$transactionNumber    = $phoneNumber==='UNDEFINED' ? 'OTHER' : '0'.substr($phoneNumber,-9);
			$repayment->user_id   = $profile->id;
			$repayment->branch_id = $profile->branchId;
			$repayment->rm        = $profile->managerId;
			$repayment->phone_transacted = $transactionNumber;
			$repayment->update();
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Loanrepayments the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Loanrepayments::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Loanrepayments $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='loanrepayments-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
