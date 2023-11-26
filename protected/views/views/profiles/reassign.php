<?php
/* @var $this RolesController */
/* @var $model Roles */
$this->pageTitle=Yii::app()->name . ' - Reassign Role';
$this->breadcrumbs=array(
	'Profiles'=>array('profiles/admin'),
	'Role'=>array('profiles/reassign/'.$id),
	'Reassign'=>array('profiles/reassign/'.$id)
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
    <div class="card">
        <div class="card-header">
  				<div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Select Role to Reassign</h5>
            <hr class="common_rule">
          </div>
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
        	<div class="col-md-12 col-lg-12 col-sm-12">
        		<div class="col-md-12 col-lg-12 col-sm-12" style="margin:1.5% 0% 1.5% 0%!important;">
        			<p>Current Role: <strong><?=$currentRole->name;?></strong></p>
        		</div>
        		<div class="col-md-12 col-lg-12 col-sm-12">
	        		<form method="post" action="<?=Yii::app()->createUrl('profiles/commitReassignment');?>">
	        			<input type="hidden" name="user" value="<?=$id;?>">
	        			<br>
	        			<div class="row" style="margin-bottom: 1.5% !important;">
								<?php Permission::displayRolesHTMLContent($roles);?>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12 col-lg-12 col-sm-12">
									<hr class="common_rule">
								</div>
								<div class="col-md-3 col-lg-3 col-sm-12">
									<div class="form-group">
										<a href="<?=Yii::app()->createUrl('profiles/admin');?>" class="btn btn-default">Cancel</a>
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-sm-12">
									<div class="form-group">
										<input type="submit" class="btn btn-primary" value="Reassign Role" name="reassign_role_cmd">
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
</div>