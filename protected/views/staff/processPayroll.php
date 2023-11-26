<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Process Staff Payroll';
$this->breadcrumbs=array(
    'Staff'=>array('admin'),
    'Process_Payroll'=>array('processPayroll'),
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
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Process Payroll</h5>
            <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="col-md-12 col-lg-12 col-sm-12">
            <form method="POST" action="<?=Yii::app()->createUrl('staff/commitPayrollProcess');?>">

                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                       <h5>Please select staff members to process payroll for </h5>
                       <hr style="border:unset !important; border-bottom: 2px dotted #dedede !important;">
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <?=StaffFunctions::displayStaffMembers($members);?>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                    <br>
                      <hr>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label >Payroll Month</label>
                          <input type="text" class="form-control" id="month_date" placeholder="Month" name="payroll_month" required="required">
                        </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                      <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Process Payroll" name="process_payroll_cmd">
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