<?php
/* @var $this AuthsController */
/* @var $model Auths */

$this->breadcrumbs=array(
	'Auths'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Auths', 'url'=>array('index')),
	array('label'=>'Create Auths', 'url'=>array('create')),
	array('label'=>'Update Auths', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Auths', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Auths', 'url'=>array('admin')),
);
?>

<h1>View Auths #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'profileId',
		'username',
		'password',
		'level',
		'authStatus',
		'resetToken',
		'lastLoggedAt',
		'createdAt',
		'createdBy',
		'updatedAt',
	),
)); ?>
