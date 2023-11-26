<?php
/* @var $this SavingaccountsController */
/* @var $model Savingaccounts */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'savingaccounts-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      	<div class="form-group">
        	<label>Account Holder</label>
        	<?php if($model->isNewRecord):?>
        		<?=$form->dropDownList($model,'user_id',$model->getUsersList(),array('prompt'=>'--SELECT USER--','class'=>'selectpicker','required'=>'required','id'=>'userID')); ?>
        	<?php else:?>
        		<?=$form->dropDownList($model,'user_id',$model->getUsersList(),array('class'=>'selectpicker','disabled'=>true,'readonly'=>true)); ?>
        	<?php endif;?>
			<?=$form->error($model,'user_id');?>
		</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
      	<div class="form-group">
        	<label>Account Type</label>
				<?=$form->dropDownList($model,'type',array('open'=>'Open','fixed'=>'Fixed'),array('class'=>'selectpicker')); ?>
				<?=$form->error($model,'type');?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<label>Opening Balance</label>
        	<?=$form->textField($model,'opening_balance',array('class'=>'form-control','required'=>'required')); ?>
			    <?=$form->error($model,'opening_balance');?>
			</div>
		</div>
	</div>
	<?php if(!$model->isNewRecord):?>
		<div class="row">
			<div class="col-md-6 col-lg-6 col-sm-12">
	        <div class="form-group">
	        	<label>Interest Rate</label>
	        	<?=$form->textField($model,'interest_rate',array('class'=>'form-control','required'=>'required')); ?>
				    <?=$form->error($model,'interest_rate');?>
				</div>
			</div>
		</div>
	<?php endif;?>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
			<label>Fixed Saving Period(Months)</label>
			<?=$form->textField($model,'fixed_period',array('class'=>'form-control','required'=>'required')); ?>
			<?=$form->error($model,'fixed_period');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
	   <div class="col-md-3 col-lg-3 col-sm-12">
	   	<div class="form-group">
	   		<a href="<?=Yii::app()->createUrl('savingaccounts/admin');?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Previous</a>
	   	</div>
	   </div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Account':'Update Account',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div>
	<br><br>
	<?php $this->endWidget(); ?>
</div><!-- form -->
<script type="text/javascript">
$(function(){
  $('#userID').on('change', function() {
     LoadPhoneNumber(this.value);
  });
});

function LoadPhoneNumber(userID){
	('#accountNumber').val('');
	$.ajax({
    type:"POST",
    url: "<?=Yii::app()->createUrl('loanaccounts/loadPhoneNumbers');?>",
    data: {'userID':userID},
    success: function(response) {
    	if(response === 'NOT FOUND'){
				$("#accountNumber").prop('disabled', false);
    	}else{
    		var phoneNumber='0'+response;
    		$('#accountNumber').val(phoneNumber);
    	}
    }
  });
}
</script>