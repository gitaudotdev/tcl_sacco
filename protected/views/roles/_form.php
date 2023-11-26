<?php
/* @var $this RolesController */
/* @var $model Roles */
/* @var $form CActiveForm */
?>
<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'roles-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
    	<div class="col-md-4 col-lg-4 col-sm-12">
       	    <div class="form-group">
        	    <label>Role Name</label>
				<?=$form->textField($model,'name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Role Name','required'=>'required')); ?>
				<?=$form->error($model,'name');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('roles/admin');?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Previous</a>
        </div>
      </div>
	   <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Role':'Update Role',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div><br>
<?php $this->endWidget(); ?>
</div>