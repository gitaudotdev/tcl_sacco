<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Daily Downloadable Report';
$this->breadcrumbs=array(
  'DailyReport'=>array('loanaccounts/dailyReport'),
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
    margin:2% 0% 0% 40% !important;
  }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <?php if($succesStatus === 1):?>
    <div class="col-lg-12 col-md-12 col-sm-12">
    <?=CommonFunctions::displayFlashMessage($successType);?>
    </div>
    <?php endif;?>
    <?php if($infoStatus === 1):?>
      <div class="col-lg-12 col-md-12 col-sm-12">
        <?=CommonFunctions::displayFlashMessage($infoType);?>
      </div>
    <?php endif;?>
    <?php if($warningStatus === 1):?>
      <div class="col-lg-12 col-md-12 col-sm-12">
        <?=CommonFunctions::displayFlashMessage($warningType);?>
      </div>
    <?php endif;?>
    <?php if($dangerStatus === 1):?>
      <div class="col-lg-12 col-md-12 col-sm-12">
        <?=CommonFunctions::displayFlashMessage($dangerType);?>
      </div>
    <?php endif;?>
    <div class="card">
          <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card-header">
            <h5 class="title">Daily Loan Report</h5>
            <hr class="common_rule">
        </div>
      </div>
        <div class="card-body">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <form style="margin:2% 0% 2% 0% !important;">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
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
                  <div class="col-md-4 col-lg-4 col-sm-12">
                         <div class="form-group">
                          <select class="selectpicker form-control-changed" name="staff" required="required" id="staff">
                              <option value="0">-- RELATION MANAGERS --</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                         <div class="form-group">
                          <select class="selectpicker form-control-changed" name="borrower" required="required" id="borrower">
                              <option value="0">-- LOAN ACCOUNTS --</option>
                          </select>
                        </div>
                    </div>
              </div>
              <br>
              <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <input type="text" class="form-control" id="start_date" placeholder="Start Date">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                            <input type="text" class="form-control" id="end_date" placeholder="End Date">
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <div class="form-group" style="margin-top: -5%">
                        <button type="button" class="btn btn-primary btn-block" id="generate_cmd"> <i class="now-ui-icons ui-1_zoom-bold"></i> Load Report</button>
                      </div>
                  </div>
              </div>
              <br>
            <hr>
            <div class="loadingData">
              <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
            </div>
            <div id="LoadArrearsReport">
            </div>
            </form>
          </div>
        </div>
     </div>
</div>
<script type="text/javascript">
  $(function(){
    LoadRelationshipManagers(); 
    LoadAccounts();
    initFiltration();

    $('#branch').on('change', function() {
      if(this.value == '0'){
        LoadRelationshipManagers();
        LoadAccounts();
      }else{
        LoadBranchManagers(this.value);
        LoadBranchAccounts(this.value);
      }
    });

    $('#staff').on('change', function() {
      if(this.value == '0'){
        LoadAccounts();
      }else{
        LoadRelationManagerAccounts(this.value);
      }
    });

    $("#generate_cmd").click(function(){
      $('.error').hide();
      var startDate = $("input#start_date").val();
      var endDate = $("input#end_date").val();
      var branch=$('#branch option:selected').val();
      var staff = $('#staff option:selected').val();
      var borrower=$('#borrower option:selected').val();
      if(startDate == '' && endDate == ''){
        var date = new Date();
        var endingDate=new Date(date.getFullYear(), date.getMonth() + 1, 0);
        var formattedEndDate=formatDate(endingDate);
        endDate=formattedEndDate;
        var startingDate=new Date(1999, 12, 1);
        var formattedStartDate=formatDate(startingDate);
        startDate=formattedStartDate;
      }else{
         startDate = $("input#start_date").val();
         endDate = $("input#end_date").val(); 
      }
      LoadFilteredDailyReport(startDate,endDate,branch,staff,borrower);
    });
  });

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

function LoadAccounts(){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadAccounts');?>",
    success: function(response) {
      var borrower = $("#borrower");
      borrower.empty();
      var option = "<option value='0'>-- LOAN ACCOUNTS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].accountID + "'>" + response[i].accountName + "</option>";
      }
      borrower.html(option);
    }
  });
}

function LoadBranchAccounts(branch){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadBranchAccounts');?>",
    data:{'branch':branch},
    success: function(response) {
      var borrower = $("#borrower");
      borrower.empty();
      var option = "<option value='0'>-- LOAN ACCOUNTS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].accountID + "'>" + response[i].accountName + "</option>";
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

function LoadRelationManagerAccounts(staff){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadRelationManagerAccounts');?>",
    data:{'staff':staff},
    success: function(response) {
      var borrower = $("#borrower");
      borrower.empty();
      var option = "<option value='0'>-- LOAN ACCOUNTS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].accountID + "'>" + response[i].accountName + "</option>";
      }
      borrower.html(option);
    }
  });
}

function LoadFilteredDailyReport(startDate,endDate,branch,staff,borrower){
    $('.error').hide();
    $('.loadingData').show();
    var dataString = 'start_date='+ startDate + '&end_date=' + endDate+ '&branch=' + branch+ '&staff=' + staff + '&borrower=' + borrower;
    $.ajax({
    type:"POST",
    url: "<?=Yii::app()->createUrl('loanaccounts/loadFilteredDailyReport');?>",
    data: dataString,
    success: function(response){
      $('.loadingData').hide();
      if(response === 'NOT FOUND'){
        $("#overall").html("<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO ACCOUNTS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** No Loan accounts available by the specified filter. Please try again with different filters. ****</p></div>");
      }else{
        $('#LoadArrearsReport').html(response).show().fadeIn('slow');
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
  var startDate=new Date(1999, 12, 1);
  var formattedStartDate=formatDate(startDate);
  LoadFilteredDailyReport(formattedStartDate,formattedEndDate,branch,staff,borrower);
}
</script>