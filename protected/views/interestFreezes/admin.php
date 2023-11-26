<?php
$this->pageTitle=Yii::app()->name . ' - Manage Frozen Loan Accounts';
$this->breadcrumbs=array(
    'Frozen_Accounts'=>array('admin'),
    'Manage'=>array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#interest-freezes-grid').yiiGridView('update', {
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
  <div class="col-md-12">
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
					<h5 class="title">Frozen Loan Accounts</h5>
					<hr class="common_rule">
				</div>
				<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
				</div>
				</div>
				<div class="table-responsive">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'interest-freezes-grid',
					'type'=>'bordered hover',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Frozen Loan Accounts Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Branch',
							'name'=>'BranchName',
						),
						array(
							'header'=>'Relation Manager',
							'name'=>'RelationManager',
						),
						array(
							'header'=>'Client Name',
							'name'=>'ClientName',
						),
						array(
							'header'=>'Current Balance',
							'name'=>'FormattedCurrentLoanBalance',
						),
						array(
							'header'=>'Interest Rate',
							'name'=>'CurrentInterestRate',
						),
						array(
							'header'=>'Start Date',
							'name'=>'FreezeStartDate',
						),
						array(
							'header'=>'End Date',
							'name'=>'FreezeEndDate',
						),
						array(
							'header'=>'Days Remaining',
							'name'=>'FreezeRemainingDays',
						),
						array(
							'header'=>'Account Status',
							'name'=>'FreezeStatus',
						),
					),
				)); ?>
			</div><br><br>
        </div>
    </div>
</div>
