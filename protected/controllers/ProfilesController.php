<?php

class ProfilesController extends Controller{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/templates/pages';
	/**
	 * @return array action filters
	 */
	public function filters(){
		return array(
			'accessControl', 
		);
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules(){
		return array(
			array('allow',
				'actions'=>array('addProfile','view','update','adminMembers','migrateUsers','addContact','addEmployment',
				'addReferee','addKin','addResidence','resetPassword','sendSMSNotification','assign','assignCommit','reassign',
				'commitReassignment','addAccountSetting','addLoanAccount','addSavingAccount','migrateStaffConfigs','migrateUsersConfigs',
				'payroll','filterPayroll','payment', 'staff','suspendProfile','activateProfile','membersReport',
				'checkIdNumberExistence','checkPhoneNumberExistence','checkUsernameExistence','updateAlerts'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCheckIdNumberExistence(){
		echo ProfileEngine::enforceIdNumberUniqueness(htmlspecialchars($_POST['idNumber']));
	}

	public function actionCheckPhoneNumberExistence(){
		echo ProfileEngine::enforceContactUniqueness(htmlspecialchars($_POST['phoneNumber']));
	}

	public function actionCheckUsernameExistence(){
		echo ProfileEngine::enforceUsernameUniqueness(htmlspecialchars($_POST['username']));
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
		$model = $this->loadModel($id);
		if(!empty($model)){
			switch(Navigation::checkIfAuthorized(17)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to view users/members.");
				$this->redirect(array('admin'));
				break;
	
				case 1:
				$kins           = ProfileEngine::getProfileRelationsByType($id,"NEXTOFKIN");
				$loans          = ProfileEngine::getProfileLoanAccounts($id);
				$referees       = ProfileEngine::getProfileRelationsByType($id,"REFEREE");
				$savingAccounts = SavingFunctions::getAllUserSavingAccounts($id);
				$contacts       = ProfileEngine::getProfileContactsByType($id,"ALL");
				$employments    = ProfileEngine::getProfileEmployments($id);
				$residences     = ProfileEngine::getProfileResidences($id);
				$configs        = ProfileEngine::getProfileAccountSettings($id,"ALL");
				$notifications  = SMS::getProfileNotifications($id);
				$this->render('view',array('model'=>$model,'kins'=>$kins,'loans'=>$loans,'savingAccounts'=>$savingAccounts,'referees'=>$referees,
				'contacts'=>$contacts,'employments'=>$employments,'residences'=>$residences,'configs'=>$configs,'notifications'=>$notifications));
				break;
			}
		}else{
			CommonFunctions::setFlashMessage('danger',"Member with the specified ID not found.");
			$this->redirect(array('admin'));
		}
	}

	public function actionUpdateAlerts(){
		$alertQuery = "SELECT * FROM sms_alerts WHERE phone_number !='0' AND profileId IS NULL";
		$alerts = SmsAlerts::model()->findAllBySql($alertQuery);
		foreach($alerts AS $alert){
			$sPhone = substr($alert->phone_number,-9);
			$contactQuery = "SELECT profileId FROM contacts WHERE contactType='PHONE' AND contactValue='$sPhone'";
			$contact = Contacts::model()->findBySql($contactQuery);
			if(!empty($contact)){
				$profile = Profiles::model()->findByPk($contact->profileId);
				if(!empty($profile)){
					$alert->profileId = $profile->id;
					$alert->branchId  = $profile->branchId;
					$alert->managerId = $profile->managerId;
					$alert->update();
					echo "Updated Alert <br>";
				}
			}
		}
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
		switch(Navigation::checkIfAuthorized(16)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update users/members.");
			$this->redirect(array('admin'));
			break;
	
			case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Profiles'])){
				$model->attributes=$_POST['Profiles'];
                //var_dump($model);exit;
				if($model->save()){
					$profileId = $model->id;
					$branchId  = $model->branchId;
					$managerId = $model->managerId;
					$idNumber  = $model->idNumber;
					ProfileEngine::propagateProfileUpdate($profileId,$branchId,$managerId,$idNumber);
					Logger::logUserActivity("Updated user account : $model->ProfileFullName",'urgent');
					CommonFunctions::setFlashMessage('success',"Account profile have been updated successfully.");
					$this->redirect(array('admin'));
				}
			}
			$this->render('update',array('model'=>$model));
			break;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(17)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view users.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=new Profiles('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Profiles'])){
				$model->attributes=$_GET['Profiles'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}

	public function actionStaff(){
		switch(Navigation::checkIfAuthorized(23)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view staff members.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=new Profiles('searchStaff');
			$model->unsetAttributes();
			if(isset($_GET['Profiles'])){
				$model->attributes=$_GET['Profiles'];
			}
			$this->render('staff',array('model'=>$model));
    		break;
    	}
	}

	public function actionAdminMembers(){
		$model=new Profiles('searchMembers');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Profiles'])){
			$model->attributes=$_GET['Profiles'];
		}
		$this->render('adminMembers',array('model'=>$model));
	}

	public function actionMigrateUsers(){
		$users = Users::model()->findAll();
		foreach($users AS $user){
			$profile = new Profiles;
			$profile->id = $user->user_id;
			$profile->branchId = $user->branch_id;
			$profile->managerId = $user->rm;
			if($user->level == '3'){
				$profileType ='MEMBER';
			}else if($user->level == '5'){
				$profileType = 'SUPPLIER';
			}else{
				$profileType = 'STAFF';
			}
			$profile->profileType = $profileType;
			$profile->firstName = $user->first_name;
			$profile->lastName =  $user->last_name;
			$profile->gender   =  strtoupper($user->gender);
			$profile->birthDate = $user->dateOfBirth;
			$profile->idNumber = $user->id_number;
			$profile->kraPIN   = $user->kra_pin;
			$profile->createdAt = $user->created_at;
			$profile->createdBy = $user->created_by;
			if($profile->save()){
				$auth = new Auths;
				$auth->profileId = $profile->id;
				$auth->username = $user->username;
				$auth->password = $user->password;
				if($user->level == '0'){
					$authLevel ='SUPERADMIN';
				}else if($user->level == '1'){
					$authLevel = 'ADMIN';
				}else if($user->level == '2'){
					$authLevel = 'STAFF';
				}else{
					$authLevel = 'USER';
				}
				$auth->level = $authLevel;
				if($user->is_active === '0'){
					$activeStatus ='DORMANT';
				}else{
					$activeStatus = 'ACTIVE';
				}
				$auth->authStatus = $activeStatus;
				$auth->resetToken = $user->token;
				$auth->lastLoggedAt = $user->last_login;
				$auth->createdAt = $user->created_at; 
				$auth->createdBy =$user->created_by;
				$auth->updatedAt = $user->updated_at;
				if($auth->save()){
					echo "User saved and auth details saved <br>";
				}
			}else{
				echo "Nothing migrated";
			}
		}
	}

	public function actionAddProfile(){
		switch(Navigation::checkIfAuthorized(15)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create users/members.");
			$this->redirect(array('admin'));
			break;
	
			case 1:
			if(isset($_POST['addProfileCmd'])){
				$branchId    = htmlspecialchars($_POST['branchId']);
                $clientCategoryClass  = htmlspecialchars($_POST['clientCategoryClass']);
				$managerId   = htmlspecialchars($_POST['managerId']);
				$profileType = isset($_POST['profileType']) ? htmlspecialchars($_POST['profileType']) : 'MEMBER';
				$firstName   = htmlspecialchars($_POST['firstName']);
				$lastName    = htmlspecialchars($_POST['lastName']);
				$gender      = htmlspecialchars($_POST['gender']);
				$birthDate   = htmlspecialchars($_POST['birthDate']);
				$username    = htmlspecialchars($_POST['username']);
				$password    = htmlspecialchars($_POST['password']);
				$level       = isset($_POST['level']) ? htmlspecialchars($_POST['level']) : 'USER';
				$phoneNumber = htmlspecialchars($_POST['phoneNumber']);
				$idNumber    = htmlspecialchars($_POST['idNumber']);
                //var_dump($phoneNumber);exit;
				//switch(ProfileEngine::createUserProfile($branchId,$managerId,$profileType,$firstName,$lastName, $gender,$birthDate,$username,$password,$level,$phoneNumber,$idNumber)){
				switch(ProfileEngine::createUserProfile($branchId,$clientCategoryClass,$managerId,$profileType,$firstName,$lastName, $gender,$birthDate,$username,$password,$level,$phoneNumber,$idNumber)){
					case 1000:
					Logger::logUserActivity("Created user account and profile : $firstName $lastName",'urgent');
					CommonFunctions::setFlashMessage('success',"Account and login profile have been created successfully.");
					$this->redirect(array('admin'));
					break;
		
					case 1003:
					CommonFunctions::setFlashMessage('danger',"Failed! The username has already been taken.");
					$this->redirect(array('addProfile'));
					break;
		
					case 1004:
					CommonFunctions::setFlashMessage('danger',"Failed! Login Profile failed to create.");
					$this->redirect(array('addProfile'));
					break;
		
					case 1005:
					CommonFunctions::setFlashMessage('danger',"Failed! Account profile not created.");
					$this->redirect(array('addProfile'));
					break;

					case 1006:
					CommonFunctions::setFlashMessage('danger',"Failed! Id Number already exists ...");
					$this->redirect(array('addProfile'));
					break;
		
					default:
					CommonFunctions::setFlashMessage('danger',"Failed! An error occurred while creating account and login profile.");
					$this->redirect(array('admin'));
					break;
				}
			}
			$this->render('addProfile', array('branches'=>Reports::getAllBranches()));
			break;
		}
	}


	public function actionAddContact($id){
		switch(Navigation::checkIfAuthorized(8)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to add user/member contact.");
			$this->redirect(array('view','id'=>$id));
			break;
	
			case 1:
			$model = $this->loadModel($id);
			if(isset($_POST['addProfileContactCmd'])){
				switch(ProfileEngine::enforceContactUniqueness($_POST['contactValue'])){
					case 1000:
					$contact = new Contacts;
					$contact->profileId    = $id;
					$contact->contactType  = htmlspecialchars($_POST['contactType']);
					$contact->contactValue = htmlspecialchars($_POST['contactValue']);
					$contact->createdAt    = date('Y-m-d H:i:s');
					$contact->createdBY    = Yii::app()->user->user_id;
					if($contact->save()){
						Logger::logUserActivity("Added user account contact details : $model->ProfileFullName",'high');
						CommonFunctions::setFlashMessage('success',"Profile contact successfully added.");
						$this->redirect(array('view','id'=>$model->id));
					}
					break;
	
					case 1001:
					CommonFunctions::setFlashMessage('danger',"Failed! Contact already exists.");
					$this->redirect(array('addContact','id'=>$model->id));
					break;
	
					default:
					CommonFunctions::setFlashMessage('danger',"Failed! An error occurred while trying to create contact.");
					$this->redirect(array('addContact','id'=>$model->id));
					break;
				}
			}
			$this->render('addContact',array('model'=>$model));
			break;
		}
	}

	public function actionAddEmployment($id){
		switch(Navigation::checkIfAuthorized(7)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to add user/member employment details.");
			$this->redirect(array('view','id'=>$id));
			break;
	
			case 1:
			$model = $this->loadModel($id);
			if(isset($_POST['addProfileEmploymentCmd'])){
				switch(ProfileEngine::enforceEmploymentUniqueness($id,$_POST['employer'])){
					case 1000:
					ProfileEngine::markEmploymentsOrResidencesAsPast($id,'employments');
					$employment = new Employments;
					$employment->profileId    = $id;
					$employment->industryType = htmlspecialchars($_POST['industryType']);
					$employment->employer     = htmlspecialchars($_POST['employer']);
					$employment->landMark     = htmlspecialchars($_POST['landMark']);
					$employment->salaryBand   = Yii::app()->params['DEFAULTSALARYBAND'];;
					$employment->town         = htmlspecialchars($_POST['town']);
					$employment->dateEmployed = htmlspecialchars($_POST['dateEmployed']);
					$employment->contactPhone = htmlspecialchars($_POST['contactPhone']);
					$employment->createdAt    = date('Y-m-d H:i:s');
					$employment->createdBy    = Yii::app()->user->user_id;
					if($employment->save()){
						Logger::logUserActivity("Added user account employment details : $model->ProfileFullName",'high');
						CommonFunctions::setFlashMessage('success',"Profile employment successfully added.");
						$this->redirect(array('view','id'=>$model->id));
					}
					break;
	
					case 1001:
					CommonFunctions::setFlashMessage('danger',"Failed! Employment already exists.");
					$this->redirect(array('addEmployment','id'=>$model->id));
					break;
	
					default:
					CommonFunctions::setFlashMessage('danger',"Failed! An error occurred while trying to create an employment.");
					$this->redirect(array('addEmployment','id'=>$model->id));
					break;
				}
			}
			$this->render('addEmployment',array('model'=>$model));
			break;
		}
	}

	public function actionAddReferee($id){
		switch(Navigation::checkIfAuthorized(11)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to add user/member referee.");
			$this->redirect(array('view','id'=>$id));
			break;
	
			case 1:
			$model = $this->loadModel($id);
			if(isset($_POST['addProfileRefereeCmd'])){
				switch(ProfileEngine::enforceRelationPhoneUniqueness($_POST['phoneNumber'])){
					case 1000:
					$referee = new Dependencies;
					$referee->profileId    = $id;
					$referee->relationType = 'REFEREE';
					$referee->firstName    = htmlspecialchars($_POST['firstName']);
					$referee->lastName     = htmlspecialchars($_POST['lastName']);
					$referee->relation     = htmlspecialchars($_POST['relation']);
					$referee->phoneNumber  = htmlspecialchars($_POST['phoneNumber']);
					$referee->createdAt    = date('Y-m-d H:i:s');
					$referee->createdBy    = Yii::app()->user->user_id;
					if($referee->save()){
						Logger::logUserActivity("Added user account referee details : $model->ProfileFullName",'high');
						CommonFunctions::setFlashMessage('success',"Profile referee successfully added.");
						$this->redirect(array('view','id'=>$model->id));
					}
					break;
	
					case 1001:
					CommonFunctions::setFlashMessage('danger',"Failed! Referee phone number already exists.");
					$this->redirect(array('addReferee','id'=>$model->id));
					break;
	
					default:
					CommonFunctions::setFlashMessage('danger',"Failed! An error occurred while trying to create a referee.");
					$this->redirect(array('addReferee','id'=>$model->id));
					break;
				}
			}
			$this->render('addReferee',array('model'=>$model));
			break;
		}
	}

	public function actionAddKin($id){
		switch(Navigation::checkIfAuthorized(10)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to add user next of kin.");
			$this->redirect(array('view','id'=>$id));
			break;
	
			case 1:
			$model = $this->loadModel($id);
			if(isset($_POST['addProfileKinCmd'])){
				switch(ProfileEngine::enforceRelationPhoneUniqueness($_POST['phoneNumber'])){
					case 1000:
					$kin = new Dependencies;
					$kin->profileId    = $id;
					$kin->relationType = 'NEXTOFKIN';
					$kin->firstName    = htmlspecialchars($_POST['firstName']);
					$kin->lastName     = htmlspecialchars($_POST['lastName']);
					$kin->relation     = htmlspecialchars($_POST['relation']);
					$kin->phoneNumber  = htmlspecialchars($_POST['phoneNumber']);
					$kin->createdAt    = date('Y-m-d H:i:s');
					$kin->createdBy    = Yii::app()->user->user_id;
					if($kin->save()){
						Logger::logUserActivity("Added user account next of kin details : $model->ProfileFullName",'high');
						CommonFunctions::setFlashMessage('success',"Profile next of kin successfully added.");
						$this->redirect(array('view','id'=>$model->id));
					}
					break;
	
					case 1001:
					CommonFunctions::setFlashMessage('danger',"Failed! Next of kin phone number already exists.");
					$this->redirect(array('addKin','id'=>$model->id));
					break;
	
					default:
					CommonFunctions::setFlashMessage('danger',"Failed! An error occurred while trying to create next of kin.");
					$this->redirect(array('addKin','id'=>$model->id));
					break;
				}
			}
			$this->render('addKin',array('model'=>$model));
			break;
		}
	}

	public function actionAddResidence($id){
		switch(Navigation::checkIfAuthorized(6)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to add user/member residence details.");
			$this->redirect(array('view','id'=>$id));
			break;
	
			case 1:
			$model = $this->loadModel($id);
			if(isset($_POST['addProfileResidenceCmd'])){
				switch(ProfileEngine::enforceResidenceUniqueness($id,$_POST['residence'])){
					case 1000:
					ProfileEngine::markEmploymentsOrResidencesAsPast($id,'residences');
					$residence = new Residences;
					$residence->profileId    = $id;
					$residence->residence    = htmlspecialchars($_POST['residence']);
					$residence->landMark     = htmlspecialchars($_POST['landMark']);
					$residence->town         = htmlspecialchars($_POST['town']);
					$residence->createdAt    = date('Y-m-d H:i:s');
					$residence->createdBy    = Yii::app()->user->user_id;
					if($residence->save()){
						Logger::logUserActivity("Added user account residence details : $model->ProfileFullName",'high');
						CommonFunctions::setFlashMessage('success',"Profile residence successfully added.");
						$this->redirect(array('view','id'=>$model->id));
					}
					break;
	
					case 1001:
					CommonFunctions::setFlashMessage('danger',"Failed! Residence already exists.");
					$this->redirect(array('addResidence','id'=>$model->id));
					break;
	
					default:
					CommonFunctions::setFlashMessage('danger',"Failed! An error occurred while trying to create residence.");
					$this->redirect(array('addResidence','id'=>$model->id));
					break;
				}
			}
			$this->render('addResidence',array('model'=>$model));
			break;
		}
	}

	public function actionResetPassword($id){
		$model           = $this->loadModel($id);
		$profileId       = $model->id;
		$profileName     = $model->ProfileFullName;
		$profileLastName = $model->lastName;
		$profileUsername = $model->ProfileUsername;
		switch(Navigation::checkIfAuthorized(18)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to reset user passwords.");
			$this->redirect(array('admin'));
			break;

			case 1:
			$auth         = Auths::model()->find('profileId=:a',array(':a'=>$profileId));
			$random       = CommonFunctions::generateRandomString();
			$emailAddress = ProfileEngine::getProfileContactByTypeOrderDesc($profileId,'EMAIL');
			$phoneNumber  = ProfileEngine::getProfileContactByTypeOrderDesc($profileId,'PHONE');
			if(Password::resetUserPassword($profileId,$random,$random) == 1){
				Logger::logUserActivity("Reset system user password for : $profileName",'high');
				$websiteLink = Yii::app()->params['website'];
				$textMessage = "Dear $profileLastName, Your password was successfully reset.
				Please use the following credentials:
				Username - $profileUsername and Password - $random to log onto $websiteLink";
				if(is_numeric($emailAddress)){
					$numbers  = array();
					array_push($numbers,$emailAddress);
					SMS::broadcastSMS($numbers,$textMessage,'1',$id);
				}else{
					$numbers     = array();
					array_push($numbers,$phoneNumber);
					SMS::broadcastSMS($numbers,$textMessage,'1',$id);
					$name       = 'IT Service Desk';
					$subject    = 'Password Reset Service';
					$body       = "<p>Welcome to Treasure Capital Systems.</p>
					<p>Your password was successfully reset by the system administrator.</p>
					<p>Please log in with the following credentials:</p>
					<p><strong>Username - $profileUsername<br>Password - $random </strong>  to log onto $websiteLink</p>
					<p>Do not hesitate to reach out if you need help.</p>";
					$message     = Mailer::Build($name,$subject,$body,$profileLastName);
					$emailStatus = CommonFunctions::broadcastEmailNotification($emailAddress,$subject,$message);
				}
				CommonFunctions::setFlashMessage('success',"Password successfully reset.");
			}else{
				CommonFunctions::setFlashMessage('warning',"Password could not be reset.");
			}
			$this->redirect(array('admin'));
			break;
		}
	}

	public function actionSendSMSNotification($id){
		$model               = $this->loadModel($id);
		$profileName         = $model->ProfileFullName;
		$profilePhoneNumber  = ProfileEngine::getProfileContactByTypeOrderDesc($id,'PHONE');
		switch(Navigation::checkIfAuthorized(9)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to send SMS to a member.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$numbers = array();
			array_push($numbers,$profilePhoneNumber);
			if(isset($_POST['send_txt_cmd'])){
				$textMessage = htmlspecialchars($_POST['textMessage']);
				$status      = SMS::broadcastSMS($numbers,$textMessage,'28',$id);
				switch($status){
					case 0:
					$type    = 'danger';
					$message = "Error occurred while sending SMS. Please ensure all phone numbers are available and in the correct format.";
					break;

					case 1:
					$type    = 'success';
					$message = "SMS Sent successfully";
					Logger::logUserActivity("Sent Member SMS Message: <strong>$textMessage</strong>: $profileName",'urgent');
					break;

					case 2:
					$type    = 'danger';
					$message = "An error occurred while trying to send the SMS. Consult your SMS service provider.";
					break;

					case 3:
					$type    = 'danger';
					$message = "The SMS category has been deactivated. Ask the Administrator to activate the category.";
					break;

					case 5:
					$type    = 'danger';
					$message = "SMS notification failed since the user record not found.";
					break;

					case 6:
					$type    = 'danger';
					$message = "The SMS notification cannot be initiated to the user since the user's SMS Alerts setting is DISABLED.";
					break;
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('view','id'=>$model->id));
			}
			break;
		}
	}

	public function actionAssign($id){
		$roles   = Roles::model()->findAll();
		switch(Navigation::checkIfAuthorized(27)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to assign roles.");
			$this->redirect(Yii::app()->request->urlReferrer);
			break;

			case 1:
			switch(Permission::checkIfUserAssignedRole($id)){
				case 0:
				$this->render('assign',array('roles'=>$roles,'id'=>$id));
				break;

				case 1:
				CommonFunctions::setFlashMessage('info',"User has already been assigned a role.");
				$this->redirect(array('admin'));
				break;
			}
			break;
		}
	}

	public function actionReassign($id){
		switch(Navigation::checkIfAuthorized(26)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to reassign staff member a role.");
			$this->redirect(Yii::app()->request->urlReferrer);
			break;

			case 1:
			$userID      = $id;
			$checkSQL    = "SELECT role_id FROM user_role WHERE user_id=$userID";
			$checker     = UserRole::model()->findBySql($checkSQL);
			$roleID      = $checker->role_id;
			$currentRole = Roles::model()->findByPk($roleID);
			$roleSQL     = "SELECT * FROM roles WHERE role_id<>$roleID";
			$roles       = Roles::model()->findAllBySql($roleSQL);
			$this->render('reassign',array('roles'=>$roles,'id'=>$id,'currentRole'=>$currentRole));	
			break;
		}
	}

	public function actionCommitReassignment(){
    	switch(Navigation::checkIfAuthorized(26)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to reassign roles.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
			if(isset($_POST['reassign_role_cmd']) && isset($_POST['roles'])){
				$roleUserId = $_POST['user'];
				$roleSQL    = "SELECT * FROM user_role WHERE user_id=$roleUserId LIMIT 1";
				$roles      =  UserRole::model()->findBySql($roleSQL);
				if(!empty($roles)){
					$profile     = Profiles::model()->findByPk($roleUserId);
					$profileName = $profile->ProfileFullName;
					$roleName    = Roles::model()->findByPk($_POST['roles'])->name;
					$user_role   = UserRole::model()->findByPk($roles->id);
					$user_role->user_id    = $roleUserId;
					$user_role->role_id    = $_POST['roles'];
					$user_role->created_by = Yii::app()->user->user_id;
					$user_role->created_at = date('Y-m-d H:i:s');
					$user_role->save();
					Logger::logUserActivity("Reassigned user: $profileName, role: $roleName",'high');
					CommonFunctions::setFlashMessage('success',"User successfully reassigned the role.");
					$this->redirect(array('admin'));
				}else{
					CommonFunctions::setFlashMessage('danger',"The user has not been reassigned this role.");
					$this->redirect(array('profiles/reassign/'.$roleUserId));
				}
			}else{
			CommonFunctions::setFlashMessage('warning',"Kindly select a role to reassign the user.");
			$this->redirect(array('admin'));
			}
	  	 	break;
	  	}
	}

	public function actionAssignCommit(){
		switch(Navigation::checkIfAuthorized(27)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to assign roles.");
			$this->redirect(Yii::app()->request->urlReferrer);
			break;

			case 1:
			$userID  = $_POST['user'];
			if(isset($_POST['assign_role_cmd']) && isset($_POST['roles'])){
				$profile     = Profiles::model()->findByPk($userID);
				$profileName = $profile->ProfileFullName;
				$roleName    = Roles::model()->findByPk($_POST['roles'])->name;
				$user_role             = new UserRole;
				$user_role->user_id    = $_POST['user'];
				$user_role->role_id    = $_POST['roles'];
				$user_role->created_by = Yii::app()->user->user_id;
				$user_role->created_at = date('Y-m-d H:i:s');
				$user_role->save();
				Logger::logUserActivity("Assigned user:$profileName, role: $roleName",'high');
				CommonFunctions::setFlashMessage('success',"User successfully assigned a role.");
				$this->redirect(array('admin'));
			}else{
				CommonFunctions::setFlashMessage('warning',"Kindly select a role to assign.");
				$this->redirect(array('profiles/assign/'.$userID));
			}
			break;
		}
	}

	public function actionAddAccountSetting($id){
		switch(Navigation::checkIfAuthorized(5)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to add user/member account settings.");
			$this->redirect(array('view','id'=>$id));
			break;

			case 1:
			$model       = $this->loadModel($id);
			$configTypes = ProfileEngine::getProfileConfigTypes($id);
			if(!empty($configTypes)){
				if(isset($_POST['addAccountSettingCmd'])){
					ProfileEngine::previousSettingAsPast($id,$_POST['configType']);
					$setting = new AccountSettings;
					$setting->profileId    = $id;
					$setting->configType   = htmlspecialchars($_POST['configType']);
					$setting->configValue  = htmlspecialchars($_POST['configValue']);
					$setting->createdAt    = date('Y-m-d H:i:s');
					$setting->createdBy    = Yii::app()->user->user_id;
					if($setting->save()){
						Logger::logUserActivity("Added user account setting : $model->ProfileFullName",'high');
						CommonFunctions::setFlashMessage('success',"Profile setting successfully added.");
						$this->redirect(array('view','id'=>$model->id));
					}else{
						CommonFunctions::setFlashMessage('danger',"Failed! An error occurred while trying to add account setting.");
						$this->redirect(array('addAccountSetting','id'=>$model->id));
					}
				}
				$this->render('addAccountSetting',array('model'=>$model,'configTypes'=>$configTypes));
			}else{
				CommonFunctions::setFlashMessage('danger',"Operation failed. The user/member has already been assigned 
				to existing setting types. Kindly just update any on the user account.");
				$this->redirect(array('view','id'=>$model->id));
			}
			break;
		}
	}

	public function actionAddLoanAccount($id){
		$model    = $this->loadModel($id);
		$fullName = $model->ProfileFullName;
		switch(Navigation::checkIfAuthorized(12)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create user/member loan account.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model    = $this->loadModel($id);
			$managers = ProfileEngine::getLoanDirectionProfiles();
			if(isset($_POST['apply_loan_cmd'])){
				if(LoanApplication::restrictMultipleRunningAccounts($model->id) === 1){
					$type    = 'danger';
					$message = "Operation failed! The member has a loan account already submitted or in progress.";
				}else{
					switch(LoanApplication::createNewApplication($_POST)){
						case 0:
						$type    = 'danger';
						$message = "Operation failed! Application not submitted. Please check the details submitted.";
						break;
	
						case 1:
						Logger::logUserActivity("Added Member Loan : $fullName",'urgent');
						$type    = 'success';
						$message = "Application submitted successfully.";
						break;
	
						case 2:
						$type    = 'danger';
						$message = "Operation failed! Application not submitted since the amount applied will exceed the client default set loan limit";
						break;

						case 3:
						$type    = 'danger';
						$message = "Operation failed! Application not submitted since the member is not active.";
						break;
					}
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('view','id'=>$model->id));
			}
			$this->render('addLoanAccount',array('model'=>$model,'managers'=>$managers));
			break;
		}
	}

	public function actionAddSavingAccount($id){
		$model         = $this->loadModel($id);
		$fullName      = $model->ProfileFullName;
		$accountNumber = $model->ProfilePhoneNumber;
		$defaultRate   = ProfileEngine::getActiveProfileAccountSettingByType($model->id,'SAVINGS_INTEREST_RATE');
		$interestRate  = $defaultRate === 'NOT SET' ? Yii::app()->params['DEFAULTSAVINGSINTEREST'] : floatval($defaultRate);
		if($accountNumber != "" && !empty($accountNumber) && !is_null($accountNumber)){
			switch(Navigation::checkIfAuthorized(13)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to create Member saving account.");
				$this->redirect(array('dashboard/default'));
				break;
	
				case 1:
				if(isset($_POST['apply_loan_cmd'])){
					if(SavingFunctions::checkIfDuplicateRecordExists($model->id) === 1){
						$type    = 'danger';
						$message = "Operation failed! The member has an existing savings account.";
					}else{
						$account = new Savingaccounts;
						$account->user_id        = $model->id; 
						$account->branch_id      = $model->branchId;
						$account->rm             = $model->managerId;
						$account->account_number = $accountNumber;
						$account->opening_balance= 0;
						$account->fixed_period   = 0;
						$account->type           = 'open';
						$account->interest_rate  = $interestRate;
						$account->is_approved    = '0';
						$account->created_by     = Yii::app()->user->user_id;
						$account->created_at     = date('Y-m-d H:i:s');
						if($account->save()){
							Logger::logUserActivity("Added Member Saving account : $fullName",'urgent');
							$type    = 'success';
							$message = "Saving account created successfully.";
						}else{
							$type    = 'danger';
							$message = "Operation failed! Saving account not created. Please check the details submitted.";
						}
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('view','id' => $model->id));
				}
				$this->render('addSavingAccount',array('model' => $model));
				break;
			}
		}else{
			CommonFunctions::setFlashMessage('danger','The member does not have a primary phone Number. Kindly add a phone number and make it primary.');
			$this->redirect(array('view','id' => $model->id));
		}
	}

	public function actionMigrateStaffConfigs(){
		$members = Staff::model()->findAll();
		foreach($members AS $member){

			if($member->payroll_listed == '0'){
				ProfileEngine::setProfileAccountSetting($member->user_id,'PAYROLL_LISTED','DISABLED');
			}else{
				ProfileEngine::setProfileAccountSetting($member->user_id,'PAYROLL_LISTED','ACTIVE');
			}

			if($member->commentsDashboard_listed == '0'){
				ProfileEngine::setProfileAccountSetting($member->user_id,'COMMENTS_DASHBOARD_LISTED','DISABLED');
			}else{
				ProfileEngine::setProfileAccountSetting($member->user_id,'COMMENTS_DASHBOARD_LISTED','ACTIVE');
			}

			if($member->payroll_auto_process == '0'){
				ProfileEngine::setProfileAccountSetting($member->user_id,'PAYROLL_AUTO_PROCESS','DISABLED');
			}else{
				ProfileEngine::setProfileAccountSetting($member->user_id,'PAYROLL_AUTO_PROCESS','ACTIVE');
			}

			if($member->is_supervisor == '0'){
				ProfileEngine::setProfileAccountSetting($member->user_id,'SUPERVISORIAL_ROLE','DISABLED');
			}else{
				ProfileEngine::setProfileAccountSetting($member->user_id,'SUPERVISORIAL_ROLE','ACTIVE');
			}

			ProfileEngine::setProfileAccountSetting($member->user_id,'SALES_TARGET',$member->sales_target);
			ProfileEngine::setProfileAccountSetting($member->user_id,'COLLECTIONS_TARGET',$member->collections_target);
			ProfileEngine::setProfileAccountSetting($member->user_id,'SALARY',$member->salary);
			ProfileEngine::setProfileAccountSetting($member->user_id,'BONUS_PERCENT',$member->bonus);
			ProfileEngine::setProfileAccountSetting($member->user_id,'COMMISSION_PERCENT',$member->commission);
			ProfileEngine::setProfileAccountSetting($member->user_id,'PROFIT_PERCENT',$member->profit);
		}
	}

	public function actionMigrateUsersConfigs(){
		$users = Users::model()->findAll();
		foreach($users AS $user){
			ProfileEngine::setProfileAccountSetting($user->user_id,'LOAN_LIMIT',$user->maximum_limit);
			ProfileEngine::setProfileAccountSetting($user->user_id,'LOAN_INTEREST_RATE',$user->loans_interest);
			ProfileEngine::setProfileAccountSetting($user->user_id,'SAVINGS_INTEREST_RATE',$user->savings_interest);
			if($user->sms_notifications === '0'){
				ProfileEngine::setProfileAccountSetting($user->user_id,'SMS_ALERTS','DISABLED');
			}else{
				ProfileEngine::setProfileAccountSetting($user->user_id,'SMS_ALERTS','ACTIVE');
			}
			if($user->fixed_payment_enlisted === '0'){
				ProfileEngine::setProfileAccountSetting($user->user_id,'FIXED_PAYMENT_LISTED','DISABLED');
			}else{
				ProfileEngine::setProfileAccountSetting($user->user_id,'FIXED_PAYMENT_LISTED','ACTIVE');
			}
			ProfileEngine::setProfileAccountSetting($user->user_id,'EMAIL_ALERTS','ACTIVE');
		}
	}

	public function actionSuspendProfile($id){
		switch(Navigation::checkIfAuthorized(21)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to deactivate user account.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$auth  = Auths::model()->find('profileId=:a',array(':a'=>$id));
			$fullName = $this->loadModel($id)->ProfileFullName;
			switch(ProfileEngine::manageAuthAccountStatus($auth->id,'SUSPENDED')){
				case 1000:
				Logger::logUserActivity("Suspended/deactivated user account : $fullName",'urgent');
				CommonFunctions::setFlashMessage('success',"User account suspended/dectivated successfully.");
				break;

				case 1001:
				CommonFunctions::setFlashMessage('danger',"An error occurred while deactivating user account.");
				break;
			}
			$this->redirect(array('profiles/'.$id));
			break;
		}
	}

	public function actionActivateProfile($id){
		$model = $this->loadModel($id);
		if(!empty($model)){
			switch(Navigation::checkIfAuthorized(20)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to activate user account.");
				$this->redirect(array('dashboard/default'));
				break;
	
				case 1:
				$auth         = Auths::model()->find('profileId=:a',array(':a'=>$id));
				$manager      = Profiles::model()->findByPk($model->managerId);
				$accountManager = $manager->ProfileFullName;
				$accountManagerBranchComment = $manager->ProfileBranch === 'UNDEFINED' ? ""
				 : "Your Account Manager is $accountManager of ".$manager->ProfileBranch." Branch.";
				$beforeStatus = $auth->authStatus;
				$fullName     = $model->ProfileFullName;
				$firstName    = strtoupper($model->firstName);
				$phoneNumber  = ProfileEngine::getProfileContactByTypeOrderDesc($id,'PHONE');
				switch(ProfileEngine::manageAuthAccountStatus($auth->id,'ACTIVE')){
					case 1000:
					if($beforeStatus === 'SUSPENDED' && $model->profileType != 'STAFF'){
					    $textMessage = "Dear $firstName, Welcome to TCL. $accountManagerBranchComment Thank you!";
						$numbers     = array();
						array_push($numbers,$phoneNumber);
						SMS::broadcastSMS($numbers,$textMessage,'35',$id);
					}
					Logger::logUserActivity("Activated user account : $fullName",'urgent');
					CommonFunctions::setFlashMessage('success',"User account activated successfully.");
					break;
	
					case 1001:
					CommonFunctions::setFlashMessage('danger',"An error occurred while activating user account.");
					break;
				}
				$this->redirect(array('profiles/'.$id));
				break;
			}
		}else{
			CommonFunctions::setFlashMessage('danger',"Member not found with specified account ID.");
			$this->redirect(array('dashboard/default'));
		}
	}

	public function actionPayroll(){
		switch(Navigation::checkIfAuthorized(28)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view staff payroll.");
			$this->redirect(Yii::app()->request->urlReferrer);
			break;

			case 1:
			$this->render('payroll',array('branches'=>Reports::getAllBranches()));
			break;
		}
	}

	public function actionFilterPayroll(){
		$month_date=$_POST['month_date'];
		$branch=$_POST['branch'];
		$staff=$_POST['staff'];
		$payrollDues = StaffFunctions::LoadFilteredStaffPayroll((int)$branch,(int)$staff,$month_date);
		echo $payrollDues;
	}

	public function actionMembersReport(){
		switch(Navigation::checkIfAuthorized(84)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view repayments.");
			$this->redirect(Yii::app()->request->urlReferrer);
			break;

			case 1:
			$model = new Profiles('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Profiles'])){
				$model->attributes = $_GET['Profiles'];
				if(isset($_GET['export'])){
					$dataProvider = $model->search();
					$dataProvider->pagination = False;
					$excelWriter = ExportFunctions::getExcelMembersReport($dataProvider->data);
					echo $excelWriter->save('php://output');
				}
			}
			$this->render('membersReport',array('model'=>$model));
			break;
		}
	}

	public function actionPayment($id){
	$staff=Staff::model()->findByPk($id);
	$staffName=$staff->StaffFullName;
	$month_date=date('m-Y');
	$amount=StaffFunctions::getMemberNetSalaryPay($staff->user_id,$month_date);
	$monthName=strtoupper(date('F Y'));
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(29)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to process staff payroll.");
				$redirectUrl=Yii::app()->request->urlReferrer;
	  	 	break;

	  	 	case 1:
				if($amount > 0){
					switch(StaffFunctions::processStaffPayroll($id,$amount)){
						case 0:
						$type='danger';
						$message="Staff payroll could not be processed for the month: $monthName";
						break;

						case 1:
				    	Logger::logUserActivity("Processed Staff Payroll: $staffName, Month: $monthName",'high');
						$type='success';
						$message="Staff payroll processed successfully for the month: $monthName";
						break;

						case 2:
						$type='warning';
						$message="The staff has already been paid for the month: $monthName. Kindly try next month or CONTACT THE SYSTEM ADMIN";
						break;
					}
				}else{
					$type='warning';
					$message="The salary cannot be processed since the staff member has zero(0) funds.";
				}
				CommonFunctions::setFlashMessage($type,$message);
				$redirectUrl=array('payroll');
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
    	$redirectUrl=array('dashboard/default');
    	break;
    	}
	  $this->redirect($redirectUrl);
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Profiles the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Profiles::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Profiles $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='profiles-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}