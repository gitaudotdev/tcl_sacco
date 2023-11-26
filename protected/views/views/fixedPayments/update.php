<?php
$this->pageTitle=Yii::app()->name . ' - Update Fixed Expenses Payment';
$this->breadcrumbs=array(
	'Fixed_Payments'=>array('admin'),
	'Update'=>array('fixedPayments/update/'.$model->id)
);
/**Flash Messages**/
$successType = 'success';
$succesStatus = CommonFunctions::checkIfFlashMessageSet($successType);
$infoType = 'info';
$infoStatus = CommonFunctions::checkIfFlashMessageSet($infoType);
$warningType = 'warning';
$warningStatus = CommonFunctions::checkIfFlashMessageSet($warningType);
$dangerType = 'danger';
$dangerStatus = CommonFunctions::checkIfFlashMessageSet($dangerType);
?>
<style type="text/css">
  .error{
  	display: block !important;
  }
	.errorField{
		top:3.5%;
		color:#ff0000;
		display: none;
		font-size: 11px;
	}
</style>
<div class="row">
  <div id="customer-growth-panel"></div>
  <?php if($succesStatus === 1):?>
  <div class="col-lg-12 col-md-12 col-sm-12">
    <?=CommonFunctions::displayFlashMessage($successType);?>
  </div>
  <?php endif;?>
  <?php if($infoStatus === 1):?>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <?=CommonFunctions::displayFlashMessage($infoType);?>
    </div>
  <?php endif;?>
  <?php if($warningStatus === 1):?>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <?=CommonFunctions::displayFlashMessage($warningType);?>
    </div>
  <?php endif;?>
  <?php if($dangerStatus === 1):?>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <?=CommonFunctions::displayFlashMessage($dangerType);?>
    </div>
  <?php endif;?>
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Fixed Payment</h5>
            <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
						<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>
<script type="text/javascript">
	var typingTimer;
	const numbersPattern   = /^[0-9]+$/;
  var doneTypingInterval = 1020;
	var messagePanel       = 'customer-growth-panel';
	var message            = "Please correct form errors as indicated";
	var infoType           = 'info';
	var dangerType         = 'danger';
	var successType        = 'success';
	var digitsOnlyText     = "Please enter digits only. No commas/ full stops/ spaces";

$("#amount").on('keyup keydown change', function () {
  clearTimeout(typingTimer);
  if(numbersPattern.test($("#amount").val())){
	  var paymentAmount = parseFloat($("#amount").val());
	  var exLimit    = parseFloat($("#maxLimit").val());
	  let limitError = "Amount should not exceed payment limit of KES "+makeNumberHumanReadable(exLimit)+" /=";
	  if(paymentAmount){
	  	if(exLimit < paymentAmount){
	  		disableButtons();
	  		$("#amountError").show();
	  		$("#amountError").text(limitError);
	  	}else{
	  		activateButtons();
	  		$("#amountError").hide();
	    	typingTimer = setTimeout(doneTyping, doneTypingInterval); 
	  	}
	  }
  }else{
  	disableButtons();
		$("#amountError").show();
		$("#amountError").text(digitsOnlyText);
  }
});

function disableButtons(){
	$("#initiate_btn").prop('disabled', true);
}

function activateButtons(){
	$("#initiate_btn").prop('disabled', false);
}

var validateFixedPayment = () => {
	var isValid;

	if($('#expensetype_id').val() === ''){
		isValid = 1003;
	}

	if($('#amount').val() === ''){
		isValid = 1005;
	}

	if(!numbersPattern.test($("#amount").val())){
		isValid = 1007;
	}
	
}

$("#initiate_btn").on('click',(event)=>{
	event.preventDefault();
	switch(validateFixedPayment()){
		case 1003:
		disableButtons();
		$("#expenseTypesError").show();
		$("#expenseTypesError").text("Please select a supplier");
		displayNotification(messagePanel,dangerType,message);
		return false;
		break;

		case 1005:
		disableButtons();
		$("#amountError").show();
		$("#amountError").text("Please enter payment amount");
		displayNotification(messagePanel,dangerType,message);
		return false;
		break;

		case 1007:
		disableButtons();
		$("#amountError").show();
		$("#amountError").text(digitsOnlyText);
		displayNotification(messagePanel,dangerType,digitsOnlyText);
		return false;
		break;

		default:
		$('#fixed-payments-form').submit();
		break;
	}
});

function makeNumberHumanReadable(number) {
  var parts = number.toString().split(".");
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return parts.join(".");
}
</script>
