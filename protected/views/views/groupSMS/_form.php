<?php
/* @var $this GroupSMSController */
/* @var $model GroupSMS */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-sms-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textField($model,'message',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>9,'maxlength'=>9)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createdBy'); ?>
		<?php echo $form->textField($model,'createdBy'); ?>
		<?php echo $form->error($model,'createdBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createdAt'); ?>
		<?php echo $form->textField($model,'createdAt'); ?>
		<?php echo $form->error($model,'createdAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actionedBy'); ?>
		<?php echo $form->textField($model,'actionedBy'); ?>
		<?php echo $form->error($model,'actionedBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actionedAt'); ?>
		<?php echo $form->textField($model,'actionedAt'); ?>
		<?php echo $form->error($model,'actionedAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actionReason'); ?>
		<?php echo $form->textField($model,'actionReason',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'actionReason'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->