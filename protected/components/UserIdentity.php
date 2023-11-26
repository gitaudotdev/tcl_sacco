<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity{
	
	const USER_NOT_ACTIVATED  = "Your account is not active. Please contact your system administrator to have your account activated.";
	const USER_PASSWORD_ERROR = "Wrong password or username. Kindly try again with the correct login credentials.";
	const USER_NOT_EXISTING   = "Account Not Found. Kindly register your account first.";

	public function __construct($username, $password){
		$this->username = $username;
		$this->password = $password;
	}

	public function authenticate(){
		$auth = Auths::model()->find('username=:pUsername',array(':pUsername'=>$this->username));
		if(!empty($auth)){
			switch ($auth->authStatus){
				case 'ACTIVE':
				if(password_verify($this->password,$auth->password)){
					$this->setState('user_id', $auth->profileId);
					$this->setState('firstname',$auth->getAuthProfile()->firstName);
					$this->setState('lastname',$auth->getAuthProfile()->lastName);
					$this->setState('username',$auth->username);
					$this->setState('user_level',$auth->AuthLevel);
					$this->setState('user_branch',$auth->getAuthProfile()->branchId);
					$this->errorCode=self::ERROR_NONE;
				}else{
					$this->errorCode=self::USER_PASSWORD_ERROR;
				}
				break;

				default:
				$this->errorCode=self::USER_NOT_ACTIVATED;
				break;
			}
		}
		if(empty($auth)){
			$this->errorCode=self::USER_NOT_EXISTING;
		}
		return !$this->errorCode;
	}

}