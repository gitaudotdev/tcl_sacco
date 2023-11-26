<?php

include_once('config.php');
include_once('Utilities.php');

$callbackJSONData  = file_get_contents('php://input');

$callbackData      = json_decode($callbackJSONData,true);
if(isset($callbackData['Body'])){
    $resultCode        = $callbackData['Body']['stkCallback']['ResultCode'];
    $resultDesc        = $callbackData['Body']['stkCallback']['ResultDesc'];
    $merchantRequestID = $callbackData['Body']['stkCallback']['MerchantRequestID'];
    $checkoutRequestID = $callbackData['Body']['stkCallback']['CheckoutRequestID'];

    $amount            = $resultCode == '0' ? $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'] : 0;
    $mpesaReceiptNumber= $resultCode == '0' ? $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'] : 'N/A';
    //$balance           = $resultCode === '0' ? $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][2]['Value'] : 0;
    //$b2CUtilityAccountAvailableFunds = $resultCode === '0' ? $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value']: 0;
    $transactionDate   = $resultCode == '0' ? $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][2]['Value']: date('Y-m-d');
    $phoneNumber       = $resultCode == '0' ? $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'][3]['Value']: 'N/A';

    processCallBack(SaccoDB(),$merchantRequestID,$resultCode,$resultDesc,$amount,$mpesaReceiptNumber,$transactionDate,$phoneNumber);
}else{
    echo "Nothing to process here";
}


function getSTKPushRecord($conn,$merchantRequestID){
    $stkQuery = "SELECT * FROM stkPush WHERE merchantRequestId=$merchantRequestID";
	$result   = $conn->query($stkQuery);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $pushID = $row['id'];
        }
    }else{
        $pushID = 0;
    }
    return $pushID;
}

function updateSTKPushRecord($conn,$id,$resultCode,$resultDesc,$resultAmount,$mpesaReceiptNumber,$transactionDate,$phoneNumber){
    $updatedAt = date('Y-m-d H:i:s');
    $resultTransactionDate = date('Y-m-d',strtotime($transactionDate));
    $updateQuery  = "UPDATE stkPush SET resultCode='$resultCode',resultDesc='$resultDesc',resultAmount='$resultAmount',MPESAReceiptNumber='$mpesaReceiptNumber',
    resultTransactionDate='$resultTransactionDate',resultPhoneNumber='$phoneNumber',updatedAt='$updatedAt' WHERE id='$id'";
	echo $conn->query($updateQuery) ? "STK Push Record updated. \n" : "STK Push record not updated. \n";
}

function processCallBack($conn,$merchantRequestID,$resultCode,$resultDesc,$resultAmount,$mpesaReceiptNumber,$transactionDate,$phoneNumber){
    $pushID = getSTKPushRecord($conn,$merchantRequestID);
    if($pushID > 0){
        updateSTKPushRecord($conn,$pushID,$resultCode,$resultDesc,$resultAmount,$mpesaReceiptNumber,$transactionDate,$phoneNumber);
    }else{
        echo "Nothing interesting happened here";
    }
}