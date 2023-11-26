<?php
$this->pageTitle=Yii::app()->name . ' -  Loan Repayments Administration';
$this->breadcrumbs=array(
	'Repayments'=>array('admin'),
	'Administration'=>array('admin')
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#loanrepayments-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
         <?php if(CommonFunctions::checkIfFlashMessageSet('success') === 1):?>
			    <div class="col-md-12 col-lg-12 col-sm-12">
			      <?=CommonFunctions::displayFlashMessage('success');?>
			    </div>
		    <?php endif;?>
		    <?php if(CommonFunctions::checkIfFlashMessageSet('info') === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage('info');?>
		      </div>
		    <?php endif;?>
		    <?php if(CommonFunctions::checkIfFlashMessageSet('warning') === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage('warning');?>
		      </div>
		    <?php endif;?>
		    <?php if(CommonFunctions::checkIfFlashMessageSet('danger') === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage('danger');?>
		      </div>
		    <?php endif;?>
        <div class="card-body">
					<div class="card-header">
						<h5 class="title">Manage Repayments</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					));?>
					</div><!-- search-form -->
					<div class="col-sm-12 col-lg-12 col-md-12">
						<hr class="common_rule">
					</div>
					<div class="table-responsive" style="overflow-x: scroll !important;">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'loanrepayments-grid',
						'type'=>'condensed striped',
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
								'header'=>'Date',
								'name'=>'FormattedTransactionDate',
							),
							array(
								'header'=>'Amount',
								'name'=>'TotalAmountPaid',
							),
							array(
								'header'=>'Paid By',
								'name'=>'RepaymentTransactingPhone',
							),
							array(
								'header'=>'Member',
								'name'=>'LoanBorrowerName',
							),
							array(
								'header'=>'Account #',
								'name'=>'LoanAccountNumber',
							),
							array(
								'header'=>'Balance',
								'name'=>'LoanAccountBalance',
							),
							array(
								'header'=>'Employer',
								'name'=>'LoanAccountMemberEmployer',
							),
							array(
								'header'=>'Manager',
								'name'=>'LoanRepaymentManager',
							),
							array(
								'header'=>'Branch',
								'name'=>'LoanRepaymentBranch',
							),
							array(
								'header'=>'Actions',
								'name'=>'Action',
							),
						),
					)); ?>
				</div><br><br>
			</div>
    </div>
</div>
