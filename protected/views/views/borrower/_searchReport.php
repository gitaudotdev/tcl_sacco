<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
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
                <?=$form->dropDownList($model,'branch_id',$model->getSaccoBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
              </div>
           </div>
           <div class="col-md-3 col-lg-3 col-sm-12">
               <div class="form-group">
                <?=$form->dropDownList($model,'rm',$model->getRelationshipManagers(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
              </div>
           </div>
           <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
              	<?=$form->dropDownList($model,'borrower_id',$model->getMembersList(),array('prompt'=>'-- MEMBERS --','class'=>'selectpicker')); ?>
              </div>
           </div>
           <div class="col-md-3 col-lg-3 col-sm-12">
               <div class="form-group">
                <?=$form->dropDownList($model,'segment',array('0'=>'Small','1'=>'Premier','2'=>'Corporate'),array('prompt'=>'-- MEMBER SEGMENT --','class'=>'selectpicker')); ?>
              </div>
           </div>
         </div><br>
         <div class="row">
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
								<?=CHtml::submitButton('Search Member Records',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
	            </div>
	        </div>
          <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
              <?=CHtml::submitButton('Download Members Report',array('class'=>'btn btn-warning','style'=>'margin-top:-2% !important;','name' =>'export','id'=>'export-btn')); ?>
            </div>
        </div>
      </div>
			<?php $this->endWidget(); ?>
  </div>
</div>
<hr>