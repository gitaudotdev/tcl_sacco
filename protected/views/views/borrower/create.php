<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance Create Member';
$this->breadcrumbs=array(
	'Members'=>array('borrower/admin'),
	'Create'=>array('borrower/create')
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Create Member</h5>
            <hr>
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model,'kin'=>$kin,'referee'=>$referee,'files'=>$files)); ?>
	        </div>
        </div>
     </div>
  </div>
</div>