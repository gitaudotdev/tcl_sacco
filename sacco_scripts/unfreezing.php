<?php

include_once('config.php');
include_once('Utilities.php');

function unfreezeLoanAccount(){
	$conn=SaccoDB();
	unfreezeFrozenLoanAccount($conn);
}

function unfreezeFrozenLoanAccount($conn){
	$frozenQuery = "SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10') AND is_frozen='1'";
	$result      = $conn->query($frozenQuery);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$userID    = $row['user_id'];
			$accountID = $row['loanaccount_id'];
			if(getAccountHolder($conn,$userID) != 0){
				$userArray = getAccountHolder($conn,$userID);
				foreach($userArray AS $user){
					$phones   = getProfileContactByTypeOrderDesc($conn,$staff,'PHONE');
					foreach($phones AS $phone){
						$pNumber = $phone['contactValue'];
					}
					$recipient = $pNumber ? "+254".substr($pNumber,-9) : 0;
					$firstName = ucfirst($user['firstName']);
					unfreezeSpecificAccount($conn,$accountID,$firstName,$recipient,$userID);
				}
			}else{
				echo "No Loan Account Holder to receive message. \n";
			}
		}
	}else{
		echo "No loan account has been frozen. \n";
	}
}

function unfreezeSpecificAccount($conn,$accountID,$firstName,$recipient,$userID){
	$currentDate   = date('Y-m-d');
	$frozenAccount = getSpecificAccountFrozenDetails($conn,$accountID);
	if($frozenAccount != 0){
		foreach($frozenAccount AS $account){
			$frozenDate   = date('Y-m-d',strtotime($account['date_frozen']));
			$frozenPeriod = (int)$account['period_frozen'];
		}
		$frozenDifference = getDateDifference($frozenDate,$currentDate);
		switch($frozenPeriod){
			case 575:
			echo "The loan account {$accountID} has an indefinite freezing period.\n";
			break;

			default:

			if($frozenDifference >= $frozenPeriod){
				unfreezeAccount($conn,$accountID);
			}else{
				$difference = $frozenPeriod-$frozenDifference;
				if($difference >0 && $difference <= 3){  
					$message     = " Your interest freeze period is expiring in {$difference} days.\nPlease plan to clear through Paybill=754298\nThank you!";
					$textMessage = "Dear ".$firstName.",". $message;
					sendNotification($textMessage,$recipient,$conn,'20',$userID);
				}
				echo "The loan account {$accountID} freezing period has not expired.($frozenDifference Days - {$difference}) \n";
			}
			break;
		}
	}else{
		echo "No loan account has been frozen. \n";
	}
}

function getSpecificAccountFrozenDetails($conn,$accountID){
	$frozenAccountQuery = "SELECT period_frozen,date_frozen FROM interest_freezes WHERE loanaccount_id=$accountID
	AND unfrozen_type='0' ORDER BY id DESC LIMIT 1";
	$result             = $conn->query($frozenAccountQuery);
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$frozenAccount[] = $row;
		}
	}else{
		$frozenAccount=0;
	}
	return $frozenAccount;
}

function unfreezeAccount($conn,$accountID){
	$unfreezeQuery = "UPDATE loanaccounts SET is_frozen='0' WHERE loanaccount_id=$accountID";
	if($conn->query($unfreezeQuery)){
		updateFrozenRecord($conn,$accountID);
		echo "Loan Account has been unfrozen successfully. \n";
		$frozen=1;
	}else{
		$frozen=0;
	}
  return $frozen;
}

function updateFrozenRecord($conn,$accountID){
	$unfrozenDate   = date('Y-m-d H:i:s');
	$unfreezeQuery  = "UPDATE interest_freezes SET unfrozen_type='1', unfrozen_reason='Automatic Unfreezing of Account',date_unfrozen='$unfrozenDate',unfrozen_by=0 WHERE loanaccount_id=$accountID";
	return $conn->query($unfreezeQuery) ? 1 : 0;
}
/********************************
Invoke Main Method
************************************/
unfreezeLoanAccount();