<?php

include_once('config.php');
include_once('Utilities.php');

function authGenerator(){
	$conn=SaccoDB();
	$authRecords=getMostRecentAuthToken($conn);
	if($authRecords !=0){
		foreach($authRecords AS $record){
			$authID=$record['id'];
		}
		switch(expireRecentAuthToken($conn,$authID)){
			case 1000:
			generateToken($conn);
			break;

			case 1001:
			echo "Nothing going on here...\n";
			break;
		}
	}else{
    generateToken($conn);
  }
}

function generateToken($conn){
  $consumerKey=X_CONSUMERSOURCE_KEY;
  $consumerSecret=X_CONSUMERSOURCE_SECRET;
  $url = X_APIAUTHTOKEN_URL;
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  $credentials = base64_encode("$consumerKey:$consumerSecret");
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); 
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $curl_response = curl_exec($curl);
  if(!empty($curl_response)){
    $authToken=json_decode($curl_response,true)['access_token'];
    insertNewAuthToken($conn,$authToken);
    echo "Generated Sucessfully...\n";
  }else{
    $authToken=1250;
    echo "Generation Failed...\n";
  }
  return $authToken;
}
/***********

	INVOKE

******************/
authGenerator();