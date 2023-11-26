<?php
/* @var $this SavingpostingsController */
/* @var $model Savingpostings */
/* @var $form CActiveForm */
?>
<div class="col-md-12 col-lg-12 col-sm-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?><br>
<div class="row">
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
	     <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
	    </div>
	  </div>
</div>
<?php $this->endWidget(); ?>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>