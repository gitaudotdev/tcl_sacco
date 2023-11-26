<?php
/* @var $this LoanrepaymentsController */
/* @var $model Loanrepayments */
$this->pageTitle=Yii::app()->name . ' - Loan Repayments';
$this->breadcrumbs=array(
	'Account_Collections'=>array('accountCollections'),
	'Manage'=>array('accountCollections')
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#loanrepayments-grid').yiiGridView('update', {
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
						<h5 class="title">Loan Account Collections</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_searchCollections',array(
						'model'=>$model,
					));?>
					</div><!-- search-form -->
					<div class="col-sm-12 col-lg-12 col-md-12">
						<hr class="common_rule">
					</div>
					<div class="table-responsive" style="overflow-x: scroll !important;">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'loanrepayments-grid',
						'type'=>'bordered',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Repayments Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Member',
								'name'=>'LoanBorrowerName',
							),
							array(
								'header'=>'Branch',
								'name'=>'LoanRepaymentBranch',
							),
							array(
								'header'=>'Relation Manager',
								'name'=>'LoanRepaymentManager',
							),
							array(
								'header'=>'Account #',
								'name'=>'LoanAccountNumber',
							),
							array(
								'header'=>'Principal',
								'name'=>'PrincipalPaid',
							),
							array(
								'header'=>'Interest',
								'name'=>'InterestPaid',
							),
							array(
								'header'=>'Penalty',
								'name'=>'PenaltyPaid',
							),
							array(
								'header'=>'Total',
								'name'=>'TotalAmountPaid',
							),
							array(
								'header'=>'Paid At',
								'name'=>'FormattedClearTransactionDate',
							),
						),
					)); ?>
				</div><br><br>
			</div>
    </div>
</div>
<script type="text/javascript">
  $('#export-btn').click(function(){ 
	  $.fn.yiiGridView.update('loanrepayments-grid', {
	    data: $('.search-form form').serialize()
	  });
	  return false;
});
</script>

