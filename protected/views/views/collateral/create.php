<?php
/* @var $this BranchController */
/* @var $model Branch */
$this->pageTitle=Yii::app()->name . ' - Microfinance : New Collateral';
$this->breadcrumbs=array(
	'Collateral'=>array('collateral/admin'),
	'Create'=>array('collateral/create')
);
?>
<div class="row">
  <div class=" col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Create Collateral</h5>
            <hr>
          </div>
        </div>
        <div class="card-body">
        	<div class=" col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
  

