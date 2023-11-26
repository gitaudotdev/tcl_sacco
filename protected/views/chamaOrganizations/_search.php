<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); ?><br>
		<div class="row">
            <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
              	<?=$form->textField($model,'name',array('class'=>'form-control','placeholder'=>'Organization Name')); ?>
              </div>
           </div>
           <div class="col-md-2 col-lg-2 col-sm-12">
	            <div class="form-group">
					<?=CHtml::submitButton('Search Organization',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
	            </div>
	        </div>
      </div>
	<?php $this->endWidget(); ?>
  </div>
</div>
<hr class="common_rule">