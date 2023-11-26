<?php
$this->pageTitle   = Yii::app()->name . ' - B2C Account Balances';
$this->breadcrumbs = array(
	'B2C'=>array('accountBalance'),
	'Balances'=>array('accountBalance'),
);
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
        <div class="card-header">
          <div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">B2C Account Balances</h5>
            <hr class="common_rule">
          </div>
        </div>
        <div class="card-body">
            <?php if(Navigation::checkIfAuthorized(303) === 1):?>
            <div class="col-md-12 col-lg-12 col-sm-12">
            <a href="<?=Yii::app()->createUrl('accounting/refreshBalance');?>" class="btn btn-success pull-left"><i class="fa fa-refresh"></i> Refresh</a>                
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
            <hr class="common_rule">
            </div>
            <?php endif;?>
            <?php if(!empty($balance)):?>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th>ACCOUNT</th>
                            <th>BALANCE</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Utility</td>
                                <td><strong>KES <?=CommonFunctions::asMoney(floatval($balance->utilityAccount));?> /-</strong></td>
                            </tr>
                            <tr>
                                <td>Working</td>
                                <td><strong>KES <?=CommonFunctions::asMoney(floatval($balance->workingAccount));?> /-</strong></td>
                            </tr>
                            <tr>
                                <td>Charge</td>
                                <td><strong>KES <?=CommonFunctions::asMoney(floatval($balance->chargeAccount));?> /-</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php else:?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                <h4>No Account balances available, please hit <strong>refresh</strong> to load recent balances</4>
                </div>
            <?php endif;?>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <hr class="common_rule"><br>
            </div>
        </div>
     </div>
  </div>
</div>