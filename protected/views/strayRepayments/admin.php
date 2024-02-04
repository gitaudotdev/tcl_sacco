<?php
$this->pageTitle=Yii::app()->name . ' - Stray Repayments';
$this->breadcrumbs=array(
	'Stray'=>array('admin'),
	'Repayments'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#stray-repayments-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
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
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
            <?php if($succesStatus === 1):?>
		    <div class="col-md-12 col-lg-12 col-sm-12">
		      <?=CommonFunctions::displayFlashMessage($successType);?>
		    </div>
		    <?php endif;?>
		    <?php if($infoStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($infoType);?>
		      </div>
		    <?php endif;?>
		    <?php if($warningStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($warningType);?>
		      </div>
		    <?php endif;?>
		    <?php if($dangerStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($dangerType);?>
		      </div>
		    <?php endif;?>
        <div class="card-body">
						<div class="card-header">
							<h5 class="title">Manage Stray Repayments</h5>
							<hr class="common_rule">
						</div>
        	  			<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="search-form">
							<?php $this->renderPartial('_search',array(
								'model'=>$model,
							)); ?>
							</div><!-- search-form -->
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll !important; margin: 3% 0% 5% 0%!important;">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'stray-repayments-grid',
								'type'=>'condensed striped',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Stray Repayments Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Transaction ID',
										'value'=>'$data->transaction_id',
									),
									array(
										'header'=>'Client Name',
										'name'=>'ClientName',
									),
									array(
										'header'=>'Client Account',
										'value'=>'$data->clientAccount',
									),
//									array(
//										'header'=>'Phone Number',
//										'value'=>'$data->source',
//									),
									array(
										'header'=>'Date Transacted',
										'name'=>'PaymentTransactionDate',
									),
									array(
										'header'=>'Amount',
										'name'=>'AmountTransacted',
									),
									array(
									'header'=>'Repayment Actions',
									'name'=>'Action',
									),
								),
							)); ?>
						</div>
				</div>
    </div>
</div>

<!-- Confirm Logout modal -->
<div class="modal fade" id="selectPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header">
        <h4 class="title">
         	Select Loans/Savings Account
      	</h4>
      </div>
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-12 col-lg-12 col-sm-12">
      			<div class="form-group">
			      	<input type="hidden" id="stray_id" value="">
			      </div>
		      </div>
      		<div class="col-md-12 col-lg-12 col-sm-12">
      			<div class="form-group">
			      	<label>Please select Loans / Savings</label><br><br>
			      	<select id="selection" class="form-control selectpicker" style="width: 100% !important;">
			      		<option value="loans">Loans</option>
			      		<option value="savings">Savings</option>
			      	</select>
			      </div>
		      </div>
	      </div>
      </div>
      <div class="modal-footer">
		<div class="col-md-6 col-lg-6 col-sm-12">
			<div class="form-group">
        		<button type="button" class="btn btn-default pull-left" data-dismiss="modal"> Cancel</button>
			</div>
		</div>
		<div class="col-md-6 col-lg-6 col-sm-12">
			<div class="form-group">
      		<button  class="btn btn-primary pull-right" id="payment_cmd" onclick="redirectPage()">
				Proceed
			</button>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- End Modal-->

<script type="text/javascript">
	function directPayment(strayID){
		loadPaymentModal();
		$("input#stray_id").val(strayID);
	}

	function loadPaymentModal(){
		$('#selectPayment').modal({backdrop: 'static',keyboard: false,show:true});
	}

	function redirectPage(){
		var selectID=$("input#stray_id").val();
		var resource=$('#selection option:selected').val();
		switch(resource){
			case 'loans':
			window.location.href ="strayRepayments/pay/"+selectID;
			break;

			case 'savings':
			window.location.href ="strayRepayments/savings/"+selectID;
			break;
		}
	}
</script>
