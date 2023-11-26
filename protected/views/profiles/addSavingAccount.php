<?php
$this->pageTitle=Yii::app()->name . ' - New Saving Account';
$this->breadcrumbs=array(
	'Member'=>array('profiles/'.$model->id),
	'SavingAccount'=>array('profiles/addSavingAccount/'.$model->id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <h5 class="title">New Saving Account</h5>
                <hr class="common_rule">
            </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
        	<form method="post">
        		<input type="hidden" name="user" value="<?=$model->id;?>">
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label>Account Holder</label>
                                <input class="form-control" type="text" value="<?=$model->ProfileFullName;?>" readonly>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label>Account Number</label>
                                <input class="form-control" type="text" value="<?=$model->ProfilePhoneNumber;?>" readonly>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-sm-12">
                            <div class="form-group">
                                <a href="<?=Yii::app()->createUrl('profiles/'.$model->id);?>" type="submit" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-12">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary pull-right" value="Submit" id="apply_loan_cmd" name="apply_loan_cmd">
                            </div>
                        </div>
                    </div>
                    <br>
        		</form>
	        </div>
        </div>
     </div>
  </div>
</div>