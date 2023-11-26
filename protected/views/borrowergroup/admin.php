<?php
/* @var $this BorrowergroupController */
/* @var $model Borrowergroup */
$this->pageTitle=Yii::app()->name . ' - Member Groups';
$this->breadcrumbs=array(
	'Groups' => array('admin'),
	'Administration' => array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#borrowergroup-grid').yiiGridView('update', {
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
            	<h5 class="title">Manage Member Groups</h5>
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
							<?php $this->renderPartial('_search',array(
								'model'=>$model,
							)); ?>
							</div><!-- search-form -->
							</div>
							<?php if(Navigation::checkIfAuthorized(134) === 1):?>
							<div class="col-md-12 col-lg-12 col-sm-12">
								<a href="<?=Yii::app()->createUrl('borrowergroup/create');?>" title='Create Member Group' class="btn btn-success pull-left"> New Group</a>
							</div>
							<div class="col-md-12 col-lg-12 col-sm-12">
								<hr class="common_rule">
							</div>
							<?php endif;?>
							<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="table-responsive">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'borrowergroup-grid',
								'type'=>'condensed striped',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Groups Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Group Name',
										'value'=>'$data->name',
									),
									array(
										'header'=>'Group Leader',
										'name'=>'GroupLeaderName',
									),
									array(
										'header'=>'Collector',
										'name'=>'GroupCollectorName',
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
