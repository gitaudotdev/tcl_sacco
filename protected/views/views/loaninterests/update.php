<?php
$this->pageTitle=Yii::app()->name . ' -  Update Loan Accrued Interest';
$this->breadcrumbs=array(
	'Accrued_Interests'=>array('admin'),
	'Update'=>array('loaninterests/update/'.$model->id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-body">
          <div class="card-header col-md-12 col-lg-12 col-sm-12">
              <h5 class="title">Update Accrued Interest</h5>
              <hr class="common_rule">
          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>