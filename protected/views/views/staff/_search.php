<?php
/* @var $this StaffController */
/* @var $model Staff */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?><br>
		<div class="row">
			<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
        </div>
      </div>
			<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'staff_id',$model->getStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'is_supervisor',array('0'=>'Normal Staff','1'=>'Supervisor'),array('prompt'=>'-- SUPERVISORIAL ROLE --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'is_active',array('0'=>'SUSPENDED','1'=>'ACTIVE','2'=>'EXITED','3'=>'ON LEAVE'),array('prompt'=>'-- ACCOUNT STATUS --','class'=>'selectpicker')); ?>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'payroll_listed',array('0'=>'Not Listed','1'=>'Listed'),array('prompt'=>'-- LISTED ON PAYROLL --','class'=>'selectpicker')); ?>
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
					<?=CHtml::submitButton('Search Staff Records',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
        </div>
      </div>
		</div>
		<?php $this->endWidget(); ?>
	</div><!-- search-form -->
</div>
<hr class="common_rule">