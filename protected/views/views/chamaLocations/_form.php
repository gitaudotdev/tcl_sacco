<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'chama-locations-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
       	<div class="form-group">
    		<label>Location Name</label>
				<?=$form->textField($model,'name',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Location Name','required'=>'required')); ?>
				<?=$form->error($model,'name');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
       	<div class="form-group">
    		<label>Chama Town</label>
				<?=$form->textField($model,'town',array('size'=>60,'maxlength'=>512,'class'=>'form-control','placeholder'=>'Chama Location Town','required'=>'required')); ?>
				<?=$form->error($model,'town');?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('chamaLocations/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
        </div>
      </div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Location':'Update Location',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div>
<?php $this->endWidget(); ?>
</div><br><br>