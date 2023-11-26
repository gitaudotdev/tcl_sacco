<?php
/* @var $this AssetsController */
/* @var $model Assets */
/* @var $form CActiveForm */
?>
<div class="col-md-12 col-lg-12 col-sm-12">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	));?><br>
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
          	<?=$form->dropDownList($model,'asset_type_id',$model->getAssetTypeList(),array('prompt'=>'-- ASSET TYPES --','class'=>'selectpicker'));?>
          </div>
       </div>
       <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<?=$form->textField($model,'serial_number',array('class'=>'form-control','placeholder'=>'Serial Number')); ?>
          </div>
       </div>
     </div>
     <br>
     <div class="row">
       <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<?=$form->dropDownList($model,'status',array('broken'=>'Broken','donated'=>'Donated','for service'=>'For Service','for auction'=>'For Auction','in use'=>'In Use','replaced'=>'Replaced','sold'=>'Sold'),array('prompt'=>'-- ASSET STATUS --','class'=>'selectpicker')); ?>
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
       <div class="col-md-1 col-lg-1 col-sm-12">
            <div class="form-group">
			       <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
            </div>
        </div>
        &emsp;&emsp;&emsp;
        <div class="col-md-1 col-lg-1 col-sm-12">
          <div class="form-group">
            <?=CHtml::submitButton('Download',array('class'=>'btn btn-warning','style'=>'margin-top:-2% !important;','name' =>'export','id'=>'export-btn')); ?>
          </div>
        </div>
    </div>
	<?php $this->endWidget(); ?>
  </div>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>