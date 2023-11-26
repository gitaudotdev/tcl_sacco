<?php
$this->pageTitle=Yii::app()->name . ' - Write Offs Report';
$this->breadcrumbs=array(
  'WriteOff_Report'=>array('writeoffsReport'),
  'Listing'=>array('writeoffsReport'),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#write-offs-grid').yiiGridView('update', {
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
							<h5 class="title">Write Offs Listing</h5>
							<hr class="common_rule">
						</div>
	        	  		<div class="search-form">
							<?php $this->renderPartial('_searchReport',array(
								'model'=>$model,
							)); ?>
						</div><!-- search-form -->
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'write-offs-grid',
							'type'=>'bordered',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No Write Off Records Found',
							'columns'=>array(
								array(
									'header'=>'#',
									'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
								),
								array(
									'header'=>'Branch',
									'name'=>'BranchName',
								),
								array(
									'header'=>'RM',
									'name'=>'ManagerName',
								),
								array(
									'header'=>'Client',
									'name'=>'ClientName',
								),
								array(
									'header'=>'Acc #',
									'name'=>'AccountNumber',
								),
								array(
									'header'=>'Original Loan',
									'name'=>'FormattedOriginalLoanAmount',
								),
								array(
									'header'=>'Interest Rate',
									'name'=>'FormattedOriginalInterestRate',
								),
								array(
									'header'=>'Amount Written Off',
									'name'=>'WriteOffAmount',
								),
								array(
									'header'=>'Date Written Off',
									'name'=>'TransactionDate',
								),
								array(
									'header'=>'Written Off By',
									'name'=>'TransactedBy',
								),
							),
						)); ?>
					</div><br><br>
        </div>
      </div>
    </div>
<script type="text/javascript">
  $('#export-btn').click(function(){ 
	  $.fn.yiiGridView.update('write-offs-grid', {
	    data: $('.search-form form').serialize()
	  });
	  return false;
});
</script>