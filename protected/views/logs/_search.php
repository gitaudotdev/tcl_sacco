<?php
/* @var $this LogsController */
/* @var $model Logs */
/* @var $form CActiveForm */
?>
<div class="col-md-12 col-lg-12 col-sm-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
));?>
<br>
	<div class="row">
     <div class="col-md-2 col-lg-2 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker'));?>
      </div>
    </div>
    <div class="col-md-2 col-lg-2 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'user_id',$model->getUsersList(),array('prompt'=>'-- MEMBERS --','class'=>'selectpicker'));?>
      </div>
    </div>
    <div class="col-md-2 col-lg-2 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'severity',array('urgent'=>'Urgent','high'=>'High','normal'=>'Normal','low'=>'Low'),array('prompt'=>'-- LOG SEVERITY --','class'=>'selectpicker'));?>
      </div>
    </div>
    <div class="col-md-2 col-lg-2 col-sm-12">
      <div class="form-group">
        <?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
      </div>
   </div>
   <div class="col-md-2 col-lg-2 col-sm-12">
      <div class="form-group">
        <?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date')); ?>
      </div>
   </div>
  <div class="col-md-2 col-lg-2 col-sm-12">
    <div class="form-group">
     <?=CHtml::submitButton('Search Logs',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
    </div>
  </div>
  </div>
  <br>
<?php $this->endWidget(); ?>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>