<?php
$this->pageTitle=Yii::app()->name . ' - Chamas';
$this->breadcrumbs=array(
	'Chamas' => array('admin'),
	'Administration' => array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#chamas-grid').yiiGridView('update', {
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
            <div class="col-lg-12 col-md-12 col-sm-12">
            	<h5 class="title">Manage Chamas</h5>
            	<hr class="common_rule">
	          </div>
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
        </div>
        <div class="card-body">
        	  	<div class="col-md-12 col-lg-12 col-sm-12">
        	  		 <br>
							<div class="search-form">
							<?php $this->renderPartial('_search',array('model'=>$model)); ?>
							</div><!-- search-form -->
							</div>
							<?php if(Navigation::checkIfAuthorized(134) === 1):?>
							<div class="col-md-12 col-lg-12 col-sm-12">
								<a href="<?=Yii::app()->createUrl('chamas/create');?>" title='Create Chama' class="btn btn-success pull-left"> New Chama</a>
							</div>
							<div class="col-md-12 col-lg-12 col-sm-12">
								<hr class="common_rule">
							</div>
							<?php endif;?>
							<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="table-responsive">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'             => 'chamas-grid',
								'type'           => 'condensed striped',
								'dataProvider'   => $model->search(),
								'filter'         => $model,
								'filterPosition' => 'none',
								'emptyText'      => 'No Chamas Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Branch',
										'name'=>'ChamaBranch',
									),
									array(
										'header'=>'Chama Manager',
										'name'=>'GroupCollectorName',
									),
									array(
										'header'=>'Name',
										'name'=>'ChamaName',
									),
									array(
										'header'=>'Leader',
										'name'=>'GroupLeaderName',
									),
									array(
										'header'=>'Organization',
										'name'=>'ChamaOrganization',
									),
									array(
										'header'=>'Location',
										'name'=>'ChamaLocation',
									),
									array(
										'header'=>'Total Members',
										'name'=>'ChamaMembershipCount',
									),
									array(
										'header'=>'Status',
										'name'=>'ChamaStatus',
									),
									array(
										'header'=>'Actions',
										'name'=>'Action',
									),
								),
							)); ?>
					</div>
					<br><br>
				</div>
			</div>
      </div>
    </div>
</div>