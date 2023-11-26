<?php
/* @var $this WithdrawalsController */
/* @var $model Withdrawals */
/* @var $form CActiveForm */
?>
<div class="form col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	));?>
  <br>
	<div class="row">
		 <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker'));?>
      </div>
    </div>
		<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'user_id',$model->getStaffList(),array('prompt'=>'-- INITIATORS --','class'=>'selectpicker'));?>
      </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
        <?=$form->dropDownList($model,'approver',$model->getAuthList(),array('prompt'=>'-- APPROVERS --','class'=>'selectpicker'));?>
      </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
      	<?=$form->dropDownList($model,'savingaccount_id',$model->getSavingAccountNumbersList(),array('prompt'=>'-- ACCOUNTS --','class'=>'selectpicker'));?>
      </div>
    </div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
				<?=$form->dropDownList($model,'is_approved',array('0'=>'Submitted','1'=>'Approved','2'=>'Rejected'),array('prompt'=>'-- STATUS --','class'=>'selectpicker')); ?>
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
     <?=CHtml::submitButton('Search Request',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
    </div>
  </div>
</div>
<?php $this->endWidget(); ?>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>