<div class="row">
  <div class="col-md-12 col-sm-12 col-lg-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); ?><br>
         <div class="row">
           <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'branchId',$model->BranchList,array('prompt'=>'-- BRANCHES --','class'=>'selectpicker'));?>
              </div>
           </div>
           <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'createdBy',$model->ManagersList,array('prompt'=>'-- INITIATED BY --','class'=>'selectpicker'));?>
              </div>
           </div>
           <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'status',array('SUBMITTED'=>'SUBMITTED','APPROVED'=>'APPROVED','REJECTED'=>'REJECTED'),array('prompt'=>'-- STATUS --','class'=>'selectpicker'));?>
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
           <div class="col-md-2 col-lg-2 col-sm-12">
	            <div class="form-group">
					      <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
	            </div>
	        </div>
      </div>
	<?php $this->endWidget(); ?>
  </div>
</div>