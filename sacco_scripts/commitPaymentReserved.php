<?php

include_once('config.php');
include_once('Utilities.php');

echo "Payment Received Notification \n";
$data  = json_decode(file_get_contents('php://input'), true);

$category = $data["category"];
if($category === "MobileC2B"){
	$conn=SaccoDB();
	$rawClientAccount=$data["clientAccount"];
	$clientAccount = $data["clientAccount"];
	$phoneNumber = $data["source"];
	$providerRefId = $data["providerRefId"];
	$transaction_id=$providerRefId;
	$providerMetadata = $data["providerMetadata"];
	$firstname = ucfirst($providerMetadata["[Personal Details][First Name]"]);
	$lastname = ucfirst($providerMetadata["[Personal Details][Last Name]"]);
	$value = $data["value"];
	$amountarray = explode(".",$value);
	$amountarray2 = explode(" ",$amountarray[0]);
	$amount = $amountarray2[1];
	$date=date('Y-m-d');
	InitiatePayment($conn,$transaction_id,$providerRefId,$clientAccount,$phoneNumber,$amount,$providerMetadata,$firstname,$lastname,$date,$rawClientAccount);
}else{
	echo "Nothing interesting happened \n";
}

function InitiatePayment($conn,$transaction_id,$providerRefId,$clientAccount,$phoneNumber,$amount,$description,$firstname,$lastname,$date,$rawClientAccount){
	$userID = getAccountUserID($conn,$clientAccount);
	if($userID != 0){
		$loanaccountID = getLoanAccount($conn,$userID);
		$userArray     = getAccountHolder($conn,$userID);
		if($loanaccountID != 0){
			createLoanRepayment($conn,$loanaccountID,$amount,$firstname,$clientAccount,$phoneNumber,$userArray);
		}else{
			createStrayRepaymentRecord($conn,$transaction_id,$providerRefId,$clientAccount,$amount,$description,$firstname,$lastname,$date,$rawClientAccount,$phoneNumber,$userID);
		}
	}

	if($userID == 0){
		$savingAccountID = getSavingAccountID($conn,$rawClientAccount);
		if($savingAccountID != 0){
			$profileID = getSavingAccountUserID($conn,$savingAccountID);
			createSavingTransaction($conn,$savingAccountID,$amount,$firstname,$rawClientAccount,$phoneNumber,$profileID);
		}else{
			$profileID = 0;
			createStrayRepaymentRecord($conn,$transaction_id,$providerRefId,$clientAccount,$amount,$description,$firstname,$lastname,$date,$rawClientAccount,$phoneNumber,$profileID);
		}
	}
}