<?php
$this->pageTitle=Yii::app()->name . ' - Update Loan Product';
$this->breadcrumbs=array(
	'Repayments'=>array('admin'),
	'Update'=>array('loanrepayments/update/'.$model->loanrepayment_id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-body">
          <div class="card-header">
              <h5 class="title">Update Repayment</h5>
              <hr class="common_rule">
          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>