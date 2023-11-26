<?php
$this->pageTitle=Yii::app()->name . ' -  View Leave Request Details';
$this->breadcrumbs=array(
	'Requests'=>array('admin'),
  'View'=>array('leaveApplications/'.$model->id)
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-body">
          <div class="card-header">
              <h5 class="title">Leave Request Details</h5>
              <hr class="common_rule">
          </div>
            <div class="col-md-3 col-lg-3 col-sm-12">
              <table class="table table-bordered table-hover">
                <tr>
                  <td>Staff Member</td>
                  <td><?=$user->ProfileFullName;?></td>
                </tr>
                <tr>
                  <td>Branch</td>
                  <td><?=$user->ProfileBranch;?></td>
                </tr>
                <tr>
                  <td>Phone</td>
                  <td><?=$user->ProfilePhoneNumber;?></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><?=$user->ProfileEmailAddress;?></td>
                </tr>
                <tr>
                  <td>ID Number</td>
                  <td><?=$user->idNumber;?></td>
                </tr>
              </table>
            </div>
             <div class="col-md-3 col-lg-3 col-sm-12">
              <table class="table table-bordered table-hover">
                <tr>
                  <td>Annual Leave Days</td>
                  <td><?=$leave->leave_days." days";?></td>
                </tr>
                <tr>
                  <td>Leave Days to Carry Over</td>
                  <td><?=$leave->carry_over." days";?></td>
                </tr>
                <tr>
                  <td>Leave Days Taken</td>
                  <td><?=leavesManager::calculateTotalLeaveDaysTaken($leave->id)." days";?></td>
                </tr>
                <tr>
                  <td>Leave Days Balance</td>
                  <td><?=leavesManager::calculateRemainingLeaveDays($leave->leave_days,leavesManager::calculateTotalLeaveDaysTaken($leave->id))." days";?></td>
                </tr>
                <tr>
                  <td>Date Created</td>
                  <td><?=date('jS M Y',strtotime($leave->created_at));?></td>
                </tr>
              </table>
            <br><br>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-12">
                  <table class="table table-bordered table-hover">
                    <tr>
                      <td>Leave Start Date</td>
                      <td><?=date('jS M Y',strtotime($model->start_date));?></td>
                    </tr>
                    <tr>
                      <td>Leave End Date</td>
                      <td><?=date('jS M Y',strtotime($model->end_date));?></td>
                    </tr>
                    <tr>
                      <td>Directed To</td>
                      <td><?=$model->DirectedTo;?></td>
                    </tr>
                    <tr>
                      <td>Handover To</td>
                      <td><?=$model->HandoverTo;?></td>
                    </tr>
                    <tr>
                      <td>Date Applied</td>
                      <td><?=date('jS M Y',strtotime($model->created_at));?></td>
                    </tr>
                  </table><br><br>
          </div>
	        	<form method="post">
                <?php
                  if($model->status==='1'){
                    $actionTaken="Approved";
                  }elseif($model->status==='2'){
                    $actionTaken="Rejected";
                  }else{
                    $actionTaken="Submitted";
                  }
                ?>
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="form-group">
                        <h6 style="text-decoration: underline;">Request Status: <span style="color:green;"><?=strtoupper($actionTaken);?></span></h6>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="form-group">
                        <?php if(is_null($model->auth_reason)):?>
                          <div class="text-wrap width-425" style="color:blue;">
                            No authorization reason was provided or the request has not yet been authorized (Approved/Rejected).
                          </div>
                        <?php else:?>
                          <div class="text-wrap width-425" style="color:blue;">
                            <?=$model->auth_reason;?>
                          </div>
                        <?php endif;?>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="form-group">
                        <h6 style="text-decoration: underline;">Handover Notes</h6>
                        <div class="text-wrap width-425" style="color:green;">
                          <?=leavesManager::getHandoverNotes($model->id);?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br><br>
                </div>
              </div>
            </form>
        </div>
     </div>
 </div>