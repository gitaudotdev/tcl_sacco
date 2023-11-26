<?php

include_once('config.php');
include_once('Utilities.php');

function creditInterestEarned(){
	creditSavingAccount(SaccoDB());
}

function creditSavingAccount($conn){
	$accountQuery = "SELECT * FROM savingaccounts";
	$result       = $conn->query($accountQuery);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$accountID       = $row['savingaccount_id'];
			$interestRate    = $row['interest_rate'];
			$transactedPhone = $row['account_number'];
			determinePostingDate($conn,$accountID,$interestRate,$transactedPhone);
		}
	}
}

function determinePostingDate($conn,$accountID,$interestRate,$transactedPhone){
	$accountBalance = getSavingAccountBalance($conn,$accountID);
	$amount         = calculateInterestEarned($accountBalance,$interestRate);
	$description    = "Post Interest Earned Account: $accountID";
	creditSavingTransaction($conn,$accountID,$amount,$description,0,$transactedPhone);
}

function getSavingAccountBalance($conn,$accountID){
	$credits = getSavingAccountTotalCredits($conn,$accountID);
	$debits  = getSavingAccountTotalDebits($conn,$accountID);
	$balance = $credits - $debits;
	return $balance <= 0 ?  0 : $balance;
}

function getSavingAccountTotalCredits($conn,$accountID){
	$transactionSql = "SELECT COALESCE(SUM(amount),0) AS amount FROM savingtransactions WHERE
	savingaccount_id=$accountID AND is_void='0' AND type='credit'";
	$transaction    = $conn->query($transactionSql);
	if($transaction->num_rows > 0) {
		while($row = $transaction->fetch_assoc()){
			$totalCredits=$row['amount'];
		}
		return $totalCredits;
	}else{
		return 0;
	}
}

function getSavingAccountTotalDebits($conn,$accountID){
	$transactionSql = "SELECT COALESCE(SUM(amount),0) AS amount FROM savingtransactions WHERE
	 savingaccount_id=$accountID AND is_void='0' AND type='debit'";
	$transaction    = $conn->query($transactionSql);
	if($transaction->num_rows > 0) {
		while($row = $transaction->fetch_assoc()){
			$totalDebits=$row['amount'];
		}
		return $totalDebits;
	}else{
		return 0;
	}
}

function creditSavingTransaction($conn,$savingaccountID,$amount,$description,$transactedBy,$transactedPhone){
	if($amount > 0){
		$userID = getSavingAccountUserID($conn,$savingaccountID);
		if($userID !== 0){
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
		}else{
			$profileId = null;
			$branchId  = null;
			$managerId = null;
		}
		$creditSql="INSERT INTO savingtransactions (savingaccount_id,amount,description,transacted_by,phone_transacted,profileId,branchId,managerId)
		 VALUES('$savingaccountID',$amount,'$description','$transactedBy','$transactedPhone','$profileId','$branchId','$managerId')";
		if($conn->query($creditSql)){
		 	$savingtransaction_id=mysqli_insert_id($conn);
		 	postSavingAccountInterest($conn,$savingtransaction_id,$amount);
		  echo "Saving Account Credited. \n";
		}else{
		  echo "Saving Account Not Credited. \n";
		}
	}else{
		echo "Saving Account Not Credited.Not Enough Funds.\n";
	}
}

function postSavingAccountInterest($conn,$savingtransaction_id,$amount){
	if($amount > 0){
		$postSql="INSERT INTO savingpostings (savingtransaction_id,posted_interest)
		 VALUES('$savingtransaction_id',$amount)";
		if($conn->query($postSql)){
		  echo "Interest Earned Posted Successfully. \n";
		}else{
		  echo "Interest Not Posted. \n";
		}
	}else{
		echo "Saving Account Interest not Credited. Not Enough Funds. \n";
	}
}
/************
INVOKE MAIN METHOD
********************/
creditInterestEarned();