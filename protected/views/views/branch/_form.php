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