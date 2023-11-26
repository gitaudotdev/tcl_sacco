<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Savings Account Listing Report';
$this->breadcrumbs=array(
  'Savings_Report'=>array('savingAccountsReport'),
  'Listing'=>array('savingAccountsReport'),
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
            	<h5 class="title">Savings Listing</h5>
            	<hr class="common_rule">
	         </div>
	        	  <div class="search-form">
						<?php $this->renderPartial('_searchReport',array(
							'model'=>$model,
						)); ?>
					</div><!-- search-form -->
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'savingaccounts-grid',
							'type'=>'bordered',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No Savings Found',
							'columns'=>array(
								array(
									'header'=>'#',
									'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
								),
								array(
									'header'=>'Branch',
									'name'=>'SavingAccountHolderBranch',
								),
								array(
									'header'=>'Manager',
									'name'=>'SavingAccountHolderRelationManager',
								),
								array(
									'header'=>'Name',
									'name'=>'SavingAccountHolderName',
								),
								array(
									'header'=>'Contact #',
									'name'=>'SavingAccountHolderPhoneNumber',
								),
								array(
									'header'=>'Account #',
									'name'=>'SavingAccountNumber',
								),
								array(
									'header'=>'Current Principal',
									'name'=>'SavingAccountBalance',
								),
								array(
									'header'=>'Interest Accrued',
									'name'=>'SavingAccountInterestAccrued',
								),
								array(
									'header'=>'Interest Rate',
									'name'=>'AccountInterestRate',
								),
								array(
								'header'=>'Total Savings',
								'name'=>'SavingAccountTotal',
								),
							),
						)); ?>
					</div><br><br>
        </div>
      </div>
    </div>
<script type="text/javascript">
  $('#export-btn').click(function(){ 
	  $.fn.yiiGridView.update('savingaccounts-grid', {
	    data: $('.search-form form').serialize()
	  });
	  return false;
});
</script>