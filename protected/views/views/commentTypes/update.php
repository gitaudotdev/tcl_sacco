<?php
$this->pageTitle=Yii::app()->name . ' -  Update Comment Type';
$this->breadcrumbs=array(
	'Comment_Types'=>array('admin'),
	'Update'=>array('commentTypes/update/'.$model->id)
);
?>
<div class="row">
  <div class=" col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Comment Type</h5>
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