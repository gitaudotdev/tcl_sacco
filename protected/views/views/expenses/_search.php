<?php
/* @var $this ExpensesController */
/* @var $model Expenses */
/* @var $form CActiveForm */
?>
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	));?>
	<div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
            <?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
          </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
            <?=$form->dropDownList($model,'user_id',$model->getStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker')); ?>
          </div>
        </div>
		    <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<?=$form->dropDownList($model,'expensetype_id',$model->getExpenseTypesList(),array('prompt'=>'-- EXPENSE TYPE --','class'=>'selectpicker'));?>
          </div>
       </div>
       <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
            <?=$form->dropDownList($model,'expense_recur',array('0'=>'One Off','1'=>'Recurring'),array('prompt'=>'-- RECURRENCE STATUS --','class'=>'selectpicker')); ?>
          </div>
       </div>
     </div>
     <br>
     <div class="row">
       <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
            <?=$form->dropDownList($model,'date_recurring',$model->getExpenseRecurringList(),array('prompt'=>'-- RECURRENCE DATE --','class'=>'selectpicker')); ?>
          </div>
       </div>
       <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
          </div>
       </div>
       <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date')); ?>
          </div>
       </div>
       <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
			     <?=CHtml::submitButton('Search Expense',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
          </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
  <hr>
</div>