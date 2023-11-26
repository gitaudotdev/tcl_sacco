<?php
/* @var $this RolesController */
/* @var $model Roles */
$this->pageTitle=Yii::app()->name . '  Update Role';
$this->breadcrumbs=array(
	'Roles'=>array('admin'),
	'Update' => array('roles/update/'.$model->role_id),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-body">
          <div class="card-header">
              <h5 class="title">Update Role</h5>
              <hr class="common_rule">
          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
            <br><br>
	        </div>
        </div>
     </div>
  </div>
</div>