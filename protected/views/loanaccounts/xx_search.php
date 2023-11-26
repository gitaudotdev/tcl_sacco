<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
/* @var $form CActiveForm */
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			));?><br>
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
<!--                            <select class="form-control selectpicker" name="member_type" id="member_type">-->
<!--                                <option value="">-- MEMBER TYPE --</option>-->
<!--                                <option value="salaried">SALARIED</option>-->
<!--                                <option value="business">BUSINESS</option>-->
<!--                            </select>-->

                        <?=$form->dropDownList($model,'member_type',array('SALARIED'=>'SALARIED','BUSINESS'=>'BUSINESS'),array('prompt'=>'-- MEMBER TYPE --','class'=>'selectpicker form-control-changed','id'=>'member_type')); ?>
                    </div>
                </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
            <div class="form-group">
<!--                get default all members, else on member type change according to type-->
                <?=$form->dropDownList($model,'user_id',$model->getBorrowerList(),array('prompt'=>'-- MEMBERS --','class'=>'selectpicker form-control-changed')); ?>

            </div>
          </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'loan_status',
                array('0'=>'Submitted','1'=>'Approved','2'=>'Disbursed','3'=>'Rejected','4'=>'Fully Paid',
                '5'=>'Restructured','6'=>'Topped Up','7'=>'Defaulted','8'=>'Forwarded','9'=>'Returned','10'=>'Resubmitted'),array('prompt'=>'-- PROCESS STATUS --','class'=>'selectpicker'));?>
              </div>
           </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
              <div class="form-group">
                <?=$form->dropDownList($model,'account_status',array('A'=>'Closed','B'=>'Dormant','C'=>'Write-Off','D'=>'Legal','E'=>'Collection','F'=>'Active','G'=>'Facility Rescheduled','H'=>'Settled','J'=>'Called Up','K'=>'Suspended','L'=>'Client Deceased','M'=>'Deferred','N'=>'Not Updated','P'=>'Disputed'),array('prompt'=>'-- ACCOUNT STATUS --','class'=>'selectpicker'));?>
              </div>
           </div>
          <div class="col-md-2 col-lg-2 col-sm-12">
            <div class="form-group">
                <?=$form->dropDownList($model,'performance_level',array('A'=>'Normal( 0 - 30 days)','B'=>'Watch( 31 - 90 days)','C'=>'Substandard( 91 - 180 days)','D'=>'Doubtful/Recovery( 181 - 360 days)','E'=>'Loss/Recovery( Over 360 days)'),array('maxlength'=>15,'class'=>'form-control selectpicker','prompt'=>'-- RISK CLASSIFICATION--')); ?>
                <?=$form->error($model,'performance_level'); ?>
            </div>
          </div>
         </div>
         <br>
         <div class="row">
          <div class="col-md-2 col-lg-2 col-sm-12">
            <div class="form-group">
                <?=$form->dropDownList($model,'crb_status',array('a'=>'Performing','b'=>'Blacklisted'),array('maxlength'=>15,'class'=>'form-control selectpicker','prompt'=>'-- CRB STATUS--')); ?>
                <?=$form->error($model,'crb_status'); ?>
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
				        <?=CHtml::submitButton('Search',array('class'=>'btn btn-primary','style'=>'margin-top:0% !important;')); ?>
	            </div>
	        </div>
         </div><br>
	<?php $this->endWidget(); ?>
  </div>
</div>
<hr class="common_rule">
<!-- search-form -->
<script type="text/javascript">
    //function fetchMembers(){
    //    var member_type = "ALL";
    //    $.ajax({
    //        url: '<?php //echo Yii::app()->createUrl('loanaccounts/loadBorrowerList'); ?>//',
    //        type: 'POST',
    //        data: {member_type:member_type},
    //        success: function(data){
    //            // var userOptions = JSON.parse(data);
    //            // console.log(userOptions)
    //            // // Update the dropdown list options
    //            // var dropdown = $('#userId');
    //            // dropdown.empty(); // Clear previous options
    //            // $.each(userOptions, function(key, value) {
    //            //     dropdown.append($('<option></option>').attr('value', key).text(value));
    //            // });
    //            //
    //            // // Refresh the selectpicker if necessary (if 'selectpicker' is a plugin)
    //            // dropdown.selectpicker('refresh');
    //        }
    //    });
    //}
    //$(document).ready(function(){
    //    fetchMembers();
    //    //$('#member_type').change(function(){
    //    //    var member_type = $(this).val();
    //    //    $.ajax({
    //    //        url: '<?php ////echo Yii::app()->createUrl('loanaccounts/loadBorrowerList'); ?>////',
    //    //        type: 'POST',
    //    //        data: {member_type:member_type},
    //    //        success: function(data){
    //    //            $('#userId').html(data);
    //    //        }
    //    //    });
    //    //});
    //});
</script>