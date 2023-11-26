<?php
/* @var $this GuarantorsController */
/* @var $model Guarantors */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'guarantors-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model);?>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
        		<label >Select Loan Account</label>
					<?=$form->dropDownList($model,'loanaccount_id',$model->getLoanAcountNumbersList(),array('prompt'=>'-- LOAN ACCOUNTS --','class'=>'selectpicker','required'=>'required'));?>
					<?=$form->error($model,'loanaccount_id');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
        		<label >Guarantor Full Name</label>
				<?=$form->textField($model,'name',array('size'=>15,'maxlength'=>75,'placeholder'=>'Guarantor Name','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'name');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
        		<label >Guarantor ID Number</label>
				<?=$form->textField($model,'id_number',array('size'=>15,'maxlength'=>15,'placeholder'=>'Guarantor ID Number','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'id_number');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
        		<label >Guarantor Phone Number</label>
				<?=$form->textField($model,'phone',array('size'=>15,'maxlength'=>15,'placeholder'=>'Guarantor Phone Number','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'phone');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create':'Update',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('guarantors/admin');?>" class="btn btn-default pull-right">Cancel</a>
        </div>
      </div>
	</div>
 	<?php $this->endWidget(); ?>
</div><!-- form -->