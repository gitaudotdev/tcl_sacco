<?php
/**********************

	COMMON FUNCTIONS

*********************************/
function asMoney($value){
	return number_format($value,2);
}

function getMonthName($monthNumber){
	return ucfirst(date("F", mktime(0, 0, 0, $monthNumber, 10)));
}

function logUserActivity($activity,$severity,$conn){
  $logQuery = "INSERT INTO logs (user_id,branch_id,activity,severity) VALUES(0,0,'$activity','$severity')";
  echo $conn->query($logQuery) ? "Activity Logged. \n" : "No Log recorded. \n";
}

function getOrganizationDetails($conn){
	$organizationQuery = "SELECT * FROM organization WHERE organization_id=1";
	$result            = $conn->query($organizationQuery);
	if(!empty($result)) {
		while($row = $result->fetch_assoc()){
			$orgArray[] = $row;
		}
		return $orgArray;
	}else{
		return 0;
	}
}

function getProfileRecentSettingByType($conn,$profileId,$configType){
	$settingQuery = "SELECT * FROM account_settings WHERE profileId=$profileId AND configType='$configType' AND configActive='ACTIVE'
    ORDER BY id DESC LIMIT 1";
	$settings     = $conn->query($settingQuery);
	if(!empty($settings)) {
		while($row = $settings->fetch_assoc()){
			$settingArray[] = $row;
		}
		return $settingArray;
	}else{
		return 0;
	}
}

function getProfilePayrollStaff($conn){
	$staffQuery  = "SELECT * FROM profiles WHERE profileType IN('STAFF') AND
	id IN(SELECT profileId FROM account_settings WHERE configType='PAYROLL_LISTED' AND configValue='ACTIVE')
	ORDER BY firstName,lastName ASC";
	$members     = $conn->query($staffQuery);
	if(!empty($members)) {
		while($row = $members->fetch_assoc()){
			$memberArray[] = $row;
		}
		return $memberArray;
	}else{
		return 0;
	}
}

function getProfileContactByTypeOrderDesc($conn,$profileId,$contactType){
	$contactQuery = "SELECT contactValue FROM contacts WHERE profileId=$profileId AND contactType='$contactType' AND isPrimary=1 LIMIT 1";
	$contacts     = $conn->query($contactQuery);
	if(!empty($contacts)) {
		while($row = $contacts->fetch_assoc()){
			$contactArray[] = $row;
		}
		return $contactArray;
	}else{
		return 0;
	}
}

function determinePerformanceMeaning($percentPerformance){
	if($percentPerformance > 0 && $percentPerformance <= 50){
		$meaning = 'Below Average';
	}else if($percentPerformance > 50 && $percentPerformance <= 75){
		$meaning = 'Fair';
	}else if($percentPerformance > 75 && $percentPerformance <= 100){
		$meaning = 'Good';
	}else if($percentPerformance > 100){
		$meaning = 'Very Good';
	}else if($percentPerformance <= 0){
		$meaning = 'Below Average';
	}
	return $meaning;
}

function getPerformancePercentage($target,$achieved){
	return $target <= 0 ? number_format(1/$target,2) : number_format(($achieved/$target) * 100,2);
}

function calculateInterestEarned($accountBalance,$interestRate){
	$dailyInterest  = $interestRate /30;
	$interestEarned = ($dailyInterest /100) * $accountBalance;
	return $interestEarned <= 0 ? 0 : $interestEarned;
}

function calculateDailyAccrualInterest($loanaccountID,$interestRate,$conn){
	$currentMonthTotalDays = 30; 	
	$dailyInterestRate     = ($interestRate/$currentMonthTotalDays) /100;
	$principalBalance      = getLoanPrincipalBalance($conn,$loanaccountID);
	$dailyInterest         = $dailyInterestRate * $principalBalance;
	return round($dailyInterest);
}

function getDateDifference($repaymentDate,$today){
	$dateToRepay    = new DateTime($repaymentDate);
	$currentDate    = new DateTime($today);
	$difference     = $dateToRepay->diff($currentDate);
	$differenceDays = (int)$difference->format('%R%a');
	return $differenceDays <= 0 ? 0 : (int)$differenceDays;
}

function getDifference($startDate,$endDate){
	$daysDifference = $endDate - $startDate;
	return $daysDifference <= 0 ? 0 : (int)$daysDifference;
}


function getSTKPushRecord($conn,$merchantRequestID){
    $stkQuery = "SELECT * FROM stkPush WHERE merchantRequestId='$merchantRequestID'";
    $result   = $conn->query($stkQuery);
    if(!empty($result)) {
        $stkArray = [];
        while($row = $result->fetch_assoc()){
            $stkArray[] = $row;
        }
        return $stkArray;
    }else{
        return 0;
    }
}

function getSTKPushRecordByAccount($conn,$clientAccount){
    $stkQuery = "SELECT * FROM stkPush WHERE accountNumber='$clientAccount'";
    $result   = $conn->query($stkQuery);
    if(!empty($result)) {
        $stkArray = [];
        while($row = $result->fetch_assoc()){
            $stkArray[] = $row;
        }
        return $stkArray;
    }else{
        return 0;
    }
}

/**********************

	SMS NOTIFICATIONS

*********************************/
function IsAutomatedAlertsDisabled($conn,$type){
	$alertQuery = "SELECT * FROM alert_configs WHERE type='$type' AND is_active='0'";
	$alert      = $conn->query($alertQuery);
	return $alert->num_rows > 0 ? 1 : 0;
}

function sendNotification($message,$phoneNumber,$conn,$type,$profileId){
	$recipients = $phoneNumber;
	$gateway    = new AfricasTalkingGateway(AS_USER,AS_KEY);
	switch(IsAutomatedAlertsDisabled($conn,$type)){
		case 0:
		try{ 
			$userArray = getAccountHolder($conn,$profileId);
			if($userArray !=0 ){
				foreach($userArray AS $user){
					$profileId    = $user['id'];
					$branchId     = $user['branchId'];
					$managerId    = $user['managerId'];
				}
				$results = $gateway->sendMessage($recipients,$message,AS_FROM);
				foreach($results as $result){
					$messageID   = $result->messageId;
					$phoneNumber = $result->number;
					$cost        = $result->cost;
					$alertQuery  = "INSERT INTO sms_alerts (message_id,phone_number,cost,message,sent_by,profileId,branchId,managerId)
					VALUES('$messageID','$phoneNumber','$cost','$message',1,'$profileId','$branchId','$managerId')";
					$conn->query($alertQuery);
				}
			}else{
				echo "No profile matching provided id was found...";
			}
		}catch(AfricasTalkingGatewayException $e ){
		  $result = "Encountered an error while sending: ".$e->getMessage()." \n";
		  echo $result;
		}
		break;

		case 1:
		echo "Automated Alerts are deactivated...";
		break;
	}
}
/**********************

	LOAN DETAILS

*********************************/
function getAccountHolder($conn,$userID){
	$userSQL = "SELECT * FROM profiles WHERE id=$userID";
	$result  = $conn->query($userSQL);
	if(!empty($result)) {
		while($row = $result->fetch_assoc()){
			$userArray[] = $row;
		}
		return $userArray;
	}else{
		return 0;
	}
}

function getLoanBalance($loanaccount_id,$conn){
	$principalBalance = getLoanPrincipalBalance($conn,$loanaccount_id);
	$interestBalance  = getUnpaidAccruedInterest($loanaccount_id,$conn);
	$accruedPenalties = getUnpaidAccruedPenalty($loanaccount_id,$conn);
	$loanBalance      = $principalBalance + $interestBalance + $accruedPenalties;
	$totalAmount      = getAmountWrittenOff($loanaccount_id,$conn);
	return $totalAmount >= $loanBalance ? 0 : $loanBalance;
}

function getUnpaidLoanInterestBalance($accountID,$conn){
  $debitInterestAmount  = getDebitInterestAmount($accountID,$conn);
  $creditInterestAmount = getCreditInterestAmount($accountID,$conn);
  $balance              = $debitInterestAmount-$creditInterestAmount;
  return $balance <= 0 ? 0 : $balance;
}

function getDebitInterestAmount($accountID,$conn){
  $debitQuery = "SELECT COALESCE(SUM(interest_accrued),0) AS interest_accrued FROM loaninterests WHERE loanaccount_id=$accountID
  AND transaction_type='debit' AND is_paid='0'";
  $totalDebit = $conn->query($debitQuery);
  if($totalDebit->num_rows > 0) {
		while($row = $totalDebit->fetch_assoc()){
			$debitInterestAmount=$row['interest_accrued'];
		}
	}else{
    $debitInterestAmount=0;
  }
  return $debitInterestAmount;
}

function getCreditInterestAmount($accountID,$conn){
  $creditQuery="SELECT COALESCE(SUM(interest_accrued),0) AS interest_accrued FROM loaninterests WHERE loanaccount_id=$accountID
  AND transaction_type='credit' AND is_paid='1'";
  $totalCredit=$conn->query($creditQuery);
  if($totalCredit->num_rows > 0) {
		while($row = $totalCredit->fetch_assoc()){
			$creditInterestAmount=$row['interest_accrued'];
		}
	}else{
    $creditInterestAmount=0;
  }
  return $creditInterestAmount;
}

function getAmountWrittenOff($accountID,$conn){
  $totalQuery = "SELECT COALESCE(SUM(write_offs.amount),0) AS amount FROM write_offs WHERE write_offs.loanaccount_id=$accountID
  AND write_offs.type != 'Interest Accrued'";
  $total     = $conn->query($totalQuery);
  if($total->num_rows > 0) {
	while($row = $total->fetch_assoc()){
		$totalAmount = $row['amount'];
	}
  }else{
	$totalAmount = 0;
  }
  return $totalAmount;
}

function getLoanPrincipalBalance($conn,$accountID){
	$disburseQuery  = "SELECT COALESCE(SUM(amount_disbursed),0) AS amount_disbursed FROM disbursed_loans WHERE loanaccount_id=$accountID";
	$totalDisbursed = $conn->query($disburseQuery);
	if(!empty($totalDisbursed)) {
		while($row = $totalDisbursed->fetch_assoc()){
			$amountDisbursed = $row['amount_disbursed'];
		}
		$principalPaid    = getTotalPrincipalPaid($conn,$accountID);
		$principalBalance = $amountDisbursed - $principalPaid;
	}else{
		$principalBalance = 0;
	}
	return $principalBalance;
} 

function getTotalPrincipalPaid($conn,$loanaccount_id){
	$principalSql = "SELECT COALESCE(SUM(principal_paid),0) AS principal_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void IN('0','4')";
	$loanaccount  = $conn->query($principalSql);
	if($loanaccount->num_rows > 0) {
		while($row = $loanaccount->fetch_assoc()){
			$principalPaid = $row['principal_paid'];
		}
	}else{
		$principalPaid = 0;
	}
	return $principalPaid;
}

function getUnpaidAccruedInterest($accountID,$conn){
  return getUnpaidLoanInterestBalance($accountID,$conn);
}

function voidAccruedInterest($accountID,$conn){
	$interestQuery = "UPDATE loaninterests SET is_paid='1' WHERE loanaccount_id=$accountID AND is_paid='0'";
	return $conn->query($interestQuery) ? 1 : 0;
}

function recordAccruedInterest($accountID,$amount,$conn,$transactionType,$paymentStatus){
  $accrueQuery = "INSERT INTO loaninterests (loanaccount_id,interest_accrued,transaction_type,is_paid) VALUES('$accountID','$amount','$transactionType','$paymentStatus')";
  echo $conn->query($accrueQuery) ?  "Accrual Interest Balance Recorded. \n" : "No Interest Balance recorded. \n";
}

function getUnpaidAccruedPenalty($accountID,$conn){
	$penaltyQuery = "SELECT COALESCE(SUM(penalty_amount),0) AS penalty_amount FROM penaltyaccrued WHERE loanaccount_id=$accountID AND is_paid='0'";
	$penalty      = $conn->query($penaltyQuery);
	if($penalty->num_rows > 0){
		while($row = $penalty->fetch_assoc()){
			$accruedPenalty=$row['penalty_amount'];
		}
	}else{
		$accruedPenalty=0;
	}
	return $accruedPenalty;
}

function voidCurrentPenaltyRecords($accountID,$conn){
	$voidQuery = "UPDATE penaltyaccrued SET is_paid='1' WHERE loanaccount_id=$accountID AND is_paid='0'";
	return $conn->query($voidQuery) ? 1 : 0;
}

/******************
	
	B2C OPS

************************/
function getMostRecentAuthToken($conn){
  $authQuery = "SELECT * FROM apitokens WHERE status=1 ORDER BY id DESC LIMIT 1";
  $result    = $conn->query($authQuery);
  if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$authRecord[] = $row;
		}
  }else{
	 $authRecord=0;
  }
  return $authRecord;
}

function insertNewAuthToken($conn,$authToken){
  $authTokenQuery = "INSERT INTO apitokens (auth_token) VALUES ('$authToken')";
  return $conn->query($authTokenQuery) ? 1000 : 1001;
}

function expireRecentAuthToken($conn,$authID){
	$expireQuery = "UPDATE apitokens SET status=0 WHERE id=$authID";
	return $conn->query($expireQuery) ? 1000 : 10001;
}

function getB2CAccountBalance($conn){
	$authRecords=getMostRecentAuthToken($conn);
	if($authRecords !=0){
		foreach($authRecords AS $record){
			$authToken=$record['auth_token'];
		}
	  $url = X_ACCOUNTBALANCE_URL;
	  $curl = curl_init();
	  curl_setopt($curl, CURLOPT_URL, $url);
	  curl_setopt($curl,CURLOPT_HTTPHEADER,array("Content-Type:application/json","Authorization:Bearer $authToken")); 
	  $curl_post_data = array(
	    'Initiator' => X_BUSINESSCONSUMER_INITIATOR_NAME,
	    'SecurityCredential' => X_CONSUMERSECURITY_KEY,
	    'CommandID' => 'AccountBalance',
	    'PartyA' => X_BUSINESSCONSUMER_SHORTCODE,
	    'IdentifierType' => '4',
	    'Remarks' => 'Querying Account Balance ',
	    'QueueTimeOutURL' => X_QUEUETIMEOUT_URL,
	    'ResultURL' => X_CONSUMERAPIRESULTS_URL
	  );
	  $data_string = json_encode($curl_post_data);
	  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($curl, CURLOPT_POST, true);
	  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	  $curl_response = curl_exec($curl);
	  $balanceResponse=json_decode($curl_response,true);
	  if(!empty($balanceResponse)){
	    $balResponseCode=(int)$balanceResponse['ResponseCode'];
	    $balRequestID=$balanceResponse['ConversationID'];
	    $balOrigConverID=$balanceResponse['OriginatorConversationID'];
	    $balResponseDesc=$balanceResponse['ResponseDescription'];
	    if($balResponseCode === 0){
	      $balanceCheckStatus=saveCheckBalanceRecord($balRequestID,$balOrigConverID,
	        $balResponseCode,$balResponseDesc,$conn);
	    }else{
	      $balanceCheckStatus=1001;
	    }
	  }else{
	    $balanceCheckStatus=1003;
	  }
	}else{
		$balanceCheckStatus=1003;
	}
  return $balanceCheckStatus;
}

function saveCheckBalanceRecord($requestID,$origConverID,$responseCode,$responseDesc,$conn){
  $createdAt = date('Y-m-d H:i:s');
  $saveBalanceCheckQuery = "INSERT INTO balancechecks (user_id,conversationID,originatorConversationID,responseCode,responseDesc,created_at)
	 VALUES(0,'$requestID','$origConverID','$responseCode','$responseDesc','$createdAt')";
  return $conn->query($saveBalanceCheckQuery) ? 1 : 0;
}

function updateCheckBalanceRecord($requestID,$resultType,$resultCode,$resultDesc,$transactionID,$workingAccount,$utilityAccount,$chargeAccount,$conn){
	$updateTime  = date('Y-m-d H:i:s');
	$updateBalanceQuery = "UPDATE balancechecks SET resultType='$resultType',resultCode='$resultCode',resultDesc='$resultDesc',transactionID='$transactionID',workingAccount='$workingAccount',utilityAccount='$utilityAccount',chargeAccount='$chargeAccount',updated_at='$updateTime' WHERE conversationID='$requestID'";
	return $conn->query($updateBalanceQuery) ? 1 : 0;
}

function B2CTransaction($commandID,$amountPaid,$phoneNumber,$remarks,$conn){
	$organization=getOrganizationDetails($conn);
	if($organization !=0 ){
		foreach($organization AS $org){
			$B2CAllowed=$org['enable_mpesa_b2c'];
		}
	}
  switch($B2CAllowed){
    case 'ENABLED':
    $authRecords=getMostRecentAuthToken($conn);
		if($authRecords !=0){
			foreach($authRecords AS $record){
				$authToken=$record['auth_token'];
			}
	    switch($authToken){
	      case 1250:
	      $paymentStatus=$authToken;
	      break;

	      default:
	      $url = X_BUSINESSCONSUMER_URL;
	      $curl = curl_init();
	      curl_setopt($curl, CURLOPT_URL, $url);
	      curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-Type:application/json","Authorization:Bearer $authToken"));
	      $curl_post_data = array(
	        'InitiatorName' =>X_BUSINESSCONSUMER_INITIATOR_NAME,
	        'SecurityCredential' =>X_CONSUMERSECURITY_KEY,
	        'CommandID' => $commandID,
	        'Amount' => $amountPaid,
	        'PartyA' => X_BUSINESSCONSUMER_SHORTCODE,
	        'PartyB' => '254'.substr($phoneNumber,-9),
	        'Remarks' => $remarks,
	        'QueueTimeOutURL' => X_QUEUETIMEOUT_URL,
	        'ResultURL' => X_CONSUMERAPIRESULTS_URL,
	        'Occasion' => ''
	      );
	      $data_string = json_encode($curl_post_data);
	      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	      curl_setopt($curl, CURLOPT_POST, true);
	      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
	      $curl_response = curl_exec($curl);
	      $transactionResponse=json_decode($curl_response,true);
	      if(!empty($transactionResponse)){
	        switch($transactionResponse['ResponseCode']){
	          case '0':
	          $paymentStatus=1;
	          $amountFormatted=asMoney($amountPaid);
	          $paymentActivity="Processed <strong>$commandID B2C M-PESA </strong>transaction of $amountFormatted, for phone number: $phoneNumber with remark: <strong>$remarks</strong>";
	          $activitySeverity='urgent';
	          logUserActivity($paymentActivity,$activitySeverity,$conn);
	          break;

	          default:
	          $paymentStatus=$transactionResponse['ResponseDescription'];
	          break;
	        }
	      }else{
	        $paymentStatus=0;
	      }
	      break;
	    }
	  }
    break;

    case 'DISABLED':
    $paymentStatus=2020;
    break;
  }
  return $paymentStatus;
}

/*****************
	
	PAYROLL

****************************/
function processPayroll($userID,$branchID,$salesCommision,$collectionsCommision,$totalLoan,$grossSalary,$netSalary,$payMonth,$payYear,$conn){
	$respectiveMonth = getMonthName((int)$payMonth);
	$payrollPeriod   = $respectiveMonth.'-'.$payYear;
	$userArray       = getAccountHolder($conn,$userID);
	switch(restrictDuplicatePayroll($userID,$payMonth,$payYear,$conn)){
		case 0:
		if($userArray !=0 ){
			foreach($userArray AS $user){
				$fullName    = strtoupper($user['firstName']).''.strtoupper($user['lastName']);
				$firstName   = strtoupper($user['firstName']);
			}
			$phones = getProfileContactByTypeOrderDesc($conn,$userID,'PHONE');
			foreach($phones AS $phone){
				$pNumber = $phone['contactValue'];
			}
			$phoneNumber = $pNumber ? "+254".substr($pNumber,-9) : 0;
			if($phoneNumber != 0){
				$commandID  = 'SalaryPayment';
				$remarks    = "Staff monthly salary payment for $fullName";
				$payStatus  = B2CTransaction($commandID,$netSalary,$phoneNumber,$remarks,$conn);
				switch($payStatus){
					case 0:
					$processStatus = 4;
					break;
		
					case 1:
					$transactionID = recordPayrollTransaction($userID,$netSalary,$conn);
					if($transactionID > 0){
						switch(recordPayrollLog($userID,$branchID,$salesCommision,$collectionsCommision,$totalLoan,$grossSalary,$netSalary,$payMonth,$payYear,$transactionID,$conn)){
							case 0:
							deleteTransaction($transactionID,$conn);
							$processStatus = 0;
							break;
		
							case 1:
							if($totalLoan > 0){
								checkOffLoan($userID,$totalLoan,$conn);
							}
							$message           = " Your ".$payrollPeriod." salary is now processed and disbursed.\nLoan if any, has been deducted.\nThank you!";
							$textMessage       = "Dear ".$firstName.",".$message;
							sendNotification($textMessage,$phoneNumber,$conn,'29',$userID);
							$processStatus     = 1;
							break;
						}
					}else{
						$processStatus = 3;
					}
					break;
		
					default:
					$processStatus = $payStatus;
					break;
				}
			}else{
				echo "Phone Number invalid or not provided<br>";
			}
		}else{
			echo "No User records available<br>";
		}
		break;

		case 1:
		$processStatus = 2;
		break;
	}
	return $processStatus;
}

function checkBoundedPayrollPeriod($payMonth,$payYear){
	$currentMonth = (int)date('m');
	$currentYear  = (int)date('Y');
	if($payYear > $currentYear){
		$boundStatus = 5;
	}else if($payYear < $currentYear){
		$boundStatus=4;
	}else if($payYear == $currentYear){
		if($payMonth > $currentMonth){
			$boundStatus = 5;
		}else if($payMonth < $currentMonth){
			$boundStatus = 4;
		}else if($payMonth == $currentMonth){
			$boundStatus = 1;
		}else{
			$boundStatus = 5;
		}
	}else{
		$boundStatus = 5;
	}
	return $boundStatus;
}

function restrictDuplicatePayroll($userID,$payMonth,$payYear,$conn){
	$duplicateQuery = "SELECT * FROM payroll WHERE user_id=$userID AND payroll_month=$payMonth AND payroll_year=$payYear";
	$records        = $conn->query($duplicateQuery);
	return $records->num_rows > 0 ? 1 : 0;
}

function recordPayrollTransaction($staffID,$netSalary,$conn){
	$payrollQuery = "INSERT INTO payroll_transactions (staff_id,amount,processed_by) VALUES('$staffID','$netSalary',0)";
	return $conn->query($payrollQuery) ? mysqli_insert_id($conn) : 0;
}

function recordPayrollLog($userID,$branchID,$salesCommision,$collectionsCommision,$totalLoan,$grossSalary,$netSalary,$payMonth,$payYear,$transactionID,$conn){
	$accrueQuery = "INSERT INTO payroll (user_id,branch_id,transaction_id,sales_commision,collections_commision,loan_paid,gross_salary,net_salary,payroll_month,payroll_year,processed_by)
	VALUES('$userID','$branchID','$transactionID','$salesCommision','$collectionsCommision','$totalLoan','$grossSalary','$netSalary','$payMonth','$payYear',0)";
	return $conn->query($accrueQuery) ? 1 : 0;
}

function deleteTransaction($transactionID,$conn){
	$voidPayrollQuery = "UPDATE payroll_transactions SET is_complete='1' WHERE id=$transactionID";
	return $conn->query($voidPayrollQuery) ? 1 : 0;
}

function checkOffLoan($userID,$totalLoan,$conn){
	$loanaccountID = getLoanAccount($conn,$userID);
	$userArray     = getAccountHolder($conn,$userID);
	if($userArray !=0 ){
		foreach($userArray AS $user){
			$clientAccount = $user['idNumber'];
			$firstname     = ucfirst($user['firstName']);
		}
		$phones = getProfileContactByTypeOrderDesc($conn,$userID,'PHONE');
		foreach($phones AS $phone){
			$pNumber = $phone['contactValue'];
		}
		$phoneNumber = $pNumber ? '+254'.substr($pNumber,-9) : 0;
		if($phoneNumber != 0){
			if($loanaccountID > 0){
				createLoanRepayment($conn,$loanaccountID,$totalLoan,$firstname,$clientAccount,$phoneNumber,$userArray);
			}
		}else{
			echo "Nothing to process for loan account<br>";
		}
	}
}

function getAccountUserID($conn,$clientAccount){
	$userSQL  = "SELECT * FROM profiles WHERE idNumber=$clientAccount";
	$userData = $conn->query($userSQL);
	if($userData->num_rows > 0) {
		while($row = $userData->fetch_assoc()){
			$userID = $row['id'];
		}
	}else{
		$userID = 0;
	}
	return $userID;
}

function getSavingAccountID($conn,$rawClientAccount){
	$accountNumber = '0'.$rawClientAccount;
	$savingQuery   = "SELECT * FROM savingaccounts WHERE account_number='$rawClientAccount' AND is_approved='1' LIMIT 1";
	$result        = $conn->query($savingQuery);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$accountID=$row['savingaccount_id'];
		}
	}else{
		$accountID = 0;
	}
	return $accountID;
}


function getSavingAccountUserID($conn,$savingAccountID){
	$savingQuery   = "SELECT * FROM savingaccounts WHERE savingaccount_id='$savingAccountID' LIMIT 1";
	$result        = $conn->query($savingQuery);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$userID=$row['user_id'];
		}
	}else{
		$userID = 0;
	}
	return $userID;
}

function createSavingTransaction($conn,$savingAccountID,$amount,$firstname,$rawClientAccount,$phoneNumber,$userID,$trx_id){
	$holder = getAccountHolder($conn,$userID);
	if($holder !== 0){
		foreach($holder AS $user){
			$profileId = $user['id'];
			$branchId  = $user['branchId'];
			$managerId = $user['managerId'];
		}
	}else{
		$profileId = null;
		$branchId  = null;
		$managerId = null;
	}
	$saveQuery = "INSERT INTO savingtransactions (savingaccount_id,amount,type,description,transacted_by,phone_transacted,profileId,branchId,managerId)
	 VALUES('$savingAccountID','$amount','credit','M-Pesa Paybill Member Savings Deposit',0,'$phoneNumber','$profileId','$branchId','$managerId')";
	if($conn->query($saveQuery)){
	  $message = "Thank you ".$firstname.", your savings of KES ".$amount." to account ".$rawClientAccount." is received. Your account is now updated. Thank you for saving with TCL.";
	  sendNotification($message,$phoneNumber,$conn,'18',$userID);
 	  echo "Saving successfully deposited... \n";
	}else{
 	  echo "Saving NOT deposited... \n";
	}
}

function getLoanAccount($conn,$userID){
	$loanQuery   = "SELECT * FROM loanaccounts WHERE user_id=$userID AND
	loan_status NOT IN('0','1','3','4','8','9','10') ORDER BY loanaccount_id DESC LIMIT 1";
	$loanaccount = $conn->query($loanQuery);
	if($loanaccount->num_rows > 0) {
		while($row = $loanaccount->fetch_assoc()){
			$loanaccountID = $row['loanaccount_id'];
		}
	}else{
		$loanaccountID = 0;
	}
	return $loanaccountID;
}

function createLoanRepayment($conn,$loanaccount_id,$amount,$firstname,$clientAccount,$phoneNumber,$userArray){
  	$transactionID = createLoanTransaction($conn,$loanaccount_id,$amount);
	return $transactionID > 0 ? createRepaymentBreakDown($conn,$loanaccount_id,$amount,$transactionID,$firstname,$clientAccount,$phoneNumber,$userArray) : 0;
}

function createLoanTransaction($conn,$loanaccount_id,$amount){
	$relationManager = getLoanRM($loanaccount_id,$conn);
	$dateCleared     = date('Y-m-d');
	$transactQuery   = "INSERT INTO loantransactions (loanaccount_id,date,amount,transacted_by)
	VALUES('$loanaccount_id','$dateCleared','$amount','$relationManager')";
	return $conn->query($transactQuery) ? mysqli_insert_id($conn) : 0;
}

function getLoanRM($accountID,$conn){
	$rmQuery = "SELECT * FROM loanaccounts WHERE loanaccount_id=$accountID";
	$manager = $conn->query($rmQuery);
	if($manager->num_rows > 0){
		while($row = $manager->fetch_assoc()){
			$relationManager = $row['rm'];
		}
	}else{
		$relationManager = 1;
	}
	return $relationManager;
}


function createPenaltyRecord($accountID,$penaltyAmount,$conn){
	$dateAccrued  = date('Y-m-d');
	$penaltyQuery = "INSERT INTO penaltyaccrued (loanaccount_id,date_defaulted,penalty_amount)
	VALUES('$accountID','$dateAccrued','$penaltyAmount')";
	echo $conn->query($penaltyQuery) ? "Penalty Recorded. \n" : "No penalty recorded. \n";
}

function createRepaymentBreakDown($conn,$accountID,$amountPaid,$transactionID,$firstname,$clientAccount,$phoneNumber,$userArray){
	//Pay Accrued Interest
	$accruedInterest = getUnpaidAccruedInterest($accountID,$conn);
	$interestPayable = $accruedInterest >= $amountPaid ? $amountPaid : $accruedInterest;
	//Record Interest Credit
	if($interestPayable > 0){
		recordAccruedInterest($accountID,$interestPayable,$conn,'credit','1');
	}
	$amountLessInterest = $amountPaid - $interestPayable;
	//Pay Accrued Penalty
	$accruedPenalty = getUnpaidAccruedPenalty($accountID,$conn);
	if($accruedPenalty >= $amountLessInterest){
		$penaltyDifference = $accruedPenalty - $amountLessInterest;
		if($penaltyDifference > 0){
			if(voidCurrentPenaltyRecords($accountID,$conn) === 1){
				createPenaltyRecord($accountID,$penaltyDifference,$conn);
			}
		}
		$penaltyPayable = $amountLessInterest;
	}else{
		voidCurrentPenaltyRecords($accountID,$conn);
		$penaltyPayable = $accruedPenalty;
	}
	$amountFurtherLessPenalty = $amountLessInterest - $penaltyPayable;
	//Pay Principal
	$principalPayable = $amountFurtherLessPenalty > 0 ? $amountFurtherLessPenalty : 0;
	/*Capture User Details*/
	if($userArray !=0 ){
		foreach($userArray AS $user){
			$userID             = $user['id'];
			$relationManager    = $user['managerId'];
			$userBranch         = $user['branchId'];
			$accountFirstName   = ucfirst($user['firstName']);
		}
		$phones = getProfileContactByTypeOrderDesc($conn,$userID,'PHONE');
		foreach($phones AS $phone){
			$pNumber = $phone['contactValue'];
		}
		$accountPhoneNumber = $pNumber ? "+254".substr($pNumber,-9) : 0;
	}
	$organization = getOrganizationDetails($conn);
	if($organization !=0 ){
		foreach($organization AS $org){
			$orgPhone = $org['phone'];
		}
	}
	$datePaid    = date('Y-m-d');
	$payQuery    = "INSERT INTO loanrepayments (loanaccount_id,loantransaction_id,date,penalty_paid,principal_paid,interest_paid,repaid_by,branch_id,rm,phone_transacted)
	VALUES ('$accountID','$transactionID','$datePaid','$penaltyPayable','$principalPayable','$interestPayable','$relationManager','$userBranch','$relationManager','$phoneNumber')";
	if($conn->query($payQuery)){
		$message  = "Thank you ".$firstname.", your loan payment of KES ".asMoney($amountPaid)." to account ". $clientAccount." is received. Your account is now updated. Please contact your manager for more info.";
	    sendNotification($message,$phoneNumber,$conn,'14',$userID);
		$amountBalance  = number_format(getLoanBalance($accountID,$conn),2);
		$balanceMessage = "Dear ".$accountFirstName.", your loan account is now updated. New balance is Kshs ".$amountBalance."/-. For queries please contact your Account Manager or call ".$orgPhone.". Thank you!";
		sendNotification($balanceMessage,$accountPhoneNumber,$conn,'15',$userID);
		$paidStatus = 1;
	}else{
		$paidStatus = 0;
	}
	return $paidStatus;
}

function createStrayRepaymentRecord($conn, $transaction_id, $clientAccount, $amount, $description, $firstname, $lastname, $date,  $phoneNumber, $userID){
	$organization = getOrganizationDetails($conn);
	if($organization !=0 ){
		foreach($organization AS $org){
			$orgPhone = $org['phone'];
		}
	}
	$straySQL = "INSERT INTO stray_repayments (transaction_id,providerRefId,clientAccount,source,amount,description,firstname,lastname,date) VALUES('$transaction_id','$transaction_id','$clientAccount','$phoneNumber','$amount','Member stray payment','$firstname','$lastname','$date')";
	if($conn->query($straySQL)){
		$message = "Thank you ".$firstname.", your payment of KES ".asMoney($amount)." to account ".$clientAccount." is received. Please contact your manager for more info. Thank you.";
		sendNotification($message,$phoneNumber,$conn,'14',$userID);
		echo "Stray repayment Recorded \n";
	}else{
		echo "No stray payment \n";
	}
}
/**********

	PAYROLL 

*************/
function getRespectiveBranch($branchID,$conn){
	$branchQuery = "SELECT * FROM branch WHERE branch_id=$branchID";
	$result      = $conn->query($branchQuery);
	if(!empty($result)) {
		while($row = $result->fetch_assoc()){
			$branchArray[] = $row;
		}
		return $branchArray;
	}else{
		return 0;
	}
}

function getMemberBonus($user_id,$month_date,$conn){
	$staffArray     = getRespectiveStaffMember($user_id,$conn);
	if($staffArray != 0){
		foreach($staffArray AS $staff){
			$branchID = $staff['branchId'];
		}
		$supervisors   = getProfileRecentSettingByType($conn,$user_id,'SUPERVISORIAL_ROLE');
		foreach($supervisors AS $supervisor){
			$supervisorValue = $supervisor['configValue'];
		}
		$supervisory = $supervisorValue ? $supervisorValue : 'DISABLED';
		$sales  = getProfileRecentSettingByType($conn,$user_id,'SALES_TARGET');
		foreach($sales AS $sale){
			$saleValue = $sale['configValue'];
		}
		$salesTarget = $saleValue ? floatval($saleValue) : 0;
		$bonuses = getProfileRecentSettingByType($conn,$user_id,'BONUS_PERCENT');
		foreach($bonuses AS $bonus){
			$bonusValue = $bonus['configValue'];
		}
		$bonusPercent = $bonusValue ? floatval($bonusValue) : 0;
	}
	$monthDate    = explode('-', $month_date);
	$payrollMonth = $monthDate[0];
	$payrollYear  = $monthDate[1];
	switch($supervisory){
		case 'DISABLED':
		$sTarget = $salesTarget <= 0 ? 1 : $salesTarget;
		break;

		default:
		$branchArray = getRespectiveBranch($branchID,$conn);
		if($branchArray != 0){
			foreach($branchArray AS $branch){
				$branchSalesTarget = $branch['sales_target'];
			}
		}
		$sTarget = $branchSalesTarget <= 0 ? 1 : $branchSalesTarget;
		break;
	}
	$amountSold      = getTotalLoanAmountSold($user_id,$month_date,$conn);
	$salesPercent    = ($amountSold/$sTarget) * 100;
	$multiplierFactor= getMultiplierFactor($salesPercent,$conn);
	return ($bonusPercent * $amountSold) * 0.01 * ($multiplierFactor * 0.01);
}

function getRespectiveStaffMember($user_id,$conn){
	$staffQuery="SELECT * FROM profiles WHERE id=$user_id";
	$result=$conn->query($staffQuery);
	if(!empty($result)) {
		while($row = $result->fetch_assoc()){
			$userArray[] = $row;
		}
		return $userArray;
	}else{
		return 0;
	}
}

function getTotalLoanAmountSold($user_id,$month_date,$conn){
	$staffArray  = getRespectiveStaffMember($user_id,$conn);
	if($staffArray != 0){
		foreach($staffArray AS $staff){
			$branchID  = $staff['branchId'];
		}
		$supervisors   = getProfileRecentSettingByType($conn,$user_id,'SUPERVISORIAL_ROLE');
		foreach($supervisors AS $supervisor){
			$supervisorValue = $supervisor['configValue'];
		}
		$supervisory = $supervisorValue ? $supervisorValue : 'DISABLED';
		$monthDate    = explode('-', $month_date);
		$payrollMonth = $monthDate[0];
		$payrollYear  = $monthDate[1];
		if($supervisory === 'DISABLED'){
			$totalPrincipalSql="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND MONTH(disbursed_at)='$payrollMonth' AND YEAR(disbursed_at)='$payrollYear' AND loanaccounts.rm=$user_id";
		}else{
			$totalPrincipalSql="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND MONTH(disbursed_at)='$payrollMonth' AND YEAR(disbursed_at)='$payrollYear'
			 AND loanaccounts.branch_id=$branchID";
		}
		$totalPrincipal=$conn->query($totalPrincipalSql);
		if($totalPrincipal->num_rows > 0){
			while($row = $totalPrincipal->fetch_assoc()){
				$totalPrincipalReleased=$row['amount_disbursed'];
			}
		}else{
			$totalPrincipalReleased=0;
		}
		return $totalPrincipalReleased;
	}
}

function getMultiplierFactor($derivedPercent,$conn){
	$formatPercent  = floor($derivedPercent);
	if($formatPercent > 0){
		$multiplierQuery = "SELECT * FROM performance_settings WHERE $formatPercent BETWEEN minimum AND maximum";
		$multiplier      = $conn->query($multiplierQuery);
		if($multiplier->num_rows > 0){
			while($row = $multiplier->fetch_assoc()){
				$multipleFactor = $row['percent_multiplier'];
			}
		}else{
			$multipleFactor = 0;
		}
	}else{
		$multipleFactor  = 0;
	}
	return $multipleFactor;
}

function getMemberCommission($user_id,$month_date,$conn){
	$monthDate   = explode('-', $month_date);
	$payrollMonth= $monthDate[0];
	$payrollYear = $monthDate[1];
	$staffArray  = getAccountHolder($conn,$user_id);
	//$staffArray  = getRespectiveStaffMember($user_id,$conn);
	if($staffArray != 0){
		foreach($staffArray AS $staff){
			$branchID = $staff['branchId'];
		}
		$supervisors   = getProfileRecentSettingByType($conn,$user_id,'SUPERVISORIAL_ROLE');
		foreach($supervisors AS $supervisor){
			$supervisorValue = $supervisor['configValue'];
		}
		$supervisory = $supervisorValue ? $supervisorValue : 'DISABLED';
		$collections  = getProfileRecentSettingByType($conn,$user_id,'COLLECTIONS_TARGET');
		foreach($collections AS $collection){
			$collectionValue = $collection['configValue'];
		}
		$collectionsTarget = $collectionValue ? floatval($collectionValue) : 0;
		$commissions = getProfileRecentSettingByType($conn,$user_id,'COMMISSION_PERCENT');
		foreach($commissions AS $commission){
			$commissionValue = $commission['configValue'];
		}
		$commisionPercent = $commissionValue ? floatval($commissionValue) : 0;
		switch($supervisory){
			case 'DISABLED':
			$cTarget = $collectionsTarget <= 0 ? 1 : $collectionsTarget;
			break;
	
			default:
			$branchArray  = getRespectiveBranch($branchID,$conn);
			if($branchArray != 0){
				foreach($branchArray AS $branch){
					$branchCollectionsTarget = $branch['collections_target'];
				}
			}
			$cTarget = $branchCollectionsTarget <= 0 ? 1 : $branchCollectionsTarget;
			break;
		}
		$amountCollected    = getTotalLoanCollections($user_id,$month_date,$conn);
		$collectionsPercent = ($amountCollected/$cTarget) * 100;
		$multiplierFactor   = getMultiplierFactor($collectionsPercent,$conn);
		return ($commisionPercent * $amountCollected) * 0.01 * ($multiplierFactor * 0.01);
	}
}

function getTotalLoanCollections($user_id,$month_date,$conn){
	$staffArray  = getAccountHolder($conn,$user_id);
	//$staffArray     = getRespectiveStaffMember($user_id,$conn);
	if($staffArray != 0){
		foreach($staffArray AS $staff){
			$branchID = $staff['branchId'];
		}
		$supervisors = getProfileRecentSettingByType($conn,$user_id,'SUPERVISORIAL_ROLE');
		foreach($supervisors AS $supervisor){
			$supervisorValue = $supervisor['configValue'];
		}
		$supervisory  = $supervisorValue ? $supervisorValue : 'DISABLED';

		$monthDate    = explode('-', $month_date);
		$payrollMonth = $monthDate[0];
		$payrollYear  = $monthDate[1];
		if($supervisory === 'DISABLED'){
			$repaymentSQL = "SELECT SUM(amount) AS amount FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.rm=$user_id AND MONTH(loantransactions.transacted_at)='$payrollMonth' AND YEAR(loantransactions.transacted_at)='$payrollYear' AND loantransactions.is_void IN('0','3','4')";
		}else{
			$repaymentSQL = "SELECT SUM(amount) AS amount FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.branch_id=$branchID AND MONTH(loantransactions.transacted_at)='$payrollMonth' AND YEAR(loantransactions.transacted_at)='$payrollYear' AND loantransactions.is_void IN('0','3','4')";
		}
		$totalCollections = $conn->query($repaymentSQL);
		if($totalCollections->num_rows > 0){
			while($row = $totalCollections->fetch_assoc()){
				$amountCollected=$row['amount'];
			}
		}else{
			$amountCollected = 0;
		}
		return $amountCollected;
	}
}

function getCurrentLoanRepayment($user_id,$conn){
	$loanAccountsQuery="SELECT * FROM loanaccounts WHERE user_id=$user_id AND loan_status IN('2','5','6','7')";
	$loans    = $conn->query($loanAccountsQuery);
	if($loans->num_rows > 0){
		$amount = 0;
		while($row = $loans->fetch_assoc()){
			$repayPeriod     = $row['repayment_period'];
			$periodRepayment = $repayPeriod <=0 ? 1 : $repayPeriod;
			$equalInstallment= getLoanBalance($row['loanaccount_id'],$conn)/$periodRepayment;
			$amount += $equalInstallment + $row['arrears'];
		}
	}else{
		$amount = 0;
	}
	return ceil($amount);
}

function getMemberNetSalaryPay($user_id,$month_date,$conn){
	$defaults          = getProfileRecentSettingByType($conn,$user_id,'SALARY');
	foreach($defaults AS $default){
		$configValue   = $default['configValue'];
	}
	$monthlySalary     = $configValue ? floatval($configValue) : 0;
	$accruedBonus      = getMemberBonus($user_id,$month_date,$conn);
	$accruedCommission = getMemberCommission($user_id,$month_date,$conn);
	$accruedProfit     = 0;
	$loanRepayment     = getCurrentLoanRepayment($user_id,$conn);
	$totalnetPay       = ($monthlySalary + $accruedBonus + $accruedCommission + $accruedProfit) - $loanRepayment;
	return round($totalnetPay);
}