<?php
/* @var $this SavingpostingsController */
/* @var $model Savingpostings */
$this->pageTitle=Yii::app()->name . ' -  Manage Saving Interest Postings';
$this->breadcrumbs=array(
    'Interest_Postings'=>array('admin'),
    'Manage'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#savingpostings-grid').yiiGridView('update', {
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
        <div class="card-body col-md-12 col-lg-12 col-sm-12">
					<div class="card-header">
						<h5 class="title">Manage Saving Interests</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
					<?php if(Navigation::checkIfAuthorized(188) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('savingpostings/create');?>" title='Create Saving Interest Posting' class="btn btn-success"> New Interest</a>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<hr class="common_rule">
						</div>
					<?php endif;?>
					<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'savingpostings-grid',
							'type'=>'condensed striped',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No Interest Postings Found',
							'columns'=>array(
								array(
									'header'=>'#',
									'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
								),
								array(
									'header'=>'Member',
									'name'=>'PostingAccountHolderName',
								),
								array(
									'header'=>'Branch',
									'name'=>'PostingBranch',
								),
								array(
									'header'=>'Relation Manager',
									'name'=>'PostingRelationManager',
								),
								array(
									'header'=>'Account #',
									'name'=>'PostingAccountNumber',
								),
								array(
									'header'=>'Interest',
									'name'=>'PostingAmount',
								),
								array(
									'header'=>'Status',
									'name'=>'PostingStatus',
								),
								array(
									'header'=>'Date Posted',
									'name'=>'PostingDate',
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
</div>