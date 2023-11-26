<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Saving Products';
$this->breadcrumbs=array(
	'Settings'=>array('dashboard/admin'),
    'Savingproducts'=>array('savingproducts/admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#savingproducts-grid').yiiGridView('update', {
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
        <div class="card-header">
        		<div class="col-lg-8 col-md-8 col-sm-8">
	            <h5 class="title">Manage Saving Products</h5>
	          </div>
            <?php if($succesStatus === 1):?>
		    <div class="col-lg-4 col-md-4 col-sm-4">
		      <?=CommonFunctions::displayFlashMessage($successType);?>
		    </div>
		    <?php endif;?>
		    <?php if($infoStatus === 1):?>
		      <div class="col-lg-4 col-md-4 col-sm-4">
		        <?=CommonFunctions::displayFlashMessage($infoType);?>
		      </div>
		    <?php endif;?>
		    <?php if($warningStatus === 1):?>
		      <div class="col-lg-4 col-md-4 col-sm-4">
		        <?=CommonFunctions::displayFlashMessage($warningType);?>
		      </div>
		    <?php endif;?>
		    <?php if($dangerStatus === 1):?>
		      <div class="col-lg-4 col-md-4 col-sm-4">
		        <?=CommonFunctions::displayFlashMessage($dangerType);?>
		      </div>
		    <?php endif;?>
        </div>
        <div class="card-body col-md-12">
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
						<div class="col-md-12 col-lg-12 col-sm-12">
					<a href="<?=Yii::app()->createUrl('savingproducts/create');?>" title='Create Saving Product' class="btn btn-success btn-round btn-sm pull-left"><i class='now-ui-icons ui-1_simple-add'></i>  New Saving Product</a>
				</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="table-responsive">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'savingproducts-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Saving Products Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Product Name',
								'name'=>'SavingProductName',
							),
							array(
								'header'=>'Opening Balance',
								'name'=>'SavingProductOpeningBalance',
							),
							array(
								'header'=>'Interest Rate',
								'name'=>'SavingProductInterestRate',
							),
							array(
								'header'=>'Interest Posting Frequency',
								'name'=>'InterestPostingFrequency',
							),
							array(
								'header'=>'Interest Posting Date',
								'name'=>'InterestPostingDate',
							),
							array(
								'header'=>'Saving Product Actions',
								'name'=>'Action',
							),
						),
					)); ?>
				</div>
				</div>
			</div>
    </div>
</div>