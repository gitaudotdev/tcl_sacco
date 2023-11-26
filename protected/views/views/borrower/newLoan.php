<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Member New Loan';
$this->breadcrumbs=array(
	'Member'=>array('borrower/'.$model->borrower_id),
	'LoanApplication'=>array('borrower/newLoan/'.$model->borrower_id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
  				<div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">New Loan Application</h5>
            <hr>
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
        	<form method="post">
        		<input type="hidden" name="user" value="<?=$model->user_id;?>">
						<br>
						<div class="row">
							<div class="col-md-3 col-lg-3 col-sm-12">
					        <div class="form-group">
					        	<label >Amount Applied (Digits Only)</label>
											<input class="form-control" type="text"  name="amount">
								  </div>
							</div>
					    	<div class="col-md-3 col-lg-3 col-sm-12">
					        	<div class="form-group">
					        	<label >Interest Rate</label>
										<input class="form-control" type="text" name="interest_rate" required="required">
								</div>
							</div>
							<div class="col-md-3 col-lg-3 col-sm-12">
					        	<div class="form-group">
					        	<label >Repayment Duration(Months)</label>
											<input class="form-control" type="text" name="repayment_period" required="required">
							 		</div>
							  </div>
					    	<div class="col-md-3 col-lg-3 col-sm-12">
					        	<div class="form-group">
					        	<label >Repayment Start Date</label>
									<input class="form-control" type="text" name="repayment_start_date" id="start_date" required="required">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-3 col-lg-3 col-sm-12">
					        	<div class="form-group">
					        	<label >Relationship Manager</label>
					        		<select name="rm" class="selectpicker" required="required">
												<option value="">-- RELATION MANAGERS --</option>
												<?php
													foreach($users as $user){
														echo "<option value='$user->user_id'>$user->StaffFullName</option>";
													}
												?>
											</select>
									</div>
								</div>
					    	<div class="col-md-3 col-lg-3 col-sm-12">
					        	<div class="form-group">
					        	<label >Directing Staff</label>
					        		<select name="direct_to" class="selectpicker" required="required">
										<option value="">-- DIRECTING STAFF --</option>
										<?php
											foreach($directed as $user){
												echo "<option value='$user->user_id'>$user->UserFullName</option>";
											}
										?>
									</select>
									</div>
							</div>
					    	<div class="col-md-6 col-lg-6 col-sm-12">
					        	<div class="form-group">
					        	<label >Brief Comment</label>
					        	<textarea class="form-control" name="special_comment" cols='5' rows='1' placeholder="Please provide brief comment pertaining the application..."></textarea>
									</div>
							</div>
						</div>
						<br>
						<div class="row">
					   		<div class="col-md-6 col-lg-6 col-sm-12">
				       		<div class="form-group">
										<input type="submit" class="btn btn-primary" value="Submit Application" id="apply_loan_cmd" name="apply_loan_cmd">
									</div>
							</div>
							<div class="col-md-6 col-lg-6 col-sm-12">
				       	<div class="form-group">
										<a href="<?=Yii::app()->createUrl('borrower/'.$model->borrower_id);?>" type="submit" class="btn btn-default pull-right">Cancel Action</a>
								</div>
							</div>
        		</form>
	        </div>
        </div>
     </div>
  </div>
</div>