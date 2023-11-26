<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : View Member Details';
$this->breadcrumbs=array(
    'Members'=>array('borrower/admin'),
    'View'=>array('borrower/'.$model->borrower_id),
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
		margin-top: 0% !important;
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
	}
	.nav-pills .nav-link.active {
	    color: #000 !important;
	    background-color: #fff !important;
	}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
  	  <!--MEMBER DETAILS-->
        <div class="card card-stats card-raised modified_card">
	        	<div class=" col-md-12 col-lg-12 col-sm-12">
		        	<h4 class="header_details" style="margin-left: 2% !important;">
		        		<?=$model->getBorrowerFullName();?> Details
		        	</h4>
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
            <div class="card-body col-md-12 col-lg-12 col-sm-12">
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                      	<table class="table table-condensed table-striped">
                      		<tr>
                      			<td>Member Name</td>
                      			<td><?=$model->getBorrowerFullName();?></td>
                      		</tr>
                      		<tr>
                      			<td>ID Number</td>
                      			<td><?=$model->id_number;?></td>
                      		</tr>
                      		<tr>
                      			<td>Age</td>
                      			<td><?=$model->getBorrowerAge();?></td>
                      		</tr>
                      		<tr>
                      			<td>Gender</td>
                      			<td><?=ucfirst($model->gender);?></td>
                      		</tr>
                      		<tr>
                      			<td>Working Status</td>
                      			<td><?=$model->getBorrowerWorkingStatus();?></td>
                      		</tr>
                      		<tr>
                      			<td>Employer/ Business</td>
                      			<td><?=$model->employer;?></td>
                      		</tr>
                      	</table>
                      </div>
                       <div class="col-md-6 col-lg-6 col-sm-12">
                      	<table class="table table-condensed table-striped">
                      		<tr>
                      			<td>Branch</td>
                      			<td><?=$model->getBranchName();?></td>
                      		</tr>
                      		<tr>
                      			<td>Relation Manager</td>
                      			<td><?=$model->getRelationManager();?></td>
                      		</tr>
                      		<tr>
                      			<td>Phone Number</td>
                      			<td><?=$model->phone;?></td>
                      		</tr>
                      		<tr>
                      			<td>Referee Name</td>
                      			<td><?=$model->referred_by;?></td>
                      		</tr>
                      		<tr>
                      			<td>Referee Phone</td>
                      			<td><?=$model->referee_phone;?></td>
                      		</tr>
                      		<tr>
                      			<td colspan="2">
                      				<a href="#" data-toggle="modal" data-target="#SMSBorrower" class="btn btn-info">Send SMS</a>
                      			</td>
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
			                                  <a class="nav-link" href="#next_of_kin" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="next_of_kin" aria-selected="false">
			                                    <i class="now-ui-icons ui-1_calendar-60"></i>
			                                    Next of Kin
			                                  </a>
			                              </li>
			                              <li class="nav-item">
			                                  <a class="nav-link" href="#referee" data-toggle="tab" data-toggle="tab" role="tab" aria-controls="referee" aria-selected="false">
			                                    <i class="now-ui-icons shopping_tag-content"></i>
			                                    Referee
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
			                                    <i class="now-ui-icons ui-2_chat-round"></i>
			                                    Savings
			                                  </a>
			                              </li>
			                          </ul>
				                </div>

				                <div class="card-body">
				                    <div class="tab-content">
				                        <div class="tab-pane active" id="next_of_kin">
				                            <h4 class="info-text"> Next of Kin </h4>
				                            <div class="row justify-content-center">
				                            	<div class="col-md-12 col-lg-12 col-sm-12">
				                          		<?php if(Yii::app()->user->user_level !== '3'):?>
					                          		<div class="col-md-6 col-lg-6 col-sm-12">
						                          		<a href="<?=Yii::app()->createUrl('borrower/kin/'.$model->borrower_id);?>" class="btn btn-success">
						                          			Add Kin
						                          		</a>
						                          	</div>
						                          <?php else:?>
						                         	<div class="col-md-6 col-lg-6 col-sm-12">
						                          </div>
						                        <?php endif;?>
				                          	</div>
				                            	<div class="col-md-12 col-lg-12 col-sm-12" style="border-top: 3px solid green;padding:20px 20px !important;">
				                            		<table class="table table-condensed table-striped" style="font-size:12px !important;">
				                            			<thead>
				                            				<th>#</th>
				                            				<th>First Name</th>
				                            				<th>Last Name</th>
				                            				<th>Relation</th>
				                            				<th>Action</th>
				                            			</thead>
				                            			<tbody>
				                            				<?php
				                            				if(!empty($kins)){
				                            					$i=1;
				                            					foreach($kins as $kin){
				                            						echo "<tr>";
				                            						echo "<td>$i</td>";
				                            						echo "<td>$kin->first_name</td>";
				                            						echo "<td>$kin->last_name</td>";
				                            						echo "<td>$kin->relation</td>";
				                            						echo "<td>";echo $kin->getAction();echo"</td>";
				                            						echo "</tr>";
				                            						$i++;
				                            					}
				                            				}?>
				                            			</tbody>
				                            		</table>
				                            	</div>
				                            </div>
				                        </div>
				                        <div class="tab-pane fade" id="referee">
				                            <h4 class="info-text"> Referees</h4>
				                            <div class="row justify-content-center">
				                            	<?php if(Yii::app()->user->user_level !== '3'):?>
				                            	<div class="col-md-12 col-lg-12 col-sm-12" style="border-bottom: 2px dotted #ddd;margin-bottom: 2% !important;padding:10px !important;">
					                          		<div class="col-md-6 col-lg-6 col-sm-12">
					                          		<a href="<?=Yii::app()->createUrl('borrower/referee/'.$model->borrower_id);?>" class="btn btn-success">
					                          			Add Referee
					                          		</a>
								                    </div>
					                          	</div>
					                          	<?php else:?>
					                          		<div class="col-md-12 col-lg-12 col-sm-12" style="border-bottom: 2px dotted #ddd;margin-bottom: 2% !important;padding:10px !important;">
					                          		</div>
					                          	<?php endif;?>
				                                <div class="col-md-12 col-lg-12 col-sm-12">
					                          			<table class="table table-condensed table-striped" style="font-size:12px !important;">
				                            			<thead>
				                            				<th>#</th>
				                            				<th>First Name</th>
				                            				<th>Last Name</th>
				                            				<th>Relation</th>
				                            				<th>Phone Number</th>
				                            				<th>Employer/Business</th>
				                            				<th>Action</th>
				                            			</thead>
				                            			<tbody>
				                            				<?php
				                            				if(!empty($referees)){
				                            					$i=1;
				                            					foreach($referees as $referee){
				                            						echo "<tr>";
				                            						echo "<td>$i</td>";
				                            						echo "<td>$referee->first_name</td>";
				                            						echo "<td>$referee->last_name</td>";
				                            						echo "<td>$referee->relation</td>";
				                            						echo "<td>$referee->phone</td>";
				                            						echo "<td>$referee->employer</td>";
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
				                        <div class="tab-pane fade" id="loans">
				                            <h4 class="info-text"> Loan Accounts </h4>
				                            <div class="row justify-content-center">
				                            	<?php if(Yii::app()->user->user_level !== '3'):?>
						                            <div class="col-md-12 col-lg-12 col-sm-12" style="border-bottom: 2px dotted #ddd;margin-bottom: 2% !important;padding:10px !important;">
							                          		<div class="col-md-6 col-lg-6 col-sm-12">
								                          	<a href="<?=Yii::app()->createUrl('borrower/newLoan/'.$model->borrower_id);?>" class="btn btn-primary" style="margin-left: -0.85%;">Add Loan</a>
										                    </div>
					                          	</div>
					                          	<?php else:?>
					                          		<div class="col-md-12 col-lg-12 col-sm-12" style="border-bottom: 2px dotted #ddd;margin-bottom: 2% !important;padding:10px !important;">
					                          		</div>
					                          	<?php endif;?>
					                          	 <div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll;">
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
				                            <h4 class="info-text"> Saving Accounts</h4>
				                            <div class="row justify-content-center">
				                            	<div class="col-md-12 col-lg-12 col-sm-12" style="border-bottom: 2px dotted #ddd;margin-bottom: 2% !important;padding:10px !important;">
				                            		<?php if(Yii::app()->user->user_level !== '3'):?>
								                          		<div class="col-md-6 col-lg-6 col-sm-12">
									                          	<a href="#" class="btn btn-primary" style="margin-left: -0.85%;">Add Account</a>
											                    </div>
											                <?php else:?>
											                	<div class="col-md-6 col-lg-6 col-sm-12"></div>
											                <?php endif;?>
											                <?php if(Navigation::checkIfAuthorized(14) === 1):?>
								                    <div class="col-md-6 col-lg-6 col-sm-12">
						                          		<div class="dropdown pull-right">
								                            <button class="dropdown-toggle btn btn-info" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								                                Statement
								                            </button>
								                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								                                <a class="dropdown-item" href="#">PDF</a>
								                                <a class="dropdown-item" href="#">Excel</a>
								                                <a class="dropdown-item" href="#">Email</a>
								                            </div>
								                        </div>
								                    </div>
								                  <?php endif;?>
									              </div>
									                    <div class="col-md-12 col-lg-12 col-sm-12">
						                          		<?php if(!empty($savingAccounts)):?>
										                        <?php Tabulate::createSavingAccountDetailsTable($savingAccounts);?>
										                      <?php else:?> 
										                      <div class="col-md-12 col-lg-12 col-sm-12" style="padding:10px 10px 10px 10px !important;">
										                          <p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
										                              <strong style="margin-left:20% !important;">NO SAVING ACCOUNTS AVAILABLE</strong></p><br>
										                          <p style="color:#f90101;font-size:1.30em;">*** THERE ARE NO SAVING ACCOUNTS AVAILABLE FOR THIS MEMBER. ****</p>
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
</div>
<!--SMS TEXT Modal -->
<div class="modal fade" id="SMSBorrower" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:45% !important;border-radius:0px !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center" style="padding:4.5% !important;">
        <h4 class="title">Send Text Message</h4>
      </div>
      <div class="modal-body" style="margin-top: -7%;">
      <br>
    <form  autocomplete="off" method="post" action="<?=Yii::app()->createUrl('borrower/sendSms/'.$model->borrower_id);?>">
      <br>
      <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
          <div class="form-group">
						<textarea class="form-control" cols="15" rows="15" name="textMessage" placeholder="Please provide a message to send the member..." required="required"></textarea>
          </div>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="col-md-6 col-lg-6 col-sm-12">
	         <button type="submit" class="btn btn-primary" name="send_txt_cmd">
	          Send SMS
	        </button>
	      </div>
        <div class="col-md-6 col-lg-6 col-sm-12">
	        <button type="button" class="btn btn-default" data-dismiss="modal">
	          Cancel
	        </button>
	      </div>
	      </div>
      </div>
    </form>
    </div>
    </div>
  </div>
</div>