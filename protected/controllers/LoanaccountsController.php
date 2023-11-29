<?php

class LoanaccountsController extends Controller{

    public $layout='//layouts/templates/pages';


    public function filters(){
        return array(
            'accessControl',
        );
    }

    public function accessRules(){
        return array(
            array('allow',
                'actions'=>array('repo','loadFilteredApplications','create','update','view','admin','delete','approve','reject',
                    'disburse','collateral','commitApproval','commitRejection','commitDisbursement','repay',
                    'commitRepayment','commitCollateral','comment','commitComment','due','loadDueLoans','noRepayment',
                    'principalOutstanding','calculator','loadInterest','loadSchedule','pastMaturity','dailyCollection',
                    'loadDailyCollectionSheet','collectionPastMaturity','loadCollectionsPastMaturityDate','missedRepayments',
                    'loadFilteredMissedRepayments','upload','importHeavyData','loadsavingBalances',
                    'deleteFile','return','loanResubmission','forward','recall','loadAccountNumbers','loadPhoneNumbers','topup',
                    'loadTopUpDetails','loadTopUpDisbursement','commitTopup','viewTopup','approveTopup','rejectTopup','disburseTopUp',
                    'writeOff','filterLoanStatement','viewDetails','resubmit','makeFile','loadExistence','revert','writeOffPenalty',
                    'writeOffAccruedInterest','freeze','commitFreezing','unfreeze','commitUnfreezing',
                    'loadUserEmployer','updateDetails','updateStatus','loanRecovery','exportLoans','profitAndLoss','exportProfitAndLoss',
                    'dailyAccountReport','updateDates','updateRecentDates','disbursedAccounts','addGuarantor','deletePrincipalBalance',
                    'updateWriteOffs','updateFrozenAccounts','createClearanceRecords','loanRepaymentSTKPush', 'loadBorrowerList'),
                'users'=>array('@'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
        );
    }
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id){
        $userID   = Yii::app()->user->user_id;
        $element  = Yii::app()->user->user_level;
        $array    = array('4');
        $arrayChecker = array('0','1','8','9','10');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(32)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to view loan account.");
                        $this->redirect(array('dashboard/default'));
                        break;

                    case 1:
                        $model        = $this->loadModel($id);
                        $arrayConfirm = array($model->user_id,$model->rm);
                        $profile      = Profiles::model()->findByPk($model->user_id);
                        $repayments   = LoanRepayment::getAllLoanRepayments($id);
                        $collaterals  = CollateralFunctions::getAllLoanCollateral($id);
                        $comments     = LoanApplication::getLoanComments($id);
                        $loanfiles    = LoanApplication::getLoanAccountFiles($id);
                        $savingAccounts= SavingFunctions::getAllUserSavingAccounts($model->user_id);
                        if($savingAccounts ===0){
                            $accountID = 0;
                        }else{
                            foreach($savingAccounts as $account) {
                                $accountID=$account->savingaccount_id;
                            }
                        }
                        $transactions = SavingFunctions::getAllSavingAccountTransactions($accountID);
                        $guarantors   = LoanApplication::getAccountGuarantors($id);
                        $others       = LoanManager::getOtherProfileLoanAccounts($profile->id,$id);
                        switch(CommonFunctions::searchElementInArray($element,$arrayChecker)){
                            case 0:
                                if(CommonFunctions::searchElementInArray($userID,$arrayConfirm) === 1){
                                    $this->render('view',array('model'=>$this->loadModel($id),'repayments'=>$repayments,'collaterals'=>$collaterals,
                                        'comments'=>$comments,'files'=>$loanfiles,'transactions'=>$transactions,'guarantors'=>$guarantors,'others'=>$others));
                                }else{
                                    CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                                    $this->redirect(array('admin'));
                                }
                                break;

                            case 1:
                                $this->render('view',array('model'=>$this->loadModel($id),'repayments'=>$repayments,'collaterals'=>$collaterals,
                                    'comments'=>$comments,'files'=>$loanfiles,'transactions'=>$transactions,'guarantors'=>$guarantors,'others'=>$others));
                                break;
                        }
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate(){
        switch(Navigation::checkIfAuthorized(30)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to create loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model = new Loanaccounts;
                if(isset($_POST['Loanaccounts'])){
                    $userID = $_POST['Loanaccounts']['user_id'];
                    switch(LoanApplication::restrictMultipleRunningAccounts($userID)){
                        case 0:
                            $amountApplied=$_POST['Loanaccounts']['amount_applied'];
                            $insuranceRate=$_POST['Loanaccounts']['insuranceRate'];
                            $processingRate=$_POST['Loanaccounts']['processingRate'];
                            $insuranceAmount=($insuranceRate * $amountApplied)/100;
                            $processingAmount=($processingRate * $amountApplied)/100;
                            $deductions=$insuranceAmount + $processingAmount;
                            $receivableAmount=$amountApplied-$deductions;

                            $insuranceRateValuee=$_POST['Loanaccounts']['insuranceRate'];
                            $processingRateValuee=$_POST['Loanaccounts']['processingRate'];

                            $data = array();
                            $data['user']             = $_POST['Loanaccounts']['user_id'];
                            $data['amount']           = $_POST['Loanaccounts']['amount_applied'];
                            $data['receivable_amount'] = $receivableAmount;
                            $data['insurance_fee'] = $insuranceAmount;
                            $data['processing_fee'] = $processingAmount;
                            $data['deduction_fee'] = $deductions;

                            $data['insurance_fee_value'] = $insuranceRateValuee;
                            $data['processing_fee_value'] = $processingRateValuee;

                            $data['freezing_period']      = $_POST['freezing_period'];
                            
                            
                            $data['direct_to']        = $_POST['Loanaccounts']['direct_to'];
                            $data['special_comment']  = $_POST['Loanaccounts']['special_comment'];
                            $data['filesPath']        = !empty($_FILES['path']) ? $_FILES['path'] : "";
                            switch(LoanApplication::createNewApplication($data)){
                                case 0:
                                    CommonFunctions::setFlashMessage('danger',"Operation failed! An error occurred while submitting the application. Kindly check your input.");
                                    $this->redirect(array('create'));
                                    break;

                                case 1:
                                    CommonFunctions::setFlashMessage('success',"Loan Application submitted successfully.");
                                    $this->redirect(array('admin'));
                                    break;

                                case 2:
                                    CommonFunctions::setFlashMessage('danger',"Operation failed! The amount applied exceeds the member allowed limit.");
                                    $this->redirect(array('create'));
                                    break;

                                case 3:
                                    CommonFunctions::setFlashMessage('danger',"Operation failed! The member is not active.");
                                    $this->redirect(array('create'));
                                    break;
                            }
                            break;

                        case 1:
                            CommonFunctions::setFlashMessage('danger',"Operation Failure. The user has a running loan account.");
                            $this->redirect(Yii::app()->request->urlReferrer);
                            break;
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
        $account     = $this->loadModel($id);
        $loanStatus  = $account->loan_status;
        $arrayStatus = array('3');
        switch(CommonFunctions::searchElementInArray($loanStatus,$arrayStatus)){
            case 0:
                $element       =  Yii::app()->user->user_level;
                $array         =  array('1','2','3','4','5');
                $model         =  $this->loadModel($id);
                $accountHolder =  $model->BorrowerFullName;
                switch(CommonFunctions::searchElementInArray($element,$array)){
                    case 0:
                        switch(Navigation::checkIfAuthorized(31)){
                            case 0:
                                CommonFunctions::setFlashMessage('danger',"Not Authorized to update loan account.");
                                $this->redirect(array('dashboard/default'));
                                break;

                            case 1:
                                $model            = $this->loadModel($id);
                                $profile          = Profiles::model()->findByPk($model->user_id);
                                $defaultLimit     = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
                                $maxLimit         = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
                                $limitFormatted   = CommonFunctions::asMoney($maxLimit);
                                $interestRate     = $model->interest_rate;
                                $repaymentPeriods = $model->repayment_period;
                                $amountApproved   = $model->amount_applied;
                                if(isset($_POST['Loanaccounts'])){
                                    $bad_symbols = array(",");
                                    $amountValue = str_replace($bad_symbols, "",$_POST['Loanaccounts']['amount_applied']);
                                    if($maxLimit < $amountValue){
                                        CommonFunctions::setFlashMessage('danger',"Application cannot be updated since the amount provided exceeds the client loan limit of KES $limitFormatted /=");
                                        $this->redirect(array('loanaccounts/update/'.$id));
                                    }else{
                                        $model->attributes     = $_POST['Loanaccounts'];
                                        $model->penalty_amount = $_POST['Loanaccounts']['penalty_amount'];
                                        $model->date_approved  = $_POST['Loanaccounts']['date_approved'];
                                        $model->created_at     = $_POST['Loanaccounts']['created_at'];
                                        if($model->save()){
                                            $amountAfter = $model->amount_applied;
                                            $rateAfter   = $model->interest_rate;
                                            $periodAfter = $model->repayment_period;
                                            if($amountAfter != $amountApproved || $rateAfter != $interestRate || $periodAfter != $repaymentPeriods){
                                                LoanManager::restructureLoanAccount($model,$id,$interestRate,$amountApproved,$repaymentPeriods);
                                            }
                                            Logger::logUserActivity("Update Loan Account for $accountHolder",'high');
                                            CommonFunctions::setFlashMessage('success',"Loan successfully updated.");
                                            $this->redirect(array('admin'));
                                        }
                                    }
                                }
                                $this->render('update',array('model'=>$model));
                                break;
                        }
                        break;

                    case 1:
                        CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                        $this->redirect(array('dashboard/default'));
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Account cannot be updated since it was either rejected or fully paid.");
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
        $accountNumber=$model->account_number;
        $fullName=$model->BorrowerFullName;
        switch(Navigation::checkIfAuthorized(33)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to delete loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                Yii::app()->db->createCommand("DELETE FROM penaltyaccrued WHERE loanaccount_id=$id")->execute();
                Yii::app()->db->createCommand("DELETE FROM loan_maturities WHERE loanaccount_id=$id")->execute();
                Yii::app()->db->createCommand("DELETE FROM loancomments WHERE loanaccount_id=$id")->execute();
                LoanManager::voidAccruedInterest($id);
                LoanManager::voidCurrentPenaltyRecords($id);
                $this->loadModel($id)->delete();
                Logger::logUserActivity("Deleted Loan Account: $accountNumber for $fullName",'urgent');
                CommonFunctions::setFlashMessage('success',"Loan account successfully deleted.");
                $this->redirect(array('admin'));
                break;
        }
    }
    /**
     * Manages all models.
     */
    public function actionAdmin(){
        $element=Yii::app()->user->user_level;
        $array=array('4');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $model=new Loanaccounts('search');
                $model->unsetAttributes();
                if(isset($_GET['Loanaccounts'])){
                    $model->attributes=$_GET['Loanaccounts'];
                }
                $this->render('admin',array('model'=>$model));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionDailyAccountReport(){
        switch(Navigation::checkIfAuthorized(178)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"You are not allowed to download daily accounts report.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model = new Loanaccounts('searchDisbursed');
                $model->unsetAttributes();  // clear any default values
                if(isset($_GET['Loanaccounts'])){
                    $model->attributes = $_GET['Loanaccounts'];
                    if(isset($_GET['export'])){
                        Logger::logUserActivity("Downloaded Loan Account Daily Report",'normal');
                        $dataProvider = $model->searchDisbursed();
                        $dataProvider->pagination = False;
                        ExportFunctions::getDailyDownloadableReport($dataProvider->data,date("Y-m-d"));
                    }
                }
                $this->render('dailyAccountReport',array('model'=>$model));
                break;
        }
    }

    public function actionProfitAndLoss(){
        $element=Yii::app()->user->user_level;
        $array=array('4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $model=new Loanaccounts('search');
                $model->unsetAttributes();  // clear any default values
                if(isset($_GET['Loanaccounts'])){
                    $model->attributes=$_GET['Loanaccounts'];
                    if(isset($_GET['export'])){
                        $dataProvider = $model->search();
                        $dataProvider->pagination = False;
                        $excelWriter = ExportFunctions::getExcelAccountsProfitAndLoss($dataProvider->data);
                        echo $excelWriter->save('php://output');
                    }
                }
                $this->render('profitLoss',array('model'=>$model));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionRepo(){
        $element=Yii::app()->user->user_level;
        $array=array('4');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $branches=Reports::getAllBranches();
                $this->render('repo',array('branches'=>$branches));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionLoadFilteredApplications(){
        $element=Yii::app()->user->user_level;
        $array=array('4');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $start_date=$_POST['start_date'];
                $end_date=$_POST['end_date'];
                $branch=(int)$_POST['branch'];
                $rm=(int)$_POST['staff'];
                $clientID=(int)$_POST['borrower'];
                $status=$_POST['status'];
                $loanaccounts=LoanApplication::LoadFilteredLoanApplications($branch,$rm,$clientID,$start_date,$end_date,$status);
                if(!empty($loanaccounts)){
                    $loanaccounts_array=[];
                    $counter=0;
                    foreach($loanaccounts as $account){
                        $loanaccounts_array[$counter]['LoanMemberName']=$account->getBorrowerFullName();
                        $loanaccounts_array[$counter]['LoanMemberBranch']=$account->getBorrowerBranchName();
                        $loanaccounts_array[$counter]['LoanAccountNumber']=$account->account_number;
                        $loanaccounts_array[$counter]['LoanAmountDisbursed']=$account->getExactAmountDisbursed();
                        $loanaccounts_array[$counter]['LoanInterestRate']=$account->getInterestRate();
                        $loanaccounts_array[$counter]['LoanAccruedInterest']=$account->getAccruedInterest();
                        $loanaccounts_array[$counter]['LoanBalance']=$account->getCurrentLoanBalance();
                        $loanaccounts_array[$counter]['LoanStatus']=$account->getCurrentLoanAccountStatus();
                        $loanaccounts_array[$counter]['LoanActions']=$account->getAction();
                        $counter++;
                    }
                    echo json_encode($loanaccounts_array);
                }else{
                    echo "NOT FOUND";
                }
                break;

            case 1:
                echo "NOT FOUND";
                break;
        }
    }

    public function actionApprove($id){
        switch(Navigation::checkIfAuthorized(34)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to approve loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model=$this->loadModel($id);
                $this->render('approve',array('model'=>$model));
                break;
        }
    }

    public function actionReject($id){
        switch(Navigation::checkIfAuthorized(35)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to reject loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model=$this->loadModel($id);
                $this->render('reject',array('model'=>$model));
                break;
        }
    }

    public function actionDisburse($id){
        $element    = Yii::app()->user->user_level;
        $notAllowed = array('0','3','4','5','8','9','10');
        $array      = array('3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                switch(Navigation::checkIfAuthorized(36)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Not Authorized to disburse loan account.");
                        $this->redirect(array('dashboard/default'));
                        break;

                    case 1:
                        $model  = $this->loadModel($id);
                        if(CommonFunctions::searchElementInArray($model->loan_status,$notAllowed) === 0){
                            $profile = Profiles::model()->findByPk($model->user_id);
                            $others  = LoanManager::getOtherProfileLoanAccounts($profile->id,$id);
                            $loanfiles      = LoanApplication::getLoanAccountFiles($id);
                            $comments       = LoanApplication::getLoanComments($id);
                            $savingAccounts = SavingFunctions::getAllUserSavingAccounts($model->user_id);
                            if($savingAccounts ===0){
                                $accountID=0;
                            }else{
                                foreach($savingAccounts as $account) {
                                    $accountID=$account->savingaccount_id;
                                }
                            }
                            $transactions = SavingFunctions::getAllSavingAccountTransactions($accountID);
                            $this->render('disburse',array('model'=>$model,'files'=>$loanfiles,'comments'=>$comments,
                                'transactions'=>$transactions,'others'=>$others));
                        }else{
                            CommonFunctions::setFlashMessage('danger',"The loan account cannot be disbursed since it has not been approved, 
					has been rejected, has been fully paid or still on the initial application stage.");
                            $this->redirect(array('admin'));
                        }
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Unauthorized access.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionCommitApproval(){
        switch(Navigation::checkIfAuthorized(34)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to aprove loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccount_id = $_POST['loanaccount_id'];
                $model          = Loanaccounts::model()->findByPk($loanaccount_id);
                $profile        = Profiles::model()->findByPk($model->user_id);
                $defaultLimit   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
                $maxLimit       = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
                $limitFormatted = CommonFunctions::asMoney($maxLimit);

                //escape % character in $insurance rate
                $insuranceRate=floatval(str_replace('%', '',$_POST["insurance_rate_value"]));
                $processingRate=floatval(str_replace('%', '',$_POST["processing_rate_value"]));

                $amount         = $_POST['amount_applied'];

                $insuranceAmount=($insuranceRate * $amount)/100;
                $processingAmount=($processingRate * $amount)/100;
                $deductions=$insuranceAmount + $processingAmount;
                $receivableAmount=$amount-$deductions;

                $finalApprovedAmount         = $receivableAmount;
                $repayment_period=$_POST['repayment_period'];
                $repayment_start_date=$_POST['repayment_start_date'];
                $penalty_amount=$_POST['penalty_amount'];
                $reason=$_POST['reason'];
                $pay_frequency = $_POST['pay_frequency'];
                if($maxLimit < $finalApprovedAmount){
                    CommonFunctions::setFlashMessage('danger',"Application cannot be approved since the amount provided exceeds the client loan limit of KES $limitFormatted /=");
                    $this->redirect(array('loanaccounts/viewDetails/'.$loanaccount_id));
                }else{
                    $authStatus = array('0','10');
                    if(CommonFunctions::searchElementInArray($model->loan_status,$authStatus) === 1){
                        switch(LoanApplication::approveLoanAccount($loanaccount_id,$amount,$repayment_period,$repayment_start_date,$penalty_amount,$reason,$insuranceAmount,$processingAmount,$deductions,$finalApprovedAmount,$pay_frequency)){
                            case 0:
                                $type='danger';
                                $message="Application not approved. Please try again.";
                                break;

                            case 1:
                                $type='success';
                                $message="Application successfully approved.";
                                break;
                        }
                        CommonFunctions::setFlashMessage($type,$message);
                    }else{
                        CommonFunctions::setFlashMessage('danger',"Application cannot be approved since it has either been approved or rejected");
                    }
                    $this->redirect(array('admin'));
                }
                break;
        }
    }

    public function actionCommitRejection(){
        switch(Navigation::checkIfAuthorized(35)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to reject loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccount_id= $_POST['loanaccount_id'];
                $model         = Loanaccounts::model()->findByPk($loanaccount_id);
                $reason        = $_POST['reason'];
                $authStatus    = array('0','10');
                if(CommonFunctions::searchElementInArray($model->loan_status,$authStatus) === 1){
                    switch(LoanApplication::rejectLoanApplication($loanaccount_id,$reason)){
                        case 0:
                            $type='danger';
                            $message="Application not rejected. Please try again.";
                            break;

                        case 1:
                            $type='success';
                            $message="Application successfully rejected.";
                            break;
                    }
                    CommonFunctions::setFlashMessage($type,$message);
                }else{
                    CommonFunctions::setFlashMessage('danger',"Application cannot be rejected since it has either been approved or rejected.");
                }
                $this->redirect(array('admin'));
                break;
        }
    }

    public function actionCommitDisbursement(){
        switch(Navigation::checkIfAuthorized(36)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to disburse loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccount_id = $_POST['loanaccount_id'];
                $loanaccount    = LoanApplication::getLoanAccount($loanaccount_id);
                $profile        = Profiles::model()->findByPk($loanaccount->user_id);
                $defaultLimit   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
                $maxLimit       = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
                $limitFormatted = CommonFunctions::asMoney($maxLimit);
                $phoneNumber= '254'.substr(ProfileEngine::getProfileContactByType($loanaccount->user_id,'PHONE'),-9);
                $clientName = $profile->ProfileFullName;
                //$amountFormatted = CommonFunctions::asMoney($amount);
                $amount     = $_POST['amount_receivable']; //Go to system
                $amountSentToClient     = $_POST['amount_approved'];   //mpesa
                //var_dump($amountSentToClient);exit;
                //$amountFormatted = CommonFunctions::asMoney($amount);
                $amountFormatted = CommonFunctions::asMoney($amountSentToClient);
                $reason      = $_POST['reason'];
                $message     = "B2C M-PESA Transaction of $amountFormatted for Phone Number: $phoneNumber ";
                $bad_symbols = array(",");
                $amountValue = str_replace($bad_symbols,"",$amount);
                if($maxLimit < $amountValue){
                    CommonFunctions::setFlashMessage('danger',"Application cannot be disbursed since the amount provided exceeds
					 the client loan limit of KES $limitFormatted /=");
                }else{
                    if($loanaccount->loan_status === '1'){
                        //$status=LoanApplication::disburseLoanApplication($loanaccount_id,$amount,$reason);
                        $status=LoanApplication::disburseLoanApplication($loanaccount_id,$amount,$reason,$amountSentToClient,$loanaccount["insurance_fee"],$loanaccount["processing_fee"]);

                        switch($status){
                            case 0:
                                $type='danger';
                                $message.=" failed. Failed to disburse the loan account. Please try again.";
                                break;

                            case 1:
                                $type='success';
                                $message.=" processed successfully. Loan account disbursed successfully.";
                                break;

                            case 2:
                                $type='danger';
                                $message.=" failed. The loan has already been disbursed.";
                                break;

                            case 3:
                                $type='danger';
                                $message.=" failed. No response received from the M-PESA system.";
                                break;

                            case 4:
                                $type='danger';
                                $message = "failed. M-pesa settings incomplete.";
                                break;

                            case 1250:
                                $type='danger';
                                $message.=" failed. Error occurred while generating the auth token. Please try again later.";
                                break;

                            case 2020:
                                $type = 'success';
                                $message ="Loan of $amountFormatted, has been  processed and disbursed to $clientName successfully..";
                                break;

                            default:
                                $type='danger';
                                $message.=" failed. ".$status;
                                break;
                        }
                        CommonFunctions::setFlashMessage($type,$message);
                    }else{
                        CommonFunctions::setFlashMessage('danger',"Application cannot be disbursed since the application has not yet been approved.");
                    }
                }
                $this->redirect(array('admin'));
                break;
        }
    }

    public function actionRepay($id){
        switch(Navigation::checkIfAuthorized(37)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to submit manual repayment to loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model=$this->loadModel($id);
                $loan_cleared=LoanRepayment::checkIfLoanHasBeenCleared($id);
                switch($loan_cleared){
                    case 0:
                        $statusArray   = array('0','1','3','4','8','9','10');
                        $statusElement = $model->loan_status;
                        if(CommonFunctions::searchElementInArray($statusElement,$statusArray) == 1){
                            CommonFunctions::setFlashMessage('danger',"Repayment cannot be submitted. The account cannot receive payments.");
                            $this->redirect(array('loanaccounts/'.$id));
                        }else{
                            $this->render('repay',array('model'=>$model));
                        }
                        break;

                    case 1:
                        CommonFunctions::setFlashMessage('danger',"Repayment cannot be submitted. Loan fully settled.");
                        $this->redirect(array('loanaccounts/'.$id));
                        break;
                }
                break;
        }
    }

    public function actionCommitRepayment(){
        switch(Navigation::checkIfAuthorized(37)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to create loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccount_id= $_POST['loanaccount_id'];
                $model         = $this->loadModel($loanaccount_id);
                $profile       = Profiles::model()->findByPk($model->user_id);
                $loan_cleared  = LoanRepayment::checkIfLoanHasBeenCleared($loanaccount_id);
                if($loan_cleared === 0){
                    $amount   = $_POST['repayment_amount'];
                    switch(LoanManager::repayLoanAccount($loanaccount_id,$amount,'0',$profile->ProfilePhoneNumber)){
                        case 0:
                            CommonFunctions::setFlashMessage('danger',"Repayment not submitted. Please try again.");
                            $this->redirect(array('loanaccounts/repay/'.$loanaccount_id));
                            break;

                        case 1:
                            CommonFunctions::setFlashMessage('success',"Repayment successfully submitted.");
                            $this->redirect(array('loanaccounts/'.$loanaccount_id));
                            break;
                    }
                }else{
                    CommonFunctions::setFlashMessage('danger',"Repayment not submitted. Loan fully settled.");
                    $this->redirect(array('loanaccounts/'.$loanaccount_id));
                }
                break;
        }
    }

    public function actionCollateral($id){
        $element=Yii::app()->user->user_level;
        $array=array('4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $model=Loanaccounts::model()->findByPk($id);
                $this->render('collateral',array('model'=>$model));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionCommitCollateral(){
        $element=Yii::app()->user->user_level;
        $array=array('4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $_POST['photo']=CUploadedFile::getInstanceByName('photo');
                $status        =CollateralFunctions::createCollateral($_POST);
                switch($status){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Collateral not submitted.");
                        $this->redirect(array('loanaccounts/collateral/'.$_POST['loanaccount_id']));
                        break;

                    case 1:
                        CommonFunctions::setFlashMessage('success',"Collateral successfully submitted.");
                        $this->redirect(array('loanaccounts/'.$_POST['loanaccount_id']));
                        break;

                    case 2:
                        CommonFunctions::setFlashMessage('danger',"Collateral not submitted. Duplicate collateral.");
                        $this->redirect(array('loanaccounts/collateral/'.$_POST['loanaccount_id']));
                        break;
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionComment($id){
        switch(Navigation::checkIfAuthorized(258)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to create loan comment.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model = Loanaccounts::model()->findByPk($id);
                $types = LoanApplication::getAllCommentTypes();
                $this->render('comment',array('model'=>$model,'types'=>$types));
                break;
        }
    }

    public function actionCommitComment(){
        switch(Navigation::checkIfAuthorized(258)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to create loan comment.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                if(isset($_POST['loan_comment_cmd'])){
                    $_POST['commented_by'] = Yii::app()->user->user_id;
                    $_POST['activity']     = "Normal Comment: ".$_POST['comment'];
                    $commentStatus         = LoanApplication::recordLoanComment($_POST);
                    switch($commentStatus){
                        case 0:
                            CommonFunctions::setFlashMessage('danger',"Comment not submitted. Try again.");
                            $this->redirect(array('loanaccounts/comment/'.$_POST['loanaccount_id']));
                            break;

                        case 1:
                            CommonFunctions::setFlashMessage('success',"Comment successfully submitted.");
                            $this->redirect(array('loanaccounts/'.$_POST['loanaccount_id']));
                            break;
                    }
                }
                break;
        }
    }

    public function actionDue(){
        $element=Yii::app()->user->user_level;
        $array=array('4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $this->render('due',array('branches'=>Reports::getAllBranches()));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionLoadDueLoans(){
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $month=$_POST['month'];
        $year=$_POST['year'];
        $branch=$_POST['branch'];
        $rm=$_POST['rm'];
        $status=$_POST['status'];
        echo LoanApplication::LoadFilteredDueLoansReport($start_date,$end_date,(int)$month,(int)$year,(int)$branch,(int)$rm,$status);
    }

    public function actionNoRepayment(){
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $loanaccounts=LoanApplication::getLoansWithoutRepayments();
                $this->render('noRepayment',array('loanaccounts'=>$loanaccounts));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionPrincipalOutstanding(){
        $element=Yii::app()->user->user_level;
        $array=array('2','4');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $userID=Yii::app()->user->user_id;
                if(Yii::app()->user->user_level !== '3'){
                    $loanSql="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3')";
                }else{
                    $loanSql="SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status NOT IN('0','1','3')";
                }
                $loanaccounts=Loanaccounts::model()->findAllBySql($loanSql);
                $this->render('principalOutstanding',array('loanaccounts'=>$loanaccounts));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionCalculator(){
        switch(Navigation::checkIfAuthorized(301)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to access loan calculator.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $this->render('calculator');
                break;
        }
    }

    public function actionLoadInterest(){
        $loanproduct_id=$_POST['loanproduct_id'];
        $loanproduct=Loanproduct::model()->findByPk($loanproduct_id);
        if(!empty($loanproduct)){
            $interestRate=$loanproduct->interest_rate;
            echo $interestRate;
        }else{
            echo "NO PRODUCT";
        }
    }

    public function actionLoadSchedule(){
        $interest_rate=$_POST['interest_rate'];
        $period=$_POST['period'];
        $amount_applied=$_POST['amount_applied'];
        $schedule=LoanCalculator::getLoanCalculatorSchedule($interest_rate,$period,$amount_applied);
        echo $schedule;
    }

    public function actionPastMaturity(){
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $loanaccounts=LoanApplication::getLoansPastMaturityDate();
                $this->render('pastMaturity',array('loanaccounts'=>$loanaccounts));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionMissedRepayments(){
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $branches=Reports::getAllBranches();
                $loanaccounts=LoanApplication::getLoansWithoutRepaymentsDate();
                $this->render('missedRepayments',array('loanaccounts'=>$loanaccounts,'branches'=>$branches));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionLoadFilteredMissedRepayments(){
        $branch=$_POST['branch'];
        $startDate=$_POST['start_date'];
        $endDate=$_POST['end_date'];
        $staff=$_POST['staff'];
        $borrower=$_POST['borrower'];
        echo LoanApplication::LoadFilteredMissedRepayments((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower);
    }

    public function actionDailyCollection(){
        $element = Yii::app()->user->user_level;
        $array   = array('2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $this->render('dailyCollection',array('users'=>ProfileEngine::getProfilesByType('ALL')));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionLoadDailyCollectionSheet(){
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $created_by=$_POST['user_id'];
        $repayments=LoanRepayment::getDailyCollectionSheet($start_date,$end_date,$created_by);
        if(!empty($repayments)){
            $downLoadLink=ExportFunctions::exportCollectionSheetsAsPdf($repayments,$start_date,$end_date);
            echo $downLoadLink;
        }else{
            echo "NOT FOUND";
        }
    }

    public function actionCollectionPastMaturity(){
        $element=Yii::app()->user->user_level;
        $array=array('4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $this->render('collectionPastMaturity',array('users'=>ProfileEngine::getProfilesByType('ALL')));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionLoadCollectionsPastMaturityDate(){
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $created_by=$_POST['user_id'];
        $repayments=LoanRepayment::getLoansPastMaturityCollectionSheet($start_date,$end_date,$created_by);
        if(!empty($repayments)){
            $downLoadLink=ExportFunctions::exportCollectionSheetsAsPdf($repayments,$start_date,$end_date);
            echo $downLoadLink;
        }else{
            echo "NOT FOUND";
        }
    }

    public function actionMakeFile($id){
        $loanaccount=$this->loadModel($id);
        switch(Navigation::checkIfAuthorized(38)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to upload loan account file.");
                $this->redirect(Yii::app()->request->urlReferrer);
                break;

            case 1:
                if(isset($_POST['upload_file_cmd'])){
                    $accountAction = $_POST['accountAction'];
                    $documentURL   = Yii::app()->params['loanDocs'];
                    $userProvidedName=$_POST['loan_file_name'];
                    $fileDisplayName=basename($_FILES["loan_file"]["name"]);
                    $fileDisplaySize=$_FILES["loan_file"]["size"];
                    $fileTempName=$_FILES["loan_file"]["tmp_name"];
                    $uploadStatus=CommonFunctions::saveUploadedonDirectory($fileTempName,$fileDisplayName,$fileDisplaySize,$documentURL);
                    switch($uploadStatus){
                        case 0:
                            $flashCategory='danger';
                            $flashMessage='Operation failed. Please upload (pdf,png,jpg,jpeg,docx,doc) files and try again.';
                            break;

                        case 2:
                            $flashCategory='danger';
                            $flashMessage='Operation failed. Uploaded file is too large.';
                            break;

                        case 3:
                            $flashCategory='danger';
                            $flashMessage='Operation failed. Failed to upload file.';
                            break;

                        default:
                            $uploadedFileName=$uploadStatus;
                            $model=new LoanFiles;
                            $model->loanaccount_id=$id;
                            $model->name=$userProvidedName;
                            $model->filename=$uploadedFileName;
                            $model->created_by=Yii::app()->user->user_id;
                            if($model->save()){
                                $accountNumber=$loanaccount->account_number;
                                $fullName=$loanaccount->BorrowerFullName;
                                Logger::logUserActivity("Uploaded Loan File: <strong>$uploadedFileName</strong> with name: <strong>$userProvidedName</strong> of size <strong>$fileDisplaySize</strong> bytes, Loan Account: <strong>$accountNumber</strong> for Account Holder: <strong>$fullName</strong>",'normal');
                                $flashCategory='success';
                                $flashMessage='File uploaded successfully.';
                            }else{
                                unlink($documentURL.'/'.$uploadedFileName);
                                $flashCategory='danger';
                                $flashMessage='Operation failed. Uploading file failed.';
                            }
                            break;
                    }
                    switch($accountAction){
                        case 'submittedAccount':
                            $redirectPage=array('loanaccounts/viewDetails/'.$id);
                            break;

                        case 'approvedAccount':
                            $redirectPage=array('loanaccounts/disburse/'.$id);
                            break;

                        case 'disbursedAccount':
                            $redirectPage=array('loanaccounts/'.$id);
                            break;

                        case 'toppedUpAccount':
                            $redirectPage=array('loanaccounts/viewTopup/'.$id);
                            break;
                    }
                    CommonFunctions::setFlashMessage($flashCategory,$flashMessage);
                    $this->redirect($redirectPage);
                }
                $this->render('makeFile',array('loan'=>$loanaccount));
                break;
        }
    }

    public function actionUpload(){
        $element=Yii::app()->user->user_level;
        $array=array('0','1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $model=new Imports;
                $this->render('upload',array('model'=>$model));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionImportHeavyData(){
        $element=Yii::app()->user->user_level;
        $array=array('0','1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $userID=Yii::app()->user->user_id;
                $generatePassword=CommonFunctions::generateRandomString();
                $uploadedFile=CUploadedFile::getInstanceByName('filename');
                if(empty($uploadedFile) || $uploadedFile === ''){
                    //Redirect With Error Message
                    $type='danger';
                    $message="No CSV File Uploaded. Please upload a CSV file in order to upload loan details.";
                    CommonFunctions::setFlashMessage($type,$message);
                    $this->redirect(array('upload'));
                }else{
                    $generateHash=CommonFunctions::generateRandomString();
                    $import=new Imports;
                    $import->filename=$uploadedFile;
                    $import->integrity_hash=password_hash($generateHash,PASSWORD_DEFAULT);
                    $import->imported_by=Yii::app()->user->user_id;
                    $import->save();
                    if($import->save()){
                        $import->filename->saveAs(Yii::app()->basePath."/../docs/csvs/loans/".$import->filename);
                        try{
                            $transaction = Yii::app()->db->beginTransaction();
                            $handle = fopen(Yii::app()->basePath."/../docs/csvs/loans/".$import->filename, "r");
                            $row = 1;
                            while (($data = fgetcsv($handle, 1500, ",")) !== FALSE) {
                                if($row > 1){
                                    $firstName=$data[0];
                                    $lastName=$data[1];
                                    $branchName=$data[9];
                                    $rate=rtrim($data[2],"%");
                                    $userID=Yii::app()->user->user_id;
                                    $branch=Branch::model()->find('name=:a',array(':a'=>$branchName));
                                    if($branch == TRUE){
                                        $user=new Users;
                                        $user->branch_id=$branch->branch_id;
                                        $user->first_name=$firstName;
                                        $user->last_name=$lastName;
                                        $user->username=$data[7];
                                        $user->email=$data[7];
                                        $user->password=password_hash($generatePassword,PASSWORD_DEFAULT);
                                        $user->created_by=$userID;
                                        $user->save();
                                        $borrower=new Borrower;
                                        $borrower->user_id=$user->user_id;
                                        $borrower->first_name=$firstName;
                                        $borrower->last_name=$lastName;
                                        $borrower->phone=$data[7];
                                        $borrower->id_number=$data[7];
                                        $borrower->email=$data[7];
                                        $borrower->birth_date="1998-01-01";
                                        $borrower->employer=$data[8];
                                        $borrower->date_employed="2010-01-01";
                                        $borrower->address="P.O. BOX 2828-00100";
                                        $borrower->city=$data[9];
                                        $borrower->branch_id=$branch->branch_id;
                                        $borrower->created_by=$userID;
                                        $borrower->save();
                                        $loanproduct=Loanproduct::model()->find('interest_rate=:a',array(':a'=>$rate));
                                        if($loanproduct == TRUE){
                                            $staffName=$data[6];
                                            $staff=Staff::model()->find('first_name=:a OR last_name=:a',array(':a'=>$staffName));
                                            if($staff == TRUE){
                                                $account=new Loanaccounts;
                                                $account->loanaccount_id=$loanproduct->loanproduct_id;
                                                $account->user_id=$borrower->user_id;
                                                $account->account_number=LoanApplication::getLoanAccountNumber($borrower->user_id,$loanproduct->loanproduct_id);
                                                $account->amount_applied=$data[4];
                                                $account->loan_status='2';
                                                $account->amount_approved=$data[4];
                                                $account->date_approved=date('Y-m-d',strtotime($data[10]));
                                                $account->approved_by=1;
                                                $account->repayment_period=$data[3];
                                                $account->rm=$staff->user_id;
                                                $account->repayment_start_date=date('Y-m-d',strtotime($data[10]));
                                                $account->created_by=$userID;
                                                if($account->save()){
                                                    $disburse=new DisbursedLoans;
                                                    $disburse->loanaccount_id=$account->loanaccount_id;
                                                    $disburse->amount_disbursed=$data[4];
                                                    $disburse->disbursed_by=$userID;
                                                    if($disburse->save()){
                                                        $penalty=new Penaltyaccrued;
                                                        $penalty->loanaccount_id=$disburse->loanaccount_id;
                                                        $penalty->date_defaulted=date('Y-m-d',strtotime($data[10]));
                                                        $penalty->penalty_amount=$data[5];
                                                        $penalty->save();
                                                    }
                                                }
                                            }
                                        }


                                    }
                                }
                                $row++;
                            }
                            $transaction->commit();
                        }catch(Exception $error){
                            $transaction->rollback();
                            $type='danger';
                            $message="There was an error uploading the file: $error";
                            CommonFunctions::setFlashMessage($type,$message);
                            $this->redirect(array('upload'));
                        }
                        //Redirect With success Message
                        $activity="Uploaded Loans CSV file ";
                        $severity='normal';
                        Logger::logUserActivity($activity,$severity);
                        $type='success';
                        $message="Loan details successfully uploaded.";
                        CommonFunctions::setFlashMessage($type,$message);
                        $this->redirect(array('upload'));
                    }else{
                        //Redirect With Error Message
                        $type='danger';
                        $message="Uploading loan details Failed. Kindly ensure you have uploaded a CSV file";
                        CommonFunctions::setFlashMessage($type,$message);
                        $this->redirect(array('upload'));
                    }
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionReturn($id){
        switch(Navigation::checkIfAuthorized(39)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to return loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model=$this->loadModel($id);
                $users=$model->getForwadingList();
                if(isset($_POST['return_loan_cmd'])){
                    $redirect= new LoanRedirects;
                    $redirect->loanaccount_id=$model->loanaccount_id;
                    $redirect->comment=$_POST['comment'];
                    $redirect->redirected_by=Yii::app()->user->user_id;
                    if($redirect->save()){
                        $model->loan_status ='9';
                        $model->save();
                        $data['loanaccount_id']=$redirect->loanaccount_id;
                        $data['comment']=$redirect->comment;
                        $data['activity']="Returning Loan Account";
                        $data['commented_by']=Yii::app()->user->user_id;
                        LoanApplication::recordLoanComment($data);
                        $profile=Profiles::model()->findByPk($model->created_by);
                        $emailAddress = ProfileEngine::getProfileContactByTypeOrderDesc($model->created_by,'EMAIL');
                        $accountNumber=$model->account_number;
                        $fullName=$profile->ProfileFullName;
                        Logger::logUserActivity("Returned Loan Account: $accountNumber for $fullName",'high');
                        /*Send Notification*/
                        $user_full_name=$profile->lastName;
                        $name = 'Loan Service Desk';
                        $subject    =   'Loan Application Redirected';
                        $body       =   "<p>Greetings from Treasure Capital Limited.</p>
							<p>A loan application has been successfully redirected back to you.</p>
							<p>You are hereby reminded to login and approve or resubmit the loan.
							</p>
							<p>Thank you member for trusting and doing business with us.</p>";
                        $message = Mailer::Build($name,$subject,$body, $user_full_name);
                        $emailStatus=CommonFunctions::broadcastEmailNotification($emailAddress,$subject,$message);
                        $type='success';
                        $message="Application returned successfully";
                        CommonFunctions::setFlashMessage($type,$message);
                        $this->redirect(array('loanaccounts/viewDetails/'.$id));
                    }else{
                        $type='danger';
                        $message="Application not returned.";
                        CommonFunctions::setFlashMessage($type,$message);
                        $this->redirect(array('loanaccounts/viewDetails/'.$id));
                    }
                }
                $this->render('return',array('model'=>$model,'users'=>$users));
                break;
        }
    }

    public function actionForward($id){
        switch(Navigation::checkIfAuthorized(40)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to forward loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model=$this->loadModel($id);
                $users=$model->getForwadingList();
                if(isset($_POST['fwd_loan_cmd'])){
                    $model->forward_to=$_POST['forwarded_to'];
                    if($model->save()){
                        $forward= new LoanForwards;
                        $forward->loanaccount_id=$model->loanaccount_id;
                        $forward->comment=$_POST['comment'];
                        $forward->forwarded_to=$_POST['forwarded_to'];
                        $forward->forwarded_by=Yii::app()->user->user_id;
                        if($forward->save()){
                            $model->loan_status ='8';
                            $model->save();
                            $data['loanaccount_id']=$forward->loanaccount_id;
                            $data['comment']=$forward->comment;
                            $data['activity']="Forwarding Loan Account";
                            $data['commented_by']=Yii::app()->user->user_id;
                            LoanApplication::recordLoanComment($data);
                            $profile=Profiles::model()->findByPk($forward->forwarded_to);
                            $emailAddress = ProfileEngine::getProfileContactByTypeOrderDesc($forward->forwarded_to,'EMAIL');
                            $accountNumber=$model->account_number;
                            $fullName=$profile->ProfileFullName;
                            Logger::logUserActivity("Forwarded Loan Account: $accountNumber for $fullName",'high');
                            /*Send Notification*/
                            $user_full_name=$profile->lastName;
                            $name = 'Loan Service Desk';
                            $subject    =   'Loan Application Forwarded';
                            $body       =   "<p>Greetings from Treasure Capital Limited.</p>
							<p>A loan application has been successfully forwarded to you.</p>
							<p>You are hereby reminded to login and approve or reject the application.
							<br> Kindly ensure you provide a comment for approving or rejecting the loan application.
							</p>
							<p>Thank you member for trusting and doing business with us.</p>";
                            $message = Mailer::Build($name,$subject,$body, $user_full_name);
                            $emailStatus=CommonFunctions::broadcastEmailNotification($emailAddress,$subject,$message);
                            $type='success';
                            $message="Application forwarded successfully";
                        }else{
                            $type='danger';
                            $message="Application not forwarded.";
                        }
                        CommonFunctions::setFlashMessage($type,$message);
                        $this->redirect(array('loanaccounts/viewDetails/'.$id));
                    }else{
                        CommonFunctions::setFlashMessage('danger',"Application not forwarded.");
                        $this->redirect(array('loanaccounts/viewDetails/'.$id));
                    }
                }
                $this->render('forward',array('model'=>$model,'users'=>$users));
                break;
        }
    }

    public function actionRecall($id){
        switch(Navigation::checkIfAuthorized(41)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to recall loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model=$this->loadModel($id);
                if(Yii::app()->user->user_id === $model->created_by){
                    $users=$model->getForwadingList();
                    if(isset($_POST['recall_loan_cmd'])){
                        $recall= new LoanRecalls;
                        $recall->loanaccount_id=$model->loanaccount_id;
                        $recall->comment=$_POST['comment'];
                        $recall->redirect_to=$_POST['redirect_to'];
                        $recall->recalled_by=Yii::app()->user->user_id;
                        if($recall->save()){
                            $model->loan_status ='10';
                            $model->save();
                            $data['loanaccount_id']=$recall->loanaccount_id;
                            $data['comment']=$recall->comment;
                            $data['activity']="Recalling Loan Account";
                            $data['commented_by']=Yii::app()->user->user_id;
                            LoanApplication::recordLoanComment($data);
                            $profile=Profiles::model()->findByPk($recall->redirect_to);
                            $emailAddress = ProfileEngine::getProfileContactByTypeOrderDesc($recall->redirect_to,'EMAIL');
                            $accountNumber=$model->account_number;
                            $fullName=$profile->ProfileFullName;
                            Logger::logUserActivity("Recalled Loan Account: $accountNumber for $fullName",'high');
                            /*Send Notification*/
                            $user_full_name=$profile->lastName;
                            $name = 'Loan Service Desk';
                            $subject    =   'Loan Application Recalled';
                            $body       =   "<p>Greetings from Treasure Capital Limited.</p>
							<p>A loan application has been successfully recalled and redirected to you.</p>
							<p>You are hereby reminded to login and approve or reject the application.
							<br> Kindly ensure you provide a comment for approving or rejecting the loan application.
							</p>
							<p>Thank you member for trusting and doing business with us.</p>";
                            $message = Mailer::Build($name,$subject,$body, $user_full_name);
                            $emailStatus=CommonFunctions::broadcastEmailNotification($emailAddress,$subject,$message);
                            $type='success';
                            $message="Application recalled and redirected successfully";
                        }else{
                            $type='danger';
                            $message="Application not recalled.";
                        }
                        CommonFunctions::setFlashMessage($type,$message);
                        $this->redirect(array('loanaccounts/viewDetails/'.$id));
                    }
                    $this->render('recall',array('model'=>$model,'users'=>$users));
                }else{
                    CommonFunctions::setFlashMessage('danger',"You are not allow to recall the application.");
                    $this->redirect(array('loanaccounts/viewDetails/'.$id));
                }
                break;
        }
    }

    public function actionLoadAccountNumbers(){
        $profile      = Profiles::model()->findByPk($_POST['userID']);
        $defaultLimit = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
        $loanLimit    = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
        $defaultRate  = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_INTEREST_RATE');
        $interestRate = $defaultRate === 'NOT SET' ? Yii::app()->params['DEFAULTLOANSINTEREST'] : floatval($defaultRate);

        $insurancePercentageRate = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'INSURANCE_PERCENT');
        $processingPercentageRate = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'PROCESSING_PERCENT');

        if(!empty($profile)){
            $accDetails = array();
            $accDetails['accountNumber']  = $profile->idNumber;
            $accDetails['employer']       = $profile->ProfileEmployment;
            $accDetails['savingsBalance'] = LoanApplication::getUserSavingAccountBalance($profile->id);
            $accDetails['loanLimit']      = $loanLimit;
            $accDetails['phoneNumber']    = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
            $accDetails['interestRate']   = $interestRate;

            $accDetails['insuranceRate']   = $insurancePercentageRate;
            $accDetails['processingRate']   = $processingPercentageRate;

            echo json_encode($accDetails);
        }else{
            $message='NOT FOUND';
            echo json_encode($message);
        }
    }

    public function actionLoadsavingBalances(){
        $user_id    = $_POST['userID'];
        $profile    = Profiles::model()->findByPk($user_id);
        if(!empty($profile)){
            $accountBal = LoanApplication::getUserSavingAccountBalance($profile->id);
        }else{
            $accountBal='NOT FOUND';
        }
        echo $accountBal;
    }

    public function LoadUserEmployer(){
        $user_id = $_POST['userID'];
        $profile = Profiles::model()->findByPk($user_id);
        if(!empty($profile)){
            $clientEmployer=$profile->ProfileEmployment;
        }else{
            $clientEmployer='NOT FOUND';
        }
        echo $clientEmployer;
    }

    public function actionLoadPhoneNumbers(){
        $user_id = $_POST['userID'];
        $profile = Profiles::model()->findByPk($user_id);
        if(!empty($profile)){
            $phoneNumber  =ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
            if($phoneNumber === '' || empty($phoneNumber)){
                $currentNumber='NOT FOUND';
            }else{
                $currentNumber=$phoneNumber;
            }
        }else{
            $currentNumber='NOT FOUND';
        }
        echo $currentNumber;
    }

    public function actionTopup(){
        switch(Navigation::checkIfAuthorized(42)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to top up loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $this->render('topup',array('users'=>LoanApplication::getOpenLoansUsers()));
                break;
        }
    }

    public function actionLoadTopUpDetails(){
        $loanaccount_id= $_POST['loanaccount_id'];
        $loanaccount  = Loanaccounts::model()->findByPk($loanaccount_id);
        $profile      = Profiles::model()->findByPk($loanaccount->user_id);
        $defaultLimit = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
        $maxLimit     = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
        if(!empty($loanaccount)){
            $accountNumber  =$loanaccount->account_number;
            $relationManager=$loanaccount->getRelationshipManagerName();
            $interestRate   =$loanaccount->interest_rate;
            $repaymentPeriods=$loanaccount->repayment_period;
            $loanBalance=LoanManager::getActualLoanBalance($loanaccount_id);
            $savingsBalance=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($loanaccount->user_id));
            $bad_symbols = array(",");
            $amountValue = str_replace($bad_symbols, "",$loanBalance);
            $topupDetails=array();
            $topupDetails['account_number']=$accountNumber;
            $topupDetails['rm']=$relationManager;
            $topupDetails['interest_rate']=$interestRate;
            $topupDetails['repayment_period']=$repaymentPeriods;
            $topupDetails['loan_balance']=$amountValue;
            $topupDetails['savings_balance']=$savingsBalance;
            $topupDetails['maximum_limit']=$maxLimit;
            echo json_encode($topupDetails);
        }else{
            $message='NOT FOUND';
            echo json_encode($message);
        }
    }

    public function actionLoadTopUpDisbursement(){
        $top_up_amount=$_POST['top_up_amount'];
        $loan_balance=$_POST['loan_balance'];
        $interest_rate=$_POST['interest_rate'];
        $repayment_period=$_POST['repayment_period'];
        $disbursementAmount=$top_up_amount+$loan_balance;
        $emi=LoanCalculator::getEMIAmount($disbursementAmount,$interest_rate,$repayment_period);
        if($emi > 0){
            $topupDetails=array();
            $topupDetails['disbursement_amount']=$disbursementAmount;
            $topupDetails['emi']=$emi;
            echo json_encode($topupDetails);
        }else{
            $message='NOT FOUND';
            echo json_encode($message);
        }
    }

    public function actionCommitTopup(){
        switch(Navigation::checkIfAuthorized(42)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to top up loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                if(isset($_POST['topup_loan_cmd'])){
                    $loanaccount_id=$_POST['loanaccount'];
                    $loan=Loanaccounts::model()->findByPk($loanaccount_id);
                    $profile=Profiles::model()->findByPk($loan->user_id);
                    $defaultLimit   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
                    $maxLimit       = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
                    $topupamount=$_POST['top_up_amount'];
                    $amountDisburse=$_POST['amount_to_disburse'];
                    $repaymentPeriod=$_POST['repayment_period'];
                    $interestRate=$loan->interest_rate;
                    $comment=$_POST['comment'];
                    $bad_symbols = array(",");
                    $amountValue = floatval(str_replace($bad_symbols,"",$amountDisburse));
                    if($maxLimit < $amountValue){
                        CommonFunctions::setFlashMessage('danger',"Topup requests cannot be submitted since the amount provided exceeds the client loan limit of KES $limitFormatted /=");
                    }else{
                        switch(LoanApplication::topUpLoanApplication($loanaccount_id,$topupamount,$amountDisburse,$interestRate,$repaymentPeriod,$comment)){
                            case 0:
                                CommonFunctions::setFlashMessage('danger',"An error occurred, please try again.");
                                $this->redirect(array('topup'));
                                break;

                            case 1:
                                $documentURL=Yii::app()->params['loanDocs'];
                                $file_name=CUploadedFile::getInstanceByName('filename');
                                $loan=Loanaccounts::model()->findByPk($loanaccount_id);
                                if(empty($file_name) || $file_name === ''){
                                    CommonFunctions::setFlashMessage('danger',"No file uploaded.");
                                }else{
                                    $file_existence=LoanFiles::model()->find('filename=:a',array('a'=>$file_name));
                                    if(!empty($file_existence)){
                                        CommonFunctions::setFlashMessage('danger',"Uploading File Failed. The file already exists.");
                                    }else{
                                        $model=new LoanFiles;
                                        $model->loanaccount_id=$loanaccount_id;
                                        $model->name=$file_name;
                                        $model->filename=$file_name;
                                        $model->created_by=Yii::app()->user->user_id;
                                        if($model->save()){
                                            $model->filename->saveAs($documentURL.$model->filename);
                                            $accountNumber=$loan->account_number;
                                            $fullName=$loan->BorrowerFullName;
                                            Logger::logUserActivity("Added Loan File: $accountNumber for $fullName",'normal');
                                            $type='success';
                                            $message="File successfully uploaded.";
                                        }else{
                                            $type='danger';
                                            $message="Uploading File Failed. Please ensure the file is a JPG or PNG or a PDF or a DOCX";
                                        }
                                        CommonFunctions::setFlashMessage($type,$message);
                                    }
                                }
                                $profile = Profiles::model()->findByPk($loan->direct_to);
                                $emailAddress = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'EMAIL');
                                /*Send Notification*/
                                $user_full_name=$profile->lastName;
                                $name = 'Loan Service Desk';
                                $subject=   'LOAN ACCOUNT TOP UP REQUEST';
                                $body="<p>Greetings from Treasure Capital Limited. </p>
								<p>A loan account top up request has been submitted.</p>
								<p>Please log in and either approve or reject the top up request.</p>
								<p>To approve/reject the request, please go to Loans Management Page and search by Loan Status: Topped Up to view the loan accounts which has top up requests and then proceed as deem fit.</p>
								<p>Do not hesitate to reach out if you need help.</p>";
                                $message = Mailer::Build($name,$subject,$body, $user_full_name);
                                $emailStatus=CommonFunctions::broadcastEmailNotification($emailAddress,$subject,$message);
                                CommonFunctions::setFlashMessage('success',"Top Up requested submitted successfully.");
                                $this->redirect(array('admin'));
                                break;

                            case 2:
                                CommonFunctions::setFlashMessage('danger',"This loan account has already been topped up.");
                                $this->redirect(array('topup'));
                                break;
                        }
                    }
                }
                break;
        }
    }

    public function actionViewTopup($id){
        switch(Navigation::checkIfAuthorized(43)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to view loan top up.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $topupSQL="SELECT * FROM loan_topup WHERE loanaccount_id=$id ORDER BY id DESC LIMIT 1";
                $loantopup=LoanTopup::model()->findBySql($topupSQL);
                $model=$this->loadModel($id);
                $filesSQL="SELECT * FROM loan_files WHERE loanaccount_id=$id";
                $loanfiles=LoanFiles::model()->findAllBySql($filesSQL);
                $this->render('viewTopup',array('topup'=>$loantopup,'model'=>$model,'files'=>$loanfiles));
                break;
        }
    }

    public function actionApproveTopup(){
        switch(Navigation::checkIfAuthorized(44)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to approve loan top up.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccountID=$_POST['loanaccount'];
                $model=Loanaccounts::model()->findByPk($loanaccountID);
                $topupID=$_POST['topupAccount'];
                $topup = LoanTopup::model()->findByPk($topupID);
                $disbursementAmount = $topup->disbursement_amount;
                $approvalReason=$_POST['approvalReason'];
                $profile=Profiles::model()->findByPk($model->user_id);
                $defaultLimit   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
                $maxLimit       = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
                $limitFormatted=CommonFunctions::asMoney($maxLimit);
                if($maxLimit < $disbursementAmount){
                    CommonFunctions::setFlashMessage('danger',"Top up cannot be approved since the total loan amount will exceed the client loan limit of KES $limitFormatted /=");
                    $this->redirect(array('loanaccounts/viewTopup/'.$loanaccountID));
                }else{
                    switch(LoanApplication::approveLoanTopUp($topupID,$loanaccountID,$approvalReason)){
                        case 0:
                            CommonFunctions::setFlashMessage('danger',"Top up request was not approved. Please try again or contact system admin");
                            $this->redirect(array('loanaccounts/viewTopup/'.$loanaccountID));
                            break;

                        case 1:
                            CommonFunctions::setFlashMessage('success',"Top up request approved successfully.");
                            $this->redirect(array('loanaccounts/viewTopup/'.$loanaccountID));
                            break;

                        case 2:
                            CommonFunctions::setFlashMessage('danger',"Operation failed. The top up was already either approved or rejected earlier.");
                            $this->redirect(array('admin'));
                            break;
                    }
                }
                break;
        }
    }

    public function actionDisburseTopup(){
        switch(Navigation::checkIfAuthorized(174)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to disburse loan top up.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccountID=$_POST['loanaccount'];
                $model=Loanaccounts::model()->findByPk($loanaccountID);
                $topupID=$_POST['topupAccount'];
                $topup = LoanTopup::model()->findByPk($topupID);
                $disbursementAmount = $topup->disbursement_amount;
                $disbursalReason=$_POST['disbursalReason'];
                $profile=Profiles::model()->findByPk($model->user_id);
                $defaultLimit   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
                $maxLimit       = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
                $limitFormatted=CommonFunctions::asMoney($maxLimit);
                if($maxLimit < $disbursementAmount){
                    CommonFunctions::setFlashMessage('danger',"Top up cannot be disbursed since the total loan amount will exceed the client loan limit of KES $limitFormatted /=");
                    $this->redirect(array('loanaccounts/viewTopup/'.$loanaccountID));
                }else{
                    $topupStatus=LoanApplication::disburseLoanTopUp($topupID,$loanaccountID,$disbursalReason);
                    switch($topupStatus){
                        case 0:
                            CommonFunctions::setFlashMessage('danger',"Top up request was not disbursed. Please try again or contact system admin");
                            $this->redirect(array('loanaccounts/viewTopup/'.$loanaccountID));
                            break;

                        case 1:
                            CommonFunctions::setFlashMessage('success',"Top up request disbursed successfully. M-PESA transaction processed successfully.");
                            $this->redirect(array('admin'));
                            break;

                        case 2:
                            CommonFunctions::setFlashMessage('danger',"Operation failed. The top up was already either not approved or was disbursed earlier.");
                            $this->redirect(array('admin'));
                            break;

                        case 3:
                            CommonFunctions::setFlashMessage('danger',"Operation failed. No response received from the M-PESA system.");
                            $this->redirect(array('admin'));
                            break;

                        case 1250:
                            CommonFunctions::setFlashMessage('danger',"Operation failed. Error occurred while generating the auth token. Please try again later...");
                            $this->redirect(array('admin'));
                            break;

                        case 2020:
                            CommonFunctions::setFlashMessage('danger',"Operation failed. Sorry this action cannot be completed at this time. Contact the system administrator.");
                            $this->redirect(array('admin'));
                            break;

                        default:
                            CommonFunctions::setFlashMessage('danger',"Operation failed. $topupStatus.");
                            $this->redirect(array('admin'));
                            break;
                    }
                }
                break;
        }
    }

    public function actionRejectTopup(){
        switch(Navigation::checkIfAuthorized(45)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to reject loan top up.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccountID=$_POST['loanaccount'];
                $topupID=$_POST['topupAccount'];
                $rejectionReason=$_POST['rejectionReason'];
                switch(LoanApplication::rejectLoanTopUp($topupID,$loanaccountID,$rejectionReason)){
                    case 0:
                        CommonFunctions::setFlashMessage('danger',"Top up request was not rejected. Please try again or contact system admin");
                        $this->redirect(array('loanaccounts/viewTopup/'.$loanaccountID));
                        break;

                    case 1:
                        CommonFunctions::setFlashMessage('success',"Top up request rejected successfully.");
                        $this->redirect(array('admin'));
                        break;

                    case 2:
                        CommonFunctions::setFlashMessage('danger',"Operation failed. The top up was already either approved or rejected earlier.");
                        $this->redirect(array('admin'));
                        break;
                }
                break;
        }
    }

    public function actionResubmit($id){
        $model = $this->loadModel($id);
        $returnedStatus = LoanApplication::checkIfApplicationReturned($id);
        switch(Navigation::checkIfAuthorized(46)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to resubmit loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $this->render('resubmit',array('model'=>$model,'returned'=>$returnedStatus));
                break;
        }
    }

    public function actionLoanResubmission(){
        switch(Navigation::checkIfAuthorized(46)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to resubmit loan account.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                if(isset($_POST['resubmit_loan_cmd'])){
                    $bad_symbols = array(",");
                    $amountValue = str_replace($bad_symbols, "",$_POST['amount_applied']);
                    $loanaccount = Loanaccounts::model()->findByPk($_POST['loanaccount']);
                    $profile=Profiles::model()->findByPk($loanaccount->user_id);
                    $defaultLimit   = ProfileEngine::getActiveProfileAccountSettingByType($profile->id,'LOAN_LIMIT');
                    $maxLimit       = $defaultLimit === 'NOT SET' ? Yii::app()->params['DEFAULTMAXLOANAMOUNT'] : floatval($defaultLimit);
                    $limitFormatted=CommonFunctions::asMoney($maxLimit);
                    if($maxLimit < $amountValue){
                        CommonFunctions::setFlashMessage('danger',"Application cannot be resubmitted since the total loan amount
					 will exceed the client loan limit of KES $limitFormatted /=");
                        $this->redirect(array('loanaccounts/resubmit/'.$loanaccount->loanaccount_id));
                    }else{
                        $loanaccount->amount_applied=$amountValue;
                        $loanaccount->repayment_period=$_POST['repayment_period'];
                        $loanaccount->special_comment=$_POST['special_comment'];
                        $loanaccount->loan_status='10';
                        if($loanaccount->save()){
                            $data['loanaccount_id']=$loanaccount->loanaccount_id;
                            $data['comment']=$loanaccount->special_comment;
                            $data['activity']="Resubmitting Loan Account";
                            $data['commented_by']=Yii::app()->user->user_id;
                            LoanApplication::recordLoanComment($data);
                            $returned=LoanRedirects::model()->findByPk($_POST['returnedStatus']);
                            $returned->resubmitted='1';
                            $returned->save();
                            $documentURL=Yii::app()->params['loanDocs'];
                            if(!empty($_FILES)){
                                $img = $_FILES['path'];
                                if(!empty($img)){
                                    $img_desc = CommonFunctions::reArrayFiles($img);
                                    foreach($img_desc as $val){
                                        $ext = pathinfo($val['name'], PATHINFO_EXTENSION);
                                        $fileUpload=new LoanFiles;
                                        $fileUpload->loanaccount_id=$loanaccount->loanaccount_id;
                                        $fileUpload->name=$loanaccount->account_number.'-'.$loanaccount->BorrowerFullName;
                                        $fileUpload->filename=date('YmdHis',time()).mt_rand().'.'.$ext;
                                        $fileUpload->created_by=Yii::app()->user->user_id;
                                        if($fileUpload->save()){
                                            move_uploaded_file($val['tmp_name'],$documentURL."/".$fileUpload->filename);
                                        }
                                    }
                                }
                                $accountNumber=$loanaccount->account_number;
                                $fullName=$loanaccount->BorrowerFullName;
                                Logger::logUserActivity("Resubmitted Loan Account: $accountNumber for $fullName",'normal');
                                $type='success';
                                $message="Loan Account successfully resubmitted with files attached";
                                CommonFunctions::setFlashMessage($type,$message);
                                $this->redirect(array('admin'));
                            }else{
                                $accountNumber=$loanaccount->account_number;
                                $fullName=$loanaccount->BorrowerFullName;
                                Logger::logUserActivity("Resubmitted Loan Account: $accountNumber for $fullName",'normal');
                                $type='success';
                                $message="Loan Account successfully resubmitted.";
                                CommonFunctions::setFlashMessage($type,$message);
                                $this->redirect(array('admin'));
                            }
                        }else{
                            echo "Loan Not Saved";exit;
                        }
                    }
                }
                break;
        }
    }

    public function actionWriteOff(){
        switch(Navigation::checkIfAuthorized(47)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to write off loan balance.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $accountID=$_POST['loanaccount'];
                $balance=$_POST['amount'];
                $writeOffReason=$_POST['reason'];
                $writeOffType='Loan Balance';
                $voidType='2';
                $loanaccount=Loanaccounts::model()->findByPk($accountID);
                $accountNumber=$loanaccount->account_number;
                $fullName=$loanaccount->BorrowerFullName;
                $formatBalance=number_format($balance,2);
                if($loanaccount->loan_status === '4'){
                    $type='danger';
                    $message="The loan balance has already been written off and account closed.";
                }else{
                    $loanaccount->amount_approved=0;
                    $loanaccount->loan_status='4';
                    if($loanaccount->save()){
                        LoanManager::recordWriteOff($accountID,$balance,$writeOffType,$writeOffReason);
                        $disburseQuery="SELECT * FROM disbursed_loans WHERE loanaccount_id=$accountID";
                        $account=DisbursedLoans::model()->findBySql($disburseQuery);
                        if(!empty($account)){
                            $account->amount_disbursed=0;
                            $account->save();
                        }
                        Yii::app()->db->createCommand("DELETE FROM loaninterests WHERE loanaccount_id=$accountID")->execute();
                        Yii::app()->db->createCommand("DELETE FROM penaltyaccrued WHERE loanaccount_id=$accountID")->execute();
                        Logger::logUserActivity("Wrote off loan balance of KSH. $formatBalance, Account: $accountNumber,Client: $fullName",'urgent');
                        $type='success';
                        $message="Loan balance successfully written off.";
                    }
                }
                CommonFunctions::setFlashMessage($type,$message);
                $this->redirect(array('loanaccounts/'.$accountID));
                break;
        }
    }

    public function actionWriteOffAccruedInterest(){
        switch(Navigation::checkIfAuthorized(48)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to write off loan arrears.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $accountID=$_POST['loanaccount'];
                $balance=$_POST['amount'];
                $writeOffReason=$_POST['reason'];
                $writeOffType='Interest Accrued';
                $voidType='2';
                $account=Loanaccounts::model()->findByPk($accountID);
                $accountNumber=$account->account_number;
                $fullName=$account->BorrowerFullName;
                $formatBalance=number_format($balance,2);
                LoanManager::recordAccruedInterest($accountID,$balance,'credit','1');
                LoanManager::recordWriteOff($accountID,$balance,$writeOffType,$writeOffReason);
                // Yii::app()->db->createCommand("DELETE FROM loaninterests WHERE loanaccount_id=$accountID")->execute();
                Logger::logUserActivity("Wrote off loan accrued interest of KSH. $formatBalance, Account: $accountNumber,Client: $fullName",'urgent');
                CommonFunctions::setFlashMessage('success',"Accrued Interests successfully written off.");
                $this->redirect(array('loanaccounts/'.$accountID));
                break;
        }
    }

    public function actionWriteOffPenalty(){
        switch(Navigation::checkIfAuthorized(49)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to write off loan penalty.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $accountID=$_POST['loanaccount'];
                $balance=$_POST['amount'];
                $writeOffReason=$_POST['reason'];
                $writeOffType='Penalty Accrued';
                $voidType='2';
                $account=Loanaccounts::model()->findByPk($accountID);
                $accountNumber=$account->account_number;
                $fullName=$account->BorrowerFullName;
                $formatBalance=number_format($balance,2);
                LoanManager::recordWriteOff($accountID,$balance,$writeOffType,$writeOffReason);
                Yii::app()->db->createCommand("DELETE FROM penaltyaccrued WHERE loanaccount_id=$accountID")->execute();
                Logger::logUserActivity("Wrote off loan penalty of KSH. $formatBalance, Account: $accountNumber,Client: $fullName",'urgent');
                CommonFunctions::setFlashMessage('success',"Penalty successfully written off.");
                $this->redirect(array('loanaccounts/'.$accountID));
                break;
        }
    }

    public function actionDeletePrincipalBalance(){
        switch(Navigation::checkIfAuthorized(200)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to delete loan principal balance.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $accountID=$_POST['loanaccount'];
                $accountPrincipalBalance=LoanManager::getPrincipalBalance($accountID);
                $balance=$_POST['amount'];
                $deletionReason=$_POST['reason'];
                $deletionType='Principal Balance Deletion';
                if($balance > $accountPrincipalBalance){
                    CommonFunctions::setFlashMessage('danger',"Failed to delete principal balance. Cannot delete amount more than the available principal balance.");
                }else{
                    if(LoanManager::deleteAccountPrincipal($accountID,$balance,$deletionReason,$deletionType) == 1){
                        CommonFunctions::setFlashMessage('success',"Principal Balance successfully deleted.");
                    }else{
                        CommonFunctions::setFlashMessage('danger',"Failed to delete principal balance.");
                    }
                }
                $this->redirect(array('loanaccounts/'.$accountID));
                break;
        }
    }

    public function actionFilterLoanStatement(){
        $loanaccountID=$_POST['loanaccount_id'];
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $report_type=$_POST['selectAction'];
        $loanaccount=$this->loadModel($loanaccountID);
        switch((int)$report_type){
            /*PDF*/
            case 1:
                $pdfLink=LoanApplication::getPdfDownloadableStatement($loanaccountID,$start_date,$end_date);
                echo $pdfLink;
                break;
            /*Excel*/
            case 2:
                $transactions = LoanManager::getAccountStatementTransactions($loanaccountID,$start_date,$end_date);
                $excelWriter  = ExportFunctions::exportClientLoanStatementAsExcel($loanaccount,$start_date,$end_date,$transactions);
                echo $excelWriter->save('php://output');
                break;
            /*Email Statement*/
            case 3:
                $mailingLink = LoanApplication::emailClientLoanStatement($loanaccountID,$start_date,$end_date);
                echo $mailingLink;
                break;
        }
    }

    public function actionViewDetails($id){
        switch(Navigation::checkIfAuthorized(50)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to view loan account details.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $model   = $this->loadModel($id);
                $profile = Profiles::model()->findByPk($model->user_id);
                $others  = LoanManager::getOtherProfileLoanAccounts($profile->id,$id);
                switch($model->loan_status){
                    case '0':
                        $users          = $model->getForwadingList();
                        $filesSQL       = "SELECT * FROM loan_files WHERE loanaccount_id=$id";
                        $loanfiles      = LoanFiles::model()->findAllBySql($filesSQL);
                        $returnedStatus = LoanApplication::checkIfApplicationReturned($id);
                        $forwardedStatus= LoanApplication::checkIfApplicationForwarded($id);
                        $savingAccounts = SavingFunctions::getAllUserSavingAccounts($model->user_id);
                        if($savingAccounts ===0){
                            $accountID=0;
                        }else{
                            foreach ($savingAccounts as $account) {
                                $accountID=$account->savingaccount_id;
                            }
                        }
                        $comments=LoanApplication::getLoanComments($id);
                        $transactions=SavingFunctions::getAllSavingAccountTransactions($accountID);
                        $this->render('viewDetails',array(
                            'model'=>$model,
                            'users'=>$users,'files'=>$loanfiles,
                            'returnedStatus'=>$returnedStatus,
                            'forwardedStatus'=>$forwardedStatus,
                            'transactions'=>$transactions,
                            'comments'=>$comments,
                            'others'=>$others
                        ));
                        break;

                    case '10':
                        $users=$model->getForwadingList();
                        $filesSQL="SELECT * FROM loan_files WHERE loanaccount_id=$id";
                        $loanfiles=LoanFiles::model()->findAllBySql($filesSQL);
                        $returnedStatus=LoanApplication::checkIfApplicationReturned($id);
                        $forwardedStatus=LoanApplication::checkIfApplicationForwarded($id);
                        $savingAccounts=SavingFunctions::getAllUserSavingAccounts($model->user_id);
                        if($savingAccounts ===0){
                            $accountID=0;
                        }else{
                            foreach ($savingAccounts as $account) {
                                $accountID=$account->savingaccount_id;
                            }
                        }
                        $comments     = LoanApplication::getLoanComments($id);
                        $transactions = SavingFunctions::getAllSavingAccountTransactions($accountID);
                        $this->render('viewDetails',array(
                            'model'=>$model,
                            'users'=>$users,'files'=>$loanfiles,
                            'returnedStatus'=>$returnedStatus,
                            'forwardedStatus'=>$forwardedStatus,
                            'transactions'=>$transactions,
                            'comments'=>$comments,
                            'others'=>$others
                        ));
                        break;

                    default:
                        CommonFunctions::setFlashMessage('danger',"Application has already been approved or rejected.");
                        $this->redirect(array('admin'));
                        break;
                }
                break;
        }
    }

    public function actionLoadExistence(){
        $userID  = $_POST['userID'];
        $loanSQL = "SELECT * FROM loanaccounts WHERE user_id=$userID AND loan_status IN('2','5','6','7')";
        $loans   = Loanaccounts::model()->findAllBySql($loanSQL);
        echo !empty($loans) ? 1 : 0;
    }

    public function actionRevert(){
        $element=Yii::app()->user->user_level;
        $array=array('1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $revertQuery="SELECT * FROM loanaccounts WHERE loan_status IN('4')";
                $loans=Loanaccounts::model()->findAllBySql($revertQuery);
                if(!empty($loans)){
                    foreach($loans AS $loan){
                        $balance = LoanManager::getPrincipalBalance($loan->loanaccount_id);
                        echo $balance;
                        if($balance > 2){
                            $accountNumber=$loan->account_number;
                            $loan->loan_status='2';
                            $loan->save();
                            echo "Reverted Loan Account : $accountNumber <br>";
                        }else{
                            echo "Account was correctly fully settled. Cannot Revert.<br>";
                        }
                    }
                }else{
                    echo "No Account to revert <br>";
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionMassUpdate(){
        $element=Yii::app()->user->user_level;
        $array=array('0','1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $loanSQL="SELECT * FROM loanaccounts WHERE loan_status IN('2','5','6','7')";
                $loans=Loanaccounts::model()->findAllBySql($loanSQL);
                if(!empty($loans)){
                    foreach($loans AS $loan){
                        $balance=LoanManager::getActualLoanBalance($loan->loanaccount_id);
                        if($balance <= 0){
                            $loanAccount=LoanApplication::getLoanAccount($loan->loanaccount_id);
                            $accountNumber=$loanAccount->account_number;
                            $loanAccount->loan_status='4';
                            $loanAccount->save();
                            echo "Updated Loan Account : $accountNumber <br>";
                        }elseif($balance > 0){
                            $loanAccount=LoanApplication::getLoanAccount($loan->loanaccount_id);
                            $accountNumber=$loanAccount->account_number;
                            $loanAccount->loan_status='2';
                            $loanAccount->save();
                            echo "Reverted Loan Account : $accountNumber <br>";
                        }else{
                            echo "Nothing to Update <br>";
                        }
                    }
                }else{
                    echo "Nothing to Update <br>";
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionMoveArrears(){
        $element=Yii::app()->user->user_level;
        $array=array('0','1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $loanQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','5')";
                $loans=Loanaccounts::model()->findAllBySql($loanQuery);
                if(!empty($loans)){
                    foreach($loans AS $loan){
                        $loanArrears=$loan->arrears;
                        $accountID=$loan->loanaccount_id;
                        $accountNumber=$loan->account_number;
                        if($loanArrears > 0){
                            $loan->arrears=0;
                            $loan->save();
                            LoanManager::recordAccruedInterest($accountID,$loanArrears);
                            echo "Loan Account Arrears: $loanArrears moved successfuly: $accountNumber <br>";
                        }else{
                            echo "Nothing to Move <br>";
                        }
                    }
                }else{
                    echo "Nothing to Move <br>";
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionRollback(){
        $element=Yii::app()->user->user_level;
        $array=array('0','1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $startDate=date('2019-01-01');
                $currentDate=date('Y-m-d');
                $formatDate=date('Y-m-d',strtotime($currentDate));
                $rollQuery= "SELECT * FROM loanaccounts,disbursed_loans WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.loan_status NOT IN('0','1','3','4','5') AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$startDate' AND '$currentDate')";
                $loans=Loanaccounts::model()->findAllBySql($rollQuery);
                $number=1;
                if(!empty($loans)){
                    foreach($loans AS $loan){
                        $dateDisbursed=$loan->disbursed_at;
                        $startMonth=(int)date('m',strtotime($dateDisbursed));
                        $repaymentDate=date('Y-m-d',strtotime($loan->repayment_start_date));
                        $endMonth=(int)date('m',strtotime($repaymentDate));
                        $interestRate=$loan->interest_rate;
                        $principalBal=0;
                        echo "$number : $loan->account_number : $dateDisbursed: $principalBal <br>";
                        $duration=CommonFunctions::getDatesDifference($dateDisbursed,$currentDate);
                        if($duration < 30){
                            for($x=0; $x<$duration;$x++){
                                $accruedDailyInterest=(($interestRate/100)/30) * $loan->amount_approved;
                                LoanManager::recordAccruedInterest($loan->loanaccount_id,$accruedDailyInterest);
                            }
                        }
                        for($i=$startMonth;$i<=$endMonth;$i++){
                            if($repaymentDate <= $formatDate){
                                $interestPayable=($interestRate/100) * $principalBal;
                                $interestPaid=LoanRepayment::getTotalInterestPaidFrom($loan->loanaccount_id,$dateDisbursed);
                                $interestDifference=$interestPayable - $interestPaid;
                                if($interestDifference > 0){
                                    LoanManager::recordAccruedInterest($loan->loanaccount_id,$interestDifference);
                                }
                            }
                            $principalBal=LoanTransactionsFunctions::getLoanPrincipalBalanceFrom($loan->loanaccount_id,$dateDisbursed);
                            $dateDisbursed = date('d-M-Y', strtotime($dateDisbursed. ' + 30 days'));
                        }
                        $number++;
                    }
                }else{
                    echo "No Accounts to Rollback <br>";
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionFreeze($id){
        if(Navigation::checkIfAuthorized(138) === 1){
            $model=$this->loadModel($id);
            $loanfiles=LoanApplication::getLoanAccountFiles($id);
            $comments=LoanApplication::getLoanComments($id);
            $this->render('freeze',array('model'=>$model,'files'=>$loanfiles,'comments'=>$comments));
        }else{
            CommonFunctions::setFlashMessage('danger',"You are not allowed to freeze interest accrual.");
            $this->redirect(array('dashboard/default'));
        }
    }

    public function actionCommitFreezing(){
        switch(Navigation::checkIfAuthorized(138)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"You are not allowed to freeze interest accrual.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccount_id=$_POST['loanaccount_id'];
                $period=(int)$_POST['freezing_period'];
                $reason=$_POST['freezing_reason'];
                switch(LoanManager::freezeInterestAccrual($loanaccount_id,$period,$reason)){
                    case 0:
                        $type='danger';
                        $message="Freezing account interest accrual failed.";
                        break;

                    case 1:
                        $type='success';
                        $message="Account interest accrual frozen successfully.";
                        break;

                    case 2:
                        $type='danger';
                        $message="Account interest accrual has already been frozen.";
                        break;

                    case 3:
                        $type='danger';
                        $message="Account to be frozen unavailable.";
                        break;
                }
                CommonFunctions::setFlashMessage($type,$message);
                $this->redirect(array('admin'));
                break;
        }
    }

    public function actionUnfreeze($id){
        if(Navigation::checkIfAuthorized(138) === 1){
            $model=$this->loadModel($id);
            $loanfiles=LoanApplication::getLoanAccountFiles($id);
            $comments=LoanApplication::getLoanComments($id);
            $this->render('unfreeze',array('model'=>$model,'files'=>$loanfiles,'comments'=>$comments));
        }else{
            CommonFunctions::setFlashMessage('danger',"You are not allowed to unfreeze interest accrual.");
            $this->redirect(array('dashboard/default'));
        }
    }

    public function actionCommitUnfreezing(){
        switch(Navigation::checkIfAuthorized(138)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"You are not allowed to unfreeze interest accrual.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $loanaccount_id=$_POST['loanaccount_id'];
                $reason=$_POST['reason'];
                switch(LoanManager::unfreezeInterestAccrual($loanaccount_id,$reason)){
                    case 0:
                        $type='danger';
                        $message="Unfreezing account interest accrual failed.";
                        break;

                    case 1:
                        $type='success';
                        $message="Account interest accrual unfrozen successfully.";
                        break;
                }
                CommonFunctions::setFlashMessage($type,$message);
                $this->redirect(array('admin'));
                break;
        }
    }

    public function actionUpdateDates(){
        $element=Yii::app()->user->user_level;
        $array=array('1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $dateQuery="SELECT * FROM `loanaccounts` WHERE loan_status NOT IN('0','1','3') AND loanaccount_id < 1526";
                $loanaccounts=Loanaccounts::model()->findAllBySql($dateQuery);
                foreach($loanaccounts AS $account){
                    if(!empty($account)){
                        $disbursementDate=date("Y-m-d",strtotime($account->created_at.'+7 days'));
                        $repaymentStartDate = date('Y-m-d', strtotime($disbursementDate. ' + 1 month'));
                        $account->repayment_start_date=$repaymentStartDate;
                        $account->date_approved=$disbursementDate;
                        if($account->save()){
                            $LoanAccountID =$account->loanaccount_id;
                            $disbursedQuery="SELECT * FROM disbursed_loans WHERE loanaccount_id=$LoanAccountID LIMIT 1";
                            $disbursedAccount=DisbursedLoans::model()->findBySql($disbursedQuery);
                            if(!empty($disbursedAccount)){
                                $disbursedAccount->disbursed_at=$disbursementDate;
                                $disbursedAccount->save();
                                echo "Account Updated";
                            }
                        }
                    }else{
                        echo "No Account Found";
                    }
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionUpdateRecentDates(){
        $element=Yii::app()->user->user_level;
        $array=array('1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $disbursedQuery="SELECT * FROM disbursed_loans WHERE loanaccount_id > 1";
                $loanaccounts=DisbursedLoans::model()->findAllBySql($disbursedQuery);
                foreach($loanaccounts AS $account){
                    if(!empty($account)){
                        $disbursementDate=date("Y-m-d",strtotime($account->disbursed_at));
                        $repaymentStartDate = date('Y-m-d', strtotime($disbursementDate. ' + 1 month'));
                        $LoanAccountID =$account->loanaccount_id;
                        $maturityQuery="SELECT * FROM loan_maturities WHERE loanaccount_id=$LoanAccountID LIMIT 1";
                        $disbursedAccount=LoanMaturities::model()->findBySql($maturityQuery);
                        if(!empty($disbursedAccount)){
                            $disbursedAccount->maturity_date=$repaymentStartDate;
                            $disbursedAccount->save();
                            echo "Account Updated";
                        }
                    }else{
                        echo "No Account Found";
                    }
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionUpdateDetails(){
        $element=Yii::app()->user->user_level;
        $array=array('1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $loanaccounts=Loanaccounts::model()->findAll();
                foreach($loanaccounts AS $account){
                    if(!empty($account)){
                        $profile=Profiles::model()->findByPk($account->user_id);
                        if(!empty($profile)){
                            $account->branch_id=$profile->branchId;
                            $account->rm=$profile->managerId;
                            $account->save();
                            echo "Account Updated";
                        }else{
                            echo "No User Found";
                        }
                    }else{
                        echo "No Account Found";
                    }
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionUpdateStatus(){
        $element=Yii::app()->user->user_level;
        $array=array('1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $loanaccounts=Loanaccounts::model()->findAll();
                foreach($loanaccounts AS $account){
                    $arrearsDays=$account->DaysInArrears;
                    if($arrearsDays <= 0){
                        $risk="A";
                        $crbStatus="a";
                    }elseif($arrearsDays >= 0 && $arrearsDays <=30){
                        $risk="A";
                        $crbStatus="a";
                    }elseif($arrearsDays >30 && $arrearsDays <=90 ){
                        $risk="B";
                        $crbStatus="a";
                    }elseif($arrearsDays >90 && $arrearsDays <=180){
                        $risk="C";
                        $crbStatus="a";
                    }elseif($arrearsDays >180 && $arrearsDays <=360){
                        $risk="D";
                        $crbStatus="b";
                    }elseif($arrearsDays >360){
                        $risk="E";
                        $crbStatus="b";
                    }
                    $account->crb_status=$crbStatus;
                    $account->performance_level=$risk;
                    $account->save();
                    if(!empty($account)){
                        $accountNumber=$account->account_number;
                        $CRBStatus=$account->crb_status;
                        $PerformanceLevel=$account->performance_level;
                        echo "Account:$accountNumber, CRB Status: $CRBStatus and Performance Level:$PerformanceLevel <br/>";
                        switch($account->loan_status){
                            case '0':
                                $accountStatus='N';
                                $updateMesage="Account Updated as NOT UPDATED <br/>";
                                break;

                            case '1':
                                $accountStatus='N';
                                $updateMesage="Account Updated as NOT UPDATED <br/>";
                                break;

                            case '2':
                                $accountStatus='F';
                                $updateMesage="Account Updated as ACTIVE <br/>";
                                break;

                            case '3':
                                $accountStatus='A';
                                $updateMesage="Account Updated as CLOSED<br/>";
                                break;

                            case '4':
                                $accountStatus='H';
                                $updateMesage="Account Updated as FULLY SETTLED <br/>";
                                break;

                            case '5':
                                $accountStatus='G';
                                $updateMesage="Account Updated as TERMS EXTENDED <br/>";
                                break;

                            case '6':
                                $accountStatus='G';
                                $updateMesage="Account Updated as TERMS EXTENDED <br/>";
                                break;

                            case '7':
                                $accountStatus='B';
                                $updateMesage="Account Updated as DORMANT<br/>";
                                break;
                        }
                        $account->account_status=$accountStatus;
                        $account->save();
                        echo "======================================= <br/>";
                    }else{
                        echo "No Account Found<br/>";
                    }
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionUpdateWriteOffs(){
        $element = Yii::app()->user->user_level;
        $array   = array('1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $writeoffs = WriteOffs::model()->findAll();
                foreach($writeoffs AS $writeOff){
                    $loanaccount=Loanaccounts::model()->findByPk($writeOff->loanaccount_id);
                    if(!empty($loanaccount)){
                        $branchID=$loanaccount->branch_id;
                        $userID=$loanaccount->user_id;
                        $rmID=$loanaccount->rm;
                        $writeOff->branch_id=$branchID;
                        $writeOff->user_id=$userID;
                        $writeOff->rm=$rmID;
                        $writeOff->save();
                    }
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionUpdateFrozenAccounts(){
        $element=Yii::app()->user->user_level;
        $array=array('1','2','3','4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $freezes=InterestFreezes::model()->findAll();
                foreach($freezes AS $freeze){
                    $loanaccount=Loanaccounts::model()->findByPk($freeze->loanaccount_id);
                    if(!empty($loanaccount)){
                        $branchID=$loanaccount->branch_id;
                        $userID=$loanaccount->user_id;
                        $rmID=$loanaccount->rm;
                        $freeze->branch_id=$branchID;
                        $freeze->user_id=$userID;
                        $freeze->rm=$rmID;
                        $freeze->save();
                    }
                }
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionExportLoans(){
        $loanQuery  = "SELECT * FROM loanaccounts,profiles WHERE loanaccounts.user_id=profiles.id
		AND loanaccounts.loan_status NOT IN('0','1','3','8','9','10') AND profiles.profileType IN('MEMBER','SUPPLIER')";
        $userBranch = Yii::app()->user->user_branch;
        $userID     = Yii::app()->user->user_id;
        switch(Yii::app()->user->user_level){
            case '0':
                $loanQuery.="";
                break;

            case '1':
                $loanQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $loanQuery.=" AND loanaccounts.rm=$rm";
                break;

            case '3':
                $loanQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }
        $excelWriter = ExportFunctions::getCRBListingReport(Yii::app()->db->createCommand($loanQuery)->queryAll());
        echo $excelWriter->save('php://output');
    }

    public function actionDisbursedAccounts(){
        $element = Yii::app()->user->user_level;
        $array   = array('4','5');
        switch(CommonFunctions::searchElementInArray($element,$array)){
            case 0:
                $model = new Loanaccounts('searchDisbursed');
                $model->unsetAttributes();  // clear any default values
                if(isset($_GET['Loanaccounts'])){
                    $model->attributes=$_GET['Loanaccounts'];
                    if(isset($_GET['export'])){
                        $dataProvider = $model->searchDisbursed();
                        $dataProvider->pagination = False;
                        $excelWriter = ExportFunctions::getExcelDisbursedAccounts($dataProvider->data);
                        echo $excelWriter->save('php://output');
                    }
                }
                $this->render('disbursedAccounts',array('model'=>$model));
                break;

            case 1:
                CommonFunctions::setFlashMessage('danger',"Access Not Allowed.");
                $this->redirect(array('dashboard/default'));
                break;
        }
    }

    public function actionExportProfitAndLoss(){
        $loanQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3')";
        $userBranch=Yii::app()->user->user_branch;
        $userID=Yii::app()->user->user_id;
        switch(Yii::app()->user->user_level){
            case '0':
                $loanQuery.="";
                break;

            case '1':
                $loanQuery.=" AND loanaccounts.branch_id=$userBranch";
                break;

            case '2':
                $loanQuery.=" AND loanaccounts.rm=$rm";
                break;

            case '3':
                $loanQuery.=" AND loanaccounts.user_id=$userID";
                break;
        }
        $loanaccounts=Loanaccounts::model()->findAllBySql($loanQuery);
        $exportPdf=ExportFunctions::exportProfitandLossReportAsPdf($loanaccounts);
        $filename =date('YmdHis')."_profit_and_loss_report.pdf";
        echo $exportPdf->Output($filename,'D');
    }

    public function actionAddGuarantor($id){
        $model = $this->loadModel($id);
        switch(Navigation::checkIfAuthorized(107)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to add loan account guarantors.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                if(isset($_POST['add_guarantor_cmd'])){
                    $accountID   = $id;
                    $name        = $_POST['name'];
                    $idNumber    = $_POST['id_number'];
                    $phoneNumber = $_POST['phone'];
                    switch(LoanApplication::createGuarantorRecord($accountID,$name,$idNumber,$phoneNumber)){
                        case 0:
                            CommonFunctions::setFlashMessage('danger',"Failed to create guarantor records.");
                            break;

                        case 1:
                            CommonFunctions::setFlashMessage('success',"Guarantor successfully created.");
                            break;
                    }
                    $this->redirect(array('loanaccounts/'.$id));
                }
                $this->render('addGuarantor',array('model'=>$model));
                break;
        }
    }

    public function actionCreateClearanceRecords(){
        Yii::app()->db->createCommand("TRUNCATE clearedloans")->execute();
        $loanaccounts = Loanaccounts::model()->findAll();
        foreach($loanaccounts AS $account){
            $balance  = LoanManager::getActualLoanBalance($account->loanaccount_id);
            if($balance <= 0){
                $loanAccount = LoanApplication::getLoanAccount($account->loanaccount_id);
                $clearedLoan = new Clearedloans;
                $clearedLoan->loanaccount_id = $account->loanaccount_id;
                $clearedLoan->date_cleared   = $account->AccountPaymentDate;
                $clearedLoan->overpayment    = $balance;
                $clearedLoan->save();
            }
        }

    }

    public function actionTestSTKPush(){
        LoanManager::updateCallBacks();
    }

    public function actionLoanRepaymentSTKPush($id){
        $model = $this->loadModel($id);
        switch(Navigation::checkIfAuthorized(283)){
            case 0:
                CommonFunctions::setFlashMessage('danger',"Not Authorized to initiate loan repayment payment prompt - STK Push.");
                $this->redirect(array('dashboard/default'));
                break;

            case 1:
                $allowed =  array('2','5','6','7');
                if(in_array($model->loan_status,$allowed)){
                    if(isset($_POST['push_loan_payment_stk_cmd'])){
                        $profileId       = $model->user_id;
                        $transactionType = 'LOAN_PAYMENT';
                        $loanBalance     = ceil(LoanManager::getActualLoanBalance($id));
                        $amountPaid      = $_POST['paymentType'] === 'full' ? ceil($loanBalance) : ceil($_POST['amountPaid']);
                        if($amountPaid <= $loanBalance && $loanBalance > 0){
                            $phoneNumber     = $_POST['phoneNumber'];
                            $accountNumber   = $_POST['accountNumber'];
                            switch(LoanManager::sendSTKPush($profileId,$transactionType,$amountPaid,$phoneNumber,$accountNumber)){
                                case 1000:
                                    CommonFunctions::setFlashMessage('success',"Loan payment prompt successfully initiated to the customer's phone number.");
                                    break;

                                case 1001:
                                    CommonFunctions::setFlashMessage('danger',"Failed to create stk push record. Please try again later.");
                                    break;

                                case 1003:
                                    CommonFunctions::setFlashMessage('danger',"No response received from MPESA. Please try again later.");
                                    break;

                                case 1005:
                                    CommonFunctions::setFlashMessage('danger',"Failed to generate MPESA API Access Token. Please try again later.");
                                    break;

                                case 1007:
                                    CommonFunctions::setFlashMessage('danger',"MPESA C2B DISABLED. Contact your system Administrator for assistance!");
                                    break;

                                case 1009:
                                    CommonFunctions::setFlashMessage('danger',"Failed. Please ensure the phone number is valid Safaricom Number.");
                                    break;

                                default:
                                    CommonFunctions::setFlashMessage('danger',"Failed to initiate MPESA payment prompt. Please try again later.");
                                    break;
                            }
                            $this->redirect(array('loanaccounts/'.$id));

                        }else{
                            CommonFunctions::setFlashMessage('danger',"Failed to initiate payment prompt. Amount paid cannot be more than the loan balance.");
                            $this->redirect(array('loanaccounts/'.$id));
                        }
                    }else{
                        CommonFunctions::setFlashMessage('danger',"Failed to initiate payment prompt. Try again later");
                        $this->redirect(array('loanaccounts/'.$id));
                    }
                }else{
                    CommonFunctions::setFlashMessage('danger',"The loan account is not active and cannot initiate customer payment prompt.");
                    $this->redirect(array('loanaccounts/'.$id));
                }
                break;
        }
    }
    
    public function actionLoadBorrowerList(){
        $member_type = $_POST['member_type'];
        $members = ProfileEngine::getProfilesByCategory($member_type);
        //return list of members
        echo CJSON::encode($members);
    }

    public function loadModel($id){
        $model=Loanaccounts::model()->findByPk($id);
        if($model==null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='loanaccounts-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
