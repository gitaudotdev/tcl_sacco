<?php
/* @var $this LeavesController */
/* @var $model Leaves */
/* @var $form CActiveForm */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'leaves-form',
	'enableAjaxValidation'=>false,
));?>
  <br>
	<?=$form->errorSummary($model); ?>
	<?php if($model->isNewRecord):?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-6 pr-1">
      <div class="form-group">
				<?=$form->labelEx($model,'user_id'); ?>
				<?=$form->dropDownList($model,'user_id',$model->getSaccoStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker')); ?>
				<?=$form->error($model,'user_id'); ?>
			</div>
		</div>
	</div>
  <br>
	<?php endif;?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-6 pr-1">
      <div class="form-group">
				<?=$form->labelEx($model,'leave_days'); ?>
				<?=$form->textField($model,'leave_days',array('class'=>'form-control','maxlength'=>'2')); ?>
				<?=$form->error($model,'leave_days'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-6 pr-1">
      <div class="form-group">
				<?=$form->labelEx($model,'carry_over'); ?>
				<?=$form->textField($model,'carry_over',array('class'=>'form-control','maxlength'=>'2')); ?>
				<?=$form->error($model,'carry_over'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-3 pr-1">
     		 <div class="form-group">
				<a href="<?=Yii::app()->createUrl('leaves/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-3 pr-1">
      		<div class="form-group">
				<?=CHtml::submitButton($model->isNewRecord ? 'Create Record' : 'Save Record',array('class'=>'btn btn-primary pull-right')); ?>
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form --><br><br>