<?php

class RolesController extends Controller
{
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete','admin','permissions','assignPermissions','assign','removePermissions'),
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
    	switch(Navigation::checkIfAuthorized(77)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view role details");
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
    	switch(Navigation::checkIfAuthorized(74)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create roles");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$model=new Roles;
				if(isset($_POST['Roles'])){
					$roleName=$_POST['Roles']['name'];
					$checksql="SELECT * FROM roles WHERE name='$roleName'";
					$check=Roles::model()->findBySql($checksql);
					if(!empty($check)){
						$type='danger';
						$message="Role not created since the role records exists.";
					}else{
						$model->attributes=$_POST['Roles'];
						$model->created_by=Yii::app()->user->user_id;
						$model->created_at=date('Y-m-d H:i:s');
						if($model->save()){
							$roleName=$model->name;
					    	Logger::logUserActivity("Added System Role : $roleName",'high');
							$type='success';
							$message="Role successfully created.";
						}
						CommonFunctions::setFlashMessage($type,$message);
						$this->redirect(array('admin'));
					}
				}
				$this->render('create',array('model'=>$model));
    		break;
    	}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){
    	switch(Navigation::checkIfAuthorized(75)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update roles");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$model=$this->loadModel($id);
			if(isset($_POST['Roles'])){
				$model->attributes=$_POST['Roles'];
				if($model->save()){
					$roleName=$model->name;
					Logger::logUserActivity("Updated System Role : $roleName",'high');
					CommonFunctions::setFlashMessage('info',"Role successfully updated.");
					$this->redirect(array('admin'));
				}
			}
			$this->render('update',array('model'=>$model));
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
		$roleName=$model->name;
    	switch(Navigation::checkIfAuthorized(78)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete roles.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			Yii::app()->db->createCommand("DELETE FROM user_role WHERE role_id=$id")->execute();
			Yii::app()->db->createCommand("DELETE FROM role_permission WHERE role_id=$id")->execute();
			$this->loadModel($id)->delete();
		    Logger::logUserActivity("Deleted System Role: $roleName",'urgent');
			CommonFunctions::setFlashMessage('success',"System Role successfully deleted.");
			$this->redirect(array('admin'));
    		break;
    	}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		$element=Yii::app()->user->user_level;
		$array=array('2','3','4');
		switch(CommonFunctions::searchElementInArray($element,$array)){
			case 0:
			$model=new Roles('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Roles'])){
				$model->attributes=$_GET['Roles'];
			}
			$this->render('admin',array('model'=>$model));
			break;

			case 1:
				CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
			$this->redirect(array('dashboard/default'));
			break;
		}
	}

	public function actionPermissions($id){
		$model=$this->loadModel($id);
		$roleName=$model->name;
    	switch(Navigation::checkIfAuthorized(76)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view role permissions.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				switch(Permission::checkForRolePermissions($id)) {
					case 0:
					CommonFunctions::setFlashMessage('warning',"No Permissions Assigned. Kindly Assign the Role Permissions.");
					$this->redirect(array('admin'));
					break;
					
					default:
					$this->render('permissions',array('permissions'=>Permission::getAllRolePermissions($id),'id'=>$id));
					break;
				}
    		break;
    	}
	}

	public function actionAssignPermissions($id){
		$model    = $this->loadModel($id);
		$roleName = $model->name;
    	switch(Navigation::checkIfAuthorized(79)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to assign role permissions.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$permissions = Permission::getAllPermissions($id);
			switch($permissions){
				case 0:
				CommonFunctions::setFlashMessage('danger',"No more permissions to be assigned for this role.");
				$this->redirect(array('admin'));
				break;

				default:
				$this->render('assign',array('permissions'=>$permissions,'id'=>$id,'role'=>$model));
				break;
			}
    		break;
    	}
	}

	public function actionAssign(){
    	switch(Navigation::checkIfAuthorized(79)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to assign role permissions");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$role_id=$_POST['role'];
			$model=$this->loadModel($role_id);
			$roleName=$model->name;
			if(isset($_POST['assign_cmd']) && isset($_POST['permissions'])){
				foreach($_POST['permissions'] as $permission){
					$permission_ID  = $permission;
					$permissionName = Permissions::model()->findByPk($permission_ID)->display_name;
					$check_sql="SELECT * FROM role_permission WHERE role_id=$role_id AND permission_id=$permission_ID";
					$check_existence=RolePermission::model()->findAllBySql($check_sql);
					if(count($check_existence)< 1){
						$role=new RolePermission;
						$role->role_id=$role_id;
						$role->permission_id=$permission_ID;
						$role->created_by=Yii::app()->user->user_id;
						$role->created_at=date('Y-m-d H:i:s');
						$role->save();
					}
					Logger::logUserActivity("Assigned permission:<strong>$permissionName</strong> to a Role: <strong>$roleName</strong>",'high');
				}
				CommonFunctions::setFlashMessage('success',"Permissions successfully assigned.");
				$this->redirect(array('admin'));
			}else{
				CommonFunctions::setFlashMessage('danger',"No permission selected. Select permission to be assigned.");
				$this->redirect(array('roles/assignPermissions/'.$role_id));
			}
    		break;
    	}
	}

	public function actionRemovePermissions(){
    	switch(Navigation::checkIfAuthorized(80)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to remove role permissions.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
				$roleID=$_POST['role'];
		    	$model=$this->loadModel($roleID);
				$roleName=$model->name;
				if(isset($_POST['remove_cmd']) && isset($_POST['permissions'])){
					foreach($_POST['permissions'] as $permissionID){
						$permissionName=Permissions::model()->findByPk($permissionID)->display_name;
						$query = "DELETE FROM role_permission WHERE role_id=$roleID AND permission_id=$permissionID";
						$command = Yii::app()->db->createCommand($query);
						$command->execute();
			    		Logger::logUserActivity("Removed permission:<strong>$permissionName</strong> from a Role: <strong>$roleName</strong>",'high');
					}
					CommonFunctions::setFlashMessage('success',"Permissions successfully removed.");
					$this->redirect(array('admin'));
				}else{
					CommonFunctions::setFlashMessage('danger',"No permission selected. Select a permission to be removed.");
					$this->redirect(array('roles/permissions/'.$roleID));
				}
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Roles the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Roles::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Roles $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='roles-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
