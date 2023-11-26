<?php
$this->pageTitle=Yii::app()->name . ' - Update Authorization';
$this->breadcrumbs=array(
	'Authorizations'=>array('admin'),
	'Update'=>array('auths/update/'.$model->id)
);
/**Flash Messages**/
$successType = 'success';
$succesStatus = CommonFunctions::checkIfFlashMessageSet($successType);
$infoType    = 'info';
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
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h5 class="title">Update Authorization</h5>
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
        </div>
        <div class="card-body">
        	<div class="col-md-11 col-lg-11 col-sm-12">
				<div class="col-md-4 col-lg-4 col-sm-12">
					<h5 class="title">Member Details</h5><br>
					<table class="table table-bordered table-hover">
						<tr>
							<td>Member</td>
							<td><strong><?=$model->AuthProfile->ProfileFullName;?></strong></td>
						</tr>
						<tr>
							<td>Branch</td>
							<td><strong><?=$model->AuthProfile->ProfileBranch;?></strong></td>
						</tr>
						<tr>
							<td>Relation Manager</td>
							<td><strong><?=$model->AuthProfile->ProfileManager;?></strong></td>
						</tr>
						<tr>
							<td>Profile Type</td>
							<td><strong><?=$model->AuthProfile->ProfileType;?></strong></td>
						</tr>
						<tr>
							<td>Username</td>
							<td><strong><?=$model->AuthProfile->ProfileUsername;?></strong></td>
						</tr>
						<tr>
							<td>Authorization</td>
							<td><strong><?=$model->AuthProfile->ProfileAuthStatus;?></strong></td>
						</tr>
						<tr>
							<td>Last Logged On</td>
							<td><strong><?=$model->AuthProfile->ProfileLastLoggedAt;?></strong></td>
						</tr>
						<tr>
							<td>Account Status</td>
							<td><strong><?=$model->AuthProfile->ProfileAccountStatus;?></strong></td>
						</tr>
						<tr>
							<td>Password Expires In</td>
							<td><strong><?=User::calculateDaysToPasswordExpiry($model->profileId);?> Days</strong></td>
						</tr>
					</table><br>
				</div>
			</div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
				<hr class="common_rule"/>
			</div>
        	<div class="col-md-11 col-lg-11 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>