<?php
$this->pageTitle=Yii::app()->name . ' - Leave Management';
$this->breadcrumbs=array(
	'Leaves'=>array('admin'),
	'Create'=>array('create')
);
/**Flash Messages**/
$successType = 'success';
$succesStatus = CommonFunctions::checkIfFlashMessageSet($successType);
$infoType = 'info';
$infoStatus = CommonFunctions::checkIfFlashMessageSet($infoType);
$warningType = 'warning';
$warningStatus = CommonFunctions::checkIfFlashMessageSet($warningType);
$dangerType = 'danger';
$dangerStatus = CommonFunctions::checkIfFlashMessageSet($dangerType);
?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
            <?php if($succesStatus === 1):?>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <?=CommonFunctions::displayFlashMessage($successType);?>
            </div>
            <?php endif;?>
            <?php if($infoStatus === 1):?>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <?=CommonFunctions::displayFlashMessage($infoType);?>
              </div>
            <?php endif;?>
            <?php if($warningStatus === 1):?>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <?=CommonFunctions::displayFlashMessage($warningType);?>
              </div>
            <?php endif;?>
            <?php if($dangerStatus === 1):?>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <?=CommonFunctions::displayFlashMessage($dangerType);?>
              </div>
            <?php endif;?>
        <div class="card-body">
            <div class="card-header">
              <h5 class="title">Create Staff Leave</h5>
              <hr class="common_rule">
            </div>
        	<div class="col-md-12">
	        	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	        </div>
        </div>
     </div>
  </div>