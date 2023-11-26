<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Loan Calculator';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'Calculator'=>array('loanaccounts/calculator'),
);
?>
<style type="text/css">
    #loanproduct_error,#number_error{
        display: none;
    }
    #loanSchedule{
        display: none;
        margin:0% 0% 2% 0% !important;
    }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
    		<div class="col-lg-12 col-md-12 col-sm-12">
                <h5 class="title">Loan Calculator</h5>
            	<hr class="common_rule">
            </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12" style="padding:20px 20px !important;" >
        		<form>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                             <div class="form-group">
                                <label>Amount Borrowed</label>
                                <input type="text" class="form-control" required="required" value="" name="amount_applied" id="amount_applied">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                             <div class="form-group">
                                <label>Interest Rate</label>
                                <input type="text" class="form-control" required="required" value="" name="interest_rate" id="interest_rate">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                             <div class="form-group">
                                <label>Number of Monthly Payments</label>
                                <input type="text" class="form-control" required="required" value="" name="period" id="period">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12" style="margin-top: 0.65% !important;">
                             <div class="form-group">
                                <button type="button" class="btn btn-primary mb-3" onclick="CalculateRepayments()">
                                    Calculate Repayments
                                </button>
                            </div>
                        </div>
                    </div>
                    <span class="error" id="number_error">Please provide digits only</span>
                </form>
        	</div>
    		<div class="col-lg-12 col-md-12 col-sm-12">
            	<hr class="common_rule">
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div id="loanSchedule"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    function CalculateRepayments(){
        $("#number_error").hide();
        $('#loanSchedule').hide();
        var interestRate=$("input#interest_rate").val();
        var period=$("input#period").val();
        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
        if(intRegex.test(period) || floatRegex.test(period)) {
            var periodValue=period;
        }else{
            $('#number_error').show();
            $("input#period").focus();
            return false;
        }
        var amountApplied=$("input#amount_applied").val();
        var amountIntRegex = /^\d+$/;
        var amountFloatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
        if(amountIntRegex.test(amountApplied) || amountFloatRegex.test(amountApplied)) {
            var amountAppliedValue=amountApplied;
        }else{
            $('#number_error').show();
            $("input#amount_applied").focus();
            return false;
        }
        var dataString = 'interest_rate='+ interestRate+ '&period=' + periodValue + '&amount_applied=' + amountAppliedValue;
          $.ajax({
            type:"POST",
            url: "<?=Yii::app()->createUrl('loanaccounts/loadSchedule');?>",
            data: dataString,
            success: function(response){
                $('#loanSchedule').show();
                $('#loanSchedule').innerHTML="";
                $('#loanSchedule').html(response).show().fadeIn('slow');
            }
          });
          return false;
    }
</script>