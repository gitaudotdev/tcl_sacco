<?php
/* @var $this SmsAlertsController */
/* @var $model SmsAlerts */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sms-alerts-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'message_id'); ?>
		<?php echo $form->textField($model,'message_id',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'message_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone_number'); ?>
		<?php echo $form->textField($model,'phone_number',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'phone_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cost'); ?>
		<?php echo $form->textField($model,'cost',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textField($model,'message',array('size'=>60,'maxlength'=>512)); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sent_by'); ?>
		<?php echo $form->textField($model,'sent_by'); ?>
		<?php echo $form->error($model,'sent_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sent_at'); ?>
		<?php echo $form->textField($model,'sent_at'); ?>
		<?php echo $form->error($model,'sent_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->