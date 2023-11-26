<?php
/* @var $this AirtimeController */
/* @var $model Airtime */
/* @var $form CActiveForm */
?>
<div class="form col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'airtime-form',
		'enableAjaxValidation'=>false,
	));?>
	<?=$form->errorSummary($model); ?>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
          <label >Select Staff</label>
					<?=$form->dropDownList($model,'user_id',$model->getStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker')); ?>
					<?=$form->error($model,'user_id'); ?>
				</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        <label >Airtime Amount</label>
        <?=$form->textField($model,'amount',array('size'=>15,'maxlength'=>15,'placeholder'=>'Amount','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'amount'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        <label >Airtime Reason</label>
					<?=$form->textArea($model,'reason',array('placeholder'=>'Please provide brief comment....','class'=>' form-control','cols'=>5,'rows'=>2,'required'=>'required')); ?>
				<?=$form->error($model,'reason'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('airtime/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
        </div>
      </div>
	  <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Transaction':'Update Transaction',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div>
	<br>
<?php $this->endWidget(); ?>
</div><!-- form -->