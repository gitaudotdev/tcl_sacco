<?php

include_once('config.php');
require_once('AfricasTalkingGateway.php');

echo "Payment Received Notification\n";
$data  = json_decode(file_get_contents('php://input'), true);

$category = $data["category"];
if($category === "MobileC2B"){
		$conn=SaccoDB();
		$clientAccount = (int)$data["clientAccount"];
		$phoneNumber = $data["source"];
		$providerRefId = $data["providerRefId"];
		$transaction_id=$providerRefId;
		$providerMetadata = $data["providerMetadata"];
		$firstname = $providerMetadata["[Personal Details][First Name]"];
		$lastname = $providerMetadata["[Personal Details][Last Name]"];
		$value = $data["value"];
		$amountarray = explode(".",$value);
		$amountarray2 = explode(" ",$amountarray[0]);
		$amount = $amountarray2[1];
		$message="Thank you ".$firstname.", your loan payment of KES ".$amount." to account ". $clientAccount." is received. Your account is now updated. Please contact your manager for more info.";
		$username   = "conrade";
    $apikey     ="272cc9a5902ce23d34772a9999b91ff5434652e62e9020ad82a3470c4a1f0018";
		$recipients = $phoneNumber;
		$from = "TCL";
		$gateway    = new AfricasTalkingGateway($username, $apikey);
		try { 
		  $results = $gateway->sendMessage($recipients, $message, $from);
		  foreach($results as $result) {
				$messageID=$result->messageId;
      	$phoneNumber=$result->number;
      	$cost=$result->cost;
      	$clearanceSQL="INSERT INTO sms_alerts (message_id,phone_number,cost,message,sent_by) VALUES('$messageID','$phoneNumber','$cost','$message',1)";
				$conn->query($clearanceSQL);
		  }
		}catch(AfricasTalkingGatewayException $e ){
		  $result = "Encountered an error while sending: ".$e->getMessage();
		}
		$date=date('Y-m-d');
		InitiatePayment($conn,$transaction_id,$providerRefId,$clientAccount,$phoneNumber,$amount,$providerMetadata,$firstname,$lastname,$date);
}else{
	echo "Nothing interesting happened \n";
}

function InitiatePayment($conn,$transaction_id,$providerRefId,$clientAccount,$source,$amount,$description,$firstname,$lastname,$date){
		$userID=getAccountUserID($conn,$clientAccount);
		if($userID > 0){
			$loanaccountID=getLoanAccount($conn,$userID);
			createLoanRepayment($conn,$loanaccountID,$amount);
		}else{
			createStrayRepaymentRecord($conn,$transaction_id,$providerRefId,$clientAccount,$source,$amount,$description,$firstname,$lastname,$date);
		}
}

function getAccountUserID($conn,$clientAccount){
	$userSQL="SELECT * FROM users WHERE id_number=$clientAccount LIMIT 1";
	$result=$conn->query($userSQL);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$userID=$row['user_id'];
		}
		return $userID;
	}else{
		return 0;
	}
}

function getLoanAccount($conn,$userID){
	$loanSQL="SELECT * FROM loanaccounts WHERE user_id=$userID AND
	 loan_status NOT IN('0','1','3') ORDER BY loanaccount_id DESC LIMIT 1";
	$loanaccount=$conn->query($loanSQL);
	if ($loanaccount->num_rows > 0) {
		while($row = $loanaccount->fetch_assoc()){
			$loanaccountID=$row['loanaccount_id'];
		}
		return $loanaccountID;
	}
}

function createLoanRepayment($conn,$loanaccount_id,$amount){
		$loantransaction_id=createLoanTransaction($conn,$loanaccount_id,$amount);
		if($loantransaction_id > 0){
			$repayStatus=createLoanRepaymentBreakDown($conn,$loanaccount_id,$amount,$loantransaction_id);
			if($repayStatus === 0){
				$status=0;
				return $status;
			}else{
				$loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id=$loanaccount_id";
				$loanaccount=$conn->query($loanSQL);
				if($loanaccount->num_rows > 0) {
					while($row = $loanaccount->fetch_assoc()){
						$loanaccount_id=$row['loanaccount_id'];
						$userID=$row['user_id'];
					}
					$userSQL="SELECT * FROM users WHERE user_id=$userID LIMIT 1";
					$result=$conn->query($userSQL);
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()){
							$userPhone=$row['phone'];
							$firstName=$row['first_name'];
						}
					  $status=1;
						return $status;
				}
			}
		}
	}
}

function createLoanTransaction($conn,$loanaccount_id,$amount){
	$date_cleared=date('Y-m-d');
	$loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id=$loanaccount_id";
	$loanaccount=$conn->query($loanSQL);
	if ($loanaccount->num_rows > 0) {
		while($row = $loanaccount->fetch_assoc()){
			$transactedBy=$row['rm'];
		}
		$clearanceSQL="INSERT INTO loantransactions (loanaccount_id,date,amount,type,transacted_by) VALUES('$loanaccount_id','$date_cleared','$amount','1','$transactedBy')";
		if($conn->query($clearanceSQL)){
		 	$transactionID=mysqli_insert_id($conn);
		 	return $transactionID;
		}else{
		 	$transactionID=0;
		 	return $transactionID;
		}
	}
}

function createLoanRepaymentBreakDown($conn,$loanaccount_id,$amount,$transaction_id){
		$penaltyAmount=repayLoanPenalty($conn,$loanaccount_id);
		if($penaltyAmount >= $amount){
			$totalPenaltyPayable=$amount;
		}else{
			$totalPenaltyPayable=$penaltyAmount;
		}
		$amountDeductPenalty=$amount-$totalPenaltyPayable;
		$totalFees=repayLoanFee($conn,$loanaccount_id);
		if($totalFees >= $amountDeductPenalty){
			$totalFeesPayable=$amountDeductPenalty;
		}else{
			$totalFeesPayable=$totalFees;
		}
		$remainingAmount=$amountDeductPenalty-$totalFeesPayable;
		$interestAmount=getInterestPayable($conn,$loanaccount_id);
		if($interestAmount == 0){
			$interestPayable=0;
		}else{
			if($interestAmount >= $remainingAmount ){
				$interestPayable=$remainingAmount;
			}else{
				$interestPayable=$interestAmount;
			}
		}
		$amountBalance=$remainingAmount-$interestPayable;
		if($amountBalance >= 0){
			$principalPayable=$amountBalance;
		}else{
			$principalPayable=0;
		}
		$date_cleared=date('Y-m-d');
		$rmSQL="SELECT * FROM loanaccounts WHERE loanaccount_id=$loanaccount_id";
		$relationManagers=$conn->query($rmSQL);
		if ($relationManagers->num_rows > 0) {
			while($row = $relationManagers->fetch_assoc()){
				$relationManager=$row['rm'];
			}
			$clearanceSQL="INSERT INTO loanrepayments (loanaccount_id,loantransaction_id,date,fee_paid,penalty_paid,principal_paid,interest_paid,repaid_by) VALUES('$loanaccount_id','$transaction_id','$date_cleared','$totalFeesPayable','$totalPenaltyPayable','$principalPayable','$interestPayable','$relationManager')";
			if($conn->query($clearanceSQL)){
				$loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id=$loanaccount_id";
				$loanaccount=$conn->query($loanSQL);
				if ($loanaccount->num_rows > 0) {
					while($row = $loanaccount->fetch_assoc()){
						$arrears=$row['arrears'];
					}
					$remainingArrears=$arrears-$totalFeesPayable;
					$updateSql="UPDATE loanaccounts SET arrears='$remainingArrears' WHERE loanaccount_id=$loanaccount_id";
					$conn->query($updateSql);
				}
			}
		}
}

function repayLoanPenalty($conn,$loanaccount_id){
		$penaltyStatus=checkIfLoanPenaltiesApply($conn,$loanaccount_id);
		if($penaltyStatus == 0){
			$penaltyAmount=0;
			return $penaltyAmount;
		}else{
			$updateSql="UPDATE penaltyaccrued SET is_paid='1' WHERE id=$penaltyStatus";
			$conn->query($updateSql);
			$loanSQL="SELECT * FROM penaltyaccrued WHERE id=$penaltyStatus";
			$loanaccount=$conn->query($loanSQL);
			if ($loanaccount->num_rows > 0) {
				while($row = $loanaccount->fetch_assoc()){
					$penaltyAmount=$row['penalty_amount'];
				}
				return $penaltyAmount;
			}
		}
}

function checkIfLoanPenaltiesApply($conn,$loanaccount_id){
	$penaltySQL="SELECT * FROM penaltyaccrued WHERE loanaccount_id=$loanaccount_id 
	AND is_paid='0' ORDER BY id DESC LIMIT 1";
	$loanaccount=$conn->query($penaltySQL);
	if(!empty($loanaccount)){
		while($row = $loanaccount->fetch_assoc()){
			$status=$row['id'];
		}
		return $status;
	}else{
		return 0;
	}
}

function repayLoanFee($conn,$loanaccount_id){
	$totalPayableFees=calculateTotalLoanFees($conn,$loanaccount_id);
	$feesPayable=$totalPayableFees;
	if($feesPayable >0){
	 return $feesPayable;
	}else{
		return 0;
	}
}

function calculateTotalLoanFees($conn,$loanaccount_id){
	$loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id=$loanaccount_id";
	$loanaccount=$conn->query($loanSQL);
	if($loanaccount->num_rows > 0) {
		while($row = $loanaccount->fetch_assoc()){
			$sumAmount=$row['arrears'];
		}
		$totalFees=$sumAmount;
		return $totalFees;
	}else{
		return 0;
	}
}	

function getInterestPayable($conn,$loanaccount_id){
	$repaymentStatus=determineIfInterestPayable($conn,$loanaccount_id);
  switch($repaymentStatus){
  	case 0:
    $interestAmount=0;
    return $interestAmount;
    break;

    case 1:
    $principalBalance=getLoanPrincipalBalance($conn,$loanaccount_id);
    $loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id=$loanaccount_id";
		$loanaccount=$conn->query($loanSQL);
		if($loanaccount->num_rows > 0) {
			while($row = $loanaccount->fetch_assoc()){
				$interest_Rate=$row['interest_rate'];
			}
	    $interestAmount=getCurrentInterest($principalBalance,$interest_Rate);   
    	return $interestAmount;
		}
    break;
  }
}

function getLoanPrincipalBalance($conn,$loanaccount_id){
	$loanSQL="SELECT * FROM loanaccounts WHERE loanaccount_id=$loanaccount_id";
	$loanaccount=$conn->query($loanSQL);
	if($loanaccount->num_rows > 0) {
		while($row = $loanaccount->fetch_assoc()){
			$amountApproved=$row['amount_approved'];
		}
		$principalPaid=getTotalPrincipalPaid($conn,$loanaccount_id);
		$principalBalance=$amountApproved - $principalPaid;
		return $principalBalance;
	}
} 

function getTotalPrincipalPaid($conn,$loanaccount_id){
	$principalSql="SELECT SUM(principal_paid) as principal_paid FROM loanrepayments WHERE
	 loanaccount_id=$loanaccount_id AND is_void='0'";
	$loanaccount=$conn->query($principalSql);
	if($loanaccount->num_rows > 0) {
		while($row = $loanaccount->fetch_assoc()){
			$principalPaid=$row['principal_paid'];
		}
		return $principalPaid;
	}else{
		return 0;
	}
}

function getCurrentInterest($principalBalance,$interestRate){
	if($interestRate > 0){
		$currentInterest=($interestRate / 100) * $principalBalance;
	}else{
		$currentInterest=0;
	}
	return $currentInterest;
}

function determineIfInterestPayable($conn,$loanaccount_id){
  $lastDate=getLastInterestPaymentDate($conn,$loanaccount_id);
  if($lastDate == 0){
    $status=1;
    return $status;
  }else{
    $monthPaid=date('m',strtotime($lastDate));
    $yearPaid=date('Y',strtotime($lastDate));
    $nowMonth=date('m');
    $nowYear=date('Y');
    if($monthPaid === $nowMonth && $yearPaid === $nowYear){
      $status=0;
      return $status;
    }else{
      $status=1;
      return $status;
    }
  }
}

function getLastInterestPaymentDate($conn,$loanaccount_id){
  $interestSQL="SELECT * FROM loanrepayments WHERE loanaccount_id=$loanaccount_id AND is_void='0' ORDER BY loanrepayment_id DESC LIMIT 1";
  $repayment=$conn->query($interestSQL);
  if($repayment->num_rows > 0){
		while($row = $repayment->fetch_assoc()){
			$interestPaid=$row['interest_paid'];
			$datePaid=$row['repaid_at'];
		}
    if($interestPaid!= 0.00){
      return date('Y-m-d',strtotime($datePaid));
    }else{
      return 0;
    }
  }else{
    return 0;
  }
}

function createStrayRepaymentRecord($conn,$transaction_id,$providerRefId,$clientAccount,$source,$amount,$description,$firstname,$lastname,$date){
	$straySQL="INSERT INTO stray_repayments (transaction_id,providerRefId,clientAccount,source,amount,description,firstname,lastname,date) VALUES('$transaction_id','$providerRefId','$clientAccount','$source','$amount','$description','$firstname','$lastname','$date')";
 if($conn->query($straySQL)){
 		echo "Stray Repayment Recorded \n";
 }else{
 	echo "NO Stray Payment";
 }
}

