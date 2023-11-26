<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Members Report';
$this->breadcrumbs=array(
	'BorrowersReport'=>array('reports/borrowersReport'),
);
?>
<style type="text/css">
 #date_error{
    margin-left: 2% !important;
    display: none;
  }
  .loadingData{
    display: none;
    margin:2% 0% 0% 40% !important;
  }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Members Report</h5>
            <hr>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<form style="margin:2% 0% 2% 0% !important;">
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                       <div class="form-group">
                        <select class="selectpicker form-control-changed" name="branch" required="required" id="branch">
                            <option value="0">-- BRANCHES --</option>
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
                   <div class="col-md-3 col-lg-3 col-sm-12">
                         <div class="form-group">
                          <select class="selectpicker form-control-changed" name="staff" required="required" id="staff">
                              <option value="0">-- RELATION MANAGER --</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                         <div class="form-group">
                          <select class="selectpicker form-control-changed" name="borrower" required="required" id="borrower">
                              <option value="0">-- MEMBERS --</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                      <div class="form-group">
                        <select class="selectpicker form-control-changed" name="employer" required="required" id="employer">
                          <option value="0">-- EMPLOYERS --</option>
                        </select>
                      </div>
                    </div>
              </div>
              <br>
              <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Start Date" required="required">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" class="form-control" id="end_date" name="end_date" placeholder="End Date" required="required">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12"  style="margin-top: -1.6% !important">
                      <div class="form-group pull-left">
                        <button type="button" class="btn btn-primary btn-block" id="borrowerReport_cmd"">
                         <i class="now-ui-icons ui-1_zoom-bold"></i> View Members Report</button>
                      </div>
                    </div>
              </div>
            </form>
          <hr>
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="loadingData">
                <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
              </div>
              <div id="loadBorrowersReport"></div>
          </div>
        </div>
     </div>
  </div>
</div>
<script type="text/javascript">
$(function(){

  LoadRelationshipManagers(); 
  LoadBorrowers();
  LoadEmployers();
  initFiltration();

  $('#branch').on('change', function() {
    if(this.value == '0'){
      LoadRelationshipManagers();
      LoadBorrowers();
      LoadEmployers();
    }else{
      LoadBranchManagers(this.value);
      LoadBranchBorrowers(this.value);
      LoadBranchEmployers(this.value);
    }
  });

  $('#staff').on('change', function() {
    if(this.value == '0'){
      LoadBorrowers();
      LoadEmployers();
    }else{
      LoadRelationManagerBorrowers(this.value);
      LoadRelationManagerEmployers(this.value);
    }
  });

  $("#borrowerReport_cmd").click(function(){
    $('.error').hide();
    var startDate = $("input#start_date").val();
    var endDate = $("input#end_date").val();
    var branch=$('#branch option:selected').val();
    var staff = $('#staff option:selected').val();
    var employer=$('#employer option:selected').val();
    var borrower=$('#borrower option:selected').val();
    if(startDate == '' && endDate == ''){
      var date = new Date();
      var endingDate=new Date(date.getFullYear(), date.getMonth() + 1, 0);
      var formattedEndDate=formatDate(endingDate);
      endDate=formattedEndDate;
      var startingDate=new Date(date.getFullYear(),date.getMonth(), 1);
      var formattedStartDate=formatDate(startingDate);
      startDate=formattedStartDate;
    }else{
       startDate = $("input#start_date").val();
       endDate = $("input#end_date").val(); 
    }
    LoadBorrowersReport(startDate,endDate,branch,staff,employer,borrower);
  });
});

function LoadEmployers(){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadEmployers');?>",
    success: function(response) {
      var employer = $("#employer");
      employer.empty();
      var option = "<option value='0'>-- EMPLOYERS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].employerName + "'>" + response[i].employerName + "</option>";
      }
      employer.html(option);
    }
  });
}

function LoadBranchEmployers(branchID){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadBranchEmployers');?>",
    data:{'branch':branchID},
    success: function(response) {
      var employer = $("#employer");
      employer.empty();
      var option = "<option value='0'>-- EMPLOYERS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].employerName + "'>" + response[i].employerName + "</option>";
      }
      employer.html(option);
    }
  });
}

function LoadRelationManagerEmployers(staffID){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadRelationManagerEmployers');?>",
    data:{'staff':staffID},
    success: function(response) {
      var employer = $("#employer");
      employer.empty();
      var option = "<option value='0'>-- EMPLOYERS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].employerName + "'>" + response[i].employerName + "</option>";
      }
      employer.html(option);
    }
  });
}

function LoadRelationshipManagers(){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
    success: function(response) {
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

function LoadBorrowersReport(startDate,endDate,branch,staff,employer,borrower){
  $("#overall").innerHTML="";
  $('.loadingData').show();
  var dataString = 'start_date='+ startDate + '&end_date=' + endDate+ '&branch=' + branch+ '&staff=' + staff + '&employer=' + employer+ '&borrower=' + borrower;
  $.ajax({
    type:"POST",
    url: "<?=Yii::app()->createUrl('reports/filterBorrowersReport');?>",
    data: dataString,
    success: function(response){
      $('.loadingData').hide();
      if(response === 'NOT FOUND'){
        $("#overall").html("<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO MEMBERS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO BORROWERS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>");
      }else{
        $('#loadBorrowersReport').html(response).show().fadeIn('slow');
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

function initFiltration(){
  var staff=0;
  var borrower=0;
  var employer='0';
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
  LoadBorrowersReport(formattedStartDate,formattedEndDate,branch,staff,employer,borrower);
}
</script>