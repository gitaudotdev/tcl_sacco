<?php
/* @var $this AuthsController */
/* @var $model Auths */

$this->breadcrumbs=array(
	'Auths'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Auths', 'url'=>array('index')),
	array('label'=>'Create Auths', 'url'=>array('create')),
	array('label'=>'View Auths', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Auths', 'url'=>array('admin')),
);
?>

<h1>Update Auths <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>