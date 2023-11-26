<?php
/* @var $this AuthsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Auths',
);

$this->menu=array(
	array('label'=>'Create Auths', 'url'=>array('create')),
	array('label'=>'Manage Auths', 'url'=>array('admin')),
);
?>

<h1>Auths</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
