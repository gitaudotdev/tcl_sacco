<?php
$this->pageTitle=Yii::app()->name . ' - System Users';
$this->breadcrumbs=array(
  'Settings'=>array('dashboard/admin'),
  'Users'=>array('users/admin'),
  'Logs'=>array('users/'.$id),
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
$i=1;
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
            	<h5 class="title">User Profile</h5>
              <hr>
	          </div>
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
        </div>
        <div class="card-body">
            <div class="card-body row">
                <?php
                    if(Navigation::checkIfAuthorized(16) == 1){
                      $elevate_link="<a href='#' class='btn btn-success' onclick='Authenticate(\"".Yii::app()->createUrl('users/update/'.$model->user_id)."\")'>Update </a>";
                    }else{
                      $elevate_link="";
                    }
                    switch($model->is_active){
                      case '0':
                      if(Navigation::checkIfAuthorized(20) == 1){
                        $activation_link="<a href='#' class='btn btn-warning' onclick='Authenticate(\"".Yii::app()->createUrl('users/activate/'.$model->user_id)."\")'>Activate</a>";
                      }else{
                        $activation_link="";
                      }
                      break;

                      case '1':
                      if(Navigation::checkIfAuthorized(21) == 1){
                        $activation_link="<a href='#' class='btn btn-default' onclick='Authenticate(\"".Yii::app()->createUrl('users/deactivate/'.$model->user_id)."\")'>Deactivate</a>";
                      }else{
                        $activation_link="";
                      }
                      break;
                    }
                  ?>
                  <div class="col-md-11 col-lg-11 col-sm-12 content_holder">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <span class="pull-left"><a href="<?=Yii::app()->createUrl('users/admin');?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Previous</a></span>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <center><span><?=$elevate_link;?></span></center>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <span class="pull-right"><?=$activation_link;?></span>
                    </div>
                  </div>
                  <div class="col-md-5 col-lg-5 col-sm-12 content_holder table-responsive">
                    <h5 class="title">Account Details</h5>
                    <hr class="common_rule">
                    <table class="table table-condensed table-striped">
                      <tr>
                        <td>Full Name</td>
                        <td><strong><?=$model->UserFullName;?></strong></td>
                      </tr>
                      <tr>
                        <td>Branch</td>
                        <td><strong><?=$model->BranchName;?></strong></td>
                      </tr>
                      <tr>
                        <td>Relation Manager</td>
                        <td><strong><?=$model->UserRelationManager;?></strong></td>
                      </tr>
                      <tr>
                        <td>ID Number</td>
                        <td><strong><?=$model->id_number;?></strong></td>
                      </tr>
                      <tr>
                        <td>Email Address</td>
                        <td><strong><?=$model->email;?></strong></td>
                      </tr>
                      <tr>
                        <td>Username</td>
                        <td><strong><?=$model->username;?></strong></td>
                      </tr>
                      <tr>
                        <td>Authorization</td>
                        <td><strong><?=$model->AuthorizationLevel;?></strong></td>
                      </tr>
                      <tr>
                        <td>SMS Notifications</td>
                        <td><strong><?=$model->NotificationStatus;?></strong></td>
                      </tr>
                      <tr>
                        <td>Date Created</td>
                        <td><strong><?=date('jS M Y',strtotime($model->created_at));?></strong></td>
                      </tr>
                    </table>
                  </div>
                  <?php if(!empty($borrower)):?>
                    <div class="col-md-5 col-lg-5 col-sm-12 content_holder table-responsive">
                        <h5 class="title">Member Details</h5>
                        <hr class="common_rule">
                        <table class="table table-condensed table-striped">
                          <tr>
                            <td>Gender</td>
                            <td><strong><?=ucfirst($borrower->gender);?></strong></td>
                          </tr>
                          <tr>
                            <td>Age</td>
                            <td><strong><?=$borrower->BorrowerAge;?></strong></td>
                          </tr>
                          <tr>
                            <td>Segment</td>
                            <td><strong><?=$borrower->MemberSegment;?> Member </strong></td>
                          </tr>
                          <tr>
                            <td>Residence</td>
                            <td>
                              <div class="text-wrap width-200">
                                <strong><?=ucfirst($borrower->residence_land_mark);?></strong>
                               </div>
                            </td>
                          </tr>
                          <tr>
                            <td>Working Status</td>
                            <td><strong><?=$borrower->BorrowerWorkingStatus;?></strong></td>
                          </tr>
                          <tr>
                            <td>Employer/ Business</td>
                            <td><strong><?=ucfirst($borrower->employer);?></strong></td>
                          </tr>
                          <tr>
                            <td>Phone Number</td>
                            <td><strong><?="+254 ".$borrower->phone;?></strong></td>
                          </tr>
                          <tr>
                            <td>Referree Name</td>
                            <td><strong><?=ucfirst($borrower->referred_by);?></strong></td>
                          </tr>
                          <tr>
                            <td>Referree Phone</td>
                            <td><strong><?=$borrower->referee_phone;?></strong></td>
                          </tr>
                        </table>
                    </div>
                  <?php endif;?>
            </div>
            <div class="col-lg-11 col-md-11 col-sm-12 content_holder">
                <h5 class="title">Activity Logs</h5>
                <hr class="common_rule">
            		<?php if(!empty($logs) && (Navigation::checkIfAuthorized(22) ===1)):?>
                <table id="example" class="display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Activity</th>
                            <th>Severity</th>
                            <th>Date Logged</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($logs AS $log):?>
                        <tr>
                            <td><?=$i;?></td>
                            <td><?=$log->LoggedUser;?></td>
                            <td><div class="text-wrap width-150"><?=$log->activity;?></div></td>
                            <td><?=$log->ActivitySeverity;?></td>
                            <td><?=$log->DateLogged;?></td>
                        </tr>
                        <?php $i++;?>
                      <?php endforeach;?>
                    </tbody>
                </table><br><br>
              </div>
          		<?php else:?>
          			 <div class="col-md-12 col-lg-12 col-sm-12" style="padding:10px 10px 10px 10px !important;">
                      <p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
                          <strong style="margin-left:20% !important;">NO USER ACTIVITY LOGS</strong></p><br>
                      <p style="color:#f90101;font-size:1.30em;">*** NO USER ACTIVITIES FOUND.ONCE ACTIVITIES OCCUR, ALL LOGS WILL BE TABULATED HERE****</p>
                      <br><br>
                  </div>
          		<?php endif;?>
				</div>
			</div>
     </div>
</div>