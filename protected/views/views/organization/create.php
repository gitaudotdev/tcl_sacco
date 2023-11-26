<?php
/* @var $this OrganizationController */
/* @var $model Organization */
$this->breadcrumbs=array(
	'Organization'=>array('admin'),
	'Create'=>array('organization/create')
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5 class="title">Create Organization</h5>
            <hr class="common_rule">
        </div>
        <div class="card-body">
        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
    </div>
  </div>
</div>

