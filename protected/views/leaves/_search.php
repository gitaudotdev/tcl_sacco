<?php
/* @var $this LeavesController */
/* @var $model Leaves */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>
		<br>
		<div class="row">
			<div class="col-md-3 col-lg-3 col-sm-12">
		    	<div class="form-group">
					<?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- SELECT BRANCH --','class'=>'selectpicker form-control')); ?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3 col-sm-12">
		    	<div class="form-group">
					<?=$form->dropDownList($model,'user_id',$model->getFullSaccoStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker form-control')); ?>
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
			<div class="col-md-2 col-lg-2 col-sm-12">
				<div class="form-group">
					<?=CHtml::submitButton('Search Records',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
				</div>
			</div>
   </div><br>
	<?php $this->endWidget(); ?>
	</div>
</div><!-- search-form -->
<hr class="common_rule">