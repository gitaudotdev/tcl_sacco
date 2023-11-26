<?php
/* @var $this FoldersController */
/* @var $model Folders */

$this->breadcrumbs=array(
	'Folders'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Folders', 'url'=>array('index')),
	array('label'=>'Create Folders', 'url'=>array('create')),
	array('label'=>'View Folders', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Folders', 'url'=>array('admin')),
);
?>

<h1>Update Folders <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>