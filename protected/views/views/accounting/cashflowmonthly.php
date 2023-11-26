<?php
$this->pageTitle=Yii::app()->name . ' -  Monthly Cash Flow';
$this->breadcrumbs=array(
  'Monthly'=>array('cashflowmonthly'),
  'CashFlow'=>array('cashflowmonthly'),
);
?>
<style type="text/css">
  #cashflowaccumulated{
    margin:2% 0% 15% 0% !important;
  }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Monthly Cash Flow</h5>
            <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
          <div class="col-md-12 col-lg-12 col-sm-12" style="border-bottom: 1px solid #000;margin-bottom:2% !important;">
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
                            <option value="0">-- RELATION MANAGERS --</option>
                        </select>
                        </div>
                    </div>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                          <div class="form-group">
                              <input type="text" class="form-control" id="start_date" placeholder="Start Date">
                          </div>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                          <div class="form-group">
                              <input type="text" class="form-control" id="end_date" placeholder="End Date">
                          </div>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-12" style="margin-top: -1.6% !important;">
                          <div class="form-group">
                              <button type="button" class="btn btn-primary" id="generate_cashflow"> <i class="now-ui-icons ui-1_zoom-bold"></i> Search</button>
                          </div>
                      </div>
                  </div>
              </form>
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12">
            <div id="cashflowaccumulated"></div>
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

  $("#generate_cashflow").click(function(){
    var startDate = $("input#start_date").val();
    var endDate = $("input#end_date").val();
    var branch=$('#branch option:selected').val();
    var staff = $('#staff option:selected').val();
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
    GenerateCashFlowAccumulatedTable(branch,staff,startDate,endDate)
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

function GenerateCashFlowAccumulatedTable(branch,staff,startDate,endDate){
  var dataString ='branch='+branch+'&staff='+staff+'&start_date='+startDate+'&end_date='+endDate;
  $.ajax({
    type:"POST",
    url: "<?=Yii::app()->createUrl('accounting/loadMonthlyCashFlowTable');?>",
    data: dataString,
    success: function(response){
      document.getElementById('cashflowaccumulated').innerHTML = "";
      $('#cashflowaccumulated').html(response);
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

function InitFiltration(){
  var staff=0;
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
  GenerateCashFlowAccumulatedTable(branch,staff,formattedStartDate,formattedEndDate);
}
</script>