<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?><br>
	<div class="row">
	    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- USER BRANCH --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'rm',$model->getRelationManagersList(),array('prompt'=>'-- MANAGERS --','class'=>'selectpicker')); ?>
        </div>
      </div>
			<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'user_id',$model->getUsersList(),array('prompt'=>'-- USER ACCOUNT --','class'=>'selectpicker'));?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'level',array('0'=>'Super Admin','1'=>'Admin','2'=>'Staff','3'=>'Member','4'=>'Share Holder','5'=>'Supplier','6'=>'Group/Chama'),array('prompt'=>'-- AUTHORIZATION --','class'=>'selectpicker')); ?>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'is_active',array('0'=>'Inactive','1'=>'Active'),array('prompt'=>'-- ACCOUNT STATUS --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
          </div>
       </div>
       <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
          <?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date'));?>
        </div>
       </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
			     <?=CHtml::submitButton('Search User',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
        </div>
      </div>
	</div>
	<?php $this->endWidget(); ?>
	</div>
</div><!-- search-form -->