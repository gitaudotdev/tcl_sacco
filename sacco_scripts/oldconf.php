<?php
ini_set('max_execution_time', 300);
date_default_timezone_set("Africa/Nairobi");
require 'php_mailer/class.phpmailer.php';
require 'AfricasTalkingGateway.php';
/****  DB  CONFIGS ******/
const DB_HOST = 'localhost';
const DB_AUTH_USER= 'root';
const DB_AUTH_PWD = 'Edarnoc@100';
const DB_AUTH_DB= 'repo';

/****** B2C *********/
const X_CONSUMERSOURCE_KEY ='yPLtyISLYWAB7cz0dNdDbn39k2A7zun9';
const X_CONSUMERSOURCE_SECRET='MkBLyM6vACqwymtL';
const X_ACCOUNTBALANCE_URL= 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query';
const X_TRANSACTIONSTATUS_URL='https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';
const X_APIAUTHTOKEN_URL='https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
const X_BUSINESSCONSUMER_URL='https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest';
const X_CONSUMERSECURITY_KEY='SCuR52TSrgGa2YZNamZzQUWRccpGbqbL6A5LUcNUYyufp4RDxYaIPrf69W35z2k79/hV88bCDWZN5r88/D3OqHtBO/u8BXqXqoK0A/puXDYdp7mwqHmlcwTp5xb5aoK/fpFEY8uabzkvOuyACVotFLXvHZ6t6WPEHPea0H2O95VNWDWlzSQnGMWiVKGspMGJTlvgnpAKqLdixrpgQgwdZwHwBfliPzqcpvS95lkayw7oX9bAsPA8Ruk3Td3pNqD4xNiGYRYLvCKQr80T63BaCC517bCRT1+cMOGhloHBitLx+RSr9Dw9YZ6lyT2A0DtM97CvWCFFhceRzgsiH8X06w==';
const X_QUEUETIMEOUT_URL='https://loans.tclfinance.co.ke/replyQueue';
const X_CONSUMERAPIRESULTS_URL='https://loans.tclfinance.co.ke/sacco_scripts/balance_result.php';
const X_BUSINESSCONSUMER_SHORTCODE='754297';
const X_BUSINESSCONSUMER_INITIATOR_NAME='Kelvin';

/****** C2B *********/
const X_PAYBILL_NUMBER='754298';
const X_CONSUMERBUSINESS_URL='https://api.safaricom.co.ke';
const X_VALIDATION_URL='https://loans.tclfinance.co.ke/sacco_scripts/validation.php';
const X_CONFIRMATION_URL='https://loans.tclfinance.co.ke/sacco_scripts/confirmation.php';
/****** AFRICASTALKING *********/
const AS_USER='conrade';
const AS_KEY='272cc9a5902ce23d34772a9999b91ff5434652e62e9020ad82a3470c4a1f0018';
const AS_FROM='TCL';
/******************

/******* DB CONNECTION ********/
function SaccoDB(){
    $conn = new mysqli(DB_HOST,DB_AUTH_USER,DB_AUTH_PWD,DB_AUTH_DB);
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        return $conn;
    }
}