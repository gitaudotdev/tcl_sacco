<?php
/* @var $this DashboardController */
$this->pageTitle=Yii::app()->name . ' - Microfinance Status Dashboard';
$this->breadcrumbs=array(
  'Dashboard'=>array('dashboard/default'),
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
<style type="text/css">
    h4,.header_details{
        font-size: 1.1em !important;
        margin-top: 1.8% !important;
    }
    .info-text{
        margin-top: 0% !important;
    }
    .card-raised{
        border-radius: 0px !important;
    }
    .card-raised-modified{
        border-radius: 0px !important;
        border-top:3px solid #00c0ef !important;
    }
    .chart-area{
        margin-top: -5% !important;
    }
    .enhanced{
        font-size: 14px !important;
        font-weight: normal !important;
    }
    .modified{
     padding:10px 10px !important;
    }
    .avg_tenure{
        background-color: #00c0ef !important;
        color:#fff !important;
        border:1px solid #00c0ef !important;
    }
    .avg_interest{
        background-color: #f39c12 !important;
        color:#fff !important; 
        border:1px solid #f39c12 !important;
    }
    .card_stats{
        font-size:2.8em !important;
    }
    .card_stats_title{
        font-size:1.2em !important;
        font-weight: bold !important;
    }
    .recovery_rate{
        background-color: #00a65a !important;
        color:#fff !important;
        border:1px solid #00a65a !important;
    }
    .recovery_rate_all{
        background-color: #f39c12 !important;
        color:#fff !important;
        border:1px solid #f39c12 !important;
    }
    .enhanced_card_title{
        text-align: left !important;
        margin-top:0% !important;
    }
    #amountReleasedDiv{
        width: 100% !important;
    }
    #date_error{
        margin-left: 2% !important;
    }
    .loadingData{
        display: none;
        margin:-9% 0% 0% 45% !important;
    }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
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
        <?php if(!empty($shareholder)):?>
            <div class="card card-stats card-raised">
                <div class="card-body">
                    <div class="row">
                      <div class="col-md-3 place-border">
                            <div class="statistics">
                                <div class="info">
                                    <?php if(Yii::app()->user->user_level != '4'):?>
                                    <a href="#">
                                    <?php else:?>
                                    <a href="<?=Yii::app()->createUrl('shareholders/viewShareholder/'.$shareholder->id);?>">
                                    <?php endif;?>
                                    <div class="icon icon-primary">
                                        <i class="now-ui-icons business_money-coins"></i>
                                    </div>
                                    </a>
                                    <h3 class="info-title enhanced">Amount Invested</h3>
                                    <h6 class="stats-title enhanced_title2">
                                        <div id="loadBorrowersCount"><br><?=$shareholder->AccountTotalInvested;?></div>
                                        <br>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3  place-border">
                            <div class="statistics">
                                <div class="info">
                                    <a href="<?=Yii::app()->createUrl('shareholders/viewShareholder/'.$shareholder->id)?>">
                                        <div class="icon icon-success">
                                            <i class="now-ui-icons business_bank"></i>
                                        </div>
                                    </a>
                                    <h3 class="info-title enhanced">Deposits</h3>
                                    <h6 class="stats-title enhanced_title2"><br><?=number_format(sharesManager::getTotalSharesCreditTransactions($shareholder->id),2);?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3  place-border">
                            <div class="statistics">
                                <div class="info">
                                     <a href="<?=Yii::app()->createUrl('shareholders/viewShareholder/'.$shareholder->id)?>">
                                        <div class="icon icon-danger">
                                            <i class="now-ui-icons shopping_credit-card"></i>
                                        </div>
                                    </a>
                                    <h3 class="info-title enhanced">Withdrawals</h3>
                                    <h6 class="stats-title enhanced_title2">
                                        <div id="loadTotalCollectionsBranchDate"><br><?=number_format(sharesManager::getTotalSharesDebitTransactions($shareholder->id),2);?></div>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="statistics">
                                <div class="info">
                                     <a href="<?=Yii::app()->createUrl('shareholders/viewShareholder/'.$shareholder->id)?>">
                                        <div class="icon icon-success">
                                            <i class="now-ui-icons objects_diamond"></i>
                                        </div>
                                    </a>
                                    <h3 class="info-title enhanced">Total Shares</h3>
                                    <h6 class="stats-title enhanced_title2">
                                        <div id="loadTotalOutstandingBranchDate"><br><?=$shareholder->AccountTotalShares;?></div>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;?>

        <div class="card card-stats card-raised">
            <div class="card-body">
                <div class="row">
                    <?php if(!empty($sharetransactions)):?>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                            <h4 class="info-text"> Account Transactions</h4>
                            <hr>
                            <table class="table table-condensed table-striped" style="font-size:12px !important;">
                            <thead>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Transacted By</th>
                                <th>Date Transacted</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php
                                if(!empty($sharetransactions)){
                                    $i=1;
                                    foreach($sharetransactions as $sharetransaction){
                                        echo "<tr>";
                                        echo "<td>$i</td>";
                                        echo "<td>$sharetransaction->TransactionAmount</td>";
                                        echo "<td>$sharetransaction->TransactionType</td>";
                                        echo "<td>$sharetransaction->TransactionTransactedBy</td>";
                                        echo "<td>$sharetransaction->TransactionDate</td>";
                                        echo "<td>";echo $sharetransaction->getAction();echo"</td>";
                                        echo "</tr>";
                                        $i++;
                                    }
                                }?>
                            </tbody>
                        </table> 
                    </div>
                    <?php else:?>
                        <?php
                        echo '<div class="col-md-12 col-lg-8\12 col-sm-8\12" style="padding:10px 10px 10px 10px !important;">
                                 <br>
                                 <center>
                                  <p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
                                      <strong>NO TRANSACTIONS</strong></p><br>
                                  <p style="color:#f90101;font-size:1.30em;">*** There are no available share transactions for this share holder account. ***</p>
                                  </center>
                              </div>';
                        ?>
                    <?php endif;?> 
                </div>
            </div>
        </div>
</div>