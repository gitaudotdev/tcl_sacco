<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle   = Yii::app()->name . ' - Daily Accounts Report';
$this->breadcrumbs = array(
    'Daily_Accounts' => array('dailyAccountReport'),
    'Report' => array('dailyAccountReport'),
);
Yii::app()->clientScript->registerScript('searchDisbursed', "
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
        <div class="card-body">
				<div class="card-header">
					<h5 class="title">Daily Accounts Report</h5>
					<hr class="common_rule">
	         	</div>
        		<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="search-form">
						<?php $this->renderPartial('_searchDisbursed',array(
							'model'=>$model,
						)); ?>
					</div><!-- search-form -->
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll !important;">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'loanaccounts-grid',
					'type'=>'condensed striped',
					'dataProvider'=>$model->searchDisbursed(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Applications Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Member Details',
							'name'=>'DownloadableUserDetails',
						),
						array(
							'header'=>'Original Principal',
							'name'=>'ExactAmountDisbursed',
						),
						array(
							'header'=>'Current Principal',
							'name'=>'AccountPrincipalBalance',
						),
						array(
							'header'=>'Interest Rate',
							'name'=>'InterestRate',
						),
						array(
							'header'=>'Accrued Interest',
							'name'=>'AccruedInterest',
						),
						array(
							'header'=>'Total Penalty',
							'name'=>'AccountPenalties',
						),
						array(
							'header'=>'Total Balance',
							'name'=>'AccountAmountDue',
						),
						array(
							'header'=>'Current Month Payment',
							'name'=>'CurrentMonthPayment',
						),
						array(
							'header'=>'Disbursement Date',
							'name'=>'FormattedDisbursedDate',
						),
						array(
							'header'=>'Repayment Date',
							'name'=>'LoanAccountPaymentDate',
						),
						array(
							'header'=>'Account Status',
							'name'=>'LoanAccountStatus',
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