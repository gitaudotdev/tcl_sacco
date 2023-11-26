<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	));?><br>
	<div class="row">
	    <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'branchId',$model->getProfileBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
          <?=$form->dropDownList($model,'managerId',$model->getProfileManagersList(),array('prompt'=>'-- MANAGERS --','class'=>'selectpicker')); ?>
        </div>
      </div>
			<div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'profileId',$model->getProfilesList(),array('prompt'=>'-- MEMBERS --','class'=>'selectpicker'));?>
        </div>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'level',array('SUPERADMIN'=>'SUPERADMIN','ADMIN'=>'ADMIN','STAFF'=>'STAFF','USER'=>'USER'),
			array('prompt'=>'-- AUTHORIZATIONS --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
        	<?=$form->dropDownList($model,'authStatus',array('ACTIVE'=>'ACTIVE','SUSPENDED'=>'SUSPENDED','DORMANT'=>'DORMANT','LOCKED'=>'LOCKED'),
			array('prompt'=>'-- ACCOUNT STATUS --','class'=>'selectpicker')); ?>
        </div>
      </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-2 col-lg-2 col-sm-12">
          <div class="form-group">
            <?=$form->textField($model,'startDate',array('class'=>'form-control','placeholder'=>'Start Date','id'=>'start_date')); ?>
          </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
          <?=$form->textField($model,'endDate',array('class'=>'form-control','placeholder'=>'End Date','id'=>'end_date'));?>
        </div>
       </div>
       <div class="col-md-2 col-lg-2 col-sm-12">
        <div class="form-group">
			<?=CHtml::submitButton('Search Authorizations',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
        </div>
      </div>
	</div>
	<?php $this->endWidget(); ?>
	</div>
</div><!-- search-form -->
<hr class="common_rule">