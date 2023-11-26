<?php
$this->pageTitle=Yii::app()->name . ' - Payment Reminder';
$this->breadcrumbs=array(
	'Payments'=>array('reminders'),
	'Reminders'=>array('reminders')
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
    <div class="card">
      <br>
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
              <h5 class="title">Payment Reminders</h5>
              <hr class="common_rule">
          </div>
      <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Select member(s)</h5>
            <form method="post">
              <div class="row">
                  <div class="col-md-4 col-lg-4 col-sm-12">
                      <div class="form-group">
                         <select multiple="multiple" name="loanaccounts_borrower[]" class="selectpicker" required="required" id="loanaccounts_select">
                          <?php
                          if(!empty($loanaccounts)){
                            foreach($loanaccounts as $loanaccount){
                              echo "<option value='";
                                echo $loanaccount->loanaccount_id;
                              echo"'>$loanaccount->AccountDetails</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                  </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-2 col-lg-2 col-sm-12"></div>
                <div class="col-md-2 col-lg-2 col-sm-12">
                    <div class="form-group">
                      <button type="submit" name="send_sms_cmd" class="btn btn-primary pull-right"> Send SMS</button>
                    </div>
                </div>
              </div>
              <br>
            </form>
	        </div>
        </div>
     </div>
  </div>
  <script type="text/javascript">
  $(function () {
    $("#loanaccounts_select").select2({
        placeholder: "Select Member(s)"
    });
  });
</script>