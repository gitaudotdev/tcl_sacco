<?php

include_once('config.php');
include_once('Utilities.php');

function remindClient(){
	$conn = SaccoDB();
	switch(IsAutomatedAlertsDisabled($conn,'13')){
		case 0:
		$loanSQL = "SELECT * FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
		$result  = $conn->query($loanSQL);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$userID        = $row['user_id'];
				$repaymentDate = $row['repayment_start_date'];
				if(getAccountHolder($conn,$userID) != 0){
					$userArray = getAccountHolder($conn,$userID);
					foreach($userArray AS $user){
						if(!in_array($user['profileType'],['STAFF'])){
							$phones = getProfileContactByTypeOrderDesc($conn,$userID,'PHONE');
							foreach($phones AS $phone){
								$pNumber = $phone['contactValue'];
							}
							$recipient     = $pNumber ? "+254".substr($pNumber,-9) : 0;
							if($recipient != 0){
								$firstName     = ucfirst($user['firstName']);
								$accountNumber = $user['idNumber'];
								broadcastReminders($conn,$repaymentDate,$recipient,$firstName,$accountNumber,$userID);
							}
						}
					}
				}else{
					echo "No Loan Account Holder to receive message. \n";
				}
			}
		}else{
			echo "No Loan Account To Broadcast Reminders. \n";
		}
		break;

		case 1:
		echo "Automated Reminders deactivated. \n";
		break;
	}
}

function broadcastReminders($conn,$repaymentDate,$recipient,$firstName,$accountNumber,$userID){
	$currentMonth    = (int)date('m');
	$repaymentMonth  = (int)date('m',strtotime($repaymentDate));
	if($currentMonth <= $repaymentMonth){
		$today       = date('d');
		$formatDate  = date('d',strtotime($repaymentDate));
		$displaydate = date('jS',strtotime($repaymentDate));
		$difference  = getDifference($today,$formatDate);
		if($difference >0 && $difference <= 3){
			$message = " Your loan is now due for remittance : Pay before $displaydate, through,\nPaybill=754298\nAccount=$accountNumber.\nThank you!";
			$text    = "Dear ".$firstName.",". $message;
			sendNotification($text,$recipient,$conn,'13',$userID);
		}
	}else{
		echo "Current Month Greater than Repayment Start Month. \n";
	}
}
/********************************
Invoke Main Method
************************************/
remindClient();