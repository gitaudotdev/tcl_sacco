<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Update Saving Product';
$this->breadcrumbs=array(
	'Savingproducts'=>array('savingproducts/admin'),
	'Update'=>array('savingproducts/update/'.$model->savingproduct_id)
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5 class="title">Update Saving Product</h5>
            <hr>
        </div>
        <div class="card-body">
        	<div class="col-md-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>