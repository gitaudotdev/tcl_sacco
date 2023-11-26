<?php
/* @var $this LoaninterestsController */
/* @var $model Loaninterests */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'loaninterests-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?=$form->errorSummary($model); ?>
	<br>
	<div class="row">
    	<div class="col-md-4 col-lg-4 col-sm-12">
			<div class="form-group">
			<?=$form->dropDownList($model,'loanaccount_id',$model->getLoanAcountNumbersList(),array('prompt'=>'-- LOAN ACCOUNTS --','class'=>'selectpicker','required'=>'required')); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
				<?=$form->textField($model,'interest_accrued',array('size'=>60,'maxlength'=>7,'class'=>'form-control','placeholder'=>'Interest Amount','required'=>'required')); ?>
				<?=$form->error($model,'interest_accrued');?>
			</div>
		</div>
	</div>
 	<br>
  <div class="row">
		 <div class="col-md-2 col-lg-2 col-sm-12">
			<div class="form-group">
			 <a href="<?=Yii::app()->createUrl('loaninterests/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
			</div>
     	</div>
		 <div class="col-md-2 col-lg-2 col-sm-12">
			<div class="form-group">
				<?=CHtml::submitButton($model->isNewRecord ? 'Accrue Interest':'Update Interest',array('class'=>'btn btn-primary pull-right'));?>
			</div>
     	</div>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
<br><br>