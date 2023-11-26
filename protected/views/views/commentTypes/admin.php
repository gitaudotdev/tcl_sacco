<?php
$this->pageTitle=Yii::app()->name . ' - Loan Comment Types Administration';
$this->breadcrumbs=array(
  'Settings'=>array('dashboard/admin'),
  'Comment'=>array('commentTypes/admin'),
  'Types'=>array('commentTypes/admin'),
);
Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#comment-types-grid').yiiGridView('update', {
			data: $(this).serialize()
		});
		return false;
	});
");
$succesStatus  = CommonFunctions::checkIfFlashMessageSet('success');
$infoStatus    = CommonFunctions::checkIfFlashMessageSet('info');
$warningStatus = CommonFunctions::checkIfFlashMessageSet('warning');
$dangerStatus  = CommonFunctions::checkIfFlashMessageSet('danger');
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
        		<div class="col-lg-12 col-md-12 col-sm-12">
	            <h5 class="title">Manage Comment Types</h5>
	            <hr class="common_rule">
	          </div>
            <?php if($succesStatus === 1):?>
				    <div class="col-lg-12 col-md-12 col-sm-12">
				      <?=CommonFunctions::displayFlashMessage('success');?>
				    </div>
				    <?php endif;?>
				    <?php if($infoStatus === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage('info');?>
				      </div>
				    <?php endif;?>
				    <?php if($warningStatus === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage('warning');?>
				      </div>
				    <?php endif;?>
				    <?php if($dangerStatus === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage('warning');?>
				      </div>
				    <?php endif;?>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="search-form">
						<?php $this->renderPartial('_search',array(
							'model'=>$model,
						)); ?>
						</div><!-- search-form -->
					</div>
					<?php if(Navigation::checkIfAuthorized(252) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('commentTypes/create');?>" title='Create Comment Type' 
								class="btn btn-success pull-left"> New Comment Type</a>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
						<hr>
					</div>
				<?php endif;?>
					<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="table-responsive">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'comment-types-grid',
								'type'=>'condensed bordered',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Comment Types Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Name',
										'name'=>'TypeName',
									),
									array(
										'header'=>'Status',
										'name'=>'TypeStatus',
									),
									array(
										'header'=>'Created By',
										'name'=>'CreatedBy',
									),
									array(
										'header'=>'Date Created',
										'name'=>'CreatedAt',
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