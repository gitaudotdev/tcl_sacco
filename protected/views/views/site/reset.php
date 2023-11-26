<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Reset Forgotten Password';
$this->breadcrumbs=array(
	'Reset Forgotten Password',
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
<div class="card card-login card-plain" style="margin-top: 35%;">
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
  <form method="post">
    <center><h4 class="authTitle"> CHANGE PASSWORD</h4><hr class="authHR"></center>
    <div class="card-body">
       <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <input type="password" placeholder="New Password" class="form-control" required="required" name="password">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <input type="password" placeholder="Confirm Password" class="form-control" required="required" name="confirm_password">
            </div>
          </div>
        </div>
        <div class="input-group">
            <button type="submit" name="reset_cmd" class="btn btn-primary mb-3">Reset</button>
        </div>
    </div>
</form>
