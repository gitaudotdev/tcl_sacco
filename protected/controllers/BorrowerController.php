<?php

class BorrowerController extends Controller{

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
				'actions'=>array('create','update','admin',
					'loans','savings','newLoan','delete','upload',
					'importData','view','kin','referee',
					'createBorrower','sendSms','membersReport'),
				'users'=>array('@'),
			),
			array('deny',
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
      switch(Navigation::checkIfAuthorized(5)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to create Member.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$branches=Reports::getAllBranches();
				$managers=BorrowerFunctions::getRelationshipManagers();
				$this->render('new_account',array('branches'=>$branches,'managers'=>$managers));
    		break;
    	}
      break;

      case 1:
	  CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
  	  $this->redirect(array('dashboard/default'));	
      break;
    }
	}

	public function actionCreateBorrower(){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(5)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create Member.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
    		$password=CommonFunctions::generateRandomString();
				$user=new Users;
				$user->branch_id=$_POST['brw_branch'];
				$user->first_name=$_POST['brw_first_name'];
				$user->last_name=$_POST['brw_last_name'];
				$user->username=$_POST['brw_id_number'];
				$user->id_number=$_POST['brw_id_number'];
				$user->phone=$_POST['brw_phone'];
				$user->email=$_POST['brw_email'];
				$user->gender=$_POST['brw_gender'];
				$user->dateOfBirth=$_POST['brw_dob'];
				$user->residence=$_POST['brw_residence_land_mark'];
				$user->password=password_hash($password,PASSWORD_DEFAULT);
				$user->level='3';
				$user->created_by=Yii::app()->user->user_id;
				$user->rm=Yii::app()->user->user_id;
				if($user->save()){
					$user_id=$user->user_id;
					$borrower= new Borrower;
					$borrower->first_name=$_POST['brw_first_name'];
					$borrower->last_name=$_POST['brw_last_name'];
					$borrower->id_number=$_POST['brw_id_number'];
					$borrower->segment=$_POST['brw_segment'];
					$borrower->user_id=$user_id;
					$borrower->branch_id=$_POST['brw_branch'];
					$borrower->gender=$_POST['brw_gender'];
					$borrower->phone=$_POST['brw_phone'];
					$borrower->email=$_POST['brw_email'];
					$borrower->address=$_POST['brw_address'];
					$borrower->city=$_POST['brw_city'];
					$borrower->birth_date=$_POST['brw_dob'];
					$borrower->residence_land_mark=$_POST['brw_residence_land_mark'];
					$borrower->working_status=$_POST['brw_working_status'];
					$borrower->job_title=$_POST['brw_job_title'];
					$borrower->job_email=$_POST['brw_job_email'];
					$borrower->office_phone=$_POST['brw_office_phone'];
					$borrower->office_location=$_POST['brw_office_location'];
					$borrower->office_land_mark=$_POST['brw_office_land_mark'];
					$borrower->alternative_phone=$_POST['brw_alt_phone'];
					$borrower->referred_by=$_POST['brw_referred_by'];
					$borrower->rm=Yii::app()->user->user_id;
					$borrower->referee_phone=$_POST['brw_referee_phone'];
					$borrower->employer=$_POST['brw_employer'];
					$borrower->date_employed=$_POST['brw_date_employed'];
					$borrower->created_by=Yii::app()->user->user_id;
					$borrower->save();
					$fullName=$borrower->BorrowerFullName;
		      		Logger::logUserActivity("Created Member: $fullName",'normal');
					$kin=new Kins;
					$kin->user_id=$user_id;
					$kin->first_name=$_POST['kin_first_name'];
					$kin->last_name=$_POST['kin_last_name'];
					$kin->phone=$_POST['kin_phone'];
					$kin->relation=$_POST['kin_relation'];
					$kin->created_by=Yii::app()->user->user_id;
					$kin->save();
					$referee=new Referee;
					$referee->user_id    = $user_id;
					$referee->first_name = $_POST['ref_first_name'];
					$referee->last_name  = $_POST['ref_last_name'];
					$referee->employer   = $_POST['ref_employer'];
					$referee->relation   = $_POST['ref_relation'];
					$referee->phone      = $_POST['ref_phone'];
					$referee->created_by = Yii::app()->user->user_id;
					$referee->save();
					$subject      = "NEW CLIENT ACCOUNT";
					$emailmessage = "<p>Welcome to Treasure Capital Systems.</p>
					<p>A new client has been created in the system. </p>
					<p>Please log in and activate the client so that they can start accessing their portal.</p>
					<p>Do not hesitate to reach out if you need help.</p>";
					$emailStatus = CommonFunctions::broadcastEmailNotification('credit@tclfinance.co.ke',
					$subject,$emailmessage);
					$type    = 'success';
					$message = "Member details created successfully.";
				}else{
					$type    = 'danger';
					$message = "Member not created.";
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('admin'));
    		break;
    	}
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
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
    $array=array('2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(6)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update Member.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=$this->loadModel($id);
				if(isset($_POST['Borrower'])){
					$model->attributes=$_POST['Borrower'];
					if($model->save()){
						$fullName=$model->BorrowerFullName;
			      Logger::logUserActivity("Updated Member: $fullName",'normal');
						CommonFunctions::setFlashMessage('success',"Member details updated.");
						$this->redirect(array('admin'));
					}
				}
				$this->render('update',array('model'=>$model));
    		break;
    	}
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
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
    $element=Yii::app()->user->user_level;
    $array=array('2','3','4');
    $model=$this->loadModel($id);
		$fullName=$model->BorrowerFullName;
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(8)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to delete Member.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$borrower=Borrower::model()->findByPk($id);
				Yii::app()->db->createCommand("DELETE FROM users WHERE user_id={$borrower->user_id}")->execute();
				$this->loadModel($id)->delete();
	      Logger::logUserActivity("Deleted Member: $fullName",'urgent');
				CommonFunctions::setFlashMessage('success',"Member successfully deleted.");
				$this->redirect(array('admin'));
    		break;
    	}
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
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
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
			$model=new Borrower('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Borrower'])){
				$model->attributes=$_GET['Borrower'];
			}
			$this->render('admin',array('model'=>$model,));
      break;

      case 1:
				CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
	  	 	$this->redirect(array('dashboard/default'));
      break;
    }
	}

	public function actionMembersReport(){
		$element=Yii::app()->user->user_level;
		$array=array('3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			switch(Navigation::checkIfAuthorized(84)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to view repayments.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=new Borrower('search');
				$model->unsetAttributes(); 
				if(isset($_GET['Borrower'])){
					$model->attributes=$_GET['Borrower'];
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
			break;

			case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionView($id){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(7)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to View Member Details.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
	      $model=$this->loadModel($id);
				$userID=$model->user_id;
				$kinSQL="SELECT * FROM kins WHERE user_id=$userID";
				$kins=Kins::model()->findAllBySql($kinSQL);
				$model=$this->loadModel($id);
				$loans_sql="SELECT * FROM loanaccounts WHERE user_id=$userID ORDER BY loanaccount_id DESC";
				$loans=Loanaccounts::model()->findAllBySql($loans_sql);
				$refereeSQL="SELECT * FROM referee WHERE user_id=$userID ORDER BY id DESC";
				$referees=Referee::model()->findAllBySql($refereeSQL);
				$model=$this->loadModel($id);
				$savingAccounts=SavingFunctions::getAllUserSavingAccounts($model->user_id);
				$this->render('view',array('model'=>$model,'kins'=>$kins,'loans'=>$loans,'savingAccounts'=>$savingAccounts,'referees'=>$referees));
    		break;
    	}
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
      break;
    }
	}

	public function actionKin($id){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    $model=$this->loadModel($id);
    $fullName=$model->BorrowerFullName;
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(10)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authhorized to add Next of Kin Records.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=$this->loadModel($id);
				if(isset($_POST['add_kin_cmd'])){
					switch(BorrowerFunctions::createKinRecord($_POST)){
						case 0:
						$type='danger';
						$message="Next of kin record not created.";
						break;

						case 1:
			      		Logger::logUserActivity("Added Member Next of Kin Records : $fullName",'high');
						$type='success';
						$message="Next of kin record successfully created.";
						break;
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('borrower/'.$id));
				}
				$this->render('kin',array('model'=>$model));
	    	break;
	    }
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
      break;
    }
	}

	public function actionReferee($id){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    $model=$this->loadModel($id);
    $fullName=$model->BorrowerFullName;
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(11)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to add referee records.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=$this->loadModel($id);
				if(isset($_POST['add_referee_cmd'])){
					switch(BorrowerFunctions::createRefereeRecord($_POST)){
						case 0:
						$type='danger';
						$message="Referee record not created.";
						break;

						case 1:
			      Logger::logUserActivity("Added Member Referee Records: $fullName",'urgent');
						$type='success';
						$message="Referee record successfully created.";
						break;
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('borrower/'.$id));
				}
				$this->render('referee',array('model'=>$model));
    		break;
    	}
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));	
      break;
    }
	}

	public function actionLoans($id){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
			$model=$this->loadModel($id);
			$user_id=$model->user_id;
			$loans_sql="SELECT * FROM loanaccounts WHERE user_id=$user_id ORDER BY loanaccount_id DESC";
			$loans=Loanaccounts::model()->findAllBySql($loans_sql);
			$this->render('loans',array('model'=>$model,'loans'=>$loans));
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
      break;
    }
	}

	public function actionNewLoan($id){
		$model=$this->loadModel($id);
		$fullName=$model->BorrowerFullName;
		switch(Navigation::checkIfAuthorized(12)){
			case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to create Member loan account.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
				$model=$this->loadModel($id);
				$users=BorrowerFunctions::getRelationshipManagers();
				$directed=BorrowerFunctions::getDirectedTo();
				if(isset($_POST['apply_loan_cmd'])){
						$status=LoanApplication::createNewApplication($_POST);
						if($status === 1){
							Logger::logUserActivity("Added Member Loan : $fullName",'urgent');
							$type='success';
							$message="Application submitted successfully.";
						}else{
							$type='danger';
							$message="Application not submitted.";
						}
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('borrower/'.$id));
				}
				$this->render('newLoan',array('model'=>$model,'users'=>$users,'directed'=>$directed));
			break;
		}
	}

	public function actionSavings($id){
		$model=$this->loadModel($id);
		$savingAccounts=SavingFunctions::getAllUserSavingAccounts($model->user_id);
		$this->render('savings',array('model'=>$model,'savingAccounts'=>$savingAccounts));
	}

	public function actionUpload(){
    $element=Yii::app()->user->user_level;
    $array=array('1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
			$model=new Imports;
			$this->render('upload',array('model'=>$model));
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
      break;
    }
	}

	public function actionImportData(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$generatePassword=CommonFunctions::generateRandomString();
		$uploadedFile=CUploadedFile::getInstanceByName('filename');
		if(empty($uploadedFile) || $uploadedFile === ''){
			//Redirect With Error Message
			$type='danger';
			$message="No CSV File Uploaded. Please upload a CSV file in order to upload the borrowers.";
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('upload'));
		}else{
			$generateHash=CommonFunctions::generateRandomString();
			$import=new Imports;
			$import->filename=$uploadedFile;
			$import->integrity_hash=password_hash($generateHash,PASSWORD_DEFAULT);
			$import->imported_by=Yii::app()->user->user_id;
			if($import->save()){
				$import->filename->saveAs(Yii::app()->basePath."/../docs/csvs/borrowers/".$import->filename);
				try{
            $transaction = Yii::app()->db->beginTransaction();
            $handle = fopen(Yii::app()->basePath."/../docs/csvs/borrowers/".$import->filename, "r");
            $row = 1;
            while (($data = fgetcsv($handle, 1500000, ",")) !== FALSE) {
              if($row>1){
          			$branch=Branch::model()->findByPk($data[11]);
          			$branchName=$branch->name;
        				$user=new Users;
            		$user->branch_id=$data[11];
            		$user->first_name=ucfirst($data[0]);
            		$user->last_name=ucfirst($data[1]);
            		$user->username=$data[7];
            		$user->email=$data[7];
            		$user->phone=$data[7];
            		$user->password=password_hash($generatePassword,PASSWORD_DEFAULT);
            		$user->created_by=$userID;
            		if($user->save()){
            			$borrower=new Borrower;
            			$borrower->user_id=$user->user_id;
            			$borrower->first_name=ucfirst($data[0]);
            			$borrower->last_name=ucfirst($data[1]);
            			$borrower->phone=$data[7];
            			$borrower->alternative_phone=$data[8];
            			$borrower->id_number=$data[9];
            			$borrower->email=$data[7];
            			$borrower->birth_date="1989-01-01";
            			$borrower->employer=$data[10];
            			$borrower->date_employed="2010-01-01";
            			$borrower->address="P.O. BOX 2828-00100";
            			$borrower->city=$branchName;
            			$borrower->branch_id=$data[11];
            			$borrower->gender=$data[13];
            			$borrower->created_by=$userID;
            			$borrower->save();
          				$account=new Loanaccounts;
          				$account->loanproduct_id=1;
          				if($data[2] > 0){
          					$account->interest_rate=$data[2];
          				}else{
          					$account->interest_rate=1;
          				}
      		  			$account->user_id=$borrower->user_id;
      		  			$account->account_number=$data[7];
      		  			$account->amount_applied=$data[4];
      		  			$account->loan_status='2';
      		  			$account->amount_approved=$data[4];
      		  			$account->arrears=$data[5];
      		  			$account->date_approved=date('Y-m-d',strtotime($data[12]));
      		  			$account->approved_by=1;
      		  			$account->direct_to=1;
      		  			$account->forward_to=1;
      		  			$account->repayment_period=$data[3];
      		  			$account->rm=$data[6];
      		  			$account->repayment_start_date=date('Y-m-d',strtotime($data[12]));
      		  			$account->created_by=$userID;
      		  			$account->save();
      		  			$disburse=new DisbursedLoans;
    		  				$disburse->loanaccount_id=$account->loanaccount_id;
    		  				$disburse->amount_disbursed=$data[4];
    		  				$disburse->disbursed_by=$userID;
    		  				$disburse->save();
              	}  
              }
              $row++;               
            }
            $transaction->commit();
        }catch(Exception $error){
            $transaction->rollback();
						CommonFunctions::setFlashMessage('danger',"There was an error uploading the file: $error");
						$this->redirect(array('upload'));
        }
		    Logger::logUserActivity("Uploaded Members CSV file ",'normal');
				CommonFunctions::setFlashMessage('success',"Members successfully uploaded.");
				$this->redirect(array('upload'));
			}else{
				CommonFunctions::setFlashMessage('danger',"Uploading members Failed. Ensure you have uploaded a CSV file");
				$this->redirect(array('upload'));
			}
		}
	}

	public function actionSendSms($id){
    $element=Yii::app()->user->user_level;
    $array=array('3','4');
    $model=$this->loadModel($id);
    $fullName=$model->BorrowerFullName;
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      switch(Navigation::checkIfAuthorized(9)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to send SMS to a member.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$borrower=Borrower::model()->findByPk($id);
				$numbers=array();
				array_push($numbers,$borrower->phone);
				if(isset($_POST['send_txt_cmd'])){
					$textMessage=$_POST['textMessage'];
					$type='28';
					$status=SMS::broadcastSMS($numbers,$textMessage,$type,$borrower->user_id);
					switch($status){
						case 0:
						$type='danger';
						$message="Error occurred while sending SMS. Please ensure all phone numbers are available and in the correct format.";
						break;

						case 1:
						$type='success';
						$message="SMS Sent successfully";
			      		Logger::logUserActivity("Sent Member SMS Message: <strong>$textMessage</strong>: $fullName",'urgent');
						break;

						case 2:
						$type='danger';
						$message="An error occurred while trying to send the SMS. Consult your SMS service provider.";
						break;

						case 3:
						$type='danger';
						$message="The SMS category has been deactivated. Ask the Administrator to activate the category.";
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
					$this->redirect(array('borrower/'.$id));
				}
    		break;
    	}
      break;

      case 1:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));	
      break;
    }
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Borrower the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Borrower::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Borrower $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='borrower-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
