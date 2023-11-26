<?php

class LoancommentsController extends Controller{
	
	public $layout='//layouts/templates/pages';

	
	public function filters(){
		return array(
			'accessControl', 
		);
	}

	public function accessRules(){
		return array(
			array('allow', 
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny', 
				'users'=>array('*'),
			),
		);
	}

	public function actionAdmin(){
		switch(Navigation::checkIfAuthorized(256)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view loan comments report");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
			break;
			
			case 1:
			if(Navigation::checkIfAuthorized(259) == 1){
				$model = new Loancomments('search');
				$model->unsetAttributes(); 
				if(isset($_GET['Loancomments'])){
					$model->attributes=$_GET['Loancomments'];
					if(isset($_GET['export'])){
						$dataProvider = $model->search();
						$dataProvider->pagination = False;
						$excelWriter = ExportFunctions::getExcelCommentsReport($dataProvider->data);
						echo $excelWriter->save('php://output');
					}
				}
				$this->render('admin',array('model'=>$model));
			}else{
				CommonFunctions::setFlashMessage('danger',"Not Authorized to download loan comments report");
				$this->redirect(Yii::app()->request->urlReferrer);
			}
			break;
		}
	}

	public function loadModel($id){
		$model=Loancomments::model()->findByPk($id);
		if($model===null){
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	protected function performAjaxValidation($model){
		if(isset($_POST['ajax']) && $_POST['ajax']==='loancomments-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
