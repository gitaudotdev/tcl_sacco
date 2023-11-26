<?php
/* @var $this AirtimeController */
/* @var $model Airtime */

$this->breadcrumbs=array(
	'Airtimes'=>array('admin'),
	'Administration'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#airtime-grid').yiiGridView('update', {
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
						<h5 class="title">Airtime Transactions</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
					<?php if(Navigation::checkIfAuthorized(172) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
						<a href="<?=Yii::app()->createUrl('airtime/create');?>" title='Initiate Transaction' class="btn btn-success pull-left"> Initiate Transaction</a>
					</div>
					<div class="col-md-12 col-lg-12 col-sm-12">
						<hr class="common_rule">
					</div>
				<?php endif;?>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="table-responsive">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'airtime-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Transactions Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Branch',
								'name'=>'AirtimeBranchName',
							),
							array(
								'header'=>'Relation Manager',
								'name'=>'AirtimeRelationManager',
							),
							array(
								'header'=>'Member Name',
								'name'=>'AirtimeMemberName',
							),
							array(
								'header'=>'Phone Number',
								'name'=>'AirtimePhoneNumber',
							),
							array(
								'header'=>'Airtime Amount',
								'name'=>'AirtimeAmount',
							),
							array(
								'header'=>'Request Status',
								'name'=>'AirtimeRequestStatus',
							),
							array(
								'header'=>'Date Requested',
								'name'=>'AirtimeDateRequested',
							),
							array(
								'header'=>'Actions',
								'name'=>'AirtimeAction',
							),
						),
					)); ?>
				</div><br><br>
				</div>
			</div>
    </div>
</div>