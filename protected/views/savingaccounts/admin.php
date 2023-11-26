<?php
$this->pageTitle=Yii::app()->name . ' - Saving Accounts Administration';
$this->breadcrumbs=array(
    'Savingaccounts'=>array('admin'),
    'Manage'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#savingaccounts-grid').yiiGridView('update', {
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

$element=Yii::app()->user->user_level;
$array=array('3','4');
?>
<div class="row">
    <div class="card">
        <div class="card-header">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	            <h5 class="title">Manage Saving Accounts</h5>
	            <hr class="common_rule">
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
        <div class="card-body col-md-12 col-lg-12 col-sm-12">
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
					<div class="col-md-12 col-lg-12 col-sm-12">
						<?php if(Navigation::checkIfAuthorized(51) == 1):?>
							<a href="<?=Yii::app()->createUrl('savingaccounts/create');?>" title='Create Saving Account' class="btn btn-success"> New Account</a>
							<hr class="common_rule">
						<?php endif;?>
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'savingaccounts-grid',
							'type'=>'condensed striped',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No Accounts Found',
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
									'header'=>'Branch',
									'name'=>'SavingAccountHolderBranch',
								),
								array(
									'header'=>'Relation Manager',
									'name'=>'SavingAccountHolderRelationManager',
								),
								array(
									'header'=>'Account #',
									'name'=>'SavingAccountNumber',
								),
								array(
									'header'=>'Rate',
									'name'=>'AccountInterestRate',
								),
								array(
									'header'=>'Balance',
									'name'=>'FormattedSavingAccountBalance',
								),
								array(
									'header'=>'Interest',
									'name'=>'FormattedSavingAccountInterestAccrued',
								),
								array(
									'header'=>'Total',
									'name'=>'FormattedSavingAccountTotal',
								),
								array(
									'header'=>'Status',
									'name'=>'AccountAuthStatus',
								),
								array(
									'header'=>'Account Actions',
									'name'=>'Action',
								),
							),
						)); ?>
				</div>
			</div>
		</div>
    </div>
</div>