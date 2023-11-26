<?php
/* @var $this ProfilesController */
/* @var $model Profiles */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?><br>
	<div class="row">
	    <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'branchId',$model->getProfileBranchList(),array('prompt'=>'-- USER BRANCH --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'managerId',$model->getProfileManagersList(),array('prompt'=>'-- MANAGERS --','class'=>'selectpicker')); ?>
        </div>
      </div>
			<div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'id',$model->getProfilesList(),array('prompt'=>'-- USER ACCOUNT --','class'=>'selectpicker'));?>
        </div>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'profileType',array('MEMBER'=>'MEMBER','STAFF'=>'STAFF','SUPPLIER'=>'SUPPLIER'),
			array('prompt'=>'-- PROFILE TYPE --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'profileStatus',array('ACTIVE'=>'ACTIVE','SUSPENDED'=>'SUSPENDED','DORMANT'=>'DORMANT','LOCKED'=>'LOCKED'),array('prompt'=>'-- PROFILE STATUS --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'gender',array('MALE'=>'MALE','FEMALE'=>'FEMALE','OTHER'=>'OTHER'),
		        array('prompt'=>'-- GENDER --','class'=>'selectpicker')); ?>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
          <?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date'));?>
        </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
			    <?=CHtml::submitButton('Search Users',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
        </div>
      </div>
	</div>
	<?php $this->endWidget(); ?>
	</div>
</div><!-- search-form -->