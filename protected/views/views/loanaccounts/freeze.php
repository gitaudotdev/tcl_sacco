<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Freeze Loan Application Interest Accrual';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'Applications'=>array('loanaccounts/admin'),
    'Freeze_Interest_Accrual'
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
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
			<div class="col-md-12 col-lg-12 col-sm-12">
          <h5 class="title">Freezing Loan Interest Accrual</h5>
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
          <div class="col-md-12 col-lg-12 col-sm-12" style="margin:0% 0% 0.25% 0%!important;">
              <div class="col-md-6 col-lg-6 col-sm-12">
                <h5>Account Details</h5>
                <hr>
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
                  <tr>
                    <td>Account Number</td>
                    <td><?=$model->account_number;?></td>
                  </tr>
                  <tr>
                    <td>Amount Applied</td>
                    <td><?=CommonFunctions::asMoney($model->amount_applied);?></td>
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
                    <td>Installment</td>
                    <td><strong><?=CommonFunctions::asMoney(LoanCalculator::getEMIAmount($model->amount_applied,$model->interest_rate,$model->repayment_period));?> </strong></td>
                  </tr>
                </table>
              </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <h5>Account Uploaded Files</h5>
                <hr>
                <?php if(!empty($files)):?>
                <table class="table table-condensed table-striped">
                  <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Download File</th>
                  </thead>
                  <tbody>
                    <?php $i=1;?>
                    <?php foreach($files as $file):?>
                      <?php
                      $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/files/'.$file->filename;
                      $exportLink="<a href='$PDF_Export_Link' class='btn btn-info'> <i class='fa fa-file-o'></i> DOWNLOAD</a>";
                      $viewLink="<a href='#' class='btn btn-warning' onclick='loadFile(\"".$file->filename."\")'> <i class='fa fa-file-o'></i> View</a>";
                      ?>
                      <tr>
                        <td><?=$i;?></td>
                        <td><div class="text-wrap"><?=$file->name;?></div></td>
                        <td><?=$viewLink;?>&emsp;<?=$exportLink;?></td>
                      </tr>
                      <?php $i++;?>
                    <?php endforeach;?>
                  </tbody>
                </table>
               <?php else:?>
                  <br>
                  <h4 style="color:red !important;">*** NO FILES UPLOADED ***</h4>
               <?php endif;?>
               <br>
               <div class="col-sm-12 col-lg-12 col-md-12">
                  <h5>Account Comments</h5>
                  <hr>
                  <?php if(!empty($comments)):?>
                    <table class="table table-condensed table-striped">
                      <thead>
                        <th>#</th>
                        <th>Loan Comment</th>
                      </thead>
                      <tbody>
                        <?php $i=1;?>
                        <?php foreach($comments as $comment):?>
                          <tr>
                            <td><?=$i;?></td>
                            <td><?=$comment->comment;?> : <?=$comment->activity;?> : <br> <cite><?=$comment->getLoanCommentedByName();?> : <?=$comment->getLoanCommentedAt();?></cite> </td>
                          </tr>
                          <?php $i++;?>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                   <?php else:?>
                   <h4>*** NO LOAN COMMENT SUPPLIED FOR THIS APPLICATION ***</h4>
                 <?php endif;?>
               </div>
            </div>
            <div class="col-sm-12 col-lg-12 col-md-12">
                  <br>
                  <hr>
                   <form method="post" action="<?=Yii::app()->createUrl('loanaccounts/CommitFreezing');?>">
                    <input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
                    <div class="row">
                      <div class="col-md-5 col-lg-5 col-sm-12">
                         <div class="form-group">
                          <label style="margin-bottom: 5% !important;">Select Freezing Period</label>
                          <select class="form-control selectpicker" name="freezing_period">
                            <option value="30">30 Days</option>
                            <option value="60">60 Days</option>
                            <option value="90">90 Days</option>
                            <option value="120">120 Days</option>
                            <option value="575">Indefinite</option>
                          </select>
                          </div>
                     </div>
                   </div><br>
                   <div class="row">
                      <div class="col-md-5 col-lg-5 col-sm-12">
                         <div class="form-group">
                          <label style="margin-bottom: 5% !important;">Reason for Freezing Interest Accrual</label>
                          <textarea class="form-control" cols="5" rows="3" name="freezing_reason" placeholder="Brief Comment ..." required="required"></textarea>
                        </div>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-12 col-lg-12 col-sm-12">
                       <div class="form-group">
                        <input type="submit" class="btn btn-primary " value="Freeze Interest">
                        <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" type="submit" class="btn btn-default  pull-right">Cancel Action</a>
                      </div>
                    </div>
                  </div>
              </form>
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
</script>
