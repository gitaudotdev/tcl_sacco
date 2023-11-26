<?php
/* @var $this LoanrepaymentsController */
/* @var $model Loanrepayments */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'loanrepayments-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
      	<div class="form-group">
      		<label >Select Branch</label>
	        <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker','required'=>'required')); ?>
				</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
      		<label >Select Relation Manager</label>
		        <?=$form->dropDownList($model,'rm',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGER --','class'=>'selectpicker','required'=>'required')); ?>
					</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
      		<label >Select Account</label>
		        <?=$form->dropDownList($model,'loanaccount_id',$model->getLoanAcountNumbersList(),array('prompt'=>'-- LOAN ACCOUNTS --','class'=>'selectpicker','required'=>'required')); ?>
					</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
      		<label >Repayment Date</label>
				<?=$form->textField($model,'date',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Date of Transaction','required'=>'required','id'=>'normaldatepicker')); ?>
				<?=$form->error($model,'date');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-3 col-lg-3 col-sm-12">
       		<div class="form-group">
      		<label >Principal Paid</label>
					<?=$form->textField($model,'principal_paid',array('size'=>15,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Principal Paid','required'=>'required')); ?>
					<?=$form->error($model,'principal_paid');?>
			</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
       		<div class="form-group">
      		<label >Interest Paid</label>
					<?=$form->textField($model,'interest_paid',array('size'=>15,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Interest Paid','required'=>'required')); ?>
					<?=$form->error($model,'interest_paid');?>
			</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
       		<div class="form-group">
      		<label >Fee Paid</label>
					<?=$form->textField($model,'fee_paid',array('size'=>15,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Fee Paid','required'=>'required')); ?>
					<?=$form->error($model,'fee_paid');?>
			</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
       		<div class="form-group">
      		<label >Penalty Paid</label>
					<?=$form->textField($model,'penalty_paid',array('size'=>15,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Penalty Paid','required'=>'required')); ?>
					<?=$form->error($model,'penalty_paid');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		 <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Submit Repayment':'Update Repayment',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
     <div class="col-md-6 col-lg-6 col-sm-12">
      <div class="form-group">
      	<a href="<?=Yii::app()->createUrl('loanrepayments/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
      </div>
    </div>
	</div>
	<br>
	<?php $this->endWidget(); ?><!-- form -->