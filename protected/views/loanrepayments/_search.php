<?php
/* @var $this LoanrepaymentsController */
/* @var $model Loanrepayments */
/* @var $form CActiveForm */
?>
	<div class="col-sm-12 col-md-12 col-lg-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?><br>
	<div class="row">
          <div class="col-md-2 col-lg-2 col-sm-12">
            <div class="form-group">
              <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
            </div>
          </div>
           <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'repaid_by',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker form-control-changed')); ?>
              </div>
          </div>
           <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'loanaccount_id',$model->getLoanAcountNumbersList(),array('prompt'=>'-- LOAN ACCOUNTS --','class'=>'selectpicker form-control-changed')); ?>
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
              <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;'));?>
            </div>
          </div>
        </div>
        <br>
	<?php $this->endWidget(); ?>
	</div><!-- search-form -->