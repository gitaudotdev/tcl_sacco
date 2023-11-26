<?php
/* @var $this CollateralController */
/* @var $model Collateral */
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Update Loan Collateral';
$this->breadcrumbs=array(
	'Collaterals'=>array('collateral/admin'),
	'Update'=>array('collateral/update/'.$model->collateral_id)
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Collateral</h5>
            <hr>
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>