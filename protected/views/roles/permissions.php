<?php
/* @var $this RolesController */
/* @var $model Roles */
$this->pageTitle=Yii::app()->name . ' -  Role Permissions';
$this->breadcrumbs=array(
	'Roles'=>array('admin'),
	'Permissions'=>array('roles/permissions/'.$id),
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
	.row_place,.roles_content{
		margin-bottom: 5% !important;
		margin-top:3% !important;
	}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <?php if($succesStatus === 1):?>
            <div class="ccol-md-12 col-lg-12 col-sm-12">
              <?=CommonFunctions::displayFlashMessage($successType);?>
            </div>
            <?php endif;?>
            <?php if($infoStatus === 1):?>
              <div class="ccol-md-12 col-lg-12 col-sm-12">
                <?=CommonFunctions::displayFlashMessage($infoType);?>
              </div>
            <?php endif;?>
            <?php if($warningStatus === 1):?>
              <div class="ccol-md-12 col-lg-12 col-sm-12">
                <?=CommonFunctions::displayFlashMessage($warningType);?>
              </div>
            <?php endif;?>
            <?php if($dangerStatus === 1):?>
              <div class="ccol-md-12 col-lg-12 col-sm-12">
                <?=CommonFunctions::displayFlashMessage($dangerType);?>
              </div>
            <?php endif;?>
        <div class="card-body">
          <div class="card-header">
              <h5 class="title">Assigned Permissions</h5>
              <hr class="common_rule">
          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <h4 class="title">Select Permissions To Remove</h4>
            <hr class="common_rule">
            <form method="post" action="<?=Yii::app()->createUrl('roles/removePermissions');?>">
              <input type="hidden" name="role" value="<?=$id;?>">
          		<div class="row_place row">
                <?php $i=1;?>
                <?php foreach(Permission::getAllPermissionsCategories() AS $category):?>
                  <div class="card col-md-12 col-lg-12 col-sm-12">
                      <div class="card-header" id="heading<?php echo $i; ?>" style="margin-top:-1.5% !important;">
                        <h4>
                          <button style="color:#000 !important; font-size:16px !important;" class="btn btn-link <?php if($i>1) echo "collapsed"; ?>" type="button" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="<?php echo ($i==1) ? 'true': 'false'; ?>" aria-controls="collapse<?php echo $i; ?>">
                              <?=$category->PermissionCategoryName;?>
                          </button>
                        </h4><hr style="margin-top: -1.5% !important;" class="common_rule">
                      </div>
                      <div id="collapse<?php echo $i; ?>" class="collapse <?php if($i==1) echo 'show'; ?>" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordionExample" style="margin-top: -1.5% !important;margin-left: 1.4% !important;margin-bottom: 10% !important;">
                          <div class="card-body" style="margin-left: -2% !important;">
                             <br>
                             <?php Permission::displayCategoryPermissionsHTMLContent(Permission::getAllRoleCategoryPermissions($id,$category->category));?>
                             <br><br><br>
                          </div>
                      </div>
                  </div> 
                  <?php $i++;?>
                <?php endforeach;?>
          		</div>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    	<a href="<?=Yii::app()->createUrl('roles/admin');?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Previous</a>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Remove Permissions" name="remove_cmd">
                  </div>
                </div>
              </div>
            </form>
	        </div>
        </div>
     </div>
  </div>
</div>