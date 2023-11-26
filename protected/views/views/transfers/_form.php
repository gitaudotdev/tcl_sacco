<?php
/* @var $this TransfersController */
/* @var $model Transfers */
/* @var $form CActiveForm */
?>

<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'transfers-form',
		'enableAjaxValidation'=>false,
	));?>
	<?=$form->errorSummary($model);?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
				<?=$form->labelEx($model,'savingaccount_id'); ?>
				<?=$form->dropDownList($model,'savingaccount_id',$model->getSavingAccountNumbersList(),array('prompt'=>'-- SAVING ACCOUNTS --','class'=>'selectpicker'));?>
				<?=$form->error($model,'savingaccount_id'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
				<?=$form->labelEx($model,'loanaccount_id'); ?>
				<?=$form->dropDownList($model,'loanaccount_id',$model->getLoanAccountNumbersList(),array('prompt'=>'-- LOAN ACCOUNTS --','class'=>'selectpicker'));?>
				<?=$form->error($model,'loanaccount_id'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
				<?=$form->labelEx($model,'amount'); ?>
				<?=$form->textField($model,'amount',array('size'=>15,'maxlength'=>15,'class'=>'form-control')); ?>
				<?=$form->error($model,'amount'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
				<?=$form->labelEx($model,'approver'); ?>
				<?=$form->dropDownList($model,'approver',$model->getAuthList(),array('prompt'=>'-- APPROVERS --','class'=>'selectpicker'));?>
				<?=$form->error($model,'approver'); ?>
			</div>
		</div>
	</div>
	<br><br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
			<?=CHtml::submitButton($model->isNewRecord ? 'Create Request' : 'Update Request',array('class'=>'btn btn-primary')); ?>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
			<a href="<?=Yii::app()->createUrl('withdrawals/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
		</div>
	</div>
	<br><br>
<?php $this->endWidget(); ?>
</div><!-- form -->