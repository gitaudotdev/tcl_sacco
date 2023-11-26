<?php
/* @var $this DashboardController */
$this->pageTitle=Yii::app()->name . ' - Asset Ratios Analytics and Statistics';
$this->breadcrumbs=array(
  'Dashboard'=>array('default'),
  'Analytics'=>array('assetQuality'),
  'Asset_Ratios'=>array('assetQuality'),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card-body">
                      <div id="customer-growth-panel"></div>
                      <div class="chart-area">
                      <br>
                        <form>
                            <div class="row">
                                 <div class="col-md-2 col-lg-2 col-sm-12 space-right">
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
                                <div class="col-md-2 col-lg-2 col-sm-12 space-right">
                                     <div class="form-group">
                                      <select class="selectpicker form-control-changed" name="staff" required="required" id="staff">
                                          <option value="0">-- RMs --</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 space-right">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="start_date" placeholder="Start Date" required="required">
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 space-right">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="end_date" placeholder="End Date" required="required">
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12 analytics_btn">
                                    <div class="form-group">
                                        <button type="button" id="generate_chart_cmd" class="btn btn-primary"
                                         onclick="loadAnalytics()"><i class="now-ui-icons business_chart-bar-32"></i> Load Stats</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TABULATION -->
        <div class="card card-stats card-raised-modified">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                        <h4 class="info-title">Asset Ratios Statistics</h4>
                        <hr/>
                        <div class="loader" id="assetQualityGrowthTableLoader"></div>
                        <div id="loadAssetQualityGrowthTable" class="loaded-content"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- GRAPH -->
        <div class="card card-stats card-raised-modified">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                        <h4 class="info-title">Asset Ratios Graph</h4>
                        <hr/>
                        <div class="loader" id="assetQualityGrowthGraphLoader"></div>
                        <div id="loadAssetQualityGrowthGraph" class="loaded-content"></div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript"> 

$(function(){
    preloadManagers();
    initializeAnalytics();
    $('#branch').on('change', function(){
      if(this.value == '0'){
        preloadManagers();
      }else{
        preloadBranchManagers(this.value);
      }
    });
});

var messagePanel   = 'customer-growth-panel';
var infoType       = 'info';
var dangerType     = 'danger';
var successType    = 'success';
var content        = '<img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Loading..." width="90px">';
var defaultStaff   = 0;
var userLevel      = "<?=Yii::app()->user->user_level;?>";
var defaultBranch;
if(userLevel  === '0'){
  defaultBranch = 0;
}else{
  defaultBranch = "<?=Yii::app()->user->user_branch;?>";
}
var defaultDate      = new Date();
var defaultStartDate = new Date(2000,1,1);
var defaultEndDate   = new Date(defaultDate.getFullYear(), defaultDate.getMonth() + 1, 0);

function loadAnalytics(){
  var startDate = $("input#start_date").val();
  var endDate   = $("input#end_date").val(); 
  var branch    = $("#branch option:selected").val();
  var staff     = $('#staff option:selected').val();
  if(startDate == '' && endDate == ''){
    var message='Start and/or end dates not selected. Default statistics will be loaded ...';
    displayNotification(messagePanel,dangerType,message);
    var formattedEndDate   = formatDate(defaultEndDate);
    var formattedStartDate = formatDate(defaultStartDate);
  }else{
    var formattedStartDate = $("input#start_date").val();
    var formattedEndDate   = $("input#end_date").val(); 
  }
  loadAssetQualityGrowthAnalytics(branch,staff,formattedStartDate,formattedEndDate);
  loadAssetQualityGrowthAnalyticsGraph(branch,staff,formattedStartDate,formattedEndDate);
}

function preloadManagers(){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
    success: function(response) {
      var staff = $("#staff");
      staff.empty();
      var option = "<option value='0'>-- RMs --</option>";
      for(i=0; i<response.length; i++){
        option+="<option value='"+response[i].managerID+"'>"+response[i].managerName+"</option>";
      }
      staff.html(option);
    }
  });
}

function preloadBranchManagers(branch){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadBranchRelationManagers');?>",
    data:{'branch':branch},
    success: function(response) {
      var staff = $("#staff");
      staff.empty();
      var option = "<option value='0'>-- RMs --</option>";
      for(i=0; i<response.length; i++){
        option+="<option value='"+response[i].managerID+"'>"+response[i].managerName+"</option>";
      }
      staff.html(option);
    }
  });
}

function loadAssetQualityGrowthAnalytics(branch,staff,start_date,end_date){
  clearDivContent('loadAssetQualityGrowthTable');
  showLoader('assetQualityGrowthTableLoader',content);
  var dataString ='branch='+branch+'&staff='+staff+'&start_date='+start_date+'&end_date='+end_date;
  $.ajax({
    type:"POST",
    dataType:"json",
    url: "<?=Yii::app()->createUrl('dashboard/loadAssetQualityGrowthAnalytics');?>",
    data: dataString,
    success: function(response){
     hideLoader('assetQualityGrowthTableLoader');
     var message = 'Assets quality statistics fetched successfully ...';
     displayNotification(messagePanel,successType,message);
     $('#loadAssetQualityGrowthTable').html(response.tabulation);
    }
  });
  return false;
}

function loadAssetQualityGrowthAnalyticsGraph(branch,staff,start_date,end_date){
  clearDivContent('loadAssetQualityGrowthGraph');
  showLoader('assetQualityGrowthGraphLoader',content);
  var dataString ='branch='+branch+'&staff='+staff+'&start_date='+start_date+'&end_date='+end_date;
  $.ajax({
    type:"POST",
    url: "<?=Yii::app()->createUrl('dashboard/loadAssetQualityGrowthGraph');?>",
    data: dataString,
    success: function(graph){
     hideLoader('assetQualityGrowthGraphLoader');
     $('#loadAssetQualityGrowthGraph').html(graph);
    }
  });
  return false;
}

function initializeAnalytics(){
 var formattedStartDate  = formatDate(defaultStartDate);
 var formattedEndDate    = formatDate(defaultEndDate);
 loadAssetQualityGrowthAnalytics(defaultBranch,defaultStaff,formattedStartDate,formattedEndDate);
 loadAssetQualityGrowthAnalyticsGraph(defaultBranch,defaultStaff,formattedStartDate,formattedEndDate);
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