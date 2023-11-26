<?php
/* @var $this SavingproductsController */
/* @var $model Savingproducts */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'savingproducts-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
	        	&nbsp;Saving Product<br><br>
	        	<?=$form->textField($model,'name',array('class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'name');?>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
				 &nbsp;Openinng Balance<br><br>
	        	<?=$form->textField($model,'opening_balance',array('class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'opening_balance');?>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
				 &nbsp;Interest Rate<br><br>
	        	<?=$form->textField($model,'interest_rate',array('class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'interest_rate');?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
	        	&nbsp;Interest Posting Frequency<br><br>
	        	<?=$form->dropDownList($model,'interest_posting_frequency',array('0'=>'Every Month','1'=>'Every Six(6) Months','2'=>'Every Year(Yearly)'),array('prompt'=>'Select Posting Frequency','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'interest_posting_frequency');?>
			</div>
		</div>
		<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
				  &nbsp;Posting Date<br><br>
        	<?=$form->dropDownList($model,'posting_date',$model->getPostingDateList(),array('prompt'=>'Select Posting Date','class'=>'selectpicker','required'=>'required')); ?>
					<?=$form->error($model,'posting_date');?>
			</div>
		</div>
	</div>
	<br>
	<hr>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Saving Product':'Update Saving Product',array('class'=>'btn btn-primary btn-round'));?>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('savingproducts/admin');?>" class="btn btn-default btn-round pull-right">Cancel</a>
        </div>
      </div>
	</div>
	<?php $this->endWidget(); ?>
</div><!-- form -->