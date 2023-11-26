<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Member Next of Kin';
$this->breadcrumbs=array(
	'Member'=>array('borrower/'.$model->borrower_id),
	'NextOfKin'=>array('borrower/kin/'.$model->borrower_id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Next of Kin</h5>
            <hr>
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
        	<form method="post">
        		<input type="hidden" name="user_id" value="<?=$model->user_id;?>">
						<br>
						<div class="row">
					        <div class="col-md-6 col-lg-6 col-sm-12">
					            <div class="form-group">
									<input class="form-control" type="text"  name="first_name" placeholder="First Name" required="required">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
					    	<div class="col-md-6 col-lg-6 col-sm-12">
					        	<div class="form-group">
										<input class="form-control" type="text" name="last_name" required="required" placeholder="Last Name">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
					    	<div class="col-md-6 col-lg-6 col-sm-12">
					        	<div class="form-group">
										<input class="form-control" type="text" name="birth_date" required="required" placeholder="Date of Birth" id="start_date">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
					    	<div class="col-md-6 col-lg-6 col-sm-12">
					        	<div class="form-group">
										<input class="form-control" type="text" name="phone" required="required" placeholder="Phone Number">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
					    	<div class="col-md-6 col-lg-6 col-sm-12">
					        	<div class="form-group">
										<input class="form-control" type="text" name="relation" required="required" placeholder="Relation">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
					   		<div class="col-md-6 col-lg-6 col-sm-12">
					       		<div class="form-group">
									<input type="submit" class="btn btn-primary" value="Create Record" id="add_kin_cmd" name="add_kin_cmd">
								</div>
							</div>
							<div class="col-md-6 col-lg-6 col-sm-12">
				       	<div class="form-group">
										<a href="<?=Yii::app()->createUrl('borrower/'.$model->borrower_id);?>" type="submit" class="btn btn-default pull-right">Cancel Action</a>
								</div>
							</div>
						</div>
        		</form>
	        </div>
        </div>
     </div>
  </div>