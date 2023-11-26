<?php
$this->pageTitle   = Yii::app()->name . ' - Loan Comments Analytics and Statistics';
$this->breadcrumbs = array(
  'Comments'       => array('comments'),
  'Statistics'     => array('comments'),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats">
            <div class="card-body">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card-body">
                      <div id="customer-growth-panel"></div>
                      <div class="chart-area">
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
                                </div>&emsp;
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                     <div class="form-group">
                                      <select class="selectpicker form-control-changed" name="staff" required="required" id="staff">
                                          <option value="0">-- MANAGERS --</option>
                                      </select>
                                    </div>
                                </div>&emsp;
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <select name="branch" id="comment_type" class="form-control selectpicker" required="required">
                                            <option value="0">-- TYPES --</option>
                                            <?php if(!empty($types)):?>
                                                <?php foreach($types as $type):?>
                                                    <option value="<?=$type->id;?>"><?=$type->name;?></option>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                </div>&emsp;
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="start_date" placeholder="Start Date" required="required">
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="end_date" placeholder="End Date" required="required">
                                    </div>
                                </div>
                                <div class="col-md-1 col-lg-1 col-sm-12 analytics_btn">
                                    <div class="form-group">
                                        <button type="button" id="generate_chart_cmd" class="btn btn-primary pull-left"
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
                        <h4 class="info-title">Branch Loan Comments</h4>
                        <hr class="common_rule"/>
                        <div class="loader" id="branchCommentsLoader"></div>
                        <div id="loadBranchCommentStats" class="loaded-content"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAPH -->
        <div class="card card-stats card-raised-modified">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                        <h4 class="info-title">Staff Loan Comments</h4>
                        <hr class="common_rule"/>
                        <div class="loader" id="staffCommentsLoader"></div>
                        <div id="staffCommentStats" class="loaded-content"></div>
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
var defaultcType     = 0; 
var defaultDate      = new Date();
var defaultStartDate = new Date(defaultDate.getFullYear(),defaultDate.getMonth(), 1);
var defaultEndDate   = new Date(defaultDate.getFullYear(),defaultDate.getMonth() + 1, 0);

function loadAnalytics(){
  var startDate = $("input#start_date").val();
  var endDate   = $("input#end_date").val(); 
  var branch    = $("#branch option:selected").val();
  var staff     = $('#staff option:selected').val();
  var cType     = $('#comment_type option:selected').val();
  if(startDate == '' && endDate == ''){
    var message='Start and/or end dates not selected. Default statistics will be loaded ...';
    displayNotification(messagePanel,dangerType,message);
    var formattedEndDate   = formatDate(defaultEndDate);
    var formattedStartDate = formatDate(defaultStartDate);
  }else{
    var formattedStartDate = $("input#start_date").val();
    var formattedEndDate   = $("input#end_date").val(); 
  }
  loadBranchCommentsDashboardStats(branch,staff,cType,formattedStartDate,formattedEndDate);
  loadStaffCommentsDashboardStats(branch,staff,cType,formattedStartDate,formattedEndDate);
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
      var option = "<option value='0'>-- MANAGERS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
      }
      staff.html(option);
    }
  });
}

function preloadManagers(){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
    success: function(response) {
      var staff = $("#staff");
      staff.empty();
      var option = "<option value='0'>-- MANAGERS --</option>";
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
      var option = "<option value='0'>-- MANAGERS --</option>";
      for(i=0; i<response.length; i++){
        option+="<option value='"+response[i].managerID+"'>"+response[i].managerName+"</option>";
      }
      staff.html(option);
    }
  });
}

function loadBranchCommentsDashboardStats(branch,staff,cType,start_date,end_date){
  clearDivContent('loadBranchCommentStats');
  showLoader('branchCommentsLoader',content);
  var dataString ='branch='+branch+'&staff='+staff+'&comment_type='+cType+'&start_date='+start_date+'&end_date='+end_date;
  $.ajax({
    type:"POST",
    dataType:"json",
    url: "<?=Yii::app()->createUrl('dashboard/loadBranchCommentsDashboard');?>",
    data: dataString,
    success: function(response){
     hideLoader('branchCommentsLoader');
     var message = 'Branch loan comments statistics fetched successfully ...';
     displayNotification(messagePanel,successType,message);
     $('#loadBranchCommentStats').html(response.tabulation);
    }
  });
  return false;
}

function loadStaffCommentsDashboardStats(branch,staff,cType, start_date,end_date){
  clearDivContent('staffCommentStats');
  showLoader('staffCommentsLoader',content);
  var dataString ='branch='+branch+'&staff='+staff+'&comment_type='+cType+'&start_date='+start_date+'&end_date='+end_date;
  $.ajax({
    type:"POST",
    dataType:"json",
    url: "<?=Yii::app()->createUrl('dashboard/loadStaffCommentsDashboard');?>",
    data: dataString,
    success: function(response){
     hideLoader('staffCommentsLoader');
     $('#staffCommentStats').html(response.tabulation);
    }
  });
  return false;
}

function initializeAnalytics(){
 var formattedStartDate  = formatDate(defaultStartDate);
 var formattedEndDate    = formatDate(defaultEndDate);    
 loadBranchCommentsDashboardStats(defaultBranch,defaultStaff,defaultcType,formattedStartDate,formattedEndDate);
 loadStaffCommentsDashboardStats(defaultBranch,defaultStaff,defaultcType,formattedStartDate,formattedEndDate);
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