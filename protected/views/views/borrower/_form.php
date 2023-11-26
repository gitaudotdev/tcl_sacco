<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'borrower-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<br>
	<?php if($model->isNewRecord):?>
	<div class="row">
        <div class="col-md-3 col-lg-3 col-sm-6 pr-1">
            <div class="form-group">
        		<label>First Name</label>
				<?=$form->textField($model,'first_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'First Name','required'=>'required')); ?>
				<?=$form->error($model,'first_name');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
        		<label>Last Name</label>
				<?=$form->textField($model,'last_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Last Name','required'=>'required')); ?>
				<?=$form->error($model,'last_name');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        	<div class="form-group">
        		<label>Date of Birth</label>
				<?=$form->textField($model,'birth_date',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Date of Birth','required'=>'required','id'=>'datepicker')); ?>
				<?=$form->error($model,'birth_date');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        	<div class="form-group">
        		<label>ID Number</label>
				<?=$form->textField($model,'id_number',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'ID Number','required'=>'required')); ?>
				<?=$form->error($model,'id_number');?>
			</div>
		</div>
	</div>
	<br>
<?php endif;?>
	<div class="row">
		<?php if($model->isNewRecord):?>
	    	<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        	<div class="form-group">
	        		<label>Gender</label>
					<?=$form->dropDownList($model,'gender',array('male'=>'Male','female'=>'Female'),array('prompt'=>'-- GENDER --','class'=>'selectpicker','required'=>'required')); ?>
				    <?=$form->error($model,'gender');?>
				</div>
			</div>
		<?php endif;?>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        	<div class="form-group">
        		<label>Branch</label>
				<?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'branch_id');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        <div class="form-group">
        		<label>Phone Number</label>
					<?=$form->textField($model,'phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'0712345678','required'=>'required')); ?>
					<?=$form->error($model,'phone');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        <div class="form-group">
        		<label>Email Address</label>
					<?=$form->textField($model,'email',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Personal Email Address','required'=>'required')); ?>
					<?=$form->error($model,'email');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        	<div class="form-group">
        		<label>Postal Address</label>
        	<?=$form->textField($model,'address',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'P.O. BOX','required'=>'required')); ?>
					<?=$form->error($model,'address');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
        		<label>Member Segment</label>
	        	<?=$form->dropDownList($model,'segment',array('0'=>'Small','1'=>'Premier','2'=>'Corporate'),array('prompt'=>'--MEMBER SEGMENT--','class'=>'selectpicker','required'=>'required')); ?>
				    <?=$form->error($model,'segment');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
        		<label>Working Status</label>
	        	<?=$form->dropDownList($model,'working_status',array('0'=>'Employee','1'=>'Owner','2'=>'Student','3'=>'Overseas Worker'),array('prompt'=>'-- EMPLOYMENT STATUS --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'working_status');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        <div class="form-group">
        		<label>Job Title</label>
				<?=$form->textField($model,'job_title',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Job Title')); ?>
				<?=$form->error($model,'job_title');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
        		<label>Employer</label>
	        	<?=$form->textField($model,'employer',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Employer/Business Name','required'=>'required')); ?>
					<?=$form->error($model,'employer');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
        		<label>Industry Type</label>
	        	<?=$form->dropDownList($model,'industry_type',array('001'=>'Agriculture','002'=>'Manufacturing','003'=>'Building/ Construction','004'=>'Mining/ Quarrying','005'=>'Energy/ Water','006'=>'Trade','007'=>'Tourism/ Restaurant/ Hotels','008'=>'Transport/ Communications','009'=>'Real Estate','010'=>'Financial Services','011'=>'Government'),array('prompt'=>'-- INDUSTRY TYPE --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'industry_type');?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-6 pr-1">
        <div class="form-group">
      		<label>Employment Date</label>
        	<?=$form->textField($model,'date_employed',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Date Joined/ Employed','required'=>'required','id'=>'normaldatepicker')); ?>
					<?=$form->error($model,'date_employed');?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-6 pr-1">
        <div class="form-group">
        <label>Income Amount</label>
				<?=$form->textField($model,'salary_band',array('size'=>60,'maxlength'=>512,'class'=>'form-control')); ?>
				<?=$form->error($model,'salary_band');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        <div class="form-group">
        <label>Job Email</label>
				<?=$form->textField($model,'job_email',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Job Email')); ?>
				<?=$form->error($model,'job_email');?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-6 pr-1">
        <div class="form-group">
        		<label>Office Phone Number</label>
				<?=$form->textField($model,'office_phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Office Phone')); ?>
				<?=$form->error($model,'office_phone');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
        		<label>Work Place and Location</label>
	        	<?=$form->textField($model,'office_location',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Office Location')); ?>
					<?=$form->error($model,'office_location');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
        		<label>Residence/Home</label>
	        	<?=$form->textField($model,'office_land_mark',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Office Land Mark')); ?>
					<?=$form->error($model,'office_land_mark');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        <div class="form-group">
        		<label>Referee</label>
				<?=$form->textField($model,'referred_by',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Referred By')); ?>
				<?=$form->error($model,'referred_by');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        <div class="form-group">
        		<label>Referee Phone Number</label>
				<?=$form->textField($model,'referee_phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Referee Phone')); ?>
				<?=$form->error($model,'referee_phone');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
        		<label>Alternate Phone Number</label>
	        	<?=$form->textField($model,'alternative_phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Alternative Phone Number')); ?>
					<?=$form->error($model,'alternative_phone');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
        	<div class="form-group">
        		<label>Relation Manager</label>
				<?=$form->dropDownList($model,'rm',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker','required'=>'required')); ?>
				<?=$form->error($model,'rm');?>
			</div>
		</div>
		<?php if($model->isNewRecord):?>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($kin,'first_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Kin First Name')); ?>
					<?=$form->error($kin,'first_name');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($kin,'last_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Kin Last Name')); ?>
					<?=$form->error($kin,'last_name');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($kin,'birth_date',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Kin Date of Birth','id'=>'start_date')); ?>
					<?=$form->error($kin,'birth_date');?>
			</div>
		</div>
	</div>
	<br>
	<?php endif;?>
	<?php if($model->isNewRecord):?>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($kin,'phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Kin Phone Number')); ?>
					<?=$form->error($kin,'phone');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($kin,'relation',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Relationship to Kin')); ?>
					<?=$form->error($kin,'relation');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($referee,'first_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Referee First Name')); ?>
					<?=$form->error($referee,'first_name');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($referee,'last_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Referee Last Name')); ?>
					<?=$form->error($referee,'last_name');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($referee,'employer',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Referee Employer')); ?>
					<?=$form->error($referee,'employer');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($referee,'relation',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Relationship to Referee')); ?>
					<?=$form->error($referee,'relation');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
	        <div class="form-group">
	        	<?=$form->textField($referee,'phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Referee Phone Number')); ?>
					<?=$form->error($referee,'phone');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
					<?=$form->label($files,'id_card');?>
	        <?=$form->fileField($files,'id_card');?>
					<?=$form->error($files,'id_card');?>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
					<?=$form->label($files,'passport');?>
	        <?=$form->fileField($files,'passport'); ?>
					<?=$form->error($files,'passport');?>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
					<?=$form->label($files,'business');?>
	        <?=$form->fileField($files,'business'); ?>
					<?=$form->error($files,'business');?>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-6 pr-1">
					<?=$form->label($files,'residence_landmark');?>
	        <?=$form->fileField($files,'residence_landmark'); ?>
					<?=$form->error($files,'residence_landmark');?>
		</div>
	</div>
  <?php endif;?>
	<div class="row col-md-12 col-lg-12 col-sm-12" style="margin:2% 0% 2.5% -2% !important;">
		 <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ?'Create Member':'Update Member',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('borrower/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
        </div>
      </div>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->