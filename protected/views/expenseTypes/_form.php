<?php
/* @var $this ExpenseTypesController */
/* @var $model ExpenseTypes */
/* @var $form CActiveForm */
?>
<div class="from col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'expense-types-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->textField($model,'name',array('size'=>15,'maxlength'=>75,'placeholder'=>'Expense Type Name','class'=>'form-control','required'=>'required')); ?>
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
        	<a href="<?=Yii::app()->createUrl('expenseTypes/admin');?>" class="btn btn-default pull-right">Cancel</a>
        </div>
      </div>
	</div>
  <?php $this->endWidget(); ?>
</div><!-- form -->