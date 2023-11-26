<?php
/* @var $this LoaninterestsController */
/* @var $model Loaninterests */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?>
	<div class="row">
		<div class="col-md-2 col-lg-2 col-sm-12">
			<div class="form-group">
			<?=$form->dropDownList($model,'branchId',$model->getBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
			<div class="form-group">
			<?=$form->dropDownList($model,'managerId',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
			</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
			<div class="form-group">
			<?=$form->dropDownList($model,'loanaccount_id',$model->getLoanAcountNumbersList(),array('prompt'=>'-- LOAN ACCOUNTS --','class'=>'selectpicker')); ?>
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
	        <?=CHtml::submitButton('Search Accounts',array('class'=>'btn btn-primary','style'=>'margin-top:0% !important;')); ?>
	      </div>
	  </div>
	</div>
	<?php $this->endWidget(); ?>
	</div><!-- search-form -->
</div>
<hr class="common_rule">