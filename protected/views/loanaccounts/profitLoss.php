<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Accounts Proft and Loss';
$this->breadcrumbs=array(
    'Profit_Loss'=>array('loanaccounts/profitAndLoss'),
    'Manage'=>array('loanaccounts/profitAndLoss'),
);
Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#loanaccounts-grid').yiiGridView('update', {
			data: $(this).serialize()
		});
		return false;
	});
");
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
			<div class="col-lg-12 col-md-12 col-sm-12">
	            <h5 class="title">Accounts Profit And Loss</h5>
	            <hr class="common_rule">
	         </div>
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
        <div class="card-body col-lg-12 col-md-12 col-sm-12">
        	<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="search-form">
						<?php $this->renderPartial('_searchpnl',array(
							'model'=>$model,
						)); ?>
					</div><!-- search-form -->
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll !important;">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'loanaccounts-grid',
					'type'=>'condensed striped',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Applications Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Member',
							'name'=>'BorrowerName',
						),
						array(
							'header'=>'Account #',
							'value'=>'$data->account_number',
						),
						array(
							'header'=>'Branch',
							'name'=>'BorrowerBranchName',
						),
						array(
							'header'=>'Relation Manager',
							'name'=>'RelationshipManagerName',
						),
						array(
							'header'=>'Principal',
							'name'=>'AccountPrincipalBalance',
						),
						array(
							'header'=>'Penalties',
							'name'=>'AccountPenalties',
						),
						array(
							'header'=>'Interest',
							'name'=>'AccruedInterest',
						),
						array(
							'header'=>'Amount Due',
							'name'=>'AccountAmountDue',
						),
						array(
							'header'=>'Amount Paid',
							'name'=>'AccountAmountPaid',
						),
						array(
							'header'=>'Profit/Loss',
							'name'=>'AccountProfitOrLoss',
						),
						array(
							'header'=>'Payment Date',
							'name'=>'AccountPaymentDate',
						),
					),
				)); ?>
			</div><br><br>
        </div>
    </div>
</div>
<script type="text/javascript">
  $('#export-btn').click(function(){ 
	  $.fn.yiiGridView.update('loanaccounts-grid', {
	    data: $('.search-form form').serialize()
	  });
	  return false;
});
</script>