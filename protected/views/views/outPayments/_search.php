<?php
/* @var $this OutPaymentsController */
/* @var $model OutPayments */
/* @var $form CActiveForm */
?>
<div class="col-md-12 col-lg-12 col-sm-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); ?>
			<div class="row">
          <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'branch_id',$model->getOutPaymentBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
              </div>
          </div>
          <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <?=$form->dropDownList($model,'rm',$model->getEligibleRelationManagerList(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
                </div>
          </div>
           <?php if(Yii::app()->user->user_level !== '3'):?>
             <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                	<?=$form->dropDownList($model,'user_id',$model->getEligibleSupplierList(),array('prompt'=>'-- SUPPLIERS --','class'=>'selectpicker')); ?>
                </div>
             </div>
           <?php endif;?>
          <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'status',array('0'=>'INITIATED','1'=>'APPROVED','2'=>'DISBURSED','3'=>'REJECTED','4'=>'CANCELLED'),array('prompt'=>'-- PAYMENT STATUS --','class'=>'selectpicker'));?>
              </div>
           </div>
         </div>
         <br>
         <div class="row">
          <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'initiated_by',$model->getEligibleRelationManagerList(),array('prompt'=>'-- PAYMENT INITIATOR --','class'=>'selectpicker'));?>
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
				        <?=CHtml::submitButton('Search Payments',array('class'=>'btn btn-primary pull-right','style'=>'margin-top:0% !important;')); ?>
	            </div>
	        </div>
    </div><br>
	<?php $this->endWidget(); ?>
</div>
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>
<!-- search-form -->