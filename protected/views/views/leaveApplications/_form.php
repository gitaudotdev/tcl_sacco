<?php
/* @var $this LeaveApplicationsController */
/* @var $model LeaveApplications */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'leave-applications-form',
		'enableAjaxValidation'=>false,
	));?>
	<br>
	<?=$form->errorSummary($model); ?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-6 pr-1">
      <div class="form-group">
				<?=$form->labelEx($model,'start_date'); ?>
				<?=$form->textField($model,'start_date',array('class'=>'form-control','id'=>'start_date')); ?>
				<?=$form->error($model,'start_date'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-6 pr-1">
      <div class="form-group">
				<?=$form->labelEx($model,'end_date'); ?>
				<?=$form->textField($model,'end_date',array('class'=>'form-control','id'=>'end_date')); ?>
				<?=$form->error($model,'end_date');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-3 pr-1">
      <div class="form-group">
				<?=CHtml::submitButton($model->isNewRecord ? 'Create Application' : 'Update Application',array('class'=>'btn btn-primary')); ?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-3 pr-1">
      <div class="form-group">
				<a href="<?=Yii::app()->createUrl('leaves/'.Yii::app()->user->user_id);?>" class="btn btn-default pull-right">Cancel Action</a>
			</div>
		</div>
	</div>
	<br>
<?php $this->endWidget(); ?>
</div><!-- form -->