<?php
$this->pageTitle=Yii::app()->name . ' - Snap Preview Report';
$this->breadcrumbs=array(
	'Snap'=>array('executiveSummary'),
  'Preview'=>array('executiveSummary'),
);
/**Flash Messages**/
$successType = 'success';
$succesStatus = CommonFunctions::checkIfFlashMessageSet($successType);
$infoType = 'info';
$infoStatus = CommonFunctions::checkIfFlashMessageSet($infoType);
$warningType = 'warning';
$warningStatus = CommonFunctions::checkIfFlashMessageSet($warningType);
$dangerType = 'danger';
$dangerStatus = CommonFunctions::checkIfFlashMessageSet($dangerType);
?>
<style type="text/css">
 #date_error{
    margin-left: 2% !important;
    display: none;
  }
  .loadingData{
    display: none;
    margin: 2% 0% 0% 40% !important;
  }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <h5 class="title">Snap Preview</h5>
              <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<form style="margin:2% 0% 2% 0% !important;">
                <div class="row">
                    <div class="col-md-2 col-lg-2 col-sm-12">
                       <div class="form-group">
                        <select class="selectpicker form-control-changed" name="summary_type" required="required" id="summary_type" onchange="toggleBranchOrStaffContainer('branchContainer',this,'staffContainer')" style="width: 100%;">
                            <option value="0">-- SUMMARY TYPE --</option>
                            <option value="1">BRANCH SUMMARY</option>
                            <option value="2">STAFF SUMMARY</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-12" id="branchContainer" style="display: none;">
                       <div class="form-group">
                        <select class="selectpicker form-control-changed" name="branch" required="required" id="branch" style="width: 100%;">
                            <option value="0">-- SELECT BRANCH --</option>
                            <?php if(!empty($branches)):?>
                                <?php foreach($branches as $branch):?>
                                    <option value="<?=$branch->branch_id;?>">
                                        <?=$branch->name;?>
                                    </option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-12" id="staffContainer" style="display: none;">
                         <div class="form-group">
                          <select class="selectpicker form-control-changed" name="staff" required="required" id="staff" style="width: 100%;">
                              <option value="0">-- RELATION MANAGER --</option>
                          </select>
                        </div>
                    </div>
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
                    <div class="col-md-2 col-lg-2 col-sm-12">
                      <div class="form-group" style="margin-top: -6% !important;">
                        <button type="button" class="btn btn-primary" id="disbursement_cmd">Snap It</button>
                      </div>
                  </div>
              </div>
              <br>
            </form>
          <hr class="common_rule">
          <div class="loadingData">
            <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="100px">
          </div>
          <div id="LoadExecutiveSummaryReport"></div>
        </div>
     </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
  LoadRelationshipManagers(); 
  initFiltration();
  $("#disbursement_cmd").click(function(){
    $('.error').hide();
    var summaryType=$('#summary_type option:selected').val();
    var startDate = $("input#start_date").val();
    var endDate = $("input#end_date").val();
    var branch=$('#branch option:selected').val();
    var staff = $('#staff option:selected').val();
    if(startDate == '' && endDate == ''){
      var defaultPeriod=0;
      var date = new Date();
      var endingDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
      var formattedEndDate=formatDate(endingDate);
      endDate = formattedEndDate;
      var startingDate=new Date(date.getFullYear(),date.getMonth(), 1);
      var formattedStartDate=formatDate(startingDate);
      startDate=formattedStartDate;
    }else{
       startDate = $("input#start_date").val();
       endDate = $("input#end_date").val(); 
       var defaultPeriod=1;
    }
    FilterExecutiveSummaryReport(startDate,endDate,branch,staff,defaultPeriod,summaryType);
  });
});

function LoadRelationshipManagers(){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
    success: function(response){
      var relationManager = $("#staff");
      relationManager.empty();
      var option = "<option value='0'>-- RELATION MANAGERS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
      }
      relationManager.html(option);
    }
  });
}


function LoadBranchManagers(branch){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadBranchRelationManagers');?>",
    data: {'branch':branch},
    success: function(response) {
      var staff = $("#staff");
      staff.empty();
      var option = "<option value='0'>-- RELATION MANAGERS --</option>";
      for (i=0; i<response.length; i++) {
        option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
      }
      staff.html(option);
    }
  });
}

function FilterExecutiveSummaryReport(startDate,endDate,branch,staff,defaultPeriod,summaryType){
    $('.loadingData').show();
    var dataString='start_date='+startDate+'&end_date='+endDate+'&branch='+branch+'&staff='+staff+'&default_period='+defaultPeriod+'&summary_type='+summaryType;
    $.ajax({
      type:"POST",
      url: "<?=Yii::app()->createUrl('reports/filterExecutiveSummaryReport');?>",
      data: dataString,
      success: function(response){
        $('.loadingData').hide();
        if(response === 'NOT FOUND'){
          $("#overall").html("<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO RECORDS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO RECORDS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>");
        }else{
          $('#LoadExecutiveSummaryReport').html(response).show().fadeIn('slow');
        }
      }
    });
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

function toggleBranchOrStaffContainer(divID, element,div2ID){
  switch(element.value){
    case '2':
    document.getElementById(divID).style.display =  'block';
    document.getElementById(div2ID).style.display = 'block';
    break;

    default:
    document.getElementById(divID).style.display =  'block';
    document.getElementById(div2ID).style.display = 'none';
    break;
  }
}

function initFiltration(){
  var staff=0;
  var userLevel="<?=Yii::app()->user->user_level;?>";
  var branch;
  if(userLevel  === '0'){
    branch = 0;
  }else{
    branch = "<?=Yii::app()->user->user_branch;?>";
  }
  var summaryType=0;
  var defaultPeriod=0;
  var date = new Date();
  var endDate=new Date(date.getFullYear(), date.getMonth() + 1, 0);
  var formattedEndDate=formatDate(endDate);
  var startDate=new Date(date.getFullYear(), date.getMonth(), 1);
  var formattedStartDate=formatDate(startDate);
  FilterExecutiveSummaryReport(formattedStartDate,formattedEndDate,branch,staff,defaultPeriod,summaryType);
}
</script>