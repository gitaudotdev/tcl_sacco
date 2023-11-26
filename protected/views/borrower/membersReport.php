<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance Members Listing Report';
$this->breadcrumbs=array(
  'Members_Report'=>array('membersReport'),
  'Listing'=>array('membersReport'),
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
            	<h5 class="title">Members Listing</h5>
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
								<?php $this->renderPartial('_searchReport',array(
									'model'=>$model,
								)); ?>
							</div><!-- search-form -->
						</div>
					<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="table-responsive">
						<?php $this->widget('bootstrap.widgets.TbGridView', array(
							'id'=>'borrower-grid',
							'type'=>'bordered',
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
									'name'=>'BorrowerFullName',
								),
								array(
									'header'=>'Gender',
									'name'=>'MemberGender',
								),
								array(
									'header'=>'ID Number',
									'name'=>'MemberIDNumber',
								),
								array(
									'header'=>'Phone Number',
									'name'=>'BorrowerPhoneNumber',
								),
								array(
									'header'=>'Employer',
									'name'=>'MemberEmployer',
								),
								array(
									'header'=>'Original Principal',
									'name'=>'FormattedMemberOriginalPrincipal',
								),
								array(
								'header'=>'Current Balance',
								'name'=>'FormattedCurrentLoanBalance',
								),
								array(
								'header'=>'Savings',
								'name'=>'MemberSavings',
								),
								array(
								'header'=>'Date Created',
								'name'=>'CreatedAtFormatted',
								),
								array(
								'header'=>'Year',
								'name'=>'MemberCreatedYear',
								),
								array(
								'header'=>'Counts',
								'name'=>'MemberLoansCount',
								),
							),
						)); ?>
					</div><br><br>
				</div>
        </div>
      </div>
    </div>
<script type="text/javascript">
  $('#export-btn').click(function(){ 
	  $.fn.yiiGridView.update('borrower-grid', {
	    data: $('.search-form form').serialize()
	  });
	  return false;
});
</script>