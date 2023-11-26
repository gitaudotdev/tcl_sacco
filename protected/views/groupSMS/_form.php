<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'group-sms-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<?=$form->errorSummary($model); ?>
	<div class="row">
    	<div class="col-md-6 col-lg-6 col-sm-12">
       	<div class="form-group">
    		<label>Message</label>
				<?=$form->textArea($model,'message',array('placeholder'=>'Draft brief message....','class'=>' form-control','cols'=>5,'rows'=>2,'required'=>'required')); ?>
				<?=$form->error($model,'message'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
			<?php if($model->isNewRecord):?>
        	<a href="<?=Yii::app()->createUrl('groupSMS/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
			<?php endif;?>
			<?php if(!($model->isNewRecord) && ($model->groupType === 'AUTH_LEVEL')):?>
        	<a href="<?=Yii::app()->createUrl('groupSMS/auths');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
			<?php else:?>
        	<a href="<?=Yii::app()->createUrl('groupSMS/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
			<?php endif;?>
        </div>
      </div>
		<div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create':'Update',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div>
<?php $this->endWidget(); ?>
</div><br><br>