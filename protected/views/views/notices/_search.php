<?php
/* @var $this NoticesController */
/* @var $model Notices */
/* @var $form CActiveForm */
?>
<div class="form">
  <div class="col-md-12 col-lg-12 col-sm-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?><br>
	<div class="row">
		<div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
				<?=$form->textField($model,'message',array('size'=>60,'maxlength'=>1200,'class'=>'form-control','placeholder'=>'Notice Message')); ?>
			</div>
		</div>

		<div class="col-md-2 col-lg-2 col-sm-12">
      <div class="form-group">
				<?=$form->dropDownList($model,'level',array('0'=>'All','1'=>'Superadmin','2'=>'Admin','3'=>'Staff','4'=>'Member','5'=>'Shareholder','6'=>'Regional'),array('prompt'=>'-- NOTICE LEVEL --','class'=>'selectpicker')); ?>
			</div>
		</div>

		<div class="col-md-2 col-lg-2 col-sm-12">
        	<div class="form-group">
				<?=$form->dropDownList($model,'is_active',array('0'=>'Disabled','1'=>'Active'),array('prompt'=>'-- NOTICE STATUS --','class'=>'selectpicker')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
	    	<div class="form-group">
			<?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
			<div class="form-group">
			<?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12 pull-right">
			<div class="form-group">
				<?=CHtml::submitButton('Search Notices',array('class'=>'btn btn-primary','style'=>'margin-top:0% !important;')); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
	</div>
<?php $this->endWidget(); ?>
</div>
</div><!-- search-form -->
  <div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>