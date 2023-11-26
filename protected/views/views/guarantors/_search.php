<?php
/* @var $this GuarantorsController */
/* @var $model Guarantors */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	));?>
	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- SELECT BRANCH --','class'=>'selectpicker'));?>
        </div>
     </div>

     <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'rm',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGER --','class'=>'selectpicker'));?>
        </div>
     </div>

     <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'user_id',$model->getUsersList(),array('prompt'=>'-- ACCOUNT HOLDER --','class'=>'selectpicker'));?>
        </div>
     </div>

	   <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'loanaccount_id',$model->getLoanAcountNumbersList(),array('prompt'=>'-- LOAN ACCOUNTS --','class'=>'selectpicker'));?>
        </div>
     </div>
</div>
<br>
<div class="row">
     <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->textField($model,'id_number',array('class'=>'form-control','placeholder'=>'Guarantor ID Number')); ?>
        </div>
     </div>

     <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->textField($model,'name',array('class'=>'form-control','placeholder'=>'Guarantor Name')); ?>
        </div>
     </div>
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
     </div>
     <br>
<div class="row">
    <div class="col-md-12 col-lg-12 col-sm-12">
      <div class="form-group pull-right">
       <?=CHtml::submitButton('Search Guarantors',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
      </div>
    </div>
</div>
	<?php $this->endWidget(); ?>
  </div>
</div><!-- search-form -->
<hr>