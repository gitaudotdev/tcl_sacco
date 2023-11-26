<?php
/* @var $this LeaveApplicationsController */
/* @var $model LeaveApplications */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?><br>
	<div class="row">
		<div class="col-md-2 col-lg-2 col-sm-12">
		    <div class="form-group">
				<?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- SELECT BRANCH --','class'=>'selectpicker form-control')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
		    <div class="form-group">
				<?=$form->dropDownList($model,'user_id',$model->getFullSaccoStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker form-control')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
		    <div class="form-group">
				<?=$form->dropDownList($model,'status',array('0'=>'Submitted','1'=>'Approved','2'=>'Rejected'),array('size'=>1,'maxlength'=>1,'class'=>'selectpicker form-control','prompt'=>'-- SELECT STATUS--')); ?>
				</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
		    <div class="form-group">
				<?=$form->textField($model,'start_date',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
		    <div class="form-group">
				<?=$form->textField($model,'end_date',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
			<div class="form-group">
				<?=CHtml::submitButton('Search Requests',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
			</div>
		</div>
	</div>
	<br>
<?php $this->endWidget(); ?>
</div><!-- search-form -->
</div>
<hr class="common_rule">