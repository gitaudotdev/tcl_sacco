<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Approve Loan Application';
$this->breadcrumbs=array(
    'Home'=>array('dashboard/admin'),
    'Applications'=>array('admin'),
    'Approve'
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
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <h5 class="title">Approve Application</h5>
                    <hr class="common_rule">
                </div>
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
            </div>
            <div class="card-body col-md-12 col-lg-12 col-sm-12">
                <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/CommitApproval');?>">
                    <input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>Savings Balance</label>
                                <input type="text" class="form-control" readonly="readonly" value="<?=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($model->user_id));?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>Amount Applied</label>
                                <input type="text" class="form-control" required="required" value="<?=$model->amount_applied;?>" name="amount_applied">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>Repayment Period</label>
                                <input type="text" class="form-control" required="required" value="<?=$model->repayment_period;?>" name="repayment_period">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>Repayment Start Date</label>
                                <input type="text" class="form-control" required="required" value="<?=$model->repayment_start_date;?>" name="repayment_start_date" id="normaldatepicker">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>Penalty Amount</label>
                                <input type="text" class="form-control" required="required" value="" name="penalty_amount">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label>Reason for Approval</label>
                                <textarea class="form-control" cols="15" rows="15" name="reason" placeholder="Please provide a reason for approving the application ..." required="required"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Approve Application">
                                <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" type="submit" class="btn btn-default pull-right">Cancel Action</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
