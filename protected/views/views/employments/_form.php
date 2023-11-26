<div class="col-md-12 col-lg-12 col-sm-12">
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'employments-form',
			'enableAjaxValidation'=>false,
		));?>
		<br>
		<?=$form->errorSummary($model); ?>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Industry Type</label>
						<?=$form->dropDownList($model,'industryType',
						array('001'=>'Agriculture','002'=>'Manufacturing','003'=>'Building/ Construction','004'=>'Mining/ Quarrying',
						'005'=>'Energy/ Water','006'=>'Trade','007'=>'Tourism/ Restaurant/ Hotels','008'=>'Transport/ Communications',
						'009'=>'Real Estate','010'=>'Financial Services','011'=>'Government'),
						array('prompt'=>'-- INDUSTRY TYPE --','class'=>'selectpicker','required'=>'required')); ?>
						<?=$form->error($model,'industryType');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Employer </label>
						<?=$form->textField($model,'employer',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'Employer','required'=>'required')); ?>
						<?=$form->error($model,'employer');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Contact Phone </label>
						<?=$form->textField($model,'contactPhone',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'Employer Contact Phone','required'=>'required')); ?>
						<?=$form->error($model,'contactPhone');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Land Mark </label>
						<?=$form->textField($model,'landMark',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'Employer Landmark','required'=>'required')); ?>
						<?=$form->error($model,'landMark');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Town </label>
						<?=$form->textField($model,'town',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'Employment Town','required'=>'required')); ?>
						<?=$form->error($model,'town');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Salary </label>
						<?=$form->textField($model,'salaryBand',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'Salary','required'=>'required')); ?>
						<?=$form->error($model,'salaryBand');?>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Date Employed </label>
						<?=$form->textField($model,'dateEmployed',array('size'=>60,'maxlength'=>512,'class'=>'form-control',
						'placeholder'=>'YYYY-MM-DD','required'=>'required','id'=>'start_date')); ?>
						<?=$form->error($model,'dateEmployed');?>
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