<?php
/* @var $this AssetsController */
/* @var $model Assets */
/* @var $form CActiveForm */
?>
<div class="form col-md-12 col-lg-12 col-sm-12">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'assets-form',
		'enableAjaxValidation'=>false,
	));?>
	<?=$form->errorSummary($model); ?>
	<br>
	<div class="row">
		<div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<label >Select Branch</label>
          <?=$form->dropDownList($model,'branch_id',$model->getBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
        </div>
      </div>
      <div class="col-md-3 col-lg-3 col-sm-12">
          <div class="form-group">
          	<label >Assigned To</label>
          <?=$form->dropDownList($model,'user_id',$model->getStaffList(),array('prompt'=>'-- STAFF MEMBER --','class'=>'selectpicker')); ?>
        </div>
      </div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
          	<label >Select Type</label>
	        		<?=$form->dropDownList($model,'asset_type_id',$model->getAssetTypeList(),array('prompt'=>'-- ASSET TYPES --','class'=>'selectpicker','required'=>'required')); ?>
					<?=$form->error($model,'asset_type_id');?>
				</div>
			</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
          	<label >Asset Name</label>
				<?=$form->textField($model,'asset_name',array('size'=>15,'maxlength'=>150,'placeholder'=>'Asset Name','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'asset_name'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
          	<label >Serial Number</label>
				<?=$form->textField($model,'serial_number',array('size'=>15,'maxlength'=>150,'placeholder'=>'Asset Serial Number','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'serial_number'); ?>
			</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
          	<label >Purchase Date</label>
				<?=$form->textField($model,'purchase_date',array('size'=>15,'maxlength'=>150,'placeholder'=>'Purchase Date','class'=>'form-control','required'=>'required','id'=>'normaldatepicker')); ?>
				<?=$form->error($model,'purchase_date'); ?>
			</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
          <label >Purchase Price</label>
				<?=$form->textField($model,'purchase_price',array('size'=>15,'maxlength'=>150,'placeholder'=>'Purchase Price','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'purchase_price'); ?>
			</div>
		</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
          	<label >Replacement Value</label>
				<?=$form->textField($model,'replacement_value',array('size'=>15,'maxlength'=>150,'placeholder'=>'Replacement Value','class'=>'form-control','required'=>'required')); ?>
				<?=$form->error($model,'replacement_value'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
          	<label >Select Status</label>
	        		<?=$form->dropDownList($model,'status',array('broken'=>'Broken','donated'=>'Donated','for service'=>'For Service','for auction'=>'For Auction','in use'=>'In Use','replaced'=>'Replaced','sold'=>'Sold'),array('prompt'=>'-- ASSET STATUS --','class'=>'selectpicker','required'=>'required')); ?>
							<?=$form->error($model,'status');?>
				</div>
			</div>
    	<div class="col-md-3 col-lg-3 col-sm-12">
        	<div class="form-group">
          	<label >Description</label>
				<?=$form->textArea($model,'description',array('placeholder'=>'Brief description...','class'=>'form-control','required'=>'required','rows'=>1,'cols'=>4)); ?>
				<?=$form->error($model,'description'); ?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
      <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<a href="<?=Yii::app()->createUrl('assets/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
        </div>
      </div>
	  <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
        	<?=CHtml::submitButton($model->isNewRecord ? 'Create Asset':'Update Asset',array('class'=>'btn btn-primary pull-right'));?>
        </div>
      </div>
	</div>
	<br>
	<?php $this->endWidget(); ?>
</div><!-- form -->