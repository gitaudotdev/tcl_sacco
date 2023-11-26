<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Upload Repayments';
$this->breadcrumbs=array(
  'BulkRepayments'=>array('loanrepayments/bulk')
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
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h5 class="title">Upload Repayments</h5>
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
          <div class="col-md-12 col-lg-12 col-sm-12">
               <h3 class="title">Please Upload a CSV file</h3>
               <div style="color:red !important;margin-top: -2% !important;">
                  <strong>NB: </strong><span>Kindly ensure you rename the file and save it as a CSV file. <br>
                    e.g. 2018-08-12 12-45-loanrepayments.csv
                  </span>
               </div>
                <form method="post" action="<?=Yii::app()->createUrl('loanrepayments/importData');?>"
                 enctype='multipart/form-data'>
                  <?php
                    echo '<br>';
                    echo CHtml::fileField('filename');
                    echo '<br>';
                    echo CHtml::submitButton('Import Repayments',array('class'=>'btn btn-primary btn-round btn-sm'));
                    echo '</form>';
                    echo '<br>';
                  ?>
          </div>
        </div>
     </div>
  </div>
</div>