<?php
/* @var $this WriteOffsController */
/* @var $model WriteOffs */
$this->breadcrumbs=array(
	'Loanaccounts'=>array('loanaccounts/admin'),
	'Write_Offs'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#write-offs-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-body">
					<div class="card-header">
						<h5 class="title">Manage Loan Write Offs</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'write-offs-grid',
					'type'=>'condensed striped',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Write Offs Found',
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
							'header'=>'Client Name',
							'name'=>'ClientName',
						),
						array(
							'header'=>'Account Number',
							'name'=>'AccountNumber',
						),
						array(
							'header'=>'RM',
							'name'=>'ManagerName',
						),
						array(
							'header'=>'Amount',
							'name'=>'WriteOffAmount',
						),
						array(
							'header'=>'Date Transacted',
							'name'=>'TransactionDate',
						),
						array(
							'header'=>'Transacted By',
							'name'=>'TransactedBy',
						),
					),
				)); ?>
				<br><br>
        </div>
    </div>
</div>
