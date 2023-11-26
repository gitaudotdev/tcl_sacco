<?php
$this->pageTitle   =  Yii::app()->name . ' - Loan Comments Administration';
$this->breadcrumbs =  array(
  'Comments'       => array('admin'),
  'Administration' => array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#loancomments-grid').yiiGridView('update', {
			data: $(this).serialize()
		});
		return false;
	});
");
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-body">
			<div class="card-header">
				<h5 class="title">Comments Administration</h5>
				<hr class="common_rule">
			</div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
						<div class="search-form">
							<?php $this->renderPartial('_search',array(
								'model'=>$model,
							)); ?>
						</div>
					</div>
					<div class="col-md-12 col-lg-12 col-sm-12">
							<div class="table-responsive">
							<?php $this->widget('bootstrap.widgets.TbGridView', array(
								'id'=>'loancomments-grid',
								'type'=>'condensed bordered',
								'dataProvider'=>$model->search(),
								'filter'=>$model,
								'filterPosition'=>'none',
								'emptyText'=>'No Comments Found',
								'columns'=>array(
									array(
										'header'=>'#',
										'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
									),
									array(
										'header'=>'Date & Time',
										'name'=>'LoanCommentedAt',
									),
									array(
										'header'=>'Client Name',
										'name'=>'CommentClientName',
									),
									array(
										'header'=>'Comment Type',
										'name'=>'CommentTypeName',
									),
									array(
										'header'=>'Comment Desc',
										'name'=>'LoanActualComment',
									),
									array(
										'header'=>'Comment By',
										'name'=>'LoanCommentedByName',
									),
									array(
										'header'=>'Client Manager',
										'name'=>'CommentRelationManager',
									),
									array(
										'header'=>'Account #',
										'name'=>'CommentAccountNumber',
									),
									array(
										'header'=>'Loan Balance',
										'name'=>'FormattedActualLoanBalance',
									),
									array(
										'header'=>'Client Branch',
										'name'=>'CommentBranchName',
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
	  $.fn.yiiGridView.update('loancomments-grid', {
	    data: $('.search-form form').serialize()
	  });
	  return false;
});
</script>