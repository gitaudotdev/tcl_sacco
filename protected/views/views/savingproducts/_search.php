<?php
/* @var $this SavingproductsController */
/* @var $model Savingproducts */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?>
	<div class="row">
           <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
              	<?=$form->textField($model,'name',array('class'=>'form-control','placeholder'=>'Product Name')); ?>
              </div>
           </div>
           <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
               	<?=$form->dropDownList($model,'interest_posting_frequency',array('0'=>'Every Month','1'=>'Every Six(6) Months','2'=>'Every Year(Yearly)'),array('prompt'=>'Select Posting Frequency','class'=>'selectpicker')); ?>
              </div>
           </div>
           <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
              	<?=$form->dropDownList($model,'posting_date',$model->getPostingDateList(),array('prompt'=>'Select Posting Date','class'=>'selectpicker')); ?>
					<?=$form->error($model,'posting_date');?>
              </div>
           </div>
           <div class="col-md-3 col-lg-3 col-sm-12">
	            <div class="form-group">
					<?=CHtml::submitButton('Search Product',array('class'=>'btn btn-primary btn-round','style'=>'margin-top:-2% !important;')); ?>
	            </div>
	        </div>
      </div>
	<?php $this->endWidget(); ?>
	</div><!-- search-form -->
</div>
<hr>