<?php
/* @var $this AlertConfigsController */
/* @var $model AlertConfigs */
$this->pageTitle=Yii::app()->name . ' - Notifications Configurations Management';
$this->breadcrumbs=array(
  'SMS_Notifications' => array('admin'),
  'Management'        => array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#alert-configs-grid').yiiGridView('update', {
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
	            <h5 class="title">Notifications Management</h5>
	            <hr class="common_rule">
	          </div>
			  <div class="col-lg-12 col-md-12 col-sm-12">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'alert-configs-grid',
					'type'=>'bordered hover',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Configs Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Name',
							'name'=>'AlertName',
						),
						array(
							'header'=>'Status',
							'name'=>'AlertStatus',
						),
						array(
							'header'=>'Date',
							'name'=>'AlertDate',
						),
						array(
							'header'=>'Actions',
							'name'=>'Action',
						),
					),
				)); ?>
			</div>
			<br><br>
        </div>
    </div>
</div>