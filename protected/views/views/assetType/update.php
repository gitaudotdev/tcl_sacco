<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Update Asset Type';
$this->breadcrumbs=array(
	'Settings'=>array('dashboard/admin'),
	'AssetTypes'=>array('assetType/admin'),
	'Update'=>array('assetType/update/'.$model->asset_type_id)
);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Update Asset Type</h5>
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