<?php
$this->breadcrumbs=array(
	'Profile'=>array('profiles/view','id'=>$model->profileId),
	'Setting'=>array('update','id'=>$model->id),
	'Update' =>array('update','id'=>$model->id),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Config</h5>
            <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
          <div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
    </div>
</div> 