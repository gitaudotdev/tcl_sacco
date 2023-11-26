<?php

class StaffController extends Controller{
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','delete','assign','assignCommit',
					'payroll','payment','reassign','commitReassignment','filterStaffPayroll','processPayroll','transfer','commitTransfer','suspend','reinstate','downloadPayroll','commitPayrollProcess'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(23)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to create staff member.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
		  	$model=new Staff;
				if(isset($_POST['Staff'])){
					$first_name=$_POST['Staff']['first_name'];
					$last_name=$_POST['Staff']['last_name'];
					$email=$_POST['Staff']['email'];
					$id_number=$_POST['Staff']['id_number'];
					$phone=$_POST['Staff']['phone'];
					$branch=$_POST['Staff']['branch_id'];
					$salary=$_POST['Staff']['salary'];
					$salesTarget=$_POST['Staff']['sales_target'];
					$collectionsTarget=$_POST['Staff']['collections_target'];
					$staffGender=$_POST['Staff']['gender'];
					$staffDateOfBirth=$_POST['Staff']['dateOfBirth'];
					switch(StaffFunctions::createStaffMemberRecord($first_name,$last_name,$email,$id_number,$phone,$branch,$salary,$salesTarget,$collectionsTarget,$staffGender,$staffDateOfBirth)){
						case 0:
						CommonFunctions::setFlashMessage('danger',"Staff not created.");
						$this->redirect(array('admin'));
						break;

						case 1:
				    Logger::logUserActivity("Added Staff Member: $first_name $last_name",'normal');
						CommonFunctions::setFlashMessage('success',"Staff successfully created.");
						$this->redirect(array('admin'));
						break;

						case 2:
						CommonFunctions::setFlashMessage('warning',"Failed Operation.Duplicate email address,phone number or ID");
						$this->redirect(array('create'));
						break;
					}
				}
				$this->render('create',array('model'=>$model,));
	  	 	break;
	  	}
    	break;

    	case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(24)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update staff member.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
				$model=$this->loadModel($id);
				if(isset($_POST['Staff'])){
					$model->attributes=$_POST['Staff'];
					$model->profit=$_POST['Staff']['profit'];
					$model->is_supervisor=$_POST['Staff']['is_supervisor'];
					$model->bonus=$_POST['Staff']['bonus'];
					$model->commission=$_POST['Staff']['commission'];
					$model->payroll_listed=$_POST['Staff']['payroll_listed'];
					$model->payroll_auto_process=$_POST['Staff']['payroll_auto_process'];
					$model->commentsDashboard_listed=$_POST['Staff']['commentsDashboard_listed'];
					if($model->save()){
						$staffName=$model->StaffFullName;
				    Logger::logUserActivity("Updated Staff Member: $staffName",'normal');
						CommonFunctions::setFlashMessage('success',"Staff details updated.");
						$this->redirect(array('admin'));
					}
				}
				$this->render('update',array('model'=>$model));
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
		$model=$this->loadModel($id);
		$staffName=$model->StaffFullName;
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(25)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to delete staff member.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
				$userID=$model->user_id;
				Yii::app()->db->createCommand("DELETE FROM users WHERE user_id=$userID")->execute();
				Yii::app()->db->createCommand("DELETE FROM user_role WHERE user_id=$userID")->execute();
				$this->loadModel($id)->delete();
		    Logger::logUserActivity("Deleted Staff Member: $staffName",'urgent');
				$type='success';
				$message="Staff Records successfully deleted.";
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('admin'));
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		$organization=Organization::model()->findByPk(1);
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
				$model=new Staff('search');
				$model->unsetAttributes();
				if(isset($_GET['Staff'])){
					$model->attributes=$_GET['Staff'];
				}
				$this->render('admin',array('model'=>$model,'organization'=>$organization));
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionAssign($id){
		$roles   = Roles::model()->findAll();
    $element = Yii::app()->user->user_level;
    $array   = array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(27)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to assign staff member a role.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
				switch(Permission::checkIfUserAssignedRole($id)){
					case 0:
					$this->render('assign',array('roles'=>$roles,'id'=>$id));
					break;

					case 1:
					CommonFunctions::setFlashMessage('info',"User has already been assigned a role.");
					$this->redirect(array('users/admin'));
					break;
				}
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionReassign($id){
    $element = Yii::app()->user->user_level;
    $array   = array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
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
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionCommitReassignment(){
    $element = Yii::app()->user->user_level;
    $array   = array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(26)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to reassign a staff member a role.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
				if(isset($_POST['reassign_role_cmd']) && isset($_POST['roles'])){
					$roleUserId = $_POST['user'];
					$roleSQL    = "SELECT * FROM user_role WHERE user_id=$roleUserId LIMIT 1";
					$roles      =  UserRole::model()->findBySql($roleSQL);
					if(!empty($roles)){
						$user      = Users::model()->findByPk($roleUserId);
						$staffName = $user->UserFullName;
						$roleName  = Roles::model()->findByPk($_POST['roles'])->name;
						$user_role = UserRole::model()->findByPk($roles->id);
						$user_role->user_id = $roleUserId;
						$user_role->role_id = $_POST['roles'];
						$user_role->created_by=Yii::app()->user->user_id;
						$user_role->save();
				    Logger::logUserActivity("Reassigned user: $staffName, role: $roleName",'high');
						CommonFunctions::setFlashMessage('success',"User successfully reassigned the role.");
						$this->redirect(array('users/admin'));
					}else{
						CommonFunctions::setFlashMessage('danger',"The user has not been reassigned this role.");
						$this->redirect(array('staff/reassign/'.$roleUserId));
					}
				}else{
					CommonFunctions::setFlashMessage('warning',"Kindly select a role to reassign the user.");
					$this->redirect(array('users/admin'));
				}
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionAssignCommit(){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(27)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to assign staff member a role.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
	    	$userID      = $_POST['user'];
				if(isset($_POST['assign_role_cmd']) && isset($_POST['roles'])){
					$user     = Users::model()->findByPk($userID);
					$userName = $user->UserFullName;
					$roleName = Roles::model()->findByPk($_POST['roles'])->name;
					$user_role             = new UserRole;
					$user_role->user_id    = $_POST['user'];
					$user_role->role_id    = $_POST['roles'];
					$user_role->created_by = Yii::app()->user->user_id;
					$user_role->save();
			    Logger::logUserActivity("Assigned user:$userName, role: $roleName",'high');
					CommonFunctions::setFlashMessage('success',"User successfully assigned a role.");
					$this->redirect(array('users/admin'));
				}else{
					CommonFunctions::setFlashMessage('warning',"Kindly select a role to assign.");
					$this->redirect(array('staff/assign/'.$userID));
				}
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionPayroll(){
		$branches=Reports::getAllBranches();
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			switch(Navigation::checkIfAuthorized(28)){
				case 0:
					CommonFunctions::setFlashMessage('danger',"Not Authorized to view staff payroll.");
				$this->redirect(Yii::app()->request->urlReferrer);
				break;

				case 1:
					$this->render('payroll',array('branches'=>$branches));
				break;
			}
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionFilterStaffPayroll(){
		$month_date=$_POST['month_date'];
		$branch=$_POST['branch'];
		$staff=$_POST['staff'];
		$payrollDues=StaffFunctions::LoadFilteredStaffPayroll((int)$branch,(int)$staff,$month_date);
		echo $payrollDues;
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

	public function actionTransfer($id){
		$model=$this->loadModel($id);
		$modelUserId=$model->user_id;
		$userQuery="SELECT * FROM users WHERE level IN('0','1','2') AND user_id NOT IN(1,$modelUserId) AND is_active='1'";
		$users=Users::model()->findAllBySql($userQuery);
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(161)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to transfer staff account.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
				$this->render('transfer',array('users'=>$users,'model'=>$model));
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionCommitTransfer(){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(161)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to transfer staff account.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
				if(isset($_POST['transfer_account_cmd'])){
					$staffID=$_POST['current_user'];
					$newUserID=$_POST['staff'];
					switch(StaffFunctions::transferStaffAccount($staffID,$newUserID)){
						case 1000:
						CommonFunctions::setFlashMessage('danger',"Operation failed. Invalid Current Staff Member.");
						break;

						case 1001:
						CommonFunctions::setFlashMessage('danger',"Operation failed. Invalid Current Staff User Account");
						break;

						case 1002:
						CommonFunctions::setFlashMessage('danger',"Operation failed. Could not transfer staff Account");
						break;

						case 1003:
						CommonFunctions::setFlashMessage('danger',"Operation failed. Could not deactivate staff user login Account");
						break;

						case 1004:
						CommonFunctions::setFlashMessage('danger',"Operation failed. Invalid new user account to transfer current staff member to.");
						break;

						case 1111:
						CommonFunctions::setFlashMessage('success',"Staff account transferred successfully.");
						break;
					}
					$this->redirect(array('admin'));
				}else{

				}
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionSuspend($id){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(165)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to suspend staff account.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
				switch(StaffFunctions::suspendStaffAccount($id)){
					case 1000:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Staff account not found.");
					break;

					case 1001:
					CommonFunctions::setFlashMessage('success',"Staff account sucessfully suspended.");
					break;

					case 1002:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Staff user account not found.");
					break;

					case 1003:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Staff account could not be suspended.");
					break;

					case 1004:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Staff user account could not be deactivated.");
					break;
				}
				$this->redirect(array('admin'));
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionReinstate($id){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(28)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to reinstate staff account.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
	  	 	break;

	  	 	case 1:
				switch(StaffFunctions::reinstateStaffAccount($id)){
					case 1000:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Staff account not found.");
					break;

					case 1001:
					CommonFunctions::setFlashMessage('success',"Staff account sucessfully reinstated.");
					break;

					case 1002:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Staff user account not found.");
					break;

					case 1003:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Staff account could not be reinstated.");
					break;

					case 1004:
					CommonFunctions::setFlashMessage('danger',"Operation failed. Staff user account could not be activated.");
					break;
				}
				$this->redirect(array('admin'));
	  	 	break;
	  	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionProcessPayroll(){
	$element=Yii::app()->user->user_level;
	$organization=Organization::model()->findByPk(1);
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(29)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to process staff payroll.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
	    	$members=StaffFunctions::getPayrollStaff();
    		if($organization->automated_payroll === 'disabled'){
					$this->render('processPayroll',array('members'=>$members));
    		}else{
				CommonFunctions::setFlashMessage('danger',"Manual processing of payroll forbidden.");
		  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		}
    		break;
    	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionCommitPayrollProcess(){
	$element=Yii::app()->user->user_level;
    $array=array('3','4');
    $organization=Organization::model()->findByPk(1);
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	switch(Navigation::checkIfAuthorized(29)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to process staff payroll.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
    		if($organization->automated_payroll === 'disabled'){
	    		if($organization->enable_mpesa_b2c === 'DISABLED'){
							CommonFunctions::setFlashMessage('danger',"Operation failed. Sorry this action cannot be completed at this time. Contact the system administrator.");
							$this->redirect(array('admin'));
	    		}else{
						if(isset($_POST['process_payroll_cmd']) && isset($_POST['staffMembers'])){
							if(isset($_POST['payroll_month'])){
								$monthDate = explode('-',$_POST['payroll_month']);
								$payMonth=(int)$monthDate[0];
								$payYear=(int)$monthDate[1];
								$boundStatus=PayrollManager::checkBoundedPayrollPeriod($payMonth,$payYear);
								//$boundStatus = 1;
								if($boundStatus === 1){
									foreach($_POST['staffMembers'] as $staffMember){
										$staffID=$staffMember;
										$model=$this->loadModel($staffID);
										$fullName=$model->getStaffFullName();
										$staffUser=Users::model()->findByPk($model->user_id);
										$userID=$staffUser->user_id;
										$branchID=$staffUser->branch_id;
										$salesCommision=StaffFunctions::getMemberBonus($userID,$_POST['payroll_month']);
										$collectionsCommision=StaffFunctions::getMemberCommission($userID,$_POST['payroll_month']);
										$totalLoan=StaffFunctions::getCurrentLoanRepayment($userID);
										$grossSalary=$model->salary;
										$netSalary=StaffFunctions::getMemberNetSalaryPay($userID,$_POST['payroll_month']);
										$formatNetSalary=CommonFunctions::asMoney($netSalary);
										$payrollPeriod=CommonFunctions::getMonthName($payMonth)." - ".$payYear;
										$processStatus=PayrollManager::processPayroll($userID,$staffID,$branchID,$salesCommision,$collectionsCommision,$totalLoan,$grossSalary,$netSalary,$payMonth,$payYear);
										if($processStatus === 1250){
											CommonFunctions::setFlashMessage('danger',"Operation failed. Error occurred while generating the auth token. Please try again later...");
											$this->redirect(array('admin'));
										}else if($processStatus === 2020){
											CommonFunctions::setFlashMessage('danger',"Operation failed. Sorry this action cannot be completed at this time. Contact the system administrator.");
											$this->redirect(array('admin'));
										}else{
											switch($processStatus){
												case 0:
							    			Logger::logUserActivity("Processing Payroll Failed for : $fullName for period : $payrollPeriod",'high');
												break;

												case 1:
							    			Logger::logUserActivity("Successfully Processed Payroll for : $fullName for period : $payrollPeriod, Net Salary : $formatNetSalary",'high');
												break;

												case 2:
							    			Logger::logUserActivity("Processing Payroll Failed for : $fullName for period : $payrollPeriod. Payroll for the selected period was already processed.",'high');
												break;

												case 3:
							    			Logger::logUserActivity("Processing Payroll Failed for : $fullName for period : $payrollPeriod. Payroll transaction for the selected period failed.",'high');
												break;

												case 4:
							    			Logger::logUserActivity("Processing Payroll Failed for : $fullName for period : $payrollPeriod. <strong>No response from the M-PESA system.</strong>.",'high');
												break;

												default:
							    			Logger::logUserActivity("Processing Payroll Failed for : $fullName for period : $payrollPeriod. <strong>$processStatus</strong>",'high');
												break;
											}
										}
									}
									CommonFunctions::setFlashMessage('success',"Payroll processed successfully.");
								}elseif($boundStatus === 4){
									CommonFunctions::setFlashMessage('danger',"Operation failed. Cannot process payroll for a month in the past.");
								}elseif($boundStatus === 5){
									CommonFunctions::setFlashMessage('danger',"Operation failed. Cannot process payroll for a month in the future.");
								}else{
									CommonFunctions::setFlashMessage('danger',"Operation failed. Cannot process payroll for a month in the future.");
								}
								$this->redirect(array('admin'));
							}else{
								CommonFunctions::setFlashMessage('warning',"Please select month to process payroll.");
					  	 	$this->redirect(Yii::app()->request->urlReferrer);
							}
						}else{
							CommonFunctions::setFlashMessage('danger',"Please select at least one staff member in order to process payroll.");
				  	 	$this->redirect(Yii::app()->request->urlReferrer);
						}
	    		}
    		}else{
					CommonFunctions::setFlashMessage('danger',"Manual processing of payroll forbidden.");
		  	 	$this->redirect(array('admin'));
    		}
    		break;
    	}
    	break;

    	case 1:
    	CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
	  	$this->redirect(array('dashboard/default'));
    	break;
    }
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Staff the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Staff::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Staff $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='staff-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
