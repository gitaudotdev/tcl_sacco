<?php
/* @var $this CollateraltypesController */
/* @var $model Collateraltypes */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Update Collateral Type';
$this->breadcrumbs=array(
	'CollateralTypes'=>array('admin'),
	'Update'=>array('collateraltypes/update/'.$model->collateralType_id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Collateral Type</h5>
            <hr>
          </div>
        </div>
        <div class="card-body">
        	<br>
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>