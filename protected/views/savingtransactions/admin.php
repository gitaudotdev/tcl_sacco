<?php
$this->pageTitle=Yii::app()->name . ' - Manage Saving Transactions';
$this->breadcrumbs=array(
    'Saving_Transactions'=>array('admin'),
    'Manage'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#savingtransactions-grid').yiiGridView('update', {
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
        <div class="card-body">
        	  <div class="card-header">
	            <h5 class="title">Manage Saving Transactions</h5>
	            <hr class="common_rule">
	          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
					  <div class="search-form">
						<?php $this->renderPartial('_search',array(
							'model'=>$model,
						));?>
					</div>
					</div><!-- search-form -->
					<?php if(Navigation::checkIfAuthorized(61) === 1):?>
					<div class="col-md-12 col-lg-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('savingtransactions/create');?>" title='Create Saving Transaction' class="btn btn-success"> New Transaction</a>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<hr class="common_rule">
						</div>
					<?php endif;?>
					<div class="table-responsive">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'savingtransactions-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Saving Transactions Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Member',
								'name'=>'SavingAccountHolderName',
							),
							array(
								'header'=>'Account #',
								'name'=>'SavingAccountNumber',
							),
//							array(
//								'header'=>'Transacted Phone',
//								'name'=>'SavingTransactionPhoneNumber',
//							),
							array(
								'header'=>'Branch',
								'name'=>'SavingAccountBranch',
							),
							array(
								'header'=>'RM',
								'name'=>'SavingAccountRelationManager',
							),
							array(
								'header'=>'Amount',
								'name'=>'SavingTransactionAmount',
							),
							array(
								'header'=>'Type',
								'name'=>'SavingTransactionType',
							),
							array(
								'header'=>'Date',
								'name'=>'SavingTransactionDate',
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