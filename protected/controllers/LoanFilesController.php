<?php

class LoanFilesController extends Controller
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
	public function accessRules(){
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','rename','deleteDisburse','deleteSubmit','deleteTopped','additionalRename'),
				'users'=>array('@'),
			),
		);
	}

	public function actionRename($id){
		$model         = $this->loadModel($id);
		$loanaccount   = Loanaccounts::model()->findByPk($model->loanaccount_id);
		$accountNumber = $loanaccount->account_number;
		$fullName      = $loanaccount->BorrowerFullName;
		switch(Navigation::checkIfAuthorized(177)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to rename loan file.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$fileDisplayName = $model->name;
			if(isset($_POST['rename_file_cmd'])){
				$newFileDisplayName = $_POST['new_file_name'];
				$loanFile           = $this->loadModel($id);
				$loanFile->name     = $newFileDisplayName;
				if($loanFile->save()){
					Logger::logUserActivity("Renamed Loan File: <strong>$fileDisplayName</strong> to <strong>$newFileDisplayName</strong> Loan Account : <strong>$accountNumber</strong> for Account Holder: <strong>$fullName</strong>",'urgent');
					CommonFunctions::setFlashMessage('success',"File successfully renamed.");
				}else{
					CommonFunctions::setFlashMessage('danger',"Failed to rename the file.");
				}
				Yii::app()->getController()->redirect(array('/loanaccounts/'.$model->loanaccount_id));
			}else{
				$this->render('rename',array('model'=>$model));
			}
			break;
		}
	}

	public function actionAdditionalRename($id){
		$model         = $this->loadModel($id);
		$loanaccount   = Loanaccounts::model()->findByPk($model->loanaccount_id);
		$accountNumber = $loanaccount->account_number;
		$fullName      = $loanaccount->BorrowerFullName;
		switch(Navigation::checkIfAuthorized(177)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to rename loan file.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$fileDisplayName = $model->name;
			if(isset($_POST['rename_file_cmd'])){
				$newFileDisplayName = $_POST['new_file_name'];
				$loanFile           = $this->loadModel($id);
				$loanFile->name     = $newFileDisplayName;
				if($loanFile->save()){
					Logger::logUserActivity("Renamed Loan File: <strong>$fileDisplayName</strong> to <strong>$newFileDisplayName</strong> Loan Account : <strong>$accountNumber</strong> for Account Holder: <strong>$fullName</strong>",'urgent');
					CommonFunctions::setFlashMessage('success',"File successfully renamed.");
				}else{
					CommonFunctions::setFlashMessage('danger',"Failed to rename the file.");
				}
				Yii::app()->getController()->redirect(array('/loanaccounts/viewDetails/'.$model->loanaccount_id));
			}else{
				$this->render('additionalRename',array('model'=>$model));
			}
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
    $loanaccount=Loanaccounts::model()->findByPk($model->loanaccount_id);
	$accountNumber=$loanaccount->account_number;
	$fullName=$loanaccount->BorrowerFullName;
      switch(Navigation::checkIfAuthorized(176)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete loan file.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
    		$documentURL=Yii::app()->params['loanDocs'];
    		$uploadedFileName=$model->filename;
    		$fileDisplayName=$model->name;
    		$fileDestination=$documentURL.'/'.$uploadedFileName;
    		if(file_exists($fileDestination)){
    			unlink($fileDestination);
    		}
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted Loan File: <strong>$uploadedFileName</strong> with name: <strong>$fileDisplayName</strong> Loan Account : <strong>$accountNumber</strong> for Account Holder: <strong>$fullName</strong>",'urgent');
			CommonFunctions::setFlashMessage('success',"File successfully deleted.");
			Yii::app()->getController()->redirect(array('/loanaccounts/'.$model->loanaccount_id));
    		break;
    	}
	}

	public function actionDeleteDisburse($id){
		$model=$this->loadModel($id);
		$loanaccount=Loanaccounts::model()->findByPk($model->loanaccount_id);
		$accountNumber=$loanaccount->account_number;
		$fullName=$loanaccount->BorrowerFullName;
		switch(Navigation::checkIfAuthorized(176)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete loan file.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$documentURL=Yii::app()->params['loanDocs'];
			$uploadedFileName=$model->filename;
			$fileDisplayName=$model->name;
			$fileDestination=$documentURL.'/'.$uploadedFileName;
			if(file_exists($fileDestination)){
				unlink($fileDestination);
			}
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted Loan File: <strong>$uploadedFileName</strong> with name: <strong>$fileDisplayName</strong> Loan Account : <strong>$accountNumber</strong> for Account Holder: <strong>$fullName</strong>",'urgent');
			CommonFunctions::setFlashMessage('success',"File successfully deleted.");
			Yii::app()->getController()->redirect(array('/loanaccounts/disburse/'.$model->loanaccount_id));
			break;
		}
	}

	public function actionDeleteTopped($id){
		$model=$this->loadModel($id);
		$loanaccount=Loanaccounts::model()->findByPk($model->loanaccount_id);
		$accountNumber=$loanaccount->account_number;
		$fullName=$loanaccount->BorrowerFullName;
		switch(Navigation::checkIfAuthorized(176)){
			case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to delete loan file.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$documentURL=Yii::app()->params['loanDocs'];
			$uploadedFileName=$model->filename;
			$fileDisplayName=$model->name;
			$fileDestination=$documentURL.'/'.$uploadedFileName;
			if(file_exists($fileDestination)){
				unlink($fileDestination);
			}
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted Loan File: <strong>$uploadedFileName</strong> with name: <strong>$fileDisplayName</strong> Loan Account : <strong>$accountNumber</strong> for Account Holder: <strong>$fullName</strong>",'urgent');
			CommonFunctions::setFlashMessage('success',"File successfully deleted.");
			Yii::app()->getController()->redirect(array('/loanaccounts/viewTopup/'.$model->loanaccount_id));
			break;
		}
	}

	public function actionDeleteSubmit($id){
		$model=$this->loadModel($id);
		$loanaccount=Loanaccounts::model()->findByPk($model->loanaccount_id);
		$accountNumber=$loanaccount->account_number;
		$fullName=$loanaccount->BorrowerFullName;
		switch(Navigation::checkIfAuthorized(176)){
			case 0:
				CommonFunctions::setFlashMessage('danger',"Not Authorized to delete loan file.");
			$this->redirect(array('dashboard/default'));
			break;

			case 1:
			$documentURL=Yii::app()->params['loanDocs'];
			$uploadedFileName=$model->filename;
			$fileDisplayName=$model->name;
			$fileDestination=$documentURL.'/'.$uploadedFileName;
			if(file_exists($fileDestination)){
				unlink($fileDestination);
			}
			$this->loadModel($id)->delete();
			Logger::logUserActivity("Deleted Loan File: <strong>$uploadedFileName</strong> with name: <strong>$fileDisplayName</strong> Loan Account : <strong>$accountNumber</strong> for Account Holder: <strong>$fullName</strong>",'urgent');
			CommonFunctions::setFlashMessage('success',"File successfully deleted.");
			Yii::app()->getController()->redirect(array('/loanaccounts/viewDetails/'.$model->loanaccount_id));
			break;
		}
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return LoanFiles the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id){
		$model=LoanFiles::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param LoanFiles $model the model to be validated
	 */
	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='loan-files-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
