<?php
/* @var $this RolesController */
/* @var $model Roles */
$this->pageTitle=Yii::app()->name .' -  User Role Assigment';
$this->breadcrumbs=array(
	'Profiles'=>array('profiles/admin'),
	'Role' =>array('profiles/assign/'.$id),
	'Assign'=>array('profiles/assign/'.$id)
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
					<h5 class="title">Select Role to Assign</h5>
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
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
        		<form method="post" action="<?=Yii::app()->createUrl('profiles/assignCommit');?>">
        			<input type="hidden" name="user" value="<?=$id;?>">
        			<br>
        			<div class="row">
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
								<input type="submit" class="btn btn-primary" value="Assign Role" name="assign_role_cmd">
							</div>
						</div>
					</div>
					<br>
				</form>
	        </div>
     </div>
  </div>
</div>