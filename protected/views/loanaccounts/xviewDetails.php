<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - View Loan Application Details';
$this->breadcrumbs=array(
    'Applications'=>array('admin'),
    'View'=>array('loanaccounts/viewDetails/'.$model->loanaccount_id),
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
      <!--MEMBER DETAILS-->
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
                  <h4 class="title">Application Details: <?=$model->account_number;?></h4>
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
                            <td>Client</td>
                            <td>
                              <div class="text-wrap"><?=$model->getBorrowerName();?></div></td>
                          </tr>
                          <tr>
                            <td>Residence</td>
                            <td>
                              <div class="text-wrap"><?=$model->getLoanAccountUserResidence();?></div></td>
                          </tr>
                          <tr>
                            <td>Employer</td>
                            <td>
                              <div class="text-wrap"><?=$model->getBorrowerEmployer();?></div></td>
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
                            <td>
                              <div class="text-wrap"><?=$model->getRelationshipManagerName();?></div></td>
                          </tr>
                        </table>
                      </div>

                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <table class="table table-bordered table-hover">
                          <tr>
                            <td>Account Opening Date</td>
                            <td><?=date('jS M Y',strtotime($model->created_at));?></td>
                          </tr>
                          <tr>
                            <td>Account Number</td>
                            <td><?=$model->account_number;?></td>
                          </tr>
                           <tr>
                            <td>Loan Limit</td>
                            <td><?=$model->ClientMaximumAmount;?></td>
                          </tr>
                          <tr>
                            <td>Savings Balance</td>
                            <td>
                              <?=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($model->user_id));?>
                              </td>
                          </tr>
                          <tr>
                            <td>Amount Applied</td>
                            <td><?=CommonFunctions::asMoney($model->amount_applied);?></td>
                          </tr>
                          <tr>
                            <td>Repayment Period</td>
                            <td><?=$model->repayment_period;?> Months</td>
                          </tr>
                          <tr>
                            <td>Interest Rate</td>
                            <td><?=$model->interest_rate;?> % p.m.</td>
                          </tr>
                          <tr>
                            <td>Installment</td>
                            <td><?=CommonFunctions::asMoney(LoanCalculator::getEMIAmount($model->amount_applied,$model->interest_rate,$model->repayment_period));?> </td>
                          </tr>
                        </table>
                      </div>


                      <div class="col-md-4 col-lg-4 col-sm-12">
                          <table class="table table-bordered table-hover">
<!--                              <tr>-->
<!--                                  <td>Insurance Fee</td>-->
<!--                                  <td>--><?php //=CommonFunctions::asMoney($model->insurance_fee);?><!--</td>-->
<!--                              </tr>-->
<!--                              <tr>-->
<!--                                  <td>Processing Fee</td>-->
<!--                                  <td>--><?php //=CommonFunctions::asMoney($model->processing_fee);?><!--</td>-->
<!--                              </tr>-->
                              <tr>
                                  <td>Insurance value</td>
                                  <td><?=CommonFunctions::asMoney($model->insurance_fee_value);?> %</td>
                              </tr>
                              <tr>
                                  <td>Processing Value</td>
                                  <td><?=CommonFunctions::asMoney($model->processing_fee_value);?> %</td>
                              </tr>
<!--                              <tr>-->
<!--                                  <td>Total Deductions</td>-->
<!--                                  <td>--><?php //=CommonFunctions::asMoney($model->deduction_fee);?><!--</td>-->
<!--                              </tr>-->

                          </table>
                      </div>


                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <hr class="common_rule"/>
                    <div class="row">
                      <div class="col-md-2 col-lg-2 col-sm-12">
			                     <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                      </div>
                      <?php if($forwardedStatus === 0):?>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                        <a href="#" class="btn btn-success" onclick="LoadApprove()">Approve</a>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                          <a href="#" class="btn btn-primary" onclick="LoadReject()">Reject</a>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                        <a href="#" class="btn btn-warning" onclick="LoadForward()">Forward</a>
                      </div>
                      <?php elseif(!empty($forwardedStatus) && $forwardedStatus->forwarded_to === Yii::app()->user->user_id):?>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                        <a href="#" class="btn btn-success" onclick="LoadApprove()"> Approve</a>
                      </div>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                        <a href="#" class="btn btn-primary" onclick="LoadReject()"> Reject</a>
                      </div>
                      <?php endif;?>
                      <?php if($returnedStatus === 0):?>
                      <div class="col-md-2 col-lg-2 col-sm-12">
                        <a href="#" class="btn btn-info" onclick="LoadReturn()"> Return</a>
                      </div>
                      <?php endif;?>
                    </div>
                    <hr class="common_rule"/>
                  </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
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
                </div>
                <div class="row">
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

      <!-- APPROVAL VIEW MODAL -->
      <div class="modal fade" id="approve" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:60% !important; height: auto!important;">
          <div class="modal-content">
            <div class="modal-body">
              <h4 style="font-weight: bold;">Approve Loan Application</h4>
              <hr class="common_rule">
              <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/CommitApproval');?>">
                <input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="form-group">
                      <label>Amount Applied</label>
                      <input type="text" class="form-control" required="required" value="<?=$model->amount_applied;?>" name="amount_applied" id="actualAmount">
                  </div>
                </div>
              </div>

                  <div class="row">
                      <div class="col-md-12 col-lg-12 col-sm-12">
                          <div class="form-group">
<!--                              <label>Insurance Fee</label>-->
<!--                              <input type="text" class="form-control" readonly value="--><?php //=$model->insurance_fee;?><!--" name="insurance_fee" id="insurance_fee">-->
<!--                              -->
                              <label>Insurance Rate</label>
                              <input type="text" class="form-control" readonly value="<?=$model->insurance_fee_value;?> %" name="insurance_rate_value" id="insurance_rate_value">
                          </div>
                      </div>
                  </div>


              <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                       <div class="form-group">
<!--                           <label>Processing Fee</label>-->
<!--                           <input type="text" class="form-control" readonly value="--><?php //=$model->processing_fee;?><!--" name="processing_fee" id="processing_fee">-->
<!--                      -->
                           <label>Processing Rate</label>
                           <input type="text" class="form-control" readonly value="<?=$model->processing_fee_value;?> %" name="processing_rate_value" id="processing_rate_value">

                       </div>
                </div>
              </div>

<!--               <div class="row">-->
<!--                      <div class="col-md-12 col-lg-12 col-sm-12">-->
<!--                          <div class="form-group">-->
<!--                              <label>Total Deduction(Insurance + Processing) on Applied Amount</label>-->
<!--                              <input type="text" class="form-control" readonly value="--><?php //=$model->deduction_fee;?><!--" name="deduction_fee" id="deduction_fee">-->
<!--                          </div>-->
<!--                      </div>-->
<!--               </div>-->



<!--                  <div class="row">-->
<!--                      <div class="col-md-12 col-lg-12 col-sm-12">-->
<!--                          <div class="form-group">-->
<!--                              <label>Loan To be Approved(Amount Applied - Total Deductions)</label>-->
<!--                              <input type="text" class="form-control" readonly value="--><?php //=$model->amount_receivable;?><!--" name="amount_receivable" id="amount_receivable">-->
<!--                          </div>-->
<!--                      </div>-->
<!--                  </div>-->

                  <div class="row">
                      <div class="col-md-12 col-lg-12 col-sm-12">
                          <div class="form-group">
                              <label>Repayment Period</label>
                              <input type="text" class="form-control" required="required" value="<?=$model->repayment_period;?>" name="repayment_period">
                          </div>
                      </div>
                  </div>

              <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                       <div class="form-group">
                        <label>Repayment Start Date</label>
                        <input type="text" class="form-control" required="required" value="<?=$model->repayment_start_date;?>" name="repayment_start_date" id="normaldatepicker">
                      </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                       <div class="form-group">
                        <label>Penalty Amount</label>
                        <input type="text" class="form-control" required="required" value="" name="penalty_amount">
                      </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="form-group">
                          <label>Reason for Approval</label>
                          <textarea class="form-control" cols="15" rows="15" name="reason" placeholder="Please provide a reason for approving the application ..." required="required"></textarea>
                      </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="form-group">
                      <input type="submit" class="btn btn-primary" value="Approve Application">
                    </div>
                </div>
              </div>
            </form>
            </div>
          </div>
          </div>
        </div>
      </div>
      <!-- END MODAL -->

      <!-- REJECTION VIEW MODAL -->
      <div class="modal fade" id="reject" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style=" height: auto!important;">
          <div class="modal-content">
            <div class="modal-body">
              <h4 style="font-weight: bold;">Reject Loan Application</h4>
              <hr class="common_rule">
              <div class="col-md-12 col-lg-12 col-sm-12">
              <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/CommitRejection');?>">
                <br>
                <input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
                <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                     <div class="form-group">
                      <label>Reason for Rejecting Application</label>
                      <textarea class="form-control" cols="15" rows="15" name="reason" placeholder="Please provide a reason for rejecting an application ..." required="required"></textarea>
                    </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-lg-12">
                 <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Reject Application">
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

      <!-- FORWARD VIEW MODAL -->
      <div class="modal fade" id="forward" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:60% !important; height: auto!important;">
          <div class="modal-content">
            <div class="modal-body">
              <h4 style="font-weight: bold;">Forward Loan Application</h4>
              <hr class="common_rule">
            <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/forward/'.$model->loanaccount_id);?>">
            <br>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="form-group" >
                    <label>Select Staff</label>
                    <select name="forwarded_to" class="selectpicker form-control-changed" required="required" style="width: 100% !important;">
                    <option value="">--Select Staff To Forward Loan To--</option>
                    <?php
                      foreach($users as $user){
                        echo "<option value='$user->id'>$user->ProfileFullName</option>";
                      }
                    ?>
                  </select>
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="form-group">
                    <label>Reason for forwarding the loan application</label>
                    <textarea class="form-control" name="comment" cols='5' rows='5' placeholder="Please provide brief comment for forwarding the application..."></textarea>
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Forward Application" id="apply_loan_cmd" name="fwd_loan_cmd">
                  </div>
                </div>
              </div>
            </form>
            </div>
          </div>
          </div>
        </div>
      </div>
      <!-- END MODAL -->

      <!-- RETURN VIEW MODAL -->
      <div class="modal fade" id="return" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:60% !important; height: auto!important;">
          <div class="modal-content">
            <div class="modal-body">
              <h4 style="font-weight: bold;">Return Loan Application</h4>
              <hr class="common_rule">
            <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/return/'.$model->loanaccount_id);?>">
            <br>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="form-group">
                    <label>Reason for returning loan Application</label>
                    <textarea class="form-control" name="comment" cols='5' rows='5' placeholder="Please provide brief comment for redirecting the application..."></textarea>
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Return Application" id="return_loan_cmd" name="return_loan_cmd">
                  </div>
              </div>
            </form>
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
               <h4 style="font-weight: bold;">Upload Application Files</h4>
              <hr class="common_rule">
              <form method="post" enctype='multipart/form-data' action="<?=Yii::app()->createUrl('loanaccounts/makeFile/'.$model->loanaccount_id);?>">
              <br>
              <input type="hidden" name="accountAction" value="submittedAccount">
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
                  <label > Browse File</label><br>
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
      var content='<strong>'+filename+'</strong><hr class="common_rule"><br><img src="'+filepath+'" width="900" alt="'+filename+'"/>';
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


  function LoadApprove(){
    $('#approve').modal({show:true});
  }

  function LoadReject(){
    $('#reject').modal({show:true});
  }

  function LoadForward(){
    $('#forward').modal({show:true});
  }

  function LoadReturn(){
    $('#return').modal({show:true});
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



