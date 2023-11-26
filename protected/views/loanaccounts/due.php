<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Due Loans';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'DueLoans'=>array('loanaccounts/due'),
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
#tabulate_due_loans{
    margin-top:10px !important;
}
.loadingData{
    display: none;
    margin:2% 0% 0% 40% !important;
}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
			<div class="col-md-12 col-lg-12 col-sm-12">
	            <h5 class="title">Due Loans</h5>
            	<hr class="common_rule">
	         </div>
			  <?php if($succesStatus === 1):?>
		    <div class="col-md-12 col-lg-12 col-sm-12">
		      <?=CommonFunctions::displayFlashMessage($successType);?>
		    </div>
		    <?php endif;?>
		    <?php if($infoStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($infoType);?>
		      </div>
		    <?php endif;?>
		    <?php if($warningStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($warningType);?>
		      </div>
		    <?php endif;?>
		    <?php if($dangerStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($dangerType);?>
		      </div>
		    <?php endif;?>
        </div>
        <div class="card-body col-md-12 col-lg-12 col-sm-12">
        	<form style="margin:2% 0% 2% 0% !important;">
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                            <select name="branch" id="branch" class="selectpicker form-control">
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
                            <select name="rm" id="rm" class="selectpicker form-control">
                                <option value="0">--RELATION MANAGER--</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                       <div class="form-group">
                            <select name="status" id="status" class="selectpicker form-control">
                                <option value="0">-- STATUS--</option>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                           <select name="startDate" id="startDate" class="selectpicker form-control">
                            <option value="0">-- FROM DATE--</option>
                             <?php
                               for($i=1;$i<=31;$i++){
                                echo '<option value='.$i.'>'.$i.'</option>';
                               }
                             ?>
                            </select>
                        </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                           <select name="endDate" id="endDate" class="selectpicker form-control">
                            <option value="0">-- END DATE--</option>
                             <?php
                               for($i=1;$i<=31;$i++){
                                echo '<option value='.$i.'>'.$i.'</option>';
                               }
                             ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                       <div class="form-group">
                            <select name="month" id="month" class="selectpicker form-control">
                                <option value="0">-- MONTH--</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                       <div class="form-group">
                            <select name="year" id="year" class="selectpicker form-control">
                                <option value="0">-- YEAR --</option>
                                <?php
                                 $maxYear=(int)date('Y');
                                 for($i=$maxYear;$i>=2012;$i--){
                                  echo '<option value='.$i.'>'.$i.'</option>';
                                 }
                               ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12" style="margin-top: -1.6% !important;">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary pull-right" id="filterDue_cmd"> <i class="now-ui-icons ui-1_zoom-bold"></i> Search Due Accounts</button>
                        </div>
                    </div>
                    <span class="error" id="date_error">End Date must be greater or equal to Start Date</span>
                </div>
            </form>
        	<hr>
        	<div class="col-md-12 col-lg-12 col-sm-12">
              <div class="loadingData">
                <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="75px">
              </div>
              <div id="tabulate_due_loans"></div>
        	</div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){

      LoadRelationshipManagers();

      initFiltration();

      $('#branch').on('change', function(){
         LoadBranchRelationManagers(this.value);
      });

      $("#filterDue_cmd").click(function(){
        var startDate = $("#startDate option:selected").val();
        var endDate = $("#endDate option:selected").val();
        if(startDate == '0' || endDate =='0'){
          var dateToday = new Date();
          var formattedTodayDate=dateToday.getDate();
          startDate = formattedTodayDate;
          endDate = formattedTodayDate;
        }
        var month =$("#month option:selected").val();
        var year =$("#year option:selected").val();
        var branch =$("#branch option:selected").val();
        var rm=$("#rm option:selected").val();
        var status=$("#status option:selected").val();
        SearchDueLoans(startDate,endDate,month,year,branch,rm,status);
      });

    });

    function LoadRelationshipManagers(){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
        success: function(response) {
          var relationManager = $("#rm");
          relationManager.empty();
          var option = "<option value='0'>--RELATION MANAGER--</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
          }
          relationManager.html(option);
        }
      });
    }

    function LoadBranchRelationManagers(branch){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadBranchRelationManagers');?>",
        data: {'branch':branch},
        success: function(response) {
          var rm = $("#rm");
          rm.empty();
          var option = "<option value='0'>--RELATION MANAGER--</option>";
          for (i=0; i<response.length; i++) {
            option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
          }
          rm.html(option);
        }
      });
    }

	function SearchDueLoans(startDate,endDate,month,year,branch,rm,status){
    $('.loadingData').show();
    var dataString ='start_date='+startDate+'&end_date='+endDate+'&month='+month+'&year='+year+'&branch='+branch+'&rm='+rm+'&status='+status;
    $.ajax({
      type:"POST",
      url: "<?=Yii::app()->createUrl('loanaccounts/loadDueLoans');?>",
      data: dataString,
      success: function(response){
        $('.loadingData').hide();
        if(response === 'NO DUE LOANS'){
          $("#tabulate_due_loans").html("<div><p style='font-size:1.02em;color:#00933b;'><strong>NO LOANS FOUND</strong></p></div>");
        }else{
          document.getElementById('tabulate_due_loans').innerHTML = "";
          $('#tabulate_due_loans').html(response).show().fadeIn('slow');
        }
      }
    });
	}

  function initFiltration(){
    var dateToday = new Date();
    var formattedTodayDate=dateToday.getDate();
    var startDate = formattedTodayDate;
    var endDate = formattedTodayDate;
    var month='0';
    var year='0';
    var userLevel="<?=Yii::app()->user->user_level;?>";
    var branch;
    if(userLevel  === '0'){
        branch = 0;
    }else{
        branch = "<?=Yii::app()->user->user_branch;?>";
    }
    var rm=0;
    var status='0';
    SearchDueLoans(startDate,endDate,month,year,branch,rm,status);
  }
</script>
