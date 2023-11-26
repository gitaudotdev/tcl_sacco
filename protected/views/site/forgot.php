<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Reset Account Password';
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
    <center><h4 class="authTitle"> RESET PASSWORD</h4><hr class="authHR"></center>
    <div class="card-body col-lg-12 col-md-12 col-sm-12">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <input type="email" placeholder="Email Address..." class="form-control" required="required" name="email">
            </div>
          </div>
        </div>
    </div>
    <div class="card-footer col-md-12 col-lg-12 col-sm-12">
      <div class="col-md-6 col-lg-6 col-sm-12">
        <input type="submit" name="forgot_cmd" class="btn btn-primary pull-left" value="Reset Password">
  		</div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <a href="<?=Yii::app()->createUrl('site/login');?>" class="btn btn-default pull-right">Cancel</a>
      </div>
    </div>
</div>
</form>
