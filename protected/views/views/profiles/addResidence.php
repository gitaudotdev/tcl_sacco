<?php
$this->pageTitle=Yii::app()->name . ' - Add Profile Residence';
$this->breadcrumbs=array(
    'Profiles'=>array('admin'),
    'View'=>array('profiles/'.$model->id),
    'Add_Residence'=>array('profiles/addResidence/'.$model->id),
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
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats card-raised modified_card">
	        	<div class=" col-md-12 col-lg-12 col-sm-12">
		        	<h4 class="info-text" style="font-weight:bold;">&emsp;<?=$model->getProfileFullName();?> Profile: New Residence</h4>
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
                                    <label>Residence</label>
                                    <input type="text" name="residence" class="form-control" required="required" placeholder="Name of Residence"/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-8 col-lg-8 col-sm-12">
                                <div class="form-group">
                                    <label>Landmark</label>
                                    <input type="text" name="landMark" class="form-control" required="required" placeholder="Residence Landmark"/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-8 col-lg-8 col-sm-12">
                                <div class="form-group">
                                    <label>Town</label>
                                    <input type="text" name="town" class="form-control" required="required" placeholder="Residence Town"/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <a href="<?=Yii::app()->createUrl('profiles/'.$model->id);?>" class="btn btn-default pull-left">Cancel</a>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <input type="submit" name="addProfileResidenceCmd" class="btn btn-primary pull-right" value="Create">
                                </div>
                            </div>
                        </div>
                        </div>
                    </form>
            </div>
        </div>
  </div>
</div>