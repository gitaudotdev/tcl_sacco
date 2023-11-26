<?php

class SavingaccountsController extends Controller{
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
				'actions'=>array('create','update','view','view','approve','reject','depositFunds','withdraw','authorize','updateTransferDetails'),
				'users'=>array('@'),
			),
			array('allow', // allow Admin user to perform 'Admin' and 'delete' actions
				'actions'=>array('admin','delete','loadAccountDetails','updateDetails','transferFunds','savingAccountsReport','depositSavingSTKPush'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionDepositFunds(){
      switch(Navigation::checkIfAuthorized(148)){
      	case 0:
		CommonFunctions::setFlashMessage('danger',"Not allow to deposit funds into saving accounts.");
	  	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
      	$accountID=$_POST['savingaccount'];
      	$amount=$_POST['amount'];
      	$amountFormatted=CommonFunctions::asMoney($amount);
      	$type='credit';
      	$desc='Deposit of savings';
      	$numbers=array();
		$alertType='18';
		$user=SavingFunctions::getAccountUser($accountID);
		$phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
      	$model = $this->loadModel($accountID);
      	$accountHolder=$model->SavingAccountHolderName;
      	$accountNumber=$model->account_number;
      	if($model->is_approved == '1'){
	      	if(SavingFunctions::createTransactionRecord($accountID,$amount,$type,$desc) > 0){
	      		$message="Your savings of KES ".$amountFormatted." to account ".$accountNumber." is received. Your account is now updated. Thank you for saving with TCL.";
				$textMessage = "Dear ".$user->firstName.",".  $message;
				array_push($numbers,$phoneNumber);
				SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id);
				Logger::logUserActivity("Deposited Ksh. $amountFormatted for $accountHolder",'high');
	      		CommonFunctions::setFlashMessage('success',"Funds successfully deposited into the account.");
	      	}else{
	      		CommonFunctions::setFlashMessage('danger',"Failed to deposit funds.");
	      	}
      	}else{
      		CommonFunctions::setFlashMessage('warning',"Failed Operation. Account not approved to receive any funds.");
      	}
      	$this->redirect(Yii::app()->request->urlReferrer);
      	break;
      }
	}

	public function actionDepositSavingSTKPush(){
		switch(Navigation::checkIfAuthorized(282)){
			  case 0:
			  CommonFunctions::setFlashMessage('danger',"Not Authorized to initiate savings deposit payment prompt - STK Push.");
			  $this->redirect(array('dashboard/default'));
			  break;
  
			  case 1:
				if(isset($_POST['push_savings_payment_stk_cmd'])){
					$accountID       = $_POST['savingaccount'];
					$profile         = SavingFunctions::getAccountUser($accountID);
					$model           = $this->loadModel($accountID);
					$profileId       = $profile->id;
					$transactionType = 'SAVINGS_DEPOSIT';
					$amountPaid      = $_POST['amount'];
					$phoneNumber     = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
					$accountNumber   = $model->account_number;
					if($model->is_approved == '1'){
						switch(LoanManager::sendSTKPush($profileId,$transactionType,$amountPaid,$phoneNumber,$accountNumber)){
							case 1000:
							CommonFunctions::setFlashMessage('success',"Saving deposits payment prompt successfully initiated to the customer's phone number.");
							break;
	
							case 1001:
							CommonFunctions::setFlashMessage('danger',"Failed to create stk push record. Please try again later.");
							break;
	
							case 1003:
							CommonFunctions::setFlashMessage('danger',"No response received from MPESA. Please try again later.");
							break;
	
							case 1005:
							CommonFunctions::setFlashMessage('danger',"Failed to generate MPESA API Access Token. Please try again later.");
							break;
	
							case 1007:
							CommonFunctions::setFlashMessage('danger',"MPESA C2B DISABLED. Contact your system Administrator for assistance!");
							break;

							case 1009:
							CommonFunctions::setFlashMessage('danger',"Failed. Please ensure the phone number is valid Safaricom Number.");
							break;
	
							default:
							CommonFunctions::setFlashMessage('danger',"Failed to initiate MPESA payment prompt. Please try again later.");
							break;
						}
						$this->redirect(array('savingaccounts/view/'.$accountID));
					}else{
						CommonFunctions::setFlashMessage('danger',"Failed to initiate payment prompt. The saving account is not yet approved and active.");
						$this->redirect(array('savingaccounts/view/'.$accountID));
					}
				}else{
					CommonFunctions::setFlashMessage('danger',"Failed to initiate payment prompt. Try again later");
					$this->redirect(Yii::app()->request->urlReferrer);
				}
			  break;
		  }
	  }

	public function actionWithdraw(){
      switch(Navigation::checkIfAuthorized(149)){
      	case 0:
		CommonFunctions::setFlashMessage('danger',"Not allowed to request withdrawal of funds from saving accounts.");
	  	$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
      	$accountID=$_POST['savingaccount'];
      	$amount=$_POST['amount'];
      	$reason=$_POST['reason'];
      	$approver=$_POST['approver'];
      	$type=$_POST['type'];
      	$model = $this->loadModel($accountID);
      	if($model->is_approved == '1'){
      		switch(SavingFunctions::requestWithdrawal($accountID,$amount,$reason,$approver,$type)){
      			case 0:
      			CommonFunctions::setFlashMessage('danger',"Failed Operation. There is no user associated with this account.");
      			break;

      			case 1:
      			CommonFunctions::setFlashMessage('success',"Withdrawal request successfully submitted.");
      			break;

      			case 2:
      			CommonFunctions::setFlashMessage('danger',"Failed Operation. The account holder has an active loan account.");
      			break;

      			case 3:
      			CommonFunctions::setFlashMessage('danger',"Failed Operation. The account has insufficient funds.");
      			break;

      			case 4:
      			CommonFunctions::setFlashMessage('danger',"Failed Operation. Request unsuccessful.");
      			break;

      			case 5:
      			CommonFunctions::setFlashMessage('danger',"Failed Operation. The interest earned by this account is insufficient.");
      			break;
      		}
      	}else{
      		CommonFunctions::setFlashMessage('danger',"Failed Operation. Withdrawals from this account forbidden since the account is not approved.");
      	}
      	$this->redirect(Yii::app()->request->urlReferrer);
      	break;
      }
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
      switch(Navigation::checkIfAuthorized(53)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not allow to view saving accounts.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('view',array('model'=>$this->loadModel($id),'savingtransactions'=>SavingFunctions::getAllSavingAccountTransactions($id),
			'users'=>SavingFunctions::getAuthorizingStaff(),'loanaccounts'=>SavingFunctions::getUserRunningLoanAccounts()));
    		break;
    	}
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
      switch(Navigation::checkIfAuthorized(51)){
		case 0:
		CommonFunctions::setFlashMessage('danger',"Not Authorized to create saving accounts.");
		$this->redirect(array('dashboard/default'));
		break;

		case 1:
		$model=new Savingaccounts;
		if(isset($_POST['Savingaccounts'])){
			switch(SavingFunctions::checkIfDuplicateRecordExists($_POST['Savingaccounts']['user_id'])){
				case 0:
				$user = Profiles::model()->findByPk($_POST['Savingaccounts']['user_id']);
				$accountNumber = $user->ProfilePhoneNumber;
				$defaultRate   = ProfileEngine::getActiveProfileAccountSettingByType($user->id,'SAVINGS_INTEREST_RATE');
				$interestRate  = $defaultRate === 'NOT SET' ? Yii::app()->params['DEFAULTSAVINGSINTEREST'] : floatval($defaultRate);
				if($accountNumber != "" && !empty($accountNumber) && !is_null($accountNumber)){
					$model->attributes     = $_POST['Savingaccounts'];
					$model->branch_id      = $user->branchId;
					$model->rm             = $user->managerId;
					$model->account_number = $accountNumber;
					$model->interest_rate  = $interestRate;
					$model->created_by     = Yii::app()->user->user_id;
					$model->created_at     = date('Y-m-d H:i:s');
					if($model->save()){
						if($model->type === 'open'){
							$model->fixed_period=0;
							$model->save();
						}
						$accountHolder = $model->SavingAccountHolderName;
						Logger::logUserActivity("Added Saving Account for $accountHolder",'normal');
						CommonFunctions::setFlashMessage('success',"Account successfully created.");
						$this->redirect(array('admin'));
					}
				}else{
					CommonFunctions::setFlashMessage('danger','The member does not have a primary phone Number. Kindly add a phone number and make it primary.');
					$this->redirect(array('admin'));
				}
				break;

				case 1:
				CommonFunctions::setFlashMessage('warning',"Duplicate account found. The user already has an account with this saving product. Try again with a different product.");
				$this->redirect(array('create'));
				break;
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
      switch(Navigation::checkIfAuthorized(52)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update saving accounts.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Savingaccounts'])){
				$model->attributes=$_POST['Savingaccounts'];
				if($model->save()){
					if($model->type === 'open'){
						$model->fixed_period= 0;
						$model->save();
					}
					$accountHolder = $model->SavingAccountHolderName;
					Logger::logUserActivity("Updated Saving Account for $accountHolder",'normal');
					CommonFunctions::setFlashMessage('success',"Account successfully updated.");
					$this->redirect(array('admin'));
				}
			}
			$this->render('update',array('model'=>$model));
    		break;
    	}
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'Admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
      switch(Navigation::checkIfAuthorized(57)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete saving accounts.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$accountHolder=$this->loadModel($id)->SavingAccountHolderName;
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted Saving Account for $accountHolder",'urgent');
			CommonFunctions::setFlashMessage('success',"Account successfully deleted.");
			$this->redirect(array('admin'));
    		break;
    	}
	}

	public function actionAuthorize($id){
      switch(Navigation::checkIfAuthorized(54)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view saving accounts.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('authorize',array('model'=>$this->loadModel($id)));
    		break;
    	}
	}

	public function actionApprove($id){
	  $model=$this->loadModel($id);
	  $accountHolder=$model->SavingAccountHolderName;
      switch(Navigation::checkIfAuthorized(55)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to approve saving accounts.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
	      	$model->is_approved='1';
			if($model->save()){
				$accountNumber=$model->account_number;
				$user=Profiles::model()->findByPk($model->user_id);
				$firstName=$user->firstName;
				$phoneNumber=ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
				$accountMessage="Dear $firstName, Your savings account is now opened.\nAcc No is $accountNumber\nSave regularly through Paybill = 754298.\nThank you!";
				$numbers=array();
				array_push($numbers,$phoneNumber);
				$alertType='1';
				SMS::broadcastSMS($numbers,$accountMessage,$alertType,$user->id);
				Logger::logUserActivity("Approved Saving Account for $accountHolder",'urgent');
				CommonFunctions::setFlashMessage('success',"Saving Account successfully approved.");
			}else{
				CommonFunctions::setFlashMessage('warning',"Failed! Could not approve the account.");
			}
			$this->redirect(array('admin'));
    		break;
    	}
	}

	public function actionReject($id){
      $model=$this->loadModel($id);
	  $accountHolder=$model->SavingAccountHolderName;
      switch(Navigation::checkIfAuthorized(56)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to reject saving accounts.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model->is_approved='2';
			if($model->save()){
				Logger::logUserActivity("Rejected Saving Account for $accountHolder",'urgent');
				CommonFunctions::setFlashMessage('success',"Saving Account successfully rejected.");
			}else{
				CommonFunctions::setFlashMessage('warning',"Failed! Could not reject the account.");
			}
			$this->redirect(array('admin'));;
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    $element=Yii::app()->user->user_level;
    $array=array('4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
		$model=new Savingaccounts('search');
		$model->unsetAttributes(); 
		if(isset($_GET['Savingaccounts'])){
			$model->attributes=$_GET['Savingaccounts'];
		}
		$this->render('admin',array('model'=>$model));
      break;

      case 1:
		CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  		$this->redirect(array('dashboard/default'));
      break;
    }
	}

	public function actionLoadAccountDetails(){
		$loanaccount_id = $_POST['loanaccount_id'];
		$loanaccount    = Loanaccounts::model()->findByPk($loanaccount_id);
		if(!empty($loanaccount)){
			$accountNumber=$loanaccount->account_number;
			$relationManager=$loanaccount->getRelationshipManagerName();
			$interestRate=$loanaccount->interest_rate;
			$repaymentPeriods=$loanaccount->repayment_period;
			$loanBalance=LoanManager::getActualLoanBalance($loanaccount_id);
			$savingsBalance=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($loanaccount->user_id));
			$bad_symbols = array(",");
		 	$amountValue = str_replace($bad_symbols, "",$loanBalance);
			$topupDetails=array();
			$topupDetails['loan_balance']=$loanBalance;
			echo json_encode($topupDetails);
		}else{
			$message='NOT FOUND';
			echo json_encode($message);
		}
	}

	public function actionUpdateDetails(){
		$element=Yii::app()->user->user_level;
		$array=array('4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$savingaccounts=Savingaccounts::model()->findAll();
			foreach($savingaccounts AS $account){
				if(!empty($account)){
					$user=Profiles::model()->findByPk($account->user_id);
					if(!empty($user)){
						$account->branch_id=$user->branchId;
						$account->rm=$user->managerId;
						$account->save();
						echo "Account Updated";
					}else{
						echo "No User Found";
					}
				}else{
					echo "No Account Found";
				}
			}
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 		$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionTransferFunds(){
      switch(Navigation::checkIfAuthorized(150)){
      	case 0:
		CommonFunctions::setFlashMessage('danger',"Not allowed to transfer savings.");
		$this->redirect(array('dashboard/default'));
      	break;

      	case 1:
      	$accountID        = $_POST['savingaccount'];
      	$amount           = $_POST['amount'];
      	$loanAccountID    = (int)$_POST['loanaccount'];
      	$reason           = $_POST['reason'];
      	$approver         = $_POST['approver'];
      	$loanaccount      = Loanaccounts::model()->findByPk($loanAccountID);
      	$loanAccountNumber= $loanaccount->account_number;
      	$savingAccount    = Savingaccounts::model()->findByPk($accountID);
		$accountHolder    = $savingAccount->SavingAccountHolderName;
		$user             = Profiles::model()->findByPk($approver);
		$phoneNumber      = ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
		$numbers          = array();
		$amountFormatted  = CommonFunctions::asMoney($amount);
		$openingBalance   = $savingAccount->opening_balance;
		$accountBalance   = SavingFunctions::getSavingAccountBalance($accountID);
		$totalBalance     = $accountBalance - $openingBalance;
		if($totalBalance > $amount){
			$transfer= new Transfers;
			$transfer->user_id   =Yii::app()->user->user_id;
			$transfer->branch_id =$savingAccount->branch_id;
			$transfer->savingaccount_id=$accountID;
			$transfer->loanaccount_id =$loanAccountID;
			$transfer->amount=$amount;
			$transfer->transfer_reason=$reason;
			$transfer->approver=$approver;
			if($transfer->save()){
				$message = "Request submitted for transfer of $amountFormatted from savings account of $accountHolder.\nLogin and authorize the transaction.\nThank you!";
				$textMessage = "Dear ".$user->firstName.",".  $message;
				array_push($numbers,$phoneNumber);
				SMS::broadcastSMS($numbers,$textMessage,'17',$user->id);
				Logger::logUserActivity("Submitted transfer request of Ksh. $amountFormatted from $accountHolder to repay loan account: $loanAccountNumber",'high');
				CommonFunctions::setFlashMessage('success',"Request submitted successfully.");
			}else{
				CommonFunctions::setFlashMessage('danger',"Failed to submit transfer request.");
			}
		}else{
			CommonFunctions::setFlashMessage('danger',"Insufficient savings account balance to request the transfer.");
		}
      	$this->redirect(Yii::app()->request->urlReferrer);
      	break;
      }
	}

	public function actionSavingAccountsReport(){
		switch(Navigation::checkIfAuthorized(195)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view savings report.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Savingaccounts('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Savingaccounts'])){
				$model->attributes=$_GET['Savingaccounts'];
				if(isset($_GET['export'])){
					$dataProvider = $model->search();
					$dataProvider->pagination = False;
					$excelWriter = ExportFunctions::getExcelSavingAccountsReport($dataProvider->data);
					echo $excelWriter->save('php://output');
				}
			}
			$this->render('savingAccountsReport',array('model'=>$model));
    		break;
    	}
	}

	public function actionUpdateTransferDetails(){
		$element=Yii::app()->user->user_level;
		$array=array('4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$transfers=Transfers::model()->findAll();
			foreach($transfers AS $transfer){
				if(!empty($transfer)){
					$savingaccount=Savingaccounts::model()->findByPk($transfer->savingaccount_id);
					if(!empty($savingaccount)){
						$transfer->user_id=$savingaccount->rm;
						$transfer->save();
						echo "Transfer Record Updated";
					}else{
						echo "No Account Found";
					}
				}else{
					echo "No Transfer Record Found";
				}
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
	 * @return Savingaccounts the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Savingaccounts::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Savingaccounts $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='savingaccounts-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
