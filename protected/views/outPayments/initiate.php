<?php
$this->pageTitle=Yii::app()->name . ' - Initiate Supplier Payment';
$this->breadcrumbs=array(
	'Supplier_Payments'=>array('admin'),
	'Initiate'=>array('initiate')
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
        <div class="card-body">
          <div class="card-header">
              <h5 class="title">Initiate Payment</h5>
              <hr class="common_rule">
          </div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<form id="regForm" enctype="multipart/form-data"  method="POST">
              <br/>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label >SELECT SUPPLIER</label>
                       <select name="supplier" class="selectpicker" required="required">
                        <option value="">-- SUPPLIERS --</option>
                        <?php
                        if(!empty($suppliers)){
                          foreach($suppliers as $supplier){
                            echo "<option value='";echo $supplier->id;echo"'>
                            $supplier->ProfileSavingAccount </option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label >SELECT EXPENSE TYPE</label>
                       <select name="expense_type" class="selectpicker" required="required">
                        <option value="">-- EXPENSE TYPES --</option>
                        <?php
                        if(!empty($types)){
                          foreach($types as $type){
                            echo "<option value='";echo $type->expensetype_id;echo"'>
                            $type->name </option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label >PAYMENT AMOUNT</label>
                      <input class="form-control" type="text"  name="payment_amount" placeholder="Payment Amount" required="required"> 
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label >PAYMENT DATE</label>
                      <input class="form-control" type="text"  name="payment_date" placeholder="Payment Date" required="required" id="normaldatepicker"> 
                    </div>
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label >PAYMENT REASON</label>
                      <textarea class="form-control" name="payment_reason" cols='5' rows='3' placeholder="Please provide brief comment ..."></textarea>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label >PAYMENT RECEIPT</label><br>
                      <a class='btn btn-info' href='javascript:;'>
                        Browse ...
                        <input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="payment_resource[]" 
                        onchange='$("#upload-file-info").html($(this).val().replace(/^.*\\/, ""));'>
                      </a>
                      <span class='label label-info' id="upload-file-info"></span>
                    </div>
                </div>
              </div>
              <br/>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label >PAYMENT RECURRING</label>
                      <select name="payment_recurring" class="selectpicker" required="required">
                        <option value="">-- RECURRING STATUS --</option>
                        <option value="0"> NOT RECURRING</option>
                        <option value="1"> RECURRING</option>
                      </select>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label >PAYMENT RECURRING DATE</label>
                      <select name="payment_recurring_date" class="selectpicker" required="required">
                        <option value="0">-- RECURRING DATE --</option>
                        <?php
                          for($i=0;$i<=30;$i++){
                            echo "<option value='";echo $i;echo"'>$i</option>";
                          }
                        ?>
                      </select>
                    </div>
                </div>
              </div>
              <br><br/>
              <div class="row">
                <div class="col-md-8 col-lg-8 col-sm-12">
                 <hr class="common_rule">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    <a href="<?=Yii::app()->createUrl('outPayments/admin');?>" class="btn btn-info">
                      <i class="fa fa-arrow-left"></i>Previous</a>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary pull-right" value="Initiate Payment" id="supplier_outpayment_cmd" name="supplier_outpayment_cmd">
                  </div>
                </div>
            </div><br/>
            </form>
	        </div>
        </div>
     </div>
  </div>
</div>