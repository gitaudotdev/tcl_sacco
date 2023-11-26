<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Loan Top Up Request';
$this->breadcrumbs=array(
    'Applications'=>array('admin'),
    'TopUps'=>array('loanaccounts/viewTopup/'.$model->loanaccount_id),
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
	            <h5 class="title">Action Top Up Request</h5>
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
        <div class="card-body col-md-12 col-lg-12 col-sm-12">
        	<div class="col-md-5 content_holder">
          	<table class="table table-condensed table-striped">
          		<tr>
          			<td>Branch</td>
          			<td><?=$model->getBorrowerBranchName();?></td>
          		</tr>
          		<tr>
          			<td>Member Name</td>
          			<td><div class="text-wrap"><?=$model->getBorrowerName();?></div></td>
          		</tr>
              <tr>
                <td>Residence</td>
                <td><div class="text-wrap"><?=$model->getLoanAccountUserResidence();?></div></td>
              </tr>
          		<tr>
          			<td>Phone Number</td>
          			<td><?=$model->getBorrowerPhoneNumber();?></td>
          		</tr>
          		<tr>
          			<td>Relationship Manager</td>
          			<td><div class="text-wrap"><?=$model->getRelationshipManagerName();?></div></td>
          		</tr>
          		<tr>
          			<td>Account Opening Date</td>
          			<td><?=date('jS M Y',strtotime($model->created_at));?></td>
          		</tr>
              <tr>
                <td>Savings Balance</td>
                <td>
                  <?=CommonFunctions::asMoney(LoanApplication::getUserSavingAccountBalance($model->user_id));?>
                </td>
              </tr>
          	</table>
          </div>
          <div class="col-md-5 content_holder">
          	<table class="table table-condensed table-striped">
          		<tr>
          			<td>Account Number</td>
          			<td><?=$model->account_number;?></td>
          		</tr>
               <tr>
                <td>Loan Limit</td>
                <td><?=$model->ClientMaximumAmount;?></td>
              </tr>
          		<tr>
          			<td><strong>Top Up Amount (Amount To Disburse)</strong></td>
          			<td><strong><?=CommonFunctions::asMoney($topup->topup_amount);?></strong></td>
          		</tr>
          		<tr>
          			<td>Repayment Period</td>
          			<td><?=$model->repayment_period;?> Months</td>
          		</tr>
          		<tr>
          			<td>Interest Rate</td>
          			<td><?=$model->interest_rate;?> % p.m.</td>
          		</tr>
          		<tr>
          			<td><strong>New Loan Principal</strong></td>
          			<td><strong><?=CommonFunctions::asMoney($topup->disbursement_amount);?></strong></td>
          		</tr>
              <tr>
                <td><strong>Monthly Installment</strong></td>
                <td><strong><?=CommonFunctions::asMoney(LoanCalculator::getEMIAmount($topup->disbursement_amount,$model->interest_rate,$model->repayment_period));?></strong></td>
              </tr>
          	</table>
          </div>
          <div class="col-md-12 col-lg-12 col-sm-12">
              <h4 class="info-text"> Account Actions </h4>
              <hr class="common_rule">
              <div class="row justify-content-center">
                <?php if(Yii::app()->user->user_level !== '3'):?>
                <div class="col-md-12 col-lg-12 col-sm-12" style="border-bottom: 2px dotted #ddd;margin-bottom: 2% !important;">
                    <div class="col-md-3 col-lg-3 col-sm-12" style="margin-bottom: 3% !important;">
                        <a href="#" class="btn btn-warning" onclick="LoadAddFile()">Add File</a>
                    </div>
                    <?php if($topup->is_approved == '0'):?>
                      <?php if(Navigation::checkIfAuthorized(44) == 1):?>
                      <div class="col-md-3 col-lg-3 col-sm-12" style="margin-bottom: 3% !important;">
                          <a class="btn btn-primary" data-toggle="modal" data-target="#approveTopUpModal" style="color:#ffff;">Approve</a>
                        </div>
                      <?php endif;?>
                       <?php if(Navigation::checkIfAuthorized(45) == 1):?>
                      <div class="col-md-3 col-lg-3 col-sm-12" style="margin-bottom: 3% !important;">
                        <a class="btn btn-info" data-toggle="modal" data-target="#rejectTopUpModal" style="color:#ffff;">Reject</a>
                      </div>
                     <?php endif;?>
                    <?php endif;?>
                    <?php if($topup->is_approved == '1'):?>
                       <?php if(Navigation::checkIfAuthorized(174) == 1):?>
                        <div class="col-md-3 col-lg-3 col-sm-12" style="margin-bottom: 3% !important;">
                          <a class="btn btn-success" data-toggle="modal" data-target="#disburseTopUpModal" style="color:#ffff;">Disburse</a>
                        </div>
                        <?php endif;?>
                    <?php endif;?>
                    <div class="col-md-3 col-lg-3 col-sm-12" style="margin-bottom: 3% !important;">
                      <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" class="btn btn-default">
                        Cancel
                      </a>
                    </div>
                </div>
                <?php else:?>
                <div class="col-md-12 col-lg-12 col-sm-12" style="border-bottom: 2px dotted #ddd;margin-bottom: 2% !important;">
                </div>
                <?php endif;?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <?php if(!empty($files)):?>
                  <table class="table table-condensed table-striped">
                    <thead>
                      <th>#</th>
                      <th>File Name</th>
                      <th>File Actions</th>
                    </thead>
                    <tbody>
                      <?php $i=1;?>
                      <?php foreach($files as $file):?>
                        <?php
                          $downloadLink=Yii::app()->params['homeDocs'].'/loans/files/'.$file->filename;
                          $exportLink="<a href='$downloadLink' class='btn btn-success'> <i class='fa fa-download'></i> Download</a>";
                          $viewLink="<a href='#' class='btn btn-info' onclick='loadFile(\"".$file->filename."\")'> <i class='fa fa-eye'></i> View</a>";
                          if(Navigation::checkIfAuthorized(176) == 1){
                            $deleteAction="<a href='#' class='btn btn-primary' onclick='Authenticate(\"".Yii::app()->createUrl('loanFiles/deleteTopped/'.$file->id)."\")' title='Delete Loan File'><i class='fa fa-trash'></i> Delete</a>";
                          }else{
                            $deleteAction="";
                          }
                          ?>
                          <tr>
                            <td><?=$i;?></td>
                            <td><?=$file->name;?></td>
                            <td><?=$viewLink;?>&emsp;<?=$exportLink;?>&emsp;<?=$deleteAction;?></td>
                          </tr>
                      <?php $i++;?>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                 <?php else:?>
                    <br>
                    <h4 style="color:red !important;">*** NO FILES UPLOADED ***</h4>
                 <?php endif;?>
                </div>
              </div>
          </div>
        </div>
    </div>
</div>
<!-- FILE VIEW MODAL -->
<div class="modal fade" id="loadingFile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:100% !important; height: auto!important;">
    <div class="modal-content">
      <div class="modal-body">
        <div id="loadedFile"></div>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END MODAL -->
<!-- ADDING FILE VIEW MODAL -->
<div class="modal fade" id="addFile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:60% !important; height: auto!important;">
    <div class="modal-content">
      <div class="modal-body">
         <h4 style="font-weight: bold;">Upload Account Files</h4>
         <hr class="common_rule">
         <form method="post" enctype='multipart/form-data' action="<?=Yii::app()->createUrl('loanaccounts/makeFile/'.$model->loanaccount_id);?>">
          <br>
          <input type="hidden" name="accountAction" value="toppedUpAccount">
          <div class="row">
            <div class="col-md-9 col-lg-9 col-sm-12">
              <div class='form-group'>
                <label >File Name</label>
                <input type='text' name="loan_file_name" required="required" class="form-control">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <label >Browse File</label><br>
              <div class='file-input'>
                <input type='file' name="loan_file" required="required">
                <span class='button'>Choose File</span>
                <span class='label' data-js-label>No file selected</label>
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
<!-- BEGIN APPROVE TOP UP MODAL-->
<div class="modal fade" id="approveTopUpModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:60% !important; height: auto!important;">
    <div class="modal-content">
      <div class="modal-body">
         <h4 style="font-weight: bold;">Approve Top Up Request</h4>
        <hr class="common_rule">
        <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/approveTopup');?>">
        <input type="hidden" name="loanaccount" value="<?=$model->loanaccount_id;?>">
        <input type="hidden" name="topupAccount" value="<?=$topup->id;?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Reason for Approving Request</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="approvalReason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <input type="submit" name="approve_top_up_cmd" value="Approve Request" class="btn btn-primary">
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END APPROVE TOP UP MODAL-->

<!-- BEGIN REJECT TOP UP MODAL-->
<div class="modal fade" id="rejectTopUpModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:60% !important; height: auto!important;">
    <div class="modal-content">
      <div class="modal-body">
         <h4 style="font-weight: bold;">Reject Top Up Request</h4>
        <hr classs="common_rule">
        <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/rejectTopup');?>">
        <input type="hidden" name="loanaccount" value="<?=$model->loanaccount_id;?>">
        <input type="hidden" name="topupAccount" value="<?=$topup->id;?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Reason for Rejecting Request</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="rejectionReason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <input type="submit" name="reject_top_up_cmd" value="Reject Request" class="btn btn-primary">
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END REJECT TOP UP MODAL-->

<!-- BEGIN DISBURSE TOP UP MODAL-->
<div class="modal fade" id="disburseTopUpModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:60% !important; height: auto!important;">
    <div class="modal-content">
      <div class="modal-body">
         <h4 style="font-weight: bold;">Reject Top Up Request</h4>
        <hr classs="common_rule">
        <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/disburseTopup');?>">
        <input type="hidden" name="loanaccount" value="<?=$model->loanaccount_id;?>">
        <input type="hidden" name="topupAccount" value="<?=$topup->id;?>">
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <label >Reason for Disbursing Request</label>
                <textarea class="form-control" placeholder="Brief comment" rows="2" cols="5" name="disbursalReason" required="required"></textarea>
              </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <input type="submit" name="disburse_top_up_cmd" value="Disburse Request" class="btn btn-primary">
          </div>
        </div>
        <br>
      </form>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END DISBURSE TOP UP MODAL-->
<script type="text/javascript">
  function loadFile(filename){
    var extension=getFileExtension(filename);
    var filepath="<?=Yii::app()->params['homeDocs'].'/loans/files/';?>"+filename;
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
      var content='<strong>'+filename+'</strong><hr classs="common_rule"><br><img src="'+filepath+'" width="900" alt="'+filename+'"/>';
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
