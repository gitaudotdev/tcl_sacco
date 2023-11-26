<?php

require 'vendor/autoload.php';
use AfricasTalking\SDK\AfricasTalking;

class AirtimeManager{
	
	public static function approveAirtimeTransaction($id){
		$request   = Airtime::model()->findByPk($id);
		$request->status = '1';
		$request->authorized_by   = Yii::app()->user->user_id;
		$request->date_authorized = date('Y-m-d');
		if($request->save()){
			$fullName      = Profiles::model()->findByPk($request->user_id)->ProfileFullName;
			$airtimeAmount = CommonFunctions::asMoney($request->amount);
			$phoneNumber   = $request->phone_number;
			Logger::logUserActivity("Approved $airtimeAmount as airtime for $fullName to number: $phoneNumber",'high');
			$status = 1;
		}else{
			$status = 0;
		}
		return $status;
	}

	public static function rejectAirtimeTransaction($id){
		$request  = Airtime::model()->findByPk($id);
		$request->status = '3';
		$request->authorized_by   = Yii::app()->user->user_id;
		$request->date_authorized = date('Y-m-d');
		if($request->save()){
			$fullName      = Profiles::model()->findByPk($request->user_id)->ProfileFullName;
			$airtimeAmount = CommonFunctions::asMoney($request->amount);
			$phoneNumber   = $request->phone_number;
			Logger::logUserActivity("Rejected $airtimeAmount as airtime for $fullName to number: $phoneNumber",'high');
			$status = 1;
		}else{
			$status = 0;
		}
		return $status;
	}

	public static function disburseAirtimeTransaction($id){
		$request = Airtime::model()->findByPk($id);
		$request->status         = '2';
		$request->disbursed_by   = Yii::app()->user->user_id;
		$request->date_disbursed = date('Y-m-d');
		if($request->save()){
			$fullName      = Profiles::model()->findByPk($request->user_id)->ProfileFullName;
			$airtimeAmount = CommonFunctions::asMoney($request->amount);
			$phoneNumber   = $request->phone_number;
			Logger::logUserActivity("Disbursed $airtimeAmount as airtime for $fullName to number: $phoneNumber",'high');
			$status = 1;
		}else{
			$status = 0;
		}
		return $status;
	}

	public static function disburseAirtime($id){
		$username = Yii::app()->params['AfricaStalking_Username'];
		$apikey   = Yii::app()->params['AfricaStalking_Key'];
		$request  = Airtime::model()->findByPk($id);
		if(!empty($request)){
			$amount         = $request->amount;
			$phoneNumber    = "+254".substr($request->phone_number,-9);
			$airtimeService = new AfricasTalking($username, $apikey);
			$airtime        = $airtimeService->airtime();
			$recipients     = [[
				"phoneNumber"  => $phoneNumber,
				"currencyCode" => "KES",
				"amount"       => $amount
			]];
			try{
			    $results = $airtime->send(["recipients" => $recipients]);
			    $status =  !empty($results) ? AirtimeManager::disburseAirtimeTransaction($id) : 0;
			}catch(Exception $e){
			    $status = 0;
			}	
		}else{
			$status = 0;
		}
		return $status;
	}
}