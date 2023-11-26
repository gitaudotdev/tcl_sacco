<?php

class GuarantorsController extends Controller{
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
	public function accessRules(){
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','delete','reconcile','notify'),
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
    	switch(Navigation::checkIfAuthorized(109)){
    		case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to view guarantors.");
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
    	switch(Navigation::checkIfAuthorized(107)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to create guarantors.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=new Guarantors;
				if(isset($_POST['Guarantors'])){
					$accountID=$_POST['Guarantors']['loanaccount_id'];
					$name=$_POST['Guarantors']['name'];
					$idNumber=$_POST['Guarantors']['id_number'];
					$phoneNumber=$_POST['Guarantors']['phone'];
					switch(LoanApplication::createGuarantorRecord($accountID,$name,$idNumber,$phoneNumber)){
						case 0:
						CommonFunctions::setFlashMessage('danger',"Failed to create guarantor records.");
						break;

						case 1:
						CommonFunctions::setFlashMessage('success',"Guarantor successfully created.");
						break;
					}
					$this->redirect(array('admin'));
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
    	switch(Navigation::checkIfAuthorized(108)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to update guarantors.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=$this->loadModel($id);
				if(isset($_POST['Guarantors'])){
					$model->attributes=$_POST['Guarantors'];
					if($model->save()){
	          			$fullDetails=$model->name."-".$model->id_number;
				    	Logger::logUserActivity("Updated Guarantor Record: $fullDetails",'normal');
						CommonFunctions::setFlashMessage('info',"Guarantor successfully updated.");
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
		$fullDetails=$model->name."-".$model->id_number;
    	switch(Navigation::checkIfAuthorized(110)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete guarantors.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted Guarantor: $fullDetails",'urgent');
			CommonFunctions::setFlashMessage('success',"Guarantor successfully deleted.");
			$this->redirect(array('admin'));
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
			$model=new Guarantors('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Guarantors'])){
				$model->attributes=$_GET['Guarantors'];
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

	public function actionReconcile(){
	  $element=Yii::app()->user->user_level;
    $array=array('1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
    	case 0:
    	$guarantors=Guarantors::model()->findAll();
    	$counter=1;
    	foreach($guarantors AS $guarantor){
    		$loanaccount=Loanaccounts::model()->findByPk($guarantor->loanaccount_id);
    		if(!empty($loanaccount)){
	    		$guarantor->branch_id=$loanaccount->branch_id;
	    		$guarantor->user_id=$loanaccount->user_id;
	    		$guarantor->rm=$loanaccount->rm;
	    		$guarantor->save();
	    		echo $counter." ".$guarantor->name." updated records<br>";
    		}else{
    			echo "A/C NOT FOUND <br>";
    		}
    		$counter++;
    	}
    	break;

    	case 1:
			CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
  	 	$this->redirect(array('dashboard/default'));
    	break;
    }
	}

	public function actionNotify($id){
		$model = $this->loadModel($id);
		$loan  = Loanaccounts::model()->findByPk($model->loanaccount_id);
		switch(Navigation::checkIfAuthorized(284)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to send SMS to guarantors.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			if(isset($_POST['send_guarantor_cmd'])){
				$numbers     = array();
				array_push($numbers,$model->phone);
				$textMessage = htmlspecialchars($_POST['textMessage']);
				$status      = SMS::broadcastSMS($numbers,$textMessage,'40',$loan->user_id);
				$profileName = $model->name;
				switch($status){
					case 0:
					$type    = 'danger';
					$message = "Error occurred while sending SMS. Please ensure all phone numbers are available and in the correct format.";
					$redirect = array('notify','id'=>$id);
					break;

					case 1:
					$type    = 'success';
					$message = "SMS Sent successfully";
					Logger::logUserActivity("Sent Guarantor SMS Message: <strong>$textMessage</strong>: $profileName",'urgent');
					$redirect = array('loanaccounts/view','id'=>$loan->loanaccount_id);
					break;

					case 2:
					$type    = 'danger';
					$message = "An error occurred while trying to send the SMS. Consult your SMS service provider.";
					$redirect = array('notify','id'=>$id);
					break;

					case 3:
					$type    = 'danger';
					$message = "The SMS category has been deactivated. Ask the Administrator to activate the category.";
					$redirect = array('notify','id'=>$id);
					break;

					case 5:
					$type    = 'danger';
					$message = "SMS notification failed since the user record not found.";
					$redirect = array('notify','id'=>$id);
					break;

					case 6:
					$type    = 'danger';
					$message = "The SMS notification cannot be initiated to the user since the user's SMS Alerts setting is DISABLED.";
					$redirect = array('notify','id'=>$id);
					break;
				}
				CommonFunctions::setFlashMessage($type,$message);
				$this->redirect($redirect);
			}
			$this->render('notify',array('model'=>$model,'loan'=>$loan));
    		break;
    	}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Guarantors the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=Guarantors::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Guarantors $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='guarantors-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
