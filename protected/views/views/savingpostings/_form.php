<?php
/* @var $this SavingpostingsController */
/* @var $model Savingpostings */
/* @var $form CActiveForm */
?>
<div class="form">
 <?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'savingpostings-form',
		'enableAjaxValidation'=>false,
	));?>
	<?=$form->errorSummary($model); ?>
	<?php if($model->isNewRecord):?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      	<div class="form-group">
        	<label >Saving Account</label>
        	<?=$form->dropDownList($model,'savingAccountID',$model->getPostingAccountNumbersList(),array('prompt'=>'-- SELECT ACCOUNT --','class'=>'selectpicker','required'=>'required')); ?>
        </div>
     </div>
	</div>
	<br>
  <?php endif;?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
    	<div class="form-group">
      	<label >Interest Amount</label>
				<?=$form->textField($model,'posted_interest',array('size'=>15,'maxlength'=>15,'class'=>'form-control','required'=>'required')); ?>
      </div>
    </div>
	</div>
	<br>
	<?php if($model->isNewRecord):?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
    	<div class="form-group">
      	<label >Description</label>
				<?=$form->textArea($model,'description',array('row'=>5,'col'=>5,'maxlength'=>512,'class'=>'form-control','required'=>'required','placeholder'=>'Brief description')); ?>
      </div>
    </div>
	</div>
	<br>
<?php endif;?>
	<div class="row">
	   <div class="col-md-3 col-lg-3 col-sm-12">
	   	<div class="form-group">
	   		<a href="<?=Yii::app()->createUrl('savingpostings/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
	   	</div>
	   </div>
	   <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create':'Update',array('class'=>'btn btn-primary pull-right'));?>
        </div>
     </div>
</div><br>
<?php $this->endWidget(); ?>
</div><!-- form -->