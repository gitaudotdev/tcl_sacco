<?php
/* @var $this IncomesController */
/* @var $model Incomes */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	));?>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<?=$form->dropDownList($model,'incometype_id',$model->getIncomeTypesList(),array('prompt'=>'-- INCOME TYPES --','class'=>'selectpicker'));?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
          	<?=$form->textField($model,'transaction_date',array('class'=>'form-control','placeholder'=>'Transaction Date','id'=>'normaldatepicker')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
          	<?=$form->dropDownList($model,'income_recur',array('0'=>'Not Recurring','1'=>'Recurring'),array('prompt'=>'-- RECURRENCE STATUS --','class'=>'selectpicker')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
          	<?=$form->dropDownList($model,'date_recurring',$model->getIncomeRecurringList(),array('prompt'=>'-- RECURRENCE DATE --','class'=>'selectpicker')); ?>
          </div>
       </div>
       <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
			<?=CHtml::submitButton('Search Income',array('class'=>'btn btn-primary btn-round','style'=>'margin-top:-2% !important;')); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    </div>
</div><!-- search-form -->
<hr>