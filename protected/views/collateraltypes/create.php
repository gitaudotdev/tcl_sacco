<?php
/* @var $this CollateraltypesController */
/* @var $model Collateraltypes */
$this->pageTitle=Yii::app()->name . ' - Microfinance  Create Collateral Type';
$this->breadcrumbs=array(
	'CollateralTypes'=>array('collateraltypes/admin'),
	'Create'=>array('collateraltypes/create')
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Create Collateral Type</h5>
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