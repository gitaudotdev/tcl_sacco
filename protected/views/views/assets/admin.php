<?php
$this->pageTitle=Yii::app()->name . ' - Assets';
$this->breadcrumbs=array(
	'Assets'=>array('admin'),
	'Manage'=>array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#assets-grid').yiiGridView('update', {
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
						<h5 class="title">Manage Assets</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
					<?php if(Navigation::checkIfAuthorized(143) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('assets/create');?>" title='Create Asset' class="btn btn-success pull-left"> New Asset</a>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<hr class="common_rule">
						</div>
					<?php endif;?>
					<div class="table-responsive">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'assets-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Assets Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Branch',
								'name'=>'AssetBranchName',
							),
							array(
								'header'=>'Staff Assigned',
								'name'=>'AssetStaffName',
							),
							array(
								'header'=>'Name',
								'name'=>'AssetName',
							),
							array(
								'header'=>'Type',
								'name'=>'AssetTypeName',
							),
							array(
								'header'=>'Serial #',
								'name'=>'AssetSerialNumber',
							),
							array(
								'header'=>'Purchase Price',
								'name'=>'AssetPurchasePrice',
							),
							array(
								'header'=>'Replacement Value',
								'name'=>'AssetReplacementValue',
							),
							array(
								'header'=>'Status',
								'name'=>'AssetStatus',
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