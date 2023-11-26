<?php
$this->pageTitle=Yii::app()->name . ' -  Payroll Administration';
$this->breadcrumbs=array(
  'Payroll'=>array('profiles/payroll'),
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
        <div class="card-body">
        <div class="card-header">
            <h5 class="title">Staff Members Payroll</h5>
            <hr class="common_rule">
        </div>
           <br>
          <div class="col-md-12 col-lg-12 col-sm-12">
            <form>
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
                            <option value="0">-- RELATION MANAGERS --</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                            <input type="text" class="form-control" id="month_date" placeholder="Month Date">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group" style="margin-top: -5%">
                          <button type="button" class="btn btn-primary" id="generate_payroll"> <i class="now-ui-icons ui-1_zoom-bold"></i> View</button>
                        </div>
                  </div>
              </div>
              <br>
            </form>
          <hr class="common_rule">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="loadingData">
                <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
              </div>
            <div id="LoadFilteredStaffPayroll"></div>
          </div>
          </div>
     </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
  LoadRelationshipManagers();
  InitFiltration();
  $('#branch').on('change', function() {
     if(this.value == '0'){
      LoadRelationshipManagers();
    }else{
      LoadBranchManagers(this.value);
    }
  });

  $("#generate_payroll").click(function(){
    $('.error').hide();
    var monthDate = $("input#month_date").val();
    var branch=$('#branch option:selected').val();
    var staff = $('#staff option:selected').val();
    if(monthDate == ''){
      var currentYear=(new Date()).getFullYear();
      var currentMonth=(new Date()).getMonth()+1;
      var monthDate=currentMonth+'-'+currentYear;
    }else{
       monthDate = $("input#month_date").val();
    }
    LoadFilteredPayrollBranchDate(branch,monthDate,staff)
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

function LoadFilteredPayrollBranchDate(branch,monthDate,staff){
  $('.loadingData').show();
  var dataString = 'month_date='+ monthDate + '&branch=' + branch+ '&staff=' + staff;
  $.ajax({
    type:"POST",
    url: "<?=Yii::app()->createUrl('staff/filterStaffPayroll');?>",
    data: dataString,
    success: function(response){
        $('.loadingData').hide();
        document.getElementById('LoadFilteredStaffPayroll').innerHTML = "";
        $('#LoadFilteredStaffPayroll').html(response);
    }
  });
}

function InitFiltration(){
    var staff=0;
    var userLevel="<?=Yii::app()->user->user_level;?>";
    var branch;
    if(userLevel  === '0'){
        branch = 0;
    }else{
        branch = "<?=Yii::app()->user->user_branch;?>";
    }
    var currentYear=(new Date()).getFullYear();
    var currentMonth=(new Date()).getMonth()+1;
    var monthDate=currentMonth+'-'+currentYear;
    LoadFilteredPayrollBranchDate(branch,monthDate,staff)
}
</script>