<?php
$this->pageTitle=Yii::app()->name . ' -  Send Guarantor SMS Notification';
$this->breadcrumbs=array(
	'Application'=>array('loanaccounts/'.$loan->loanaccount_id),
	'Guarantor'=>array('guarantors/notify/'.$model->guarantor_id)
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
              <h5 class="title">Loan Guarantor: Send Notification</h5>
              <hr class="common_rule">
          </div>
          <div class="col-md-12 col-lg-12 col-sm-12"><br>
              <div class="col-md-10 col-lg-10 col-sm-12">
              <h5 class="title">Loan Details</h5><br> </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
              <table class="table table-bordered table-hover">
                <tr>
                  <td>Branch</td>
                  <td><?=$loan->getBorrowerBranchName();?></td>
                </tr>
                <tr>
                  <td>Member Name</td>
                  <td><div class="text-wrap"><?=$loan->getBorrowerName();?></div></td>
                </tr>
                <tr>
                  <td>Phone Number</td>
                  <td><?=$loan->getBorrowerPhoneNumber();?></td>
                </tr>
                <tr>
                  <td>Employer</td>
                  <td><div class="text-wrap"><?=$loan->getBorrowerEmployer();?></div></td>
                </tr>
                <tr>
                  <td>Relationship Manager</td>
                  <td><div class="text-wrap"><?=$loan->getRelationshipManagerName();?></div></td>
                </tr>
                <tr>
                  <td>Account Opening Date</td>
                  <td><?=date('jS M Y',strtotime($loan->created_at));?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-12">
              <table class="table table-bordered table-hover">
                <tr>
                <td>Savings Balance</td>
                <td>
                  <?=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($loan->user_id));?>
                </td>
              </tr>
                <tr>
                  <td>Account Number</td>
                  <td><?=$loan->account_number;?></td>
                </tr>
                <tr>
                  <td>Amount Applied</td>
                  <td><?=CommonFunctions::asMoney($loan->amount_applied);?></td>
                </tr>
                <tr>
                  <td>Repayment Period</td>
                  <td><?=$loan->repayment_period;?> Months</td>
                </tr>
                <tr>
                  <td>Interest Rate</td>
                  <td><?=$loan->interest_rate;?> % p.m.</td>
                </tr>
                <tr>
                  <td>Installment</td>
                  <td><strong><?=CommonFunctions::asMoney(LoanCalculator::getEMIAmount($loan->amount_applied,$loan->interest_rate,$loan->repayment_period));?> </strong></td>
                </tr>
              </table>
          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12"><br><br>
              <h5 class="title">Draft SMS Message</h5>
              <hr class="common_rule">
            <form method="POST">
            <br>
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
			         <textarea class="form-control" cols="15" rows="5" name="textMessage" placeholder="Draft brief message..." required="required"></textarea>
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                    <a href="<?=Yii::app()->createUrl('loanaccounts/'.$loan->loanaccount_id);?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary pull-right" value="Send" name="send_guarantor_cmd">
                  </div>
              </div>
              </div>
              <br><br>
            </form>
	        </div>
        </div>
     </div>
  </div>
</div>