<?php
/* @var $this GroupSMSController */
/* @var $model GroupSMS */

$this->breadcrumbs=array(
	'Group Sms'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GroupSMS', 'url'=>array('index')),
	array('label'=>'Create GroupSMS', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#group-sms-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Group Sms</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'group-sms-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'message',
		'status',
		'createdBy',
		'createdAt',
		'actionedBy',
		/*
		'actionedAt',
		'actionReason',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
<?php
$this->breadcrumbs=array(
	'Group_SMS'=> array('admin'),
	'Manage'   => array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#group-sms-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
            <?php if(CommonFunctions::checkIfFlashMessageSet('success') === 1):?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<?=CommonFunctions::displayFlashMessage('success');?>
			</div>
			<?php endif;?>
			<?php if(CommonFunctions::checkIfFlashMessageSet('info') === 1):?>
				<div class="col-lg-12 col-md-12 col-sm-12">
				<?=CommonFunctions::displayFlashMessage('info');?>
				</div>
			<?php endif;?>
			<?php if(CommonFunctions::checkIfFlashMessageSet('warning') === 1):?>
				<div class="col-lg-12 col-md-12 col-sm-12">
				<?=CommonFunctions::displayFlashMessage('warning');?>
				</div>
			<?php endif;?>
			<?php if(CommonFunctions::checkIfFlashMessageSet('danger') === 1):?>
			<div class="col-lg-12 col-md-12 col-sm-12">
			<?=CommonFunctions::displayFlashMessage('danger');?>
			</div>
			<?php endif;?>
        <div class="card-body">
				<div class="card-header">
					<h5 class="title">Group SMS Administration</h5>
					<hr class="common_rule">
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="search-form">
						<?php $this->renderPartial('_search',array(
							'model'=>$model,
						)); ?>
					</div><!-- search-form -->
					<hr class="common_rule">
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<?php if(Navigation::checkIfAuthorized(285) === 1):?>
						<a href="<?=Yii::app()->createUrl('groupSMS/create');?>" title='Initiate SMS' class="btn btn-success">Initiate SMS</a>
					<?php endif;?>
					<hr class="common_rule">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'group-sms-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No SMS Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Date Initiated',
								'name'=>'GroupSMSDateInitiated',
							),
							array(
								'header'=>'Message',
								'name'=>'GroupSMSMessage',
							),
							array(
								'header'=>'Status',
								'name'=>'GroupSMSStatus',
							),
							array(
							    'header'=>'Actions',
							    'name'=>'GroupSMSAction',
							),
						),
					)); ?>
				</div>
			<br>
		</div>
	</div>
  </div>
</div>
