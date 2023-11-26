<?php
$this->pageTitle=Yii::app()->name . ' - Approve Leave Request';
$this->breadcrumbs=array(
	'LeaveApplication'=>array('admin'),
  'Approve'=>array('leaveApplications/approve/'.$model->id)
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Approve Leave Request</h5>
            <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
          <div class="col-md-12 col-lg-12 col-sm-12">
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
              </table>
              <br><br>
            </div>
          </div>
          <div class="col-md-12 col-lg-12 col-sm-12">
	        	<form method="post">
                  <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                      <div class="form-group">
                        <strong >Requested: <?=leavesManager::calculateLeaveDuration($model->start_date,$model->end_date);?> days</strong>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                      <div class="form-group">
                        <label >Approval Reason</label>
                        <textarea class="form-control" cols="5" rows="3" name="auth_reason" placeholder="Brief Comment..." required="required"></textarea>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                      <div class="form-group">
                        <a href="<?=Yii::app()->createUrl('leaveApplications/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                      </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                      <div class="form-group">
                        <input type="submit" name="auth_leave_cmd" class="btn btn-primary pull-right" value="Approve">
                      </div>
                    </div>
                </div>
                <br><br>
            </form>
	        </div>
        </div>
     </div>
 </div>