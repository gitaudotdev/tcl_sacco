<?php
$this->pageTitle=Yii::app()->name . ' - Add Profile Account Setting';
$this->breadcrumbs=array(
    'Profiles'=>array('admin'),
    'View'=>array('profiles/'.$model->id),
    'Add_Setting'=>array('profiles/addAccountSetting/'.$model->id),
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
		text-transform: uppercase !important;
	}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats card-raised modified_card">
	        	<div class=" col-md-12 col-lg-12 col-sm-12">
		        	<h4 class="info-text" style="font-weight:bold;">&emsp;<?=$model->getProfileFullName();?> Profile: New Setting</h4>
           			<hr class="common_rule">
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
                   <div class="card-body col-md-12 col-lg-12 col-sm-12">
                    <form method="POST">
                        <div class="col-md-8 col-lg-8 col-sm-12">
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-sm-12">
                                    <div class="form-group">
                                        <label>Config Type</label>
                                        <select name="configType" class="selectpicker form-control" required="required" id="configType">
                                            <option value="">-- SELECT CONFIG TYPE --</option>
                                            <?php
                                                foreach($configTypes AS $configType){
                                                    echo "<option value=";echo $configType; echo">";echo $configType;echo "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row" id="configValueHolder"></div>
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <a href="<?=Yii::app()->createUrl('profiles/'.$model->id);?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <input type="submit" name="addAccountSettingCmd" class="btn btn-primary pull-right" value="Create">
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
      $("body").on("change", "#configType",function(e){
         var booleanValues = ['EMAIL_ALERTS','SMS_ALERTS','FIXED_PAYMENT_LISTED','COMMENTS_DASHBOARD_LISTED',
         'PAYROLL_LISTED','PAYROLL_AUTO_PROCESS','SUPERVISORIAL_ROLE'];
         if(this.value != ''){
            $("#configValueInput").remove();
            $("#configValueSelector").remove();
            if(booleanValues.includes(this.value)){
                var htmlContent = '<div class="col-md-8 col-lg-8 col-sm-12" id="configValueSelector">'+
                                    '<div class="form-group">'+
                                        '<label>Config Value</label>'+
                                         '<select name="configValue" class="selectpicker form-control" required="required">'+
                                            '<option value="">-- SELECT CONFIG --</option>'+
                                            '<option value="ACTIVE"> ACTIVATE</option>'+
                                            '<option value="DISABLED">DISABLE</option>'+
                                        '</select>'+
                                    '</div>'+
                                '</div>';
            }else{
                var htmlContent = '<div class="col-md-8 col-lg-8 col-sm-12" id="configValueInput">'+
                                    '<div class="form-group">'+
                                        '<label>Config Value</label>'+
                                         '<input name="configValue" type="text" class="form-control" required="required">'+
                                    '</div>'+
                                '</div>';
            } 
            $("#configValueHolder").append(htmlContent);
         }else{
            $("#configValueHolder").empty();
         }
       });
    });
</script>