<?php
/* @var $this BorrowergroupController */
/* @var $model Borrowergroup */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); ?>
			<div class="row">
          <div class="col-md-3">
              <div class="form-group">
              	<?=$form->textField($model,'name',array('class'=>'form-control','placeholder'=>'Group Name')); ?>
              </div>
           </div>
           <div class="col-md-3">
              <div class="form-group">
              	<?=$form->textField($model,'group_leader',array('class'=>'form-control','placeholder'=>'Leader')); ?>
              </div>
           </div>
           <div class="col-md-3">
              <div class="form-group">
              	<?=$form->textField($model,'collector_id',array('class'=>'form-control','placeholder'=>'Collector')); ?>
              </div>
           </div>
           <div class="col-md-3">
	            <div class="form-group">
								<?=CHtml::submitButton('Search Group',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
	            </div>
	        </div>
      </div>
			<?php $this->endWidget(); ?>
  </div>
</div>
<hr class="common_rule">