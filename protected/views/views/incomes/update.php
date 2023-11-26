<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Update Income';
$this->breadcrumbs=array(
	'Incomes'=>array('incomes/admin'),
	'Update'=>array('incomes/update/'.$model->income_id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Income</h5>
            <hr>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>