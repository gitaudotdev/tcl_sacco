<?php

class CommonFunctions{
/****************************

	DISPLAYING FLASH MESSAGES

********************************/
public static function displayFlashMessage($type){
	CommonFunctions::printFlashMessage($type);
}

public static function setFlashMessage($type,$message){
	return Yii::app()->user->setFlash($type, '<div class="alert alert-'."$type".'">
	        <button type="button" aria-hidden="true" class="close">
	            <i class="now-ui-icons ui-1_simple-remove"></i>
	        </button>
	        <span>'."$message".'</span>
	    </div>');

}

public static function checkIfFlashMessageSet($type){
	return Yii::app()->user->hasFlash($type) ? 1 : 0;
}

public static function printFlashMessage($type){
	if(CommonFunctions::checkIfFlashMessageSet($type)=== 1){
		echo Yii::app()->user->getFlash($type);	
	}
}
/**************************

	GENERATE RANDOM STRINGS

*******************************/
public static function generateRandomString($length=8){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for($i = 0; $i <= $length; $i++){
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

public static function generateRandomOTP(){
  return rand(1000,9999);
}
/***********************************

	BROADCAST EMAILS

**********************************/
public static function broadcastEmailNotification($user_email,$subject,$message){
  Yii::app()->mailer->Host =Yii::app()->params['MAILER_HOST'];
  Yii::app()->mailer->IsSMTP(true);
  Yii::app()->mailer->SMTPDebug  = 1;
  Yii::app()->mailer->SMTPAuth = true;
  Yii::app()->mailer->Username = Yii::app()->params['MAILER_AUTH_USER'];
  Yii::app()->mailer->Password = Yii::app()->params['MAILER_AUTH_PWD'];
  Yii::app()->mailer->SMTPSecure = Yii::app()->params['MAILER_SMTP_PROTOCOL'];
  Yii::app()->mailer->Port = Yii::app()->params['MAILER_PORT_NUMBER'];
  Yii::app()->mailer->From = Yii::app()->params['MAILER_AUTH_SENDER_EMAIL'];
  Yii::app()->mailer->AddReplyTo = '';
  Yii::app()->mailer->FromName = Yii::app()->params['MAILER_AUTH_SENDER_NAME'];
  Yii::app()->mailer->AddAddress($user_email);
  Yii::app()->mailer->Subject = $subject;
  Yii::app()->mailer->Body = $message;
  Yii::app()->mailer->Timeout = 20;
  Yii::app()->mailer->IsHTML(true);
  return Yii::app()->mailer->Send() ? 1 : 0;
}

public static function displayFirstFourCharactersOnly($word){
	return strtoupper(substr($word,0,4));
}

public static function asMoney($value){
	return number_format($value,2);
}

public static function shortenSentence($sentence, $replaceWith, $wordLimit){
	return strlen($sentence) > $wordLimit ? substr($sentence, 0, $wordLimit) . $replaceWith : $sentence;
}

public static function getDatesDifference($startDate,$endDate){
	$datetime1 = new DateTime($startDate);
	$datetime2 = new DateTime($endDate);
	$interval  = $datetime1->diff($datetime2);
	$difference=(int)$interval->format('%R%a');
	return $difference <= 0 ? 0 : $difference;
}

public static function reArrayFiles($file){
  $file_ary = array();
  $file_count = count($file['name']);
  $file_key = array_keys($file);
  for($i=0;$i<$file_count;$i++){
    foreach($file_key as $val){
      $file_ary[$i][$val] = $file[$val][$i];
    }
  }
  return $file_ary;
}

public static function getRespectiveMonth($month_date){
	$monthDate      = explode('-',$month_date);
	$extractedMonth = $monthDate[0];
	$extractedYear  = $monthDate[1];
	$dateObject     = DateTime::createFromFormat('!m', $extractedMonth);
	$monthName      = $dateObject->format('M'); 
	return strtoupper($monthName).'-'.$extractedYear;
}

public static function generateToken($tokenLength=32){
	$tokenLength = ($tokenLength < 4) ? 4 : $tokenLength;
	return bin2hex(openssl_random_pseudo_bytes(($tokenLength-($tokenLength%2))/2));
}

public static function searchElementInArray($element,$array){
	return in_array($element, $array) ? 1 : 0;
}

public static function exportExcelFile($exportName,$records){
	$filename = $exportName."_".date("YmdHis")."_".CommonFunctions::generateToken(8).".xls";		 
  	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$filename\"");
	$heading = false;
	if(!empty($records)){
	  foreach($records as $row) {
		if(!$heading){
			echo implode("\t", array_keys($row))."\n";
			$heading = true;
		}
		echo implode("\t", array_values($row))."\n";
	  }
	}
	exit;
}

public static function getMonthName($monthNumber){
	return ucfirst(date("F", mktime(0, 0, 0, $monthNumber, 10)));
}
/**************************************
	
	FILE STATUS

	0: Wrong File Extension 
	2: File Too Large (more than 2.5 MB)
	3: Failed to upload file
	default : File Uploaded successfully

*****************************************/
public static function saveUploadedonDirectory($fileTempName,$fileName,$fileSize,$uploadDirectory){
  $extension = pathinfo($fileName, PATHINFO_EXTENSION);
  $hashedFileName=CommonFunctions::generateToken(7).''.date('YmdHis',time()).mt_rand().'.'.$extension;
  $destination = $uploadDirectory."/".$hashedFileName;
  if(!in_array($extension, ['pdf','jpg','jpeg','docx','doc','png'])){
    $fileStatus=0;
  }elseif($fileSize > 3500000){ 
    $fileStatus=2;
  }else{
    if(move_uploaded_file($fileTempName,$destination)){
      $fileStatus=$hashedFileName;
    }else{
      $fileStatus=3;
    } 
  }
  return $fileStatus;
}

public static function formatFileSizeInUnits($bytes){
  if($bytes >= 1073741824){
      $bytes = number_format($bytes / 1073741824, 2) . ' GB';
  }elseif ($bytes >= 1048576){
      $bytes = number_format($bytes / 1048576, 2) . ' MB';
  }elseif ($bytes >= 1024){
      $bytes = number_format($bytes / 1024, 2) . ' KB';
  }elseif ($bytes > 1){
      $bytes = $bytes . ' bytes';
  }elseif($bytes == 1){
      $bytes = $bytes . ' byte';
  }else{
      $bytes = '0 bytes';
  }
  return $bytes;
}


}