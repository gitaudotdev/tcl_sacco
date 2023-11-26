<?php
/* @var $this PerformanceSettingsController */
/* @var $model PerformanceSettings */
$this->pageTitle=Yii::app()->name . ' - Payroll Performance Settings';

$this->breadcrumbs=array(
	'Performance_Settings'=>array('admin'),
	'Manage'=>array('admin'),
);
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
					<h5 class="title">Performance Settings</h5>
					<hr class="common_rule">
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll !important;margin-bottom: 2.5% !important;">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'performance-settings-grid',
					'type'=>'bordered hover',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Records Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Name',
							'name'=>'PerformanceName',
						),
						array(
							'header'=>'Minimum',
							'name'=>'PerformanceMinimumValue',
						),
						array(
							'header'=>'Maximum',
							'name'=>'PerformanceMaximumValue',
						),
						array(
							'header'=>'% Multiplier',
							'name'=>'PerformancePercentMultiplier',
						),
						array(
							'header'=>'Colour',
							'name'=>'PerformanceColour',
						),
						array(
							'header'=>'Action',
							'name'=>'Action',
						),
					),
				)); ?>
			</div><br><br>
      </div>
    </div>
</div>
