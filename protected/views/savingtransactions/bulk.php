<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Bulk Transactions';
$this->breadcrumbs=array(
	'BulkTransactions'=>array('bulk'),
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
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Bulk Saving Transactions</h5>
            <hr>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <form method="post" action="<?=Yii::app()->createUrl('savingtransactions/commitBulk')?>">
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label >Select Saving Product</label>
                    <select class="form-control selectpicker" name="savingproduct" id="savingproduct" onchange="loadSavingAccounts()">
                      <option value="0">--Select Saving Product--</option>
                      <?php
                        if(!empty($savingproducts)){
                          foreach($savingproducts as $savingproduct){
                            $productID=$savingproduct->savingproduct_id;
                            $productName=$savingproduct->name;
                            echo "<option value=";echo $productID;echo">$productName</option>";
                          } 
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label >Select Transaction Type</label>
                    <select class="form-control selectpicker" name="type" required="required">
                      <option value="">--Select Type--</option>
                      <option value="credit">Deposit Funds</option>
                      <option value="debit">Withdraw Funds</option>
                    </select>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label >Transaction Amount</label>
                    <input type="text" name="amount" class="form-control" required="required" placeholder="Amount in Digits">
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label >Provide Transaction Description</label>
                    <textarea name="description" class="form-control" required="required" cols="5" rows="2" placeholder="Provide brief description..."></textarea>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <label >Select Saving Accounts</label>
                  <div id="loadedAccounts"></div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Commit Transaction">
                  </div>
                </div>
              </div>
            </form>
	        </div>
        </div>
     </div>
  </div>
</div>

<script type="text/javascript">
  function loadSavingAccounts(){
    var productID=$('#savingproduct').val();
    $.ajax({
       url: "<?=Yii::app()->createUrl('savingtransactions/loadSavingAccounts');?>",
       type: 'get',
       data: {savingproduct_id:productID},
       success: function(response){
        switch(response){
          case 0:
          $('#loadedAccounts').innerHTML="";
          break;

          default:
          $('#loadedAccounts').html(response);
          break;
        }
       }
    });
  }
</script>