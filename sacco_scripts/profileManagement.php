<?php

include_once('config.php');
include_once('Utilities.php');

function manageProfileAccounts(){
    $conn      = SaccoDB();
	$today     = date('Y-m-d');
    $authQuery = "SELECT * FROM auths WHERE authStatus IN('ACTIVE') AND level IN('USER')";
	$result    = $conn->query($authQuery);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
            $authId       = $row['id'];
            $profileId    = $row['profileId'];
            $lastLoggedAt = date('Y-m-d',strtotime($row['lastLoggedAt']));
            suspendLoginProfile($conn,$authId,$profileId,$today,$lastLoggedAt);
		}
	}else{
		echo "No Login profile found. \n";
	}
}

function suspendLoginProfile($conn,$authId,$profileId,$today,$lastLoggedAt){
	$userArray     = getAccountHolder($conn,$profileId);
    if($userArray !=0 ){
        foreach($userArray AS $user){
            $fullName    = strtoupper($user['firstName']).''.strtoupper($user['lastName']);
            $profileType = $user['profileType'];
        }
        $lockDays      = $profileType === 'STAFF' ? 90 : 30;
        $dormantDays   = $profileType === 'STAFF' ? 90 : 45;
        $difference = getDateDifference($lastLoggedAt,$today);
        if($difference >= $lockDays && $difference <= $dormantDays){
            $updateQuery = " UPDATE auths SET authStatus='LOCKED' WHERE id=$authId";
            echo $conn->query($updateQuery) ? "Login Profile locked. \n" : "Login profile not locked. \n";
            $updateProfileQuery = " UPDATE profiles SET profileStatus='LOCKED' WHERE id=$profileId";
            echo $conn->query($updateProfileQuery) ? "Profile locked. \n" : "Profile not locked. \n";
            logUserActivity('Login and account profile marked as locked for not logging into the account for 30 or more days -'.$fullName,'high',$conn);
        }else if($difference > $dormantDays){
            $updateQuery = " UPDATE auths SET authStatus='DORMANT' WHERE id=$authId";
            echo $conn->query($updateQuery) ? "Login Profile made dormant. \n" : "Login profile not made dormant. \n";
            $updateProfileQuery = " UPDATE profiles SET profileStatus='DORMANT' WHERE id=$profileId";
            echo $conn->query($updateProfileQuery) ? "Profile made dormant. \n" : "Profile not made dormant. \n";
            logUserActivity('Login and account profile marked as dormant for not logging into the account for 45 or more days -'.$fullName,'high',$conn);
        }
    }else{
        echo "No Account associated with the account ID \n";
    }
}

manageProfileAccounts();