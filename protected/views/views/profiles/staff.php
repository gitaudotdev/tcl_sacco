<?php
/* @var $this ProfilesController */
/* @var $model Profiles */
$this->breadcrumbs=array(
	'Profiles'=>array('staff'),
	'Staff'=>array('staff'),
);

Yii::app()->clientScript->registerScript('searchStaff', "
$('.search-form form').submit(function(){
	$('#profiles-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
            	<h5 class="title">Staff Members</h5>
            	<hr class="common_rule">
	          </div>
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
        </div>
        <div class="card-body">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="search-form">
						<?php $this->renderPartial('_searchStaff',array(
							'model'=>$model,
						)); ?>
					</div><!-- search-form -->
					<hr class="common_rule">
				</div>
				<div class="col-md-12 col-lg-12 col-sm-12">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'profiles-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->searchStaff(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Profiles  Found',
						'columns'=>array(
							array(
								'header'=>'#',
								'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
							),
							array(
								'header'=>'Branch',
								'name'=>'ProfileBranch',
							),
							array(
								'header'=>'Manager',
								'name'=>'ProfileManager',
							),
							array(
								'header'=>'Name',
								'name'=>'ProfileFullName',
							),
							array(
								'header'=>'Gender',
								'value'=>'$data->gender',
							),
							array(
								'header'=>'Id Number',
								'value'=>'$data->idNumber',
							),
							array(
								'header'=>'Type',
								'name'=>'ProfileType',
							),
							array(
								'header'=>'Role',
								'name'=>'ProfileRoleName',
							),
							array(
								'header'=>'Role Actions',
								'name'=>'MoreActions',
							),
							array(
							'header'=>'Actions',
							'name'=>'Action',
							),
						),
					)); ?>
				</div>
			<br>
		</div>
	</div>
  </div>
</div>
