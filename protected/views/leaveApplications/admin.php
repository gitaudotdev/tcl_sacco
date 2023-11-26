<?php
/* @var $this LeaveApplicationsController */
/* @var $model LeaveApplications */
$this->breadcrumbs=array(
	'Leave'=>array('admin'),
	'Requests'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#leave-applications-grid').yiiGridView('update', {
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
				<h5 class="title">Leave Requests</h5>
				<hr class="common_rule">
			</div>
		    <div class="col-lg-12 col-md-12 col-sm-12">
				<div class="search-form">
				<?php $this->renderPartial('_search',array(
					'model'=>$model,
				)); ?>
				</div><!-- search-form -->
			</div>
			<?php if(Navigation::checkIfAuthorized(182) === 1):?>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<a href="<?=Yii::app()->createUrl('leaveApplications/create');?>" title='Request' class="btn btn-success pull-left">New Request</a>
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<hr class="common_rule">
				</div>
			<?php endif;?>
			<div class="table-responsive">
			<?php $this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'leave-applications-grid',
				'type'=>'condensed striped',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'filterPosition'=>'none',
				'emptyText'=>'No Leave Requests Found',
				'columns'=>array(
					array(
						'header'=>'#',
						'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
					),
					array(
						'header'=>'Staff Member',
						'name'=>'ApplicationStaffName',
					),
					array(
						'header'=>'Branch',
						'name'=>'ApplicationStaffBranch',
					),
					array(
						'header'=>'Phone',
						'name'=>'ApplicationStaffPhone',
					),
					array(
						'header'=>'Email',
						'name'=>'ApplicationStaffEmail',
					),
					array(
						'header'=>'Start Date',
						'name'=>'LeaveStartOn',
					),
					array(
						'header'=>'End Date',
						'name'=>'LeaveEndOn',
					),
					array(
						'header'=>'Status',
						'name'=>'ApplicationStatus',
					),
					array(
						'header'=>'Authorized By',
						'name'=>'ApplicationAuthorizedByName',
					),
					array(
						'header'=>'Date Applied',
						'name'=>'LeaveCreatedAt',
					),
					array(
					'header'=>'Actions',
					'name'=>'Action',
					),
				),
			));?>
			<br><br>
				</div>
			</div>
  </div>
</div>
