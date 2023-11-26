<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Missed Repayments Loans';
$this->breadcrumbs=array(
  'Repayments'=>array('loanrepayments/admin'),
  'Missed'=>array('missedRepayments'),
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
</style>
<div class="row">
    <div class="card">
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
                <div class="card-body">
                  <div class="card-header">
                      <h5 class="title">Missed Repayments</h5>
                      <hr class="common_rule">
                  </div>
                  <div class="chart-area">
                    <br><br>
                    <form>
                        <div class="row">
                          <div class="col-md-12 col-lg-12 col-sm-12">
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
                                      <option value="0">--RELATION MANAGERS--</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12">
                                 <div class="form-group">
                                  <select class="selectpicker form-control-changed" name="borrower" required="required" id="borrower">
                                      <option value="0">--MEMBERS--</option>
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
                            <div class="col-md-2 col-lg-2 col-sm-12" style="margin-top: -0.75% !important">
                                <div class="form-group">
                                  <button type="button" id="generate_chart_cmd" class="btn btn-primary">Search Accounts</button>
                                </div>
                                <span class="error" id="date_error">End Date must be greater or equal to Start Date</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12"><hr class="common_rule"></div>
              </div>
        </div>
        <div class="card-body">
            <div class="loadFilteredMissedRepayments" style="margin-top: 3% !important; margin-left: 46% !important;">
                <img src="<?=Yii::app()->baseUrl;?>/images/site/loadingData.gif" alt="Data Loading...." width="120px">
            </div>
            <div id="loadFilteredMissedRepayments"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        LoadManagers();
        LoadBorrowers();
        InitializeMissedRepayments();
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

        $("#generate_chart_cmd").click(function(){
          $('.error').hide();
          var startDate = $("input#start_date").val();
          var endDate = $("input#end_date").val(); 
          var branch= $("#branch option:selected").val();
          var staff = $('#staff option:selected').val();
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
            LoadFilteredMissedRepayments(branch,startDate,endDate,staff,borrower);
        });
    });

    function LoadManagers(){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
        success: function(response) {
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

    function LoadBranchManagers(branch){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadBranchRelationManagers');?>",
        data:{'branch':branch},
        success: function(response) {
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

    function LoadBorrowers(){
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('reports/loadBorrowers');?>",
        success: function(response) {
          var borrower = $("#borrower");
          borrower.empty();
          var option = "<option value='0'>--MEMBERS--</option>";
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
          var option = "<option value='0'>--MEMBERS--</option>";
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
          var option = "<option value='0'>--MEMBERS--</option>";
          for(i=0; i<response.length; i++){
            option += "<option value='" + response[i].borrowerID + "'>" + response[i].borrowerName + "</option>";
          }
          borrower.html(option);
        }
      });
    }

    function LoadFilteredMissedRepayments(branch,startDate,endDate,staff,borrower){
      $('.loadFilteredMissedRepayments').show();
      var dataString ='branch='+ branch + '&start_date='+ startDate + '&end_date=' + endDate+ '&staff='+ staff + '&borrower=' + borrower;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('loanaccounts/loadFilteredMissedRepayments');?>",
        data: dataString,
        success: function(response){
            $('.loadFilteredMissedRepayments').hide();
            document.getElementById('loadFilteredMissedRepayments').innerHTML = "";
            $('#loadFilteredMissedRepayments').html(response);
        }
      });
      return false;
    }

    function InitializeMissedRepayments(){
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
        /***************************************
         SACCO INCEPTION
        ****************************************/
        LoadFilteredMissedRepayments(branch,formattedSaccoStartDate,formattedEndDate,staff,borrower);
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
