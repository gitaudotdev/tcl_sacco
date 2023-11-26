<?php
/* @var $this StrayRepaymentsController */
/* @var $model StrayRepayments */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
<br>
	<div class="row">
      <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'transaction_id',array('class'=>'form-control','placeholder'=>'Transaction ID')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'clientAccount',array('class'=>'form-control','placeholder'=>'Account Number')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'source',array('class'=>'form-control','placeholder'=>'Phone Number')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'lastname',array('class'=>'form-control','placeholder'=>'Surname')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'date',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=CHtml::submitButton('Search Record',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
          </div>
       </div>
  </div>
<?php $this->endWidget(); ?>
</div><!-- search-form -->
</div>
<hr class="common_rule">