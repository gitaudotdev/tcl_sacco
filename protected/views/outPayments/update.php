<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Update Supplier Payment';
$this->breadcrumbs=array(
	'Supplier_Payments'=>array('admin'),
	'Update'=>array('outPayments/update/'.$model->id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
           <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Supplier Payment</h5>
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
</div>