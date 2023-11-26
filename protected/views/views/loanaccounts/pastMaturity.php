 <?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Loans Past Maturity Date';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'PastMaturityDate'=>array('loanaccounts/pastMaturity'),
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
	            <h5 class="title">Loans Past Maturity Date</h5>
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
                <?php if(!empty($loanaccounts)):?>
        		<table class="table table-condensed table-striped"  style="margin:2% 0% 2% 0% !important;">
        			<thead>
        				<th>#</th>
        				<th>Loan Number</th>
        				<th>Borrower</th>
        				<th>Loan Balance</th>
        				<th>Loan Status</th>
        			</thead>
        			<tbody>
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
                    </tbody>
        		</table>
                <?php else:?> 
                    <div class="col-md-8 col-lg-8 col-sm-8" style="padding:10px 10px 10px 10px !important;">
                        <p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
                            <strong style="margin-left:20% !important;">NO LOANS PAST MATURITY DATE</strong></p><br>
                        <p style="color:#f90101;font-size:1.30em;">*** THERE ARE NO LOANS PAST MATURITY DATE AVAILABLE. ****</p>
                    </div>
                <?php endif;?> 
        	</div>
        </div>
    </div>
</div>
