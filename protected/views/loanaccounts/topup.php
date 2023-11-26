<?php
$this->pageTitle=Yii::app()->name . ' -  Loan Top Up';
$this->breadcrumbs=array(
	'loanaccounts'=>array('admin'),
	'TopUp'=>array('topup')
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
    <div class="card">
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
        <div class="card-body">
          <div class="card-header">
            <h5 class="title">Loan Top Up</h5>
            <hr class="common_rule">
          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/commitTopup');?>" enctype='multipart/form-data'>
            <div class="row">
                <div class="col-md-3 col-lg-3 col-sm-12">
                    <div class="form-group">
                      <label>Select Account</label>
                      <select name="loanaccount" class="selectpicker" required="required" id="loanaccount">
                        <option value="">--LOAN ACCOUNTS--</option>
                        <?php
                          foreach($users as $user){
                            echo "<option value='$user->loanaccount_id'>$user->AccountDetails</option>";
                          }
                        ?>
                      </select>
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Account Number</label>
                   <input class="form-control" name="account_number" id="account_number" disabled="disabled" readonly="readonly">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Relationship Manager</label>
                   <input class="form-control" name="rm" id="rm" disabled="disabled" readonly="readonly">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Loan Balance</label>
                   <input type="text" class="form-control" name="loan_balance" id="loan_balance" disabled="disabled" readonly="readonly">
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Loan Limit</label>
                   <input type="text" class="form-control" name="loanLimit" id="loanLimit" disabled="disabled" readonly="readonly">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Interest Rate</label>
                   <input class="form-control" name="interest_rate" id="interest_rate" disabled="disabled" readonly="readonly">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Repayment Periods</label>
                   <input class="form-control" name="repayment_period" id="repayment_period">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Repayment Start Date</label>
                    <?php
                      $today=date('Y-m-d');
                      $startDate=date('Y-m-d',strtotime($today.'+ 1 month'));
                    ?>
                    <input class="form-control" name="repayment_start_date" id="repayment_start_date" disabled="disabled" readonly="readonly" value="<?=$startDate?>">
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Top Up Amount</label>
                   <input type="text" class="form-control" name="top_up_amount" id="top_up_amount" required="required">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Installment</label>
                   <input class="form-control" name="installment" id="installment" disabled="disabled" readonly="readonly">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Savings Balance</label>
                   <input type="text" class="form-control" name="savings_balance" id="savings_balance" disabled="disabled" readonly="readonly">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                   <label>New Principal</label>
                   <input type="text" class="form-control" name="amount_to_disburse" id="amount_to_disburse" required="required">
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Brief Comment</label>
                    <textarea class="form-control" name="comment" rows="2" cols="6" placeholder="Brief top up comment..."></textarea>
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12" style="border:1px solid #dedede;padding:12px 3px 3px 12px;">
                   <label>Upload File</label>
                   <?= CHtml::fileField('filename');?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
			            <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary pull-right" value="Submit Request" id="apply_loan_cmd" name="topup_loan_cmd">
                  </div>
              </div>
            </div>
            <br><br>
            </form>
	        </div>
        </div>
     </div>
  </div>
<script>
$(function(){
  var typingTimer;
  var doneTypingInterval=1020;
  $('#loanaccount').on('change', function() {
     $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('loanaccounts/loadTopUpDetails');?>",
        data: {'loanaccount_id':this.value},
        success: function(response) {
          if(response === 'NOT FOUND'){
            alert('Kindly select a valid loan account');
          }else{
            $("#account_number").val(response.account_number);
            $("#rm").val(response.rm);
            $("#interest_rate").val(response.interest_rate);
            $("#repayment_period").val(response.repayment_period);
            $("#loanLimit").val(response.maximum_limit);
            $("#loan_balance").val(response.loan_balance);
            $("#savings_balance").val(response.savings_balance);
          }
        }
      });
  });
  //on keyup, start the countdown
  $("#top_up_amount").on('keyup keydown change', function () {
    clearTimeout(typingTimer);
    if($("#top_up_amount").val()){
       typingTimer = setTimeout(doneTyping, doneTypingInterval); 
    }
  });

  $("#repayment_period").on('keyup keydown change', function () {
    clearTimeout(typingTimer);
    if($("#repayment_period").val()){
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
    }
  });

  $("#interest_rate").on('keyup keydown change', function () {
    clearTimeout(typingTimer);
    if($("#interest_rate").val()){
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
    }
  });
  //user is "finished typing," do something
  function doneTyping(){
    $('#apply_loan_cmd').prop('disabled',false);
    var balance=parseFloat($("#loan_balance").val());
    var topupAmount=parseFloat($("#top_up_amount").val());
    var repaymentPeriod=$("#repayment_period").val();
    var interestRate=$("#interest_rate").val();
    var loan_limit = $("#loanLimit").val();
    $('#apply_loan_cmd').prop('disabled',false);
      $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('loanaccounts/loadTopUpDisbursement');?>",
        data: {'loan_balance':balance,'top_up_amount':topupAmount,'interest_rate':interestRate,'repayment_period':repaymentPeriod},
        success: function(response) {
          if(response === 'NOT FOUND'){
            alert('Kindly provide a valid top up amount in digits only');
          }else{
            if(loan_limit < response.disbursement_amount){
              $('#apply_loan_cmd').prop('disabled',true);
              alert('Top up amount will make the total loan for this account to exceed the client loan limit');
            }else{
              $("#amount_to_disburse").val(response.disbursement_amount);
              $("#installment").val(response.emi);
            }
          }
        }
      });
  }
});
</script>