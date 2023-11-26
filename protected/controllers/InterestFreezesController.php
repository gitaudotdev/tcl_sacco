<?php

class InterestFreezesController extends Controller{

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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','updateDetails'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(196)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view frozen accounts Report.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
				$model=new InterestFreezes('search');
				$model->unsetAttributes(); 
				if(isset($_GET['InterestFreezes'])){
					$model->attributes=$_GET['InterestFreezes'];
					if(isset($_GET['export'])){
						$dataProvider = $model->search();
						$dataProvider->pagination = False;
						$excelWriter = ExportFunctions::getExcelFrozenLoanAccountsReport($dataProvider->data);
						echo $excelWriter->save('php://output');
					}
				}
				$this->render('admin',array('model'=>$model));
    		break;
    	}
	}

	public function actionUpdateDetails(){
		$freezes = InterestFreezes::model()->findAll();
		foreach($freezes AS $freeze){
			$loanaccount = Loanaccounts::model()->findByPk($freeze->loanaccount_id);
			if(!empty($loanaccount)){
				$freeze->user_id=$loanaccount->user_id;
				$freeze->rm=$loanaccount->rm;
				$freeze->branch_id=$loanaccount->branch_id;
				$freeze->update();

			}
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return InterestFreezes the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=InterestFreezes::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param InterestFreezes $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='interest-freezes-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
