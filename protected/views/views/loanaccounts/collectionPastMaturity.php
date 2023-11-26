<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Loans Past Maturity Date- Collection Sheet';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'LoanPastMaturityCollectionSheet'=>array('loanaccounts/collectionPastMaturity'),
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
    <div class="card">
        <div class="card-header">
			<div class="col-md-12 col-lg-12 col-sm-12">
	            <h5 class="title">Loans Past Maturity Date: Collection Sheet</h5>
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
                    <div class="col-md-3 col-lg-3 col-sm-12">
                         <div class="form-group">
                            <select class="selectpicker form-control-changed" name="staff" required="required" id="staff">
                                <option value="0">--STAFF MEMBER --</option>
                                <?php if(!empty($users)):?>
                                    <?php foreach($users as $user):?>
                                        <option value="<?=$user->user_id;?>">
                                            <?=$user->getUserFullName();?>
                                        </option>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12" style="margin-top: -1.25% !important;">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="PastMaturityDateCollectionSheet(event)"> <i class="now-ui-icons ui-1_zoom-bold"></i> 
                            View Sheet</button>
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
        		<div id="loadCollectionsPastMaturityDate"></div>
        	</div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function PastMaturityDateCollectionSheet(event){
      event.preventDefault();
      $('.error').hide();
      var startDate = $("input#start_date").val();
      var endDate = $("input#end_date").val(); 
      var staff = document.getElementById("staff");
      var staffValue = staff.options[staff.selectedIndex].value;
      if(endDate >= startDate){
            $('.loadingData').show();
            var dataString = 'start_date='+ startDate + '&end_date=' + endDate+ '&user_id=' + staffValue;
            $.ajax({
                type:"POST",
            url: "<?=Yii::app()->createUrl('loanaccounts/loadCollectionsPastMaturityDate');?>",
            data: dataString,
            success: function(response){
                $('.loadingData').hide();
                if(response === 'NOT FOUND'){
                    $('#loadCollectionsPastMaturityDate').html("<div class='col-md-8 col-lg-8 col-sm-8' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO COLLECTIONS PAST MATURITY DATE</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO LOAN COLLECTIONS PAST LOAN MATURITY DATE. ****</p></div>");
                }else{
                    $('#loadCollectionsPastMaturityDate').html(response).show().fadeIn('slow');
                }
            }
          });
          return false;
      }else{
        $("span#date_error").show();
        $("input#end_date").focus();
        return false;
      }
    }
</script>
