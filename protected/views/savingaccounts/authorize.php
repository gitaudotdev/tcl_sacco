<?php
$this->pageTitle=Yii::app()->name . ' - Saving Account Authorization';
$this->breadcrumbs=array(
	'Savingaccounts'=>array('savingaccounts/admin'),
	'Authorize'=>array('savingaccounts/authorize/'.$model->savingaccount_id)
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
    <div class="card">
        <div class="card-body">
              <div class="card-header">
                  <h5 class="title">Saving Account Authorization</h5>
                  <hr class="common_rule">
              </div>
              <br>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <table class="table table-bordered table-hoverped">
                  <tr>
                    <td>Account Holder</td>
                    <td><strong><?=$model->SavingAccountHolderName;?></strong></td>
                  </tr>
                  <tr>
                    <td>Branch</td>
                    <td><strong><?=$model->SavingAccountHolderBranch;?></strong></td>
                  </tr>
                  <tr>
                    <td>ID Number</td>
                    <td><strong><?=$model->SavingAccountHolderIDNumber;?></strong></td>
                  </tr>
                  <tr>
                    <td>Phone Number</td>
                    <td><strong><?=$model->SavingAccountHolderPhoneNumber;?></strong></td>
                  </tr>
                  <tr>
                    <td>Email Address</td>
                    <td><strong><?=$model->SavingAccountHolderEmail;?></strong></td>
                  </tr>
                  <tr>
                    <td>Member Since</td>
                    <td><strong><?=$model->SavingAccountHolderMemberSince;?></strong></td>
                  </tr>
                  <tr>
                    <td>Recent Login Date</td>
                    <td><strong><?=$model->SavingAccountHolderLastLogin;?></strong></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-4 col-lg-4 col-sm-12">
                <table class="table table-bordered table-hover">
                  <tr>
                    <td>Account Number</td>
                    <td><strong><?=$model->account_number;?></strong></td>
                  </tr>
                  <tr>
                    <td>Account Type</td>
                    <td><strong><?=$model->AccountType;?></strong></td>
                  </tr>
                  <tr>
                    <td>Interest Rate(Monthly)</td>
                    <td><strong><?=$model->AccountInterestRate;?></strong></td>
                  </tr>
                  <tr>
                    <td>Opening Balance</td>
                    <td><strong><?=$model->AccountOpeningBalance;?></strong></td>
                  </tr>
                  <tr>
                    <td>Authorization</td>
                    <td><strong><?=$model->AccountAuthStatus;?></strong></td>
                  </tr>
                  <tr>
                    <td>Account Balance</td>
                    <td><strong><?=SavingFunctions::getSavingAccountBalance($model->savingaccount_id);?></strong></td>
                  </tr>
                </table>
              <br>
              </div>
              <?php if(in_array($model->is_approved,array('0','2')) && Navigation::checkIfAuthorized(54) == 1):?>
              <div class="col-md-12 col-lg-12 col-sm-12" style="padding-bottom:25px;">
                <hr class="common_rule">
                <?php
                  if(Navigation::checkIfAuthorized(55) == 1){
                    $approveLink = "<a href='#' class='btn btn-success'
                     onclick='Authenticate(\"".Yii::app()->createUrl('savingaccounts/approve/'.$model->savingaccount_id)."\")'
                    style='margin-right:1%;'> Approve</a>";
                  }else{
                    $approveLink = "";
                  }

                  if(Navigation::checkIfAuthorized(56) == 1){
                    $rejectLink = "<a href='#' class='btn btn-danger'
                    onclick='Authenticate(\"".Yii::app()->createUrl('savingaccounts/reject/'.$model->savingaccount_id)."\")'
                    style='margin-right:1%;'> Reject</a>";
                  }else{
                    $rejectLink = " ";
                  }
                ?>
                <div>
                  <a href="<?=Yii::app()->createUrl('savingaccounts/admin');?>" class="btn btn-info" style="margin-right:1%;"><i class="fa fa-arrow-left"></i> Previous</a>
                  <?=$approveLink;?><?=$rejectLink;?>
                </div>
              </div>
            <?php endif;?>
        </div>
    </div>
</div>