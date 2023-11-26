<?php
/* @var $this StrayRepaymentsController */
/* @var $model StrayRepayments */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'transaction_id'); ?>
		<?php echo $form->textField($model,'transaction_id',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'transaction_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'providerRefId'); ?>
		<?php echo $form->textField($model,'providerRefId',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'providerRefId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'clientAccount'); ?>
		<?php echo $form->textField($model,'clientAccount',array('size'=>25,'maxlength'=>25)); ?>
		<?php echo $form->error($model,'clientAccount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source'); ?>
		<?php echo $form->textField($model,'source',array('size'=>60,'maxlength'=>75)); ?>
		<?php echo $form->error($model,'source'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>1200)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'firstname'); ?>
		<?php echo $form->textField($model,'firstname',array('size'=>60,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'firstname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastname'); ?>
		<?php echo $form->textField($model,'lastname',array('size'=>60,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'lastname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->