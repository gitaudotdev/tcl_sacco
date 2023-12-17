<?php

class UsersController extends Controller{
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update','reset','view'),
                'users'=>array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete','activate','deactivate'),
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
        $model=$this->loadModel($id);
        $logs=Logger::getUserLogs($id);
        $borrower=BorrowerFunctions::getBorrowerByUserID($id);
        $fullname=$model->UserFullName;
        $timestamp =date('jS M Y \a\t g:ia');
        $userBranch=Yii::app()->user->user_branch;
        $array=array('2','3','4');
        $arrayChecker=array('0');
        $arrayConfirm=array($model->branch_id);
        $element=Yii::app()->user->user_level;
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(17)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to view users.");
                        $this->redirect(array('admin'));
                        break;

                    case 1:
                        switch(CommonFunctions::searchElementInArray($element,$arrayChecker)){
                            case 0:
                                if(CommonFunctions::searchElementInArray($userBranch,$arrayConfirm) === 1){
                                    $this->render('view',array('model'=>$model,'logs'=>$logs,'id'=>$id,'borrower'=>$borrower));
                                }else{
                                    CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                                    $this->redirect(array('admin'));
                                }
                                break;

                            case 1:
                                $this->render('view',array('model'=>$model,'logs'=>$logs,'id'=>$id,'borrower'=>$borrower));
                                break;
                        }
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('admin'));
                break;
        }
    }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate(){
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(15)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to Create User.");
                        $this->redirect(array('admin'));
                        break;

                    case 1:
                        $model=new Users;
                        if(isset($_POST['Users'])){
                            $model->attributes=$_POST['Users'];
                            $model->password=password_hash($_POST['Users']['password'],PASSWORD_DEFAULT);
                            $model->created_by=Yii::app()->user->user_id;
                            $model->rm=Yii::app()->user->user_id;
                            if($model->save()){
                                $fullName=$model->UserFullName;
                                Logger::logUserActivity("Created System User: $fullName",'high');
                                CommonFunctions::setFlashMessage('success',"User successfully created.");
                                $this->redirect(array('admin'));
                            }
                        }
                        $this->render('create',array('model'=>$model));
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('admin'));
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
        $userBranch=Yii::app()->user->user_branch;
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4');
        $arrayChecker=array('0');
        $arrayConfirm=array($model->branch_id);
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(16)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to Update User.");
                        $this->redirect(array('admin'));
                        break;

                    case 1:
                        switch(CommonFunctions::searchElementInArray($element,$arrayChecker)){
                            case 0:
                                if(CommonFunctions::searchElementInArray($userBranch,$arrayConfirm) === 1){
                                    if(isset($_POST['Users'])){
                                        if($model->level === '5'){
                                            $fixedEnlisted=htmlspecialchars($_POST['Users']['fixed_payment_enlisted']);
                                            $model->fixed_payment_enlisted=$fixedEnlisted;
                                        }
                                        $bad_symbols = array(",");
                                        $model->attributes   = $_POST['Users'];
                                        $maxLimit = htmlspecialchars(str_replace($bad_symbols,"",$_POST['Users']['maximum_limit']));
                                        $model->maximum_limit    = $maxLimit;
                                        $model->loans_interest   = $_POST['Users']['loans_interest'];
                                        $model->savings_interest = $_POST['Users']['savings_interest'];
                                        $model->created_at       = htmlspecialchars($_POST['Users']['created_at']);
                                        $model->rm               = htmlspecialchars($_POST['Users']['rm']);
                                        if($model->save()){
                                            $fullName = $model->UserFullName;
                                            User::commitDrillDownUpdate($model);
                                            Logger::logUserActivity("Updated System User: $fullName",'high');
                                            CommonFunctions::setFlashMessage('success',"User successfully updated.");
                                            $this->redirect(array('users/'.$id));
                                        }
                                    }
                                    $this->render('update',array('model'=>$model,));
                                }else{
                                    CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                                    $this->redirect(array('admin'));
                                }
                                break;

                            case 1:
                                if(isset($_POST['Users'])){
                                    if($model->level === '5'){
                                        $fixedEnlisted=htmlspecialchars($_POST['Users']['fixed_payment_enlisted']);
                                        $model->fixed_payment_enlisted=$fixedEnlisted;
                                    }
                                    $bad_symbols = array(",");
                                    $model->attributes=$_POST['Users'];
                                    $maxLimit = htmlspecialchars(str_replace($bad_symbols,"",$_POST['Users']['maximum_limit']));
                                    $model->maximum_limit=$maxLimit;
                                    $model->loans_interest   = $_POST['Users']['loans_interest'];
                                    $model->savings_interest = $_POST['Users']['savings_interest'];
                                    $model->created_at=htmlspecialchars($_POST['Users']['created_at']);
                                    $model->rm=htmlspecialchars($_POST['Users']['rm']);
                                    if($model->save()){
                                        $fullName=$model->UserFullName;
                                        User::commitDrillDownUpdate($model);
                                        Logger::logUserActivity("Updated System User: $fullName",'high');
                                        CommonFunctions::setFlashMessage('success',"User successfully updated.");
                                        $this->redirect(array('users/'.$id));
                                    }
                                }
                                $this->render('update',array('model'=>$model,));
                                break;
                        }
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('admin'));
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
        $fullname=$model->UserFullName;
        $timestamp =date('jS M Y \a\t g:ia');
        $userBranch=Yii::app()->user->user_branch;
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4');
        $arrayChecker=array('0');
        $arrayConfirm=array($model->branch_id);
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(19)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to delete User.");
                        $this->redirect(array('admin'));
                        break;

                    case 1:
                        switch(CommonFunctions::searchElementInArray($element,$arrayChecker)){
                            case 0:
                                if(CommonFunctions::searchElementInArray($userBranch,$arrayConfirm) === 1){
                                    Yii::app()->db->createCommand("DELETE FROM user_role WHERE user_id=$id")->execute();
                                    $this->loadModel($id)->delete();
                                    Logger::logUserActivity("Deleted User Records: $fullname on $timestamp",'urgent');
                                    $type='success';
                                    $message="User Records successfully deleted.";
                                }else{
                                    $type='danger';
                                    $message="Restricted Area. You are not allowed to access this resource.";
                                }
                                break;

                            case 1:
                                Yii::app()->db->createCommand("DELETE FROM user_role WHERE user_id=$id")->execute();
                                $this->loadModel($id)->delete();
                                Logger::logUserActivity("Deleted User Records: $fullname on $timestamp",'urgent');
                                $type='success';
                                $message="User Records successfully deleted.";
                                break;
                        }
                        CommonFunctions::setFlashMessage($type,$message);
                        $this->redirect(array('admin'));
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
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
                $model=new Users('search');
                $model->unsetAttributes();  // clear any default values
                if(isset($_GET['Users'])){
                    $model->attributes=$_GET['Users'];
                }
                $this->render('admin',array(
                    'model'=>$model,
                ));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('admin'));
                break;
        }
    }

    public function actionActivate($id){
        $model=$this->loadModel($id);
        $fullname=$model->UserFullName;
        $timestamp =date('jS M Y \a\t g:ia');
        $userBranch=Yii::app()->user->user_branch;
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4');
        $arrayChecker=array('0');
        $arrayConfirm=array($model->branch_id);
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(20)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to activate users.");
                        $this->redirect(array('admin'));
                        break;

                    case 1:
                        switch(CommonFunctions::searchElementInArray($element,$arrayChecker)){
                            case 0:
                                if(CommonFunctions::searchElementInArray($userBranch,$arrayConfirm) === 1){
                                    switch(User::activateUserAccount($id)){
                                        case 0:
                                            $type='warning';
                                            $message="Account could not be activated.";
                                            break;

                                        case 1:
                                            Logger::logUserActivity("Activated System User: $fullname on $timestamp",'high');
                                            $type='success';
                                            $message="Account successfully activated.";
                                            break;
                                    }
                                    CommonFunctions::setFlashMessage($type,$message);
                                    $this->redirect(array('users/'.$id));
                                }else{
                                    CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                                    $this->redirect(array('admin'));
                                }
                                break;

                            case 1:
                                switch(User::activateUserAccount($id)){
                                    case 0:
                                        $type='warning';
                                        $message="Account could not be activated.";
                                        break;

                                    case 1:
                                        Logger::logUserActivity("Activated System User: $fullname on $timestamp",'high');
                                        $type='success';
                                        $message="Account successfully activated.";
                                        break;
                                }
                                CommonFunctions::setFlashMessage($type,$message);
                                $this->redirect(array('users/'.$id));
                                break;
                        }
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('admin'));
                break;
        }
    }

    public function actionDeactivate($id){
        $model=$this->loadModel($id);
        $fullname=$model->UserFullName;
        $timestamp =date('jS M Y \a\t g:ia');
        $userBranch=Yii::app()->user->user_branch;
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4');
        $arrayChecker=array('0');
        $arrayConfirm=array($model->branch_id);
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(21)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to deactivate users.");
                        $this->redirect(array('admin'));
                        break;

                    case 1:
                        switch(CommonFunctions::searchElementInArray($element,$arrayChecker)){
                            case 0:
                                if(CommonFunctions::searchElementInArray($userBranch,$arrayConfirm) === 1){
                                    switch(User::deactivateUserAccount($id)){
                                        case 0:
                                            $type='warning';
                                            $message="Account could not be deactivated.";
                                            break;

                                        case 1:
                                            Logger::logUserActivity("Deactivated System User: $fullname on $timestamp",'high');
                                            $type='success';
                                            $message="Account successfully deactivated.";
                                            break;
                                    }
                                    CommonFunctions::setFlashMessage($type,$message);
                                    $this->redirect(array('users/'.$id));
                                }else{
                                    CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                                    $this->redirect(array('admin'));
                                }
                                break;

                            case 1:
                                switch(User::deactivateUserAccount($id)){
                                    case 0:
                                        $type='warning';
                                        $message="Account could not be deactivated.";
                                        break;

                                    case 1:
                                        Logger::logUserActivity("Deactivated System User: $fullname on $timestamp",'high');
                                        $type='success';
                                        $message="Account successfully deactivated.";
                                        break;
                                }
                                CommonFunctions::setFlashMessage($type,$message);
                                $this->redirect(array('users/'.$id));
                                break;
                        }
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('admin'));
                break;
        }
    }

    public function actionReset($id){
        $model=$this->loadModel($id);
        $fullname=$model->UserFullName;
        $timestamp =date('jS M Y \a\t g:ia');
        $userBranch=Yii::app()->user->user_branch;
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4');
        $arrayChecker=array('0');
        $arrayConfirm=array($model->branch_id);
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(18)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to reset user passwords.");
                        $this->redirect(array('admin'));
                        break;

                    case 1:
                        switch(CommonFunctions::searchElementInArray($element,$arrayChecker)){
                            case 0:
                                if(CommonFunctions::searchElementInArray($userBranch,$arrayConfirm) === 1){
                                    $generatedPassword=CommonFunctions::generateRandomString();
                                    switch(Password::resetUserPassword($id,$generatedPassword,$generatedPassword)){
                                        case 0:
                                            CommonFunctions::setFlashMessage('warning',"Password could not be reset.");
                                            $this->redirect(array('admin'));
                                            break;

                                        case 1:
                                            $user=Users::model()->findByPk($id);
                                            Logger::logUserActivity("Reset System User Password for : $fullname on $timestamp",'high');
                                            $user_full_name=$user->last_name;
                                            if(is_numeric($user->email)){
                                                $numbers=array();
                                                array_push($numbers,$user->email);
                                                $textMessage="Dear $user_full_name, Your password was successfully reset.
									Please use the following credentials:
									Username - $user->username and Password - $generatedPassword";
                                                $alertType='1';
                                                SMS::broadcastSMS($numbers,$textMessage,$alertType,$id);
                                            }else{
                                                $numbers=array();
                                                array_push($numbers,$user->phone);
                                                $textMessage="Dear $user_full_name, Your password was successfully reset.
									Please use the following credentials:
									Username - $user->username and Password - $generatedPassword";
                                                $alertType='1';
                                                SMS::broadcastSMS($numbers,$textMessage,$alertType,$id);
                                                $name = 'IT Service Desk';
                                                $subject    =   'Password Reset Service';
                                                $body       =   "<p>Welcome to Treasure Capital Systems.</p>
									<p>Your password was successfully reset by the system administrator.</p>
									<p>Please log in with the following credentials:</p>
									<p><strong>Username - $user->username <br>Password - $generatedPassword </strong></p>
									<p>Do not hesitate to reach out if you need help.</p>";
                                                $message = Mailer::Build($name,$subject,$body, $user_full_name);
                                                $emailStatus=CommonFunctions::broadcastEmailNotification($user->email,$subject,$message);
                                            }
                                            CommonFunctions::setFlashMessage('success',"Password successfully reset.");
                                            $this->redirect(array('admin'));
                                            break;
                                    }
                                }else{
                                    CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                                    $this->redirect(array('admin'));
                                }
                                break;

                            case 1:
                                $generatedPassword=CommonFunctions::generateRandomString();
                                switch(Password::resetUserPassword($id,$generatedPassword,$generatedPassword)){
                                    case 0:
                                        CommonFunctions::setFlashMessage('warning',"Password could not be reset.");
                                        $this->redirect(array('admin'));
                                        break;

                                    case 1:
                                        $user=Users::model()->findByPk($id);
                                        Logger::logUserActivity("Reset System User Password for : $fullname on $timestamp",'high');
                                        $user_full_name=$user->last_name;
                                        if(is_numeric($user->email)){
                                            $numbers=array();
                                            array_push($numbers,$user->email);
                                            $textMessage="Dear $user_full_name, Your password was successfully reset.
							Please use the following credentials:
							Username - $user->username and Password - $generatedPassword";
                                            $alertType='1';
                                            SMS::broadcastSMS($numbers,$textMessage,$alertType,$id);
                                        }else{
                                            $numbers=array();
                                            array_push($numbers,$user->phone);
                                            $textMessage="Dear $user_full_name, Your password was successfully reset.
							Please use the following credentials:
							Username - $user->username and Password - $generatedPassword";
                                            $alertType='1';
                                            SMS::broadcastSMS($numbers,$textMessage,$alertType,$id);
                                            $name = 'IT Service Desk';
                                            $subject    =   'Password Reset Service';
                                            $body       =   "<p>Welcome to Treasure Capital Systems.</p>
							<p>Your password was successfully reset by the system administrator.</p>
							<p>Please log in with the following credentials:</p>
							<p><strong>Username - $user->username <br>Password - $generatedPassword </strong></p>
							<p>Do not hesitate to reach out if you need help.</p>";
                                            $message = Mailer::Build($name,$subject,$body, $user_full_name);
                                            $emailStatus=CommonFunctions::broadcastEmailNotification($user->email,$subject,$message);
                                        }
                                        CommonFunctions::setFlashMessage('success',"Password successfully reset.");
                                        $this->redirect(array('admin'));
                                        break;
                                }
                                break;
                        }
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('admin'));
                break;
        }
    }
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Users the loaded model
     * @throws CHttpException
     */
    public function loadModel($id){
        $model=Users::model()->findByPk($id);
        if($model===null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Users $model the model to be validated
     */
    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
