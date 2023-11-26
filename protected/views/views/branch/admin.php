<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Branches';
$this->breadcrumbs=array(
    'Branches'=>array('branch/admin'),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#branch-grid').yiiGridView('update', {
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
        		<div class="card-body">
					<div class="card-header">
						<h5 class="title">Manage Branches</h5>
						<hr class="common_rule">
					</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="search-form">
							<?php $this->renderPartial('_search',array(
								'model'=>$model,
							)); ?>
							</div><!-- search-form -->
						</div>
					<?php if(Navigation::checkIfAuthorized(1) == 1):?>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<a href="<?=Yii::app()->createUrl('branch/create');?>" title='Create Branch' class="btn btn-success pull-left"> New Branch</a>
							<?php if(Navigation::checkIfAuthorized(4) == 1):?>
							<a title='Merge Branches' class="btn btn-primary pull-right" onclick="MergeBranches()" style="color:#fff !important;"> Merge Branches</a>
						<?php endif;?>
						</div>
						<div class="col-md-12 col-lg-12 col-sm-12">
							<hr class="common_rule">
						</div>
				<?php endif;?>
							<div class="table-responsive">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'branch-grid',
								'type'=>'condensed striped',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Branches Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Code',
										'name'=>'BranchCode',
									),
									array(
										'header'=>'Name',
										'value'=>'$data->name',
									),
									array(
										'header'=>'Town',
										'name'=>'BranchTown',
									),
									array(
										'header'=>'Sales Target',
										'name'=>'SalesTarget',
									),
									array(
										'header'=>'Collections Target',
										'name'=>'CollectionsTarget',
									),
									array(
										'header'=>'Created By',
										'name'=>'CreatedByFullName',
									),
									array(
										'header'=>'Date Created',
										'name'=>'CreatedByDateFormatted',
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
<script type="text/javascript">
	function MergeBranches(){
		$('#mergeBranches').modal({show:true});
	}
</script>
<!-- MERGE BRANCHES MODAL-->
<div class="modal fade" id="mergeBranches" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:75% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center">
        <h4 class="title">
         	Merge Branches
      	</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?=Yii::app()->createUrl('branch/merge');?>">
        	<div class="col-md-10 col-lg-10 col-sm-12">
        		<h6>Select Branches to Merge: </h6>
        	</div>
      	<?php SaccoBranch::displaySaccoBranches();?>
      	<div class="col-md-10 col-lg-10 col-sm-12">
      		<h6>New Branch To Merge Branches into: </h6>
      		<div class="form-group">
      			<input type="text" name="newBranch" placeholder="New Branch" class="form-control" required="required">
      		</div>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-round">
          Merge Branches
        </button>
        <button type="button" class="btn btn-default btn-round mb-3" data-dismiss="modal">
          Cancel Action
        </button>
      </div>
    </form>
  </div>
  </div>
</div>
<!-- CUT TILL HERE -->