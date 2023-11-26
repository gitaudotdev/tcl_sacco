<?php
/* @var $this NoticesController */
/* @var $model Notices */
$this->pageTitle=Yii::app()->name . ' -  System Notices';
$this->breadcrumbs=array(
  'Notices'=>array('notices/admin'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#notices-grid').yiiGridView('update', {
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
        <div class="card-body col-lg-12 col-md-12 col-sm-12">
					<div class="card-header">
						<h5 class="title">Manage Notices</h5>
						<hr class="common_rule">
					</div>
					<div class="search-form">
					<?php $this->renderPartial('_search',array(
						'model'=>$model,
					)); ?>
					</div><!-- search-form -->
				<?php if(Navigation::checkIfAuthorized(129) == 1):?>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<a href="<?=Yii::app()->createUrl('notices/create');?>" title='Publish Notice' class="btn btn-success pull-left">Publish Notice</a>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<hr class="common_rule">
					</div>
				<?php endif?>
				<div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll !important;">
				<?php $this->widget('bootstrap.widgets.TbGridView', array(
					'id'=>'notices-grid',
					'type'=>'condensed striped',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'filterPosition'=>'none',
					'emptyText'=>'No Notices Found',
					'columns'=>array(
						array(
							'header'=>'#',
							'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
						),
						array(
							'header'=>'Author',
							'name'=>'NoticeAuthor',
						),
						array(
							'header'=>'Message',
							'name'=>'NoticeContent',
						),
						array(
							'header'=>'Level',
							'name'=>'NoticeLevel',
						),
						array(
							'header'=>'Status',
							'name'=>'NoticeStatus',
						),
						array(
							'header'=>'Date',
							'name'=>'NoticeDate',
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
