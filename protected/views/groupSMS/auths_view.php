
<?php
$this->pageTitle=Yii::app()->name . ' : Auth Level SMS Details';
$this->breadcrumbs=array(
	'Auths_Level' => array('auths'),
	'Details'     => array('groupSMS/authsView/'.$model->id)
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
            <div class="card-body">
                <div class="card-header">
                  <h5 class="title">Auth Level SMS Details</h5>
                  <hr class="common_rule">
                </div>
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12"><br>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <table class="table table-bordered table-hover">
                          <tr>
                            <td>Message</td>
                            <td><?=$model->GroupSMSMessage;?></td>
                          </tr>
                          <tr>
                            <td>Status</td>
                            <td><?=$model->GroupSMSStatus;?></td>
                          </tr>
                          <tr>
                            <td>Created By</td>
                            <td><?=$model->GroupSMSInitiatedBy;?></td>
                          </tr>
                            <tr>
                            <td>Created At</td>
                            <td><?=$model->GroupSMSDateInitiated;?></td>
                          </tr>
                        </table>
                  </div>
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <br>
                  <hr class="common_rule"/>
                </div>
                <div class="col-md-2 col-lg-2 col-sm-12">
                  <a href="<?=Yii::app()->createUrl('groupSMS/auths');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                </div>
                <?php if(Navigation::checkIfAuthorized(308) === 1 && $model->status === 'SUBMITTED'):?>
                <div class="col-md-2 col-lg-2 col-sm-12">
                  <a href="#" class="btn btn-success pull-left" onclick="LoadApprove()">Approve</a>
                </div>
                <?php endif;?>
                <?php if(Navigation::checkIfAuthorized(309) === 1 && $model->status === 'SUBMITTED'):?>
                <div class="col-md-2 col-lg-2 col-sm-12">
                    <a href="#" class="btn btn-primary pull-left" onclick="LoadReject()">Reject</a>
                </div>
                <?php endif;?>
             </div>
            </div>
					</div>
          <br>
        </div>
    </div>
</div>
<!-- APPROVE VIEW MODAL -->
<div class="modal fade" id="approve" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style=" height: auto!important;">
      <div class="modal-content">
        <div class="modal-body">
          <div class="col-md-12 col-lg-12 col-sm-12">
          <h4 class="title">Approve Request</h4>
          <hr class="common_rule">
          <form method="post" action="<?=Yii::app()->createUrl('groupSMS/approve/'.$model->id);?>">
            <br>
            <div class="row">
              <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                  <label>Brief Reason</label>
                  <textarea class="form-control" cols="10" rows="15" name="actionReason" placeholder="Brief comment..." required="required"></textarea>
                </div>
              </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12">
              <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Approve" name="approve_sms_cmd">
            </div>
          </div>
        </div>
      </form>
    </div>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END MODAL -->
<!-- REJECTION VIEW MODAL -->
<div class="modal fade" id="reject" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style=" height: auto!important;">
      <div class="modal-content">
        <div class="modal-body">
          <div class="col-md-10 col-lg-10 col-sm-12">
          <h4 class="title">Reject Request</h4>
          <hr class="common_rule">
          <form method="post" action="<?=Yii::app()->createUrl('groupSMS/reject/'.$model->id);?>">
            <br>
            <div class="row">
              <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                  <label>Brief Reason</label>
                  <textarea class="form-control" cols="10" rows="15" name="actionReason" placeholder="Brief comment..." required="required"></textarea>
                </div>
              </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12">
              <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Reject" name="reject_sms_cmd">
            </div>
          </div>
        </div>
      </form>
    </div>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- END MODAL -->
<script type="text/javascript">

  function LoadApprove(){
    $('#approve').modal({show:true});
  }

  function LoadReject(){
    $('#reject').modal({show:true});
  }
</script>