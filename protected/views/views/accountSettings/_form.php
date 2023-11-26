<?php
/* @var $this AccountSettingsController */
/* @var $model AccountSettings */
/* @var $form CActiveForm */
$booleans = array('EMAIL_ALERTS','SMS_ALERTS','FIXED_PAYMENT_LISTED','COMMENTS_DASHBOARD_LISTED','PAYROLL_LISTED','PAYROLL_AUTO_PROCESS','SUPERVISORIAL_ROLE');
?>
<div class="form col-md-12 col-lg-12 col-sm-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'account-settings-form',
	'enableAjaxValidation'=>false,
	));?>
	<?=$form->errorSummary($model);?>
	<br>
	<div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
			<div class="form-group">
				<label>Config Type</label>
        		<?=$form->textField($model,'configType',array('size'=>255,'maxlength'=>255,'class'=>'form-control','readonly'=>'readonly','disabled'=>'disabled')); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
			<div class="form-group">
				<label>Current Value</label>
        		<?=$form->textField($model,'configValue',array('size'=>255,'maxlength'=>255,'class'=>'form-control','readonly'=>'readonly','disabled'=>'disabled')); ?>
			</div>
		</div>
	</div>
	<br>
	<?php if(in_array($model->configType,$booleans)):?>
	<div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
        	<div class="form-group">
        		<label>Config Value</label>
				<?=$form->dropDownList($model,'configValue',array('ACTIVE'=>'ACTIVE','DISABLED'=>'DISABLED'),array('prompt'=>'-- CONFIG TYPE --','class'=>'selectpicker')); ?>
				<?=$form->error($model,'configValue'); ?>
			</div>
		</div>
	</div>
	<br>
	<?php else:?>
	<div class="row">
		<div class="col-md-4 col-lg-4 col-sm-12">
       		 <div class="form-group">
        		<label>Config Value</label>
        		<?=$form->textField($model,'configValue',array('size'=>25,'maxlength'=>25,'class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'configValue'); ?>
			</div>
		</div>
	</div>
	<br>
	<?php endif;?>
	<div class="row">
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('profiles/'.$model->profileId);?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Previous</a>
        </div>
      </div>
	  <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create':'Update',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div>
	<br>
<?php $this->endWidget(); ?>
</div><!-- form -->