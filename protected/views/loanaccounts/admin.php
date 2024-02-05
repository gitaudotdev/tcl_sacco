<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Loan Applications Management';
$this->breadcrumbs=array(
    'Applications'=>array('admin'),
    'Manage'=>array('admin'),
);
Yii::app()->clientScript->registerScript('search', "
	$('.search-form form').submit(function(){
		$('#loanaccounts-grid').yiiGridView('update', {
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
                    <h5 class="title">Manage Loan Applications</h5>
                    <hr class="common_rule">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="search-form">
                        <?php $this->renderPartial('_search',array(
                            'model'=>$model,
                        )); ?>
                    </div><!-- search-form -->
                </div>
                <?php if(Navigation::checkIfAuthorized(30) == 1):?>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <a href="<?=Yii::app()->createUrl('loanaccounts/create');?>" title='Create Application' class="btn btn-success pull-left">New Application</a>
                        </div>
                        <?php if(Navigation::checkIfAuthorized(311) == 1):?>
                        <div class="col-lg-3 col-md-4 col-sm-12">
                            <a href="<?=Yii::app()->createUrl('loanaccounts/upload');?>"  title='Bulk Import Accounts' class="btn btn-info">Import Accounts</a>
                        </div>
                        <?php endif;?>
                        <?php if(Navigation::checkIfAuthorized(158) == 1):?>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <a href="<?=Yii::app()->createUrl('loanaccounts/exportLoans');?>" target='_blank' title='CRB Listing Report' class="btn btn-warning">CRB Report</a>
                            </div>
                        <?php endif;?>

                        <?php if(Navigation::checkIfAuthorized(42) == 1):?>
                            <div class="col-lg-3 col-md-4 col-sm-12">
                                <a href="<?=Yii::app()->createUrl('loanaccounts/topup');?>" title='Top Up Application' class="btn btn-info ">Top Up Account</a>
                            </div>
                        <?php endif;?>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <hr class="common_rule">
                    </div>
                <?php endif?>
                <div class="col-md-12 col-lg-12 col-sm-12" style="overflow-x: scroll !important;">
                    <?php $this->widget('bootstrap.widgets.TbGridView', array(
                        'id'=>'loanaccounts-grid',
                        'type'=>'condensed striped',
                        'dataProvider'=>$model->search(),
                        'filter'=>$model,
                        'filterPosition'=>'none',
                        'emptyText'=>'No Applications Found',
                        'columns'=>array(
                            array(
                                'header'=>'#',
                                'value'=>'$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + ($row+1)',
                            ),
                            array(
                                'header'=>'Branch',
                                'name'=>'BorrowerBranchName',

                            ),
                            array(
                                'header'=>'Member',
                                'name'=>'BorrowerName',
                            ),
                            array(
                                'header'=>'Account #',
                                'value'=>'$data->account_number',
                            ),
                            array(
                                'header'=>'Applied',
                                'value'=>'$data->amount_applied',
                            ),
                            array(
                                //'header'=>'Processed',
                                'header'=>'Approved',
                                'name'=>'AmountDisbursed',
                            ),
                            array(
                                'header'=>'Disbursed',
                                'value'=>'$data->amount_approved',
                            ),
                            array(
                                'header'=>'Mthly %',
                                'name'=>'InterestRate',
                            ),
                            array(
                                'header'=>' Acc Int',
                                'name'=>'AccruedInterest',
                            ),
                            array(
                                'header'=>'Set Pty',
                                'name'=>'DailyPenalty',
                            ),
                            array(
                                'header'=>'Acc Pty',
                                'name'=>'AccountPenalties',
                            ),
                            array(
                                'header'=>' Pyt Frq',
                                'name'=>'PaymentFrequency',
                            ),
                            array(
                                'header'=>'Balance',
                                'name'=>'CurrentLoanBalance',
                            ),
                            array(
                                'header'=>'Process Status',
                                'name'=>'LoanAccountStatus',
                            ),
                            array(
                                'header'=>'Actions',
                                'name'=>'Action',
                            ),
                            array(
                                'header'=>'Risk Classification',
                                'name'=>'LoanAccountPerfomanceStatus',
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
    </div>
