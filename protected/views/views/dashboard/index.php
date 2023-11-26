<?php
$this->pageTitle=Yii::app()->name . ' - Overall Performance Data Analytics and Statistics';
$this->breadcrumbs=array(
  'Dashboard'=> array('index'),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats">
            <div class="card-body">
                <?php if(CommonFunctions::checkIfFlashMessageSet('success') === 1):?>
                <div class="col-lg-12 col-md-12 col-sm-12">
                  <?=CommonFunctions::displayFlashMessage('success');?>
                </div>
                <?php endif;?>
                <?php if(CommonFunctions::checkIfFlashMessageSet('info') === 1):?>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <?=CommonFunctions::displayFlashMessage('info');?>
                  </div>
                <?php endif;?>
                <?php if(CommonFunctions::checkIfFlashMessageSet('warning') === 1):?>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <?=CommonFunctions::displayFlashMessage('warning');?>
                  </div>
                <?php endif;?>
                <?php if(CommonFunctions::checkIfFlashMessageSet('danger') === 1):?>
                  <div class="col-lg-12 col-md-12 col-sm-12">
                    <?=CommonFunctions::displayFlashMessage('danger');?>
                  </div>
                <?php endif;?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                      <div class="chart-area" style="padding:50px 0px 20px 0px !important;">
                      <br>
                        <form>
                            <div class="row">
                                 <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <select name="branch" id="branch" class="form-control selectpicker" required="required">
                                            <option value="0">-- BRANCHES --</option>
                                            <?php if(!empty($branches)):?>
                                                <?php foreach($branches as $branch):?>
                                                    <option value="<?=$branch->branch_id;?>"><?=$branch->name;?></option>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                     <div class="form-group">
                                      <select class="selectpicker form-control-changed" name="staff" required="required" id="staff">
                                          <option value="0">-- MANAGERS --</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                     <div class="form-group">
                                      <select class="selectpicker form-control-changed" name="borrower" required="required" id="borrower">
                                          <option value="0">-- MEMBERS --</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <select class="selectpicker form-control-changed" id="showAll">
                                            <option value="not_defined">-- DISPLAY --</option>
                                            <option value="period">Defined Period</option>
                                            <option value="all">Show All</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="start_date" placeholder="Start Date" required="required">
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="end_date" placeholder="End Date" required="required">
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1 col-sm-12" style="margin-top: -1% !important">
                                    <div class="form-group">
                                        <button type="button" id="generate_chart_cmd" class="btn btn-primary pull-left" onclick="LoadStatisticsDashboard()"> <i class="now-ui-icons business_chart-bar-32"></i> Load Stats</button>
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
            <div class="card-body">
                <div class="row">
                  <div class="col-md-3 place-border">
                        <div class="statistics">
                            <div class="info">
                                <?php if(Yii::app()->user->user_level === '3'):?>
                                <a href="#">
                                <?php else:?>
                                <a href="<?=Yii::app()->createUrl('borrower/admin')?>">
                                <?php endif;?>
                                <div class="icon icon-primary">
                                    <i class="now-ui-icons users_circle-08"></i>
                                </div>
                                </a>
                                <h3 class="info-title enhanced">Members</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadingDataBorrowers" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadBorrowersCount"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  place-border">
                        <div class="statistics">
                            <div class="info">
                                <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                    <div class="icon icon-success">
                                        <i class="now-ui-icons objects_diamond"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Total Principal Released</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadTotalPrincipalReleasedBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadTotalPrincipalReleasedBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  place-border">
                        <div class="statistics">
                            <div class="info">
                                <?php if(Yii::app()->user->user_level != '3'):?>
                                <a href="<?=Yii::app()->createUrl('loanrepayments/admin')?>">
                                <?php else:?> 
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                <?php endif;?>
                                    <div class="icon icon-danger">
                                        <i class="now-ui-icons business_money-coins"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Total Collections</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadTotalCollectionsBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadTotalCollectionsBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="statistics">
                            <div class="info">
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                    <div class="icon icon-info">
                                        <i class="now-ui-icons education_agenda-bookmark"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Total Outstanding</h3>
                                <h6 class="stats-title enhanced_title2">
                                     <div class="loadTotalOutstandingBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadTotalOutstandingBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-3 place-border">
                        <div class="statistics">
                            <div class="info">
                                <?php if(Yii::app()->user->user_level != '3'):?>
                                <a href="<?=Yii::app()->createUrl('loanrepayments/admin')?>">
                                <?php else:?>
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                <?php endif;?>
                                    <div class="icon icon-primary">
                                        <i class="now-ui-icons ui-2_time-alarm"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Principal Outstanding</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadPrincipalOutstandingBranchdate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadPrincipalOutstandingBranchdate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3  place-border">
                        <div class="statistics">
                            <div class="info">
                                  <?php if(Yii::app()->user->user_level != '3'):?>
                                <a href="<?=Yii::app()->createUrl('loanrepayments/admin')?>">
                                <?php else:?>
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                <?php endif;?>
                                    <div class="icon icon-success">
                                        <i class="now-ui-icons education_atom"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Interest Outstanding</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadInterestOutstandingBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadInterestOutstandingBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 place-border">
                        <div class="statistics">
                            <div class="info">
                                  <?php if(Yii::app()->user->user_level != '3'):?>
                                <a href="<?=Yii::app()->createUrl('loanrepayments/admin')?>">
                                <?php else:?>
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                <?php endif;?>
                                    <div class="icon icon-danger">
                                        <i class="now-ui-icons design_scissors"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Penalty Outstanding</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadPenaltyOutstandingBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadPenaltyOutstandingBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="statistics">
                            <div class="info">
                                <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                    <div class="icon icon-info">
                                        <i class="now-ui-icons loader_refresh"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Open Loans</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadOpenLoansBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadOpenLoansBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="card card-stats card-raised-modified">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3  place-border">
                        <div class="statistics">
                            <div class="info">
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                    <div class="icon icon-primary">
                                        <i class="now-ui-icons design_bullet-list-67"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Payment Counts</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadPaymentCountsBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadPaymentCountsBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 place-border">
                        <div class="statistics">
                            <div class="info">
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                    <div class="icon icon-success">
                                        <i class="now-ui-icons business_money-coins"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Savings</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadSavingsBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadSavingsBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 place-border">
                        <div class="statistics">
                            <div class="info">
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                    <div class="icon icon-danger">
                                        <i class="now-ui-icons business_chart-bar-32"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Accrued Savings</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadAccruedSavingsBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadAccruedSavingsBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="statistics">
                            <div class="info">
                                 <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                    <div class="icon icon-info">
                                        <i class="now-ui-icons business_bank"></i>
                                    </div>
                                </a>
                                <h3 class="info-title enhanced">Total Savings</h3>
                                <h6 class="stats-title enhanced_title2">
                                    <div class="loadTotalSavingsBranchDate" style="margin-top: 2.5% !important; margin-left: -5.5% !important;">
                                        <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
                                    </div>
                                    <div id="loadTotalSavingsBranchDate"></div>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
    $(function(){
        LoadManagers();
        LoadBorrowers();
        InitializeCharts();
        $('.error').hide();
        $('#branch').on('change', function(){
          if(this.value == '0'){
            LoadManagers();
            LoadBorrowers();
          }else{
            LoadBranchManagers(this.value);
            LoadBranchBorrowers(this.value);
          }
        });

        $('#staff').on('change', function() {
            if(this.value == '0'){
              LoadBorrowers();
            }else{
              LoadRelationManagerBorrowers(this.value);
            }
        });
    });

   function LoadStatisticsDashboard(){
      $('.error').hide();
      var startDate = $("input#start_date").val();
      var endDate = $("input#end_date").val(); 
      var branch= $("#branch option:selected").val();
      var staff = $('#staff option:selected').val();
      var borrower=$('#borrower option:selected').val();
      var showAll=$('#showAll option:selected').val();

      if(showAll == 'not_defined'){
        showAll = "period";
      }else{
        showAll=$('#showAll option:selected').val();
      }

      if(startDate == '' && endDate == ''){
        var date = new Date();
        var endingDate=new Date(date.getFullYear(), date.getMonth() + 1, 0);
        var formattedEndDate=formatDate(endingDate);
        endDate=formattedEndDate;
        var startingDate=new Date(date.getFullYear(),date.getMonth(), 1);
        var formattedStartDate=formatDate(startingDate);
        startDate=formattedStartDate;
        /********************************
         Savings Period Formatting
        ******************************/
        var savingsStartDate=new Date(2018,12,1);
        var formattedSavingsStartDate=formatDate(savingsStartDate);

      }else{
         startDate = $("input#start_date").val();
         endDate = $("input#end_date").val(); 
         var formattedSavingsStartDate=startDate;
      }
     LoadBorrowersCountBranchDate(branch,startDate,endDate,staff,borrower,showAll);
     LoadTotalPrincipalReleasedBranchDate(branch,startDate,endDate,staff,borrower,showAll);
     LoadTotalCollectionsBranchDate(branch,startDate,endDate,staff,borrower,showAll);
     LoadPrincipalOutstandingBranchdate(branch,startDate,endDate,staff,borrower,showAll);
     LoadInterestOutstandingBranchDate(branch,startDate,endDate,staff,borrower,showAll);
     LoadPenaltyOutstandingBranchDate(branch,startDate,endDate,staff,borrower,showAll);
     LoadTotalOutstandingBranchDate(branch,startDate,endDate,staff,borrower,showAll);
     LoadOpenLoansBranchDate(branch,startDate,endDate,staff,borrower,showAll);
     LoadPaymentCountsBranchDate(branch,startDate,endDate,staff,borrower,showAll);
     LoadSavingsBranchDate(branch,formattedSavingsStartDate,endDate,staff,borrower,showAll);
     LoadAccruedSavingsBranchDate(branch,formattedSavingsStartDate,endDate,staff,borrower,showAll);
     LoadTotalSavingsBranchDate(branch,formattedSavingsStartDate,endDate,staff,borrower,showAll);
   }

    function LoadManagers(){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
        success: function(response) {
          var staff = $("#staff");
          staff.empty();
          var option = "<option value='0'>-- MANAGERS --</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
          }
          staff.html(option);
        }
      });
    }

    function LoadBranchManagers(branch){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadBranchRelationManagers');?>",
        data:{'branch':branch},
        success: function(response) {
          var staff = $("#staff");
          staff.empty();
          var option = "<option value='0'>-- MANAGERS --</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
          }
          staff.html(option);
        }
      });
    }

    function LoadBorrowers(){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadBorrowers');?>",
        success: function(response) {
          var borrower = $("#borrower");
          borrower.empty();
          var option = "<option value='0'>-- MEMBERS --</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].borrowerID + "'>" + response[i].borrowerName + "</option>";
          }
          borrower.html(option);
        }
      });
    }

    function LoadBranchBorrowers(branch){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadBranchBorrowers');?>",
        data:{'branch':branch},
        success: function(response) {
          var borrower = $("#borrower");
          borrower.empty();
          var option = "<option value='0'>-- MEMBERS --</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].borrowerID + "'>" + response[i].borrowerName + "</option>";
          }
          borrower.html(option);
        }
      });
    }

    function LoadRelationManagerBorrowers(staff){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadRelationManagerBorrowers');?>",
        data:{'staff':staff},
        success: function(response) {
          var borrower = $("#borrower");
          borrower.empty();
          var option = "<option value='0'>-- MEMBERS --</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].borrowerID + "'>" + response[i].borrowerName + "</option>";
          }
          borrower.html(option);
        }
      });
    }

    function LoadBorrowersCountBranchDate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadingDataBorrowers').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+ '&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadBorrowersCount');?>",
        data: dataString,
        success: function(response){
            $('.loadingDataBorrowers').hide();
            document.getElementById('loadBorrowersCount').innerHTML = "";
            $('#loadBorrowersCount').html(response);
        }
      });
      return false;
    }


    function LoadTotalPrincipalReleasedBranchDate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadTotalPrincipalReleasedBranchDate').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadTotalPrincipalReleasedBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadTotalPrincipalReleasedBranchDate').hide();
            document.getElementById('loadTotalPrincipalReleasedBranchDate').innerHTML = "";
            $('#loadTotalPrincipalReleasedBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadTotalCollectionsBranchDate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadTotalCollectionsBranchDate').show();
       var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadTotalCollectionsBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadTotalCollectionsBranchDate').hide();
            document.getElementById('loadTotalCollectionsBranchDate').innerHTML = "";
            $('#loadTotalCollectionsBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadTotalOutstandingBranchDate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadTotalOutstandingBranchDate').show();
     var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadTotalOutstandingBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadTotalOutstandingBranchDate').hide();
            document.getElementById('loadTotalOutstandingBranchDate').innerHTML = "";
            $('#loadTotalOutstandingBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadPrincipalOutstandingBranchdate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadPrincipalOutstandingBranchdate').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadPrincipalOutstandingBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadPrincipalOutstandingBranchdate').hide();
            document.getElementById('loadPrincipalOutstandingBranchdate').innerHTML = "";
            $('#loadPrincipalOutstandingBranchdate').html(response);
        }
      });
      return false;
    }

    function LoadInterestOutstandingBranchDate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadInterestOutstandingBranchDate').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadInterestOutstandingBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadInterestOutstandingBranchDate').hide();
            document.getElementById('loadInterestOutstandingBranchDate').innerHTML = "";
            $('#loadInterestOutstandingBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadPenaltyOutstandingBranchDate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadPenaltyOutstandingBranchDate').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadPenaltyOutstandingBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadPenaltyOutstandingBranchDate').hide();
            document.getElementById('loadPenaltyOutstandingBranchDate').innerHTML = "";
            $('#loadPenaltyOutstandingBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadOpenLoansBranchDate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadOpenLoansBranchDate').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadOpenLoansBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadOpenLoansBranchDate').hide();
            document.getElementById('loadOpenLoansBranchDate').innerHTML = "";
            $('#loadOpenLoansBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadPaymentCountsBranchDate(branch,startDate,endDate,staff,borrower,showAll){
     $('.loadPaymentCountsBranchDate').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadPaymentCountsBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadPaymentCountsBranchDate').hide();
            document.getElementById('loadPaymentCountsBranchDate').innerHTML = "";
            $('#loadPaymentCountsBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadSavingsBranchDate(branch,startDate,endDate,staff,borrower,showAll){
      $('.loadSavingsBranchDate').show();
      var dataString ='branch='+branch+'&start_date='+startDate+'&end_date='+endDate+'&staff='+staff
      +'&borrower='+borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadSavingsBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadSavingsBranchDate').hide();
            document.getElementById('loadSavingsBranchDate').innerHTML = "";
            $('#loadSavingsBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadAccruedSavingsBranchDate(branch,startDate,endDate,staff,borrower,showAll){
        $('.loadAccruedSavingsBranchDate').show();
      var dataString ='branch='+branch+'&start_date='+startDate+'&end_date='+endDate+'&staff='+staff
      +'&borrower='+borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadAccruedSavingsBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadAccruedSavingsBranchDate').hide();
            document.getElementById('loadAccruedSavingsBranchDate').innerHTML = "";
            $('#loadAccruedSavingsBranchDate').html(response);
        }
      });
      return false;
    }

    function LoadTotalSavingsBranchDate(branch,startDate,endDate,staff,borrower,showAll){
        $('.loadTotalSavingsBranchDate').show();
      var dataString ='branch='+branch+'&start_date='+startDate+'&end_date='+endDate+'&staff='+staff
      +'&borrower='+borrower+'&showAll='+showAll;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadTotalSavingsBranchDate');?>",
        data: dataString,
        success: function(response){
            $('.loadTotalSavingsBranchDate').hide();
            document.getElementById('loadTotalSavingsBranchDate').innerHTML = "";
            $('#loadTotalSavingsBranchDate').html(response);
        }
      });
      return false;
    }


    function InitializeCharts(){
        var staff=0;
        var borrower=0;
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
        var SaccoStartDate=new Date(2000,8,1);
        var formattedSaccoStartDate=formatDate(SaccoStartDate);
        var showAll = "period";
        /********************************
         Savings Period Formatting
        ******************************/
        var savingsStartDate=new Date(2018,12,1);
        var formattedSavingsStartDate=formatDate(savingsStartDate);
        /***************************************
         CURRENT MONTH
        ****************************************/
        LoadTotalPrincipalReleasedBranchDate(branch,formattedStartDate,formattedEndDate,staff,borrower,showAll);
        LoadPaymentCountsBranchDate(branch,formattedStartDate,formattedEndDate,staff,borrower,showAll);
        LoadTotalCollectionsBranchDate(branch,formattedStartDate,formattedEndDate,staff,borrower,showAll);
        /***************************************
         SACCO INCEPTION
        ****************************************/
        LoadBorrowersCountBranchDate(branch,formattedSaccoStartDate,formattedEndDate,staff,borrower,showAll);
        LoadPrincipalOutstandingBranchdate(branch,formattedSaccoStartDate,formattedEndDate,staff,borrower,showAll);
        LoadInterestOutstandingBranchDate(branch,formattedSaccoStartDate,formattedEndDate,staff,borrower,showAll);
        LoadPenaltyOutstandingBranchDate(branch,formattedSaccoStartDate,formattedEndDate,staff,borrower,showAll);
        LoadTotalOutstandingBranchDate(branch,formattedSaccoStartDate,formattedEndDate,staff,borrower,showAll);
        LoadOpenLoansBranchDate(branch,formattedSaccoStartDate,formattedEndDate,staff,borrower,showAll);
        LoadSavingsBranchDate(branch,formattedSavingsStartDate,formattedEndDate,staff,borrower,showAll);
        LoadAccruedSavingsBranchDate(branch,formattedSavingsStartDate,formattedEndDate,staff,borrower,showAll);
        LoadTotalSavingsBranchDate(branch,formattedSavingsStartDate,formattedEndDate,staff,borrower,showAll);
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