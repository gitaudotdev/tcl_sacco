<?php
/* @var $this CollateralController */
/* @var $model Collateral */
/* @var $form CActiveForm */
?>
  <div class="row col-md-12 col-lg-12 col-sm-12">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?>
  <br>
	<div class="row">
      <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          <?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          <?=$form->dropDownList($model,'user_id',$model->getStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<?=$form->dropDownList($model,'collateraltype_id',$model->getCollateralTypeList(),array('class'=>'selectpicker','prompt'=>'-- COLLATERAL TYPES --')); ?>
          </div>
       </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
              <?=$form->dropDownList($model,'status',array('0'=>'Deposited Into Branch','1'=>'Collateral With Member','2'=>'Returned To Member','3'=>'Repossesion Initiated','4'=>'Repossesed','5'=>'Under Auction','6'=>'Sold','7'=>'Lost'),array('prompt'=>'-- COLLATERAL STATUS --','class'=>'selectpicker')); ?>
          </div>
        </div>
      </div>
      <br>
    <div class="row">
       <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'serial_number',array('class'=>'form-control','placeholder'=>'Serial Number')); ?>
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
			<?=CHtml::submitButton('Search Collateral',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
          </div>
      </div>
  </div>
	<?php $this->endWidget(); ?>
  </div>
</div><!-- search-form -->
  <div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>