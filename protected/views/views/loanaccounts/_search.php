<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			));?><br>
			<div class="row">
          <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
              </div>
          </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <?=$form->dropDownList($model,'rm',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
                </div>
          </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
            <div class="form-group">
              <?=$form->dropDownList($model,'user_id',$model->getBorrowerList(),array('prompt'=>'-- MEMBERS --',
              'class'=>'selectpicker')); ?>
            </div>
          </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'loan_status',
                array('0'=>'Submitted','1'=>'Approved','2'=>'Disbursed','3'=>'Rejected','4'=>'Fully Paid',
                '5'=>'Restructured','6'=>'Topped Up','7'=>'Defaulted','8'=>'Forwarded','9'=>'Returned','10'=>'Resubmitted'),array('prompt'=>'-- PROCESS STATUS --','class'=>'selectpicker'));?>
              </div>
           </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'account_status',array('A'=>'Closed','B'=>'Dormant','C'=>'Write-Off','D'=>'Legal','E'=>'Collection','F'=>'Active','G'=>'Facility Rescheduled','H'=>'Settled','J'=>'Called Up','K'=>'Suspended','L'=>'Client Deceased','M'=>'Deferred','N'=>'Not Updated','P'=>'Disputed'),array('prompt'=>'-- ACCOUNT STATUS --','class'=>'selectpicker'));?>
              </div>
           </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
            <div class="form-group">
                <?=$form->dropDownList($model,'performance_level',array('A'=>'Normal( 0 - 30 days)','B'=>'Watch( 31 - 90 days)','C'=>'Substandard( 91 - 180 days)','D'=>'Doubtful/Recovery( 181 - 360 days)','E'=>'Loss/Recovery( Over 360 days)'),array('maxlength'=>15,'class'=>'form-control selectpicker','prompt'=>'-- RISK CLASSIFICATION--')); ?>
                <?=$form->error($model,'performance_level'); ?>
            </div>
          </div>
         </div>
         <br>
         <div class="row">
          <div class="col-md-2 col-lg-2 col-sm-12">
            <div class="form-group">
                <?=$form->dropDownList($model,'crb_status',array('a'=>'Performing','b'=>'Blacklisted'),array('maxlength'=>15,'class'=>'form-control selectpicker','prompt'=>'-- CRB STATUS--')); ?>
                <?=$form->error($model,'crb_status'); ?>
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
				        <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:0% !important;')); ?>
	            </div>
	        </div>
         </div><br>
	<?php $this->endWidget(); ?>
  </div>
</div>
<hr class="common_rule">
<!-- search-form -->