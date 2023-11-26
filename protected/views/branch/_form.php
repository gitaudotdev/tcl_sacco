<?php
/* @var $this BranchController */
/* @var $model Branch */
/* @var $form CActiveForm */
?>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'branch-form',
        'enableAjaxValidation'=>false,
    )); ?>
    <?=$form->errorSummary($model); ?>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
                <label >Branch Name</label>
                <?=$form->textField($model,'name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Branch Name','required'=>'required')); ?>
                <?=$form->error($model,'name');?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
                <label >Branch Town</label>
                <?=$form->textField($model,'branch_town',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Branch Town','required'=>'required')); ?>
                <?=$form->error($model,'branch_town');?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
                <label >Branch Sales Target</label>
                <?=$form->textField($model,'sales_target',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Branch Sales Target','required'=>'required')); ?>
                <?=$form->error($model,'sales_target');?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
                <label >Branch Collections Target</label>
                <?=$form->textField($model,'collections_target',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Branch Collections Target','required'=>'required')); ?>
                <?=$form->error($model,'collections_target');?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <div class="form-group">
                <label >Member Loan Limit</label>
                <?=$form->textField($model,'loan_limit',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Loan Limit','required'=>'required')); ?>
                <?=$form->error($model,'loan_limit');?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Insurance Rate</label>
                <?=$form->textField($model,'insurance_rate',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Insurance Rate','required'=>'required')); ?>
                <?=$form->error($model,'insurance_rate');?>
            </div>
        </div>

        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Loan Interest rate</label>
                <?=$form->textField($model,'interest_rate',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Loan Interest Rate','required'=>'required')); ?>
                <?=$form->error($model,'loan_interest_rate');?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Savings Interest Rate</label>
                <?=$form->textField($model,'savings_interest_rate',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Savings Interest Rate','required'=>'required')); ?>
                <?=$form->error($model,'savings_interest_rate');?>
            </div>
        </div>

        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Processing Fee</label>
                <?=$form->textField($model,'processing_fee',array('size'=>60,'maxlength'=>15,'class'=>'form-control','placeholder'=>'Processing Fee','required'=>'required')); ?>
                <?=$form->error($model,'processing_fee');?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <a href="<?=Yii::app()->createUrl('branch/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <?=CHtml::submitButton($model->isNewRecord ? 'Create Branch':'Update Branch',array('class'=>'btn btn-primary pull-right'));?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div><br><br>