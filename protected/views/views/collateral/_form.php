<?php
/* @var $this CollateralController */
/* @var $model Collateral */
/* @var $form CActiveForm */
?>
<div class="form  col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'collateral-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<br><br>
	<?=$form->errorSummary($model); ?>
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
	        	<?=$form->dropDownList($model,'collateraltype_id',$model->getCollateralTypeList(),array('prompt'=>'-- COLLATERAL TYPES --','class'=>'selectpicker','required'=>'required')); ?>
				</div>
			</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
        	<?=$form->dropDownList($model,'loanaccount_id',$model->getLoanAcountNumbersList(),array('prompt'=>'-- LOAN ACCOUNTS --','class'=>'selectpicker','required'=>'required')); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
					<?=$form->textField($model,'name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Collateral Name','required'=>'required')); ?>
					<?=$form->error($model,'name');?>
				</div>
			</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
       		<div class="form-group">
				<?=$form->textField($model,'model',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Model','required'=>'required')); ?>
				<?=$form->error($model,'model');?>
			</div>
		</div>
    <div class="col-md-3 col-lg-3 col-sm-12">
       		<div class="form-group">
					<?=$form->textField($model,'serial_number',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Serial Number','required'=>'required')); ?>
					<?=$form->error($model,'serial_number');?>
			</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
       		<div class="form-group">
					<?=$form->textField($model,'market_value',array('size'=>15,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Market Value','required'=>'required')); ?>
					<?=$form->error($model,'market_value');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
        	<?=$form->dropDownList($model,'status',array('0'=>'Deposited Into Branch','1'=>'Collateral With Member','2'=>'Returned To Member','3'=>'Repossesion Initiated','4'=>'Repossesed','5'=>'Under Auction','6'=>'Sold','7'=>'Lost'),array('prompt'=>'-- COLLATERAL STATUS --','class'=>'selectpicker','required'=>'required')); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Collateral':'Update Collateral',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('collateral/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
        </div>
      </div>
	</div>
	<br><br>
	<?php $this->endWidget(); ?>
</div><!-- form -->