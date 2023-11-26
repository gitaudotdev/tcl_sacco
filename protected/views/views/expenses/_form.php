<?php
/* @var $this ExpensesController */
/* @var $model Expenses */
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
  	<div class="col-md-4 col-lg-4 col-sm-12">
      	<div class="form-group">
      		<label > Select Branch </label>
				<?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'branch_id'); ?>
			</div>
		</div>
    <div class="col-md-4 col-lg-4 col-sm-12">
      	<div class="form-group">
      		<label >Expense Type</label>
				<?=$form->dropDownList($model,'expensetype_id',$model->getExpenseTypesList(),array('prompt'=>'-- EXPENSE TYPE --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'expensetype_id'); ?>
			</div>
		</div>
  	<div class="col-md-4 col-lg-4 col-sm-12">
      	<div class="form-group">
      		<label >Expense Name</label>
			<?=$form->textField($model,'name',array('size'=>15,'maxlength'=>512,'placeholder'=>'Expense name','class'=>'form-control','required'=>'required')); ?>
			<?=$form->error($model,'name'); ?>
		</div>
	</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
        	<label >Expense Amount</label>
				<?=$form->textField($model,'amount',array('size'=>15,'maxlength'=>15,'placeholder'=>'Expense amount','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'amount'); ?>
			</div>
		</div>
    	<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
        	<label >Expense Date</label>
				<?=$form->textField($model,'expense_date',array('size'=>15,'maxlength'=>15,'placeholder'=>'Date','class'=>'form-control','required'=>'required','id'=>'normaldatepicker')); ?>
				<?=$form->error($model,'expense_date'); ?>
			</div>
		</div>
    <div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
        		<label >Expense Status</label>
					<?=$form->dropDownList($model,'expense_recur',array('0'=>'Not Recurring','1'=>'Recurring'),array('prompt'=>'-- RECURRENCE STATUS --','class'=>'selectpicker','required'=>'required')); ?>
					<?=$form->error($model,'expense_recur'); ?>
				</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-4 col-lg-4 col-sm-12">
        <div class="form-group">
        	<label >Recurring Date</label>
				<?=$form->dropDownList($model,'date_recurring',$model->getExpenseRecurringList(),array('prompt'=>'-- RECURRENCE DATE --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'date_recurring'); ?>
			</div>
		</div>
    <div class="col-md-4 col-lg-4 col-sm-12">
        <div class="form-group">
        	<label >Expense Description</label>
				<?=$form->textArea($model,'description',array('placeholder'=>'Briefly describe expense','class'=>'form-control','required'=>'required','cols'=>5,'rows'=>1)); ?>
				<?=$form->error($model,'description'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Expense':'Update Expense',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('expenses/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
        </div>
      </div>
	</div>
	<br><br>
	<?php $this->endWidget(); ?>
</div><!-- form -->