<?php
$this->pageTitle=Yii::app()->name . ' -  Members Listing Report';
$this->breadcrumbs=array(
  'Members/Users'=>array('membersReport'),
  'Report'=>array('membersReport'),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#profiles-grid').yiiGridView('update', {
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
        <div class="card-body">
				<div class="card-header">
					<h5 class="title">Members/ Users</h5>
					<hr class="common_rule">
				</div>
        	  <div class="col-md-12">
	        	  <div class="search-form">
								<?php $this->renderPartial('_searchReport',array(
									'model'=>$model,
								)); ?>
							</div><!-- search-form -->
						</div>
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'profiles-grid',
							'type'=>'bordered',
							'dataProvider'=>$model->search(),
							'filter'=>$model,
							'filterPosition'=>'none',
							'emptyText'=>'No Members/Users Found',
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
									'name'=>'ProfileGender',
								),
								array(
									'header'=>'ID Number',
									'name'=>'ProfileIdNumber',
								),
								array(
									'header'=>'Phone Number',
									'name'=>'ProfilePhoneNumber',
								),
								array(
									'header'=>'Employer',
									'name'=>'ProfileEmployment',
								),
								array(
									'header'=>'Original Principal',
									'name'=>'ProfileOriginalPrincipal',
								),
								array(
								'header'=>'Current Balance',
								'name'=>'ProfileOutstandingLoanBalance',
								),
								array(
								'header'=>'Savings',
								'name'=>'ProfileSavingsBalance',
								),
								array(
								'header'=>'Date Created',
								'name'=>'ProfileCreatedAt',
								),
								array(
								'header'=>'Year',
								'name'=>'ProfileCreatedYear',
								),
								array(
								'header'=>'Counts',
								'name'=>'ProfileLoansCount',
								),
							),
						)); ?>
					</div>
				<br><br>
        </div>
      </div>
    </div>
<script type="text/javascript">
  $('#export-btn').click(function(){ 
	  $.fn.yiiGridView.update('profiles-grid', {
	    data: $('.search-form form').serialize()
	  });
	  return false;
});
</script>