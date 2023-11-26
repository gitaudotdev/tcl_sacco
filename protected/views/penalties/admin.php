<?php
/* @var $this PenaltiesController */
/* @var $model Penaltyaccrued */
$this->breadcrumbs=array(
	'Penalties'=>array('penalties/admin'),
	'Penalties'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#penalty-accrued-grid').yiiGridView('update', {
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
						<h5 class="title">Accrued Loan Penalties</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
<!--					--><?php //$this->renderPartial('_search',array(
//						'model'=>$model,
//					)); ?>
<!--					</div>-->
            <!-- search-form -->
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'write-offs-grid',
					'type'=>'condensed striped',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Accrued Penalties',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
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
                            'header'=>'Amount',
                            'name'=>'PenaltyAmount',
                        ),
                        array(
                            'header'=>'Date Defaulted',
                            'name'=>'DateDefaulted',
                        ),
						array(
							'header'=>'Is Paid',
							'name'=>'IsPaid',
						),
						array(
							'header'=>'Date Transacted',
							'name'=>'TransactionDate',
						),
					),
				)); ?>
				<br><br>
        </div>
    </div>
</div>
