<?php
$this->pageTitle   =  Yii::app()->name . ' -  View Loan Application';
$this->breadcrumbs =  array(
    'Applications'   => array('admin'),
    'View'           => array('loanaccounts/'.$model->loanaccount_id),
);
$succesStatus  = CommonFunctions::checkIfFlashMessageSet('success');
$infoStatus    = CommonFunctions::checkIfFlashMessageSet('info');
$warningStatus = CommonFunctions::checkIfFlashMessageSet('warning');
$dangerStatus  = CommonFunctions::checkIfFlashMessageSet('danger');
$allowed       = array('2','5','6','7');
//echo 'isaac'; exit;
?>
<style type="text/css">
    tr>td{
        font-size: 0.85em !important;
    }
    tr>td:last-of-type{
        font-weight: bold !important;
        font-size: 0.80em !important;
    }
    .nav-pills{
        padding: 10px 10px !important;
        margin-top: -1.5% !important;
    }
    .nav-item{
        font-size:.85em !important;
    }
    .nav-pills .nav-link{
        background-color: #222d32 !important;
        color:#fff !important;
        border-radius: 0px !important;
    }
    .nav-pills .nav-link.active {
        color: #fff !important;
        background-color: green !important;
    }
    #date_error{
        margin-left: 2% !important;
        display: none;
    }
</style>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12">
        <!--MEMBER DETAILS-->
        <div class="card">
            <?php if($succesStatus === 1):?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <?=CommonFunctions::displayFlashMessage('success');?>
                </div>
            <?php endif;?>
            <?php if($infoStatus === 1):?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <?=CommonFunctions::displayFlashMessage('info');?>
                </div>
            <?php endif;?>
            <?php if($warningStatus === 1):?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <?=CommonFunctions::displayFlashMessage('warning');?>
                </div>
            <?php endif;?>
            <?php if($dangerStatus === 1):?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <?=CommonFunctions::displayFlashMessage('danger');?>
                </div>
            <?php endif;?>
            <div class="card-body">
                <div class="card-header">
                    <h4 class="title">View Loan: <?=$model->account_number;?></h4>
                    <hr class="common_rule">
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <br>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>Branch</td>
                                    <td><?=$model->getBorrowerBranchName();?></td>
                                </tr>
                                <tr>
                                    <td>Member</td>
                                    <td>
                                        <div class="text-wrap">
                                            <?=$model->getBorrowerName();?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Residence</td>
                                    <td>
                                        <div class="text-wrap">
                                            <?=$model->getLoanAccountUserResidence();?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Employer</td>
                                    <td>
                                        <div class="text-wrap">
                                            <?=$model->getBorrowerEmployer();?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date Joined</td>
                                    <td><?=$model->getBorrowerJoiningDate();?></td>
                                </tr>
                                <tr>
                                    <td>Phone #</td>
                                    <td><?=$model->getBorrowerPhoneNumber();?>
                                        &emsp;&emsp;&emsp;<a href="#" data-toggle="modal"
                                                             data-target="#SmsProfile" class="btn btn-primary btn-xs" title="Send SMS"><i class="now-ui-icons ui-1_send"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Manager</td>
                                    <td>
                                        <div class="text-wrap">
                                            <?=$model->getRelationshipManagerName();?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Opening Date</td>
                                    <td><?=date('jS M Y',strtotime($model->created_at));?></td>
                                </tr>
                                <tr>
                                    <td>Account #</td>
                                    <td><?=$model->account_number;?></td>
                                </tr>
                                <tr>
                                    <td>Loan Limit</td>
                                    <td><?=$model->ClientMaximumAmount;?></td>
                                </tr>
                                <tr>
                                    <td>Savings</td>
                                    <td>
                                        <?=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($model->user_id));?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <table class="table table-bordered table-hover table-responsive">

                                <tr>
                                    <td><div class="text-wrap">Amount Disbursed</div></td>
                                    <td><div class="text-wrap"><?=CommonFunctions::asMoney(LoanManager::getPrincipalDisbursed($model->loanaccount_id));?></div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Repayment Period</div></td>
                                    <td><div class="text-wrap"><?=$model->repayment_period;?> Months</div></td>
                                </tr>

                                <tr>
                                    <td><div class="text-wrap">Date Disbursed</div></td>
                                    <td><div class="text-wrap"><?=$model->FormattedDisbursedDate;?></div></td>
                                </tr>

                                <tr>
                                    <td><div class="text-wrap">First Repayment Date</div></td>
                                    <td><div class="text-wrap"><?=date('jS M Y',strtotime($model->repayment_start_date));?></div> </td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Interest Rate</div></td>
                                    <td><div class="text-wrap"><?=round($model->interest_rate);?> % p.m.</div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">One-off Installment</div></td>
                                    <td><div class="text-wrap"><?=CommonFunctions::asMoney(LoanApplication::getEMIAmount($model->loanaccount_id));?></div> </td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Penalty Accrued</div></td>
                                    <td><div class="text-wrap"><?=CommonFunctions::asMoney(LoanManager::getUnpaidAccruedPenalty($model->loanaccount_id));?>
                                            <?php if((LoanManager::getUnpaidAccruedPenalty($model->loanaccount_id) > 0) && (Navigation::checkIfAuthorized(49) === 1)):?>
                                                <?php
                                                $writeOffLink="<a href='#' class='btn btn-danger btn-sm' title='Write Off Penalty'  onclick='LoanWriteOffPenalty(\"".$model->loanaccount_id."\",\"".LoanManager::getUnpaidAccruedPenalty($model->loanaccount_id)."\")'> <i class='fa fa-remove'></i></a>";
                                                ?>
                                                <?=$writeOffLink;?>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Process Status</div></td>
                                    <td><div class="text-wrap"><?=$model->getLoanAccountStatus();?></div></td>
                                </tr>

                                <tr>
                                    <td><div class="text-wrap">Amount Approved</div></td>
                                    <td><div class="text-wrap"><?=strtoupper($model->amount_receivable);?></div></td>
                                </tr>

                                <tr>
                                    <td><div class="text-wrap">Days Since Disbursed</div></td>
                                    <td><div class="text-wrap"><?=$model->DaysPastDisbursementDate22;?></div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Days Since Last Paid</div> </td>
                                    <td><div class="text-wrap"><?=$model->DaysPastLastAccountPayment;?></div></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <table class="table table-bordered table-hover table-responsive">
                                <tr>
                                    <td><div class="text-wrap">Principal Paid</div></td>
                                    <td><div class="text-wrap"><?=CommonFunctions::asMoney(LoanManager::getPrincipalPaid($model->loanaccount_id));?></div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Principal Balance</div></td>
                                    <td><div class="text-wrap">
                                            <?=CommonFunctions::asMoney(LoanManager::getPrincipalBalance($model->loanaccount_id));?>
                                            <?php if( (LoanManager::isAccountPrincipalDeletable($model->loanaccount_id) === 1) && Navigation::checkIfAuthorized(200) === 1):?>
                                                <?php
                                                $deletePrincipalBalLink="<a href='#' class='btn btn-danger btn-sm' title='Delete Account Principal'  onclick='LoanDeleteLoanPrincipalBalance(\"".$model->loanaccount_id."\",\"".LoanManager::getPrincipalBalance($model->loanaccount_id)."\")'> <i class='fa fa-remove'></i></a>";
                                                ?>
                                                <?=$deletePrincipalBalLink;?>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Interest Paid</div></td>
                                    <td><div class="text-wrap"><?=CommonFunctions::asMoney(LoanManager::getPaidAccruedInterest($model->loanaccount_id));?></div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Interest Balance</div></td>
                                    <td><div class="text-wrap"><?=CommonFunctions::asMoney(LoanManager::getUnpaidAccruedInterest($model->loanaccount_id));?></div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Loan Balance</div></td>
                                    <td><div class="text-wrap"><?=CommonFunctions::asMoney(LoanManager::getActualLoanBalance($model->loanaccount_id));?>
                                            <?php if((LoanManager::getActualLoanBalance($model->loanaccount_id) > 0) && (Navigation::checkIfAuthorized(47) === 1)):?>
                                                <?php
                                                $writeOffLink="&nbsp;<a href='#' class='btn btn-danger btn-sm' title='Write Off Balance' onclick='LoanWriteOffBalance(\"".$model->loanaccount_id."\",\"".LoanManager::getActualLoanBalance($model->loanaccount_id)."\")'> <i class='fa fa-remove'></i> </a>";
                                                ?>
                                                <?=$writeOffLink;?>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Accrued Interest</div></td>
                                    <td><div class="text-wrap">
                                            <?=CommonFunctions::asMoney(LoanManager::getUnpaidAccruedInterest($model->loanaccount_id));?>
                                            <?php if((LoanManager::getUnpaidAccruedInterest($model->loanaccount_id) > 0) && (Navigation::checkIfAuthorized(48) === 1)):?>
                                                <?php
                                                $writeOffLink="&nbsp;<a href='#' class='btn btn-danger btn-sm' title='Write Off Accrued Interests'  onclick='LoanWriteOffAccruedInterest(\"".$model->loanaccount_id."\",\"".LoanManager::getUnpaidAccruedInterest($model->loanaccount_id)."\")'> <i class='fa fa-remove'></i></a>";
                                                ?>
                                                <?=$writeOffLink;?>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td><div class="text-wrap">Insurance Fee</div></td>
                                    <td><div class="text-wrap"><?=strtoupper($model->insurance_fee);?></div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Processing Fee</div></td>
                                    <td><div class="text-wrap"><?=strtoupper($model->processing_fee);?></div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Total Deductions</div></td>
                                    <td><div class="text-wrap"><?=strtoupper($model->deduction_fee);?></div></td>
                                </tr>
                                <tr>
                                    <td><div class="text-wrap">Amount to Disburse</div></td>
                                    <td><div class="text-wrap"><?=strtoupper($model->amount_approved);?></div></td>
                                </tr>

                                <tr>
                                    <td><div class="text-wrap">Loan Status</div></td>
                                    <td><div class="text-wrap"><?=strtoupper($model->EmptyLoanAccountStatus);?></div></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <br>
                        <hr class="common_rule">
                        <?php
                        switch($model->loan_status){
                            case '0':
                                $responseMessage = "<p><strong>Loan Account not yet approved or rejected.</strong></p>";
                                break;

                            case '3':
                                $rejectionReason = $model->AccountRejectionReason;
                                $rejector        = $model->AccountRejectedBy;
                                $responseMessage = "<p>Loan Account Rejected by: <strong>$rejector</strong><br> Rejection Reason: <strong>$rejectionReason</strong></p>";
                                break;

                            default:
                                $approvalReason = $model->approval_reason;
                                $approver       = $model->AccountApprovedBy;
                                $responseMessage= "<p>Loan Account Approved by: <strong>$approver</strong><br> Approval Reason: <strong>$approvalReason</strong></p>";
                                break;
                        }
                        echo $responseMessage;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!--LOAN DETAILS TABULATED-->
        <div class="row">
            <div class="col-md-12">
                <div class="card" data-color="blue">
                    <div class="card-header text-center" data-background-color="blue">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link" href="#repayments" data-toggle="tab" role="tab" aria-controls="repayments" aria-selected="true">
                                    <i class="now-ui-icons business_money-coins"></i>
                                    Repayments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#schedule" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="schedule" aria-selected="false">
                                    <i class="now-ui-icons files_paper"></i>
                                    Schedule
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#pending_dues" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="pending_dues" aria-selected="false">
                                    <i class="now-ui-icons ui-1_calendar-60"></i>
                                    Pending Dues
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#loan_collateral" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="loan_collateral" aria-selected="false">
                                    <i class="now-ui-icons shopping_tag-content"></i>
                                    Collateral
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#loan_files" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="loan_files" aria-selected="false">
                                    <i class="now-ui-icons files_single-copy-04"></i>
                                    Files
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#loan_comments" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="loan_comments" aria-selected="false">
                                    <i class="now-ui-icons ui-2_chat-round"></i>
                                    Comments
                                </a>
                            </li>
                            <?php if(Navigation::checkIfAuthorized(281) == 1):?>
                                <li class="nav-item">
                                    <a class="nav-link" href="#loan_history" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="loan_comments" aria-selected="false">
                                        <i class="now-ui-icons files_box"></i>
                                        History
                                    </a>
                                </li>
                            <?php endif;?>
                            <li class="nav-item">
                                <a class="nav-link" href="#loan_guarantors" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="loan_guarantors" aria-selected="false">
                                    <i class="now-ui-icons design_bullet-list-67"></i>
                                    Guarantors
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#saving_transactions" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="saving_transactions" aria-selected="false">
                                    <i class="now-ui-icons media-2_sound-wave"></i>
                                    Savings
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="repayments">
                                <h4 class="title"> Repayments</h4>
                                <hr class="common_rule">
                                <div class="row">
                                    <?php if(Navigation::checkIfAuthorized(37) == 1):?>
                                        <div class="col-md-3 col-lg-3 col-sm-12">
                                            <a href="<?=Yii::app()->createUrl('loanaccounts/repay/'.$model->loanaccount_id);?>" class="btn btn-success">
                                                Add Repayment
                                            </a>
                                        </div>
                                    <?php else:?>
                                        <div class="col-md-3 col-lg-3 col-sm-12"></div>
                                    <?php endif;?>
                                    <?php if((Navigation::checkIfAuthorized(283) == 1) && (in_array($model->loan_status,$allowed))):?>
                                        <div class="col-md-3 col-lg-3 col-sm-12">
                                            <a href='#' class='btn btn-warning' style='font-weight:bold;' onclick='LoadClientPaymentModal()'>MPESA Client PayLoan</a>
                                        </div>
                                    <?php else:?>
                                        <div class="col-md-3 col-lg-3 col-sm-12"></div>
                                    <?php endif;?>
                                    <?php if(Navigation::checkIfAuthorized(175) == 1):?>
                                        <div class="col-md-6 col-lg-6 col-sm-12">
                                            <div class="pull-right">
                                                <a href='#' class='btn btn-primary' style='font-weight:bold;' onclick='LoadStatementModal()'>Statement</a>
                                            </div>
                                        </div>
                                    <?php else:?>
                                        <div class="col-md-6 col-lg-6 col-sm-12"></div>
                                    <?php endif;?>
                                    <div class="col-md-12 col-lg-12 col-sm-12"><hr class="common_rule"></div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <table class="table table-condensed table-bordered">
                                            <thead>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Principal</th>
                                            <th>Interest</th>
                                            <th>Penalty</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Balance</th>
                                            <th>Transacted By</th>
                                            </thead>
                                            <tbody>
                                            <?php if(!empty($repayments)):?>
                                                <?php $i=1;?>
                                                <?php foreach($repayments as $repayment):?>
                                                    <?php
                                                    if($repayment->is_void === '3'){
                                                        $statusPrint="Before Restructuring";
                                                    }elseif($repayment->is_void === '4'){
                                                        $statusPrint="Before Top Up/Principal Deleted";
                                                    }else{
                                                        $statusPrint='Current';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?=$i;?></td>
                                                        <td><?=$repayment->getFormattedTransactionDate();?></td>
                                                        <td><?=$repayment->getPrincipalPaid();?></td>
                                                        <td><?=$repayment->getInterestPaid();?></td>
                                                        <td><?=$repayment->getPenaltyPaid();?></td>
                                                        <td><?=$repayment->getTotalAmountPaid();?></td>
                                                        <td><strong><?=$statusPrint;?></strong></td>
                                                        <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalLoanBalanceFrom($repayment->loanaccount_id,$repayment->date));?></td>
                                                        <td><?=$repayment->getTransactedBy();?></td>
                                                    </tr>
                                                    <?php $i++;?>
                                                <?php endforeach;?>
                                                <tr>
                                                    <td></td><td></td><td></td><td></td><td></td><td></td>
                                                    <td><strong>Actual Balance </strong></td>
                                                    <td><strong><?=CommonFunctions::asMoney(LoanManager::getActualLoanBalance($model->loanaccount_id));?></strong></td><td></td>
                                                </tr>
                                            <?php endif;?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="schedule">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <h4 class="title">Repayment Schedule </h4>
                                        <hr class="common_rule">
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php
                                        LoanTransactionsFunctions::displayLoanRepaymentSchedule($model->loanaccount_id);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pending_dues">
                                <h4 class="title"> Pending Dues </h4>
                                <hr class="common_rule">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <table class="table table-condensed table-bordered" style="font-size:12px !important;">
                                            <?php
                                            $principalPayable = LoanApplication::getLoanAccount($model->loanaccount_id)->amount_approved;
                                            $interestPayable  = LoanTransactionsFunctions::getTotalInterestAmount($model->loanaccount_id);
                                            $penaltyPayable   = 0;
                                            $totalPayables    = $principalPayable + $interestPayable + $penaltyPayable;
                                            $principalPaid    = LoanRepayment::getTotalPrincipalPaid($model->loanaccount_id);
                                            $interestPaid     = LoanRepayment::getTotalInterestPaid($model->loanaccount_id);
                                            $penaltyPaid      = 0;
                                            $totalPaid        = $principalPaid + $interestPaid  + $penaltyPaid;
                                            ?>
                                            <thead>
                                            <th>Based on Loan Terms</th>
                                            <th>Principal</th>
                                            <th>Interest</th>
                                            <th>Penalty</th>
                                            <th>Total</th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td style="background-color: red !important;font-size:14px !important;padding:10px 20px !important;">Total Due</td>
                                                <td><?=CommonFunctions::asMoney($principalPayable);?></td>
                                                <td><?=CommonFunctions::asMoney($interestPayable);?></td>
                                                <td><?=CommonFunctions::asMoney($penaltyPayable);?></td>
                                                <td><?=CommonFunctions::asMoney($totalPayables);?></td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: green !important;font-size:14px !important;padding:10px 20px !important;">Total Paid</td>
                                                <td><?=CommonFunctions::asMoney($principalPaid);?></td>
                                                <td><?=CommonFunctions::asMoney($interestPaid);?></td>
                                                <td><?=CommonFunctions::asMoney($penaltyPaid);?></td>
                                                <td><?=CommonFunctions::asMoney($totalPaid);?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:14px !important;padding:10px 20px !important;">Balance</td>
                                                <td><?=CommonFunctions::asMoney($principalPayable-$principalPaid);?></td>
                                                <td><?=CommonFunctions::asMoney($interestPayable-$interestPaid);?></td>
                                                <td><?=CommonFunctions::asMoney($penaltyPayable-$penaltyPaid);?></td>
                                                <td><?=CommonFunctions::asMoney($totalPayables-$totalPaid);?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="loan_collateral">
                                <h4 class="title"> Collaterals</h4>
                                <hr class="common_rule">
                                <div class="row">
                                    <?php if(Yii::app()->user->user_level !== '3'):?>
                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                            <a href="<?=Yii::app()->createUrl('loanaccounts/collateral/'.$model->loanaccount_id);?>" class="btn btn-success">
                                                Add Collateral
                                            </a>
                                        </div>
                                    <?php else:?>
                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                        </div>
                                    <?php endif;?>
                                    <div class="col-md-12 col-lg-12 col-sm-12"><hr class="common_rule"></div>
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if(!empty($collaterals)):?>
                                            <table class="table table-condensed table-bordered">
                                                <thead>
                                                <th>#</th>
                                                <th>Collateral Type</th>
                                                <th>Collateral Name</th>
                                                <th>Collateral Model</th>
                                                <th>Serial Number</th>
                                                <th>Market Value</th>
                                                <th>Loan to Value Ratio</th>
                                                <th>Collateral Status</th>
                                                </thead>
                                                <tbody>
                                                <?php $i=1;?>
                                                <?php foreach($collaterals as $collateral):?>
                                                    <tr>
                                                        <td><?=$i;?></td>
                                                        <td><?=$collateral->getCollateralTypeName();?></td>
                                                        <td><?=$collateral->name;?></td>
                                                        <td><?=$collateral->getCollateralModel();?></td>
                                                        <td><?=$collateral->getCollateralSerialNumber();?></td>
                                                        <td><?=$collateral->getCollateralMarketValue();?></td>
                                                        <td><?=$collateral->getCollateralLoanToValueRatio();?></td>
                                                        <td><?=$collateral->getCollateralCurrentStatus();?></td>
                                                    </tr>
                                                    <?php $i++;?>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        <?php else:?>
                                            <h4>*** NO LOAN COLLATERAL SUPPLIED FOR THIS APPLICATION ***</h4>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="loan_files">
                                <h4 class="title"> Files </h4>
                                <hr class="common_rule">
                                <div class="row">
                                    <?php if(Navigation::checkIfAuthorized(38) == 1):?>
                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                            <a href="<?=Yii::app()->createUrl('loanaccounts/makeFile/'.$model->loanaccount_id);?>" class="btn btn-success">
                                                Add File
                                            </a>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-sm-12"><hr class="common_rule"></div>
                                    <?php endif;?>
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if(!empty($files)):?>
                                            <table class="table table-condensed table-bordered">
                                                <thead>
                                                <th>#</th>
                                                <th>File Name</th>
                                                <th>File Actions</th>
                                                </thead>
                                                <tbody>
                                                <?php $i=1;?>
                                                <?php foreach($files as $file):?>
                                                    <?php
                                                    $downloadLink=Yii::app()->params['homeDocs'].'/loans/files/'.$file->filename;
                                                    $exportLink="<a href='$downloadLink' class='btn btn-success'> <i class='fa fa-download'></i> Download</a>";
                                                    $viewLink="<a href='#' class='btn btn-info' onclick='loadFile(\"".$file->filename."\")'> <i class='fa fa-eye'></i> View</a>";
                                                    if(Navigation::checkIfAuthorized(176) == 1){
                                                        $deleteAction="<a href='#' class='btn btn-primary' onclick='Authenticate(\"".Yii::app()->createUrl('loanFiles/delete/'.$file->id)."\")' title='Delete Loan File'><i class='fa fa-trash'></i> Delete</a>";
                                                    }else{
                                                        $deleteAction="";
                                                    }
                                                    if(Navigation::checkIfAuthorized(177) == 1){
                                                        $renameAction="<a href='#' class='btn btn-warning' onclick='Authenticate(\"".Yii::app()->createUrl('loanFiles/rename/'.$file->id)."\")' title='Rename Loan File'><i class='fa fa-edit'></i> Rename</a>";
                                                    }else{
                                                        $renameAction="";
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?=$i;?></td>
                                                        <td><?=$file->name;?></td>
                                                        <td><?=$viewLink;?>&nbsp;<?=$renameAction;?>&nbsp;<?=$exportLink;?>&nbsp;<?=$deleteAction;?></td>
                                                    </tr>
                                                    <?php $i++;?>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        <?php else:?>
                                            <h4>*** NO LOAN FILES AVAILABLE FOR THIS APPLICATION ***</h4>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="loan_comments">
                                <h4 class="title">Comments </h4>
                                <hr class="common_rule">
                                <div class="row">
                                    <?php if(Navigation::checkIfAuthorized(258) == 1):?>
                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                            <a href="<?=Yii::app()->createUrl('loanaccounts/comment/'.$model->loanaccount_id);?>" class="btn btn-success">
                                                Add Comment
                                            </a>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-sm-12"><hr class="common_rule"></div>
                                    <?php endif;?>
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if(!empty($comments)):?>
                                            <table class="table table-condensed table-bordered">
                                                <thead>
                                                <th>#</th>
                                                <th>Commented On</th>
                                                <th>Comment Type</th>
                                                <th>Comment Details</th>
                                                <th>Comment Activity</th>
                                                <th>Commented By</th>
                                                </thead>
                                                <tbody>
                                                <?php $i=1;?>
                                                <?php foreach($comments as $comment):?>
                                                    <tr>
                                                        <td><?=$i;?></td>
                                                        <td><?=$comment->getLoanCommentedAt();?></td>
                                                        <td><?=$comment->getCommentTypeName();?></td>
                                                        <td><div class="text-wrap width-150">
                                                                <?=$comment->comment;?></div></td>
                                                        <td><div class="text-wrap width-150">
                                                                <?=$comment->activity;?></div></td>
                                                        <td><?=$comment->getLoanCommentedByName();?></td>
                                                    </tr>
                                                    <?php $i++;?>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        <?php else:?>
                                            <h4>*** NO LOAN COMMENT SUPPLIED FOR THIS APPLICATION ***</h4>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="loan_history">
                                <h4 class="title">Loan History </h4>
                                <hr class="common_rule">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if(!empty($comments)):?>
                                            <table class="table table-condensed table-bordered">
                                                <thead>
                                                <th>#</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Period</th>
                                                <th>Days</th>
                                                <th>Rate</th>
                                                <th>Status</th>
                                                <th>P&L</th>
                                                <th>Acc #</th>
                                                </thead>
                                                <tbody>
                                                <?php $i=1;?>
                                                <?php foreach($others as $other):?>
                                                    <tr>
                                                        <td><?=$i;?></td>
                                                        <td><?=$other->FormattedAmountApplied;?></td>
                                                        <td><?=$other->FormattedApplicationDate;?></td>
                                                        <td><?=$other->LoanAccountPeriod;?></td>
                                                        <td><?=$other->DaysPastDisbursementDate;?></td>
                                                        <td><?=$other->InterestRate;?></td>
                                                        <td><?=$other->CurrentLoanAccountStatus;?></td>
                                                        <td><?=$other->ProfitLoss;?></td>
                                                        <td><?=$other->LoanAccountNumber;?></td>
                                                    </tr>
                                                    <?php $i++;?>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        <?php else:?>
                                            <h4>*** NO LOAN HISTORY FOR THE ACCOUNT HOLDER ***</h4>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="loan_guarantors">
                                <h4 class="title">Guarantors</h4>
                                <hr class="common_rule">
                                <div class="row">
                                    <?php if(Navigation::checkIfAuthorized(107) === 1):?>
                                        <div class="col-md-12 col-lg-12 col-sm-12">
                                            <a href="<?=Yii::app()->createUrl('loanaccounts/addGuarantor/'.$model->loanaccount_id);?>" class="btn btn-success">
                                                Add Guarantor
                                            </a>
                                        </div>
                                        <div class="col-md-12 col-lg-12 col-sm-12"><hr class="common_rule"></div>
                                    <?php endif;?>
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if(!empty($guarantors)):?>
                                            <table class="table table-condensed table-bordered">
                                                <thead>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>ID Number</th>
                                                <th>Phone Number</th>
                                                <th>Created At</th>
                                                </thead>
                                                <tbody>
                                                <?php $i=1;?>
                                                <?php foreach($guarantors as $guarantor):?>
                                                    <tr>
                                                        <td><?=$i;?></td>
                                                        <td><?=$guarantor->GuarantorName;?></td>
                                                        <td><?=$guarantor->GuarantorIDNumber;?></td>
                                                        <td><?=$guarantor->GuarantorPhoneNumber;?>&emsp;&emsp;<?=$guarantor->getNotificationLink();?></td>
                                                        <td><?=date('jS M Y',strtotime($guarantor->created_at));?></td>
                                                    </tr>
                                                    <?php $i++;?>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        <?php else:?>
                                            <h4>*** NO LOAN GUARANTORS AVAILABLE FOR THIS APPLICATION ***</h4>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="saving_transactions">
                                <h4 class="title">Savings</h4>
                                <hr class="common_rule">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <?php if(!empty($transactions)):?>
                                            <?php Tabulate::createMemberSavingTransactionsTable($transactions);?>
                                        <?php else:?>
                                            <span style="color:red;font-style: italic;">The member has not yet saved with the Microfinance or all the saved amount was withdrawn/transferred. </span>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- FILE VIEW MODAL -->
<div class="modal fade" id="loadingFile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:100% !important; height: auto!important;">
        <div class="modal-content">
            <div class="modal-body">
                <div id="loadedFile"></div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- END MODAL -->

<!-- WRITEOFF VIEW MODAL -->
<div class="modal fade" id="loadingWriteOff" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:85% !important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <h5 class="title">Write Off Loan Balance</h5>
                        <hr class="common_rule">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <table class="table table-condensed table-bordered">
                            <tr>
                                <td>Member Name</td>
                                <td><?=$model->getBorrowerName();?></td>
                            </tr>
                            <tr>
                                <td>Branch</td>
                                <td><?=$model->getBorrowerBranchName();?></td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td><?=$model->getBorrowerPhoneNumber();?></td>
                            </tr>
                            <tr>
                                <td>Relationship Manager</td>
                                <td><?=$model->getRelationshipManagerName();?></td>
                            </tr>
                            <tr>
                                <td>Account Number</td>
                                <td><?=$model->account_number;?></td>
                            </tr>
                            <tr>
                                <td>Account Opening Date</td>
                                <td><?=date('jS M Y',strtotime($model->created_at));?></td>
                            </tr>
                            <tr>
                                <td>Amount Disbursed</td>
                                <td><?=CommonFunctions::asMoney($model->amount_approved);?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <table class="table table-condensed table-bordered">
                            <tr>
                                <td>Loan Arrears</td>
                                <td><?=CommonFunctions::asMoney($model->arrears);?></td>
                            </tr>
                            <tr>
                                <td>Principal Paid</td>
                                <td><?=CommonFunctions::asMoney(LoanRepayment::getTotalPrincipalPaid($model->loanaccount_id));?></td>
                            </tr>
                            <tr>
                                <td>Principal Balance</td>
                                <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanPrincipalBalance($model->loanaccount_id));?></td>
                            </tr>
                            <tr>
                                <td>Interest Paid</td>
                                <td><?=CommonFunctions::asMoney(LoanRepayment::getTotalInterestPaid($model->loanaccount_id));?></td>
                            </tr>
                            <tr>
                                <td>Interest Balance</td>
                                <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanInterestBalance($model->loanaccount_id));?></td>
                            </tr>
                            <tr>
                                <td>Loan Balance</td>
                                <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getCurrentLoanBalance($model->loanaccount_id));?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <form action="<?=Yii::app()->createUrl('loanaccounts/writeOff');?>" method="post">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <input type="hidden" name="loanaccount" id="loanaccount" value="">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 1.5% !important;" class="pull-left">Balance</label>
                                    <input type="text" name="amount" value="" readonly="readonly" class="form-control" id="amount">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 1.5% !important;" class="pull-left">Comment</label>
                                    <textarea class="form-control" rows="2" cols="3" placeholder="Brief comment ..." name="reason" required="required"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary pull-left" value="Write Off">
                                </div>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- PENALTY WRITEOFF VIEW MODAL -->
<div class="modal fade" id="loadingWriteOffPenalty" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:75% !important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <h5 class="title">Write Off Loan Penalty</h5>
                        <hr class="common_rule">
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <td>Member Name</td>
                            <td><?=$model->getBorrowerName();?></td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td><?=$model->getBorrowerBranchName();?></td>
                        </tr>
                        <tr>
                            <td>Phone Number</td>
                            <td><?=$model->getBorrowerPhoneNumber();?></td>
                        </tr>
                        <tr>
                            <td>Relationship Manager</td>
                            <td><?=$model->getRelationshipManagerName();?></td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td><?=$model->account_number;?></td>
                        </tr>
                        <tr>
                            <td>Amount Disbursed</td>
                            <td><?=CommonFunctions::asMoney($model->amount_approved);?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <td>Loan Arrears</td>
                            <td><?=CommonFunctions::asMoney($model->arrears);?></td>
                        </tr>
                        <tr>
                            <td>Principal Paid</td>
                            <td><?=CommonFunctions::asMoney(LoanRepayment::getTotalPrincipalPaid($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Principal Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanPrincipalBalance($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Interest Paid</td>
                            <td><?=CommonFunctions::asMoney(LoanRepayment::getTotalInterestPaid($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Interest Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanInterestBalance($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Loan Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getCurrentLoanBalance($model->loanaccount_id));?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <form action="<?=Yii::app()->createUrl('loanaccounts/writeOffPenalty');?>" method="post">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <input type="hidden" name="loanaccount" id="loanaccountPenalty" value="">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label style="" class="pull-left"> Penalty</label>
                                    <input type="text" name="amount" value="" readonly="readonly" class="form-control" id="amountPenalty">
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 1.5% !important;" class="pull-left">Comment</label>
                                    <textarea class="form-control" rows="2" cols="3" placeholder="Brief comment ..." name="reason" required="required"></textarea>
                                </div>
                            </div><br>
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary pull-left" value="Write Off Penalty">
                                </div>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- ACCRUED INTEREST WRITEOFF VIEW MODAL -->
<div class="modal fade" id="loadingWriteOffAccruedInterest" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:75% !important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <h5 class="title">Write Off Loan Accrued Interest</h5>
                        <hr class="common_rule">
                    </div>
                </div>
                <div class="col-md-6  col-lg-6 col-sm-12">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <td>Borrower Name</td>
                            <td><?=$model->getBorrowerName();?></td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td><?=$model->getBorrowerBranchName();?></td>
                        </tr>
                        <tr>
                            <td>Phone Number</td>
                            <td><?=$model->getBorrowerPhoneNumber();?></td>
                        </tr>
                        <tr>
                            <td>Relationship Manager</td>
                            <td><?=$model->getRelationshipManagerName();?></td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td><?=$model->account_number;?></td>
                        </tr>
                        <tr>
                            <td>Amount Disbursed</td>
                            <td><?=CommonFunctions::asMoney($model->amount_approved);?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <td>Loan Arrears</td>
                            <td><?=CommonFunctions::asMoney($model->arrears);?></td>
                        </tr>
                        <tr>
                            <td>Principal Paid</td>
                            <td><?=CommonFunctions::asMoney(LoanRepayment::getTotalPrincipalPaid($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Principal Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanPrincipalBalance($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Interest Paid</td>
                            <td><?=CommonFunctions::asMoney(LoanRepayment::getTotalInterestPaid($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Interest Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanInterestBalance($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Loan Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getCurrentLoanBalance($model->loanaccount_id));?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <form action="<?=Yii::app()->createUrl('loanaccounts/writeOffAccruedInterest');?>" method="post">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <input type="hidden" name="loanaccount" id="loanaccountAccruedInterest" value="">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label style="" class="pull-left"> Interest</label>
                                    <input type="text" name="amount" value="" readonly="readonly" class="form-control" id="amountAccruedInterest">
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 1.5% !important;" class="pull-left">Comment</label>
                                    <textarea class="form-control" rows="2" cols="3" placeholder="Brief comment ..." name="reason" required="required"></textarea>
                                </div>
                            </div><br>
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary pull-left" value="Write Off Interest">
                                </div>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- STATEMENT VIEW MODAL -->
<div class="modal fade" id="loadingStatement" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:55% !important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <h5 class="title pull-left">Account No. <?=$model->account_number;?> : Loan Statement</h5>
                    <hr class="common_rule">
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <p class="pull-left">Kindly select the required details for the statement</p>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12" style="margin:0% 0% 2% 0% !important;padding:2% 2% 2% 2% !important;">
                    <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/filterLoanStatement');?>">
                        <input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>" readonly="readonly">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label style="" class="pull-left"> Start Date</label>
                                    <input type="text" id="start_date" placeholder="Start Date" class="form-control" name="start_date">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label style="" class="pull-left"> End Date</label>
                                    <input type="text" id="end_date" placeholder="End Date" class="form-control" name="end_date">
                                </div>
                            </div>
                            <span class="error" id="date_error">End Date must be greater or equal to Start Date</span>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label style="" class="pull-left"> Statement Type</label>
                                    <select name="selectAction" id="selectAction" class="form-control selectpicker" style="width: 100% !important;">
                                        <option value="2">Excel</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-block" value="Download Statement">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <a href="<?=Yii::app()->createUrl('loanaccounts/'.$model->loanaccount_id);?>" class="btn btn-default btn-block">Cancel Action</a>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div id="loadStatementResult"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- END MODAL -->

<!-- CLIENT MPESA VIEW MODAL -->
<div class="modal fade" id="loadingClientPaymentModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:40% !important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <h5 class="title">Initiate Payment Prompt</h5>
                    <hr class="common_rule">
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12" style="margin:0% 0% 2% 0% !important;padding:2% 2% 2% 2% !important;">
                    <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/loanRepaymentSTKPush/'.$model->loanaccount_id);?>">
                        <input type="hidden" name="accountNumber" value="<?=$model->account_number;?>" readonly="readonly">
                        <input type="hidden" name="phoneNumber"   value="<?=$model->BorrowerPhoneNumber;?>" readonly="readonly">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label> Payment Type</label>
                                    <select name="paymentType" id="paymentType" class="form-control selectpicker" style="width: 100% !important;" required='required'>
                                        <option value="full">Full Payment</option>
                                        <option value="partial">Partial Payment</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="text" class="form-control" name="amountPaid" id="amountPaid" required='required'/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <hr class="common_rule">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Initiate" name="push_loan_payment_stk_cmd">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group pull-right">
                                    <a href="<?=Yii::app()->createUrl('loanaccounts/'.$model->loanaccount_id);?>" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- END MODAL -->

<!-- PRINCIPAL BALANCE VIEW MODAL -->
<div class="modal fade" id="loadingDeleteLoanPrincipalBalance" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:75% !important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <h5 class="title">Delete Loan Principal Balance</h5>
                        <hr class="common_rule">
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <td>Member Name</td>
                            <td><?=$model->getBorrowerName();?></td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td><?=$model->getBorrowerBranchName();?></td>
                        </tr>
                        <tr>
                            <td>Phone Number</td>
                            <td><?=$model->getBorrowerPhoneNumber();?></td>
                        </tr>
                        <tr>
                            <td>Relationship Manager</td>
                            <td><?=$model->getRelationshipManagerName();?></td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td><?=$model->account_number;?></td>
                        </tr>
                        <tr>
                            <td>Amount Disbursed</td>
                            <td><?=CommonFunctions::asMoney($model->amount_approved);?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <table class="table table-condensed table-bordered">
                        <tr>
                            <td>Loan Arrears</td>
                            <td><?=CommonFunctions::asMoney($model->arrears);?></td>
                        </tr>
                        <tr>
                            <td>Principal Paid</td>
                            <td><?=CommonFunctions::asMoney(LoanRepayment::getTotalPrincipalPaid($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Principal Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanPrincipalBalance($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Interest Paid</td>
                            <td><?=CommonFunctions::asMoney(LoanRepayment::getTotalInterestPaid($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Interest Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanInterestBalance($model->loanaccount_id));?></td>
                        </tr>
                        <tr>
                            <td>Loan Balance</td>
                            <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getCurrentLoanBalance($model->loanaccount_id));?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <form action="<?=Yii::app()->createUrl('loanaccounts/deletePrincipalBalance');?>" method="post">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <input type="hidden" name="loanaccount" id="loanaccountPrincipalBalance" value="">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label style="" class="pull-left"> Principal Balance</label>
                                    <input type="text" name="amount" value="" class="form-control" id="amountPrincipalBalance" required="required">
                                </div>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 1.5% !important;" class="pull-left">Comment</label>
                                    <textarea class="form-control" rows="2" cols="3" placeholder="Brief comment ..." name="reason" required="required"></textarea>
                                </div>
                            </div><br>
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary pull-left" value="Delete Principal">
                                </div>
                            </div>
                        </div><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!--SMS TEXT Modal -->
<div class="modal fade" id="SmsProfile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:45% !important;border-radius:0px !important;">
        <div class="modal-content" style="text-align: left;">
            <div class="modal-header" style="padding:4.5% !important;">
                <h4 class="title">Draft Message</h4>
            </div>
            <div class="modal-body" style="margin-top: -7%;">
                <br>
                <form  autocomplete="off" method="post" action="<?=Yii::app()->createUrl('profiles/sendSMSNotification/'.$model->user_id);?>">
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <textarea class="form-control" cols="15" rows="5" name="textMessage" placeholder="Draft brief message..." required="required"></textarea>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <button type="submit" class="btn btn-primary pull-right" name="send_txt_cmd">Send</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $(function(){
        var loanBalance = "<?=LoanTransactionsFunctions::getCurrentLoanBalance($model->loanaccount_id);?>";
        disableAmountPaidField(loanBalance);
        $('#paymentType').on('change',function(){
            switch(this.value){
                case 'full':
                    disableAmountPaidField(loanBalance);
                    break;

                case 'partial':
                    activateAmountPaidField();
                    break;
            }
        });
    });

    function disableAmountPaidField(balance){
        $('#amountPaid').prop('disabled',true);
        $('#amountPaid').val(balance);
    }

    function activateAmountPaidField(){
        $('#amountPaid').prop('disabled',false);
        $('#amountPaid').val('');
    }

    function loadFile(filename){
        var extension=getFileExtension(filename);
        var filepath="<?=Yii::app()->params['homeDocs'].'/loans/files/';?>"+filename;
        switch(extension.toLowerCase()){
            case 'doc':
                var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
                LoadRespectiveFile(content);
                break;

            case 'docx':
                var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
                LoadRespectiveFile(content);
                break;

            case 'pdf':
                var content='<object data="'+filepath+'" type="application/pdf" style="overflow:scroll !important;width:100% !important;height:100vh !important;"><a href="'+filepath+'">'+filepath+'</a></object>';
                LoadRespectiveFile(content);
                break;

            default:
                var content='<strong>'+filename+'</strong><hr><br><img src="'+filepath+'" width="900" alt="'+filename+'"/>';
                LoadRespectiveFile(content);
                break;

        }
    }

    function getFileExtension(filename){
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function LoadRespectiveFile(content){
        $('#loadingFile').modal({show:true});
        $('#loadedFile').html(content).show().fadeIn('slow');
    }

    function LoanWriteOffBalance(loanID,balance){
        LoadWriteOffBalance(loanID,balance);
    }

    function LoadWriteOffBalance(loanID,balance){
        $('#loadingWriteOff').modal({show:true});
        $('#loanaccount').val(loanID);
        $('#amount').val(balance);
    }

    function LoanWriteOffPenalty(loanID,balance){
        LoadWriteOffPenalty(loanID,balance);
    }

    function LoadWriteOffPenalty(loanID,balance){
        $('#loadingWriteOffPenalty').modal({show:true});
        $('#loanaccountPenalty').val(loanID);
        $('#amountPenalty').val(balance);
    }

    function LoanDeleteLoanPrincipalBalance(loanID,balance){
        LoadDeleteLoanPrincipalBalance(loanID,balance);
    }

    function LoadDeleteLoanPrincipalBalance(loanID,balance){
        $('#loadingDeleteLoanPrincipalBalance').modal({show:true});
        $('#loanaccountPrincipalBalance').val(loanID);
        $('#amountPrincipalBalance').val(balance);
    }

    function LoanWriteOffAccruedInterest(loanID,balance){
        LoadWriteOffAccruedInterest(loanID,balance);
    }

    function LoadWriteOffAccruedInterest(loanID,balance){
        $('#loadingWriteOffAccruedInterest').modal({show:true});
        $('#loanaccountAccruedInterest').val(loanID);
        $('#amountAccruedInterest').val(balance);
    }

    function LoadStatementModal(){
        $('#loadingStatement').modal({show:true});
    }

    function LoadClientPaymentModal(){
        $('#loadingClientPaymentModal').modal({show:true});
    }

    function getLoanStatement(event){
        event.preventDefault();
        $('.error').hide();
        var loanaccount = $("input#loanaccount_id").val();
        var startDate = $("input#start_date").val();
        var endDate = $("input#end_date").val();
        var statement=$('#selectAction option:selected').val();
        if(endDate >= startDate && startDate !='' && endDate!=''){
            var dataString='start_date='+startDate+'&end_date='+endDate+'&reportType='+statement+'&loanaccount_id='+loanaccount;
            $.ajax({
                type:"POST",
                url: "<?=Yii::app()->createUrl('loanaccounts/filterLoanStatement');?>",
                data: dataString,
                success: function(response){
                    if(response === 'NOT FOUND'){
                        $("#loadStatementResult").html("<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong>NO TRANSACTIONS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** No loan transactions found within the selected period. Try again with a different period. ***</p></div>");
                    }else{
                        $('#loadStatementResult').html(response).show().fadeIn('slow');
                    }
                }
            });
            return false;
        }else{
            $("span#date_error").show();
            $("input#end_date").focus();
            return false;
        }
    }
</script>