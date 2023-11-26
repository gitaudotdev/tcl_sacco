<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Transfer Account of Exited Staff';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
  'Staff'=>array('admin'),
  'Transfer_Account'=>array('staff/transfer/'.$model->staff_id),
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
        <div class="card-header">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <h5 class="title">Transfer Staff Account (Exited Staff Member)</h5>
            <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="col-md-12 col-lg-12 col-sm-12">
            <form style="margin:2% 0% 2% 0% !important;" action="<?=Yii::app()->createUrl('staff/commitTransfer');?>" method="POST">
                  <input type="hidden" name="current_user" value="<?=$model->staff_id;?>">
                  <div class="row">
                    <div class="col-md-9 col-lg-9 col-sm-12">
                     <p style="color:red !important;">Please Note that <strong>clients, loan accounts, loan repayments, saving accounts and user accounts </strong> under the current staff member will be transferred to the staff this account is being transferred to.</p>
                     <p style="color:red !important;"><i>The current staff account will be deactivated and will no longer login and use the system.</i></p>
                     <hr>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                     <label >Current Staff Member </label>
                     <div class="form-group">
                      <input type="text" name="current_staff" class="form-control" readonly="readonly" value="<?=$model->StaffFullName;?>">
                     </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                      <label >Select Member to Transfer Account To </label>
                       <div class="form-group">
                        <select class="selectpicker form-control-changed" name="staff" required="required" id="staff">
                            <option value="0">-- STAFF MEMBERS --</option>
                            <?php if(!empty($users)):?>
                                <?php foreach($users as $user):?>
                                    <option value="<?=$user->user_id;?>">
                                        <?=$user->UserFullName;?>
                                    </option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary" name="transfer_account_cmd">Transfer</button>
                      </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                      <div class="form-group">
                        <a href="<?=Yii::app()->createUrl('staff/admin');?>" class="btn btn-default pull-right">Cancel</a>
                      </div>
                    </div>
                  </div>
              <br>
            </form>
          </div>
        </div>
     </div>
  </div>
</div>