<?php

class Expenditure{

	/************************

		REQUISITE DATA

	*********************/
	public static function getExpenseFiles($expenseID){
		$expenseQuery = "SELECT * FROM expense_files WHERE expense_id=$expenseID";
		$files  = ExpenseFiles::model()->findAllBySql($expenseQuery);
		return !empty($files) ? $files : 0;
	}

	public static function getExpenditureSupplierList(){
		$profileBranchID = Yii::app()->user->user_branch;
		$profileID       = Yii::app()->user->user_id;
        $profileQuery    = "SELECT * FROM profiles WHERE profileType IN('STAFF','SUPPLIER')";
		switch(Yii::app()->user->user_level){
			case '0':
			$profileQuery.="";
			break;

			case '1':
            $profileQuery.=" AND branchId=$profileBranchID";
			break;

            case '2':
            $profileQuery.=" AND managerId=$profileID";
            break;

            case '3':
            $profileQuery.=" AND id=$profileID";
            break;
		}
        $profileQuery.="  ORDER BY firstName,lastName ASC";
		return Profiles::model()->findAllBySql($profileQuery);
	}

	public static function getExpenditureExpenseTypeList(){
		return ExpenseTypes::model()->findAll();
	}
	/************************

		Out Payments

	*********************/
	public static function getOutpaymentFiles($outpaymentID){
		$fileQuery="SELECT * FROM outpayment_files WHERE outpayment_id=$outpaymentID
		 ORDER BY id DESC";
		$uploadedFiles= OutpaymentFiles::model()->findAllBySql($fileQuery);
		return $uploadedFiles;
	}

	public static function initiateOutPayment($userID,$expenseTypeID,$amount,$initiationReason,$outPaymentDate,$outPaymentRecurStatus,$outPaymentRecurDate,$outPaymentReceipt){
		$profile=Profiles::model()->findByPk($userID);
		if(!empty($profile)){
			$outPayment=new OutPayments;
			$outPayment->user_id=$userID;
			$outPayment->expensetype_id=$expenseTypeID;
			$outPayment->branch_id=$profile->branchId;
			$outPayment->rm=$profile->managerId;
			$outPayment->amount=$amount;
			$outPayment->initiated_by=Yii::app()->user->user_id;
			$outPayment->initiation_reason=$initiationReason;
			$outPayment->outpayment_date=$outPaymentDate;
			$outPayment->outpayment_status=$outPaymentRecurStatus;
			$outPayment->outpayment_recur_date=$outPaymentRecurDate;
			$outPayment->initiated_at = date('Y-m-d H:i:s');
			if($outPayment->save()){
				$outpaymentID=$outPayment->id;
				$activity='initiation of supplier payment';
				$uploadStatus=Expenditure::uploadOutpaymentFile($outpaymentID,$outPaymentReceipt,$activity);
				$supplierFullName=$outPayment->OutPaymentSupplier;
				$supplierTotalAmount=CommonFunctions::asMoney($outPayment->amount);
			  	Logger::logUserActivity("Initiated supplier payment for <strong>$supplierFullName</strong>
				    for amount: <strong>$supplierTotalAmount</strong> with reason: <strong>
				    $initiationReason </strong>",'high');
				$outPaymentStatus=1;
			}else{
				$outPaymentStatus=0;
			}
		}else{
			$outPaymentStatus=2;
		}
		return $outPaymentStatus;
	}

	public static function uploadOutpaymentFile($outpaymentID,$uploadFileName,$activity){
		$expenseDocumentURL=Yii::app()->params['expenseDocs'];
		$outpayment=OutPayments::model()->findByPk($outpaymentID);
		$supplierFullName=$outpayment->OutPaymentSupplier;
		$supplierTotalAmount=CommonFunctions::asMoney($outpayment->amount);
		if($uploadFileName != 12345){
	    $receiptDescription=CommonFunctions::reArrayFiles($uploadFileName);
	    foreach($receiptDescription as $receiptValue){
				$receiptExtension = pathinfo($receiptValue['name'],PATHINFO_EXTENSION);
				$currentFileName=$receiptValue['name'];
				$paymentFile = new OutpaymentFiles;
				$paymentFile->outpayment_id=$outpaymentID;
				$paymentFile->name=$currentFileName;
				$paymentFile->filename=date('YmdHis',time()).mt_rand().'.'.$receiptExtension;
				$paymentFile->activity=$activity;
				$paymentFile->uploaded_by=Yii::app()->user->user_id;
				$paymentFile->uploaded_at=date('Y-m-d H:i:s');
				if($paymentFile->save()){
					$uploadedReceipt=$paymentFile->filename;
					move_uploaded_file($receiptValue['tmp_name'],$expenseDocumentURL."/".$uploadedReceipt);
					Logger::logUserActivity("Uploaded supplier payment file: <strong>$currentFileName</strong> for
					<strong>$supplierFullName</strong> for amount: <strong>$supplierTotalAmount</strong> during
					<strong>$activity</strong>",'high');
				}
	    }
	    $uploadStatus=1;
		}else{
			$uploadStatus=0;
		}
		return $uploadStatus;
	}

	public static function renameOutpaymentFile($fileID,$newFileName){
		$paymentFile =OutpaymentFiles::model()->findByPk($fileID);
		$paymentFile->name=$newFileName;
		if($paymentFile->save()){
			$renameStatus=1;
		}else{
			$renameStatus=0;
		}
		return $renameStatus;
	}

	public static function getRecentUploadedOutpaymentFile($outpaymentID){
		$fileQuery="SELECT * FROM outpayment_files WHERE outpayment_id=$outpaymentID
		 ORDER BY id DESC LIMIT 1";
		$file= OutpaymentFiles::model()->findBySql($fileQuery);
		if(!empty($file)){
			$recentFileName=$file->filename;
		}else{
			$recentFileName=0;
		}
		return $recentFileName;
	}
	
	public static function approveOutPayment($outPaymentID,$approvalReason){
		$outpayment=OutPayments::model()->findByPk($outPaymentID);
		if(!empty($outpayment)){
			switch($outpayment->status){
				case '0':
				$outpayment->status='1';
				$outpayment->approved_by=Yii::app()->user->user_id;
				$outpayment->approval_reason=$approvalReason;
				$outpayment->approved_at=date('Y-m-d H:i:s');
				if($outpayment->save()){
					$supplierFullName=$outpayment->OutPaymentSupplier;
					$supplierTotalAmount=CommonFunctions::asMoney($outpayment->amount);
		   			Logger::logUserActivity("Approved supplier payment for <strong>$supplierFullName</strong> for amount: <strong>$supplierTotalAmount</strong> with reason: <strong>$approvalReason</strong>",'high');
					$approvalStatus=1;//Approved success
				}else{
					$approvalStatus=0;//Failed to approve
				}
				break;

				case '1':
				$approvalStatus=3;//already approved
				break;

				case '2':
				$approvalStatus=4;//already disbursed
				break;

				case '3':
				$approvalStatus=5;//already rejected
				break;

				case '4':
				$approvalStatus=6;//Already Cancelled
				break;
			}
		}else{
			$approvalStatus=2;
		}
		return $approvalStatus;
	}

	public static function rejectOutPayment($outPaymentID,$rejectionReason){
		$outpayment=OutPayments::model()->findByPk($outPaymentID);
		if(!empty($outpayment)){
			switch($outpayment->status){
				case '0':
				$outpayment->status='3';
				$outpayment->rejected_by=Yii::app()->user->user_id;
				$outpayment->rejection_reason=$rejectionReason;
				$outpayment->rejected_at=date('Y-m-d H:i:s');
				if($outpayment->save()){
					$supplierFullName=$outpayment->OutPaymentSupplier;
					$supplierTotalAmount=CommonFunctions::asMoney($outpayment->amount);
		   			Logger::logUserActivity("Rejected supplier payment for <strong>$supplierFullName</strong> for amount:<strong>$supplierTotalAmount</strong> with reason: <strong>$rejectionReason</strong>",'high');
					$rejectionStatus=1;
				}else{
					$rejectionStatus=0;
				}
				break;

				case '1':
				$rejectionStatus=3;//Already Approved
				break;

				case '2':
				$rejectionStatus=4;//Already Disbursed
				break;

				case '3':
				$rejectionStatus=5;//Already Rejected
				break;

				case '4':
				$rejectionStatus=6;//Already Cancelled
				break;
			}
		}else{
			$rejectionStatus=2;
		}
		return $rejectionStatus;
	}

	public static function disburseOutPayment($outPaymentID,$disbursalReason){
		$outpayment=OutPayments::model()->findByPk($outPaymentID);
		if(!empty($outpayment)){
			switch($outpayment->status){
				case '0':
				$disbursalStatus=3;//Not Approved
				break;

				case '1':
				//Essential Details
				$amount=$outpayment->amount;
				$amountFormatted=CommonFunctions::asMoney($amount);
				$profile=Profiles::model()->findByPk($outpayment->user_id);
				$clientFirstName=strtoupper($profile->firstName);
				$fullName=$profile->ProfileFullName;
				$clientPhoneNumber=ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
				$phoneNumber='254'.$clientPhoneNumber;
				//B2C MPESA
				$commandID='BusinessPayment';
				$remarks='Processed and disbursed supplier payment : '.$amountFormatted;
				$transtatus=LoanManager::B2CTransaction($commandID,$amount,$phoneNumber,$remarks);
				switch($transtatus){
					case 0:
					$disbursalStatus=8;
					break;

					case 1:
					$outpayment->status='2';
					$outpayment->disbursed_by=Yii::app()->user->user_id;
					$outpayment->disbursal_reason=$disbursalReason;
					$outpayment->disbursed_at=date('Y-m-d H:i:s');
					if($outpayment->save()){
						$recentFileName=Expenditure::getRecentUploadedOutpaymentFile($outpayment->id);
						$expenseDerivedName=$outpayment->getOutPaymentExpenseType()."_".$fullName."-".$outpayment->disbursed_at."-".strtoupper(CommonFunctions::generateToken(8));
						$supplierFullName=$outpayment->OutPaymentSupplier;
						$supplierTotalAmount=CommonFunctions::asMoney($outpayment->amount);
			   			Logger::logUserActivity("Disbursed supplier payment for <strong>$supplierFullName</strong> for amount:<strong>$supplierTotalAmount</strong> with reason: <strong>$disbursalReason</strong> and recorded an Expense: <strong>$expenseDerivedName</strong>",'high');
						$expense=new Expenses;
						$expense->expensetype_id=$outpayment->expensetype_id;
						$expense->user_id=$outpayment->user_id;
						$expense->branch_id=$outpayment->branch_id;
						$expense->name=strtoupper($expenseDerivedName);
						$expense->amount=$outpayment->amount;
						$expense->modifiable='0';
						if(!empty($recentFileName) && $recentFileName != 0){
							$expense->attachment=$recentFileName;
						}
						$expense->expense_date=$outpayment->outpayment_date;
						$expense->expense_recur=$outpayment->outpayment_status;
						$expense->date_recurring=$outpayment->outpayment_recur_date;
						$expense->description=$outpayment->disbursal_reason;
						$expense->created_by=$outpayment->disbursed_by;
						$expense->created_at = date('Y-m-d H:i:s');
						if($expense->save()){
							if(!empty($expense->attachment) && !is_null($expense->attachment)){
								$expenseAttachment=new ExpenseFiles;
							 	$expenseAttachment->expense_id=$expense->expense_id;
								$expenseAttachment->filename=$expense->attachment;
								$expenseAttachment->uploaded_by=Yii::app()->user->user_id;
								$expenseAttachment->save();
							}
							$paymentFor = ucfirst(ExpenseTypes::model()->findByPk($expense->expensetype_id)->name);
							$msg="Your $paymentFor Expense of KES $amountFormatted has been processed and disbursed.\nThank you!";
							$textMessage = "Dear ".$clientFirstName.", ". $msg;
							$numbers=array();
							array_push($numbers,$clientPhoneNumber);
							$alertType='3';
							SMS::broadcastSMS($numbers,$textMessage,$alertType,$profile->id);
							$disbursalStatus=1;//Disbursed success
						}else{
							$disbursalStatus=7;//Failed to attach the receipt
						}
					}else{
						$disbursalStatus=0;//Disbursed failed
					}
					break;

					default:
					$disbursalStatus=$transtatus;
					break;
				}
				break;

				case '2':
				$disbursalStatus=4;//Already Disbursed
				break;

				case '3':
				$disbursalStatus=5;//Already Rejected
				break;

				case '4':
				$disbursalStatus=6;//Already Cancelled
				break;
			}
		}else{
			$disbursalStatus=2;
		}
		return $disbursalStatus;
	}

	public static function cancelOutPayment($outPaymentID,$cancellationReason){
		$outpayment=OutPayments::model()->findByPk($outPaymentID);
		if(!empty($outpayment)){
			switch($outpayment->status){
				case '0':
				$outpayment->status='4';
				$outpayment->cancelled_by=Yii::app()->user->user_id;
				$outpayment->cancellation_reason=$cancellationReason;
				$outpayment->cancelled_at=date('Y-m-d H:i:s');
				if($outpayment->save()){
					$supplierFullName=$outpayment->OutPaymentSupplier;
					$supplierTotalAmount=CommonFunctions::asMoney($outpayment->amount);
		   			Logger::logUserActivity("Cancelled supplier payment <strong>$supplierFullName</strong> for amount:
		    		<strong>$supplierTotalAmount</strong> with reason: <strong>$cancellationReason</strong>",'high');
					$cancellationStatus=1;//Cancelled success
				}else{
					$cancellationStatus=0;//Cancel failed
				}
				break;

				case '1':
				$outpayment->status='4';
				$outpayment->cancelled_by=Yii::app()->user->user_id;
				$outpayment->cancellation_reason=$cancellationReason;
				$outpayment->cancelled_at=date('Y-m-d H:i:s');
				if($outpayment->save()){
					$supplierFullName=$outpayment->OutPaymentSupplier;
					$supplierTotalAmount=CommonFunctions::asMoney($outpayment->amount);
		   			Logger::logUserActivity("Cancelled supplier payment for <strong>$supplierFullName</strong> for amount: <strong>$supplierTotalAmount</strong> with reason: <strong>$cancellationReason</strong>",'high');
					$cancellationStatus=1;//Cancelled success
				}else{
					$cancellationStatus=0;//Cancel failed
				}
				break;

				case '2':
				$cancellationStatus=3;//Already Disbursed
				break;

				case '3':
				$cancellationStatus=4;//Already Rejected
				break;

				case '4':
				$cancellationStatus=5;//Already Cancelled
				break;
			}
		}else{
			$cancellationStatus=2;
		}
		return $cancellationStatus;
	}

	/******************

		FIXED PAYMENTS

	**********************/
	public static function getFixedPaymentSuppliers(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$suppliersQuery="SELECT * FROM profiles,auths WHERE profiles.id=auths.profileId AND profiles.profileType IN('SUPPLIER') AND auths.authStatus IN('ACTIVE')
		AND profiles.id NOT IN(SELECT user_id FROM fixed_payments WHERE status IN ('0','1'))";
		switch(Yii::app()->user->user_level){
			case '0':
			$suppliersQuery .= "";
			break;

			case '1':
			$suppliersQuery .= " AND profiles.branchId=$userBranch";
			break;

			case '2':
			$suppliersQuery .= " AND profiles.managerId=$userID";
			break;

			default:
			$suppliersQuery .= " AND profiles.id=$userID";
			break;
		}
		$suppliersQuery .= " ORDER BY profiles.firstName,profiles.lastName ASC";
		return Profiles::model()->findAllBySql($suppliersQuery);
	}

	public static function generateBatchNumber(){
		return strtoupper(CommonFunctions::generateToken(32));
	}

	public function getAllExpensePaymentsByBatchNumber($batchNumber){
		$expenseQuery="SELECT * FROM fixed_payments WHERE batch_number='$batchNumber' ORDER BY id DESC";
		return FixedPayments::model()->findAllBySql($expenseQuery);
	}

	public static function restrictDuplicateFixedExpense($userID,$payMonth,$payYear){
		$duplicateQuery="SELECT * FROM fixed_payments WHERE user_id=$userID AND expense_month=$payMonth
		 AND expense_year=$payYear AND status IN('0','1','2')";
		$records=FixedPayments::model()->findAllBySql($duplicateQuery);
		if(!empty($records)){
			$duplicated=1;
		}else{
			$duplicated=0;
		}
		return $duplicated;
	}

	public static function initiateFixedPayment($batchNumber,$expenseTypeID,$userID,$amount,$selectedMonth){
	  $monthDate = explode('-',$selectedMonth);
		$expenseMonth=(int)$monthDate[0];
		$expenseYear=(int)$monthDate[1];
		switch(Expenditure::restrictDuplicateFixedExpense($userID,$expenseMonth,$expenseYear)){
			case 0:
			$profile = Profiles::model()->findByPk($userID);
			if(!empty($profile)){
				$supplierName=$profile->ProfileFullName;
				$userManager=$profile->managerId;
				$branchID=$profile->branchId;
				$payment = new FixedPayments;
				$payment->batch_number=$batchNumber;
				$payment->expensetype_id=$expenseTypeID;
				$payment->user_id=$userID;
				$payment->branch_id=$branchID;
				$payment->rm=$userManager;
				$payment->amount=$amount;
				$payment->expense_month=$expenseMonth;
				$payment->expense_year=$expenseYear;
				$payment->initiation_reason ="Initiated fixed payment|Supplier|".$supplierName."|Manager|".$userManager."|ExpenseType|".$expenseTypeID;
				$payment->initiated_by =Yii::app()->user->user_id;
				$payment->created_by   =Yii::app()->user->user_id;
				$payment->created_at=date('Y-m-d H:i:s');
				if($payment->save()){
					$amountFormatted=CommonFunctions::asMoney($amount);
					$expenseName=$payment->FixedPaymentExpenseTypeName;
					$expensePeriod=CommonFunctions::getMonthName($expenseMonth).'-'.$expenseYear;
					$activity="Initiated fixed expense payment: <strong> $expenseName </strong> worth
					 KES $amountFormatted for supplier: $supplierName for period: <strong>$expensePeriod</strong>";
					Logger::logUserActivity($activity,'high');
					$initiatedStatus=1000;//success
				}else{
					$initiatedStatus=1003;//Failed
				}
			}else{
				$initiatedStatus=1001;//No User
			}
			break;

			case 1:
			$initiatedStatus=1005;//Duplicate Record for the month
			break;
		}
		return $initiatedStatus;
	}

	public static function authorizeFixedExpensePayment($status,$paymentID,$typeID,$amount,$reason){
		$payment=FixedPayments::model()->findByPk($paymentID);
		$profile=Profiles::model()->findByPk($payment->user_id);
		$defaultLimit = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
		$maxLimit     = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
		if($status === '1250'){
			$auth_status=1007;//Undefined Action
		}else{
			$amountFormatted=CommonFunctions::asMoney($amount);
			$expenseName=$payment->FixedPaymentExpenseTypeName;
			$clientFirstName=strtoupper($profile->firstName);
			$derivedName=$expenseName."_".$clientFirstName."-".strtotime($payment->created_at)."-".
			strtoupper(CommonFunctions::generateToken(8));
			$fullName=$profile->ProfileFullName;
			$clientPhoneNumber=ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
			$phoneNumber='254'.$clientPhoneNumber;
			switch($status){
				case '1':
				if(floatval($amount) > floatval($maxLimit)){
					$auth_status=2025;
				}else{
					$auth_status=Expenditure::updateFixedExpensePayment($paymentID,$typeID,$amount,
						$status,$derivedName,$reason);
				}
				break;

				case '2':
				if(floatval($amount) > floatval($maxLimit)){
					$auth_status=2025;
				}else{
					$commandID='BusinessPayment';
					$remarks='Processed and disbursed supplier payment : '.$amountFormatted;
					$transtatus=LoanManager::B2CTransaction($commandID,$amount,$phoneNumber,$remarks);
					switch($transtatus){
						case 0:
						$auth_status=8;
						break;

						case 1:
						$auth_status=Expenditure::updateFixedExpensePayment($paymentID,$typeID,$amount,$status,$derivedName,$reason);
						break;

						default:
						$auth_status=$transtatus;
						break;
					}
				}
				break;

				default:
				$auth_status=Expenditure::updateFixedExpensePayment($paymentID,$typeID,$amount,$status,$derivedName,$reason);
				break;
			}
			Expenditure::sendFixedPaymentsLoggingAndNotification($status,$clientFirstName,$clientPhoneNumber,
				$expenseName,$amountFormatted,$reason,$profile->id);
		}
		return $auth_status;
	}

	public static function updateFixedExpensePayment($paymentID,$typeID,$amount,$status,$derivedName,$reason){
		$payment=FixedPayments::model()->findByPk($paymentID);
		if(!empty($payment)){
			$payment->expensetype_id=$typeID;
			$payment->amount=$amount;
			$payment->status=$status;
			switch($status){
				case '1':
				$payment->approved_by=Yii::app()->user->user_id;
				$payment->approval_reason=$reason;
				if($payment->save()){
					$updatedStatus=1000;//Success
				}else{
					$updatedStatus=1003;//Failed
				}
				break;

				case '2':
				$payment->disbursed_by=Yii::app()->user->user_id;
				$payment->disbursal_reason=$reason;
				if($payment->save()){
					$expense=new Expenses;
					$expense->expensetype_id=$typeID;
					$expense->user_id=$payment->user_id;
					$expense->branch_id=$payment->branch_id;
					$expense->name=strtoupper($derivedName);
					$expense->amount=$amount;
					$expense->modifiable='0';
					$expense->expense_date=date('Y-m-d');
					$expense->expense_recur='0';
					$expense->date_recurring=0;
					$expense->description=$reason;
					$expense->created_by=Yii::app()->user->user_id;
					$expense->created_at=date('Y-m-d H:i:s');
					$expense->save();
					$updatedStatus=1000;//Success
				}else{
					$updatedStatus=1003;//Failed
				}
				break;

				case '3':
				$payment->rejected_by=Yii::app()->user->user_id;
				$payment->rejection_reason =$reason;
				if($payment->save()){
					$updatedStatus=1000;//Success
				}else{
					$updatedStatus=1003;//Failed
				}
				break;

				case '4':
				$payment->cancelled_by=Yii::app()->user->user_id;
				$payment->cancellation_reason=$reason;
				if($payment->save()){
					$updatedStatus=1000;//Success
				}else{
					$updatedStatus=1003;//Failed
				}
				break;
			}
		}else{
			$updatedStatus=1001;//Fixed Payment Unavailable
		}
		return $updatedStatus;
	}

	public static function sendFixedPaymentsLoggingAndNotification($status,$firstName,$phone,$expense,
		$amount,$reason,$profileId){
		$numbers=array();
		switch($status){
			case '1':
			$activity="Approved supplier fixed $expense expense payment for <strong>$firstName</strong> worth: 
			<strong>KES $amount</strong> with reason: <strong>$reason</strong>";
			$msg="Your $expense expense/payment of KES $amount has been approved.\nThank you!";
			$textMessage = "Dear ".$firstName.", ". $msg;
			array_push($numbers,$phone);
			$alertType='31';
			break;

			case '2':
			$activity="Disbursed supplier fixed $expense expense payment for <strong>$firstName</strong> worth: 
			<strong>KES $amount</strong> with reason: <strong>$reason</strong>";
			$msg="Your $expense expense/payment of KES $amount has been processed and disbursed.\nThank you!";
			$textMessage = "Dear ".$firstName.", ". $msg;
			array_push($numbers,$phone);
			$alertType='32';
			break;

			case '3':
			$activity="Rejected supplier fixed $expense expense payment for <strong>$firstName</strong> worth: 
			<strong>KES $amount</strong> with reason: <strong>$reason</strong>";
			$msg="Your $expense expense/payment of KES $amount has been rejected.\nContact your manager for more info.\nThank you!";
			$textMessage = "Dear ".$firstName.", ". $msg;
			array_push($numbers,$phone);
			$alertType='33';
			break;

			case '4':
			$activity="Cancelled supplier fixed $expense expense payment for <strong>$firstName</strong> worth: 
			<strong>KES $amount</strong> with reason: <strong>$reason</strong>";
			$msg="Your $expense expense/payment of KES $amount has been cancelled.\nContact your manager for more info.\nThank you!";
			$textMessage = "Dear ".$firstName.", ". $msg;
			array_push($numbers,$phone);
			$alertType='34';
			break;
		}
		Logger::logUserActivity($activity,'high');
		SMS::broadcastSMS($numbers,$textMessage,$alertType,$profileId);
	}

}