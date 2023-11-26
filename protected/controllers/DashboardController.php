<?php

class DashboardController extends Controller{

	public $layout='//layouts/templates/pages';

	public function filters(){
		return array(
			'accessControl', 
		);
	}

	public function accessRules(){
		return array(
			array('allow', // allow authenticated user 
				'actions'=>array('logs','index','shareholder','password','admin','calendar','default',
          'loadCalendarData','confirmPassword','loansReleased','loanCollections','outstandingPrincipalBalance',
          'principalDueVersusCollections','numberLoansReleased','numberRepaymentsCollected','interestDueVersusCollections',
					'loanCollectionsVersusloansReleased','loanCollectionsVersusDueLoans','numberLoansCumulative','feesDueVersusCollections',
          'penaltyDueVersusPenaltyCollections','totalFullyPaidLoans','loadBorrowersCount','loadTotalPrincipalReleasedBranchDate','loadTotalCollectionsBranchDate',
          'loadPrincipalOutstandingBranchDate','loadInterestOutstandingBranchDate','loadPenaltyOutstandingBranchDate','loadTotalOutstandingBranchDate',
          'loadOpenLoansBranchDate','loadFullyPaidLoansBranchDate','loadPaymentCountsBranchDate','loadDefaultedLoansBranchDate','loadSavingsBranchDate',
          'loadAccruedSavingsBranchDate','loadTotalSavingsBranchDate','branch','loadBranchPerformance','staff','loadStaffPerformance','customerGrowth',
          'loadCustomerGrowthAnalytics','loadCustomerGrowthGraph','savings','loadSavingsGrowthAnalytics','loadSavingsGrowthGraph',
          'turnovers','loadTurnoversGrowthAnalytics','loadTurnoversGrowthGraph','disbursedPaidLoans','loadDisbursedPaidLoansGrowthAnalytics',
          'loadDisbursedPaidLoansGrowthGraph','interestGeneratedPaid','loadInterestGeneratedPaidGrowthAnalytics','loadInterestGeneratedPaidGrowthGraph',
          'loanPerformance','loadLoanPerformanceGrowthAnalytics','loadLoanPerformanceGrowthGraph','overallCollectionPerformance',
          'loadOverallCollectionPerformanceGrowthAnalytics','loadOverallCollectionPerformanceGrowthGraph','recoveredInterestPrinciple',
          'loadRecoveredInterestPrincipleGrowthAnalytics','loadRecoveredInterestPrincipleGrowthGraph','riskCounts','loadRiskCountsGrowthAnalytics',
          'loadRiskCountsGrowthGraph','riskAmounts','loadRiskAmountsGrowthAnalytics','loadRiskAmountsGrowthGraph','assetReturns','loadAssetReturnsGrowthAnalytics',
          'loadAssetReturnsGrowthGraph','assetQuality','loadAssetQualityGrowthAnalytics','loadAssetQualityGrowthGraph','incomeExpenses',
          'loadIncomeExpensesGrowthAnalytics','loadIncomeExpensesGrowthGraph','profitLoss','loadProfitLossGrowthAnalytics','loadProfitLossGrowthGraph',
          'comments','loadBranchCommentsDashboard','loadStaffCommentsDashboard'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

  public function actionLogs(){
    $element=Yii::app()->user->user_level;
    $array=array('1','2','3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      $this->render('logs',array('logs'=>Logs::model()->byID()->findAll()));
      break;

      case 1:
      CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access audit logs.");
      $this->redirect(array('dashboard/default'));
      break;
    }
  }

  public function actionDefault(){
    $this->render('default',array('profile'=>Profiles::model()->findByPk(Yii::app()->user->user_id),'notices'=>Dashboard::getLandingPageNotices()));
  }

	public function actionIndex(){
    switch(Navigation::checkIfAuthorized(116) == 1){
      case 0:
      CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access the statistics and analytics dashboard.");
      $this->redirect(array('dashboard/default'));
      break;

      case 1:
      $this->render('index',array('branches'=>Reports::getAllBranches()));
      break;
    }
	}

  public function actionBranch(){
    if(Navigation::checkIfAuthorized(118) == 1){
      $this->render('branch',array('branches'=>Reports::getAllBranches()));
    }else{
      CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access the branch performance dashboard.");
      $this->redirect(array('dashboard/default'));
    }
  }

  public function actionLoadBranchPerformance(){
    $branch     = $_POST['branch'];
    $startDate  = $_POST['start_date'];
    $endDate    = $_POST['end_date'];
    echo Performance::LoadFilteredBranchPerformance((int)$branch,$startDate,$endDate);
  }

  public function actionStaff(){
    $element = Yii::app()->user->user_level;
    $array   = array('3','4');
    switch(CommonFunctions::searchElementInArray($element,$array)){
      case 0:
      $this->render('staff',array('branches'=>Reports::getAllBranches()));
      break;

      case 1:
      CommonFunctions::setFlashMessage('danger',"Restricted Area. You are not allowed to access staff performance dashboard.");
      $this->redirect(array('dashboard/default')); 
      break;
    }
  }

  public function actionLoadStaffPerformance(){
    $branch    = $_POST['branch'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $staff     = $_POST['staff'];
    echo Performance::LoadFilteredStaffPerformance((int)$branch,$startDate,$endDate,(int)$staff);
  }

  public function actionLoadBorrowersCount(){
    $branch    = $_POST['branch'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $staff     = $_POST['staff'];
    $borrower  = $_POST['borrower'];
    $showAll   = $_POST['showAll'];
    echo Dashboard::LoadFilteredBorrowersCount((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
  }

  public function actionLoadTotalPrincipalReleasedBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $principal=Dashboard::LoadFilteredTotalPrincipalReleased((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo $principal;
  }

  public function actionLoadTotalCollectionsBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $repayments=Dashboard::LoadFilteredTotalLoanRepaymentsTransactions((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo $repayments;
  }

  public function actionLoadPrincipalOutstandingBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $principalOutstanding=Dashboard::LoadFilteredPrincipalOutstandingOpenLoans((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo CommonFunctions::asMoney($principalOutstanding);
  }

  public function actionLoadInterestOutstandingBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $interestOutstanding=Dashboard::LoadFilteredInterestOutstandingOpenLoans((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo CommonFunctions::asMoney($interestOutstanding);
  }

  public function actionLoadPenaltyOutstandingBranchDate(){
     $branch=$_POST['branch'];
     $startDate=$_POST['start_date'];
     $endDate=$_POST['end_date'];
     $staff=$_POST['staff'];
     $borrower=$_POST['borrower'];
     $showAll=$_POST['showAll'];
     $penaltyOutstanding=Dashboard::LoadFilteredPenaltyOutstandingOpenLoans((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
     echo CommonFunctions::asMoney($penaltyOutstanding);
  }

  public function actionLoadTotalOutstandingBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $totalOutstanding=Dashboard::LoadFilteredPenaltyOutstandingOpenLoans((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll)+Dashboard::LoadFilteredInterestOutstandingOpenLoans((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll)+Dashboard::LoadFilteredPrincipalOutstandingOpenLoans((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo CommonFunctions::asMoney($totalOutstanding);
  }

  public function actionLoadOpenLoansBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $openLoansCount=Dashboard::LoadFilteredLoansInQueueCount((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo $openLoansCount;
  }

  public function actionLoadFullyPaidLoansBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $fullySettledLoansCount=Dashboard::LoadFilteredFullySettledLoansCount((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo $fullySettledLoansCount;
  }

  public function actionLoadPaymentCountsBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $restructuredLoansCount=Dashboard::LoadPaymentCounts((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo $restructuredLoansCount;
  }

  public function actionLoadDefaultedLoansBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    echo Dashboard::LoadFilteredTotalDefaultedLoans((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
  }


  public function actionLoadSavingsBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $savingsBalance=Dashboard::LoadFilteredSavingsBalanceBranchDate((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    $accruedSavings=Dashboard::LoadFilteredAccruedSavingsBranchDate((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    $savingsAlone=$savingsBalance - $accruedSavings;
    if($savingsAlone<=0){
      $savingsAlone=0;
    }
    echo CommonFunctions::asMoney($savingsAlone);
  }


  public function actionLoadAccruedSavingsBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    echo CommonFunctions::asMoney(Dashboard::LoadFilteredAccruedSavingsBranchDate((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll));
  }

  public function actionLoadTotalSavingsBranchDate(){
    $branch=$_POST['branch'];
    $startDate=$_POST['start_date'];
    $endDate=$_POST['end_date'];
    $staff=$_POST['staff'];
    $borrower=$_POST['borrower'];
    $showAll=$_POST['showAll'];
    $to=Dashboard::LoadFilteredSavingsBalanceBranchDate((int)$branch,$startDate,$endDate,(int)$staff,(int)$borrower,$showAll);
    echo CommonFunctions::asMoney($to);
  }

	public function actionAdmin(){
    if(Navigation::checkIfAuthorized(260) == 1){
      $this->render('admin');
    }else{
      CommonFunctions::setFlashMessage('danger',"You are not allowed to access the system administration dashboard.");
      $this->redirect(array('dashboard/default'));
    }
	}

	public function actionPassword(){
    $userID=Yii::app()->user->user_id;
		$current=$_POST['current_password'];
		$new=$_POST['new_password'];
		$confirm=$_POST['confirm_password'];
		$status=Password::changeUserPassword($userID,$current, $new, $confirm);
		switch($status){
			case 0:
			$message='no_match';
			echo $message;
			break;

			case 1:
      $activity="Changed Password";
      $severity='normal';
      Logger::logUserActivity($activity,$severity);
			$message='success';
			echo $message;
			break;

			case 2:
			$message='incorrect_password';
			echo $message;
			break;

			case 3:
			$message='no_change';
			echo $message;
			break;
		}
	}

	public function actionConfirmPassword(){
		$user=Auths::model()->find('profileId=:a',array(':a'=>Yii::app()->user->user_id));
		$password=$_POST['password'];
		if(password_verify($password,$user->password)){
			$message="authorized";
			echo $message;
		}else{
			$message="failed";
			echo $message;
		}
	}

  public function actionCalendar(){
    $this->render('calendar');
  }

  public function actionLoadCalendarData(){
    $events=Dashboard::populateLoanRepaymentsDateSchedule();
  }

	public function actionLoansReleased(){
		if(isset($_POST['start_date']) && isset($_POST['end_date'])){
	      $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $formattedStartDate=date('jS F Y',strtotime($start_date));
        $formattedEndDate=date('jS F Y',strtotime($end_date));
        $title  = "Duration: $formattedStartDate to $formattedEndDate";
        $container_name = 'amountReleasedDiv';
        $amountReleasedChart=DashboardCharts::displayTotalLoanAmountReleasedChart($start_date,$end_date,$title,$container_name);
        echo $amountReleasedChart;
		}else{
			  $start_date=date('Y-m-d');
        $end_date=date('Y-m-d');
        $formattedStartDate=date('jS F Y',strtotime($start_date));
        $formattedEndDate=date('jS F Y',strtotime($end_date));
        $title  = "Duration: $formattedStartDate to $formattedEndDate";
        $container_name = 'amountReleasedDiv';
        $amountReleasedChart=DashboardCharts::displayTotalLoanAmountReleasedChart($start_date,$end_date,$title,$container_name);
        echo $amountReleasedChart;
		}
	}

	public function actionLoanCollections(){
		if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'amountCollectedDiv';
          $amountCollectedChart=DashboardCharts::displayTotalAmountCollectedChart($start_date,$end_date,$title,$container_name);
          echo $amountCollectedChart;
		}else{
		  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'amountCollectedDiv';
          $amountCollectedChart=DashboardCharts::displayTotalAmountCollectedChart($start_date,$end_date,$title,$container_name);
          echo $amountCollectedChart;
		}
	}

	public function actionOutstandingPrincipalBalance(){
		if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'principalOutstandingDiv';
          $principalOutstaningChart=DashboardCharts::displayTotalPrincipalOutstanding($start_date,$end_date,$title,$container_name);
          echo $principalOutstaningChart;
		}else{
		  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'principalOutstandingDiv';
          $principalOutstaningChart=DashboardCharts::displayTotalPrincipalOutstanding($start_date,$end_date,$title,$container_name);
          echo $principalOutstaningChart;
		}
	}

	public function actionPrincipalDueVersusCollections(){
	  if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'principalDueCollectionsDiv';
          $principalOutstandingChart=DashboardCharts::getPrincipalDueVersusCollectionsChart($start_date,$end_date,$title,$container_name);
          echo $principalOutstandingChart;
      }else{
      	  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'principalDueCollectionsDiv';
          $principalOutstandingChart=DashboardCharts::getPrincipalDueVersusCollectionsChart($start_date,$end_date,$title,$container_name);
          echo $principalOutstandingChart;
      }
	}

	public function actionInterestDueVersusCollections(){
		if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'interestDueCollectionsDiv';
          $principalOutstandingChart=DashboardCharts::getInterestDueVersusCollectionsChart($start_date,$end_date,$title,$container_name);
          echo $principalOutstandingChart;
      }else{
      	  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'interestDueCollectionsDiv';
          $principalOutstandingChart=DashboardCharts::getInterestDueVersusCollectionsChart($start_date,$end_date,$title,$container_name);
          echo $principalOutstandingChart;
      }
	}

	public function actionNumberLoansReleased(){
	  if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'loansReleasedDiv';
          $loansReleasedChart=DashboardCharts::displayTotalLoansReleasedChart($start_date,$end_date,$title,$container_name);
          echo $loansReleasedChart;
      }else{
      	  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'loansReleasedDiv';
          $loansReleasedChart=DashboardCharts::displayTotalLoansReleasedChart($start_date,$end_date,$title,$container_name);
          echo $loansReleasedChart;
      }
	}

  public function actionTotalFullyPaidLoans(){
    if(isset($_POST['start_date']) && isset($_POST['end_date'])){
      $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'totalFullyPaidLoans';
          $totalFullyPaidLoansChart=DashboardCharts::getTotalFullyPaidLoansChart($start_date,$end_date,$title,$container_name);
          echo $totalFullyPaidLoansChart;
      }else{
          $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'totalFullyPaidLoans';
          $totalFullyPaidLoansChart=DashboardCharts::getTotalFullyPaidLoansChart($start_date,$end_date,$title,$container_name);
          echo $totalFullyPaidLoansChart;
      }
  }

	public function actionNumberLoansCumulative(){
		if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'numberLoansCumulativeDiv';
          $loansReleasedChart=DashboardCharts::displayTotalLoansCumulativeReleasedChart($start_date,$end_date,$title,$container_name);
          echo $loansReleasedChart;
      }else{
      	  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'numberLoansCumulativeDiv';
          $loansReleasedChart=DashboardCharts::displayTotalLoansCumulativeReleasedChart($start_date,$end_date,$title,$container_name);
          echo $loansReleasedChart;
      }
	}
	public function actionNumberRepaymentsCollected(){
		if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'repaymentsCollectedDiv';
          $repaymentsCollectedChart=DashboardCharts::displayTotalCollectionsReceivedChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }else{
      	  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'repaymentsCollectedDiv';
          $repaymentsCollectedChart=DashboardCharts::displayTotalCollectionsReceivedChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }
	}

	public function actionLoanCollectionsVersusloansReleased(){
	  if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'loanCollectionvsreleased';
          $repaymentsCollectedChart=DashboardCharts::getLoanCollectionsVersusloansReleasedChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }else{
      	  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'loanCollectionvsreleased';
          $repaymentsCollectedChart=DashboardCharts::getLoanCollectionsVersusloansReleasedChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }
	}

	public function actionLoanCollectionsVersusDueLoans(){
	  if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		  $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'loanCollectionsVersusDueLoans';
          $repaymentsCollectedChart=DashboardCharts::getLoanCollectionsVersusloansDueChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }else{
      	  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'loanCollectionsVersusDueLoans';
          $repaymentsCollectedChart=DashboardCharts::getLoanCollectionsVersusloansDueChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }
	}

	public function actionFeesDueVersusCollections(){
	  if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		      $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'feesDueVersusCollections';
          $repaymentsCollectedChart=DashboardCharts::getFeesDueVersusCollectionsChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }else{
      	  $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'feesDueVersusCollections';
          $repaymentsCollectedChart=DashboardCharts::getFeesDueVersusCollectionsChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }
	}

  public function actionPenaltyDueVersusPenaltyCollections(){
    if(isset($_POST['start_date']) && isset($_POST['end_date'])){
          $start_date=$_POST['start_date'];
          $end_date=$_POST['end_date'];
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'penaltyDueVersusPenaltyCollections';
          $repaymentsCollectedChart=DashboardCharts::getPenaltyDueVersusPenaltyCollectionsChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }else{
          $start_date=date('Y-m-d');
          $end_date=date('Y-m-d');
          $formattedStartDate=date('jS F Y',strtotime($start_date));
          $formattedEndDate=date('jS F Y',strtotime($end_date));
          $title  = "Duration: $formattedStartDate to $formattedEndDate";
          $container_name = 'penaltyDueVersusPenaltyCollections';
          $repaymentsCollectedChart=DashboardCharts::getPenaltyDueVersusPenaltyCollectionsChart($start_date,$end_date,$title,$container_name);
          echo $repaymentsCollectedChart;
      }
  }

  /**********

    ANALYTICS

  ***********************/
  public function actionCustomerGrowth(){
      if(Navigation::checkIfAuthorized(233) == 1){
        $this->render('customerGrowth',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access the customer growth trend dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadCustomerGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Customer growth analytics and statistics tabulation not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getCustomerGrowthTabulation($branch,$staff,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadCustomerGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Customer growth analytics and statistics graph not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getCustomerGrowthTabulation($branch,$staff,$startDate,$endDate);
    $openingCount= Analytics::getOpeningCustomerCount($branch,$staff,$startDate);
    $customers   = Analytics::getCustomerTotals($branch,$staff,$startDate,$endDate);
    $cTitle      = 'Month on Month (MoM) Customer Base Growth';
    $cHolder     = 'customer-growth-graph-container';
    if($tabulation === 0){
      $growthGraph = $emptyGraph;
    }else{
      $growthGraph = Analytics::getCustomerGrowthBarGraph($openingCount,$customers,$cTitle,$cHolder);
    }
    echo $growthGraph;

  }

  public function actionSavings(){
    if(Navigation::checkIfAuthorized(232) == 1){
      $this->render('savings',array('branches'=>Reports::getAllBranches()));
    }else{
      CommonFunctions::setFlashMessage('danger',"You are not allowed to access the savings performance dashboard.");
      $this->redirect(array('dashboard/default'));
    }
  }

  public function actionLoadSavingsGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Savings analytics and statistics tabulation not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getSavingsTrendTabulation($branch,$staff,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadSavingsGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Savings analytics and statistics graph not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getSavingsTrendTabulation($branch,$staff,$startDate,$endDate);
    $openingCount= Analytics::getOpeningSavingTotal($branch,$staff,$startDate);
    $savings     = Analytics::getSavingTotals($branch,$staff,$startDate,$endDate);
    $cTitle      = 'Month on Month (MoM) Total Savings Trend';
    $cHolder     = 'savings-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getSavingsGrowthLineGraph($openingCount,$savings,$cTitle,$cHolder,$branch,$staff);
    }
    echo $graph;

  }

  public function actionTurnovers(){
    if(Navigation::checkIfAuthorized(237) == 1){
      $this->render('turnovers',array('branches'=>Reports::getAllBranches()));
    }else{
      CommonFunctions::setFlashMessage('danger',"You are not allowed to access the turnovers dashboard.");
      $this->redirect(array('dashboard/default'));
    }
  }

  public function actionLoadTurnoversGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Turnovers analytics and statistics tabulation not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getTurnoversTrendTabulation($branch,$staff,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadTurnoversGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Turnovers analytics and statistics graph not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getTurnoversTrendTabulation($branch,$staff,$startDate,$endDate);
    $turnovers   = Analytics::getTurnoverTotals($branch,$staff,$startDate,$endDate);
    $cTitle      = 'Month on Month (MoM) Total Turnover Trend';
    $cHolder     = 'turnovers-growth-graph-container';
    if($tabulation === 0){
      $growthGraph = $emptyGraph;
    }else{
      $growthGraph = Analytics::getTurnoversGrowthLineGraph($turnovers,$cTitle,$cHolder);
    }
    echo $growthGraph;

  }

  public function actionDisbursedPaidLoans(){
      if(Navigation::checkIfAuthorized(239) == 1){
        $this->render('disbursedPaidLoans',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access the disbursed vs paid loans dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadDisbursedPaidLoansGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Disbursed Loans analytics and statistics tabulation not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getDisbursedVsPaidLoansTrendTabulation($branch,$staff,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadDisbursedPaidLoansGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Turnovers analytics and statistics graph not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getDisbursedVsPaidLoansTrendTabulation($branch,$staff,$startDate,$endDate);
    $disbursed   = Analytics::getDisbursedTotals($branch,$staff,$startDate,$endDate);
    $cTitle      = 'Month on Month (MoM) Disbursed Vs Paid Loans Trend';
    $cHolder     = 'disbursedPaid-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getDisbursedVsPaidLoansGrowthLineGraph($disbursed,$cTitle,$cHolder,$branch,$staff);
    }
    echo $graph;
  }

  public function actionInterestGeneratedPaid(){
      if(Navigation::checkIfAuthorized(232) == 1){
        $this->render('interestGeneratedPaid',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access the paid interests dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadInterestGeneratedPaidGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Interest Generated analytics and statistics tabulation not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getInterestGeneratedVsPaidTrendTabulation($branch,$staff,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadInterestGeneratedPaidGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Interest Generated analytics and statistics graph not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getInterestGeneratedVsPaidTrendTabulation($branch,$staff,$startDate,$endDate);
    $disbursed   = Analytics::getInterestGeneratedTotals($branch,$staff,$startDate,$endDate);
    $cTitle      = 'Month on Month (MoM) Interest Generated Vs Paid';
    $cHolder     = 'interestGeneratedPaid-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph =Analytics::getInterestGeneratedVsPaidGrowthLineGraph($disbursed,$cTitle,$cHolder,$branch,$staff);
    }
    echo $graph;
  }

  public function actionLoanPerformance(){
    if(Navigation::checkIfAuthorized(230) == 1){
      $this->render('loanPerformance',array('branches'=>Reports::getAllBranches()));
    }else{
      CommonFunctions::setFlashMessage('danger',"You are not allowed to access the loan performance dashboard.");
      $this->redirect(array('dashboard/default'));
    }
  }

  public function actionLoadLoanPerformanceGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Loan performance statistics tabulation not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getLoanPerformanceTrendTabulation($branch,$staff,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadLoanPerformanceGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Loan performance statistics graph not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getLoanPerformanceTrendTabulation($branch,$staff,$startDate,$endDate);
    $disbursed   = Analytics::getLoanPerformanceTotals($branch,$staff,$startDate,$endDate);
    $cTitle      = 'Month on Month (MoM) Loan Performance';
    $cHolder     = 'loanPerformance-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph =Analytics::getLoanPerformanceGrowthLineGraph($disbursed,$cTitle,$cHolder,$branch,$staff,$startDate);
    }
    echo $graph;
  }


  public function actionOverallCollectionPerformance(){
      if(Navigation::checkIfAuthorized(238) == 1){
        $this->render('overallCollectionPerformance',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access overall collection performance dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadOverallCollectionPerformanceGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Overall collections performance statistics tabulation not populated</p>".
                    "</div>";
    $tabulation= Analytics::getOverallCollectionPerformanceTrendTabulation($branch,$staff,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadOverallCollectionPerformanceGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Overall collections performance statistics graph not populated</p>".
                    "</div>";
    $tabulation= Analytics::getOverallCollectionPerformanceTrendTabulation($branch,$staff,$startDate,$endDate);
    $collections = Analytics::getOverallCollections($branch,$staff,$startDate,$endDate);
    $cTitle      = 'Month on Month (MoM) Overall Collections Performance';
    $cHolder     = 'OverallCollectionPerformance-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph =Analytics::getOverallCollectionPerformanceGrowthLineGraph($collections,$cTitle,$cHolder,$branch,$staff);
    }
    echo $graph;
  }

  public function actionRecoveredInterestPrinciple(){
      if(Navigation::checkIfAuthorized(236) == 1){
        $this->render('recoveredInterestPrinciple',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access the recovered interest and principle dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadRecoveredInterestPrincipleGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Recovered interests and principles statistics tabulation not populated</p>".
                    "</div>";
    $tabulation= Analytics::getRecoveredInterestPrincipleTrendTabulation($branch,$staff,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadRecoveredInterestPrincipleGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Recovered interests and principles statistics graph not populated</p>".
                    "</div>";
    $tabulation  = Analytics::getRecoveredInterestPrincipleTrendTabulation($branch,$staff,$startDate,$endDate);
    $collections = Analytics::getRecoveredInterestPrinciple($branch,$staff,$startDate,$endDate);
    $cTitle      = 'Month on Month (MoM) Recovered Interests and Principles';
    $cHolder     = 'recoveredInterestPrinciple-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getRecoveredInterestPrincipleGrowthLineGraph($collections,$cTitle,$cHolder,$branch,$staff);
    }
    echo $graph;
  }

  public function actionRiskCounts(){
      if(Navigation::checkIfAuthorized(240) == 1){
        $this->render('riskCounts',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access risk counts dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadRiskCountsGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Loan Accounts risk classification statistics tabulation not populated</p>".
                    "</div>";
    $disbursements = Analytics::getRiskCounts($branch,$staff,$startDate,$endDate);
    $tabulation    = Analytics::generateRiskCountsTabulation($disbursements,$branch,$staff,$startDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadRiskCountsGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Total Loan Accounts Risk classification statistics graph not populated</p>".
                    "</div>";
    $disbursements = Analytics::getRiskCounts($branch,$staff,$startDate,$endDate);
    $tabulation    = Analytics::generateRiskCountsTabulation($disbursements,$branch,$staff,$startDate);
    $cTitle        = 'Month on Month (MoM) Risk Classification: Total Loan Counts';
    $cHolder       = 'RiskCounts-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getRiskCountsGrowthLineGraph($disbursements,$cTitle,$cHolder,$branch,$staff,$startDate);
    }
    echo $graph;
  }

  public function actionRiskAmounts(){
      if(Navigation::checkIfAuthorized(228) == 1){
        $this->render('riskAmounts',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access the risk amounts dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadRiskAmountsGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Amount disbursed risk classification statistics tabulation not populated</p>".
                    "</div>";
    $disbursements = Analytics::getRiskAmounts($branch,$staff,$startDate,$endDate);
    $tabulation    = Analytics::generateRiskAmountsTabulation($disbursements,$branch,$staff,$startDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadRiskAmountsGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Amount disbursed risk classification statistics graph not populated</p>".
                    "</div>";
    $disbursements = Analytics::getRiskAmounts($branch,$staff,$startDate,$endDate);
    $tabulation    = Analytics::generateRiskAmountsTabulation($disbursements,$branch,$staff,$startDate);
    $cTitle        = 'Month on Month (MoM) Risk Classification: Amount Disbursed ';
    $cHolder       = 'RiskAmounts-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getRiskAmountsGrowthLineGraph($disbursements,$cTitle,$cHolder,$branch,$staff,$startDate);
    }
    echo $graph;
  }

  public function actionAssetReturns(){
      if(Navigation::checkIfAuthorized(234) == 1){
        $this->render('assetReturns',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access asset returns dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadAssetReturnsGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Return on assets statistics tabulation not populated</p>".
                    "</div>";
    $assets = Analytics::getLoanPerformanceTotals($branch,$staff,$startDate,$endDate);
    $tabulation = Analytics::generateAssetReturnsTabulation($assets,$branch,$staff,$startDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadAssetReturnsGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Return on assets statistics graph not populated</p>".
                    "</div>";
    $assets = Analytics::getLoanPerformanceTotals($branch,$staff,$startDate,$endDate);
    $tabulation    = Analytics::generateAssetReturnsTabulation($assets,$branch,$staff,$startDate);
    $cTitle        = 'Month on Month (MoM) Return on Assets ';
    $cHolder       = 'RiskAmounts-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getAssetReturnsGrowthLineGraph($assets,$cTitle,$cHolder,$branch,$staff,$startDate);
    }
    echo $graph;
  }

  public function actionAssetQuality(){
      if(Navigation::checkIfAuthorized(235) == 1){
        $this->render('assetQuality',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access tasset quality dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadAssetQualityGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Assets quality statistics tabulation not populated</p>".
                    "</div>";
    $assets = Analytics::getLoanPerformanceTotals($branch,$staff,$startDate,$endDate);
    $tabulation = Analytics::generateAssetQualityTabulation($assets,$branch,$staff,$startDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadAssetQualityGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Assets quality statistics graph not populated</p>".
                    "</div>";
    $assets        = Analytics::getLoanPerformanceTotals($branch,$staff,$startDate,$endDate);
    $tabulation    = Analytics::generateAssetQualityTabulation($assets,$branch,$staff,$startDate);
    $cTitle        = 'Month on Month (MoM) Assets Quality ';
    $cHolder       = 'AssetQuality-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getAssetQualityGrowthLineGraph($assets,$cTitle,$cHolder,$branch,$staff,$startDate);
    }
    echo $graph;
  }

  public function actionIncomeExpenses(){
      if(Navigation::checkIfAuthorized(229) == 1){
        $this->render('incomeExpenses',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access income and expense dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadIncomeExpensesGrowthAnalytics(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    //Analytics Table
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Income and expenses statistics tabulation not populated</p>".
                    "</div>";
    $assets     = Analytics::getLoanPerformanceTotals($branch,$staff,$startDate,$endDate);
    $tabulation = Analytics::generateIncomeExpensesTabulation($assets,$branch,$staff);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadIncomeExpensesGrowthGraph(){
    $branch    = (int)$_POST['branch'];
    $staff     = (int)$_POST['staff'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Income and expenses statistics graph not populated</p>".
                    "</div>";
    $assets        = Analytics::getAssetReturns($branch,$staff,$startDate,$endDate);
    $tabulation    = Analytics::generateIncomeExpensesTabulation($assets,$branch,$staff);
    $cTitle        = 'Month on Month (MoM) Income and Expenses';
    $cHolder       = 'IncomeExpenses-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getIncomeExpensesGrowthLineGraph($assets,$cTitle,$cHolder,$branch,$staff);
    }
    echo $graph;
  }

  public function actionProfitLoss(){
      if(Navigation::checkIfAuthorized(231) == 1){
        $this->render('profitLoss',array('branches'=>Reports::getAllBranches()));
      }else{
        CommonFunctions::setFlashMessage('danger',"You are not allowed to access profit and loss dashboard.");
        $this->redirect(array('dashboard/default'));
      }
  }

  public function actionLoadProfitLossGrowthAnalytics(){
    $branch      = (int)$_POST['branch'];
    $staff       = (int)$_POST['staff'];
    $startDate   = $_POST['start_date'];
    $endDate     = $_POST['end_date'];
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Table</h4><hr class='common_rule'/>".
                      "<p class='error'>Profit and loss statistics tabulation not populated</p>".
                    "</div>";
    $assets     = Analytics::getLoanPerformanceTotals($branch,$staff,$startDate,$endDate);
    $tabulation = Analytics::generateProfitLossTabulation($assets,$branch,$staff);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadProfitLossGrowthGraph(){
    $branch      = (int)$_POST['branch'];
    $staff       = (int)$_POST['staff'];
    $startDate   = $_POST['start_date'];
    $endDate     = $_POST['end_date'];
    $emptyGraph  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Growth Trend Graph</h4><hr class='common_rule'/>".
                      "<p class='error'>Profit and loss statistics graph not populated</p>".
                    "</div>";
    $assets = Analytics::getLoanPerformanceTotals($branch,$staff,$startDate,$endDate);
    $tabulation    = Analytics::generateProfitLossTabulation($assets,$branch,$staff);
    $cTitle        = 'Month on Month (MoM) Profit and Loss';
    $cHolder       = 'ProfitLoss-growth-graph-container';
    if($tabulation === 0){
      $graph = $emptyGraph;
    }else{
      $graph = Analytics::getProfitLossGrowthLineGraph($assets,$cTitle,$cHolder,$branch,$staff);
    }
    echo $graph;
  }

  public function actionComments(){
    if(Navigation::checkIfAuthorized(257) == 1){
      $this->render('comments',array('branches'=>Reports::getAllBranches(),'types'=>LoanApplication::getAllCommentTypes()));
    }else{
      CommonFunctions::setFlashMessage('danger',"You are not allowed to access comments dashboard.");
      $this->redirect(array('dashboard/default'));
    }
  }

  public function actionLoadBranchCommentsDashboard(){
    $branch      = (int)$_POST['branch'];
    $startDate   = $_POST['start_date'];
    $endDate     = $_POST['end_date'];
    $cType       = (int)$_POST['comment_type'];
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Branch Comments</h4><hr class='common_rule'/>".
                      "<p class='error'>Branch comments tabulation not populated</p>".
                    "</div>";
    $tabulation = Analytics::getBranchCommentsTabulation($branch,$cType,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }

  public function actionLoadStaffCommentsDashboard(){
    $branch      = (int)$_POST['branch'];
    $staff       = (int)$_POST['staff'];
    $startDate   = $_POST['start_date'];
    $endDate     = $_POST['end_date'];
    $cType       = (int)$_POST['comment_type'];
    $growthArray = [];
    $emptyTable  = "<div class='col-md-8 col-lg-8 col-sm-12 analytics-empty'>".
                      "<h4>Staff Comments</h4><hr class='common_rule'/>".
                      "<p class='error'>Staff comments tabulation not populated or no staff has been configured to be listed on the comments dashboard</p>".
                    "</div>";
    $tabulation = Analytics::getStaffCommentsTabulation($branch,$staff,$cType,$startDate,$endDate);
    if($tabulation === 0){
      $growthArray['tabulation'] = $emptyTable;
    }else{
      $growthArray['tabulation'] = $tabulation;
    }
    echo json_encode($growthArray);
  }
}