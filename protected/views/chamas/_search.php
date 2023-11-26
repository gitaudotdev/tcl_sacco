<div class="row">
  <div class="col-md-12 col-sm-12 col-lg-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); ?>
			<div class="row">
          <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
              	<?=$form->textField($model,'name',array('class'=>'form-control','placeholder'=>'Group Name')); ?>
              </div>
           </div>
           <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'is_registered',array('0'=>'NOT REGISTERED','1'=>'REGISTERED'),array('prompt'=>'-- REGISTRATION STATUS --','class'=>'selectpicker'));?>
              </div>
           </div>
         <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'branch_id',$model->getChamaBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
              </div>
          </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <?=$form->dropDownList($model,'rm',$model->getChamaManagersList(),array('prompt'=>'-- RELATION MANAGERS --','class'=>'selectpicker')); ?>
                </div>
          </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'organization_id',$model->getChamaOrganizationsList(),array('prompt'=>'-- ORGANIZATIONS --','class'=>'selectpicker')); ?>
              </div>
          </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <?=$form->dropDownList($model,'location_id',$model->getChamaLocationsList(),array('prompt'=>'-- LOCATIONS --','class'=>'selectpicker')); ?>
                </div>
          </div>
      </div><br>
         <div class="row">
          <div class="col-md-2 col-lg-2 col-sm-12">
                <div class="form-group">
                  <?=$form->dropDownList($model,'leader',$model->getChamaLeadersList(),array('prompt'=>'-- LEADERS --','class'=>'selectpicker')); ?>
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
						<?=CHtml::submitButton('Search Chama',array('class'=>'btn btn-primary','style'=>'margin-top:-2% !important;')); ?>
	            </div>
	        </div>
      </div>
			<?php $this->endWidget(); ?>
  </div>
</div>
<hr class="common_rule">