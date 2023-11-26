<?php
$this->pageTitle=Yii::app()->name . ' -  Update Chama Organization';
$this->breadcrumbs=array(
	'Chama_Organizations'=>array('admin'),
	'Update',
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-body">
          <div class="card-header">
              <h5 class="title">Update Chama Organization</h5>
              <hr class="common_rule">
          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>