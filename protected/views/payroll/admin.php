<?php
/* @var $this PayrollController */
/* @var $model Payroll */
$this->pageTitle=Yii::app()->name . ' -  Payroll Transactions Log';

$this->breadcrumbs=array(
   'Payroll_Transactions'=>array('admin'),
   'Manage'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#payroll-grid').yiiGridView('update', {
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
					<h5 class="title">Payroll Transactions Logs</h5>
					<hr class="common_rule">
				</div>
				<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
				</div><!-- search-form -->
				<div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll !important;">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'payroll-grid',
					'type'=>'condensed striped',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Records Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Member',
							'name'=>'StaffMemberName',
						),
						array(
							'header'=>'Branch',
							'name'=>'StaffMemberBranchName',
						),
						array(
							'header'=>'Payroll Period',
							'name'=>'StaffMemberPayrollPeriod',
						),
						array(
							'header'=>'Total Loan',
							'name'=>'StaffMemberPayrollTotalLoan',
						),
						array(
							'header'=>'Net Salary',
							'name'=>'StaffMemberPayrollNetSalary',
						),
						array(
							'header'=>'Date Processed',
							'name'=>'PayrollDateProcessed',
						),
						array(
							'header'=>'Processed By',
							'name'=>'PayrollDateProcessedBy',
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