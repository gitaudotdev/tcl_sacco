<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : View Request Details';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/default'),
	'Transfers'=>array('transfers/admin'),
	'Details'=>array('transfers/'.$model->id)
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
	table{
		margin-top:3% !important;
	}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
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
    <div class="card">
        <div class="card-header">
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Transfer Request Details</h5>
            <hr>
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	          <div class="col-md-6 col-lg-6 col-sm-12">
	          	<table class="table table-condensed table-striped">
	          		<tr>
	          			<td>Branch</td>
	          			<td><?=$model->getRequestBranch();?></td>
	          		</tr>
                <tr>
                  <td>Client</td>
                  <td><?=$model->RequestSavingAccountHolder;?></td>
                </tr>
	          		<tr>
	          			<td>Initiator</td>
	          			<td><?=$model->getRequestBy();?></td>
	          		</tr>
	          		<tr>
	          			<td>Saving Account</td>
	          			<td><?=$model->getRequestSavingAccountNumber();?></td>
	          		</tr>
	          		<tr>
	          			<td>Loan Account</td>
	          			<td><?=$model->getRequestLoanAccountNumber();?></td>
	          		</tr>
	          	</table>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
	          	<table class="table table-condensed table-striped">
	          		<tr>
	          			<td>Approver</td>
	          			<td><?=$model->getRequestAuthorizedBy();?></td>
	          		</tr>
	          		<tr>
	          			<td>Date</td>
	          			<td><?=$model->getRequestDate();?></td>
	          		</tr>
	          		<tr>
	          			<td>Amount</td>
	          			<td><?=$model->getRequestAmount();?></td>
	          		</tr>
	          		<tr>
	          			<td>Reason</td>
	          			<td><div class="text-wrap width-200"><?=$model->transfer_reason;?></div></td>
	          		</tr>
	          	</table>
	          </div>
	        </div>
	        <div class="col-md-12 col-lg-12 col-sm-12" style="margin: 5% 0% 5% 0% !important;">
            <?php if((Navigation::checkIfAuthorized(154) == 1) && $model->is_approved === '0'):?>
            <div class="col-md-12 col-lg-12 col-sm-12">
            	<?php if(Navigation::checkIfAuthorized(156) == 1):?>
              <div class="col-md-3 col-lg-3 col-sm-12">
              	<a data-toggle="modal" data-target="#approveRequest" title='Approve Request' class="btn btn-success" style="color:#fff !important;">Approve Transfer</a>
              </div>
              <?php endif;?>
              <?php if(Navigation::checkIfAuthorized(157) == 1):?>
              <div class="col-md-3 col-lg-3 col-sm-12">
              	<a data-toggle="modal" data-target="#rejectRequest" title='Reject Request' class="btn btn-primary" style="color:#fff !important;">Reject Transfer</a>
              </div>
              <?php endif;?>
              <div class="col-md-6 col-lg-6 col-sm-12">
      					<a href="<?=Yii::app()->createUrl('transfers/admin');?>" class="btn btn-default pull-right">Go Back</a>
              </div>
            </div>
            <?php else:?>
            	<div class="col-md-12 col-lg-12 col-sm-12">
      					<a href="<?=Yii::app()->createUrl('transfers/admin');?>" class="btn btn-default pull-right">Go Back</a>
              </div>
            <?php endif;?>
     			</div>
     		</div>
     	</div>
</div>

<!-----Approve Request Modal----->
<div class="modal fade" id="approveRequest" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center">
        <h4 class="title">
           Approve Transfer
        </h4>
      </div>
      <div class="modal-body">
      <form method="post" action="<?=Yii::app()->createUrl('transfers/approve');?>">
       <input type="hidden" name="request" value="<?=$model->id;?>">
       <br>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Brief Comment</label>
                <textarea class="form-control" name="reason" rows="2" cols="5" placeholder="Brief comment ..." 
                required="required"></textarea>
              </div>
            </div>
        </div>
        <br>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary mb-3">Approve</button>
          <button type="button" class="btn btn-default mb-3" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
    </div>
  </div>
</div>
<!-----Approve Request Modal----->

<!-----Reject Request Modal----->
<div class="modal fade" id="rejectRequest" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center">
        <h4 class="title">
           Reject Transfer
        </h4>
      </div>
      <div class="modal-body">
      <form method="post" action="<?=Yii::app()->createUrl('transfers/reject');?>">
       <input type="hidden" name="request" value="<?=$model->id;?>">
       <br>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Brief Comment</label>
                <textarea class="form-control" name="reason" rows="2" cols="5" placeholder="Brief comment ..." 
                required="required"></textarea>
              </div>
            </div>
        </div>
        <br>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary mb-3">Reject</button>
          <button type="button" class="btn btn-default mb-3" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
    </div>
  </div>
</div>
<!-----Reject Request Modal----->