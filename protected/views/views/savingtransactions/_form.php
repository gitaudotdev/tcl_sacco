<?php
/* @var $this SavingtransactionsController */
/* @var $model Savingtransactions */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'savingtransactions-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
	        	<label >Account Number</label>
	        	<?=$form->dropDownList($model,'savingaccount_id',$model->getSavingAccountNumbersList(),array('prompt'=>'Select Saving Account','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'savingaccount_id');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		   <div class="col-md-6 col-lg-6 col-sm-12">
	        <div class="form-group">
	        	<label >Transaction Amount</label>
	        	<?=$form->textField($model,'amount',array('class'=>'form-control','required'=>'required','placeholder'=>'Amount in Digits')); ?>
	        	<?=$form->error($model,'amount');?>
	        </div>
	      </div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
	        	<label >Transaction Type</label>
	        	<?=$form->dropDownList($model,'type',array('credit'=>'Deposit Funds','debit'=>'Withdraw Funds'),array('prompt'=>'Select Transaction Type','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'type');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
	   <div class="col-md-6 col-lg-6 col-sm-12">
	        <div class="form-group">
	        	<label >Transaction Description</label>
	        	<?=$form->textArea($model,'description',array('class'=>'form-control','required'=>'required','cols'=>5,'rows'=>4,'placeholder'=>'Please provide transaction description'));?>
	        	<?=$form->error($model,'description');?>
	        </div>
      </div>
	</div>
	<br>
	<div class="row">
		   <div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	   		<a href="<?=Yii::app()->createUrl('savingtransactions/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
	        </div>
	      </div>
		   <div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Transaction':'Update Transaction',array('class'=>'btn btn-primary pull-right'));?>
	        </div>
	      </div>
	</div>
  <?php $this->endWidget(); ?>
</div><!-- form --><br><br>