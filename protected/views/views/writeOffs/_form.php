<?php
/* @var $this WriteOffsController */
/* @var $model WriteOffs */
/* @var $form CActiveForm */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'write-offs-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?=$form->errorSummary($model); ?>

	<div class="row">
		<?=$form->labelEx($model,'loanaccount_id'); ?>
		<?=$form->textField($model,'loanaccount_id'); ?>
		<?=$form->error($model,'loanaccount_id'); ?>
	</div>

	<div class="row">
		<?=$form->labelEx($model,'amount'); ?>
		<?=$form->textField($model,'amount',array('size'=>20,'maxlength'=>20)); ?>
		<?=$form->error($model,'amount'); ?>
	</div>
	<div class="row buttons">
		<?=CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->