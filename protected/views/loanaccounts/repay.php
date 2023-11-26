<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Repay Loan';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'Application'=>array('loanaccounts/'.$model->loanaccount_id),
    'Repay'=>array('loanaccounts/repay/'.$model->loanaccount_id),
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
	            <h5 class="title">Submit Repayment</h5>
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
        	<form method="post" action="<?=Yii::app()->createUrl('loanaccounts/CommitRepayment');?>">
        		<input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
	        	<div class="row">
			    	<div class="col-md-6 col-lg-6 col-sm-12">
			       		 <div class="form-group">
			       		 	<label>Repayment Amount</label>
							<input type="text" class="form-control" required="required" value="" name="repayment_amount">
						</div>
					</div>	
				</div><br><br>
				<div class="row">
			    	  <div class="col-md-3 col-lg-3 col-sm-12">
			       		<div class="form-group">
									<input type="submit" class="btn btn-primary" value="Submit Repayment">
								</div>
							</div>
							<div class="col-md-3 col-lg-3 col-sm-12">
			       		<div class="form-group">
									<a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" type="submit" class="btn btn-default pull-right">Cancel Action</a>
								</div>
							</div>
						</div>
        	</form>
        </div>
    </div>
</div>
