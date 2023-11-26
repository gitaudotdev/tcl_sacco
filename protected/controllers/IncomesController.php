<?php

class IncomesController extends Controller
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){
    	switch(Navigation::checkIfAuthorized(105)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to view Incomes");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
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
    	switch(Navigation::checkIfAuthorized(103)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to create Incomes");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=new Incomes;
				if(isset($_POST['Incomes'])){
					$model->attributes=$_POST['Incomes'];
					$model->created_by=Yii::app()->user->user_id;
					$model->attachment=date('YmdHis',time()).mt_rand();
					$model->created_at=date('Y-m-d H:i:s');
					if($model->save()){
						$incomeName=$model->name;
			    		Logger::logUserActivity("Added Income Record: $incomeName",'normal');
						CommonFunctions::setFlashMessage('success',"Income successfully created.");
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
    	switch(Navigation::checkIfAuthorized(104)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to update Incomes");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=$this->loadModel($id);
				if(isset($_POST['Incomes'])){
					$model->attributes=$_POST['Incomes'];
					if($model->save()){
						$incomeName=$model->name;
			      Logger::logUserActivity("Updated Income Record: $incomeName",'normal');
						CommonFunctions::setFlashMessage('info',"Income successfully updated.");
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
    	switch(Navigation::checkIfAuthorized(106)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to delete Incomes");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$this->loadModel($id)->delete();
		    Logger::logUserActivity("Deleted Income: $incomeName.",'urgent');
				CommonFunctions::setFlashMessage('success',"Income successfully deleted.");
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
			$model=new Incomes('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Incomes'])){
				$model->attributes=$_GET['Incomes'];
			}
			$this->render('admin',array(
				'model'=>$model,
			));
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
	 * @return Incomes the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Incomes::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Incomes $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='incomes-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
