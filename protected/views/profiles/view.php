<?php
$this->pageTitle=Yii::app()->name . ' - Profile Management Dashboard';
$this->breadcrumbs=array(
    'Profiles'=>array('admin'),
    'View'=>array('profiles/'.$model->id),
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
	h4,.header_details{
		font-size: 1.1em !important;
		margin-top: 1.8% !important;
	}
	.info-text{
		text-transform: uppercase !important;
	}
	tr>td{
		font-size: 0.85em !important;
	}
	tr>td:last-of-type{
		font-weight: bold !important;
		font-size: 0.80em !important;
	}
	.nav-pills{
		padding: 10px 10px !important;
		margin-top: -1.5% !important;
	}
	.nav-item{
		font-size:.85em !important;
	}
	.nav-pills .nav-link{
		background-color: #222d32 !important;
		color:#fff !important;
		border-radius: 0px !important;
		text-transform: uppercase !important;
	}
	.nav-pills .nav-link.active {
	    color: #000 !important;
	    background-color: #fff !important;
	}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats card-raised modified_card">
	        	<div class=" col-md-12 col-lg-12 col-sm-12">
		        	<h4 class="info-text" style="font-weight:bold;">&emsp;<?=$model->getProfileFullName();?> Profile Details</h4>
           			<hr class="common_rule">
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
            <div class="card-body col-md-12 col-lg-12 col-sm-12">
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="col-md-4 col-lg-4 col-sm-12">
                      	<table class="table table-bordered table-hover">
                      		<tr>
                      			<td>Name</td>
                      			<td><?=$model->ProfileFullName;?></td>
                      		</tr>
                      		<tr>
                      			<td>ID Number</td>
                      			<td><?=$model->idNumber;?></td>
                      		</tr>
                      		<tr>
                      			<td>Age (Years)</td>
                      			<td><?=$model->ProfileAge;?></td>
                      		</tr>
                      		<tr>
                      			<td>Gender</td>
                      			<td><?=ucfirst($model->gender);?></td>
                      		</tr>
                      		<tr>
                      			<td>Phone Number</td>
                      			<td><?=$model->ProfilePhoneNumber;?>
								&emsp;&emsp;&emsp;<a href="#" data-toggle="modal"
								 data-target="#SmsProfile" class="btn btn-primary btn-xs" title="Send SMS"><i class="now-ui-icons ui-1_send"></i></a></td>
                      		</tr>
							<tr>
                      			<td>Residence</td>
                      			<td><?=$model->ProfileResidence;?></td>
                      		</tr>
                      		<tr>
                      			<td>Employer</td>
                      			<td><?=$model->ProfileEmployment;?></td>
                      		</tr>
                      	</table>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                      	<table class="table table-bordered table-hover">
                      		<tr>
                      			<td>Branch</td>
                      			<td><?=$model->ProfileBranch;?></td>
                      		</tr>
                      		<tr>
                      			<td>Relation Manager</td>
                      			<td><?=$model->ProfileManager;?></td>
                      		</tr>
                      		<tr>
                      			<td>Profile Type</td>
                      			<td><?=$model->ProfileType;?></td>
                      		</tr>
                      		<tr>
                      			<td>Username</td>
                      			<td><?=$model->ProfileUsername;?></td>
                      		</tr>
                      		<tr>
                      			<td>Authorization</td>
                      			<td><?=$model->ProfileAuthStatus;?></td>
                      		</tr>
							<tr>
                      			<td>Last Logged On</td>
                      			<td><?=$model->ProfileLastLoggedAt;?></td>
                      		</tr>
							<tr>
                      			<td>Account Status</td>
                      			<td><?=$model->ProfileAccountStatus; ProfileEngine::determineStatusActionLink($model->id);?></td>
                      		</tr>
							<tr>
                      			<td>Password Expires In</td>
                      			<td><?=User::calculateDaysToPasswordExpiry($model->id);?> Days</td>
                      		</tr>
                      	</table>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                      	<table class="table table-bordered table-hover">
                      		<tr>
                      			<td>Maximum Loan Limit</td>
                      			<td><?=$model->ProfileMaxLoanLimit;?></td>
                      		</tr>
                      		<tr>
                      			<td>Default Loan Interest Rate</td>
                      			<td><?=$model->ProfileLoansInterest;?></td>
                      		</tr>
                      		<tr>
                      			<td>Default Savings Interest Rate</td>
                      			<td><?=$model->ProfileSavingsInterest;?></td>
                      		</tr>
                      		<tr>
                      			<td>Total Savings Balance</td>
                      			<td><?=$model->ProfileSavingsBalance;?></td>
                      		</tr>
                      		<tr>
                      			<td>Outstanding Loan Balance</td>
                      			<td><?=$model->ProfileOutstandingLoanBalance;?></td>
                      		</tr>
							<tr>
                      			<td>Email Alerts</td>
                      			<td><strong><?=ProfileEngine::getActiveProfileAccountSettingByType($model->id,'EMAIL_ALERTS');?></strong></td>
                      		</tr>
							<tr>
                      			<td>SMS Alerts</td>
                      			<td><strong><?=ProfileEngine::getActiveProfileAccountSettingByType($model->id,'SMS_ALERTS');?></strong></td>
                      		</tr>
                      	</table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        <!--LOAN DETAILS TABULATED-->
        <div class="card card-stats card-raised">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
				        <div class="card" data-color="blue">
				                <div class="card-header text-center" data-background-color="blue">
			                          <ul class="nav nav-pills">
									  	<li class="nav-item">
			                                  <a class="nav-link" href="#contacts" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="contacts" aria-selected="false">
			                                    <i class="now-ui-icons ui-1_calendar-60"></i>
			                                    Contacts
			                                  </a>
			                              </li>
										  <li class="nav-item">
			                                  <a class="nav-link" href="#employments" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="employments" aria-selected="false">
			                                    <i class="now-ui-icons business_badge"></i>
			                                    Employments
			                                  </a>
			                              </li>
			                              <li class="nav-item">
			                                  <a class="nav-link" href="#next_of_kin" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="next_of_kin" aria-selected="false">
			                                    <i class="now-ui-icons users_circle-08"></i>
			                                    Next of Kin
			                                  </a>
			                              </li>
			                              <li class="nav-item">
			                                  <a class="nav-link" href="#referee" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="referee" aria-selected="false">
			                                    <i class="now-ui-icons design_vector"></i>
			                                    Referee
			                                  </a>
			                              </li>
										  <li class="nav-item">
			                                  <a class="nav-link" href="#residences" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="residences" aria-selected="false">
			                                    <i class="now-ui-icons shopping_tag-content"></i>
			                                    Residences
			                                  </a>
			                              </li>
										  <li class="nav-item">
			                                  <a class="nav-link" href="#configs" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="configs" aria-selected="false">
			                                    <i class="now-ui-icons ui-1_settings-gear-63"></i>
			                                    Settings
			                                  </a>
			                              </li>
			                              <li class="nav-item">
			                                  <a class="nav-link" href="#loans" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="loans" aria-selected="false">
			                                    <i class="now-ui-icons files_single-copy-04"></i>
			                                    Loans
			                                  </a>
			                              </li>
			                              <li class="nav-item">
			                                  <a class="nav-link" href="#savings" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="savings" aria-selected="false">
			                                    <i class="now-ui-icons business_bank"></i>
			                                    Savings
			                                  </a>
			                              </li>
										  <li class="nav-item">
			                                  <a class="nav-link" href="#sms" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="savings" aria-selected="false">
											  <i class="now-ui-icons ui-1_send"></i>
			                                    SMS
			                                  </a>
			                              </li>
			                          </ul>
				                </div>

				                <div class="card-body">
				                    <div class="tab-content">
									<div class="tab-pane active" id="contacts">
				                            <h4 class="info-text"> Contact Details </h4>
											<hr class="common_rule">
				                            <div class="row">
				                          		<?php if(Navigation::checkIfAuthorized(8) ==1):?>
					                          		<div class="col-md-6 col-lg-6 col-sm-12">
						                          		<a href="<?=Yii::app()->createUrl('profiles/addContact/'.$model->id);?>" class="btn btn-success">
						                          			Add Contact
						                          		</a>
						                          	</div>
						                          <?php else:?>
						                         	<div class="col-md-6 col-lg-6 col-sm-12">
						                          	</div>
						                        <?php endif;?>
				                            	<div class="col-md-12 col-lg-12 col-sm-12">
													<hr class="common_rule">
				                            		<table class="table table-condensed table-striped" style="font-size:12px !important;">
				                            			<thead>
				                            				<th>#</th>
				                            				<th>Contact</th>
				                            				<th>Type</th>
				                            				<th>Primary</th>
				                            				<th>Verification</th>
															<th>Action</th>
				                            			</thead>
				                            			<tbody>
				                            				<?php
				                            				if(!empty($contacts)){
				                            					$i=1;
				                            					foreach($contacts as $contact){
				                            						echo "<tr>";
				                            						echo "<td>$i</td>";
				                            						echo "<td>$contact->ContactValueFormatted</td>";
				                            						echo "<td>$contact->contactType</td>";
				                            						echo "<td>$contact->ContactPrimaryStatus</td>";
																	echo "<td>$contact->ContactVerificationStatus</td>";
				                            						echo "<td>";echo $contact->getAction();echo"</td>";
				                            						echo "</tr>";
				                            						$i++;
				                            					}
				                            				} ?>
				                            			</tbody>
				                            		</table>
				                            	</div>
				                            </div>
				                        </div>
										<div class="tab-pane" id="employments">
				                            <h4 class="info-text"> Employment Details</h4>
											<hr class="common_rule">
				                            <div class="row">
				                          		<?php if(Navigation::checkIfAuthorized(7) ==1):?>
					                          		<div class="col-md-6 col-lg-6 col-sm-12">
						                          		<a href="<?=Yii::app()->createUrl('profiles/addEmployment/'.$model->id);?>" class="btn btn-success">
						                          			New Employment
						                          		</a>
						                          	</div>
						                          <?php else:?>
						                         	<div class="col-md-6 col-lg-6 col-sm-12">
						                          </div>
						                        <?php endif;?>
				                            	<div class="col-md-12 col-lg-12 col-sm-12">
													<hr class="common_rule">
				                            		<table class="table table-condensed table-striped" style="font-size:12px !important;">
				                            			<thead>
				                            				<th>#</th>
				                            				<th>Employer</th>
				                            				<th>Industry</th>
															<th>Landmark</th>
				                            				<th>Town</th>
															<th>Contact</th>
															<th>Date Employed</th>
															<th>Status</th>
				                            				<th>Action</th>
				                            			</thead>
				                            			<tbody>
				                            				<?php
																if(!empty($employments)){
																	$i=1;
																	foreach($employments as $employment){
																		echo "<tr>";
																		echo "<td>$i</td>";
																		echo "<td>$employment->EmploymentEmployer</td>";
																		echo "<td>$employment->EmploymentIndustryType</td>";
																		echo "<td>$employment->EmploymentLandMark</td>";
																		echo "<td>$employment->EmploymentTown</td>";
																		echo "<td>$employment->contactPhone</td>";
																		echo "<td>$employment->EmploymentDate</td>";
																		echo "<td>$employment->EmploymentCurrentStatus</td>";
																		echo "<td>";echo $employment->getAction();echo"</td>";
																		echo "</tr>";
																		$i++;
																	}
																} 
															?>
				                            			</tbody>
				                            		</table>
				                            	</div>
				                            </div>
				                        </div>
				                        <div class="tab-pane" id="next_of_kin">
				                            <h4 class="info-text"> Next of Kin Details</h4>
											<hr class="common_rule">
				                            <div class="row">
				                          		<?php if(Navigation::checkIfAuthorized(10) ==1):?>
					                          		<div class="col-md-6 col-lg-6 col-sm-12">
						                          		<a href="<?=Yii::app()->createUrl('profiles/addKin/'.$model->id);?>" class="btn btn-success">
						                          			Add Kin
						                          		</a>
						                          	</div>
						                          <?php else:?>
						                         	<div class="col-md-6 col-lg-6 col-sm-12">
						                          </div>
						                        <?php endif;?>
				                            	<div class="col-md-12 col-lg-12 col-sm-12">
													<hr class="common_rule">
				                            		<table class="table table-condensed table-striped" style="font-size:12px !important;">
				                            			<thead>
				                            				<th>#</th>
				                            				<th>First Name</th>
				                            				<th>Last Name</th>
				                            				<th>Relation</th>
															<th>Phone Number</th>
				                            				<th>Action</th>
				                            			</thead>
				                            			<tbody>
				                            				<?php
				                            				if(!empty($kins)){
				                            					$i=1;
				                            					foreach($kins as $kin){
				                            						echo "<tr>";
				                            						echo "<td>$i</td>";
				                            						echo "<td>$kin->firstName</td>";
				                            						echo "<td>$kin->lastName</td>";
				                            						echo "<td>$kin->relation</td>";
				                            						echo "<td>$kin->phoneNumber</td>";
				                            						echo "<td>";echo $kin->getAction();echo"</td>";
				                            						echo "</tr>";
				                            						$i++;
				                            					}
				                            				} ?>
				                            			</tbody>
				                            		</table>
				                            	</div>
				                            </div>
				                        </div>
				                        <div class="tab-pane fade" id="referee">
				                            <h4 class="info-text"> Referee Details</h4>
											<hr class="common_rule">
				                            <div class="row">
				                          		<?php if(Navigation::checkIfAuthorized(11) ==1):?>
					                          		<div class="col-md-6 col-lg-6 col-sm-12">
														<a href="<?=Yii::app()->createUrl('profiles/addReferee/'.$model->id);?>" class="btn btn-success">
															Add Referee
														</a>
								                    </div>
					                          	<?php else:?>
					                          		<div class="col-md-12 col-lg-12 col-sm-12">
					                          		</div>
					                          	<?php endif;?>
				                                <div class="col-md-12 col-lg-12 col-sm-12">
														  <hr class="common_rule">
					                          			<table class="table table-condensed table-striped" style="font-size:12px !important;">
				                            			<thead>
				                            				<th>#</th>
				                            				<th>First Name</th>
				                            				<th>Last Name</th>
				                            				<th>Relation</th>
				                            				<th>Phone Number</th>
				                            				<th>Action</th>
				                            			</thead>
				                            			<tbody>
				                            				<?php
				                            				if(!empty($referees)){
				                            					$i=1;
				                            					foreach($referees as $referee){
				                            						echo "<tr>";
				                            						echo "<td>$i</td>";
				                            						echo "<td>$referee->firstName</td>";
				                            						echo "<td>$referee->lastName</td>";
				                            						echo "<td>$referee->relation</td>";
				                            						echo "<td>$referee->phoneNumber</td>";
				                            						echo "<td>";echo $referee->getAction();echo"</td>";
				                            						echo "</tr>";
				                            						$i++;
				                            					}
				                            				}?>
				                            			</tbody>
				                            		</table>
				                                </div>
				                            </div>
				                        </div>
										<div class="tab-pane fade" id="residences">
				                            <h4 class="info-text"> Residence Details</h4>
											<hr class="common_rule">
				                            <div class="row">
				                          		<?php if(Navigation::checkIfAuthorized(6) ==1):?>
					                          		<div class="col-md-6 col-lg-6 col-sm-12">
														<a href="<?=Yii::app()->createUrl('profiles/addResidence/'.$model->id);?>" class="btn btn-success">
															Add Residence
														</a>
								                    </div>
					                          	<?php else:?>
					                          		<div class="col-md-12 col-lg-12 col-sm-12">
					                          		</div>
					                          	<?php endif;?>
				                                <div class="col-md-12 col-lg-12 col-sm-12">
														  <hr class="common_rule">
					                          			<table class="table table-condensed table-striped" style="font-size:12px !important;">
				                            			<thead>
				                            				<th>#</th>
				                            				<th>Residence</th>
				                            				<th>Land Mark</th>
				                            				<th>Town</th>
															<th>Status</th>
				                            				<th>Action</th>
				                            			</thead>
				                            			<tbody>
				                            				<?php
				                            				if(!empty($residences)){
				                            					$i=1;
				                            					foreach($residences as $residence){
				                            						echo "<tr>";
				                            						echo "<td>$i</td>";
				                            						echo "<td>$residence->residence</td>";
				                            						echo "<td>$residence->landMark</td>";
				                            						echo "<td>$residence->town</td>";
																	echo "<td>$residence->ResidenceCurrentStatus</td>";
				                            						echo "<td>";echo $residence->getAction();echo"</td>";
				                            						echo "</tr>";
				                            						$i++;
				                            					}
				                            				}?>
				                            			</tbody>
				                            		</table>
				                                </div>
				                            </div>
				                        </div>
										<div class="tab-pane fade" id="configs">
				                            <h4 class="info-text"> Profile Configs</h4>
											<hr class="common_rule">
				                            <div class="row">
				                          		<?php if(Navigation::checkIfAuthorized(5) ==1):?>
					                          		<div class="col-md-6 col-lg-6 col-sm-12">
						                          		<a href="<?=Yii::app()->createUrl('profiles/addAccountSetting/'.$model->id);?>" class="btn btn-success">
						                          			New Config
						                          		</a>
						                          	</div>
						                          <?php else:?>
						                         	<div class="col-md-6 col-lg-6 col-sm-12">
						                          </div>
						                        <?php endif;?>
				                                <div class="col-md-12 col-lg-12 col-sm-12">
														<hr class="common_rule">
					                          			<table class="table table-condensed table-striped" style="font-size:12px !important;">
				                            			<thead>
				                            				<th>#</th>
				                            				<th>Type</th>
				                            				<th>Config</th>
				                            				<th>Status</th>
				                            				<th>Date</th>
				                            				<th>Action</th>
				                            			</thead>
				                            			<tbody>
				                            				<?php
				                            				if(!empty($configs)){
				                            					$i=1;
				                            					foreach($configs as $config){
				                            						echo "<tr>";
				                            						echo "<td>$i</td>";
				                            						echo "<td>$config->AccountConfigType</td>";
				                            						echo "<td>$config->AccountConfigValue</td>";
				                            						echo "<td>$config->AccountConfigStatus</td>";
				                            						echo "<td>$config->AccountConfigDate</td>";
				                            						echo "<td>";echo $config->getAction();echo"</td>";
				                            						echo "</tr>";
				                            						$i++;
				                            					}
				                            				}?>
				                            			</tbody>
				                            		</table>
				                                </div>
				                            </div>
				                        </div>
				                        <div class="tab-pane fade" id="loans">
				                            <h4 class="info-text"> Loan Accounts </h4>
											<hr class="common_rule">
				                            <div class="row">
				                            	<?php if(Navigation::checkIfAuthorized(12) == 1):?>
													<div class="col-md-6 col-lg-6 col-sm-12">
														<a href="<?=Yii::app()->createUrl('profiles/addLoanAccount/'.$model->id);?>" class="btn btn-success">Add Loan</a>
													</div>
					                          	<?php else:?>
													<div class="col-md-12 col-lg-12 col-sm-12"></div>
					                          	<?php endif;?>
					                          	 <div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll;">
														<hr class="common_rule">
					                          			<?php
														if(!empty($loans)){
															Tabulate::displayMemberLoansTable($loans);
														}else{
															echo '<div class="col-md-8 col-lg-8 col-sm-8" style="padding:10px 10px 10px 10px !important;">
																	<p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
																		<strong style="margin-left:20% !important;">NO LOAN ACCOUNTS</strong></p><br>
																	<p style="color:#f90101;font-size:1.30em;">*** THERE ARE NO LOANS FOR THIS MEMBER. ****</p>
																</div>';
														}
														?>
				                                </div>
				                            </div>
				                        </div>

				                        <div class="tab-pane fade" id="savings">
				                            <h4 class="info-text"> Account Details</h4>
											<hr class="common_rule">
				                            <div class="row">
				                            		<?php if(Navigation::checkIfAuthorized(51) == 1 && $savingAccounts == 0):?>
													<div class="col-md-6 col-lg-6 col-sm-12">
														<a href="<?=Yii::app()->createUrl('profiles/addSavingAccount/'.$model->id);?>" class="btn btn-success">New Account</a>
													</div>
													<?php else:?>
													<div class="col-md-6 col-lg-6 col-sm-12"></div>
													<?php endif;?>
													<div class="col-md-12 col-lg-12 col-sm-12">
														<?php if($savingAccounts == 0):?>
															<hr class="common_rule">
														<?php endif;?>
														<?php if(!empty($savingAccounts)):?>
															<?php Tabulate::createSavingAccountDetailsTable($savingAccounts);?>
														<?php else:?> 
															<div class="col-md-12 col-lg-12 col-sm-12" style="padding:10px 10px 10px 10px !important;">
																<p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
																	<strong style="margin-left:20% !important;">NO SAVING ACCOUNT AVAILABLE</strong></p><br>
																<p style="color:#f90101;font-size:1.30em;">*** THERE ARE NO SAVING ACCOUNTS AVAILABLE FOR THIS MEMBER. ****</p>
															</div>
														<?php endif;?>  
					                             	</div>
					                          	</div>
				                        </div>

										<div class="tab-pane fade" id="sms">
				                            <h4 class="info-text"> SMS Notifications</h4>
											<hr class="common_rule">
				                            <div class="row">
												<div class="col-md-12 col-lg-12 col-sm-12">
													<?php if(!empty($notifications)):?>
														<?php Tabulate::createNotificationsDetailsTable($notifications);?>
													<?php else:?> 
														<div class="col-md-12 col-lg-12 col-sm-12" style="padding:10px 10px 10px 10px !important;">
															<p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
																<strong style="margin-left:20% !important;">NO SMS Notifications</strong></p><br>
															<p style="color:#f90101;font-size:1.30em;">*** THERE ARE NO SMS AVAILABLE FOR THIS MEMBER. ****</p>
														</div>
													<?php endif;?>  
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
  </div>
</div>
<!--SMS TEXT Modal -->
<div class="modal fade" id="SmsProfile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:45% !important;border-radius:0px !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header" style="padding:4.5% !important;">
        <h4 class="title">Draft Message</h4>
      </div>
      <div class="modal-body" style="margin-top: -7%;">
      <br>
    <form  autocomplete="off" method="post" action="<?=Yii::app()->createUrl('profiles/sendSMSNotification/'.$model->id);?>">
      <br>
      <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
          <div class="form-group">
			<textarea class="form-control" cols="15" rows="5" name="textMessage" placeholder="Draft brief message..." required="required"></textarea>
          </div>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <div class="col-md-12 col-lg-12 col-sm-12">
          <div class="col-md-6 col-lg-6 col-sm-12">
	        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
	      </div>
        	<div class="col-md-6 col-lg-6 col-sm-12">
	        <button type="submit" class="btn btn-primary pull-right" name="send_txt_cmd">Send</button>
	      </div>
	    </div>
      </div>
    </form>
    </div>
    </div>
  </div>
</div>