<?php
$this->pageTitle=Yii::app()->name . ' -  New Comment Type';
$this->breadcrumbs=array(
	'Comment_Types'=>array('admin'),
	'Create'=>array('create')
);
?>
<div class="row">
  <div class=" col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Create Comment Type</h5>
            <hr class="common_rule">
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