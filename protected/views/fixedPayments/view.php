
<?php
$this->pageTitle=Yii::app()->name . ' - View Fixed Payment Details';
$this->breadcrumbs=array(
	'Fixed_Payments'=>array('admin'),
	'View'=>array('fixedPayments/'.$model->id)
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
	.common_container{
		border:1px solid #dedede;
		padding:4% 4% 12% 8% !important;
	}

	#dialog-normal{
		width: 60% !important;
		height: auto !important;
	}

	.modal-content{
		padding:4% 4% 4% 4% !important;

	}

</style>
<div class="row">
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
            <h5 class="title">Fixed Payment Details</h5>
            <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	           <div class="col-md-5 content_holder">
	          	<table class="table table-condensed table-striped">
	          		<tr>
	          			<td>Supplier</td>
	          			<td><div class="text-wrap"><?=$model->getFixedPaymentSupplierName();?></div></td>
	          		</tr>
	          		<tr>
	          			<td>Branch</td>
	          			<td><?=$model->getFixedPaymentSupplierBranchName();?></td>
	          		</tr>
	          		<tr>
	          			<td>Manager</td>
	          			<td><div class="text-wrap"><?=$model->getFixedPaymentSupplierManager();?></div></td>
	          		</tr>
	          		<tr>
	          			<td>Phone Number</td>
	          			<td><?=$model->getFixedPaymentSupplierAccountNumber();?></td>
	          		</tr>
	          		<tr>
	          			<td>Maximum Limit</td>
	          			<td><?=$model->getFixedPaymentSupplierMaximumLimit();?></td>
	          		</tr>
	          		<tr>
	          			<td>Payment Period</td>
	          			<td><?=$model->getFixedPaymentPeriod();?></td>
	          		</tr>
	          		<tr>
	          			<td>Payment Type</td>
	          			<td><?=$model->getFixedPaymentExpenseTypeName();?></td>
	          		</tr>
	          		<tr>
	          			<td>Payment Amount</td>
	          			<td><?=$model->getFixedPaymentAmount();?></td>
	          		</tr>
	              <tr>
	                <td>Payment Status</td>
	                <td>
	                  <?=strtoupper($model->getFixedPaymentStatus());?>
	                </td>
	              </tr>
	          	</table>
	          </div>
	          <div class="col-md-5 content_holder">
	          	<table class="table table-condensed table-striped">
	          		<tr>
	          			<td>Initiated By</td>
	          			<td><?=$model->getFixedPaymentCreatedBy();?></td>
	          		</tr>
	          		<tr>
	          			<td>Initiation Reason</td>
	          			<td><div class="text-wrap"><?=$model->initiation_reason;?></div></td>
	          		</tr>
	          		<?php
	          		switch($model->status){
	          			case '1':
	          			$actionHeader = "Approved By";
	          			$actionedBy   = $model->getFixedPaymentApprovedBy();
	          			$statusHeader = "Approval Reason";
	          			$statusReason = $model->approval_reason;
	          			break;

	          			case '2':
	          			$actionHeader = "Disbursed By";
	          			$actionedBy   = $model->getFixedPaymentDisbursedBy();
	          			$statusHeader = "Disbursal Reason";
	          			$statusReason = $model->disbursal_reason;
	          			break;

	          			case '3':
	          			$actionHeader = "Rejected By";
	          			$actionedBy   = $model->getFixedPaymentRejectedBy();
	          			$statusHeader = "Rejection Reason";
	          			$statusReason = $model->rejection_reason;
	          			break;

	          			case '4':
	          			$actionHeader = "Cancelled By";
	          			$actionedBy   = $model->getFixedPaymentCancelledBy();
	          			$statusHeader = "Cancellation Reason";
	          			$statusReason = $model->cancellation_reason;
	          			break;

	          			default:
	          			$actionHeader = "";
	          			break;
	          		}
	          		?>
	          		<?php if($actionHeader != ""):?>
		          		<tr>
	          			<td><?=$actionHeader;?></td>
	          			<td><?=$actionedBy;?></td>
	          		</tr>
	          		<tr>
	          			<td><?=$statusHeader;?></td>
	          			<td><div class="text-wrap"><?=$statusReason;?></div></td>
	          		</tr>
		          	<?php endif;?>
	          	</table>
	          	<div class="common_container">
		          	<?php if($model->status === '0'):?>
					          <a href="<?=Yii::app()->createUrl('fixedPayments/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>&emsp;&emsp;
		          		<?php if(Navigation::checkIfAuthorized(247) == 1):?>
		          		<a href="#" class="btn btn-success" onclick="approvePaymentModal()"><i class="fa fa-check-circle"></i> Approve</a>&emsp;&emsp;
		          		<?php endif;?>
		          		<?php if(Navigation::checkIfAuthorized(248) == 1):?>
		          		<a href="#" class="btn btn-danger"  onclick="rejectPaymentModal()"><i class="fa fa-times-circle"></i>  Reject</a>&emsp;&emsp;&emsp;&nbsp;
			          	<?php endif;?>
			          	<?php if(Navigation::checkIfAuthorized(250) == 1):?>
			          		<a href="#" class="btn btn-default" onclick="cancelPaymentModal()"><i class="fa fa-trash"></i>  Cancel</a>
			          	<?php endif;?>
								<?php elseif($model->status === '1'):?>
		          	  <a href="<?=Yii::app()->createUrl('fixedPayments/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>&emsp;&emsp;
		          	  <?php if(Navigation::checkIfAuthorized(249) == 1):?>
										<a href="#" class="btn btn-success" onclick="disbursePaymentModal()"><i class="fa fa-send"></i> Disburse</a>&emsp;&emsp;
									<?php endif;?>
									<?php if(Navigation::checkIfAuthorized(250) == 1):?>
			          		<a href="#" class="btn btn-default" onclick="cancelPaymentModal()"><i class="fa fa-trash"></i>  Cancel</a>
			          	<?php endif;?>
		          	<?php else:?>
		          	  <a href="<?=Yii::app()->createUrl('fixedPayments/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>&emsp;&emsp;
		          	<?php endif;?>
		          </div>
	          </div>
	        </div>
        </div>
     </div>
  </div>
</div>

<!--APPROVE PAYMENTL-->
<div class="modal fade" id="approvePayment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="dialog-normal">
    <div class="modal-content">
      <div class="modal-body">
        <h4 class="title">Approve Payment</h4>
        <hr class="common_rule">
        <form method="post" action="<?=Yii::app()->createUrl('fixedPayments/approve');?>">
        <input type="hidden" name="fixed_payment" value="<?=$model->id;?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Brief Reason</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="reason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
            <input type="submit" name="approve_cmd" value="Approve" class="btn btn-primary">
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12">
            <a href="<?=Yii::app()->createUrl('fixedPayments/'.$model->id);?>" class="btn btn-default pull-right">
            Close</a>
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- REJECT PAYMENT-->
<div class="modal fade" id="rejectPayment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="dialog-normal">
    <div class="modal-content">
      <div class="modal-body">
        <h4 class="title">Reject Payment</h4>
        <hr class="common_rule">
        <form method="post" action="<?=Yii::app()->createUrl('fixedPayments/reject');?>">
        <input type="hidden" name="fixed_payment" value="<?=$model->id;?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Brief Reason</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="reason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
            <input type="submit" name="reject_cmd" value="Reject" class="btn btn-primary">
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12">
            <a href="<?=Yii::app()->createUrl('fixedPayments/'.$model->id);?>" class="btn btn-default pull-right">
            Close</a>
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>

<!-- DISBURSE PAYMENT-->
<div class="modal fade" id="disbursePayment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="dialog-normal">
    <div class="modal-content">
      <div class="modal-body">
        <h4 class="title">Disburse Payment</h4>
        <hr class="common_rule">
        <form method="post" action="<?=Yii::app()->createUrl('fixedPayments/disburse');?>">
        <input type="hidden" name="fixed_payment" value="<?=$model->id;?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Brief Reason</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="reason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
            <input type="submit" name="disburse_cmd" value="Disburse" class="btn btn-primary">
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12">
            <a href="<?=Yii::app()->createUrl('fixedPayments/'.$model->id);?>" class="btn btn-default pull-right">
            Close</a>
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>

<!-- CANCEL PAYMENT-->
<div class="modal fade" id="cancelPayment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="dialog-normal">
    <div class="modal-content">
      <div class="modal-body">
        <h4 class="title">Cancel Payment</h4>
        <hr class="common_rule">
        <form method="post" action="<?=Yii::app()->createUrl('fixedPayments/cancel');?>">
        <input type="hidden" name="fixed_payment" value="<?=$model->id;?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Brief Reason</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="reason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
            <input type="submit" name="cancel_cmd" value="Cancel" class="btn btn-primary">
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12">
            <a href="<?=Yii::app()->createUrl('fixedPayments/'.$model->id);?>" class="btn btn-default pull-right">
            Close</a>
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<script type="text/javascript">
	function approvePaymentModal(){
    $('#approvePayment').modal({show:true});
  }

  function rejectPaymentModal(){
    $('#rejectPayment').modal({show:true});
  }

  function disbursePaymentModal(){
    $('#disbursePayment').modal({show:true});
  }

  function cancelPaymentModal(){
    $('#cancelPayment').modal({show:true});
  }
</script>