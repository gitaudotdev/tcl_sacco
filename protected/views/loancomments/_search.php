<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
	  	<?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			));?><br/>
			<div class="row">
				<div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
              <?=$form->dropDownList($model,'branch_id',$model->getCommentBranchList(),array('prompt'=>'-- BRANCHES --','class'=>'selectpicker')); ?>
            </div>
          </div>
          <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                  <?=$form->dropDownList($model,'rm',$model->getCommentManagerList(),array('prompt'=>'-- MANAGERS --','class'=>'selectpicker')); ?>
                </div>
          </div>
           <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'user_id',$model->getCommentClientList(),array('prompt'=>'-- CLIENTS --','class'=>'selectpicker')); ?>
              </div>
           </div>
          <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'type_id',$model->getAllCommentTypeList(),array('prompt'=>'-- COMMENT TYPES --','class'=>'selectpicker')); ?>
              </div>
           </div>
			</div><br/>
			<div class="row">
          <div class="col-md-3 col-lg-3 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'commented_by',$model->getCommentManagerList(),array('prompt'=>'-- COMMENTED BY --','class'=>'selectpicker')); ?>
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
           <div class="col-md-1 col-lg-1 col-sm-12">
	            <div class="form-group">
				        <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:0% !important;')); ?>
	            </div>
	        </div>&emsp;&emsp;
          <?php if(Navigation::checkIfAuthorized(259) === 1):?>
	        <div class="col-md-1 col-lg-1 col-sm-12">
	            <div class="form-group">
                <?=CHtml::submitButton('Download',array('class'=>'btn btn-warning','style'=>'margin-top:0% !important;','name' =>'export','id'=>'export-btn')); ?>
              </div>
	        </div>
          <?php endif;?>
	       </div>
			</div>
		<?php $this->endWidget(); ?>
	</div>
	<hr class="common_rule">
</div><!-- search-form -->