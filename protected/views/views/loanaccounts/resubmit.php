<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Resubmit Application';
$this->breadcrumbs=array(
	'loanaccounts'=>array('admin'),
	'Resubmit'=>array('loanaccounts/resubmit/'.$model->loanaccount_id)
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
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Resubmit</h5>
            <hr class="common_rule">
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
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <form method="post" enctype="multipart/form-data" action="<?=Yii::app()->createUrl('loanaccounts/loanResubmission');?>">
            <br/>
            <input type="hidden" name="loanaccount" value="<?=$model->loanaccount_id;?>"/>
            <input type="hidden" name="returnedStatus" value="<?=$returned->id;?>"/>
           <div class="row">
                <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Member</label>
                    <input type="text" class="form-control" readonly="readonly" value="<?=$model->AccountDetails;?>"/>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                    <div class="form-group">
                    <label>Relationship Manager</label>
                    <input type="text" class="form-control" readonly="readonly" value="<?=$model->RelationshipManagerName;?>"/>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Savings Balance</label>
                    <input type="text" class="form-control" readonly="readonly"
                     value="<?=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($model->user_id));?>">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Reason for returning Application</label>
                    <textarea readonly="readonly" class="form-control"
                     style="font-weight: bold !important;" cols="2" rows='1'><?=$returned->comment;?></textarea>
                  </div>
                </div>
            </div>
            <br>
            <div class="row">
             <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <label>Loan Limit</label>
                    <input type="text" class="form-control" readonly="readonly" value="<?=$model->ClientMaximumAmount;?>">
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                    <div class="form-group">
                    <label>Amount Applied</label>
                    <input type="text" class="form-control" value="<?=$model->amount_applied;?>" name="amount_applied"/>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                    <div class="form-group">
                    <label>Repayment Duration(Digits only)</label>
                    <input type="text" class="form-control" value="<?=$model->repayment_period;?>"  name="repayment_period"/>
                  </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                    <div class="form-group">
                    <label>Brief Comment</label>
                    <textarea class="form-control" cols="2" rows='1' name="special_comment"><?=$model->special_comment;?></textarea>
                  </div>
              </div>
            </div>
              <hr class="common_rule">
              <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <a name="docs" href="#docs" class="btn btn-warning btn-round" id="add">Add Client Documents</a>
                  &emsp; <span style="color:red;">Kindly upload an image, word document or a PDF.</span>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12" id="items"></div>
              </div>
              <br>
            <br>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Resubmit" id="resubmit_loan_cmd" name="resubmit_loan_cmd">
                  </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                    <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" type="submit" class="btn btn-default pull-right">Cancel Action</a>
                </div>
              </div>
          </div>
          </form>
        </div>
     </div>
  </div>
</div>
<br>
<script type="text/javascript">
$(document).ready(function(){
  $("body").on("click", "#add",function(e){
   $("#items").append('<div class="col-md-6 col-lg-6 col-sm-12" style="border-bottom:2px dotted #dedede!important;padding:2% 2% 2% 0% !important;"><input name="path[]" type="file" required="required"/><button type="button" class="btn btn-info btn-round" id="add">Add </button>&emsp;|&emsp;<button class="delete btn btn-danger btn-round">Remove</button></div>'); 
  });
  $("body").on("click",".delete",function(e){
      $(this).parent("div").remove();
  });
});
</script>