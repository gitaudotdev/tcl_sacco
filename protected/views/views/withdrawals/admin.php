<?php
$this->pageTitle=Yii::app()->name . ' - Withdrawal Requests';
$this->breadcrumbs=array(
    'Withdrawals'=>array('admin'),
    'Manage'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#withdrawals-grid').yiiGridView('update',{
			data: $(this).serialize()
		});
		return false;
	});
");
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
						<h5 class="title">Manage Withdrawal Requests</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array('model'=>$model));?>
					</div><!-- search-form -->
					<div class="table-responsive">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'withdrawals-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Requests Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Branch',
								'name'=>'RequestBranch',
							),
							array(
								'header'=>'Client',
								'name'=>'RequestAccountHolder',
							),
							array(
								'header'=>'Initiator',
								'name'=>'RequestBy',
							),
							array(
								'header'=>'Account #',
								'name'=>'RequestAccountNumber',
							),
							array(
								'header'=>'Amount',
								'name'=>'RequestAmount',
							),
							array(
								'header'=>'Type',
								'name'=>'RequestType',
							),
							array(
								'header'=>'Authorizer',
								'name'=>'RequestAuthorizedBy',
							),
							array(
								'header'=>'Status',
								'name'=>'RequestStatus',
							),
							array(
								'header'=>'Date Created',
								'name'=>'RequestDate',
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