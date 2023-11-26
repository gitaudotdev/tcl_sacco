<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Update Income Type';
$this->breadcrumbs=array(
	'IncomeTypes'=>array('incomeTypes/admin'),
	'Update'=>array('incomeTypes/update/'.$model->incometype_id)
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Income Type</h5>
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
</div>