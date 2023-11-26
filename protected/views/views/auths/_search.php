<?php
/* @var $this AuthsController */
/* @var $model Auths */
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
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>75)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'level'); ?>
		<?php echo $form->textField($model,'level',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'authStatus'); ?>
		<?php echo $form->textField($model,'authStatus',array('size'=>9,'maxlength'=>9)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'resetToken'); ?>
		<?php echo $form->textField($model,'resetToken',array('size'=>60,'maxlength'=>1024)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lastLoggedAt'); ?>
		<?php echo $form->textField($model,'lastLoggedAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createdAt'); ?>
		<?php echo $form->textField($model,'createdAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'createdBy'); ?>
		<?php echo $form->textField($model,'createdBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updatedAt'); ?>
		<?php echo $form->textField($model,'updatedAt'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->