<div class="col-md-12 col-lg-12 col-sm-12">
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'auths-form',
			'enableAjaxValidation'=>false,
		));?>
		<br>
		<?=$form->errorSummary($model); ?>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label> Select Authorization </label>
						<?=$form->dropDownList($model,'level',array('SUPERADMIN'=>'SUPERADMIN','ADMIN'=>'ADMIN','STAFF'=>'STAFF','USER'=>'USER'),array('prompt'=>'-- AUTHORIZATION LEVEL --','class'=>'selectpicker','required'=>'required')); ?>
						<?=$form->error($model,'level');?>
					</div>
				</div>
			</div>
			<br/>
		</div>
		<br>
		<div class="row">
			<div class="col-md-2 col-lg-2 col-sm-12">
				<div class="form-group">
					<a href="<?=Yii::app()->createUrl('auths/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
				</div>
			</div>
			<div class="col-md-2 col-lg-2 col-sm-12">
				<div class="form-group">
					<?=CHtml::submitButton($model->isNewRecord ? 'Create':'Update',array('class'=>'btn btn-primary pull-right'));?>
				</div>
			</div>
		</div>
		<br><br>
	<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>