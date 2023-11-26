<?php
/* @var $this IncomeTypesController */
/* @var $model IncomeTypes */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->textField($model,'name',array('size'=>15,'maxlength'=>75,'placeholder'=>'Income Type Name','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'name'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Submit Record':'Update Record',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('incomeTypes/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
        </div>
      </div>
	</div>
	<?php $this->endWidget(); ?>
</div><!-- form -->