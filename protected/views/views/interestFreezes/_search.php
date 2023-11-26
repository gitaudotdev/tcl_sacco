  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?>
  <br>
	<div class="row">
      <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
         <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
          <?=$form->dropDownList($model,'rm',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
        </div>
      </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->dropDownList($model,'user_id',$model->getBorrowerList(),array('prompt'=>'-- MEMBERS --',
            'class'=>'selectpicker')); ?>
          </div>
       </div>
        <div class="col-md-2 col-lg-2 col-sm-12">
            <div class="form-group">
              <?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
          </div>
        </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date')); ?>
          </div>
       </div>
       <div class="col-md-1 col-lg-1 col-sm-12">
          <div class="form-group">
			     <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
          </div>
      </div>
      <div class="col-md-1 col-lg-1 col-sm-12">
        <div class="form-group">
          <?=CHtml::submitButton('Download',array('class'=>'btn btn-warning','style'=>'margin-top:-2% !important;','name' =>'export','id'=>'export-btn')); ?>
        </div>
      </div>
      </div>
      <br>
	<?php $this->endWidget();?>
</div><!-- search-form -->
<div class="col-md-12 col-lg-12 col-sm-12">
<hr class="common_rule">
</div>