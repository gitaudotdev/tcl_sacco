<?php
$this->pageTitle=Yii::app()->name . ' - Add Profile Contact';
$this->breadcrumbs=array(
    'Profiles'=>array('admin'),
    'View'=>array('profiles/'.$model->id),
    'Add_Contact'=>array('profiles/addContact/'.$model->id),
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
		        	<h4 class="info-text" style="font-weight:bold;">&emsp;<?=$model->getProfileFullName();?> Profile: New Contact</h4>
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
                    <form method="POST">
                        <div class="col-md-8 col-lg-8 col-sm-12">
                        <div class="row">
                            <div class="col-md-8 col-lg-8 col-sm-12">
                            <div class="form-group">
                                <label>Contact Type</label>
                                <select name="contactType" class="selectpicker form-control" required="required">
                                    <option value="">-- SELECT CONTACT TYPE --</option>
                                    <option value="PHONE"> Phone Number</option>
                                    <option value="EMAIL"> Email Address</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-8 col-lg-8 col-sm-12">
                            <div class="form-group">
                                <label>Contact Value</label>
                                <input type="text" name="contactValue" class="form-control" required="required"/>
                            </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <input type="submit" name="addProfileContactCmd" class="btn btn-primary" value="Create">
                            </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <a href="<?=Yii::app()->createUrl('profiles/'.$model->id);?>" class="btn btn-default pull-right">Cancel</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </form>
            </div>
        </div>
  </div>
</div>