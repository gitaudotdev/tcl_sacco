<?php
include_once('config.php');
include_once('Utilities.php');

echo "Acccount Balance Response Entry Point\n";
$balanceResponse=file_get_contents('php://input');
$conn  = SaccoDB();
$data  = json_decode($balanceResponse,true);

foreach($data as $value){
	$resultType=$value["ResultType"];
	$resultCode=$value["ResultCode"];
	$resultDesc=$value["ResultDesc"];
	$resultOriginatorConversationID=$value['OriginatorConversationID'];
	$resultConversationID=$value['ConversationID'];
	$resultTransactionID=$value['TransactionID'];
	foreach($value['ResultParameters']['ResultParameter'] AS $parameter){ 
		if($parameter['Key'] === 'AccountBalance'){
			 $balances = explode("&", $parameter['Value']);
			 foreach($balances AS $balance){
			 	$detail=explode('|',$balance);
		 		$balanceTitle=$detail[0];
		 		$balanceCurrency=$detail[1];
		 		switch($balanceTitle){
		 			case 'Working Account':
		 			$workingAccount = $detail[2];
		 			// $utilityAccount = 0;
		 			// $chargeAccount  = 0;
		 			break;

		 			case 'Utility Account':
		 			$utilityAccount = $detail[2];
		 			// $workingAccount = 0;
		 			// $chargeAccount  = 0;
		 			break;

		 			case 'Charges Paid Account':
		 			$chargeAccount  = $detail[2];
		 			// $workingAccount = 0;
		 			// $utilityAccount = 0;
		 			break;
		 		}
		 		$updateBalanceStatus=updateCheckBalanceRecord($resultConversationID,$resultType,$resultCode,
  			    $resultDesc,$resultTransactionID,$workingAccount,$utilityAccount,$chargeAccount,$conn);
			 }
		}else{
			$updateBalanceStatus = 0;
		}
	}
}