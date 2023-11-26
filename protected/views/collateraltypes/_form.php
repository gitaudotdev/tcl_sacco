<?php
/* @var $this CollateraltypesController */
/* @var $model Collateraltypes */
/* @var $form CActiveForm */
?>
<div class="form col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'collateraltypes-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
    	<div class="col-md-6 pr-1">
        	<div class="form-group">
					<?=$form->textField($model,'name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Type Name','required'=>'required')); ?>
					<?=$form->error($model,'name');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Type':'Update Type',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('collateraltypes/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
        </div>
      </div>
	</div>
	<?php $this->endWidget(); ?>
</div><!-- form -->