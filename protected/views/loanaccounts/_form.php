<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
/* @var $form CActiveForm */
?>
<style type="text/css">
    .error{
        display: none;
        font-size: 11px;
        color:red;
        top:2.5%;
    }
</style>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'loanaccounts-form',
        'enableAjaxValidation'=>false,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    )); ?>
    <?=$form->errorSummary($model); ?>
    <br>
    <div class="row">
        <!--        <div class="col-md-3 col-lg-3 col-sm-12">-->
        <!--            <div class="form-group">-->
        <!--                <label >Member TYPE</label>-->
        <!--                <select class="form-control selectpicker" name="type" id="member_type">-->
        <!--                    <option value="salaried">SALARIED</option>-->
        <!--                    <option value="business">BUSINESS</option>-->
        <!--                </select>-->
        <!--            </div>-->
        <!--        </div>-->
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Member</label>
                <?php if($model->isNewRecord):?>
                    <?=$form->dropDownList($model,'user_id',$model->getBorrowerList(),array('prompt'=>'-- MEMBERS --','class'=>'selectpicker form-control-changed','required'=>'required','id'=>'userID')); ?>
                    <?=$form->error($model,'user_id'); ?>
                <?php endif;?>
                <?php if(!($model->isNewRecord)):?>
                    <?=$form->dropDownList($model,'user_id',$model->getBorrowerList(),array('prompt'=>'-- MEMBERS --','class'=>'selectpicker form-control-changed','disabled'=>'disabled','id'=>'userID')); ?>
                    <?=$form->error($model,'user_id'); ?>
                <?php endif;?>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label>Account Number</label>
                <?=$form->textField($model,'account_number',array('required'=>'required','maxlength'=>15,'class'=>'form-control','placeholder'=>'Loan Account Number','id'=>'accountNumber')); ?>
                <?=$form->error($model,'account_number'); ?>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Client Employer</label>
                <?=$form->textField($model,'user_employer',array('required'=>'required','maxlength'=>15,'class'=>'form-control','placeholder'=>'Employer','readonly'=>'readonly','id'=>'userEmployer')); ?>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Saving Account Balance</label>
                <?=$form->textField($model,'saving_balance',array('required'=>'required','maxlength'=>15,'class'=>'form-control','placeholder'=>'Current Balance','readonly'=>'readonly','id'=>'savingBalance')); ?>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Loan Limit</label>
                <?=$form->textField($model,'maxLimit',array('readonly'=>'readonly','maxlength'=>15,'class'=>'form-control','placeholder'=>'Amount Applied Digits Only', 'id'=>'maxLimit')); ?>
                <?=$form->error($model,'maxLimit'); ?>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Interest Rate</label>
                <?=$form->textField($model,'interest_rate',array('readonly'=>'readonly','maxlength'=>15,'class'=>'form-control','placeholder'=>'Interest Rate','id'=>'interestRate')); ?>
                <?=$form->error($model,'interest_rate'); ?>
                <small class="error" id="interestRateError"></small>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Processing Rate</label>
                <?=$form->textField($model,'processingRate',array('readonly'=>'readonly','maxlength'=>15,'class'=>'form-control','placeholder'=>'Processing percentage Rate', 'id'=>'processingRate')); ?>
                <?=$form->error($model,'processingRate'); ?>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Insurance Rate</label>
                <?=$form->textField($model,'insuranceRate',array('readonly'=>'readonly','maxlength'=>15,'class'=>'form-control','placeholder'=>'Insurance Percentage Rate','id'=>'insuranceRate')); ?>
                <?=$form->error($model,'insuranceRate'); ?>
                <small class="error" id="insuranceRateRateError"></small>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Amount Applied</label>
                <?=$form->textField($model,'amount_applied',array('required'=>'required','maxlength'=>15,'class'=>'form-control','placeholder'=>'Amount Applied Digits Only','id'=>'amountApplied')); ?>
                <?=$form->error($model,'amount_applied'); ?>
                <small class="error" id="amountAppliedError"></small>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label>Direct Loan To</label>
                <?=$form->dropDownList($model,'direct_to',$model->getDirectedToList(),array('prompt'=>'-- STAFF MEMBERS --','class'=>'selectpicker form-control-changed','required'=>'required')); ?>
                <?=$form->error($model,'direct_to'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label>Loan Period</label>
                <select class="form-control selectpicker" name="repayment_period" id="repaymentPeriod">
                    <option value="1">One Day</option>
                    <option value="7">One Week</option>
                    <option value="14">Two Weeks</option>
                    <option value="21">Three Weeks</option>
                    <option value="30">One Month</option>
                    <option value="60">Two Months</option>
                    <option value="90">Three Months</option>
                    <option value="120">Four Months</option>
                    <option value="150">Five Months</option>
                    <option value="180">Six Months</option>
                    <option value="210">Seven Months</option>
                    <option value="240">Eight Months</option>
                    <option value="270">Nine Months</option>
                    <option value="300">Ten Months</option>
                    <option value="330">Eleven Months</option>
                    <option value="365">Twelve Months</option>
                </select>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Expiry Date</label>
                <input class="form-control" name="expiryDate" readonly  id="expiry_date"  value="<?php echo date('d-m-Y'); ?>"/>
            </div>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label>Repayment Frequency</label>
                <select class="form-control selectpicker" name="repayment_frequency" id="payFrequency">
                    <option id="daily" value="daily">Daily</option>
                    <option id="weekly" value="weekly">Weekly</option>
                    <option id="bi-weekly" value="bi-weekly">Bi-Weekly</option>
                    <option id="monthly" value="monthly">Monthly</option>
                    <option id="quarterly" value="quarterly">Quarterly</option>
                </select>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
            <label >Brief Comment</label>
            <?=$form->textArea($model,'special_comment',array('placeholder'=>'Please provide brief comment....','class'=>' form-control','cols'=>5,'rows'=>2,'required'=>'required')); ?>
            <?=$form->error($model,'special_comment'); ?>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-6 col-lg-6 col-sm-12">
        <a name="docs" href="#docs" class="btn btn-warning" id="add">Add Client Documents</a>
        <br><br><span style="color:red;">Kindly upload an image, word document or a PDF.</span>
    </div>
    <div class="col-md-12 col-lg-12 col-sm-12" id="items"></div>
</div>
<br>
<?php if(in_array(Yii::app()->user->user_level,array('0','1')) && (!$model->isNewRecord)):?>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label>Account Opening Date</label>
                <?=$form->textField($model,'created_at',array('required'=>'required','maxlength'=>15,'class'=>'form-control','id'=>'end_date','placeholder'=>'Account Opening Date')); ?>
                <?=$form->error($model,'created_at'); ?>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Date Loan Approved</label>
                <?=$form->textField($model,'date_approved',array('required'=>'required','maxlength'=>15,'class'=>'form-control','id'=>'normaldatepicker','placeholder'=>'Date Loan Approved')); ?>
                <?=$form->error($model,'date_approved'); ?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label>Penalty Amount</label>
                <?=$form->textField($model,'penalty_amount',array('required'=>'required','maxlength'=>15,'class'=>'form-control','placeholder'=>'Penalty Amount')); ?>
                <?=$form->error($model,'penalty_amount'); ?>
            </div>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >Account Status</label>
                <?=$form->dropDownList($model,'account_status',array('A'=>'Closed','B'=>'Dormant','C'=>'Write-Off','D'=>'Legal','E'=>'Collection','F'=>'Active','G'=>'Facility Rescheduled','H'=>'Settled','J'=>'Called Up','K'=>'Suspended','L'=>'Client Deceased','M'=>'Deferred','N'=>'Not Updated','P'=>'Disputed'),array('prompt'=>'-- ACCOUNT STATUS --','class'=>'selectpicker'));?>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3 col-lg-3 col-sm-12">
            <div class="form-group">
                <label >CRB Status</label>
                <?=$form->dropDownList($model,'crb_status',array('a'=>'Performing','b'=>'Blacklisted'),array('maxlength'=>15,'class'=>'form-control selectpicker','prompt'=>'-- CRB STATUS --')); ?>
                <?=$form->error($model,'crb_status'); ?>
            </div>
        </div>
    </div>
    <br>

<?php endif;?>
<div class="row">
    <div class="col-md-6 col-lg-6 col-sm-12">
        <hr class="common_rule">
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
            <a href="<?=Yii::app()->request->urlReferrer;?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
        </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-12">
        <div class="form-group">
            <?=CHtml::submitButton($model->isNewRecord ? 'Submit Request':'Update Request',array('class'=>'btn btn-primary pull-right','id'=>'submitApplication'));?>
        </div>
    </div>
</div>
<br><br>
<?php $this->endWidget(); ?>
<!-- Restrict Application Modal -->
<div class="modal fade" id="restrictApplication" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:60% !important;">
        <div class="modal-content" style="text-align: left;">
            <div class="modal-header justify-content-center" style="padding:1.5% !important;">
                <h4 class="title">
                    <i class="now-ui-icons ui-1_bell-53"></i>
                    NEW APPLICATION RESTRICTED
                </h4>
            </div>
            <div class="modal-body" style="padding:6% 6% 6% 6% !important;">
                <strong style="text-align: center;color:#2ca8ff;">The client still has a running loan. Please top up the current loan account since you cannot create a new account for the client unless the current account is cleared.</strong>
            </div>
            <div class="modal-footer">
                <a href="<?=Yii::app()->createUrl('loanaccounts/topup');?>" class="btn btn-primary mb-3">
                    Try Top Up
                </a>
                <button type="button" class="btn btn-default mb-3" data-dismiss="modal">
                    Cancel</button>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Modal-->

<script type="text/javascript">
    const numbersPattern = /^[0-9]+$/;
    var typingTimer;
    var doneTypingInterval=1020;
    loadClientDetails($('#userID').val());
    $(function(){
        $('#userID').on('change', function() {
            loadClientDetails(this.value);
            checkRunningLoan(this.value);
        });
    });


    // $(document).ready(function() {
    //     // Disable all options except 'daily'
    //     // $('#payFrequency option').each(function() {
    //     //     if (this.value !== 'daily') {
    //     //         $(this).prop('disabled', true);
    //     //     }
    //     // });
    //     updatePayFrequency();
    // });


    //when loan period is changed calculate expiry date
    $(function(){
        $('#repaymentPeriod').on('change', function() {
            var days = this.value;
            var date = new Date();
            var newdate = new Date(date);
            newdate.setDate(newdate.getDate() + parseInt(days));
            var dd = newdate.getDate();
            var mm = newdate.getMonth() + 1;
            var y = newdate.getFullYear();
            var someFormattedDate = dd + '-' + mm + '-' + y;
            $('#expiry_date').val(someFormattedDate);
        });

    });

    // function updatePayFrequency(){
    //     var period = $('#repaymentPeriod').val();
    //     if(period <= 30){
    //         $('#payFrequency option').each(function() {
    //             if (this.value !== 'daily') {
    //                 $(this).prop('disabled', true);
    //             }
    //         });
    //
    //     }else if(period > 30 && period <= 90){
    //         $('#payFrequency option').each(function() {
    //             if (this.value !== 'daily' && this.value !== 'weekly') {
    //                 $(this).prop('disabled', true);
    //             }
    //         });
    //     }else if(period > 90 && period <= 180){
    //         $('#payFrequency option').each(function() {
    //             if (this.value != 'daily' && this.value != 'weekly' && this.value != 'bi-weekly') {
    //                 $(this).prop('disabled', true);
    //             }
    //         });
    //     }else if(period > 180 && period <= 365){
    //         $('#payFrequency option').each(function() {
    //             if (this.value != 'daily' && this.value != 'weekly' && this.value != 'bi-weekly' && this.value != 'monthly') {
    //                 $(this).prop('disabled', true);
    //             }
    //         });
    //     }
    //     $('#payFrequency').selectpicker('refresh');
    //
    // }
    //
    // updatePayFrequency();

    function loadClientDetails(userID){
        activateButtons();
        $("#accountNumber").prop('disabled', true);
        $('#accountNumber').val('');
        $.ajax({
            type:"POST",
            dataType: "json",
            url: "<?=Yii::app()->createUrl('loanaccounts/loadAccountNumbers');?>",
            data: {'userID':userID},
            success: function(response) {
                if(response === 'NOT FOUND'){
                    $("#accountNumber").prop('disabled', false);
                }else{
                    $('#accountNumber').val(response.accountNumber);
                    $('#userEmployer').val(response.employer);
                    $('#savingBalance').val(response.savingsBalance);
                    $('#maxLimit').val(response.loanLimit);
                    $('#interestRate').val(response.interestRate);


                    $('#insuranceRate').val(response.insuranceRate);
                    $('#processingRate').val(response.processingRate);

                }
            }
        });
    }

    function checkRunningLoan(userID){
        $.ajax({
            type:"POST",
            url: "<?=Yii::app()->createUrl('loanaccounts/loadExistence');?>",
            data: {'userID':userID},
            success: function(response) {
                if(response === "1"){
                    displayRestriction();
                }
            }
        });
    }

    $("#amountApplied").on('keyup keydown change', function () {
        clearTimeout(typingTimer);
        if(numbersPattern.test($("#amountApplied").val())){
            var loanAmount = parseFloat($("#amountApplied").val());
            var exLimit = parseFloat($("#maxLimit").val());
            let limitError = "Amount should not exceed loan limit of KES "+makeNumberHumanReadable(exLimit)+" /=";
            if(loanAmount){
                if(exLimit < loanAmount){
                    disableButtons();
                    $("#amountAppliedError").show();
                    $("#amountAppliedError").text(limitError);
                }else{
                    activateButtons();
                    $("#amountAppliedError").hide();
                    typingTimer = setTimeout(doneTyping, doneTypingInterval);
                }
            }
        }else{
            disableButtons();
            $("#amountAppliedError").show();
            $("#amountAppliedError").text("Please enter digits only. No commas/ full stops");
        }
    });

    $("#interestRate").on('keyup keydown change', function () {
        clearTimeout(typingTimer);
        if(numbersPattern.test($("#interestRate").val())){
            var interestLimit = parseFloat($("#interestRate").val());
            if(interestLimit){
                if(interestLimit > 100){
                    disableButtons();
                    $("#interestRateError").show();
                    $("#interestRateError").text("Rate should not exceed 100");
                }else{
                    activateButtons();
                    $("#interestRateError").hide();
                    typingTimer = setTimeout(doneTyping, doneTypingInterval);
                }
            }
        }else{
            disableButtons();
            $("#interestRateError").show();
            $("#interestRateError").text("Please enter digits only. No commas/ full stops");
        }
    });

    $("#durationMonths").on('keyup keydown change', function () {
        clearTimeout(typingTimer);
        if(numbersPattern.test($("#durationMonths").val())){
            var durationLimit = parseFloat($("#durationMonths").val());
            if(durationLimit){
                if(durationLimit > 72){
                    disableButtons();
                    $("#durationMonthsError").show();
                    $("#durationMonthsError").text("Duration should not exceed 72 months");
                }else{
                    activateButtons();
                    $("#durationMonthsError").hide();
                    typingTimer = setTimeout(doneTyping, doneTypingInterval);
                }
            }
        }else{
            disableButtons();
            $("#durationMonthsError").show();
            $("#durationMonthsError").text("Please enter digits only. No commas/ full stops");
        }
    });

    //member_type change, change borrower list
    $("#member_type").on('change', function () {
        var member_type = $("#member_type").val();
        $.ajax({
            type:"POST",
            url: "<?=Yii::app()->createUrl('loanaccounts/loadBorrowerList');?>",
            data: {'member_type':member_type},
            success: function(response) {
                $("#userID").html(response);
            }
        });
    });



    function displayRestriction(){
        disableButtons();
        $('#restrictApplication').modal({backdrop: 'static',keyboard: false,show:true});
    }

    function disableButtons(){
        $("#submitApplication").prop('disabled', true);
        $("#add").prop('disabled', true);
    }

    function activateButtons(){
        $("#submitApplication").prop('disabled', false);
        $("#add").prop('disabled', false);
    }

    function makeNumberHumanReadable(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }

</script>

<script type="text/javascript">
    $(document).ready(function(){
        $("body").on("click", "#add",function(e){
            $("#items").append('<div class="col-md-6 col-lg-6 col-sm-12" style="border-bottom:2px dotted #dedede!important;padding:2% 2% 2% 0% !important;"><input name="path[]" type="file" required="required"/><button type="button" class="btn btn-info" id="add">Add </button>&emsp;|&emsp;<button class="delete btn btn-danger">Remove</button></div>');
        });
        $("body").on("click",".delete",function(e){
            $(this).parent("div").remove();
        });
    });
</script>