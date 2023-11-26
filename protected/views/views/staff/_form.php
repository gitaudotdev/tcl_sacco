<?php
/* @var $this StaffController */
/* @var $model Staff */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'staff-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
    		<label >First Name</label>
					<?=$form->textField($model,'first_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'First Name','required'=>'required')); ?>
					<?=$form->error($model,'first_name');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Last Name</label>
					<?=$form->textField($model,'last_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Last Name','required'=>'required')); ?>
					<?=$form->error($model,'last_name');?>
			</div>
		</div>
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >ID Number</label>
					<?=$form->textField($model,'id_number',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'ID Number','required'=>'required')); ?>
					<?=$form->error($model,'id_number');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Phone Number</label>
					<?=$form->textField($model,'phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'0712335678','required'=>'required')); ?>
					<?=$form->error($model,'phone');?>
			</div>
		</div>
	</div>
	<br>
<?php if($model->isNewRecord):?>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
        	<label>Date of Birth</label>
					<?=$form->textField($model,'dateOfBirth',array('size'=>60,'maxlength'=>25,'class'=>'form-control','placeholder'=>'DoB','required'=>'required','id'=>'datepicker')); ?>
					<?=$form->error($model,'dateOfBirth');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          <label>Gender</label>
					<?=$form->dropDownList($model,'gender',array('male'=>'Male','female'=>'Female'),array('prompt'=>'-- GENDER --','class'=>'selectpicker')); ?>
		      <?=$form->error($model,'gender'); ?>
		  </div>
		</div>
	</div>
	<br>
<?php endif;?>

	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Email Address</label>
					<?=$form->textField($model,'email',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Email Address','required'=>'required')); ?>
					<?=$form->error($model,'email');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Staff Branch</label>
					<?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'Select Branch','class'=>'selectpicker','required'=>'required')); ?>
					<?=$form->error($model,'branch_id');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Sales Target</label>
					<?=$form->textField($model,'sales_target',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Staff Sales Target','required'=>'required')); ?>
					<?=$form->error($model,'sales_target');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Collections Target</label>
					<?=$form->textField($model,'collections_target',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Staff Collections Target','required'=>'required')); ?>
					<?=$form->error($model,'collections_target');?>
			</div>
		</div>
	</div>
	<br>

	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Staff Salary</label>
					<?=$form->textField($model,'salary',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Staff Salary','required'=>'required')); ?>
					<?=$form->error($model,'salary');?>
			</div>
		</div>
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Sales Bonus %</label>
					<?=$form->textField($model,'bonus',array('size'=>6,'maxlength'=>5,'class'=>'form-control','placeholder'=>'Collection Percent Bonus','required'=>'required')); ?>
					<?=$form->error($model,'bonus');?>
			</div>
		</div>
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Collection Bonus %</label>
					<?=$form->textField($model,'commission',array('size'=>6,'maxlength'=>5,'class'=>'form-control','placeholder'=>'Collection Percent Commission','required'=>'required')); ?>
					<?=$form->error($model,'commission');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Profit Bonus %</label>
					<?=$form->textField($model,'profit',array('size'=>6,'maxlength'=>5,'class'=>'form-control','placeholder'=>'Staff Percent Profit','required'=>'required')); ?>
					<?=$form->error($model,'profit');?>
			</div>
		</div>
	</div>
	<br>

	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >Supervisorial Role?</label>
					<?=$form->dropDownList($model,'is_supervisor',array('0'=>'No','1'=>'Yes'),array('size'=>6,'maxlength'=>5,'class'=>'form-control selectpicker','prompt'=>'-- SUPERVISORIAL ROLE --','required'=>'required')); ?>
					<?=$form->error($model,'is_supervisor');?>
			</div>
		</div>
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >List on Payroll</label>
					<?=$form->dropDownList($model,'payroll_listed',array('0'=>'No','1'=>'Yes'),array('size'=>6,'maxlength'=>5,'class'=>'form-control selectpicker','prompt'=>'-- LIST ON PAYROLL --','required'=>'required')); ?>
					<?=$form->error($model,'payroll_listed');?>
			</div>
		</div>
		<?php if(!($model->isNewRecord)):?>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
    		<label >List on Comments Dashboard</label>
					<?=$form->dropDownList($model,'commentsDashboard_listed',array('0'=>'No','1'=>'Yes'),array('size'=>6,'maxlength'=>5,'class'=>'form-control selectpicker','prompt'=>'-- LIST ON COMMENTS DASHBOARD --','required'=>'required')); ?>
					<?=$form->error($model,'commentsDashboard_listed');?>
			</div>
		</div>
	    <div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	    		<label >Automated Payroll Processing</label>
						<?=$form->dropDownList($model,'payroll_auto_process',array('1'=>'ENABLED','0'=>'DISABLED'),array('size'=>6,'maxlength'=>5,'class'=>'form-control selectpicker','prompt'=>'-- Automated Salary Processing --','required'=>'required')); ?>
						<?=$form->error($model,'payroll_auto_process');?>
				</div>
			</div>
		<?php endif;?>
	</div>
	<br>
  <div class="row">
    <div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
      	<a href="<?=Yii::app()->createUrl('staff/admin');?>" class="btn btn-info"><i class="fa fa-arrow-left"> Previous</i></a>
      </div>
    </div>
  	<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Staff Member':'Update Staff Member',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
<br/>