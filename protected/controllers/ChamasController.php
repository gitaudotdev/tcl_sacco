<?php

class ChamasController extends Controller{
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
			   'actions'=>array('create','update','admin','delete','remove','changename','newMembers','view','applyLoan'),
			   'users'  =>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
		switch(Navigation::checkIfAuthorized(137)){
		  case 0:
		  CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
		  $this->redirect(array('dashboard/default'));
		  break;
  
		  case 1:
		  $model      = $this->loadModel($id);
		  $members    = ProfileEngine::getProfileChamaMembers($id);
		  $borrowers  = ProfileEngine::getProfilesNotInAnyGroup();
		  $memberswithoutLeader = ProfileEngine::getProfileGroupMembersWithoutLeader($id);
		  $this->render('view',array('model'=>$model,'members'=>$members,'memberswithoutLeader'=>$memberswithoutLeader,'borrowers'=>$borrowers));
		  break;
		}
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
		switch(Navigation::checkIfAuthorized(134)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$members       = ProfileEngine::getProfilesNotInAnyGroup();
			$branches      = Reports::getAllBranches();
			$locations     = Chama::getChamaLocations();
			$organizations = Chama::getChamaOrganizations();
			if(isset($_POST['group_cmd'])){
				switch(ProfileEngine::createNewProfilesGroup($_POST)){
					case 0:
					$type    = 'danger';
					$message = "Failed to create chama. Check your details and retry.";
					break;

					case 1:
					Logger::logUserActivity("Added chama records",'normal');
					$type    = 'success';
					$message = "Chama created successfully.";
					break;
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('admin'));
			}
			$this->render('create',array('members'=>$members,'branches'=>$branches,'organizations'=>$organizations,'locations'=>$locations));
			break;
		}
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
		switch(Navigation::checkIfAuthorized(136)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
		    $model      = $this->loadModel($id);
			$members    = ProfileEngine::getProfileGroupMembers($id);
			$collectors = ProfileEngine::getProfileGroupAccountManagers();
			$locations     = Chama::getChamaLocations();
			$organizations = Chama::getChamaOrganizations();
			if(isset($_POST['Chamas'])){
				$model->attributes = $_POST['Chamas'];
				if($model->save()){
					Logger::logUserActivity("Updated chama details","normal");
					$this->redirect(array('admin'));
				}
			}
			$this->render('update',array('model'=>$model,'members'=>$members,'collectors'=>$collectors,'locations'=>$locations,'organizations'=>$organizations));
			break;
		}
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
		switch(Navigation::checkIfAuthorized(135)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			Yii::app()->db->createCommand("DELETE FROM chama_members WHERE chama_id=$id")->execute();
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted chama record",'urgent');
			CommonFunctions::setFlashMessage('success',"Chama deleted successfully.");
			$this->redirect(array('admin'));
			break;
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(137)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$model = new Chamas('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Chamas'])){
				$model->attributes = $_GET['Chamas'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}

	public function actionRemove(){
		$group_id = $_POST['group_id'];
		switch(Navigation::checkIfAuthorized(297)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Action Restricted. You are not allowed to remove members from a group.");
			$this->redirect(array('chamas/update/'.$group_id));
			break;

			case 1:
			if(isset($_POST['remove_cmd'])){
				foreach($_POST['borrowers'] as $memberID){
					$member = Profiles::model()->findByPk($memberID);
					$chama  = Chamas::model()->findByPk($group_id);
					if($chama->leader != $member->id){
						Yii::app()->db->createCommand("DELETE FROM chama_members WHERE chama_id=$group_id AND user_id=$memberID")->execute();
					}
				}
				CommonFunctions::setFlashMessage('success',"Member successfully removed from the group.");
				$this->redirect(array('chamas/'.$group_id));
			}else{
				CommonFunctions::setFlashMessage('danger',"Kindly select member to remove");
				$this->redirect(array('chamas/'.$group_id));
			}
			break;
		}
	}

	public function actionChangename(){
		$groupID = $_POST['group'];
		switch(Navigation::checkIfAuthorized(136)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			if(isset($_POST['update_name_cmd']) && isset($_POST['name']) && isset($_POST['leader']) && isset($_POST['accountManager'])){
				$groupName      = $_POST['name'];
				$groupLeader    = $_POST['leader'];
				$groupCollector = $_POST['accountManager'];
				$organizationId = $_POST['organizationId'];
				$locationId     = $_POST['locationId'];
				$isRegistered   = $_POST['isRegistered'];
				$profile        = Profiles::model()->findByPk($groupLeader);
				$borrowerGroup  = Chamas::model()->findByPk($groupID);
				$borrowerGroup->name          = $groupName;
				$borrowerGroup->leader        = $groupLeader;
				$borrowerGroup->rm            = $groupCollector;
				$borrowerGroup->branch_id     = $profile->branchId;
				$borrowerGroup->location_id   = $locationId;
				$borrowerGroup->is_registered = $isRegistered;
				$borrowerGroup->organization_id = $organizationId;
				if($borrowerGroup->update()){
					$type    = 'success';
					$message = "Chama updated successfully.";
				}else{
					$type    = 'danger';
					$message = "Chama not updated. Try again later.";
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('chamas/update/'.$groupID));
			}else{
				CommonFunctions::setFlashMessage('danger',"Kindly provide all details.");
				$this->redirect(array('chamas/update/'.$groupID));
			}
			break;
		}
	}

	public function actionNewMembers(){
		switch(Navigation::checkIfAuthorized(296)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			if(isset($_POST['add_borrower_cmd'])){
				$groupID = $_POST['group'];
				ProfileEngine::enlistProfilesToGroup($groupID,$_POST);
				$type    = 'success';
				$message = "Members added successfully.";
			}else{
				$type    = 'danger';
				$message = "Operation failed.Please try again.";
			}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('chamas/'.$groupID));
			break;
		}
	}

	public function actionApplyLoan($id){
		$model    = Profiles::model()->findByPk($id);
		$fullName = $model->ProfileFullName;
		switch(Navigation::checkIfAuthorized(12)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create user/member loan account.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$managers = ProfileEngine::getLoanDirectionProfiles();
			$chama    = Chama::getMemberChama($id);
			if(isset($_POST['apply_loan_cmd']) && !empty($chama)){
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
				$this->redirect(array('view','id'=>$chama->id));
			}
			$this->render('applyLoan',array('model'=>$model,'managers'=>$managers,'chama'=>$chama));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Chamas the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model = Chamas::model()->findByPk($id);
		if($model === null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Chamas $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax'] === 'chamas-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
