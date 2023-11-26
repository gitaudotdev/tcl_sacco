<?php

class SavingproductsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/templates/pages';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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
	 * Creates a new model.
	 * If creation is successful
	 */
	public function actionCreate(){
	  $element=Yii::app()->user->user_level;
    $array=array('2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
			$model=new Savingproducts;
			if(isset($_POST['Savingproducts'])){
				$model->attributes=$_POST['Savingproducts'];
				$model->posting_date=(int)$_POST['Savingproducts']['posting_date'] + 1;
				$model->created_by=Yii::app()->user->user_id;
				if($model->save()){
					$activity="Added Saving Product";
		      $severity='normal';
		      Logger::logUserActivity($activity,$severity);
					$type='success';
					$message="Product successfully created.";
					CommonFunctions::setFlashMessage($type,$message);
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
  	 	$this->redirect(array('dashboard/index'));
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
    $array=array('1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
			$model=$this->loadModel($id);
			if(isset($_POST['Savingproducts'])){
				$model->attributes=$_POST['Savingproducts'];
				$model->posting_date=(int)$_POST['Savingproducts']['posting_date'] + 1;
				if($model->save()){
					$activity="Updated Saving Product";
		      $severity='normal';
		      Logger::logUserActivity($activity,$severity);
					$type='info';
					$message="Product successfully updated.";
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
  	 	$this->redirect(array('dashboard/index'));
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
    $array=array('1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
			$accountSql="SELECT * FROM savingaccounts WHERE savingproduct_id=$id";
			$savingAccounts=Savingaccounts::model()->findAllBySql($accountSql);
			if(!empty($savingAccounts)){
				foreach($savingAccounts as $savingaccount){
					Yii::app()->db->createCommand("DELETE FROM savingaccounts WHERE savingaccount_id={$savingaccount->savingaccount_id}")->execute();
					Yii::app()->db->createCommand("DELETE FROM savingtransactions WHERE savingaccount_id={$savingaccount->savingaccount_id}")->execute();
				}
				$this->loadModel($id)->delete();
				$activity="Deleted Saving Product";
		    $severity='urgent';
		    Logger::logUserActivity($activity,$severity);
				$type='success';
				$message="Saving Product successfully deleted.";
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('admin'));
			}else{
				$this->loadModel($id)->delete();
				$activity="Deleted Saving Product";
		    $severity='urgent';
		    Logger::logUserActivity($activity,$severity);
				$type='success';
				$message="Saving Product successfully deleted.";
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect(array('admin'));
			}
    	break;

    	case 1:
			$type='danger';
			$message="Restricted Area. You are not allowed to access this resource.";
			CommonFunctions::setFlashMessage($type,$message);
  	 	$this->redirect(array('dashboard/index'));
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
			$model=new Savingproducts('search');
			$model->unsetAttributes(); 
			if(isset($_GET['Savingproducts'])){
				$model->attributes=$_GET['Savingproducts'];
			}
			$this->render('admin',array(
				'model'=>$model,
			));
    	break;

    	case 1:
			$type='danger';
			$message="Restricted Area. You are not allowed to access this resource.";
			CommonFunctions::setFlashMessage($type,$message);
  	 	$this->redirect(array('dashboard/index'));
    	break;
    }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Savingproducts the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Savingproducts::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Savingproducts $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='savingproducts-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
