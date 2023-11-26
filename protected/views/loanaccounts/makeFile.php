<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Updload Loan Files';
$this->breadcrumbs=array(
	'loanaccount'=>array('loanaccounts/'.$loan->loanaccount_id),
	'Upload'=>array('loanaccounts/makeFile/'.$loan->loanaccount_id)
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
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h5 class="title">Upload Loan File</h5>
              <hr class="common_rule">
            </div>
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
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <form method="post" enctype='multipart/form-data'>
              <br>
              <input type="hidden" name="accountAction" value="disbursedAccount">
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class='form-group'>
                    <label >File Name</label>
                    <input type='text' name="loan_file_name" required="required" class="form-control">
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <label >Browse File</label><br>
                  <div class='file-input'>
                    <input type='file' name="loan_file" required="required">
                    <span class='button'>Choose File</span>
                    <span class='label' data-js-label>No file selected</label>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <input type="submit" name="upload_file_cmd" value="Upload" class="btn btn-primary">
                </div>
              </div>
              <br>
            </form>
	        </div>
        </div>
     </div>
  </div>
</div>
<script type="text/javascript">
  
var inputs = document.querySelectorAll('.file-input')

for (var i = 0, len = inputs.length; i < len; i++) {
  customInput(inputs[i])
}

function customInput (el) {
  const fileInput = el.querySelector('[type="file"]')
  const label = el.querySelector('[data-js-label]')
  
  fileInput.onchange =
  fileInput.onmouseout = function () {
    if (!fileInput.value) return
    
    var value = fileInput.value.replace(/^.*[\\\/]/, '')
    el.className += ' -chosen'
    label.innerText = value
  }
}
</script>