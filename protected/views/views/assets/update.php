<?php
$this->pageTitle=Yii::app()->name . ' - Update Asset';
$this->breadcrumbs=array(
	'Assets'=>array('admin'),
	'Update'=>array('assets/update/'.$model->asset_id)
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-body">
          <div class="card-header">
              <h5 class="title">Update Asset</h5>
              <hr class="common_rule">
          </div>
          <div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>