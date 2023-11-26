<?php
/* @var $this ContactsController */
/* @var $model Contacts */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'profileId'); ?>
		<?php echo $form->textField($model,'profileId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contactType'); ?>
		<?php echo $form->textField($model,'contactType',array('size'=>5,'maxlength'=>5)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isPrimary'); ?>
		<?php echo $form->textField($model,'isPrimary'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'contactValue'); ?>
		<?php echo $form->textField($model,'contactValue',array('size'=>25,'maxlength'=>25)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'isVerified'); ?>
		<?php echo $form->textField($model,'isVerified'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createdAt'); ?>
		<?php echo $form->textField($model,'createdAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createdBY'); ?>
		<?php echo $form->textField($model,'createdBY'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->