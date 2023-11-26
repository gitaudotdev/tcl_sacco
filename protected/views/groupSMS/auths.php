<?php
$this->breadcrumbs=array(
	'Auths_Level'=> array('auths'),
	'Manage'   => array('auths'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#group-sms-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
            <?php if(CommonFunctions::checkIfFlashMessageSet('success') === 1):?>
			<div class="col-lg-12 col-md-12 col-sm-12">
				<?=CommonFunctions::displayFlashMessage('success');?>
			</div>
			<?php endif;?>
			<?php if(CommonFunctions::checkIfFlashMessageSet('info') === 1):?>
				<div class="col-lg-12 col-md-12 col-sm-12">
				<?=CommonFunctions::displayFlashMessage('info');?>
				</div>
			<?php endif;?>
			<?php if(CommonFunctions::checkIfFlashMessageSet('warning') === 1):?>
				<div class="col-lg-12 col-md-12 col-sm-12">
				<?=CommonFunctions::displayFlashMessage('warning');?>
				</div>
			<?php endif;?>
			<?php if(CommonFunctions::checkIfFlashMessageSet('danger') === 1):?>
			<div class="col-lg-12 col-md-12 col-sm-12">
			<?=CommonFunctions::displayFlashMessage('danger');?>
			</div>
			<?php endif;?>
        <div class="card-body">
				<div class="card-header">
					<h5 class="title">Auth Level SMS Administration</h5>
					<hr class="common_rule">
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="search-form">
						<?php $this->renderPartial('_search',array(
							'model'=>$model,
						)); ?>
					</div><!-- search-form -->
					<hr class="common_rule">
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<?php if(Navigation::checkIfAuthorized(305) === 1):?>
						<a href="<?=Yii::app()->createUrl('groupSMS/authsCreate');?>" title='Initiate SMS' class="btn btn-success">Initiate SMS</a>
					<?php endif;?>
					<hr class="common_rule">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'group-sms-grid',
						'type'=>'bordered condensed',
						'dataProvider'=>$model->searchAuths(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No SMS Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Branch',
								'name'=>'GroupSMSBranch',
							),
							array(
								'header'=>'Manager',
								'name'=>'GroupSMSManager',
							),
							array(
								'header'=>'Date Initiated',
								'name'=>'GroupSMSDateInitiated',
							),
							array(
								'header'=>'Message',
								'name'=>'GroupSMSMessage',
							),
							array(
								'header'=>'Status',
								'name'=>'GroupSMSStatus',
							),
							array(
								'header'=>'Initiated By',
								'name'=>'GroupSMSInitiatedBy',
							),
							array(
							    'header'=>'Actions',
							    'name'=>'AuthLevelSMSAction',
							),
						),
					)); ?>
				<br><br>
			</div>
		</div>
	</div>
  </div>
</div>