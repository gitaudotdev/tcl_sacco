<?php
/* @var $this LeavesController */
/* @var $model Leaves */
$this->pageTitle=Yii::app()->name . ' - Staff Leave Details';
$this->breadcrumbs=array(
	'Leave'=>array('admin'),
	'Records'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#leaves-grid').yiiGridView('update', {
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
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
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
        <div class="card-body">
        	  <div class="card-header">
            	<h5 class="title">Leave Details</h5>
            	<hr class="common_rule">
	          </div>
        	  <div class="col-md-12 col-lg-12 col-sm-12">
				<div class="search-form">
				<?php $this->renderPartial('_search',array(
					'model'=>$model,
				)); ?>
				</div><!-- search-form -->
			</div>
        	  <div class="col-md-12 col-lg-12 col-sm-12">
							<?php if(Navigation::checkIfAuthorized(185) === 1):?>
								<div class="col-md-6 col-lg-6 col-sm-12">
									<a href="<?=Yii::app()->createUrl('leaves/create');?>" title='Create New Record' class="btn btn-success pull-left">New Record</a>
								</div>
							<?php endif;?>
							</div>
							<div class="col-md-12 col-lg-12 col-sm-12">
								<hr class="common_rule">
							</div>
							<div class="table-responsive">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'leaves-grid',
								'type'=>'condensed striped',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Staff Leave Records Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Staff Member',
										'name'=>'LeaveStaffName',
									),
									array(
										'header'=>'Branch',
										'name'=>'LeaveStaffBranch',
									),
									array(
										'header'=>'Phone',
										'name'=>'LeaveStaffPhone',
									),
									array(
										'header'=>'Email',
										'name'=>'LeaveStaffEmail',
									),
									array(
										'header'=>'Annual Leave Days',
										'value'=>'$data->leave_days',
									),
									array(
										'header'=>'Leave Balance',
										'name'=>'StaffLeaveBalance',
									),
									array(
									'header'=>'Actions',
									'name'=>'Action',
									),
								),
							)); ?>
							<br><br>
				</div>
			</div>
  </div>
</div>
