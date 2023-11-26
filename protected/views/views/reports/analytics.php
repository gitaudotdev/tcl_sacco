<?php
/* @var $this DashboardController */
$this->pageTitle=Yii::app()->name . ' - Microfinance Status Dashboard';
$this->breadcrumbs=array(
  'Analytics'=>array('reports/analytics'),
);
?>
<style type="text/css">
    .card-raised{
        border-radius: 0px !important;
    }
    .card-raised-modified{
        border-radius: 0px !important;
        border-top:3px solid #00c0ef !important;
    }
    .chart-area{
        margin-top: -5% !important;
    }
    .enhanced{
        font-size: 14px !important;
        font-weight: normal !important;
    }
    .modified{
     padding:10px 10px !important;
    }
    .avg_tenure{
        background-color: #00c0ef !important;
        color:#fff !important;
        border:1px solid #00c0ef !important;
    }
    .avg_interest{
        background-color: #f39c12 !important;
        color:#fff !important; 
        border:1px solid #f39c12 !important;
    }
    .card_stats{
        font-size:2.8em !important;
    }
    .card_stats_title{
        font-size:1.2em !important;
        font-weight: bold !important;
    }
    .recovery_rate{
        background-color: #00a65a !important;
        color:#fff !important;
        border:1px solid #00a65a !important;
    }
    .recovery_rate_all{
        background-color: #f39c12 !important;
        color:#fff !important;
        border:1px solid #f39c12 !important;
    }
    .enhanced_card_title{
        text-align: left !important;
        margin-top:0% !important;
    }
    #amountReleasedDiv{
        width: 100% !important;
    }
    #date_error{
        margin-left: 2% !important;
    }
    .loadingData{
        display: none;
        margin:-9% 0% 0% 45% !important;
    }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats">
            <div class="card-body">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                      <div class="chart-area" style="padding:50px 20px 20px 20px !important;">
                        <form>
                            <div class="row">
                                 <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <select name="branch" id="branch" class="form-control selectpicker" required="required">
                                            <?php if(Yii::app()->user->user_level === '0' ):?>
                                            <option value="0">-- BRANCHES --</option>
                                                <?php if(!empty($branches)):?>
                                                    <?php foreach($branches as $branch):?>
                                                        <option value="<?=$branch->branch_id;?>"><?=$branch->name;?></option>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                            <?php else:?>
                                                <?php if(!empty($branches)):?>
                                                    <?php foreach($branches as $branch):?>
                                                        <option value="<?=$branch->branch_id;?>"><?=$branch->name;?></option>
                                                    <?php endforeach;?>
                                                <?php endif;?>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="start_date" placeholder="Start Date" required="required">
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="end_date" placeholder="End Date" required="required">
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12" style="margin-top: -1.6% !important;">
                                    <div class="form-group">
                                        <button type="button" id="generate_chart_cmd" class="btn btn-primary"> <i class="now-ui-icons business_chart-bar-32"></i> Load Statistics</button>
                                    </div>
                                </div>
                                <span class="error" id="date_error">End Date must be greater or equal to Start Date</span>
                            </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Loans Released</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="loansReleased"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Loan Collections</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                   <div class="chart-area" id="loanCollections"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Loan Collections Vs Due Loans</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="loanCollectionsVersusDueLoans"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Loan Collections Vs Loans Released</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="loanCollectionsVersusloansReleased"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Outstanding Principal Balance</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="outstandingPrincipalBalance"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Principal - Due Vs Collections</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="principalDueVersusCollections"></div>
                </div>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Interest- Due Vs Collections</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="interestDueVersusCollections"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Fees - Due Vs Collections</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="feesDueVersusCollections"></div>
                </div>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Penalty- Due Vs Collections</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="penaltyDueVersusPenaltyCollections"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Number of Loans(Cumulative)</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="numberLoansCumulative"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Number of Loans Released</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="numberLoansReleased"></div>
                </div>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Number of Repayments Collected</h4>
                </div>
                <div class="card-body">
                    <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="numberRepaymentsCollected"></div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Number of Fully Paid Loans</h4>
                </div>
                <div class="card-body">
                     <div class="loadingData">
                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                    </div>
                  <div class="chart-area" id="totalFullyPaidLoans"></div>
                </div>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Open Loan Status</h4>
                </div>
                <div class="card-body">
                  <div class="chart-area">
                     <?php 
                        $array=Dashboard::getLoanAccountsStatusCount();
                        if(!empty($array)){
                            $title  = "Open Loans Status";
                            $subtitle =  "Source: Sacco System";
                            $container_name = 'openLoanStatusDiv';
                            $openLoanStatusChart = DashboardCharts::getLoanAccountsStatusPieChart($array,$title,$subtitle,$container_name);
                        }
                     ?>
                  </div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-body recovery_rate_all">
                  <h4 class="enhanced enhanced_card_title card_stats_title">
                  Rate of Recovery (All Loans)</h4>
                  <div class="chart-area">
                    <br>
                    <p>Percentage due amount paid for all loans until today</p>
                    <p class="card_stats"><?=DashboardCharts::getRateofRecoveryAllLoans();?></p>
                  </div>
                </div>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-body recovery_rate">
                  <h4 class="enhanced enhanced_card_title card_stats_title">Rate of Recovery (Open Loans)</h4>
                  <div class="chart-area">
                    <br>
                    <p>Percentage due amount paid for open loans until today</p>
                    <p class="card_stats"><?=DashboardCharts::getRateofRecoveryOpenLoans();?></p>
                  </div>
                </div>
            </div>  
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card-header">
                  <h4 class="card-title enhanced enhanced_card_title">Rate of Return %(All Time)</h4>
                </div>
                <div class="card-body">
                  <div class="chart-area">
                    <div class="progress-container progress-success">
                        <span class="progress-badge">All Loans</span>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow=" <?=DashboardCharts::getRateProgressAllLoans();?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=DashboardCharts::getRateProgressAllLoans();?>%;">
                                <span class="progress-value"><?=DashboardCharts::getRateProgressAllLoans();?>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="progress-container progress-info">
                        <span class="progress-badge">Open Loans</span>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=DashboardCharts::getRateProgressOpenLoans();?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=DashboardCharts::getRateProgressOpenLoans();?>%;">
                                <span class="progress-value"><?=DashboardCharts::getRateProgressOpenLoans();?>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="progress-container progress-warning">
                        <span class="progress-badge">Fully Paid Loans</span>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=DashboardCharts::getRateProgressFullyPaidLoans();?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=DashboardCharts::getRateProgressFullyPaidLoans();?>%;">
                                <span class="progress-value"><?=DashboardCharts::getRateProgressFullyPaidLoans();?>%</span>
                            </div>
                        </div>
                    </div>
                    <div class="progress-container progress-danger">
                        <span class="progress-badge">Default Loans</span>
                        <div class="progress">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=DashboardCharts::getRateProgressDefaultLoans();?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=DashboardCharts::getRateProgressDefaultLoans();?>%;">
                                <span class="progress-value"><?=DashboardCharts::getRateProgressDefaultLoans();?>%</span>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
            <?php if(Yii::app()->user->user_level!=='3'):?>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card-body">
                  <h4 class="enhanced enhanced_card_title">Active Male/Female Members</h4>
                  <div class="chart-area">
                    <?php 
                        $array=Dashboard::getBorrowerGenderCount();
                        if(!empty($array)){
                            $title  = "Active Male/ Female Percent";
                            $subtitle =  "Source: Sacco System";
                            $container_name = 'activemalefemale';
                            $activemalefemaleChart = DashboardCharts::getBorrowersGenderStatusPieChart($array,$title,$subtitle,$container_name);
                        }
                     ?>
                  </div>
                </div>
            </div> 
            <?php endif;?>     
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="col-lg-6 col-md-6 col-sm-12 modified"">
                <div class="card-body avg_tenure">
                  <h4 class="card-title enhanced enhanced_card_title card_stats_title">
                  Average Loan Tenure (All Time)
                  </h4>
                  <div class="chart-area">
                    <br>
                    <p>Average number of days for loans to be fully paid</p>
                    <p class="card_stats"><?=Dashboard::getAverageLoanTenureTime();?> Days</p>
                  </div>
                </div>
            </div>  
            <div class="col-lg-6 col-md-6 col-sm-12 modified">
                <div class="card-body avg_interest">
                  <h4 class="enhanced enhanced_card_title card_stats_title">Average Interest Rate</h4>
                  <div class="chart-area">
                    <br>
                    <p>Total Interest Receivables/Total Principal Released</p>
                    <p class="card_stats"><?=DashboardCharts::getAverageInterestRateAllTime();?></p>
                  </div>
                </div>
            </div>  
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        InitializeCharts();
        $('.error').hide();
        $("#generate_chart_cmd").click(function(){
          $('.error').hide();
          var startDate = $("input#start_date").val();
          var endDate = $("input#end_date").val(); 
          var branch= $("#branch option:selected").val();
          if(endDate >= startDate && startDate != '' && endDate != ''){
            GenerateLoansReleasedChart(startDate,endDate);
            GenerateLoanCollectionsChart(startDate,endDate);
            GenerateOustandingPrincipalBalanceChart(startDate,endDate);
            GeneratePrincipalDueVersusCollectionsChart(startDate,endDate);
            GenerateNumberLoansReleasedChart(startDate,endDate);
            GenerateNumberLoansCumulativeReleasedChart(startDate,endDate);
            GenerateNumberRepaymentsCollectedChart(startDate,endDate);
            GenerateInterestDueVersusCollectionsChart(startDate,endDate);
            GenerateLoanCollectionsVersusLoansReleasedChart(startDate,endDate);
            GenerateLoanCollectionsVersusLoansDueChart(startDate,endDate);
            GenerateFeesDueVersusCollectionsChart(startDate,endDate);
            GeneratePenaltyDueVersusCollectionsChart(startDate,endDate);
            GeneratePenaltyDueVersusCollectionsChart(startDate,endDate);
            GenerateTotalFullyPaidLoansChart(startDate,endDate);
          }else{
            $("span#date_error").show();
            $("input#end_date").focus();
            return false;
          }
        });
    });

    function GenerateLoansReleasedChart(startDate,endDate){
      document.getElementById('loansReleased').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loansReleased');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#loansReleased').html(response);
        }
      });
      return false;
    }

    function GenerateLoanCollectionsChart(startDate,endDate){
      document.getElementById('loanCollections').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loanCollections');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#loanCollections').html(response);
        }
      });
      return false;
    }

    function GenerateOustandingPrincipalBalanceChart(startDate,endDate){
      document.getElementById('outstandingPrincipalBalance').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/outstandingPrincipalBalance');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#outstandingPrincipalBalance').html(response);
        }
      });
      return false;
    }

    function GeneratePrincipalDueVersusCollectionsChart(startDate,endDate){
      document.getElementById('principalDueVersusCollections').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/principalDueVersusCollections');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#principalDueVersusCollections').html(response);
        }
      });
      return false;
    }

    function GenerateInterestDueVersusCollectionsChart(startDate,endDate){
      document.getElementById('interestDueVersusCollections').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/interestDueVersusCollections');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#interestDueVersusCollections').html(response);
        }
      });
      return false;
    }

    function GenerateNumberLoansReleasedChart(startDate,endDate){
      document.getElementById('numberLoansReleased').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/numberLoansReleased');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#numberLoansReleased').html(response);
        }
      });
      return false;
    }

    function GenerateNumberLoansCumulativeReleasedChart(startDate,endDate){
      document.getElementById('numberLoansCumulative').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/numberLoansCumulative');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#numberLoansCumulative').html(response);
        }
      });
      return false;
    }

    function GenerateNumberRepaymentsCollectedChart(startDate,endDate){
      document.getElementById('numberRepaymentsCollected').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/numberRepaymentsCollected');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#numberRepaymentsCollected').html(response);
        }
      });
      return false;
    }

    function GenerateLoanCollectionsVersusLoansReleasedChart(startDate,endDate){
      document.getElementById('loanCollectionsVersusloansReleased').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loanCollectionsVersusloansReleased');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#loanCollectionsVersusloansReleased').html(response);
        }
      });
      return false;
    }

    function GenerateLoanCollectionsVersusLoansDueChart(startDate,endDate){
      document.getElementById('loanCollectionsVersusDueLoans').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loanCollectionsVersusDueLoans');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#loanCollectionsVersusDueLoans').html(response);
        }
      });
      return false;
    }

    function GenerateFeesDueVersusCollectionsChart(startDate,endDate){
      document.getElementById('feesDueVersusCollections').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/feesDueVersusCollections');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#feesDueVersusCollections').html(response);
        }
      });
      return false;
    }

    function GeneratePenaltyDueVersusCollectionsChart(startDate,endDate){
      document.getElementById('penaltyDueVersusPenaltyCollections').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/penaltyDueVersusPenaltyCollections');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#penaltyDueVersusPenaltyCollections').html(response);
        }
      });
      return false;
    }

    function GenerateTotalFullyPaidLoansChart(startDate,endDate){
      document.getElementById('totalFullyPaidLoans').innerHTML = "";
      $('.loadingData').show();
      var dataString = 'start_date='+ startDate + '&end_date=' + endDate;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/totalFullyPaidLoans');?>",
        data: dataString,
        success: function(response){
            $('.loadingData').hide();
            $('#totalFullyPaidLoans').html(response);
        }
      });
      return false;
    }

    function InitializeCharts(){
        var userLevel="<?=Yii::app()->user->user_level;?>";
        var branch;
        if(userLevel  === '0'){
            branch = 0;
        }else{
            branch = "<?=Yii::app()->user->user_branch;?>";
        }
        var date = new Date();
        var endDate=new Date(date.getFullYear(), date.getMonth() + 1, 0);
        var formattedEndDate=formatDate(endDate);
        var startDate=new Date(date.getFullYear(), date.getMonth(), 1);
        var formattedStartDate=formatDate(startDate);
        GenerateLoansReleasedChart(formattedStartDate,formattedEndDate);
        GenerateLoanCollectionsChart(formattedStartDate,formattedEndDate);
        GenerateOustandingPrincipalBalanceChart(formattedStartDate,formattedEndDate);
        GeneratePrincipalDueVersusCollectionsChart(formattedStartDate,formattedEndDate);
        GenerateInterestDueVersusCollectionsChart(formattedStartDate,formattedEndDate);
        GenerateNumberLoansReleasedChart(formattedStartDate,formattedEndDate);
        GenerateNumberLoansCumulativeReleasedChart(formattedStartDate,formattedEndDate);
        GenerateNumberRepaymentsCollectedChart(formattedStartDate,formattedEndDate);
        GenerateLoanCollectionsVersusLoansReleasedChart(formattedStartDate,formattedEndDate);
        GenerateLoanCollectionsVersusLoansDueChart(formattedStartDate,formattedEndDate);
        GenerateFeesDueVersusCollectionsChart(formattedStartDate,formattedEndDate);
        GeneratePenaltyDueVersusCollectionsChart(formattedStartDate,formattedEndDate);
        GenerateTotalFullyPaidLoansChart(formattedStartDate,formattedEndDate);
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
</script>