<?php
/* @var $this DashboardController */
$this->pageTitle=Yii::app()->name . ' - Account Administration, Configurations and Management';
$this->breadcrumbs=array(
  'Settings'=>array('admin'),
  'Administration'=>array('admin'),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats card-raised">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12 content_holder">
                        <div class="statistics">
                            <div class="info">
                                <div class="icon icon-success">
                                    <i class="now-ui-icons business_bulb-63"></i>
                                </div>
                                <h4 class="info-title">Administration</h4>
                                <hr class="common_rule">
                                <?php if(Navigation::checkIfAuthorized(17) == 1):?>
                                    <h6 class="stats-title enhanced_title">
                                        <a href="<?=Yii::app()->createUrl('profiles/admin');?>">Profiles</a>
                                    </h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(1) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('branch/admin');?>">Branches</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(105) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('incomeTypes/admin');?>">Income Types</a></h6>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('incomes/admin');?>">Manage Incomes</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(101) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('expenseTypes/admin');?>">Expense Types</a></h6>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('expenses/admin');?>">Manage Expenses</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(145) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('assetType/admin');?>">Asset Types</a></h6>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('assets/admin');?>">Assets Management</a></h6>
                                <?php endif;?>
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12  content_holder">
                        <div class="statistics">
                            <div class="info">
                                <div class="icon icon-success">
                                    <i class="now-ui-icons business_badge"></i>
                                </div>
                                <h4 class="info-title">Human Resources</h4>
                                <hr class="common_rule">
                                <?php if(Navigation::checkIfAuthorized(23) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('staff/admin');?>">Staff</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(28) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('staff/payroll');?>">Payroll </a>
                                    </h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(120) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('leaves/admin');?>">Leave Details</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(120) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('leaveApplications/admin');?>">Leave Management</a>
                                    </h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(119) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('folders/admin');?>">Documents Management </a>
                                    </h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(128) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('notices/admin');?>">Notice Board Management</a></h6>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12 content_holder">
                        <div class="statistics">
                            <div class="info">
                                <div class="icon icon-success">
                                    <i class="now-ui-icons business_briefcase-24""></i>
                                </div>
                                <h4 class="info-title">Loans</h4>
                                <hr class="common_rule">
                                <?php if(Navigation::checkIfAuthorized(32) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>">Accounts</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(125) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('loaninterests/admin');?>">Accrued Interests</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(251) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('commentTypes/admin');?>">Comment Types</a></h6>
                                 <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(141) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('collateraltypes/admin');?>">Collateral Types</a></h6>
                                 <?php endif;?>
                                 <?php if(Navigation::checkIfAuthorized(141) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('collateral/admin');?>">Manage Collateral</a></h6>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12  content_holder">
                        <div class="statistics">
                            <div class="info">
                                <div class="icon icon-info">
                                    <i class="now-ui-icons business_money-coins"></i>
                                </div>
                                <h4 class="info-title">Repayments</h4>
                                <hr class="common_rule">
                                <?php if(Navigation::checkIfAuthorized(147) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('loanrepayments/admin');?>">Repayments</a></h6>
                                <?php endif;?>
                                 <?php if(Navigation::checkIfAuthorized(65) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('strayRepayments/admin');?>">Stray Repayments</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(47) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('writeOffs/admin');?>">Loan Write Offs</a></h6>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12 content_holder">
                        <div class="statistics">
                            <div class="info">
                                <div class="icon icon-info">
                                    <i class="now-ui-icons business_bank"></i>
                                </div>
                                <h4 class="info-title">Savings</h4>
                                <hr class="common_rule">
                                <?php if(Navigation::checkIfAuthorized(53) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('savingaccounts/admin');?>">Accounts</a></h6>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('savingtransactions/admin');?>">Transactions</a></h6>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12  content_holder">
                        <div class="statistics">
                            <div class="info">
                                <div class="icon icon-info">
                                    <i class="now-ui-icons ui-2_settings-90"></i>
                                </div>
                                <h4 class="info-title">Settings</h4>
                                <hr class="common_rule">
                                 <?php if(Navigation::checkIfAuthorized(77) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('roles/admin');?>">Roles & Permissions</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(122) == 1):?>
                                    <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('organization/admin');?>">Organization Settings & Configs</a></h6>
                                <?php endif;?>
                                <?php if(Navigation::checkIfAuthorized(121) == 1):?>
                                     <h6 class="stats-title enhanced_title"><a href="<?=Yii::app()->createUrl('logs/admin');?>">Logging & Monitoring</a>
                                    </h6>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
  </div>