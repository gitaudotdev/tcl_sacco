<?php
/* @var $this SavingaccountsController */
/* @var $model Savingaccounts */
/* @var $form CActiveForm */
?>
  <div class="col-md-12 col-lg-12 col-sm-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>
		<div class="row">
     <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker'));?>
      </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'rm',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker'));?>
      </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'user_id',$model->getUsersList(),array('prompt'=>'-- MEMBERS --','class'=>'selectpicker'));?>
      </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'is_approved',array('0'=>'Submitted','1'=>'Approved','2'=>'Rejected'),array('prompt'=>'-- AUTH STATUS --','class'=>'selectpicker'));?>
      </div>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
         <?=$form->textField($model,'account_number',array('class'=>'form-control','placeholder'=>'Account Number')); ?>
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
  <div class="col-md-3 col-lg-3 col-sm-12">
    <div class="form-group">
     <?=CHtml::submitButton('Search Accounts',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
    </div>
  </div>
<?php $this->endWidget(); ?>
</div><!-- search-form -->
<hr class="common_rule">