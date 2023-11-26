<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Loans Without Repayments';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'NoRepayments'=>array('loanaccounts/noRepayment'),
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
	 #date_error{
        margin-left: 2% !important;
        display: none;
    }
    #tabulate_due_loans{
    	margin-top:10px !important;
    }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
			<div class="col-md-12 col-lg-12 col-sm-12">
	            <h5 class="title">Loans Without Repayments</h5>
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
        	<div class="col-md-12 col-lg-12 col-sm-12">
        		<table class="table table-condensed table-striped"  style="margin:2% 0% 2% 0% !important;">
        			<thead>
        				<th>#</th>
        				<th>Loan Number</th>
        				<th>Borrower</th>
        				<th>Loan Balance</th>
        				<th>Loan Status</th>
        			</thead>
        			<tbody>
                        <?php if(!empty($loanaccounts)):?>
                            <?php $i=1;?>
                            <?php foreach($loanaccounts as $loanaccount):?> 
                                <tr>
                                    <td><?=$i;?></td>
                                    <td><?=$loanaccount->account_number;?></td>
                                    <td><?=$loanaccount->getBorrowerFullName();?></td>
                                    <td><?=CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalLoanBalance($loanaccount->loanaccount_id));?></td>
                                    <td><?=$loanaccount->getCurrentLoanAccountStatus();?></td>
                                </tr>
                                <?php $i++;?>
                            <?php endforeach;?>
                        <?php endif;?>        
                    </tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>
