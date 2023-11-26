<?php
/* @var $this RolesController */
/* @var $model Roles */
/* @var $form CActiveForm */
?>
<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'action'=>Yii::app()->createUrl($this->route),
			'method'=>'get',
		)); ?>
		<div class="row">
				<div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
	        	<?=$form->textField($model,'name',array('class'=>'form-control','placeholder'=>'Role Name')); ?>
	        </div>
	      </div>
	      <div class="col-md-3 col-lg-3 col-sm-12">
	        <div class="form-group">
				<?=CHtml::submitButton('Search Role',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
	        </div>
	      </div>
	  </div>
	 <?php $this->endWidget(); ?>
	</div>
</div><!-- search-form -->
<hr class="common_rule">