<?php

class ExpensesController extends Controller{
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
				'actions'=>array('create','update','uploadReceipt'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','view'),
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
    	switch(Navigation::checkIfAuthorized(101)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Expenses.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
    		$files=Expenditure::getExpenseFiles($id);
    		$model=$this->loadModel($id);
			$this->render('view',array('model'=>$model,'files'=>$files));
    		break;
    	}
	}

	public function actionUploadReceipt($id){
      switch(Navigation::checkIfAuthorized(99)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to upload expenditure receipt.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$loan=$this->loadModel($id);
			$documentURL=Yii::app()->params['expenseDocs'];
				if(isset($_POST['upload_file_cmd'])){
				$file_name=CUploadedFile::getInstanceByName('filename');
				if(empty($file_name) || $file_name === ''){
					CommonFunctions::setFlashMessage('danger',"No receipt uploaded.");
					$this->redirect(Yii::app()->request->urlReferrer);
				}else{
					$file_existence=ExpenseFiles::model()->find('filename=:a',array('a'=>$file_name));
						if(!empty($file_existence)){
							CommonFunctions::setFlashMessage('danger',"Uploading receipt failed. The receipt already exists.");
							$this->redirect(Yii::app()->request->urlReferrer);
						}else{
							$model=new ExpenseFiles;
							$model->expense_id=$id;
							$model->filename=$file_name;
							$model->uploaded_by=Yii::app()->user->user_id;
							if($model->save()){
								$expense=Expenses::model()->findByPk($model->expense_id);
								$model->filename->saveAs($documentURL.$model->filename);
								$expenseName=$expense->name;
								$branchName=$expense->ExpenseBranchName;
								Logger::logUserActivity("Added expense receipt: $expenseName for $branchName",'normal');
								$type='success';
								$message="Receipt successfully uploaded.";
							}else{
								$type='danger';
								$message="Uploading receipt failed. Please ensure the file is a JPG or PNG or a PDF or a DOCX";
							}
							CommonFunctions::setFlashMessage($type,$message);
							$this->redirect(Yii::app()->request->urlReferrer);
					}
				}
			}
			$this->redirect(Yii::app()->request->urlReferrer);
    		break;
    	}
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate(){
    	switch(Navigation::checkIfAuthorized(99)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create Expenses.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$model=new Expenses;
			if(isset($_POST['Expenses'])){
				$model->attributes=$_POST['Expenses'];
				$model->created_by=Yii::app()->user->user_id;
				$model->user_id=Yii::app()->user->user_id;
				$model->branch_id=$_POST['Expenses']['branch_id'];
				if($model->save()){
					$expenseName=$model->name;
					Logger::logUserActivity("Added Expense Record : $expenseName",'normal');
					CommonFunctions::setFlashMessage('success',"Expense successfully created.");
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
		$model=$this->loadModel($id);
		if($model->modifiable === '0'){
			CommonFunctions::setFlashMessage('danger',"The expense cannot be updated/modified.");
			$this->redirect(array('admin'));
		}else{
			switch(Navigation::checkIfAuthorized(100)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update Expenses.");
				$this->redirect(Yii::app()->request->urlReferrer);
				break;

				case 1:
				if(isset($_POST['Expenses'])){
					$model->attributes=$_POST['Expenses'];
					if($model->save()){
						$expenseName=$model->name;
						Logger::logUserActivity("Updated Expense Record: $expenseName",'normal');
						CommonFunctions::setFlashMessage('info',"Expense successfully updated.");
						$this->redirect(array('admin'));
					}
				}
				$this->render('update',array('model'=>$model));
				break;
			}
		}
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id){
    	$model=$this->loadModel($id);
		$expenseName=$model->name;
		if($model->modifiable === '0'){
			CommonFunctions::setFlashMessage('warning',"The expense cannot be deleted.");
			$this->redirect(array('admin'));
		}else{
			switch(Navigation::checkIfAuthorized(102)){
				case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to delete Expenses.");
				$this->redirect(Yii::app()->request->urlReferrer);
				break;

				case 1:
				$this->loadModel($id)->delete();
				Logger::logUserActivity("Deleted Expense: $expenseName",'urgent');
				CommonFunctions::setFlashMessage('success',"Expense successfully deleted.");
				$this->redirect(array('admin'));
				break;
			}
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
    	switch(Navigation::checkIfAuthorized(101)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Expenses.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			if(Navigation::checkIfAuthorized(192) === 0 && Navigation::checkIfAuthorized(193) === 0){
				CommonFunctions::setFlashMessage('danger',"Not Authorized to view Expenses.");;
			    $this->redirect(array('dashboard/default'));
			}
			$model=new Expenses('search');
			$model->unsetAttributes();
			if(isset($_GET['Expenses'])){
				$model->attributes=$_GET['Expenses'];
			}
			$this->render('admin',array('model'=>$model));
    		break;
    	}
	}	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Expenses the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Expenses::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Expenses $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='expenses-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
