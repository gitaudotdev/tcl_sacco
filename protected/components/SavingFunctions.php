<?php

class SavingFunctions{

	public static function checkIfDuplicateRecordExists($user_id){
		$checkSql = "SELECT * FROM savingaccounts WHERE user_id=$user_id";
		$checker  = Savingaccounts::model()->findAllBySql($checkSql);
		return !empty($checker) ? 1 : 0;
	}

	public static function getAllUserSavingAccounts($user_id){
		$savingSql      = "SELECT * FROM savingaccounts WHERE user_id=$user_id";
		$savingAccounts = Savingaccounts::model()->findAllBySql($savingSql);
		return !empty($savingAccounts) ? $savingAccounts : 0;
	}

	public static function getSavingAccountBalance($savingaccount_id){
		$savingDeposits    = SavingFunctions::getTotalSavingAccountDeposits($savingaccount_id);
		$savingWithdrawals = SavingFunctions::getTotalSavingAccountWithdrawals($savingaccount_id);
		$totalSavings =$savingDeposits-$savingWithdrawals;
		return $totalSavings < 0 ? 0: $totalSavings;
	}

	public static function getTotalSavingAccountDeposits($savingaccount_id){
		$depositTransactionSql = "SELECT COALESCE(SUM(amount),0) AS amount FROM savingtransactions WHERE type='credit'
		AND savingaccount_id=$savingaccount_id AND is_void='0'";
		$deposits  = Savingtransactions::model()->findBySql($depositTransactionSql);
		return !empty($deposits) ? $deposits->amount : 0;
	}

	public static function getTotalSavingAccountWithdrawals($savingaccount_id){
		$withdrawTransactionSql="SELECT COALESCE(SUM(amount),0) AS amount FROM savingtransactions
		WHERE type='debit' AND savingaccount_id=$savingaccount_id AND is_void='0'";
		$withdrawals=Savingtransactions::model()->findBySql($withdrawTransactionSql);
		return !empty($withdrawals) ? $withdrawals->amount : 0;
	}


	public static function getSavingAccountCreditAccruedInterest($savingaccount_id){
		$transactQuery = "SELECT SUM(savingpostings.posted_interest) as posted_interest FROM savingpostings,savingtransactions,savingaccounts WHERE savingtransactions.savingtransaction_id=savingpostings.savingtransaction_id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.savingaccount_id=$savingaccount_id
		AND savingpostings.type='credit'";
		$accruals = Savingpostings::model()->findBySql($transactQuery);
		return !empty($accruals) ? $accruals->posted_interest : 0;
	}

	public static function getSavingAccountDebitAccruedInterest($savingaccount_id){
		$transactQuery="SELECT SUM(savingpostings.posted_interest) as posted_interest FROM savingpostings,savingtransactions,savingaccounts WHERE savingtransactions.savingtransaction_id=savingpostings.savingtransaction_id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.savingaccount_id=$savingaccount_id
		AND savingpostings.type='debit'";
		$accruals=Savingpostings::model()->findBySql($transactQuery);
		return !empty($accruals) ? $accruals->posted_interest : 0;
	}

	public static function getSavingAccountAccruedInterest($savingaccount_id){
		$accruedInterestCredits = SavingFunctions::getSavingAccountCreditAccruedInterest($savingaccount_id);
		$accruedInterestDebits  = SavingFunctions::getSavingAccountDebitAccruedInterest($savingaccount_id);
		$totalAccruedInterests  = $accruedInterestCredits- $accruedInterestDebits;
		return $totalAccruedInterests <=0 ? 0 : $totalAccruedInterests;
	}

	public static function getTotalSavingAccountBalance($savingaccount_id){
		return SavingFunctions::getSavingAccountBalance($savingaccount_id);
	}

	public static function getAllSavingAccountTransactions($savingaccount_id){
		$transactionSql="SELECT * FROM savingtransactions WHERE savingaccount_id=$savingaccount_id AND is_void='0'
		ORDER BY savingtransaction_id DESC";
		return Savingtransactions::model()->findAllBySql($transactionSql);
	}

	public static function displaySavingAccountsFourPerRow($savingaccounts){
		if(!empty($savingaccounts)){
			foreach($savingaccounts as $savingaccount){
				echo '<div class="col-md-3 col-lg-3 col-sm-12">
					<div class="form-check">
						<label class="form-check-label">
							<input class="form-check-input" type="checkbox" name="savingaccounts[]" value="';echo $savingaccount['savingaccount_id']; echo'" checked="checked">
							<span class="form-check-sign"></span>';
							echo $savingaccount['account_number']; echo'
						</label>
					</div>
				</div>';
			}
		}
	}

	public static function recordBulkTransactions($data){
		if(!empty($data['savingaccounts'])){
			foreach($data['savingaccounts'] as $savingaccount){
				$transaction=new Savingtransactions;
				$transaction->savingaccount_id=$savingaccount;
				$transaction->amount=$data['amount'];
				$transaction->type=$data['type'];
				$transaction->description=$data['description'];
				$transaction->transacted_by=$data['transacted_by'];
				$transaction->transacted_at=date('Y-m-d H:i:s');
				$transaction->save();
			}
			$status=1;
		}else{
			$status=0;
		}
		return $status;
	}

	public static function getAllUsersList(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$usersQuery = "SELECT DISTINCT(profiles.id), profiles.firstName, profiles.lastName FROM profiles,auths WHERE profiles.id=auths.profileId
		AND auths.authStatus IN('ACTIVE')";
		switch(Yii::app()->user->user_level){
			case '0':
			$usersQuery.="";
			break;

			case '1':
			$usersQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$usersQuery.=" AND profiles.branchId=$userBranch";
			break;
		}
		$usersQuery.=" ORDER BY profiles.firstName,profiles.lastName ASC";
		return Profiles::model()->findAllBySql($usersQuery);
	}

	public static function getAllBranchUsersList($branchID){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$usersQuery = "SELECT DISTINCT(profiles.id), profiles.firstName, profiles.lastName FROM profiles,auths WHERE profiles.id=auths.profileId
		AND auths.authStatus IN('ACTIVE') AND profiles.branchId=$branchID";
		switch(Yii::app()->user->user_level){
			case '0':
			$usersQuery.="";
			break;

			case '1':
			$usersQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$usersQuery.=" AND profiles.branchId=$userBranch";
			break;
		}
		$usersQuery.=" ORDER BY profiles.firstName,profiles.lastName ASC";
		return Profiles::model()->findAllBySql($usersQuery);
	}

	public static function getAllRelationManagerUsersList($manager){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$usersQuery = "SELECT DISTINCT(profiles.id), profiles.firstName, profiles.lastName FROM profiles,auths WHERE profiles.id=auths.profileId
		AND auths.authStatus IN('ACTIVE') AND profiles.managerId=$manager";
		switch(Yii::app()->user->user_level){
			case '0':
			$usersQuery.="";
			break;

			case '1':
			$usersQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$usersQuery.=" AND profiles.managerId=$userID";
			break;
		}
		$usersQuery.=" ORDER BY profiles.firstName,profiles.lastName ASC";
		return Profiles::model()->findAllBySql($usersQuery);
	}

	public static function LoadFilteredSavingAccounts($branch,$rm,$clientID,$accountNumber,$start_date,$end_date,$status){
		$savingQuery="SELECT DISTINCT(savingaccounts.savingaccount_id),savingaccounts.interest_rate,savingaccounts.account_number,savingaccounts.user_id,savingaccounts.is_approved FROM savingaccounts,users
		WHERE profiles.id=savingaccounts.user_id AND (DATE(savingaccounts.created_at) BETWEEN '$start_date' AND '$end_date')";
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		switch(Yii::app()->user->user_level){
			case '0':
			$savingQuery.="";
			break;

			case '1':
			$savingQuery.=" AND profiles.branchId=$userBranch";
			break;

			case '2':
			$savingQuery.=" AND profiles.managerId=$userID";
			break;

			case '3':
			$savingQuery.=" AND profiles.id=$userID";
			break;
		}
		echo SavingFunctions::getFilteredSavingAccounts($branch,$rm,$clientID,$accountNumber,$savingQuery,$status);
	}

	public static function getFilteredSavingAccounts($branch,$rm,$clientID,$accountNumber,$savingQuery,$status){
		if($branch !=0){
			$savingQuery.=" AND profiles.branchId=$branch";
		}
		if($rm !=0){
			$savingQuery.=" AND profiles.managerId=$rm";
		}
		if($clientID !=0){
			$savingQuery.=" AND profiles.id=$clientID";
		}
		if($accountNumber != '0'){
			$savingQuery.=" AND savingaccounts.account_number='$accountNumber'";
		}
		if($status != 'niemals'){
			$formattedStatus=(int)$status;
			$savingQuery.=" AND savingaccounts.is_approved='$formattedStatus'";
		}

		$savingQuery.=" ORDER BY savingaccounts.savingaccount_id DESC";
		$savings=Savingaccounts::model()->findAllBySql($savingQuery);
		echo abulate::createSavingAccountDetailsTable($savings);
	}

	/**************************
		ACCOUNTS TRANSACTIONS
	***************************/

	public static function getUserRunningLoanAccounts(){
		$accountQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10')";
		return Loanaccounts::model()->findAllBySql($accountQuery);
	}

	public static function getAuthorizingStaff(){
		$userID    = Yii::app()->user->user_id;
		$authQuery = "SELECT * FROM profiles WHERE id<>$userID AND id IN(SELECT profileId FROM auths WHERE level IN('SUPERADMIN','ADMIN','STAFF'))";
		return Profiles::model()->findAllBySql($authQuery);
	}


	public static function getAccountUser($accountID){
		$account = Savingaccounts::model()->findByPk($accountID);
		return Profiles::model()->findByPk($account->user_id);
	}

	public static function checkIfUserHasRunningLoan($accountID){
		$user = SavingFunctions::getAccountUser($accountID);
		if(!empty($user)){
			$accountStatus = LoanApplication::restrictMultipleActiveAccounts($user->id) == 0 ? 1 : 2;
		}else{
			$accountStatus=0;
		}
		return $accountStatus;
	}

	/****************************************************

		0: No Account Holder , 1: Success, 2: Running Loan

		3: Insufficient Funds, 4: Failed, 5: Insufficient Accruals

	*****************************************************************/
	public static function requestWithdrawal($accountID,$amount,$reason,$approver,$type){
		$savingAccount   = Savingaccounts::model()->findByPk($accountID);
		$accountHolder   = $savingAccount->SavingAccountHolderName;
		$amountFormatted = CommonFunctions::asMoney($amount);
		$openingBalance  = $savingAccount->opening_balance;
		$accountBalance  = SavingFunctions::getSavingAccountBalance($accountID);
		$totalBalance    = $accountBalance - $openingBalance;
		$totalAccrued    = SavingFunctions::getSavingAccountAccruedInterest($accountID);
		$user            = SavingFunctions::getAccountUser($accountID);
		$phoneNumber     = ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
		$account         = SavingFunctions::checkIfUserHasRunningLoan($accountID);
		$numbers   = array();
		$alertType = '17';
		switch($account){
			case 0:
			$accountStatus=0;
			break;

			case 1:
			if($type == '0'){
				if($totalBalance > $amount){
					if(SavingFunctions::createWithdrawalRecord($accountID,$user,$amount,$type,$reason,$approver) == 1){
						$message = " Request submitted for withdrawal of $amountFormatted from your savings.\nContact your manager for more info.\nThank you!";
						$textMessage = "Dear ".$user->firstName.",".  $message;
						array_push($numbers,$phoneNumber);
						SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id);
						Logger::logUserActivity("Requested withdrawal of Ksh. $amountFormatted for $accountHolder",'high');
						$accountStatus=1;
					}else{
						$accountStatus=4;
					}
				}else{
					$accountStatus=3;
				}
			}else{
				if($totalAccrued > $amount){
					if($totalBalance > $amount){
						if(SavingFunctions::createWithdrawalRecord($accountID,$user,$amount,$type,$reason,$approver) == 1){
							$message = " Request submitted for withdrawal of $amountFormatted from your savings.\nContact your manager for more info.\nThank you!";
							$textMessage = "Dear ".$user->firstName.",".  $message;
							array_push($numbers,$phoneNumber);
							SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id);
							Logger::logUserActivity("Requested withdrawal of accrued interest worth Ksh. $amountFormatted for $accountHolder",'high');
							$accountStatus=1;
						}else{
							$accountStatus=4;
						}
					}else{
						$accountStatus=3;
					}
				}else{
					$accountStatus=5;
				}
			}
			break;

			case 2:
			$accountStatus=2;
			break;
		}
		return $accountStatus;
	}

	public static function createWithdrawalRecord($accountID,$user,$amount,$type,$reason,$approver){
		$request = new Withdrawals;
		$request->savingaccount_id=$accountID;
		$request->user_id=Yii::app()->user->user_id;
		$request->branch_id=$user->branchId;
		$request->amount=$amount;
		$request->type=$type;
		$request->withdrawal_reason=$reason;
		$request->approver=$approver;
		$request->created_at=date('Y-m-d H:i:s');
		if($request->save()){
			$savingAccount=Savingaccounts::model()->findByPk($accountID);
			$accountHolder=$savingAccount->SavingAccountHolderName;
			$amountFormatted=CommonFunctions::asMoney($amount);
			$user=Profiles::model()->findByPk($approver);
			$phoneNumber= ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
			$numbers=array();
			$alertType='17';
			$message = "Request submitted for withdrawal of $amountFormatted from savings account of $accountHolder .\nLogin and authorize the transaction.\nThank you!";
			$textMessage = "Dear ".$user->firstName.",".  $message;
			array_push($numbers,$phoneNumber);
			SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id);
			$creationStatus=1;
		}else{
			$creationStatus=0;
		}
		return $creationStatus;
	}

	public static function approveWithdrawalRequest($requestID,$reason){
		$request= Withdrawals::model()->findByPk($requestID);
		if(!empty($request)){
			//Essential Details
			$amount          = $request->amount;
			$amountFormatted = CommonFunctions::asMoney($amount);
			$accountID       = $request->savingaccount_id;
			$savingAccount   = Savingaccounts::model()->findByPk($accountID);
			$accountHolder   = $savingAccount->SavingAccountHolderName;
			$openingBalance  = $savingAccount->opening_balance;
			$accountBalance  = SavingFunctions::getSavingAccountBalance($accountID);
			$totalBalance    = $accountBalance - $openingBalance;
			if($totalBalance > $amount){
				$user              = SavingFunctions::getAccountUser($accountID);
				$clientFirstName   = strtoupper($user->firstName);
				$fullName          = $user->ProfileFullName;
				$clientPhoneNumber = $savingAccount->account_number;
				$phoneNumber       = '254'.substr($clientPhoneNumber, -9);
				//B2C MPESA
				$commandID         = 'BusinessPayment';
				$remarks           = "$accountHolder savings Withdrawal of: ".$amountFormatted;
				$transtatus        = LoanManager::B2CTransaction($commandID,$amount,$phoneNumber,$remarks);
				switch($transtatus){
					case 0:
					$approvalStatus=3;
					break;
		
					case 1:
					$request->is_approved='1';
					$request->date_authorized=date('Y-m-d');
					$request->authorization_reason=$reason;
					if($request->save()){
						$requestType=$request->type;
						$user=SavingFunctions::getAccountUser($accountID);
						$numbers=array();
						$alertType='17';
						switch($requestType){
							case '0':
							$desc="Authorized funds withdrawal worth KES $amountFormatted";
							$type='debit';
							SavingFunctions::createTransactionRecord($accountID,$amount,$type,$desc);
							break;
		
							case '1':
							SavingFunctions::withdrawAccruedInterests($accountID,$amount);
							break;
						}
						$message = "Request approved and disbursed for withdrawal of $amountFormatted from your savings.\nThank you!";
						$textMessage = "Dear ".$clientFirstName.",".  $message;
						array_push($numbers,$clientPhoneNumber);
						SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id);
						Logger::logUserActivity("Approved and disbursed withdrawal of <strong>Ksh. $amountFormatted for $accountHolder</strong>",'high');
						$approvalStatus=1;
					}else{
						$approvalStatus=0;
					}
					break;
		
					default:
					$approvalStatus=$transtatus;
					break;
				}
			}else{
				$approvalStatus=0;
			}
		}else{
			$approvalStatus=0;
		}
		return $approvalStatus;
	}

	public static function rejectWithdrawalRequest($requestID,$reason){
		$request= Withdrawals::model()->findByPk($requestID);
		if(!empty($request)){
			$request->is_approved='2';
			$request->date_authorized=date('Y-m-d');
			$request->authorization_reason=$reason;
			if($request->save()){
				$amount=$request->amount;
				$amountFormatted=CommonFunctions::asMoney($amount);
				$accountID=$request->savingaccount_id;
				$savingAccount=Savingaccounts::model()->findByPk($accountID);
				$accountHolder=$savingAccount->SavingAccountHolderName;
				$user=SavingFunctions::getAccountUser($accountID);
				$phoneNumber= ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
				$numbers=array();
				$alertType='17';
				$message = "Request rejected for withdrawal of $amountFormatted from your savings.\nContact your manager for more info.\nThank you!";
				$textMessage = "Dear ".$user->firstName.",".  $message;
				array_push($numbers,$phoneNumber);
				SMS::broadcastSMS($numbers,$textMessage,$alertType,$user->id);
				Logger::logUserActivity("Rejected withdrawal of Ksh. $amountFormatted for $accountHolder",'high');
				$rejectionStatus=1;
			}else{
				$rejectionStatus=0;
			}
		}else{
			$rejectionStatus=0;
		}
		return $rejectionStatus;
	}

	public static function createTransactionRecord($accountID,$amount,$type,$desc){
		$transactedPhone=Savingaccounts::model()->findByPk($accountID)->account_number;
		$transaction=new Savingtransactions;
		$transaction->savingaccount_id=$accountID;
		$transaction->amount=$amount;
		$transaction->type=$type;
		$transaction->description=$desc;
		$transaction->phone_transacted=$transactedPhone;
		$transaction->transacted_by=Yii::app()->user->user_id;
		$transaction->transacted_at=date('Y-m-d H:i:s');
		if($transaction->save()){
			$transactionID=$transaction->savingtransaction_id;
		}else{
			$transactionID=0;
		}
		return $transactionID;
	}

	public static function createTransactionPosting($transactionID,$amount,$type){
		$posting= new Savingpostings;
		$posting->savingtransaction_id=$transactionID;
		$posting->posted_interest=$amount;
		$posting->type=$type;
		$posting->save();
	}

	public static function withdrawAccruedInterests($accountID,$amount){
		$amountFormatted=CommonFunctions::asMoney($amount);
		$desc="Withdrawal of accrued interests worth KES $amountFormatted";
		$type='debit';
		$transactionID=SavingFunctions::createTransactionRecord($accountID,$amount,$type,$desc);
		if($transactionID >0){
			SavingFunctions::createTransactionPosting($transactionID,$amount,$type);
		}
	}

	public static function voidAccountAccruedInterests($accountID){
		$postingQuery="SELECT * FROM savingpostings,savingtransactions WHERE savingpostings.savingtransaction_id=savingtransactions.savingtransaction_id AND savingtransactions.savingaccount_id=$accountID AND savingpostings.is_withdrawn='0'";
		$postings=Savingpostings::model()->findAllBySql($postingQuery);
		if(!empty($postings)){
			foreach($postings AS $posting){
				SavingFunctions::voidSavingTransaction($posting->savingtransaction_id);
				$posting->is_void='1';
				$posting->save();
			}
		}
	}

	public static function voidaccruedInterest($transactionID){
		$postingQuery="SELECT * FROM savingpostings WHERE savingtransaction_id=$transactionID LIMIT 1";
		$posting=Savingpostings::model()->findBySql($postingQuery);
		if(!empty($posting)){
			$posting->is_void='1';
			$posting->save();
			$voidPostingStatus=1;
		}else{
			$voidPostingStatus=0;
		}
		return $voidPostingStatus;
	}

	public static function voidSavingTransaction($transactionID){
		$transaction=Savingtransactions::model()->findByPk($transactionID);
		if(!empty($transaction)){
			$transaction->is_void='1';
			$transaction->save();
		}
	}
	/****************************************************
		
		0: Failed Funds Transfer, 1: Success Funds Transfer

		2: Insufficient Funds, 3: Repayment Unsuccessful

	**********************************************************/
	public static function transferFundsToLoanAccount($accountID,$loanAccountID,$amount){
		$type='debit';
		$desc='Transfer as Loan Repayment for an active Loan Account';
		$savingAccount=Savingaccounts::model()->findByPk($accountID);
		$accountHolder=$savingAccount->SavingAccountHolderName;
		$amountFormatted=CommonFunctions::asMoney($amount);
		$openingBalance=$savingAccount->opening_balance;
		$accountBalance=SavingFunctions::getSavingAccountBalance($accountID);
		$totalBalance=$accountBalance - $openingBalance;
		$totalAccrued=SavingFunctions::getSavingAccountAccruedInterest($accountID);
		$user=SavingFunctions::getAccountUser($accountID);
		$phoneNumber=ProfileEngine::getProfileContactByTypeOrderDesc($user->id,'PHONE');
		$loanaccount=Loanaccounts::model()->findByPk($loanAccountID);
		$loanAccountNumber = $loanaccount->account_number;
		$loanAccountHolder=$loanaccount->BorrowerFullName;
		$numbers=array();
		if($totalBalance > $amount){
			$transactionID=SavingFunctions::createTransactionRecord($accountID,$amount,$type,$desc);
			if($transactionID > 0){
				if(LoanManager::repayLoanAccount($loanAccountID,$amount,'0',$phoneNumber) == 1){
					$message = " Transfer of Kshs $amountFormatted/- from your savings account, to pay for an active Loan Account $loanAccountNumber for $loanAccountHolder is successful.\nThank you!";
					$textMessage = "Dear ".$user->firstName.",".  $message;
					array_push($numbers,$phoneNumber);
					SMS::broadcastSMS($numbers,$textMessage,'19',$user->id);
					Logger::logUserActivity("Transfer funds worth Ksh. $amountFormatted from $accountHolder as loan repayment to loan account: $loanAccountNumber for $loanAccountHolder",'urgent');
					$transferStatus=1;
				}else{
					SavingFunctions::voidSavingTransaction($transactionID);
					$transferStatus=3;
				}
			}else{
				$transferStatus=0;
			}
		}else{
			$transferStatus=2;
		}
		return $transferStatus;
	}

}