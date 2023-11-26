<?php
$this->pageTitle=Yii::app()->name . ' : Saving Account Details';
$this->breadcrumbs=array(
	'Savingaccounts'=>array('admin'),
	'Details'=>array('savingaccounts/'.$model->savingaccount_id)
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
/*Allowed*/
$element=Yii::app()->user->user_level;
$array=array('2','3','4');
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
                  <h5 class="title">Account Details</h5>
                  <hr class="common_rule">
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <table class="table table-bordered table-hover">
                          <tr>
                            <td>Account Holder</td>
                            <td><?=$model->getSavingAccountHolderName();?></td>
                          </tr>
                          <tr>
                            <td>Account Number</td>
                            <td><?=$model->getSavingAccountNumber();?></td>
                          </tr>
                          <tr>
                            <td>Account Type</td>
                            <td><?=$model->getAccountType();?></td>
                          </tr>
                          <tr>
                            <td>Member Branch</td>
                            <td><?=$model->getSavingAccountHolderBranch();?></td>
                          </tr>
                          <tr>
                            <td>Relation Manager</td>
                            <td><?=$model->getSavingAccountHolderRelationManager();?></td>
                          </tr>
                          <tr>
                            <td>Date Opened</td>
                            <td><?=$model->getAccountOpenedAt();?></td>
                          </tr>
                        </table>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                            <table class="table table-bordered table-hover">
                              <tr>
                                <td>Account Status</td>
                                <td><?=$model->getPlainAccountAuthStatus();?></td>
                              </tr>
                              <tr>
                                <td>Opening Balance</td>
                                <td><?=$model->getAccountOpeningBalance();?></td>
                              </tr>
                              <tr>
                                <td>Interest Rate</td>
                                <td><?=$model->interest_rate;?></td>
                              </tr>
                              <tr>
                                <td>Total Withdrawals</td>
                                <td><?=CommonFunctions::asMoney(SavingFunctions::getTotalSavingAccountWithdrawals($model->savingaccount_id));?></td>
                              </tr>
                              <?php if(Yii::app()->user->user_level=='0'):?>
                              <tr>
                                <td>Accrued Interest</td>
                                <td>Kshs. <?=CommonFunctions::asMoney(SavingFunctions::getSavingAccountAccruedInterest($model->savingaccount_id));?></td>
                              </tr>
                              <tr>
                                <td><strong>Total Account Balance</strong></td>
                                <td><strong>Kshs. <?=CommonFunctions::asMoney(SavingFunctions::getTotalSavingAccountBalance($model->savingaccount_id));?></strong></td>
                              </tr>
                              <?php endif;?>
                            </table>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <br>
                      <hr class="common_rule">
                      <?php if($model->is_approved =='1'):?>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <?php if(Navigation::checkIfAuthorized(148) == 1):?>
                          <a data-toggle="modal" data-target="#depositFunds" title='Deposit Funds' class="btn btn-primary">Deposit</a>
                        <?php endif;?>
                        <?php if(Navigation::checkIfAuthorized(149) == 1):?>
                          &emsp; <a data-toggle="modal" data-target="#withdrawFunds" title='Withdraw Funds' class="btn btn-success">Withdraw</a>
                        <?php endif;?>
                        <?php if(Navigation::checkIfAuthorized(150) == 1):?>
                          &emsp; <a data-toggle="modal" data-target="#transferFunds" title='Transfer Funds to Loan Account' class="btn btn-warning">Transfer</a>
                        <?php endif;?>
                        <?php if(Navigation::checkIfAuthorized(282) == 1):?>
                          &emsp; <a data-toggle="modal" data-target="#initiateDepositFunds" title='Initiate Deposit Payment Prompt' class="btn btn-primary">
                          M-PESA Client Saving</a>
                        <?php endif;?>
                      </div>
                      <?php else:?>
                        <div class="col-md-6 col-lg-6 col-sm-12"></div>
                      <?php endif;?>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="dropdown" style="margin-left: 53% !important;">
                            <button class="dropdown-toggle btn btn-info" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Statement
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">PDF</a>
                                <a class="dropdown-item" href="#">Excel</a>
                                <a class="dropdown-item" href="#">Email</a>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
        <!--SAVING Transactions TABULATED-->
        <div class="card">
          <div class="card-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                  <br>
                  <?php if(!empty($savingtransactions)):?>
                    <?php Tabulate::createMemberSavingTransactionsTable($savingtransactions);?>
                  <?php else:?>
                    <p style='border-bottom: 1px solid #000;font-size:1.2em;color:#00933b;'>
                      <strong>NO TRANSACTIONS FOUND</strong></p><br>
                      <p style='color:#f90101;font-size:1.30em;'>*** No available transactions for this saving account. ****
                    </p>
                  <?php endif;?>
                  <br><br>
              </div>
          </div>
      </div>
    </div>
</div>
<!-----Deposit Transaction Modal----->
<div class="modal fade" id="depositFunds" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header">
        <h4 class="title">
            Deposit Funds
        </h4>
      </div>
      <div class="modal-body">
      <form method="post" action="<?=Yii::app()->createUrl('savingaccounts/depositFunds');?>">
       <input type="hidden" name="savingaccount" value="<?=$model->savingaccount_id;?>">
       <br>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Amount To Deposit</label>
                <input class="form-control" type="text"  name="amount" placeholder="Amount in Digits" required="required">
              </div>
            </div>
        </div>
        <br>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary mb-3">Deposit</button>
          &emsp;<button type="button" class="btn btn-default mb-3" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<!-----Deposit Transaction Modal----->

<!-----Initiate Deposit Transaction Modal----->
<div class="modal fade" id="initiateDepositFunds" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header">
        <h4 class="title">Initiate Deposit Payment Prompt</h4>
        <hr class="common_rule"/>
      </div>
      <div class="modal-body">
      <form method="post" action="<?=Yii::app()->createUrl('savingaccounts/depositSavingSTKPush');?>">
       <input type="hidden" name="savingaccount" value="<?=$model->savingaccount_id;?>">
       <br>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Amount To Deposit</label>
                <input class="form-control" type="text"  name="amount" placeholder="Amount in Digits" required="required">
              </div>
            </div>
        </div>
        <br>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary mb-3" name="push_savings_payment_stk_cmd">Initiate</button>
          &emsp;<button type="button" class="btn btn-default mb-3" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<!-----Deposit Transaction Modal----->

<!-----Withdraw Transaction Modal----->
<div class="modal fade" id="withdrawFunds" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header">
        <h4 class="title">Withdraw Funds</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="<?=Yii::app()->createUrl('savingaccounts/withdraw');?>">
       <input type="hidden" name="savingaccount" value="<?=$model->savingaccount_id;?>">
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Type of Withdrawal Request</label>
                <select class="form-control selectpicker" name="type" required="required" style="width: 100% !important;">
                  <option>-- REQUEST TYPE --</option>
                  <option value="0">Withdraw Main Savings</option>
                  <option value="1">Withdraw Interests</option>
                </select>
              </div>
            </div>
        </div>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Amount To Withdraw</label>
                <input class="form-control" type="text"  name="amount" placeholder="Amount in Digits" required="required">
              </div>
            </div>
        </div>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Staff To Authorize</label>
                <select class="selectpicker form-control" name="approver" required="required" style="width: 100% !important;">
                  <option>-- STAFF MEMBER --</option>
                  <?php if(!empty($users)):?>
                      <?php foreach($users as $user):?>
                          <option value="<?=$user->id;?>">
                              <?=$user->ProfileFullName;?>
                          </option>
                      <?php endforeach;?>
                  <?php endif;?>
                </select>
              </div>
            </div>
        </div>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Brief Comment</label>
                <textarea name="reason" class="form-control" rows="2" cols="5" placeholder="Brief Reason ..." required="required"></textarea>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary mb-3">Withdraw</button>
          &emsp;<button type="button" class="btn btn-default mb-3" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
    </div>
  </div>
</div>
<!-----Withdraw Transaction Modal----->

<!-----Transfer Transaction Modal----->
<div class="modal fade" id="transferFunds" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="overflow:hidden;">
  <div class="modal-dialog" style="width:65% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header">
        <h4 class="title">Transfer Funds</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="<?=Yii::app()->createUrl('savingaccounts/transferFunds');?>">
        <input type="hidden" name="savingaccount" value="<?=$model->savingaccount_id;?>">
        <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
              <label >Loan Account</label>
              <select class="selectpicker" name="loanaccount" required="required" style="width: 100% !important;" id="loanaccount_id">
                <option value="0">-- LOAN ACCOUNTS --</option>
                <?php if(!empty($loanaccounts)):?>
                    <?php foreach($loanaccounts as $account):?>
                        <option value="<?=$account->loanaccount_id;?>">
                            <?=$account->AccountDetails;?>
                        </option>
                    <?php endforeach;?>
                <?php endif;?>
              </select>
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
              <label >Loan Balance</label>
              <input class="form-control" type="text" placeholder="Amount In Digits" id="loan_balance" readonly="readonly">
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
              <label >Amount To Transfer</label>
              <input class="form-control" type="text"  name="amount" placeholder="Amount In Digits" required="required">
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
              <label >Staff To Authorize</label>
              <select class="selectpicker form-control-changed" name="approver" required="required" style="width: 100% !important;">
                <option>-- APPROVER --</option>
                <?php if(!empty($users)):?>
                    <?php foreach($users as $user):?>
                        <option value="<?=$user->id;?>">
                            <?=$user->ProfileFullName;?>
                        </option>
                    <?php endforeach;?>
                <?php endif;?>
              </select>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="form-group">
              <label >Brief Comment</label>
              <textarea name="reason" class="form-control" rows="2" cols="5" placeholder="Brief Reason ..." required="required"></textarea>
            </div>
          </div>
        </div>
        <br>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary mb-3">Transfer</button>
          &emsp;<button type="button" class="btn btn-default mb-3" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
    </div>
  </div>
</div>
<!-----Transfer Transaction Modal----->
<script>
 $(function(){
  var typingTimer;
  var doneTypingInterval=1020;
  $('#loanaccount_id').on('change', function() {
     $.ajax({
        type:"POST",
        dataType: "json",
        url: "<?=Yii::app()->createUrl('savingaccounts/loadAccountDetails');?>",
        data: {'loanaccount_id':this.value},
        success: function(response) {
          if(response === 'NOT FOUND'){
            alert('Kindly select a valid loan account');
          }else{
            $("#loan_balance").val(response.loan_balance);
          }
        }
      });
  });
});
</script>



