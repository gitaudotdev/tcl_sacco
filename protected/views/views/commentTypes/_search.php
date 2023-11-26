<div class="wide form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?><br/>
	<div class="row">
	   <div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
          <?=$form->dropDownList($model,'is_active',array('0'=>'DISABLED','1'=>'ACTIVE'),array('maxlength'=>15,'class'=>'form-control selectpicker','prompt'=>'-- TYPE STATUS--')); ?>
      </div>
    </div>
		<div class="col-md-3 col-lg-3 col-sm-12">
      <div class="form-group">
        <?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
      </div>
   </div>
	 <div class="col-md-3 col-lg-3 col-sm-12">
	    <div class="form-group">
	      <?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date')); ?>
	    </div>
		</div>
		<div class="col-md-3 col-lg-3 col-sm-12">
	    <div class="form-group">
	      <?=CHtml::submitButton('Search Comment Type',array('class'=>'btn btn-primary pull-right','style'=>'margin-top:0% !important;')); ?>
	    </div>
	</div>	
</div>
<?php $this->endWidget(); ?>
</div>
<hr class="common_rule">