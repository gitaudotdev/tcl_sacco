
<?php

class ReportsController extends Controller{

	public $layout='//layouts/templates/pages';

	public function filters(){
		return array(
			'accessControl',
		);
	}

	public function accessRules(){
		return array(
			array('allow',
				'actions'=>array('feesReport','loadBranchBorrowers','loadBranchRelationManagers','loadRelationManagers','loadBorrowers',
				'loadAccounts','loadBranchAccounts','loadRelationManagerAccounts','loadFilteredDisbursement','loadEmployers',
				'loadBranchEmployers','loadRelationManagerEmployers','loadRelationManagerBorrowers','filterArrearsReport','analytics',
				'sendAirtime','executiveSummary','filterExecutiveSummaryReport'),
				'users'=>array('@'),
			),
			array('deny', 
				'users'=>array('*'),
			),
		);
	}

	public function actionAnalytics(){
    	$branches=Reports::getAllBranches();
    	switch(Navigation::checkIfAuthorized(81)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Analytics Report.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$this->render('analytics',array('branches'=>$branches));
    		break;
    	}
	}

	public function actionLoadEmployers(){
		$employers     = Reports::LoadEmployers();
		$employerArray =[];
		$counter=0;
		if(!empty($employers)){
			foreach($employers as $employ){ 
				$employerName=$employ->employer;
				$employerArray[$counter]['employerName']=$employerName;
				$counter++;
			}
		}
		echo json_encode($employerArray);
	}

	public function actionLoadBranchEmployers(){
		$employers=Reports::LoadBranchEmployers((int)$_POST['branch']);
		$employerArray=[];
		$counter=0;
		if(!empty($employers)){
			foreach($employers as $employ){
				$employerName=$employ->employer;
				$employerArray[$counter]['employerName']=$employerName;
				$counter++;
			}
		}
		echo json_encode($employerArray);
	}

	public function actionLoadRelationManagerEmployers(){
		$employers     = Reports::LoadRelationManagerEmployers((int)$_POST['staff']);
		$employerArray = [];
		$counter       = 0;
		if(!empty($employers)){
			foreach($employers as $employ){
				$employerName = $employ->employer;
				$employerArray[$counter]['employerName']=$employerName;
				$counter++;
			}
		}
		echo json_encode($employerArray);
	}

	public function actionLoadRelationManagers(){
		$managers = Reports::LoadRelationManagers();
		$manager_array=[];
		$counter=0;
		if(!empty($managers)){
			foreach($managers as $manage){
				$manager_array[$counter]['managerID']   = $manage->id;
				$manager_array[$counter]['managerName'] = $manage->ProfileFullName;
				$counter++;
			}
		}
		echo json_encode($manager_array);
	}

	public function actionLoadBranchRelationManagers(){
    	$branchID = $_POST['branch'];
		$managers = Reports::LoadBranchRelationManagers($branchID);
		$manager_array=[];
		$counter = 0;
		if(!empty($managers)){
			foreach($managers as $manage){
				$manager_array[$counter]['managerID']   = $manage->id;
				$manager_array[$counter]['managerName'] = $manage->ProfileFullName;
				$counter++;
			}
		}
		echo json_encode($manager_array);
	}

	public function actionLoadBorrowers(){
		$borrowers=Reports::LoadBorrowers();
		$borrower_array=[];
		$counter=0;
		foreach($borrowers as $borrower){
			$borrower_array[$counter]['borrowerID']=$borrower->id;
			$borrower_array[$counter]['borrowerName']=$borrower->ProfileNameWithIdNumber;
			$counter++;
		}
		echo json_encode($borrower_array);
	}

	public function actionLoadBranchBorrowers(){
		$borrowers=Reports::LoadBranchBorrowers($_POST['branch']);
		$borrower_array=[];
		$counter=0;
		foreach($borrowers as $borrower){
			$borrower_array[$counter]['borrowerID']=$borrower->id;
			$borrower_array[$counter]['borrowerName']=$borrower->ProfileNameWithIdNumber;
			$counter++;
		}
		echo json_encode($borrower_array);
	}

	public function actionLoadAccounts(){
		$accounts=Reports::LoadAccounts();
		$accounts_array=[];
		$counter=0;
		foreach($accounts as $account){
			$accounts_array[$counter]['accountID']=$account->loanaccount_id;
			$accounts_array[$counter]['accountName']=$account->getAccountDetails();
			$counter++;
		}
		echo json_encode($accounts_array);
	}

	public function actionLoadBranchAccounts(){
		$accounts=Reports::LoadBranchAccounts($_POST['branch']);
		$accounts_array=[];
		$counter=0;
		foreach($accounts as $account){
			$accounts_array[$counter]['accountID']=$account->loanaccount_id;
			$accounts_array[$counter]['accountName']=$account->getAccountDetails();
			$counter++;
		}
		echo json_encode($accounts_array);
	}

	public function actionLoadRelationManagerAccounts(){
		$accounts=Reports::LoadRelationManagerAccounts($_POST['staff']);
		$accounts_array=[];
		$counter=0;
		foreach($accounts as $account){
			$accounts_array[$counter]['accountID']=$account->loanaccount_id;
			$accounts_array[$counter]['accountName']=$account->getAccountDetails();
			$counter++;
		}
		echo json_encode($accounts_array);
	}

	public function actionLoadRelationManagerBorrowers(){
		$borrowers=Reports::LoadRelationManagerBorrowers($_POST['staff']);
		$borrower_array=[];
		$counter=0;
		foreach($borrowers as $borrower){
			$borrower_array[$counter]['borrowerID']=$borrower->id;
			$borrower_array[$counter]['borrowerName']=$borrower->ProfileNameWithIdNumber;
			$counter++;
		}
		echo json_encode($borrower_array);
	}

	public function actionFilterArrearsReport(){
		$start_date=$_POST['start_date'];
	    $end_date=$_POST['end_date'];
	    $branch=$_POST['branch'];
	    $staff=$_POST['staff'];
	    $borrower=$_POST['borrower'];
	    echo Reports::LoadFilteredArrearsReport($start_date,$end_date,(int)$branch,(int)$staff,(int)$borrower);
	}

	public function actionFeesReport(){
		$usersSQL     = "SELECT * FROM profiles WHERE profileType NOT IN('MEMBER')";
		$staff        = Profiles::model()->findAllBySql($usersSQL);
    	switch(Navigation::checkIfAuthorized(83)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not Authorized to view Arrears Report.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$this->render('feesReport',array('branches'=>Reports::getAllBranches(),'staff'=>$staff,'loanaccounts'=>SMS::getAllRepaidAccounts()));
    		break;
    	}
	}

	public function actionExecutiveSummary(){
    	switch(Navigation::checkIfAuthorized(201)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to view executive summary report.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$this->render('executiveSummary',array('branches'=>Reports::getAllBranches()));
    		break;
    	}
	}

	public function actionFilterExecutiveSummaryReport(){
    	switch(Navigation::checkIfAuthorized(201)){
    		case 0:
			CommonFunctions::setFlashMessage('danger',"Not authorized to view snap preview report.");
	  	 	$this->redirect(Yii::app()->request->urlReferrer);
    		break;

    		case 1:
			$startDate=$_POST['start_date'];
		    $endDate=$_POST['end_date'];
		    $branch=(int)$_POST['branch'];
		    $staff  =(int)$_POST['staff'];
		    $defaultPeriod=(int)$_POST['default_period'];
		    $summaryType=(int)$_POST['summary_type'];
		    echo Reports::LoadFilteredExecutiveSummaryReport($startDate,$endDate,$branch,$staff,$defaultPeriod,$summaryType);
    		break;
    	}
	}

}

			