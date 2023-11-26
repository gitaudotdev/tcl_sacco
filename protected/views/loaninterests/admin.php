<?php
$this->pageTitle=Yii::app()->name . ' - Accrued Loan Interests';
$this->breadcrumbs=array(
	'Accrued_Interests'=>array('admin')
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#loaninterests-grid').yiiGridView('update', {
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
						<h5 class="title">Manage Loan Accrued Interests</h5>
						<hr class="common_rule">
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<br>
						<div class="search-form">
						<?php $this->renderPartial('_search',array(
							'model'=>$model,
						)); ?>
						</div><!-- search-form -->
					</div>
					<?php if(Navigation::checkIfAuthorized(126) == 1):?>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('loaninterests/create');?>" title='Create Accrued Interest' class="btn btn-success pull-left">Accrue Interest</a>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<hr class="common_rule">
						</div>
					<?php endif?>
					<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="table-responsive" style="overflow-x: scroll !important;">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'loaninterests-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Interests Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Member',
								'name'=>'MemberFullName',
							),
							array(
								'header'=>'Branch',
								'name'=>'MemberBranchName',
							),
							array(
								'header'=>'Relation Manager',
								'name'=>'RelationshipManagerName',
							),
							array(
								'header'=>'Account Number',
								'name'=>'AccountNumber',
							),
							array(
								'header'=>'Interest Accrued',
								'name'=>'InterestAccrued',
							),
							array(
								'header'=>'Date Accrued',
								'name'=>'DateAccrued',
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

