<?php
/* @var $this NoticesController */
/* @var $model Notices */
/* @var $form CActiveForm */
?>
<div class="form col-md-12 col-lg-12 col-sm-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notices-form',
	'enableAjaxValidation'=>false,
));?>
	<?=$form->errorSummary($model);?>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
				<?=$form->labelEx($model,'message'); ?>
				<?=$form->textArea($model,'message',array('size'=>60,'maxlength'=>1200,'class'=>'form-control','placeholder'=>'Brief notice message ...')); ?>
				<?=$form->error($model,'message'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
			<?=$form->labelEx($model,'level'); ?><br><br>
			<?=$form->dropDownList($model,'level',array('0'=>'All','1'=>'Superadmin','2'=>'Admin','3'=>'Staff','4'=>'Member','5'=>'Shareholder','6'=>'Regional'),array('prompt'=>'-- NOTICE LEVEL--','class'=>'selectpicker')); ?>
			<?=$form->error($model,'level'); ?>
		</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
				<a href="<?=Yii::app()->createUrl('notices/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
      		<div class="form-group">
				<?=CHtml::submitButton($model->isNewRecord ? 'Publish Notice' : 'Update Notice',array('class'=>'btn btn-primary pull-right')); ?>
			</div>
		</div>
	</div>
	<br>
<?php $this->endWidget(); ?>
</div><!-- form -->