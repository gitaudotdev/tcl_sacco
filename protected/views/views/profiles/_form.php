<div class="col-md-12 col-lg-12 col-sm-12">
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'users-form',
		'enableAjaxValidation'=>false,
	));?>
	<br>
	<?=$form->errorSummary($model); ?>
		<div class="row">
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>Branch</label>
						<?=$form->dropDownList($model,'branchId',$model->getProfileBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
						<?=$form->error($model,'branchId'); ?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>Relation Manager</label>
					<?=$form->dropDownList($model,'managerId',$model->getProfileManagersList(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
					<?=$form->error($model,'managerId'); ?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>Profile Type</label>
					<?=$form->dropDownList($model,'profileType',array('MEMBER'=>'MEMBER','STAFF'=>'STAFF MEMBER','SUPPLIER'=>'SUPPLIER'),
					array('prompt'=>'-- PROFILE --','class'=>'selectpicker')); ?>
					<?=$form->error($model,'profileType'); ?>
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>First Name</label>
					<?=$form->textField($model,'firstName',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'First Name','required'=>'required')); ?>
					<?=$form->error($model,'firstName');?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>Last Name</label>
					<?=$form->textField($model,'lastName',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Last Name','required'=>'required')); ?>
					<?=$form->error($model,'lastName');?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>Gender</label>
					<?=$form->dropDownList($model,'gender',array('MALE'=>'MALE','FEMALE'=>'FEMALE'),array('prompt'=>'-- GENDER --','class'=>'selectpicker')); ?>
					<?=$form->error($model,'gender'); ?>
				</div>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>ID Number</label>
					<?=$form->textField($model,'idNumber',array('size'=>60,'maxlength'=>25,'class'=>'form-control','placeholder'=>'ID Number','required'=>'required')); ?>
					<?=$form->error($model,'idNumber');?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>KRA PIN</label>
					<?=$form->textField($model,'kraPIN',array('size'=>60,'maxlength'=>25,'class'=>'form-control','placeholder'=>'KRA PIN'));?>
					<?=$form->error($model,'kraPIN');?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
				<div class="form-group">
					<label>Date of Birth</label>
					<?=$form->textField($model,'birthDate',array('size'=>60,'maxlength'=>25,'class'=>'form-control','placeholder'=>'DoB','required'=>'required','id'=>'datepicker')); ?>
					<?=$form->error($model,'birthDate');?>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
			<div class="form-group">
				<a href="<?=Yii::app()->createUrl('profiles/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12"></div>
		<div class="col-md-3 col-lg-3 col-sm-12">
			<div class="form-group">
				<?=CHtml::submitButton($model->isNewRecord ? 'Create Profile':'Update Profile',array('class'=>'btn btn-primary pull-right'));?>
			</div>
      </div>
	</div><br><br>
   <?php $this->endWidget(); ?>
</div><!-- form -->
</div>