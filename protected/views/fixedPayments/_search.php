<div class="row">
	  <div class="col-md-12 col-lg-12 col-sm-12">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			));?>
			<div class="row">
				<div class="col-md-3 col-lg-3 col-sm-12">
	          <div class="form-group">
	          	<?=$form->dropDownList($model,'expensetype_id',$model->getFixedPaymentTypes(),array('prompt'=>'-- EXPENSE TYPES --','class'=>'selectpicker')); ?>
					</div>
				</div>
				<div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
             <?=$form->dropDownList($model,'user_id',$model->getFixedPaymentSuppliers(),array('prompt'=>'-- SUPPLIERS --','class'=>'selectpicker')); ?>
            </div>
         </div>
         <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
              <?=$form->dropDownList($model,'branch_id',$model->getFixedPaymentBranches(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
            </div>
         </div>
				  <div class="col-md-3 col-lg-3 col-sm-12">
	          <div class="form-group">
              <?=$form->dropDownList($model,'rm',$model->getFixedPaymentsInitiators(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
						</div>
					</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-3 col-lg-3 col-sm-12">
	          <div class="form-group">
	          	<?=$form->dropDownList($model,'status',array('0'=>'Initiated','1'=>'Approved','2'=>'Disbursed','3'=>'Rejected','4'=>'Cancelled'),array('prompt'=>'-- STATUS --','class'=>'selectpicker')); ?>
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
							 <?=CHtml::submitButton('Search Payment',array('class'=>'btn btn-primary pull-right','style'=>'margin-top:-2%;')); ?>
						</div>
					</div>
		 </div>
		<?php $this->endWidget(); ?>
		</div>
		<div class="col-md-12 col-lg-12 col-sm-12">
			<hr class="common_rule">
		</div>
</div>