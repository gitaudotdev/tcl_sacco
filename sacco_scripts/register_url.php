<?php

include_once('config.php');
include_once('Utilities.php');

$conn=SaccoDB();
$authRecords=getMostRecentAuthToken($conn);
if($authRecords !=0){

    foreach($authRecords AS $record){
      $authToken=$record['auth_token'];
    }
    $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
  
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c,CURLOPT_HTTPHEADER,array("Content-Type:application/json","Authorization:Bearer $authToken")); 
    
    $c_post_data = array(
      'ShortCode' => X_BUSINESSCONSUMER_SHORTCODE,
      'ResponseType' => '1000',
      'ConfirmationURL' => X_CONFIRMATION_URL,
      'ValidationURL' => X_VALIDATION_URL
    );
    
    $data_string = json_encode($c_post_data);
    
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $data_string);
    
    $c_response = curl_exec($c);
    print_r($c_response);
    
    echo $c_response;
  }
?>  