<?php
$this->pageTitle=Yii::app()->name . ' - Staff Performance Dashboard';
$this->breadcrumbs=array(
  'Staff'    =>array('staff'),
  'Dashboard'=>array('staff'),
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
                                            <option value="0">--BRANCHES--</option>
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
                                          <option value="0">--RELATION MANAGERS--</option>
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
                                <div class="col-md-2 col-lg-2 col-sm-12" style="margin-top: -1% !important">
                                    <div class="form-group">
                                        <button type="button" id="generate_chart_cmd" class="btn btn-primary" onclick="loadStaffPerformanceTabulation()"> <i class="now-ui-icons business_chart-bar-32"></i> Load Stats</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-stats card-raised-modified">
            <div class="card-body">
                <div class="loadingStaffPerformance" style="margin-top: 2.5% !important; margin-left:38% !important;">
                    <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="150px">
                </div>
                <div id="loadStaffPerformance"></div>
            </div>
        </div>
</div>

<script type="text/javascript">
    $( () => {
        loadManagers();
        initiateStaffPerformanceTabulation ();
        $('.error').hide();
        $('#branch').on('change', function(){
          if(this.value == '0'){
            loadManagers();
          }else{
            loadBranchManagers(this.value);
          }
        });
    });

   let loadStaffPerformanceTabulation = () =>{
      $('.error').hide();
      var startDate = $("input#start_date").val();
      var endDate = $("input#end_date").val(); 
      var branch= $("#branch option:selected").val();
      var staff = $('#staff option:selected').val();
      if(branch === '0'){
        branch = 0;
      }else{
        branch= $("#branch option:selected").val();
      }

      if(staff === '0'){
        staff = 0;
      }else{
        staff= $("#staff option:selected").val();
      }

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
      loadFilteredStaffPerformance(branch,startDate,endDate,staff);
   }

    let loadManagers = () =>{
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
        success: (response)=>{
          var staff = $("#staff");
          staff.empty();
          var option = "<option value='0'>-- RELATION MANAGERS --</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
          }
          staff.html(option);
        }
      });
    }

    let loadBranchManagers= (branch) =>{
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadBranchRelationManagers');?>",
        data:{'branch':branch},
        success: (response) => {
          var staff = $("#staff");
          staff.empty();
          var option = "<option value='0'>--RELATION MANAGERS--</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
          }
          staff.html(option);
        }
      });
    }

    let  loadFilteredStaffPerformance= (branch,startDate,endDate,staff) =>{
      $('.loadingStaffPerformance').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/loadStaffPerformance');?>",
        data: dataString,
        success: (response)=>{
            $('.loadingStaffPerformance').hide();
            $('#loadStaffPerformance').innerHTML = "";
            $('#loadStaffPerformance').html(response);
        }
      });
      return false;
    }


    let initiateStaffPerformanceTabulation = () =>{
        var staff=0;
        var branch = 0;
        var date = new Date();
        var endDate=new Date(date.getFullYear(), date.getMonth() + 1, 0);
        var formattedEndDate=formatDate(endDate);
        var startDate=new Date(date.getFullYear(), date.getMonth(), 1);
        var formattedStartDate=formatDate(startDate);
        loadFilteredStaffPerformance(branch,formattedStartDate,formattedEndDate,staff);
    }

    let formatDate = (date) => {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }
</script>