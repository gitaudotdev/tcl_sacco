<?php
/* @var $this RolesController */
/* @var $model Roles */
$this->pageTitle=Yii::app()->name . ' - Roles and Permisssions';
$this->breadcrumbs=array(
  'Roles'=>array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#roles-grid').yiiGridView('update', {
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
            	<h5 class="title">Manage Roles</h5>
            	<hr class="common_rule">
	          </div>
        	  <div class="col-md-12 col-lg-12 col-sm-12">
			  <br>
							<div class="search-form">
							<?php $this->renderPartial('_search',array(
								'model'=>$model,
							)); ?>
							</div><!-- search-form -->
						</div>
						<?php if(Navigation::checkIfAuthorized(74) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('roles/create');?>" title='Create Role' class="btn btn-success">New Role</a>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
						<hr class="common_rule">
						</div>
						<?php endif;?>
					<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'roles-grid',
							'type'=>'condensed striped',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No Roles Found',
							'columns'=>array(
								array(
									'header'=>'#',
									'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
								),
								array(
									'header'=>'Role Name',
									'value'=>'$data->name',
								),
								array(
										'header'=>'Role Permisssions Actions',
										'name'=>'PermissionsActions',
								),
								array(
										'header'=>'Role Actions',
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
