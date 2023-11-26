<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' -  Disbursed Loan Accounts Report';
$this->breadcrumbs=array(
    'Disbursement'=>array('disbursedAccounts'),
    'Report'=>array('disbursedAccounts'),
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
	            <h5 class="title">Disbursed Loan Accounts</h5>
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
					'type'=>'bordered',
					'dataProvider'=>$model->searchDisbursed(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Accounts Found',
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
							'header'=>'Branch',
							'name'=>'BorrowerBranchName',
						),
						array(
							'header'=>'Relation Manager',
							'name'=>'RelationshipManagerName',
						),
						array(
							'header'=>'Account #',
							'value'=>'$data->account_number',
						),
						array(
							'header'=>'Applied',
							'name'=>'AmountApplied',
						),
						array(
							'header'=>'Rate',
							'name'=>'InterestRate',
						),
						array(
							'header'=>'Period',
							'value'=>'$data->repayment_period',
						),
						array(
							'header'=>'Disbursed',
							'name'=>'AmountDisbursed',
						),
						array(
							'header'=>'Disbursed At',
							'name'=>'FormattedDisbursedDate',
						),
						array(
							'header'=>'Loan Balance',
							'name'=>'CurrentLoanBalance',
						)
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
