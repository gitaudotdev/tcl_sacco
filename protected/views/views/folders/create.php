<?php
/* @var $this FoldersController */
/* @var $model Folders */

$this->breadcrumbs=array(
	'Folders'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Folders', 'url'=>array('index')),
	array('label'=>'Manage Folders', 'url'=>array('admin')),
);
?>

<h1>Create Folders</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>