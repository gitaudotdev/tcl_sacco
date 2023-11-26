<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Disburse Loan Application';
$this->breadcrumbs=array(
    'Applications'=>array('admin'),
    'Disburse'=>array('loanaccounts/disburse/'.$model->loanaccount_id),
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
<style type="text/css">
  h4,.header_details{
    font-size: 1.1em !important;
    margin-top: 1.8% !important;
  }
  .info-text{
    margin-top: 0% !important;
  }
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
    <div class="card">
        <div class="card-header">
			<div class="col-md-12 col-lg-12 col-sm-12">
          <h5 class="title">Disburse Application</h5>
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
        <div class="card-body">
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
                      			<td><?=$model->getBorrowerPhoneNumber();?></td>
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
                            <td>Loan Limit</td>
                            <td><?=$model->ClientMaximumAmount;?></td>
                          </tr>
                      		<tr>
                      			<td><div class="text-wrap">Amount Disbursed</div></td>
                      			<td><div class="text-wrap"><?=CommonFunctions::asMoney(LoanManager::getPrincipalDisbursed($model->loanaccount_id));?></div></td>
                      		</tr>
                      		<tr>
                      			<td><div class="text-wrap">Repayment Period</div></td>
                      			<td><div class="text-wrap"><?=$model->repayment_period;?> Months</div></td>
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
                            <td><div class="text-wrap">D/S Disbursed</div></td>
                            <td><div class="text-wrap"><?=$model->DaysPastDisbursementDate;?></div></td>
                          </tr>
                          <tr>
                            <td><div class="text-wrap">D/S Paid</div> </td>
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
                      			<td><div class="text-wrap">Date Disbursed</div></td>
                      			<td><div class="text-wrap"><?=$model->FormattedDisbursedDate;?></div></td>
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
              <br>
                <div class="col-md-7 col-lg-7 col-sm-12"><br><br>
                  <div class="card" data-color="blue">
                        <div class="card-header text-center" data-background-color="blue">
                                <ul class="nav nav-pills">
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
                                      <a class="nav-link" href="#saving_transactions" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="saving_transactions" aria-selected="false">
                                      <i class="now-ui-icons media-2_sound-wave"></i>
                                      Savings
                                      </a>
                                    </li>
                                </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="loan_files">
                                    <h4 class="title"> Files </h4>
                                    <hr class="common_rule">
                                    <div class="row">
                                        <?php if(Navigation::checkIfAuthorized(38) == 1):?>
                                          <div class="col-md-12 col-lg-12 col-sm-12">
                                              <a href="#" class="btn btn-success" onclick="LoadAddFile()">Add File</a>
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
                                              $exportLink  ="<a href='$downloadLink' class='btn btn-success'> <i class='fa fa-download'></i> Download</a>";
                                              $viewLink="<a href='#' class='btn btn-info' onclick='loadFile(\"".$file->filename."\")'> <i class='fa fa-eye'></i> View</a>";
                                                if(Navigation::checkIfAuthorized(176) == 1){
                                                  $deleteAction="<a href='#' class='btn btn-primary' onclick='Authenticate(\"".Yii::app()->createUrl('loanFiles/delete/'.$file->id)."\")' title='Delete Loan File'><i class='fa fa-trash'></i> Delete</a>";
                                                }else{
                                                  $deleteAction="";
                                                }
                                                if(Navigation::checkIfAuthorized(177) == 1){
                                                  $renameAction="<a href='#' class='btn btn-warning' onclick='Authenticate(\"".Yii::app()->createUrl('loanFiles/additionalRename/'.$file->id)."\")' title='Rename Loan File'><i class='fa fa-edit'></i> Rename</a>";
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
               <div class="col-sm-12 col-lg-5 col-md-5">
                  <br>
                  <h5 class="title">Disbursement Details</h5>
                  <hr class="common_rule">
                   <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/CommitDisbursement');?>">
                      <input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
                    <div class="row">
                      <div class="col-md-8 col-lg-8 col-sm-12">
                         <div class="form-group">
                          <label>Amount Approved</label>
                          <input type="text" class="form-control" required="required" value="<?=$model->amount_approved;?>" name="amount_approved" readonly="readonly">
                          </div>
                     </div>
                     </div>
                     <div class="row">
                      <div class="col-md-8 col-lg-8 col-sm-12">
                        <div class="form-group">
                        <label>Repayment Period</label>
                        <input type="text" class="form-control" required="required" value="<?=$model->repayment_period;?>" name="repayment_period" readonly="readonly">
                      </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-8 col-lg-8 col-sm-12">
                      <div class="form-group">
                        <label>Repayment Date</label>
                        <input type="text" class="form-control" required="required" value="<?=$model->repayment_start_date;?>" name="repayment_start_date" id="normaldatepicker" readonly="readonly" disabled="disabled">
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-8 col-lg-8 col-sm-12">
                       <div class="form-group">
                        <label>Reason for Disbursement</label>
                        <textarea class="form-control" cols="5" rows="1" name="reason" placeholder="Brief Comment ..." required="required"></textarea>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-4 col-lg-4 col-sm-12">
                       <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Disburse">
                      </div>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                       <div class="form-group">
                        <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" type="submit" class="btn btn-default pull-right">Cancel</a>
                      </div>
                    </div>
                  </div>
              </form>
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

<!-- ADDING FILE VIEW MODAL -->
  <div class="modal fade" id="addFile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60% !important; height: auto!important;">
      <div class="modal-content">
        <div class="modal-body">
           <h4 style="font-weight: bold;">Upload Files</h4>
          <hr>
          <form method="post" enctype='multipart/form-data' action="<?=Yii::app()->createUrl('loanaccounts/makeFile/'.$model->loanaccount_id);?>">
          <br>
          <input type="hidden" name="accountAction" value="approvedAccount">
          <div class="row">
            <div class="col-md-9 col-lg-9 col-sm-12">
              <div class='form-group'>
                <label >File Name</label>
                <input type='text' name="loan_file_name" required="required" class="form-control">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <label >Browse File</label><br>
              <div class='file-input'>
                <input type='file' name="loan_file" required="required">
                <span class='button'>Choose File</span>
                <span class='label' data-js-label>No file selected</label>
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <input type="submit" name="upload_file_cmd" value="Upload" class="btn btn-primary">
            </div>
          </div>
          <br>
        </form>
        </div>
      </div>
      </div>
    </div>
  </div>
  <!-- END MODAL -->

<script type="text/javascript">

  function loadFile(filename){
    var extension=getFileExtension(filename);
    var filepath="<?=Yii::app()->params['homeDocs'].'/loans/files/';?>"+filename;
    switch(extension.toLowerCase()){
      case 'doc':
      var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
      LoadRespectiveFile(content)
      break;

      case 'docx':
      var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
      LoadRespectiveFile(content)
      break;

      case 'pdf':
      var content='<object data="'+filepath+'" type="application/pdf" style="overflow:scroll !important;width:100% !important;height:100vh !important;"><a href="'+filepath+'">'+filepath+'</a></object>';
      LoadRespectiveFile(content)
      break;

      default:
      var content='<strong>'+filename+'</strong><hr><br><img src="'+filepath+'" width="900" alt="'+filename+'"/>';
      LoadRespectiveFile(content)
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

  function LoadAddFile(){
    $('#addFile').modal({show:true});
  }
</script>
<script type="text/javascript">
  
var inputs = document.querySelectorAll('.file-input')

for (var i = 0, len = inputs.length; i < len; i++) {
  customInput(inputs[i])
}

function customInput (el) {
  const fileInput = el.querySelector('[type="file"]')
  const label = el.querySelector('[data-js-label]')
  
  fileInput.onchange =
  fileInput.onmouseout = function () {
    if (!fileInput.value) return
    
    var value = fileInput.value.replace(/^.*[\\\/]/, '')
    el.className += ' -chosen'
    label.innerText = value
  }
}
</script>
