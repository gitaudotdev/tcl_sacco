<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Error Page';
$this->breadcrumbs=array(
	'Error'=>array('site/error')
);
?>
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header">
    		<p id="error_message">
	        	<?=CHtml::encode($message);?>
	        </p>
        </div>
        <div class="card-body">
    	  <div class="circles">
		      <p>404<br>
		       <small>PAGE NOT FOUND</small>
		      </p>
		      <span class="circle big"></span>
		      <span class="circle med"></span>
		      <span class="circle small"></span>
		    </div>
        </div>
     </div>
  </div>
</div>