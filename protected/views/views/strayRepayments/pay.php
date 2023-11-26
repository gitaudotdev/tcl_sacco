<?php
/* @var $this StaffController */
/* @var $model Staff */
$this->pageTitle=Yii::app()->name . ' - Pay To Account';
$this->breadcrumbs=array(
	'StrayRepayments'=>array('admin'),
	'Pay'=>array('strayRepayments/pay/'.$model->id)
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
            <div class="col-md-12 col-lg-12 col-sm-12">
              <?=CommonFunctions::displayFlashMessage($successType);?>
            </div>
            <?php endif;?>
            <?php if($infoStatus === 1):?>
              <div class="col-md-12 col-lg-12 col-sm-12">
                <?=CommonFunctions::displayFlashMessage($infoType);?>
              </div>
            <?php endif;?>
            <?php if($warningStatus === 1):?>
              <div class="col-md-12 col-lg-12 col-sm-12">
                <?=CommonFunctions::displayFlashMessage($warningType);?>
              </div>
            <?php endif;?>
            <?php if($dangerStatus === 1):?>
              <div class="col-md-12 col-lg-12 col-sm-12">
                <?=CommonFunctions::displayFlashMessage($dangerType);?>
              </div>
            <?php endif;?>
        <div class="card-body">
            <div class="card-header">
              <h5 class="title">Pay To Loan Account</h5>
              <hr class="common_rule">
            </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <br>
            <form method="post" action="<?=Yii::app()->createUrl('strayRepayments/commitPayment');?>">
              <input type="hidden" name="stray" value="<?=$model->id;?>">
              <input type="hidden" name="repayment_amount" value="<?=$model->amount;?>">
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    <label style="margin-bottom: 5%!important;">Transacted By: </label>
                     <input type="text" class="form-control" value="<?=$model->getClientName();?>" readonly="readonly">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    <label style="margin-bottom: 5%!important;">Transaction Phone: </label>
                    <input type="text" class="form-control" value="<?=$model->source;?>" readonly="readonly">
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    <label style="margin-bottom: 5%!important;">Transaction ID/ACC Number: </label>
                    <input type="text" class="form-control" value="<?=$model->clientAccount;?>" readonly="readonly">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    <label style="margin-bottom: 5%!important;">Amount Transacted: </label>
                     <input type="text" class="form-control" value="<?=$model->getAmountTransacted();?>" readonly="readonly">
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-8 col-lg-8 col-sm-12">
                  <div class="form-group">
                    <label style="margin-bottom: 5%!important;">Kindly select the valid loan account to pay to: </label>
                     <select name="loanaccount" class="selectpicker" required="required">
                      <option value="">--Select The Valid Loan Account--</option>
                      <?php
                      if(!empty($loanaccounts)){
                        foreach($loanaccounts as $loanaccount){
                          echo "<option value='";echo $loanaccount->loanaccount_id;echo"'>
                          $loanaccount->AccountDetails</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    <a href="<?=Yii::app()->createUrl('strayRepayments/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <input type="submit" class="btn btn-primary pull-right" value="Submit Repayment" id="apply_loan_cmd" name="apply_loan_cmd">
                </div>
              </div>
              <br>
            </form>
	        </div>
        </div>
     </div>
  </div>