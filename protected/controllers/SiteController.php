<?php

class SiteController extends Controller{
	/**
	 * Declares class-based actions.
	 */
	public function actions(){
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/** Dashboard**/
	public function actionIndex(){
		if(Yii::app()->user->isGuest){
  			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form'){
  				echo CActiveForm::validate($model);
  				Yii::app()->end();
  			}
  			if(isset($_POST['LoginForm'])){
 	 				$model->attributes=$_POST['LoginForm'];
 	 				if($model->validate() && $model->login()){
						$profile = Profiles::model()->findByPk(Yii::app()->user->user_id);
						if(SMS::getSMSAlertStatus($type='16') === 0){
							$token       = CommonFunctions::generateRandomOTP();
							$otpMessage  = "Your one time PIN is : $token";
							$phoneNumber = ProfileEngine::getProfileContactByType($profile->id,'PHONE');
							$numbers     = array();
						 	array_push($numbers,$phoneNumber);
							if(SMS::broadcastSMS($numbers,$otpMessage,$type='16',$profile->id) === 1){
								User::insertOTP($token);
								$type='success';
						    	$message="One time PIN has been sent to your phone. Use it to verify your account.";
								CommonFunctions::setFlashMessage($type,$message);
								$this->redirect(array('token'));
							}else{
								ProfileEngine::persistLastLogInDate($profile->id);
	 	 						$this->redirect(Yii::app()->user->returnUrl);
							}
						}else{
							ProfileEngine::persistLastLogInDate($profile->id);
 	 						$this->redirect(Yii::app()->user->returnUrl);
						}
 	 				}else{
	 					$type='danger';
						$message=$model->login();
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('login'));
	 				}
  			}
  			$this->redirect(array('login'));
  		}else{
	  		$this->redirect(array('dashboard/default'));
 		}
	}
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError(){
		$this->layout='//layouts/templates/error';
		if($error=Yii::app()->errorHandler->error){
			if(Yii::app()->request->isAjaxRequest){
				echo $error['message'];
			}
			else{
				$this->render('error', $error);
			}
		}
	}
	/**Login Page */
	public function actionLogin(){
		$this->layout='//layouts/templates/login';
		$model=new LoginForm;
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['LoginForm'])){
			$model->attributes=$_POST['LoginForm'];
			if($model->validate() && $model->login()){
				$profile     = Profiles::model()->findByPk(Yii::app()->user->user_id);
				if(SMS::getSMSAlertStatus($type='16') === 0){
					$token       = CommonFunctions::generateRandomOTP();
					$otpMessage  = "Your one time PIN is : $token";
					$phoneNumber = ProfileEngine::getProfileContactByType($profile->id,'PHONE');
					$numbers     = array();
					array_push($numbers,$phoneNumber);
					if(SMS::broadcastSMS($numbers,$otpMessage,$type='16',$profile->id) === 1){
						User::insertOTP($token);
						$type     = 'success';
				   		$message  = "One time PIN has been sent to your phone for account verification. OTP expires in 10 minutes.";
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('token'));
					}else{
						ProfileEngine::persistLastLogInDate($profile->id);
	 					$this->redirect(Yii::app()->user->returnUrl);
					}
				}else{
					ProfileEngine::persistLastLogInDate($profile->id);
 					$this->redirect(Yii::app()->user->returnUrl);
				}
			}else{
				$type    = 'danger';
				$message = $model->login();
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('login'));
			}
		}
		$this->render('login',array('model'=>$model));
	}

	public function actionToken(){
		$this->layout='//layouts/templates/login';
		if(isset($_POST['verify_account_cmd'])){
			$otp=$_POST['otp'];
			switch(User::verifyAccount($otp)){
				case 0:
				$type    = 'danger';
				$message = "Invalid OTP or OTP has already expired.";
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('token'));
				break;

				case 1:
				$this->redirect(array('dashboard/default'));
				break;
			}
		}
		$this->render('onetimepwd');
	}

	public function actionRegenerate(){
		if(isset(Yii::app()->user->user_id)){
			$profile     = Profiles::model()->findByPk(Yii::app()->user->user_id);
			$token       = CommonFunctions::generateRandomOTP();
			$otpMessage  = "Your one time PIN is : $token";
			$phoneNumber = ProfileEngine::getProfileContactByType($profile->id,'PHONE');
			$numbers     = array();
			array_push($numbers,$phoneNumber);
			if(SMS::broadcastSMS($numbers,$otpMessage,$type='16',$profile->id) === 1){
				User::insertOTP($token);
				$type     = 'success';
		    	$message  = "One time PIN has been sent to your phone for account verification. OTP expires in 10 minutes.";
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('token'));
			}else{
				$this->redirect(array('login'));
			}
		}else{
			$this->redirect(array('login'));
		}
	}
	
	public function actionForgot(){
		$this->layout='//layouts/templates/login';
		if(isset($_POST['forgot_cmd']) && isset($_POST['email'])){
			$email     = htmlspecialchars($_POST['email']);
			$profileId = ProfileEngine::getProfileByContact($email,'EMAIL');
			switch($profileId){
				case 0:
				$type='danger';
				$message='Email Address provided does not match any on our records.';
				CommonFunctions::setFlashMessage($type,$message);
				break;

				default:
				$auth         = Auths::model()->find('profileId=:a',array(':a'=>$profileId));
				$randomString = CommonFunctions::generateRandomString();
				if(!empty($auth)){
					$hashedToken      = password_hash($randomString,PASSWORD_DEFAULT);
					$auth->resetToken = $hashedToken;
					if($auth->save()){
						$profileFullName = $auth->getAuthProfile()->ProfileFullName;
						//Generate RESET URL
						$confirmationLink = Yii::app()->request->hostInfo.Yii::app()->createUrl('site/reset',array('token'=>$hashedToken,'ID'=>$auth->profileId));
						//Send EMail attaching the link
						$name    = 'Treasure Capital';
						$subject = 'Treasure Capital System: Password Reset Request ';
						$content = "<p>You are receiving this email since you requested to reset your password.</p>
									<p>Your reset request has been received and in order to reset your password kindly use the link below:<p>
									<p>$confirmationLink</p>
									<p>Do not hesitate to reach out if you need help.</p>";
						$message = Mailer::Build($name,$subject,$content, $profileFullName);
						$status  = CommonFunctions::broadcastEmailNotification($email,$subject,$message);
						if($status === 1){
							$type    = 'success';
							$message = 'A notification with instructions to reset your password has been sent to your email address.';
							CommonFunctions::setFlashMessage($type,$message);
							$this->redirect(array('login'));
						}else{
							$type    = 'warning';
							$message = 'Email notification has not been sent. You might be experiencing network connectivity issues.Please try resetting your password later.';
							CommonFunctions::setFlashMessage($type,$message);
						}
					}
				}else{
					$type='danger';
					$message='Email Address provided does not match any on our records.';
					CommonFunctions::setFlashMessage($type,$message);
				}
				break;
			}
		}
		$this->render('forgot');
	}

	public function actionReset(){
		$this->layout='//layouts/templates/login';
		if(isset($_GET['token']) && isset($_GET['ID'])){
			$token   = $_GET['token'];
			$userID  = $_GET['ID'];
			$auth    = Auths::model()->find('profileId=:a AND resetToken=:b',array(':a'=>$userID,':b'=>$token));
			if(!empty($auth)){
				if(isset($_POST['reset_cmd'])){
					$new        =  $_POST['password'];
					$confirm    =  $_POST['confirm_password'];
					$pwd_status = Password::resetUserPassword($auth->profileId,$new,$confirm);
					switch($pwd_status){
						case 0:
						$type    = 'danger';
						$message = 'Password could not be reset. Try again later.';
						CommonFunctions::setFlashMessage($type,$message);
						break;

						case 1:
						$type    = 'success';
						$message = 'Password reset successfully. You can now successfully log in.';
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('login'));
						break;

						case 2:
						$type     = 'warning';
						$message  = 'Passwords do not match. Try again with confirmed passwords.';
						CommonFunctions::setFlashMessage($type,$message);
						break;
					}
				}
				$this->render('reset');
			}else{
				exit();
			}
		}else{
			exit();
		}
	}
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout(){
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}