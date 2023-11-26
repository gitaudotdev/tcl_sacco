<?php
/* @var $this AuthsController */
/* @var $model Auths */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'auths-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'profileId'); ?>
		<?php echo $form->textField($model,'profileId'); ?>
		<?php echo $form->error($model,'profileId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>75)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->textField($model,'level',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'authStatus'); ?>
		<?php echo $form->textField($model,'authStatus',array('size'=>9,'maxlength'=>9)); ?>
		<?php echo $form->error($model,'authStatus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'resetToken'); ?>
		<?php echo $form->textField($model,'resetToken',array('size'=>60,'maxlength'=>1024)); ?>
		<?php echo $form->error($model,'resetToken'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastLoggedAt'); ?>
		<?php echo $form->textField($model,'lastLoggedAt'); ?>
		<?php echo $form->error($model,'lastLoggedAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createdAt'); ?>
		<?php echo $form->textField($model,'createdAt'); ?>
		<?php echo $form->error($model,'createdAt'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createdBy'); ?>
		<?php echo $form->textField($model,'createdBy'); ?>
		<?php echo $form->error($model,'createdBy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updatedAt'); ?>
		<?php echo $form->textField($model,'updatedAt'); ?>
		<?php echo $form->error($model,'updatedAt'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->