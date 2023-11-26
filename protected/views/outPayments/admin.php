<?php
/* @var $this OutPaymentsController */
/* @var $model OutPayments */
$this->pageTitle=Yii::app()->name . ' - Manage Supplier Payments';
$this->breadcrumbs=array(
  'Supplier_Payments'=>array('admin'),
  'Manage'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#out-payments-grid').yiiGridView('update', {
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
						<h5 class="title">Supplier Payments</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array('model'=>$model));?>
					</div><!-- search-form -->
					<?php if(Navigation::checkIfAuthorized(203) == 1):?>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('outPayments/initiate');?>" title='Initiate Supplier Payment' class="btn btn-success pull-left">Initiate Payment</a>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<hr class="common_rule">
						</div>
					<?php endif?>
					<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'out-payments-grid',
							'type'=>'condensed striped',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No payments found',
							'columns'=>array(
								array(
									'header'=>'#',
									'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
								),
								array(
									'header'=>'Supplier',
									'name'=>'OutPaymentSupplier',
								),
								array(
									'header'=>'Branch',
									'name'=>'OutPaymentBranch',
								),
								array(
									'header'=>'Relation Manager',
									'name'=>'OutPaymentRelationManager',
								),
								array(
									'header'=>'Expense Type',
									'name'=>'OutPaymentExpenseType',
								),
								array(
									'header'=>'Amount',
									'name'=>'OutPaymentAmount',
								),
								array(
									'header'=>'Status',
									'name'=>'OutPaymentStatus',
								),
								array(
									'header'=>'Initiated By',
									'name'=>'OutPaymentInitiatedBy',
								),
								array(
									'header'=>'Date Initiated',
									'name'=>'OutPaymentInitiatedAt',
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
    </div>
</div>