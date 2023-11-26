<?php
/* @var $this OutPaymentsController */
/* @var $model OutPayments */
/* @var $form CActiveForm */
?>
<div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'out-payments-form',
		'enableAjaxValidation'=>false,
	));?>
	<div class="row">
		<?=$form->errorSummary($model);?>
	</div>
	<div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
        <div class="form-group">
        	<label >Supplier</label>
				<?=$form->dropDownList($model,'user_id',$model->getEligibleSupplierList(),array('prompt'=>'-- SUPPLIER --','class'=>'selectpicker form-control-changed','required'=>'required','id'=>'user_id')); ?>
				<?=$form->error($model,'user_id'); ?>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-12">
        <div class="form-group">
        	<label >Expense Type</label>
				<?=$form->dropDownList($model,'expensetype_id',$model->getExpenseTypeList(),array('prompt'=>'-- EXPENSE TYPES --','class'=>'selectpicker form-control-changed','required'=>'required','id'=>'expensetype_id')); ?>
				<?=$form->error($model,'expensetype_id'); ?>
			</div>
		</div>
	</div>
	<br/>
  <div class="row">
	  <div class="col-md-4 col-lg-4 col-sm-12">
	      <div class="form-group">
	      	<label >Amount</label>
					<?=$form->textField($model,'amount',array('required'=>'required','maxlength'=>15,'class'=>'form-control','placeholder'=>'Expense Amount','id'=>'expenseAmount')); ?>
					<?=$form->error($model,'amount'); ?>
				</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-12">
	    <div class="form-group">
	    	<label >Payment Processing Date</label>
					<?=$form->textField($model,'outpayment_date',array('required'=>'required','maxlength'=>15,'class'=>'form-control','id'=>'normaldatepicker','placeholder'=>'Date Payment Processed')); ?>
				  <?=$form->error($model,'outpayment_date'); ?>
				</div>
		</div>
	</div>
	<br/>
  <div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
	      <div class="form-group">
	      	<label >Payment Recurring Status</label>
	        <?=$form->dropDownList($model,'outpayment_status',array('0'=>'NOT RECURRING','1'=>'RECURRING'),array('prompt'=>'-- RECURRING STATUS --','class'=>'selectpicker'));?>
	      </div>
	  </div>
		<div class="col-md-4 col-lg-4 col-sm-12">
		   <div class="form-group">
		    	<label >Recurring Date</label>
					<?=$form->dropDownList($model,'outpayment_recur_date',$model->getOutPaymentRecurringList(),array('prompt'=>'-- RECURRENCE DATE --','class'=>'selectpicker','required'=>'required')); ?>
					<?=$form->error($model,'outpayment_recur_date'); ?>
			</div>
		</div>
	</div>
  <br/>
  <div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
		   <div class="form-group">
		    	<label >Payment Reason</label>
					<?=$form->textArea($model,'initiation_reason',array('placeholder'=>'Please provide brief comment....','class'=>' form-control','cols'=>5,'rows'=>2,'required'=>'required')); ?>
					<?=$form->error($model,'initiation_reason'); ?>
			</div>
		</div>
	</div>
	<br/>
	<div class="row">
    <div class="col-md-4 col-lg-4 col-sm-12">
      <div class="form-group">
      	<a href="<?=Yii::app()->createUrl('outPayments/admin');?>" class="btn btn-info"><i class="fa fa-arrow-left"></i>Previous</a>
      </div>
    </div>
		<div class="col-md-4 col-lg-4 col-sm-12">
      <div class="form-group">
      	<?=CHtml::submitButton($model->isNewRecord ? 'Initiate Payment':'Update Payment',array('class'=>'btn btn-primary pull-right','id'=>'submitApplication'));?>
      </div>
    </div>
</div><br/>
<?php $this->endWidget(); ?>
</div><!-- form -->