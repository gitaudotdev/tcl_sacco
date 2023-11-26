<?php
/* @var $this AssetTypeController */
/* @var $model AssetType */
/* @var $form CActiveForm */
?>
<div class="form col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'asset-type-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
        	<div class="form-group">
				<?=$form->textField($model,'name',array('size'=>15,'maxlength'=>75,'placeholder'=>'Asset Type Name','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'name'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Record':'Update Record',array('class'=>'btn btn-primary'));?>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('assetType/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
        </div>
      </div>
	</div>
	<?php $this->endWidget(); ?>
</div><!-- form -->