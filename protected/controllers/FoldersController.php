<?php

class FoldersController extends Controller{
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
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','myFolder'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
    $element=Yii::app()->user->user_level;
    $array=array('0','1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
			$this->render('view',array(
				'model'=>$this->loadModel($id),
			));
    	break;

    	case 1:
			$type='danger';
			$message="Restricted Area. You are not allowed to access this resource.";
			CommonFunctions::setFlashMessage($type,$message);
  	 	$this->redirect(array('dashboard/default'));
    	break;
    }
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
    $element=Yii::app()->user->user_level;
    $array=array('0','1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
			$model=new Folders;
			if(isset($_POST['Folders'])){
				$model->attributes=$_POST['Folders'];
				if($model->save()){
					$this->redirect(array('admin'));
				}
			}
			$this->render('create',array(
				'model'=>$model,
			));
    	break;

    	case 1:
			$type='danger';
			$message="Restricted Area. You are not allowed to access this resource.";
			CommonFunctions::setFlashMessage($type,$message);
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
    $array=array('0','1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
			$model=$this->loadModel($id);
			if(isset($_POST['Folders'])){
				$model->attributes=$_POST['Folders'];
				if($model->save()){
					$type='danger';
					$message="Directory updated successfully.";
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('admin'));
				}
			}
			$this->render('update',array(
				'model'=>$model,
			));
    	break;

    	case 1:
			$type='danger';
			$message="Restricted Area. You are not allowed to access this resource.";
			CommonFunctions::setFlashMessage($type,$message);
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
    $array=array('0','1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
			$this->loadModel($id)->delete();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax'])){
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
    	break;

    	case 1:
			$type='danger';
			$message="Restricted Area. You are not allowed to access this resource.";
			CommonFunctions::setFlashMessage($type,$message);
  	 	$this->redirect(array('dashboard/default'));
    	break;
    }
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(211)){
    		case 0:
				$type='danger';
				$message="Restricted Area. You are not allowed to access this resource.";
				CommonFunctions::setFlashMessage($type,$message);
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
	    	$listing=new FileManager();
				$data = $listing->run();
				$model=new Folders('search');
				$model->unsetAttributes();  // clear any default values
				if(isset($_GET['Folders'])){
					$model->attributes=$_GET['Folders'];
				}
				if(isset($_FILES['upload'])){
					switch(Navigation::checkIfAuthorized(215)){
						case 0:
						$message = 'Your are not allowed to upload files.';
						$type='warning';
						break;

						case 1:
						$uploadStatus = $listing->upload();
						if($uploadStatus == 1){
							$message = 'Your file was successfully uploaded!';
							$type='success';
						}elseif ($uploadStatus == 2) {
							$message = 'Your file could not be uploaded. A file with that name already exists.';
							$type='danger';
						}elseif ($uploadStatus == 3) {
							$message = 'Your file could not be uploaded as the file type is blocked.';
							$type='warning';
						}
						break;
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(Yii::app()->request->urlReferrer);
				}elseif(isset($_POST['directory'])){
					switch(Navigation::checkIfAuthorized(216)){
						case 0:
						$message ='You are not allowed to create vault folders/directories.';
						$type='warning';
						break;

						case 1:
						switch($listing->createDirectory()){
							case 0:
							$message ='There was a problem creating your directory.';
							$type='danger';
							break;

							case 1:
							$message = 'Directory Created Successfully!';
							$type='success';
							break;

							case 2:
							$message ='A folder exists with the same name. Try again with a different name.';
							$type='warning';
							break;
						}
						break;
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(Yii::app()->request->urlReferrer);
				}elseif(isset($_GET['deleteFile']) && $listing->enableFileDeletion){
					switch(Navigation::checkIfAuthorized(214)){
						case 0:
						$message = 'You are not allowed to delete vault files.';
						$type='danger';
						break;

						case 1:
						if($listing->deleteFile()){
							$message = 'The file was successfully deleted!';
							$type='success';
						}else{
							$message = 'The selected file could not be deleted. Please check your file permissions and try again.';
							$type='danger';
						}
						break;
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(Yii::app()->request->urlReferrer);
				}elseif(isset($_GET['dir']) && isset($_GET['delete']) && $listing->enableDirectoryDeletion){
					switch(Navigation::checkIfAuthorized(214)){
						case 0:
						$message = 'You are not allowed to delete vault folders/directories.';
						$type='danger';
						break;

						case 1:
						if($listing->deleteDirectory() === 1) {
							$message = 'The directory was successfully deleted!';
							$type='success';
						}else{
							$message = 'The selected directory could not be deleted. Please check your file permissions and try again.';
							$type='danger';
						}
						break;
					}
					CommonFunctions::setFlashMessage($type,$message);
					$this->redirect(array('folders/admin'));
				}
				$this->render('admin',array('model'=>$model,'listing'=>$listing,'data'=>$data));
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Folders the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Folders::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Folders $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='folders-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
