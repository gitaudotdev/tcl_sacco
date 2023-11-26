<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'fixed-payments-form',
		'enableAjaxValidation'=>false,
	));?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
			<div class="form-group">
	      		<label >Select Supplier</label>
	      		<?=$form->dropDownList($model,'user_id',$model->getFixedPaymentSuppliers(),
	     				 array('prompt'=>'-- SUPPLIERS --','class'=>'selectpicker','required'=>'required','id'=>'user_id'));?>
				<small class="errorField" id="supplierError"></small>
			</div>
		</div>
	</div><br/>

	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
			<div class="form-group">
	     	 <label >Maximum Limit</label>
			<?php if($model->isNewRecord):?>
				<input type="text" class="form-control" id="maxLimit" value="" readonly="readonly">
			<?php else:?>
				<input type="text" class="form-control" id="maxLimit" readonly="readonly">
			<?php endif;?>
			</div>
		</div>
	</div><br/>

	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
			<div class="form-group">
	      <label >Expense Type </label>
	      <?=$form->dropDownList($model,'expensetype_id',$model->getFixedPaymentTypes(),
	      array('prompt'=>'-- EXPENSE TYPES --','class'=>'selectpicker','required'=>'required','id'=>'expensetype_id'));?>
				<small class="errorField" id="expenseTypesError"></small>
			</div>
		</div>
	</div><br/>

	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
			<div class="form-group">
	      <label >Payment Amount</label>
				<?=$form->textField($model,'amount',array('size'=>15,'maxlength'=>15,'class'=>'form-control','required'=>'required','id'=>'amount'));?>
				<small class="errorField" id="amountError"></small>
			</div>
		</div>
	</div><br/>

	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
			<div class="form-group">
	      <?php if($model->isNewRecord):?>
	      <label >Payment Period </label>
						<?=$form->textField($model,'expense_month',array('class'=>'form-control','required'=>'required','id'=>'month_date'));?>
					<?php else:?>
	      		<label >Expense Month</label>
						<?=$form->textField($model,'expense_month',array('class'=>'form-control','required'=>'required','value'=>$model->expense_month,'readonly'=>'readonly'));?>
					<?php endif;?>
				<small class="errorField" id="expenseMonthError"></small>
			</div>
		</div>
	</div><br/>

	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
			<div class="form-group">
				<a href="<?=Yii::app()->createUrl('fixedPayments/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
			<div class="form-group">
	      <?=CHtml::submitButton($model->isNewRecord ? 'Initiate Payment':'Update Payment',array('class'=>'btn btn-primary pull-right','id'=>'initiate_btn'));?>
			</div>
		</div>
	</div><br/>
<?php $this->endWidget(); ?>
</div><!-- form -->