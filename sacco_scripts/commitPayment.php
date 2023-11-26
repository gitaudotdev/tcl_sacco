<?php

include_once('config.php');
include_once('Utilities.php');

echo "Payment Received Notification \n";
$callback_data = json_decode(file_get_contents('php://input'), true);
 $logFile = "stkPushResponse.txt";
 logResponse($logFile,$callback_data);
$conn = SaccoDB();
if(isset($callback_data['Body'])){
    $callback_body = $callback_data['Body'];
    $callback_message = $callback_body['stkCallback'];
    $resultCode   = $callback_message['ResultCode'];
    $resultDesc = $callback_message['ResultDesc'];
    $merchantRequestID = $callback_message['MerchantRequestID'];
    $checkoutRequestID = $callback_message['CheckoutRequestID'];

    $amount             = $resultCode  == 0 ? $callback_message['CallbackMetadata']['Item'][0]['Value'] : 0;
    $mpesaReceiptNumber = $resultCode  == 0 ? $callback_message['CallbackMetadata']['Item'][1]['Value'] : 'N/A';
    $balance            = 0;
    $transactionDate    = $resultCode  == 0 ? $callback_message['CallbackMetadata']['Item'][3]['Value'] : date('Y-m-d');
    $phoneNumber        = $resultCode  == 0 ? $callback_message['CallbackMetadata']['Item'][4]['Value'] : 'N/A';


    processCallBack($conn,$merchantRequestID,$resultCode,$resultDesc,$amount,$mpesaReceiptNumber,$transactionDate,$phoneNumber);
}else{
    processUserInitiatedPayment($conn,$callback_data);
}

function processCallBack($conn,$merchantRequestID,$resultCode,$resultDesc,$resultAmount,$mpesaReceiptNumber,$transactionDate,$phoneNumber){
    $stkRecords = getSTKPushRecord($conn,$merchantRequestID);
    if($stkRecords != 0){
        foreach($stkRecords AS $record){
            $pushID    = $record['id'];
        }
        if($pushID > 0){
            updateSTKPushRecord($conn,$pushID,$resultCode,$resultDesc,$resultAmount,$mpesaReceiptNumber,$transactionDate,$phoneNumber);
        }
    }
}


function updateSTKPushRecord($conn,$id,$resultCode,$resultDesc,$resultAmount,$mpesaReceiptNumber,$transactionDate,$phoneNumber){
    $updatedAt = date('Y-m-d H:i:s');
    $resultTransactionDate = date('Y-m-d',strtotime($transactionDate));
    $updateQuery  = "UPDATE stkPush SET resultCode='$resultCode',resultDesc='$resultDesc',resultAmount='$resultAmount',MPESAReceiptNumber='$mpesaReceiptNumber',
    resultTransactionDate='$resultTransactionDate',resultPhoneNumber='$phoneNumber',updatedAt='$updatedAt' WHERE id='$id'";
    echo $conn->query($updateQuery) ? "STK Push Record updated. \n" : "STK Push record not updated. \n";
}


function processUserInitiatedPayment($conn,$callback_data){
    $transactionType   = $callback_data['TransactionType'];
    $transactionId     = $callback_data['TransID'];
    $transactionTime   = $callback_data['TransTime'];
    $transactionAmount = $callback_data['TransAmount'];
    $businessShortCode = $callback_data['BusinessShortCode'];
    $billRefNumber     = $callback_data['BillRefNumber'];
    $orgAccountBalance = $callback_data['OrgAccountBalance'];
    $phoneNumber       = $callback_data['MSISDN'];
    $firstName         = $callback_data['FirstName'];
    $lastName          = '******';
    InitiatePayment($conn,$transactionId,$billRefNumber,$phoneNumber,$transactionAmount,'User Initiated Payment',$firstName,$lastName,$transactionTime);
}

function InitiatePayment($conn,$transaction_id,$clientAccount,$phoneNumber,$amount,$description,$firstname,$lastname,$date){
	$userID = getAccountUserID($conn,$clientAccount);
	if($userID != 0){
		$loanaccountID = getLoanAccount($conn,$userID);
		$userArray     = getAccountHolder($conn,$userID);
		if($loanaccountID != 0){
			createLoanRepayment($conn,$loanaccountID,$amount,$firstname,$clientAccount,$phoneNumber,$userArray);
		}else{
			createStrayRepaymentRecord($conn,$transaction_id,$clientAccount,$amount,$description,$firstname,$lastname,$date,$phoneNumber,$userID);
		}
	}

	if($userID == 0){
		$savingAccountID = getSavingAccountID($conn,$clientAccount);
		if($savingAccountID != 0){
			$profileID = getSavingAccountUserID($conn,$savingAccountID);
			createSavingTransaction($conn,$savingAccountID,$amount,$firstname,$clientAccount,$phoneNumber,$profileID,$transaction_id);
		}else{
			$profileID = 0;
			createStrayRepaymentRecord($conn,$transaction_id,$clientAccount,$amount,$description,$firstname,$lastname,$date,$phoneNumber,$profileID);
		}
	}
}

function logResponse($logFile,$data){
    $log = fopen($logFile, "a");
    fwrite($log, print_r($data,true));
    fclose($log);
}

//if($category === "MobileC2B"){
//	$conn=SaccoDB();
//	$rawClientAccount=$data["clientAccount"];
//	$clientAccount = $data["clientAccount"];
//	$phoneNumber = $data["source"];
//	$providerRefId = $data["providerRefId"];
//	$transaction_id=$providerRefId;
//	$providerMetadata = $data["providerMetadata"];
//	$firstname = ucfirst($providerMetadata["[Personal Details][First Name]"]);
//	$lastname = ucfirst($providerMetadata["[Personal Details][Last Name]"]);
//	$value = $data["value"];
//	$amountarray = explode(".",$value);
//	$amountarray2 = explode(" ",$amountarray[0]);
//	$amount = $amountarray2[1];
//	$date=date('Y-m-d');
//	InitiatePayment($conn,$transaction_id,$providerRefId,$clientAccount,$phoneNumber,$amount,$providerMetadata,$firstname,$lastname,$date,$rawClientAccount);
//}else{
//	echo "Nothing interesting happened \n";
//}