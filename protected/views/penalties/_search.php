<?php
/* @var $this PenaltiesController */
/* @var $model Penaltyaccrued */
/* @var $form CActiveForm */
?>
<div class="col-md-12 col-lg-12 col-sm-12">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?>
	<div class="row">
<!--      <div class="col-md-2 col-lg-2 col-sm-12">-->
<!--          <div class="form-group">-->
<!--            --><?php //=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
<!--          </div>-->
<!--      </div>-->
<!--      <div class="col-md-2 col-lg-2 col-sm-12">-->
<!--            <div class="form-group">-->
<!--              --><?php //=$form->dropDownList($model,'rm',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
<!--            </div>-->
<!--      </div>-->
<!--      <div class="col-md-2 col-lg-2 col-sm-12">-->
<!--        <div class="form-group">-->
<!--          --><?php //=$form->dropDownList($model,'user_id',$model->getBorrowerList(),array('prompt'=>'-- MEMBERS --',
//          'class'=>'selectpicker')); ?>
<!--        </div>-->
<!--      </div>-->
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
        <div class="col-md-1 col-lg-1 col-sm-12">
            <div class="form-group">
              <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
            </div>
        </div>
   </div>
	<?php $this->endWidget(); ?>
	</div>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>