<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' -  View Staff Leave';
$this->breadcrumbs=array(
    'Leave_Details'=>array('admin'),
    'View'=>array('leaves/'.$model->id),
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
  	  <!--MEMBER DETAILS-->
        <div class="card">
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
            <div class="card-body">
          	 	<div class="card-header">
		        	<h4 class="title"> <?=$model->getLeaveStaffName();?> : Leave Details</h4>
           			<hr class="common_rule">
		        </div>
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="col-md-4 col-lg-4 col-sm-12">
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
                       <div class="col-md-4 col-lg-4 col-sm-12">
                      	<table class="table table-bordered table-hover">
                      		<tr>
                      			<td>Annual Leave Days</td>
                      			<td><?=$model->leave_days." days";?></td>
                      		</tr>
                      		<tr>
                      			<td>Leave Days to Carry Over</td>
                      			<td><?=$model->carry_over." days";?></td>
                      		</tr>
                      		<tr>
                      			<td>Leave Days Taken</td>
                      			<td><?=leavesManager::calculateTotalLeaveDaysTaken($model->id)." days";?></td>
                      		</tr>
                          <tr>
                            <td>Leave Days Balance</td>
                            <td><?=leavesManager::calculateRemainingLeaveDays($model->leave_days,leavesManager::calculateTotalLeaveDaysTaken($model->id))." days";?></td>
                          </tr>
                      		<tr>
                      			<td>Date Created</td>
                      			<td><?=date('jS M Y',strtotime($model->created_at));?></td>
                      		</tr>
                      	</table>
                      <br><br>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <!--SHAREHOLDER DETAILS TABULATED-->
        <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
				        <div class="card-body">
                            <h4 class="title">Leave Requests</h4>
                            <hr class="common_rule"> 
                            <div class="col-md-12 col-lg-12 col-sm-12">
                            	<br>
                            	<?php if($applications != 0):?>
                        			<table class="table table-bordered table-hover" style="font-size:12px !important;">
                        			<thead>
                        				<th>#</th>
                        				<th>Start Date</th>
                        				<th>End Date</th>
                        				<th>Date Applied</th>
                        				<th>Status</th>
                                		<th>Direct To</th>
                        				<th>Authorized By</th>
                        				<th>Action</th>
                        			</thead>
                        			<tbody>
                        				<?php
                          				if(!empty($applications)){
                          					$i=1;
                          					foreach($applications as $application){
                          						echo "<tr>";
                          						echo "<td>$i</td>";
                          						echo "<td>$application->LeaveStartOn</td>";
                          						echo "<td>$application->LeaveEndOn</td>";
                          						echo "<td>$application->LeaveCreatedAt</td>";
                          						echo "<td>$application->Status</td>";
                                      			echo "<td>$application->DirectedTo</td>";
                          						echo "<td>$application->ApplicationAuthorizedByName</td>";
                          						echo "<td>";echo $application->getAction();echo"</td>";
                          						echo "</tr>";
                          						$i++;
                          					}
                          				}?>
                        			</tbody>
                        		</table>
                        		<?php else:?>
                        			<?php
                        			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="padding:10px 10px 10px 10px !important;">
				                              <p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
				                                  <strong style="margin-left:20% !important;">NO APPLICATIONS</strong></p><br>
				                              <p style="color:#f90101;font-size:1.30em;">*** THERE ARE NO LEAVE APPLICATIONS MADE. ***</p>
				                          </div>';
                        			?>
                        		<?php endif;?>	
                        		<br>
	                        </div>
	                    </div>
                   </div>
                </div>
			</div>
          </div>
        </div>
    </div>
</div>
</div>