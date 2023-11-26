<?php
$this->pageTitle=Yii::app()->name . ' -  Manage Audit Trail';
$this->breadcrumbs=array(
  'Audit'=>array('admin'),
  'Trail'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#logs-grid').yiiGridView('update', {
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
						<h5 class="title">Audit Trail</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array('model'=>$model));?>
					</div><!-- search-form -->
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'logs-grid',
							'type'=>'bordered hover',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No Activity Logs Found',
							'columns'=>array(
								array(
									'header'=>'#',
									'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
								),
								array(
									'header'=>'Member',
									'name'=>'LoggedUser',
								),
								array(
									'header'=>'Branch',
									'name'=>'LoggedBranch',
								),
								array(
									'header'=>'Manager',
									'name'=>'LoggedUserRelationManager',
								),
								array(
									'header'=>'Activity',
									'name'=>'LoggedActivity',
								),
								array(
									'header'=>'Date',
									'name'=>'DateLogged',
								),
								array(
									'header'=>'Severity',
									'name'=>'ActivitySeverity',
								),
							),
						)); ?>
					</div><br><br>
				</div>
			</div>
    </div>
</div>
