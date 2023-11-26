<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance Members';
$this->breadcrumbs=array(
  'Members'=>array('borrower/admin'),
  'Manage'=>array('borrower/admin'),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#borrower-grid').yiiGridView('update', {
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
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
            	<h5 class="title">Manage Members</h5>
            	<hr>
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
        	  <div class="col-md-12">
	        	  <div class="search-form">
								<?php $this->renderPartial('_search',array(
									'model'=>$model,
								)); ?>
							</div><!-- search-form -->
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<?php if(Navigation::checkIfAuthorized(5) == 1):?>
							<div class="col-md-12 col-lg-12 col-sm-12">
								<a href="<?=Yii::app()->createUrl('borrower/create');?>" title='Create Member' class="btn btn-success pull-left">New Member</a>
							</div>
							<div class="col-md-12 col-lg-12 col-sm-12">
								<hr>
							</div>
						<?php endif;?>
					</div>
					<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'borrower-grid',
							'type'=>'condensed striped',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No Members Found',
							'columns'=>array(
								array(
									'header'=>'#',
									'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
								),
								array(
									'header'=>'Branch',
									'name'=>'BranchName',
								),
								array(
									'header'=>'Manager',
									'name'=>'RelationManager',
								),
								array(
									'header'=>'Name',
									'name'=>'BorrowerDetails',
								),
								array(
									'header'=>'Age',
									'name'=>'BorrowerAge',
								),
								array(
									'header'=>'Phone',
									'value'=>'$data->phone',
								),
								array(
									'header'=>'Segment',
									'name'=>'MemberSegment',
								),
								array(
									'header'=>'Working Status',
									'name'=>'BorrowerWorkingStatus',
								),
								array(
								'header'=>'Member Actions',
								'name'=>'Action',
								),
							),
						)); ?>
					</div><br><br>
				</div>
        </div>
      </div>
    </div>
