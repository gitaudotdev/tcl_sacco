<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Reject Loan Application';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'Applications'=>array('loanaccounts/admin'),
    'Reject'
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
	            <h5 class="title">Reject Application</h5>
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
        	<form method="post" action="<?=Yii::app()->createUrl('loanaccounts/CommitRejection');?>">
        		<input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
        		<div class="row">
					    	<div class="col-md-8 col-sm-12 col-lg-8">
					       	<div class="form-group">
					       	<label>Savings Balance</label>
									<input type="text" class="form-control" readonly="readonly" value="<?=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($model->user_id));?>">
								</div>
							</div>
						</div>
	        	<div class="row">
			    	<div class="col-md-8 col-sm-12 col-lg-8">
			       		 <div class="form-group">
			       		 	<label style="margin-left:2.4%;">Reason for Rejecting Application</label>
									<textarea class="form-control" cols="15" rows="15" name="reason" placeholder="Please provide a reason for rejecting an application ..." required="required"></textarea>
								</div>
					</div>
				</div>
				<div class="row">
			    	<div class="col-md-8 col-sm-12 col-lg-8">
			       		 <div class="form-group">
							<input type="submit" class="btn btn-primary" value="Reject Application">
							<a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" type="submit" class="btn btn-default pull-right">Cancel Action </a>
						</div>
					</div>
				</div>
			</form>
        </div>
    </div>
</div>
