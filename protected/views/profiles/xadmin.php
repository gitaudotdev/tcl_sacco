<?php
/* @var $this ProfilesController */
/* @var $model Profiles */
$this->breadcrumbs=array(
	'Profiles'=>array('admin'),
	'Manage'=>array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
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
					<h5 class="title">Profiles Administration</h5>
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
					<?php if(Navigation::checkIfAuthorized(15) === 1):?>
						<a href="<?=Yii::app()->createUrl('profiles/addProfile');?>" title='Create User' class="btn btn-success">New User/Member</a>
					<?php endif;?>
					<hr class="common_rule">
					<?php $this->widget('bootstrap.widgets.TbGridView', array(
						'id'=>'profiles-grid',
						'type'=>'condensed striped',
						'dataProvider'=>$model->search(),
						'filter'=>$model,
						'filterPosition'=>'none',
						'emptyText'=>'No Profiles Found',
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
								'header'=>'Client Category Class',
								'name'=>'clientCategoryClass',
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
								'header'=>'Status',
								'value'=>'$data->profileStatus',
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
