<?php
$this->pageTitle=Yii::app()->name . ' - Fixed Payments Administration and Management';
$this->breadcrumbs=array(
  'Fixed_Payments'=>array('admin'),
  'Manage'=>array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#fixed-payments-grid').yiiGridView('update', {
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
        <h5 class="title">Manage Fixed Payments</h5>
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
						<?php $this->renderPartial('_search',array(
							'model'=>$model,
						)); ?>
					</div><!-- search-form -->
				</div>
					<?php if(Navigation::checkIfAuthorized(244) == 1):?>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="col-lg-4 col-md-4 col-sm-12">
								<a href="<?=Yii::app()->createUrl('fixedPayments/create');?>" title='Initiate Fixed Payment' class="btn btn-success pull-left">Initiate Payment</a>
							</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<hr class="common_rule">
					</div>
				<?php endif?>
				<div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll !important;">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'fixed-payments-grid',
					'type'=>'condensed striped',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Fixed Payments Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Supplier',
							'name'=>'FixedPaymentSupplierName',
						),
						array(
							'header'=>'Branch',
							'name'=>'FixedPaymentSupplierBranchName',
						),
						array(
							'header'=>'Manager',
							'name'=>'FixedPaymentSupplierManager',
						),
						array(
							'header'=>'Status',
							'name'=>'FixedPaymentStatus',
						),
						array(
							'header'=>'Type',
							'name'=>'FixedPaymentExpenseTypeName',
						),
						array(
							'header'=>'Limit',
							'name'=>'FixedPaymentSupplierMaximumLimit',
						),
						array(
							'header'=>'Amount',
							'name'=>'FixedPaymentAmount',
						),
						array(
							'header'=>'Period',
							'name'=>'FixedPaymentPeriod',
						),
						array(
							'header'=>'Actions',
							'name'=>'Action',
						),
					),
				)); ?>
			</div>
        </div>
    </div>
</div>

