<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Member Referee';
$this->breadcrumbs=array(
	'Member'=>array('borrower/'.$model->borrower_id),
	'Referee'=>array('borrower/referee/'.$model->borrower_id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Referee</h5>
            <hr>
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
										<input class="form-control" type="text" name="phone" required="required" placeholder="0712345678">
									</div>
							</div>
						</div>
						<br>
						<div class="row">
					    	<div class="col-md-6 col-lg-6 col-sm-12">
					        	<div class="form-group">
										<input class="form-control" type="text" name="employer" required="required" placeholder="Employer/Business">
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
									<input type="submit" class="btn btn-primary" value="Create Record" id="add_referee_cmd" name="add_referee_cmd">
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