<?php

class PenaltiesController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/templates/pages';

    /**
     * @return array action filters
     */
    public function filters()
    {
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
    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete',),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        switch (Navigation::checkIfAuthorized(310)) {
            case 0:
                CommonFunctions::setFlashMessage('danger', "Not Authorized to view write offs Report.");
                $this->redirect(Yii::app()->request->urlReferrer);
                break;

            case 1:
                $model = new Penaltyaccrued('search');
                $model->unsetAttributes();
                if (isset($_GET['Penaltyaccrued'])) {
                    $model->attributes = $_GET['Penaltyaccrued'];
                }
                $this->render('admin', array('model' => $model));
                break;
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Penaltyaccrued the loaded model
     * @throws CHttpException
     */
    public function loadModel($id){
        $model=Penaltyaccrued::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }
    /**
     * Performs the AJAX validation.
     * @param WriteOffs $model the model to be validated
     */
    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='penalty-accrued-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}