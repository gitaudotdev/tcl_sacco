<?php
$this->pageTitle=Yii::app()->name . ' - System Users';
$this->breadcrumbs=array(
  'Settings'=>array('dashboard/admin'),
  'Users'=>array('users/admin'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#users-grid').yiiGridView('update', {
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
  <div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
            	<h5 class="title">Manage Users</h5>
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
        	  <div class="col-lg-11 col-md-11 col-sm-12 content_holder">
							<div class="search-form">
							<?php $this->renderPartial('_search',array(
								'model'=>$model,
							)); ?>
							</div><!-- search-form -->
						</div>
						<div class="col-md-11 col-lg-11 col-sm-12 content_holder">
							<div class="table-responsive">
							<?php if(Navigation::checkIfAuthorized(15) === 1):?>
									<a href="<?=Yii::app()->createUrl('users/create');?>" title='Create User' class="btn btn-success">New User</a>
							<?php endif;?>
							<hr class="common_rule">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'users-grid',
								'type'=>'condensed striped',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Users  Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Account Details',
										'name'=>'UserDetails',
									),
									array(
										'header'=>'Branch',
										'name'=>'BranchName',
									),
									array(
										'header'=>'Manager',
										'name'=>'UserRelationManager',
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
						</div><br><br>
				</div>
			</div>
  </div>
</div>