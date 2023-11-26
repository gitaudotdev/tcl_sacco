<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Chama Locations';
$this->breadcrumbs=array(
    'Chama_Locations'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#chama-locations-grid').yiiGridView('update', {
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
						<h5 class="title">Manage Chama Locations</h5>
						<hr class="common_rule">
					</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="search-form">
							<?php $this->renderPartial('_search',array(
								'model'=>$model,
							)); ?>
							</div><!-- search-form -->
						</div>
					    <?php if(Navigation::checkIfAuthorized(290) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('chamaLocations/create');?>" title='Create Location' class="btn btn-success pull-left"> New Location</a>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<hr class="common_rule">
						</div>
				      <?php endif;?>
							<div class="table-responsive">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'chama-locations-grid',
								'type'=>'condensed striped',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Locations Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Name',
										'value'=>'$data->name',
									),
									array(
										'header'=>'Town',
										'value'=>'$data->town',
									),
									array(
										'header'=>'Created By',
										'name'=>'CreatedByFullName',
									),
									array(
										'header'=>'Date Created',
										'name'=>'CreatedByDateFormatted',
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