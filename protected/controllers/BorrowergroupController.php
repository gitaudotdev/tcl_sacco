<?php

class BorrowergroupController extends Controller{
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
				'actions'=>array('create','update','admin','delete','remove','changename','newMembers'),
				'users'=>array('@'),
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
		$this->render('view',array('model'=>$this->loadModel($id)));
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
			$managers      = ProfileEngine::getProfileGroupAccountManagers();
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
			$this->render('create',array('members'=>$members,'managers'=>$managers,'organizations'=>$organizations,'locations'=>$locations));
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
			$model=$this->loadModel($id);
				$members    = ProfileEngine::getProfileGroupMembers($id);
				$collectors = ProfileEngine::getProfileGroupAccountManagers();
				$borrowers  = ProfileEngine::getProfilesNotInAnyGroup();
				$memberswithoutLeader = ProfileEngine::getProfileGroupMembersWithoutLeader($id);
				if(isset($_POST['Borrowergroup'])){
					$model->attributes = $_POST['Borrowergroup'];
					if($model->save()){
						Logger::logUserActivity("Updated chama details","normal");
						$this->redirect(array('admin'));
					}
				}
				$this->render('update',array('model'=>$model,'members'=>$members,'collectors'=>$collectors,'borrowers'=>$borrowers,
					'memberswithoutLeader'=>$memberswithoutLeader));
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
			Yii::app()->db->createCommand("DELETE FROM borrowers_group WHERE group_id=$id")->execute();
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted chama record",'urgent');
			CommonFunctions::setFlashMessage('success',"Chama successfully deleted.");
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
			$model=new Borrowergroup('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Borrowergroup'])){
				$model->attributes=$_GET['Borrowergroup'];
			}
			$this->render('admin',array('model'=>$model));
			break;
		}
	}

	public function actionRemove(){
		$group_id = $_POST['group_id'];
		switch(Navigation::checkIfAuthorized(135)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Action Restricted. You are not allowed to remove members from a group.");
			$this->redirect(array('borrowergroup/update/'.$group_id));
			break;

			case 1:
			if(isset($_POST['remove_cmd'])){
				foreach($_POST['borrowers'] as $borrowerID){
					$borrower = Profiles::model()->findByPk($borrowerID);
					$group    = Borrowergroup::model()->findByPk($group_id);
					if($group->group_leader != $borrower->id){
						$query   = "DELETE FROM borrowers_group WHERE group_id=$group_id AND borrower_id=$borrowerID";
						$command = Yii::app()->db->createCommand($query);
						$command->execute();
					}
				}
				CommonFunctions::setFlashMessage('success',"Member successfully removed from the group.");
				$this->redirect(array('borrowergroup/update/'.$group_id));
			}else{
				CommonFunctions::setFlashMessage('danger',"Kindly select member to remove");
				$this->redirect(array('borrowergroup/update/'.$group_id));
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
			if(isset($_POST['update_name_cmd']) && isset($_POST['group_name']) && isset($_POST['group_leader']) && isset($_POST['group_collector'])){
				$groupName      = $_POST['group_name'];
				$groupLeader    = $_POST['group_leader'];
				$groupCollector = $_POST['group_collector'];
				$borrowerGroup  = Borrowergroup::model()->findByPk($groupID);
				$borrowerGroup->name         = $groupName;
				$borrowerGroup->group_leader = $groupLeader;
				$borrowerGroup->collector_id = $groupCollector;
				if($borrowerGroup->save()){
					$type    = 'success';
					$message = "Chama successfully updated.";
				}else{
					$type    = 'danger';
					$message = "Chama not updated. Try again.";
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('borrowergroup/update/'.$groupID));
			}else{
				CommonFunctions::setFlashMessage('danger',"Kindly provide all details.");
				$this->redirect(array('borrowergroup/update/'.$groupID));
			}
			break;
		}
	}

	public function actionNewMembers(){
		switch(Navigation::checkIfAuthorized(134)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access this resource.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			if(isset($_POST['add_borrower_cmd'])){
				$groupID = $_POST['group'];
				ProfileEngine::enlistProfilesToGroup($groupID,$_POST);
				$type    = 'success';
				$message = "Members successfully added.";
			}else{
				$type    = 'danger';
				$message = "Operation failed.Please try again.";
			}
			CommonFunctions::setFlashMessage($type,$message);
			$this->redirect(array('borrowergroup/update/'.$groupID));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Borrowergroup the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Borrowergroup::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Borrowergroup $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='borrowergroup-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
