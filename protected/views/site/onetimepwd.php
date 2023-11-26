<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Account Verification - OTP';
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
  .imageHolder{
    background: url(../images/site/tcl_logo.jpg) repeat 0 0 !important; 
  }
</style>
<div class="card card-login card-plain">
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
    <center><h4 class="authTitle"> ONE TIME PASSWORD - OTP </h4><hr class="authHR"></center>
    <div class="card-body col-lg-12 col-md-12 col-sm-12">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <input type="text" placeholder="OTP" class="form-control" required="required" name="otp">
            </div>
          </div>
        </div>
    </div>
    <div class="card-footer col-md-12 col-lg-12 col-sm-12">
      <div class="col-md-6 col-lg-6 col-sm-12">
        <input type="submit" name="verify_account_cmd" class="btn btn-primary pull-left" value="Verify Account">
  		</div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <a href="<?=Yii::app()->createUrl('site/regenerate');?>" class="btn btn-info pull-right">Generate New OTP</a>
      </div>
    </div>
</div>
</form>
