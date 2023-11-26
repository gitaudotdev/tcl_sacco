<?php
/* @var $this CollateralController */
/* @var $model Collateral */

$this->pageTitle=Yii::app()->name . ' -  Collaterals';
$this->breadcrumbs=array(
    'Collaterals'=>array('admin'),
    'Manage'=>array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#collateral-grid').yiiGridView('update', {
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
					<h5 class="title">Manage Collaterals</h5>
					<hr class="common_rule">
				</div>
        		<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="search-form">
						<?php $this->renderPartial('_search',array(
							'model'=>$model,
						)); ?>
					</div>
					<!-- search-form -->
					<?php if(Navigation::checkIfAuthorized(139) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
						<a href="<?=Yii::app()->createUrl('collateral/create');?>" title='Create Collateral' class="btn btn-success pull-left"> New Collateral</a>
					</div>
					<div class="col-md-12 col-lg-12 col-sm-12">
						<hr class="common_rule">
					</div>
				<?php endif;?>
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12">
				<div class="table-responsive">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'collateral-grid',
					'type'=>'condensed striped',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Collaterals Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Branch',
							'name'=>'CollateralBranchName',
						),
						array(
							'header'=>'Staff',
							'name'=>'CollateralStaffName',
						),
						array(
							'header'=>'Type',
							'name'=>'CollateralTypeName',
						),
						array(
							'header'=>'Model',
							'name'=>'CollateralModel',
						),
						array(
							'header'=>'Serial #',
							'name'=>'CollateralSerialNumber',
						),
						array(
							'header'=>'Market Value',
							'name'=>'CollateralMarketValue',
						),
						array(
							'header'=>'Loan to Value Ratio',
							'name'=>'CollateralLoanToValueRatio',
						),
						array(
							'header'=>'Status',
							'name'=>'CollateralCurrentStatus',
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