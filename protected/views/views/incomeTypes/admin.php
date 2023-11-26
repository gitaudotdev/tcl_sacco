
<?php
/* @var $this PayrollstaffController */
/* @var $model Payrollstaff */
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Expense Types';
$this->breadcrumbs=array(
	'Settings'=>array('dashboard/admin'),
	'incomeTypes'=>array('incomeTypes/admin')
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#income-types-grid').yiiGridView('update', {
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
            <h5 class="title">Manage Income Types</h5>
            <hr>
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
        <div class="card-body col-md-12">
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
					<div class="col-md-12 col-lg-12 col-sm-12">
					<a href="<?=Yii::app()->createUrl('incomeTypes/create');?>" title='Create Expense Type' class="btn btn-success pull-left"> New Income Type</a>
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<hr>
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="table-responsive">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'income-types-grid',
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
								'header'=>'Type Name',
								'name'=>'IncomeTypeName',
							),
							array(
								'header'=>'Income Type Actions',
								'name'=>'Action',
							),
						),
					)); ?>
				</div>
				</div>
			</div>
    </div>
</div>