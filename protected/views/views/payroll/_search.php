<?php
/* @var $this PayrollController */
/* @var $model Payroll */
/* @var $form CActiveForm */
?>
<div class="wide col-md-12 col-lg-12 col-sm-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
<br>
	<div class="row">
			<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          		<?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- STAFF BRANCHES --','class'=>'selectpicker')); ?>
					</div>
			</div>
		 <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
            <?=$form->dropDownList($model,'user_id',$model->getStaffPayrollList(),array('prompt'=>'-- STAFF MEMBERS --','class'=>'selectpicker')); ?>
          </div>
      </div>
			<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
						<?=$form->dropDownList($model,'payroll_month',$model->getPayrollMonthArray(),array('prompt'=>'-- PAYROLL MONTH --','class'=>'selectpicker')); ?>
					</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
						<?=$form->dropDownList($model,'payroll_year',$model->getPayrollYearArray(),array('prompt'=>'-- PAYROLL YEAR --','class'=>'selectpicker')); ?>
					</div>
			</div>
	</div>
	<br>
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
						<?=$form->dropDownList($model,'processed_by',$model->getSystemAdminsList(),array('prompt'=>'-- PROCESSED BY --','class'=>'selectpicker','title'=>'Processed By')); ?>
					</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
						<?=CHtml::submitButton('Search Payroll Records',array('class'=>'btn btn-primary ','style'=>'margin-top:0% !important;')); ?>
					</div>
			</div>
	</div>
<br>
<?php $this->endWidget(); ?>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>