<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Return Loan Application';
$this->breadcrumbs=array(
	'loanaccounts'=>array('loanaccounts/admin'),
	'ReturnApplication'=>array('loanaccounts/return/'.$model->loanaccount_id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5 class="title">Return Application</h5>
            <hr class="common_rule">
        </div>
        <div class="card-body">
          <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                      <table class="table table-condensed table-striped">
                        <tr>
                          <td>Branch</td>
                          <td><?=$model->getBorrowerBranchName();?></td>
                        </tr>
                        <tr>
                          <td>Member Name</td>
                          <td><div class="text-wrap"><?=$model->getBorrowerName();?></div></td>
                        </tr>
                        <tr>
                          <td>Employer Name</td>
                          <td><div class="text-wrap"><?=$model->getBorrowerEmployer();?></div></td>
                        </tr>
                        <tr>
                          <td>Date Joined</td>
                          <td><?=$model->getBorrowerJoiningDate();?></td>
                        </tr>
                        <tr>
                          <td>Phone Number</td>
                          <td><?=$model->getBorrowerPhoneNumber();?></td>
                        </tr>
                        <tr>
                          <td>Relationship Manager</td>
                          <td> <div class="text-wrap"><?=$model->getRelationshipManagerName();?></div></td>
                        </tr>
                        <tr>
                          <td>Account Opening Date</td>
                          <td><?=date('jS M Y',strtotime($model->created_at));?></td>
                        </tr>
                        <tr>
                          <td>Account Number</td>
                          <td><?=$model->account_number;?></td>
                        </tr>
                      </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12">
                      <table class="table table-condensed table-striped">
                        <tr>
                          <td>Savings Balance</td>
                          <td><?=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($model->user_id));;?></td>
                        </tr>
                        <tr>
                          <td>Amount Disbursed</td>
                          <td><?=CommonFunctions::asMoney($model->amount_approved);?></td>
                        </tr>
                        <tr>
                          <td>Repayment Period</td>
                          <td><?=$model->repayment_period;?> Months</td>
                        </tr>
                        <tr>
                          <td>First Repayment Date</td>
                          <td><?=date('jS M Y',strtotime($model->repayment_start_date));?> </td>
                        </tr>
                        <tr>
                          <td>Interest Rate</td>
                          <td><?=$model->interest_rate;?> % p.m.</td>
                        </tr>
                        <tr>
                          <td>Monthly Installment</td>
                          <td><?=CommonFunctions::asMoney(LoanApplication::getEMIAmount($model->loanaccount_id));?> </td>
                        </tr>
                        <tr>
                          <td>Loan Arrears</td>
                          <td><?=CommonFunctions::asMoney($model->arrears);?>
                            <?php if(($model->arrears > 0) && (Yii::app()->user->user_level === '0')):?>
                              <?php
                                $writeOffLink="<a href='#' class='btn btn-danger btn-sm' title='Write Off Arrears'  onclick='LoanWriteOffArrears(\"".$model->loanaccount_id."\",\"".$model->arrears."\")'> <i class='fa fa-remove'></i></a>";
                              ?>
                              &emsp;&emsp;&emsp;&emsp;<?=$writeOffLink;?>
                            <?php endif;?>
                          </td>
                        </tr>
                        <tr>
                          <td>Penalty Accrued</td>
                          <td><?=CommonFunctions::asMoney(LoanRepayment::getAccruedPenalty($model->loanaccount_id));?>
                            <?php if((LoanRepayment::getAccruedPenalty($model->loanaccount_id) > 0) && (Yii::app()->user->user_level === '0')):?>
                              <?php
                                $writeOffLink="<a href='#' class='btn btn-danger btn-sm' title='Write Off Penalty'  onclick='LoanWriteOffPenalty(\"".$model->loanaccount_id."\",\"".LoanRepayment::getAccruedPenalty($model->loanaccount_id)."\")'> <i class='fa fa-remove'></i></a>";
                              ?>
                              &emsp;&emsp;<?=$writeOffLink;?>
                            <?php endif;?>
                          </td>
                        </tr>
                        <tr>
                          <td>Loan Status</td>
                          <td><?=$model->getLoanAccountStatus();?></td>
                        </tr>
                      </table>
                    </div>
            </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <form method="post">
            <br>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="form-group">
                    <textarea class="form-control" name="comment" cols='5' rows='5' placeholder="Please provide brief comment for redirecting the application..."></textarea>
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Return Application" id="return_loan_cmd" name="return_loan_cmd">
                  </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                      <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" type="submit" class="btn btn-default pull-right">Cancel Action</a>
                  </div>
              </div>
            </form>
          </div>
        </div>
     </div>
  </div>
</div>