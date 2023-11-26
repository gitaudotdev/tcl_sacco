<?php
/* @var $this StaffController */
/* @var $model Staff */
$this->pageTitle=Yii::app()->name . ' - Staff Administration';
$this->breadcrumbs=array(
	'Staff'=>array('admin'),
  'Administration'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#staff-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
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
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
            	<h5 class="title">Staff Administration</h5>
            	<hr class="common_rule">
	          </div>
            <?php if($succesStatus === 1):?>
		    <div class="col-lg-12 col-md-12 col-sm-12">
		      <?=CommonFunctions::displayFlashMessage($successType);?>
		    </div>
		    <?php endif;?>
		    <?php if($infoStatus === 1):?>
		      <div class="col-lg-12 col-md-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($infoType);?>
		      </div>
		    <?php endif;?>
		    <?php if($warningStatus === 1):?>
		      <div class="col-lg-12 col-md-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($warningType);?>
		      </div>
		    <?php endif;?>
		    <?php if($dangerStatus === 1):?>
		      <div class="col-lg-12 col-md-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($dangerType);?>
		      </div>
		    <?php endif;?>
        </div>
        <div class="card-body">
        	  <div class="col-md-12 col-lg-12 col-sm-12">
							<div class="search-form">
							<?php $this->renderPartial('_search',array(
								'model'=>$model,
							)); ?>
							</div><!-- search-form -->
						</div>
							<div class="col-md-12 col-lg-12 col-sm-12">
								<?php if(Navigation::checkIfAuthorized(23) === 1):?>
								<div class="col-md-4 col-lg-4 col-sm-12">
									<a href="<?=Yii::app()->createUrl('staff/create');?>" title='Create Staff' class="btn btn-success">New Staff</a>
								</div>
								<?php endif;?>
								<?php if((Navigation::checkIfAuthorized(29) === 1) && ($organization->automated_payroll === 'disabled')):?>
								<div class="col-md-4 col-lg-4 col-sm-12">
									<a href="<?=Yii::app()->createUrl('staff/processPayroll');?>" title='Process Payroll' class="btn btn-warning">Process Payroll</a>
								</div>
								<?php endif;?>
								<div class="col-md-12 col-lg-12 col-sm-12">
									<hr  class="common_rule">
								</div>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="table-responsive">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'staff-grid',
								'type'=>'condensed striped',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Staff Members Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Details',
										'name'=>'StaffDetails',
									),
									array(
										'header'=>'Branch',
										'name'=>'BranchName',
									),
									array(
										'header'=>'Targets',
										'name'=>'Targets',
									),
									array(
										'header'=>'Salary',
										'name'=>'StaffSalary',
									),
									array(
									'header'=>'Actions',
									'name'=>'Action',
									),
								),
							)); ?>
						</div><br><br>
				</div>
			</div>
   </div>
</div>

