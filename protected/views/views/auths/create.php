<?php
/* @var $this AuthsController */
/* @var $model Auths */

$this->breadcrumbs=array(
	'Auths'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Auths', 'url'=>array('index')),
	array('label'=>'Manage Auths', 'url'=>array('admin')),
);
?>

<h1>Create Auths</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>