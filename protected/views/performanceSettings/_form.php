<?php
/* @var $this PerformanceSettingsController */
/* @var $model PerformanceSettings */
/* @var $form CActiveForm */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'performance-settings-form',
		'enableAjaxValidation'=>false,
	));?>
	<?=$form->errorSummary($model); ?>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<label >Name</label>
					<?=$form->textField($model,'name',array('size'=>60,'maxlength'=>75,'class'=>'form-control')); ?>
				</div>
			</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<label >Minimum Value</label>
					<?=$form->textField($model,'minimum',array('class'=>'form-control')); ?>
				</div>
			</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<label >Maximum Value</label>
					<?=$form->textField($model,'maximum',array('class'=>'form-control')); ?>
				</div>
			</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<label >Percent Multiplier</label>
					<?=$form->textField($model,'percent_multiplier',array('class'=>'form-control')); ?>
				</div>
			</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<label >Colour</label>
        	<?=$form->dropDownList($model,'colour',array('red'=>'Red','amber'=>'Amber','green'=>'Green','purple'=>'Purple'),array('required'=>'required','maxlength'=>6,'class'=>'form-control selectpicker','prompt'=>'-- COLOURS --')); ?>
				</div>
			</div>
	</div>
	<br>
	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
		<div class="form-group">
			<a href="<?=Yii::app()->createUrl('performanceSettings/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
		</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
			<div class="form-group">
				<?=CHtml::submitButton($model->isNewRecord ?'Create Setting':'Update Setting',array('class'=>'btn btn-primary pull-right'));?>
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form --><br><br>