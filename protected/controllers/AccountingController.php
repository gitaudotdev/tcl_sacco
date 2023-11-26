<?php

class AccountingController extends Controller{

	public $layout='//layouts/templates/pages';

	public function filters(){
		return array(
			'accessControl',
		);
	}

	public function accessRules(){
		return array(
			array('allow', 
				'actions'=>array('balancesheet','cashflowaccumulated','cashflowmonthly','cashflowprojection','profitandloss',
				'loadCashFlowAccumulatedTable','filterProfitAndLossReport','loadMonthlyCashFlowTable','accountBalance','refreshBalance'),
				'users'=>array('@'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionBalancesheet(){
    	switch(Navigation::checkIfAuthorized(86)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Balancesheet Report.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('balancesheet');
    		break;
    	}
	}

	public function actionCashflowaccumulated(){
    	switch(Navigation::checkIfAuthorized(87)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Cash Flow Accumulated Report.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('cashflowaccumulated',array('branches'=>Reports::getAllBranches()));
    		break;
    	}
	}

	public function actionLoadCashFlowAccumulatedTable(){
		switch(Navigation::checkIfAuthorized(87)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Cash Flow Accumulated Report.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$branch=$_POST['branch'];
			$staff=$_POST['staff'];
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
			$cashflowaccumulated=Accounting::getCashFlowAccumulatedTable($branch,$staff,$start_date,$end_date);
			echo $cashflowaccumulated;
    		break;
    	}
	}


	public function actionCashflowmonthly(){
    	switch(Navigation::checkIfAuthorized(88)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Cash Flow Monthly Report.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('cashflowmonthly',array('branches'=>Reports::getAllBranches()));
    		break;
    	}
	}

	public function actionLoadMonthlyCashFlowTable(){
		switch(Navigation::checkIfAuthorized(88)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Cash Flow Monthly Report.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$branch=$_POST['branch'];
			$staff=$_POST['staff'];
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
			$cashflowaccumulated=Accounting::getMonthlyCashFlowTable($branch,$staff,$start_date,$end_date);
			echo $cashflowaccumulated;
    		break;
    	}
	  }

	public function actionCashflowprojection(){
    	switch(Navigation::checkIfAuthorized(89)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Cash Flow Projection Report.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('cashflowprojection');
    		break;
    	}
	}

	public function actionProfitandloss(){
    	switch(Navigation::checkIfAuthorized(90)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Profit and Loss Report.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$usersSQL       = "SELECT * FROM profiles WHERE profileType IN('STAFF')";
			$staff          = Profiles::model()->findAllBySql($usersSQL);
			$loanaccountSQL = "SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','8','9','10')";
			$loanaccounts   = Loanaccounts::model()->findAllBySql($loanaccountSQL);
			$this->render('profitandloss',array('branches'=>Reports::getAllBranches(),'staff'=>$staff,'loanaccounts'=>$loanaccounts));
    		break;
    	}
	}

	public function actionFilterProfitAndLossReport(){
		switch(Navigation::checkIfAuthorized(90)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Profit and Loss Report.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$start_date=$_POST['start_date'];
			$end_date=$_POST['end_date'];
			$branch=$_POST['branch'];
			$staff=$_POST['staff'];
			$borrower=$_POST['borrower'];
			echo Accounting::LoadFilteredProfitAndLossReport($start_date,$end_date,(int)$branch,(int)$staff,(int)$borrower);
    		break;
    	}
	}

	public function actionAccountBalance(){
		switch(Navigation::checkIfAuthorized(302)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view B2C balances.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			$this->render('b2c_balance',array('balance'=>LoanManager::getLatestC2BBalance()));
    		break;
    	}
	}

	public function actionRefreshBalance(){
		switch(Navigation::checkIfAuthorized(303)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to refresh B2C balance.");
	  	 	$this->redirect(array('dashboard/default'));
    		break;

    		case 1:
			switch(LoanManager::getB2CAccountBalance()){
				case 1000:
				CommonFunctions::setFlashMessage('success',"B2C account balances refreshed sucessfully.");
				Logger::logUserActivity("Refreshed B2C account balances",'normal');
				break;

				case 1001:
				CommonFunctions::setFlashMessage('danger',"B2C account balances refresh failed due to an error from M-PESA.");
				break;

				case 1003:
				CommonFunctions::setFlashMessage('danger',"B2C account balances refresh failed due to an invalid M-PESA access token.");
				break;

				case 1005:
				CommonFunctions::setFlashMessage('danger',"B2C account balances refresh failed due to database failure.");
				break;

				default:
				CommonFunctions::setFlashMessage('danger',"B2C account balances refresh failed. Try again later.");
				break;
			}
			$this->redirect(array('accountBalance'));
    		break;
    	}
	}
}