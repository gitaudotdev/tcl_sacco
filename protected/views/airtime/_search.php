<?php
/* @var $this AirtimeController */
/* @var $model Airtime */
/* @var $form CActiveForm */
?>
<div class="col-md-12 col-lg-12 col-sm-12">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?><br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
				<?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- SELECT BRANCH--','class'=>'selectpicker')); ?>
			</div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
				<?=$form->dropDownList($model,'user_id',$model->getStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker')); ?>
			</div>
		</div>
	<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
				<?=$form->dropDownList($model,'rm',$model->getRelationManagersList(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
			</div>
		</div>
	<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
      	<?=$form->dropDownList($model,'status',array('0'=>'Initiated','1'=>'Approved','2'=>'Disbursed','3'=>'Rejected'),array('prompt'=>'-- REQUEST STATUS --','class'=>'selectpicker')); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
				<?=$form->dropDownList($model,'authorized_by',$model->getRelationManagersList(),array('prompt'=>'-- AUTHORIZED BY --','class'=>'selectpicker')); ?>
			</div>
		</div>

		<div class="col-md-3 col-lg-3 col-sm-12">
	      <div class="form-group">
	        <?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
	      </div>
	   </div>
	   <div class="col-md-3 col-lg-3 col-sm-12">
	      <div class="form-group">
	        <?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date')); ?>
	      </div>
	   </div>

	   <div class="col-md-3 col-lg-3 col-sm-12">
	      <div class="form-group">
	       <?=CHtml::submitButton('Search Transactions',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
	      </div>
	  </div>
	</div>
<?php $this->endWidget(); ?>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>