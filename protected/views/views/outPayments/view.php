<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : View Supplier Payment Details';
$this->breadcrumbs=array(
    'Supplier_Payments'=>array('admin'),
    'Payment_Details'=>array('outPayments/'.$model->id),
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
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-122">
    <div class="card">
        <div class="card-header">
  				<div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Supplier Payment Details</h5>
          	<hr class="common_rule">
  		     </div>
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
        </div>
        <div class="card-body">
        	<div class="col-md-5 col-lg-5 col-sm-12 content_holder">
            <h4 class="info-text">Supplier Details</h4>
            <hr class="common_rule">
          	<table class="table table-condensed table-striped">
          		<tr>
          			<td>Branch</td>
          			<td><?=$model->getOutPaymentBranch();?></td>
          		</tr>
              <tr>
                <td>Relation Manager</td>
                <td><?=$model->getOutPaymentRelationManager();?></td>
              </tr>
          		<tr>
          			<td>Supplier</td>
          			<td><?=$model->getOutPaymentSupplier();?></td>
          		</tr>
          		<tr>
          			<td>Phone Number</td>
          			<td><?=Users::model()->findByPk($model->user_id)->UserPhoneNumberAlternate;?></td>
          		</tr>
          		<tr>
          			<td>Expense Type</td>
          			<td><?=$model->getOutPaymentExpenseType();?></td>
          		</tr>
          		<tr>
          			<td>Amount</td>
          			<td><?=$model->getOutPaymentAmount();?></td>
          		</tr>
          		<tr>
          			<td><strong>Payment Status</strong></td>
          			<td><strong><?=$model->getOutPaymentStatus();?></strong></td>
          		</tr>
              <tr>
                <td>Staff Initiated</td>
                <td><?=$model->getOutPaymentInitiatedBy();?></td>
              </tr>
              <tr>
                <td>Initiation Reason</td>
                <td><div class="text-wrap width-200"><?=$model->initiation_reason;?></div></td>
              </tr>
              <tr>
                <td>Date Initiated</td>
                <td><?=$model->getOutPaymentInitiatedAt();?></td>
              </tr>
          	</table>
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12 content_holder">
            <h4 class="info-text">STATUS History</h4>
            <hr class="common_rule">
          	<table class="table table-condensed table-striped">
          		<?php if($model->status === '1'):?>
          			<tr>
	          			<td>Staff Approved</td>
	          			<td><?=$model->getOutPaymentApprovedBy();?></td>
	          		</tr>
	          		<tr>
	          			<td>Approval Reason</td>
	          			<td><div class="text-wrap width-200"><?=$model->approval_reason;?></div></td>
	          		</tr>
	          		<tr>
	          			<td>Date Approved</td>
	          			<td><?=$model->getOutPaymentApprovedAt();?></td>
	          		</tr>
	          	<?php endif;?>
	          	<?php if($model->status === '2'):?>
          			<tr>
	          			<td>Staff Approved</td>
	          			<td><?=$model->getOutPaymentApprovedBy();?></td>
	          		</tr>
	          		<tr>
	          			<td>Approval Reason</td>
	          			<td><div class="text-wrap width-200"><?=$model->approval_reason;?></div></td>
	          		</tr>
	          		<tr>
	          			<td>Date Approved</td>
	          			<td><?=$model->getOutPaymentApprovedAt();?></td>
	          		</tr>
	          		<tr>
	          			<td>Staff Disbursed</td>
	          			<td><?=$model->getOutPaymentDisbursedBy();?></td>
	          		</tr>
	          		<tr>
	          			<td>Disbursal Reason</td>
	          			<td><div class="text-wrap width-200"><?=$model->disbursal_reason;?></div></td>
	          		</tr>
	          		<tr>
	          			<td>Date Disbursed</td>
	          			<td><?=$model->getOutPaymentDisbursedAt();?></td>
	          		</tr>
	          	<?php endif;?>
	          	<?php if($model->status === '3'):?>
	          		<tr>
	          			<td>Staff Rejected</td>
	          			<td><?=$model->getOutPaymentRejectedBy();?></td>
	          		</tr>
	          		<tr>
	          			<td>Rejection Reason</td>
	          			<td><div class="text-wrap width-200"><?=$model->rejection_reason;?></div></td>
	          		</tr>
	          		<tr>
	          			<td>Date Rejected</td>
	          			<td><?=$model->getOutPaymentRejectedAt();?></td>
	          		</tr>
	          	<?php endif;?>
	          	<?php if($model->status === '4'):?>
	          		<tr>
	          			<td>Staff Cancelled</td>
	          			<td><?=$model->getOutPaymentCancelledBy();?></td>
	          		</tr>
	          		<tr>
	          			<td>Cancellation Reason</td>
	          			<td><div class="text-wrap width-200"><?=$model->cancellation_reason;?></div></td>
	          		</tr>
	          		<tr>
	          			<td>Date Cancelled</td>
	          			<td><?=$model->getOutPaymentCancelledAt();?></td>
	          		</tr>
	          	<?php endif;?>
            </table>
          </div>
					<div class="col-md-6 col-lg-6 col-sm-12 content_holder">
              <h4 class="info-text">Payment Files</h4>
              <hr class="common_rule">
              <?php 
                 $currentStatus=$model->status;
                 $allowedStatus=array('0','1','2');
              ?>
              <?php if((CommonFunctions::searchElementInArray($currentStatus,$allowedStatus) === 1) && (Navigation::checkIfAuthorized(217) === 1)):?>
              <a href="#" class="btn btn-success" onclick="LoadAddFile()">Add File</a>
              <hr class="common_rule">
              <?php endif;?>
              <?php if(!empty($files) && count($files) > 0):?>
              <table class="table table-condensed table-bordered">
                <thead>
                	<th>#</th>
                  <th>Name</th>
                  <th>Size</th>
                  <th>Action</th>
                </thead>
                <tbody>
                  <?php $counter=1;?>
                  <?php foreach($files AS $file):?>
                  <?php

                    $locationLink   = Yii::app()->params['expenseDocs'].'/'.$file->filename;
                    if(file_exists($locationLink)){

                      $downloadLink = Yii::app()->params['homeDocs'].'/expenses/'.$file->filename;
                      $receiptSize  = CommonFunctions::formatFileSizeInUnits(filesize($locationLink));
                      /************

                        VIEW IMAGE

                      ********************/
                      if(Navigation::checkIfAuthorized(218) === 1){
                        $viewLink = "<a href='#' class='btn btn-info btn-sm' onclick='loadFile(\"".$file->filename."\")'> <i class='fa fa-eye'></i></a>";
                      }else{
                        $viewLink = "";
                      }
                      /***************

                        DOWNLOAD IMAGE

                      ********************/
                      if(Navigation::checkIfAuthorized(219) === 1){
                        $exportLink = "<a href='$downloadLink' class='btn btn-success btn-sm' target='_blank'> <i class='fa fa-download'></i></a>";
                      }else{
                        $exportLink = "";
                      }
                      
                      echo "<tr>";
                        echo "<td>$counter</td>";
                        echo "<td><div class='text-wrap width-150'>"; echo $file->name; echo "</div></td>";
                        echo "<td>$receiptSize</td>";
                        echo "<td>$viewLink&emsp;$exportLink;</td>";
                      echo"</tr>";
                      $counter++;
                    }
                    ?>
                  <?php endforeach;?>
                </tbody>
              </table>
             	<?php else:?>
                <br>
                <h4 class="text-danger">*** NO FILES UPLOADED ***</h4>
                <br/><br/>
             	<?php endif;?>
          </div>
          </div>
            <?php if(Yii::app()->user->user_level !== '3'):?>
            <div class="col-md-11 col-lg-11 col-sm-12 btn_content_holder">
                <div class="col-md-3 col-lg-3 col-sm-12 holder_margin_bottom">
                    <a class="btn btn-info" href="<?=Yii::app()->createUrl('outPayments/admin');?>">
                      <i class="fa fa-arrow-left"></i>  Previous
                    </a>
                </div>
                <?php if($model->status == '0'):?>
                  <?php if(Navigation::checkIfAuthorized(204) == 1):?>
                  <div class="col-md-3 col-lg-3 col-sm-12 holder_margin_bottom">
                    <a class="btn btn-success" data-toggle="modal" data-target="#approvePayment">Approve</a>
                  </div>
                  <?php endif;?>
                  <?php if(Navigation::checkIfAuthorized(205) == 1):?>
                  <div class="col-md-3 col-lg-3 col-sm-12 holder_margin_bottom">
                    <a class="btn btn-danger" data-toggle="modal" data-target="#rejectPayment">Reject</a>
                  </div>
                 <?php endif;?>
                 <?php if(Navigation::checkIfAuthorized(206) == 1):?>
                  <div class="col-md-3 col-lg-3 col-sm-12 holder_margin_bottom">
                    <a class="btn btn-default" data-toggle="modal" data-target="#cancelPayment">Cancel</a>
                  </div>
                 <?php endif;?>
                <?php endif;?>
                <?php if($model->status == '1'):?>
                 <?php if(Navigation::checkIfAuthorized(206) == 1):?>
                  <div class="col-md-3 col-lg-3 col-sm-12 holder_margin_bottom">
                    <a class="btn btn-default" data-toggle="modal" data-target="#cancelPayment">Cancel</a>
                  </div>
                 <?php endif;?>
                 <?php if(Navigation::checkIfAuthorized(207) == 1):?>
                  <div class="col-md-3 col-lg-3 col-sm-12 holder_margin_bottom">
                    <a class="btn btn-success" data-toggle="modal" data-target="#disburseTopUpModal">Disburse</a>
                  </div>
                  <?php endif;?>
                <?php endif;?>
            </div>
            <?php endif;?>
          </div>
        </div>
    </div>
</div>
<!-- FILE VIEW MODAL -->
<div class="modal fade" id="loadingFile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal_dialog_common_image_viewer">
    <div class="modal-content">
      <div class="modal-body">
        <div id="loadedFile"></div>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END MODAL -->
<!-- BEGIN APPROVE MODAL-->
<div class="modal fade" id="approvePayment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal_dialog_common_width">
    <div class="modal-content modal_common_padding">
      <div class="modal-body">
         <h4 class="info-text">Approve Payment</h4>
        <hr>
        <form method="post" action="<?=Yii::app()->createUrl('outPayments/approve/'.$model->id);?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Reason for Approving Payment</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="approvalReason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <input type="submit" name="approve_top_up_cmd" value="Approve" class="btn btn-primary">
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END APPROVE MODAL-->

<!-- BEGIN REJECT MODAL-->
<div class="modal fade" id="rejectPayment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal_dialog_common_width">
    <div class="modal-content modal_common_padding">
      <div class="modal-body">
         <h4 class="info-text">Reject Payment</h4>
        <hr>
        <form method="post" action="<?=Yii::app()->createUrl('outPayments/reject/'.$model->id);?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Reason for Rejecting Payment</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="rejectionReason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <input type="submit" name="reject_top_up_cmd" value="Reject" class="btn btn-primary">
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END REJECT MODAL-->

<!-- BEGIN CANCEL MODAL-->
<div class="modal fade" id="cancelPayment" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal_dialog_common_width">
    <div class="modal-content modal_common_padding">
      <div class="modal-body">
         <h4 class="info-text">Cancel Payment</h4>
        <hr>
        <form method="post" action="<?=Yii::app()->createUrl('outPayments/cancel/'.$model->id);?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Cancellation Reason</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="cancellationReason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <input type="submit" name="cancel_top_up_cmd" value="Cancel" class="btn btn-primary">
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END CANCEL MODAL-->

<!-- BEGIN DISBURSE MODAL-->
<div class="modal fade" id="disburseTopUpModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal_dialog_common_width">
    <div class="modal-content modal_common_padding">
      <div class="modal-body">
         <h4 class="info-text">Disburse Payment</h4>
        <hr>
        <form method="post" action="<?=Yii::app()->createUrl('outPayments/disburse/'.$model->id);?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Reason for Disbursing Payment</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="disbursalReason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <input type="submit" name="disburse_top_up_cmd" value="Disburse" class="btn btn-primary">
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END DISBURSE MODAL-->
<!-- ADDING FILE VIEW MODAL -->
<div class="modal fade" id="addFile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal_dialog_common_width">
    <div class="modal-content">
      <div class="modal-body modal_common_padding">
        <h4>Upload File</h4>
        <hr class="common_rule">
        <form method="post" enctype='multipart/form-data'
         action="<?=Yii::app()->createUrl('outPayments/UploadReceipt');?>">
        <br>
        <input type="hidden" name="outpayment_ID" value="<?=$model->id;?>">
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label >Browse File</label><br>
                <a class='btn btn-info' href='javascript:;'>
                  Browse ...
                  <input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="payment_resource[]" 
                  onchange='$("#upload-file-info").html($(this).val().replace(/^.*\\/, ""));'>
                </a>
                <span class='label label-info' id="upload-file-info"></span>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="form-group">
                <label >Comment</label><br>
                 <textarea class="form-control" name="outpayment_activity" cols='5' rows='3' placeholder="Brief comment ..." required="required"></textarea>
              </div>
            </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6 col-lg-6 col-sm-12">
            <input type="submit" name="upload_file_cmd" value="Upload" class="btn btn-primary">
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END MODAL -->
<script type="text/javascript">
  function loadFile(filename){
    var extension=getFileExtension(filename);
    var filepath="<?=Yii::app()->params['homeDocs'].'/expenses/';?>"+filename;
    switch(extension.toLowerCase()){
      case 'doc':
      var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
      LoadRespectiveFile(content)
      break;

      case 'docx':
      var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
      LoadRespectiveFile(content)
      break;

      case 'pdf':
      var content='<object data="'+filepath+'" type="application/pdf" style="overflow:scroll !important;width:100% !important;height:100vh !important;"><a href="'+filepath+'">'+filepath+'</a></object>';
      LoadRespectiveFile(content)
      break;

      default:
      var content='<strong>'+filename+'</strong><hr><br><img src="'+filepath+'" width="900" alt="'+filename+'"/>';
      LoadRespectiveFile(content)
      break;

    }
  }

  function getFileExtension(filename){
    var parts = filename.split('.');
    return parts[parts.length - 1];
  }

  function LoadRespectiveFile(content){
    $('#loadingFile').modal({show:true});
    $('#loadedFile').html(content).show().fadeIn('slow');
  }

  function LoadAddFile(){
    $('#addFile').modal({show:true});
  }
</script>

<script type="text/javascript">
  
var inputs = document.querySelectorAll('.file-input')

for (var i = 0, len = inputs.length; i < len; i++) {
  customInput(inputs[i])
}

function customInput (el) {
  const fileInput = el.querySelector('[type="file"]')
  const label = el.querySelector('[data-js-label]')
  
  fileInput.onchange =
  fileInput.onmouseout = function () {
    if (!fileInput.value) return
    
    var value = fileInput.value.replace(/^.*[\\\/]/, '')
    el.className += ' -chosen'
    label.innerText = value
  }
}
</script>
