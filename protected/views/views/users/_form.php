<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>
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
          	<label>First Name</label>
				<?=$form->textField($model,'first_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'First Name','required'=>'required')); ?>
				<?=$form->error($model,'first_name');?>
		  </div>
		</div>
    <div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
        	<label>Last Name</label>
				<?=$form->textField($model,'last_name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Last Name','required'=>'required')); ?>
				<?=$form->error($model,'last_name');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          <label>Gender</label>
					<?=$form->dropDownList($model,'gender',array('male'=>'Male','female'=>'Female'),array('prompt'=>'-- GENDER --','class'=>'selectpicker')); ?>
		      <?=$form->error($model,'gender'); ?>
		  </div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
        	<label>Date of Birth</label>
					<?=$form->textField($model,'dateOfBirth',array('size'=>60,'maxlength'=>25,'class'=>'form-control','placeholder'=>'DoB','required'=>'required','id'=>'datepicker')); ?>
					<?=$form->error($model,'dateOfBirth');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
        		<label>ID Number</label>
				<?=$form->textField($model,'id_number',array('size'=>60,'maxlength'=>25,'class'=>'form-control','placeholder'=>'ID Number','required'=>'required')); ?>
				<?=$form->error($model,'id_number');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
        		<label>KRA PIN</label>
				<?=$form->textField($model,'kra_pin',array('size'=>60,'maxlength'=>25,'class'=>'form-control','placeholder'=>'KRA PIN'));?>
				<?=$form->error($model,'kra_pin');?>
			</div>
		</div>
    <div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
        		<label>Phone Number</label>
				<?=$form->textField($model,'phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'712335678','required'=>'required')); ?>
				<?=$form->error($model,'phone');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<label>Email Address</label>
				<?=$form->textField($model,'email',array('size'=>60,'maxlength'=>255,'class'=>'form-control','placeholder'=>'Email Address','required'=>'required')); ?>
				<?=$form->error($model,'email');?>
		  </div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<label>Username</label>
				<?=$form->textField($model,'username',array('size'=>60,'maxlength'=>255,'class'=>'form-control','placeholder'=>'Username','required'=>'required')); ?>
				<?=$form->error($model,'username');?>
		  </div>
		</div>
		<?php if($model->isNewRecord):?>
	    	<div class="col-md-3 col-lg-3 col-sm-12">
	        	<div class="form-group">
	        	<label>Password</label>
						<?=$form->passwordField($model,'password',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Password','required'=>'required')); ?>
						<?=$form->error($model,'password');?>
				</div>
			</div>
		<?php endif;?>
		<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          <label>Authorization</label>
					<?=$form->dropDownList($model,'level',array('0'=>'Super Admin','1'=>'Admin','2'=>'Staff','3'=>'Member','4'=>'Share Holder','5'=>'Supplier','6'=>'Group/Chama'),array('prompt'=>'-- AUTH LEVEL --','class'=>'selectpicker')); ?>
		      <?=$form->error($model,'level'); ?>
		  </div>
		</div>
  	<div class="col-md-3 col-lg-3 col-sm-12">
      	<div class="form-group">
      		<label>User Branch</label>
				<?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
				<?=$form->error($model,'branch_id'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
      	<div class="form-group">
      		<label>Relation Manager</label>
				<?=$form->dropDownList($model,'rm',$model->getRelationManagersList(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
				<?=$form->error($model,'rm'); ?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	        	<label>Residence</label>
	        	<?=$form->textField($model,'residence',array('required'=>'required','maxlength'=>512,'class'=>'form-control','placeholder'=>'Residence')); ?>
						<?=$form->error($model,'residence');?>
				</div>
			</div>
		<?php if(!$model->isNewRecord):?>
			<div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	        	<label>Date Joined</label>
	        	<?=$form->textField($model,'created_at',array('required'=>'required','maxlength'=>15,'class'=>'form-control','id'=>'start_date','placeholder'=>'Date Created')); ?>
						<?=$form->error($model,'created_at');?>
				</div>
			</div>
		<?php endif;?>
		<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          <label>Receive SMS? </label>
					<?=$form->dropDownList($model,'sms_notifications',array('1'=>'YES','0'=>'NO'),array('prompt'=>'Receive SMS Notifications? ','class'=>'selectpicker')); ?>
		        <?=$form->error($model,'sms_notifications'); ?>
		  </div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	        	<label>Loans Interest</label>
	        	<?=$form->textField($model,'loans_interest',array('maxlength'=>5,'class'=>'form-control','placeholder'=>'Default Loans Interest')); ?>
						<?=$form->error($model,'loans_interest');?>
				 </div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	        	<label>Savings Interest</label>
	        	<?=$form->textField($model,'savings_interest',array('maxlength'=>5,'class'=>'form-control','placeholder'=>'Default Savings Interest')); ?>
						<?=$form->error($model,'savings_interest');?>
				 </div>
			</div>
		  <div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	        	<label>Payable Limit</label>
	        	<?=$form->textField($model,'maximum_limit',array('required'=>'required','maxlength'=>17,'class'=>'form-control','placeholder'=>'Maximum Payable Limit')); ?>
						<?=$form->error($model,'maximum_limit');?>
				 </div>
			</div>
		<?php if((!$model->isNewRecord) && ($model->level === '5')):?>
			<div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	        	<label>Fixed Payment Enlisted</label>
					<?=$form->dropDownList($model,'fixed_payment_enlisted',array('0'=>'NO','1'=>'YES'),array('prompt'=>'Enlist On Fixed Payment?','class'=>'selectpicker')); ?>
		        <?=$form->error($model,'fixed_payment_enlisted'); ?>
				</div>
			</div>
		<?php endif;?>
	</div>
	<br>
	<div class="row">
    <div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
      	<a href="<?=Yii::app()->createUrl('users/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
      </div>
    </div>
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create User':'Update User',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div><br><br>
   <?php $this->endWidget(); ?>
</div><!-- form -->
</div>