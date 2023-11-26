<div class="col-md-12 col-lg-12 col-sm-12">
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'dependencies-form',
			'enableAjaxValidation'=>false,
		));?>
		<br>
		<?=$form->errorSummary($model); ?>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>First Name </label>
						<?=$form->textField($model,'firstName',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'First Name','required'=>'required')); ?>
						<?=$form->error($model,'firstName');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Last Name </label>
						<?=$form->textField($model,'lastName',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'Last Name','required'=>'required')); ?>
						<?=$form->error($model,'lastName');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Relationship </label>
						<?=$form->textField($model,'relation',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'Relation','required'=>'required')); ?>
						<?=$form->error($model,'relation');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Contact Phone </label>
						<?=$form->textField($model,'phoneNumber',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'Phone Number','required'=>'required')); ?>
						<?=$form->error($model,'phoneNumber');?>
					</div>
				</div>
			</div>
			<br/>
		</div>
		<br>
		<div class="row">
			<div class="col-md-2 col-lg-2 col-sm-12">
				<div class="form-group">
					<a href="<?=Yii::app()->createUrl('profiles/'.$model->profileId);?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
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