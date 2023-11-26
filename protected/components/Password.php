<?php

class Password{

	public static function changeUserPassword($user,$current,$new,$confirm){
		$status  = Password::checkIfCurrentPasswordExists($user,$current,$new,$confirm);
		switch($status){
			case 0:
			return 0;
			break;

			case 1:
			$auth = Auths::model()->find('profileId=:a',array(':a'=>$user));
			$auth->password= password_hash($confirm,PASSWORD_DEFAULT);
			if($auth->update()){
				ProfileEngine::persistPasswordUpdateDate($auth->profileId);
				return 1;
			}
			break;

			case 2:
			return 2;
			break;

			case 3:
			return 3;
			break;
		}
	}

	public static function checkIfCurrentPasswordExists($user,$current,$new, $confirm){
		$auth = Auths::model()->find('profileId=:a',array(':a'=>$user));
		if(!empty($auth)){
			if(password_verify($current, $auth->password)){
				return Password::checkIfPasswordConfirmed($new,$confirm,$current);
			}else{
				return 2;
			}
		}
	}

	public static function checkIfPasswordConfirmed($new,$confirm,$current){
		$comparison = Password::compareNewPasswordWithCurrentPassword($current,$new);
		if($comparison === 2){
			return 3;
		}else{
			if($new === $confirm){
				return 1;
			}
			if($new != $confirm){
				return 0;
			}
		}
	}

	public static function compareNewPasswordWithCurrentPassword($current, $new){
		if($new === $current){
			return 2;
		}
		if($new != $current){
			return 1;
		}
	}

	public static function resetUserPassword($profileId,$new,$confirm){
		if($new === $confirm){
			$auth  =  Auths::model()->find('profileId=:a',array(':a'=>$profileId));
			$auth->password=password_hash($confirm,PASSWORD_DEFAULT);
			if($auth->save()){
				ProfileEngine::persistPasswordUpdateDate($profileId);
				return 1;
			}else{
				return 0;
			}
		}else{
			return 2;
		}
	}
}