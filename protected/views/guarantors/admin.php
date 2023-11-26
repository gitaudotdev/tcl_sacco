<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Manager Account Guarantors';
$this->breadcrumbs=array(
	'Guarantors'=>array('admin'),
	'Manage'=>array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#guarantors-grid').yiiGridView('update', {
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
            <h5 class="title">Manage Guarantors</h5>
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
        <div class="card-body col-md-12 col-lg-12 col-sm-122">
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
					<?php if(Navigation::checkIfAuthorized(107) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
								<div class="col-md-12 col-lg-12 col-sm-12">
									<a href="<?=Yii::app()->createUrl('guarantors/create');?>" title='Create Guarantor' class="btn btn-success"> New Guarantor</a>
								</div>
								<div class="col-md-12 col-lg-12 col-sm-12">
									<hr>
								</div>
						</div>
					<?php endif;?>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="table-responsive">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'guarantors-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Guarantors Found',
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
								'header'=>'Relation Manager',
								'name'=>'RelationManager',
							),
							array(
								'header'=>'A/C #',
								'name'=>'LoanAccountNumber',
							),
							array(
								'header'=>'A/C Holder',
								'name'=>'AccountHolder',
							),
							array(
								'header'=>'Guarantor Name',
								'name'=>'GuarantorName',
							),
							array(
								'header'=>'ID Number',
								'name'=>'GuarantorIDNumber',
							),
							array(
								'header'=>'Phone Number',
								'name'=>'GuarantorPhoneNumber',
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