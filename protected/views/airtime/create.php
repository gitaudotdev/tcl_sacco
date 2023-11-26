<?php
/* @var $this AirtimeController */
/* @var $model Airtime */
$this->breadcrumbs=array(
	'Airtime'=>array('admin'),
	'Create_Transaction'=>array('create'),
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-body">
          <div class="card-header">
              <h5 class="title">Create Artime Transaction</h5>
              <hr class="common_rule">
          </div>
          <div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
 </div>