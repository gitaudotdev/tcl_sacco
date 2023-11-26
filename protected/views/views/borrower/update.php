<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Update Member Details';
$this->breadcrumbs=array(
	'Members'=>array('admin'),
	'Update',
);
?>
<div class="row">
  <div class=" col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Member</h5>
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
</div>