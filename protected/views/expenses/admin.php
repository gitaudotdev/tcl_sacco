<?php
/* @var $this PayrollstaffController */
/* @var $model Payrollstaff */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Manage Expenditure';
$this->breadcrumbs=array(
	'Expenditure'=>array('expenses/admin'),
	'Manage'=>array('expenses/admin')
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#expenses-grid').yiiGridView('update', {
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
        <div class="card-header">
    	  <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Manage Expenses</h5>
            <hr>
          </div>
            <?php if($succesStatus === 1):?>
		    <div class="col-md-12 col-lg-12 col-sm-12">
		      <?=CommonFunctions::displayFlashMessage($successType);?>
		    </div>
		    <?php endif;?>
		    <?php if($infoStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($infoType);?>
		      </div>
		    <?php endif;?>
		    <?php if($warningStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($warningType);?>
		      </div>
		    <?php endif;?>
		    <?php if($dangerStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($dangerType);?>
		      </div>
		    <?php endif;?>
        </div>
        <div class="card-body col-md-12">
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
					<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="col-md-12 col-lg-12 col-sm-12">
							<?php if(Navigation::checkIfAuthorized(99) == 1):?>
							<a href="<?=Yii::app()->createUrl('expenses/create');?>" title='Create Expense' class="btn btn-success">New Expense</a>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<hr>
						</div>
					<?php endif;?>
					</div>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="table-responsive">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'expenses-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Expenditure Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Branch',
								'name'=>'ExpenseBranchName',
							),
							array(
								'header'=>'Staff',
								'name'=>'StaffName',
							),
							array(
								'header'=>'Name',
								'name'=>'ExpenseName',
							),
							array(
								'header'=>'Type',
								'name'=>'ExpenseTypeName',
							),
							array(
								'header'=>'Amount',
								'name'=>'ExpenseAmount',
							),
							array(
								'header'=>'Date',
								'name'=>'ExpenseDate',
							),
							array(
								'header'=>'Status',
								'name'=>'ExpenseRecurrence',
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