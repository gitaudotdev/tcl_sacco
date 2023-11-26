<?php
/* @var $this IncomesController */
/* @var $model Incomes */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'expenses-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->textField($model,'name',array('size'=>15,'maxlength'=>512,'placeholder'=>'Income name','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'name'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->dropDownList($model,'incometype_id',$model->getIncomeTypesList(),array('prompt'=>'-- INCOME TYPES --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'incometype_id'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->textField($model,'amount',array('size'=>15,'maxlength'=>15,'placeholder'=>'Income amount','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'amount'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->textField($model,'transaction_date',array('size'=>15,'maxlength'=>15,'placeholder'=>'Transaction Date','class'=>'form-control','required'=>'required','id'=>'normaldatepicker')); ?>
				<?=$form->error($model,'transaction_date'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->dropDownList($model,'income_recur',array('0'=>'Not Recurring','1'=>'Recurring'),array('prompt'=>'-- RECURRENCE STATUS --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'income_recur'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->dropDownList($model,'date_recurring',$model->getIncomeRecurringList(),array('prompt'=>'-- RECURRENCE DATE --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'date_recurring'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->textArea($model,'description',array('placeholder'=>'Briefly describe income','class'=>'form-control','required'=>'required','cols'=>5,'rows'=>3)); ?>
				<?=$form->error($model,'description'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Submit Income':'Update Income',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('incomes/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
        </div>
      </div>
	</div>
	<?php $this->endWidget(); ?>
</div><!-- form -->