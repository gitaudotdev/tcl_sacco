<?php
/* @var $this OrganizationController */
/* @var $model Organization */
/* @var $form CActiveForm */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'organization-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<label >Name</label>
					<?=$form->textField($model,'name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Organization Name','required'=>'required')); ?>
					<?=$form->error($model,'name');?>
			</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<label >Email Address</label>
					<?=$form->textField($model,'email',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Email Address','required'=>'required')); ?>
					<?=$form->error($model,'email');?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
			<div class="form-group">
				<label >Phone Number</label>
						<?=$form->textField($model,'phone',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'0712345678','required'=>'required')); ?>
						<?=$form->error($model,'phone');?>
				</div>
			</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
			<div class="form-group">
				<label >Physical Address</label>
						<?=$form->textField($model,'address',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'P.O. BOX','required'=>'required')); ?>
						<?=$form->error($model,'address');?>
				</div>
		</div>
	</div>
	<br>
	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<label >Website</label>
					<?=$form->textField($model,'website',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Organization Website','required'=>'required')); ?>
					<?=$form->error($model,'website');?>
			</div>
		</div>
		<?php if(Navigation::checkIfAuthorized(202) === 1):?>
				<div class="col-md-3 col-lg-3 col-sm-12">
					<div class="form-group">
						<label >M-PESA Status</label>
						<?=$form->dropDownList($model,'enable_mpesa_b2c',array('ENABLED'=>'ENABLED','DISABLED'=>'DISABLED'),array('prompt'=>'-- ENABLE/DISABLE M-PESA B2C --','class'=>'selectpicker')); ?>
						<?=$form->error($model,'enable_mpesa_b2c'); ?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
			<div class="form-group">
				<label >Automated Payroll Status</label>
				<?=$form->dropDownList($model,'automated_payroll',array('enabled'=>'ENABLED','disabled'=>'DISABLED'),array('prompt'=>'-- ENABLE/DISABLE AUTOMATED PAYROLL --','class'=>'selectpicker')); ?>
						<?=$form->error($model,'automated_payroll'); ?>
				</div>
			</div>
		<?php endif;?>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
			<a href="<?=Yii::app()->createUrl('organization/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
		</div>
		 <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Records':'Update Records',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div>
  <br><br>
<?php $this->endWidget(); ?>

</div><!-- form -->