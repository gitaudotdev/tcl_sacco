<?php
/* @var $this LoanaccountsController */
/* @var $model Loanaccounts */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Add Loan Collateral';
$this->breadcrumbs=array(
	'Home'=>array('dashboard/admin'),
    'Application'=>array('loanaccounts/'.$model->loanaccount_id),
    'Collateral'=>array('loanaccounts/collateral/'.$model->loanaccount_id)
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
	*, ::after, ::before {
	    box-sizing: border-box;
	}
	.card-title, label{
		font-size:13px !important;
	}
	.fileinput {
	    display: inline-block;
	    margin-bottom: 9px;
	}
	.text-center {
	    text-align: center !important;
	}
	.fileinput .thumbnail {
	    display: inline-block !important;
	    margin-bottom: 10px !important;
	    overflow: hidden !important;
	    text-align: center !important; 
	    vertical-align: middle !important;
	    max-width: 250px !important;
	    min-height: 20px !important;
	    box-shadow: 0 1px 15px 1px rgba(39,39,39,.1) !important;
	}
	.fileinput-exists .fileinput-new, .fileinput-new .fileinput-exists {
	    display: none !important;
	}
	.thumbnail {
   		 border: 0 none;
	    border-radius: 3px;
	    padding: 0;
	}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
			<div class="col-md-12 col-lg-12 col-sm-12">
	            <h5 class="title">Add Collateral</h5>
            	<hr class="common_rule">
	         </div>
			<?php if($succesStatus === 1):?>
		    <div class="col-md-12 col-lg-12 col-sm-12">
		      <?=CommonFunctions::displayFlashMessage($successType);?>
		    </div>
		    <?php endif;?>
		    <?php if($infoStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($infoType);?>
		      </div>
		    <?php endif;?>
		    <?php if($warningStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($warningType);?>
		      </div>
		    <?php endif;?>
		    <?php if($dangerStatus === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage($dangerType);?>
		      </div>
		    <?php endif;?>
        </div>
        <div class="card-body col-md-12 col-lg-12 col-sm-12">
        	<form method="post" action="<?=Yii::app()->createUrl('loanaccounts/commitCollateral');?>" enctype="multipart/form-data">
        		<input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
        		<div class="row">
			    	<div class="col-md-6 pr-1">
			       		 <div class="form-group">
			       		 	<label>Type</label>
							<select class="selectpicker form-control-changed" name="collateraltype_id" required="required">
								<option value="">Select Collateral Type</option>
								<?php CollateralFunctions::getCollateralTypes();?>
							</select>
						</div>
					</div>
				</div>
	        	<div class="row">
			    	<div class="col-md-6 pr-1">
			       		 <div class="form-group">
			       		 	<label>Name</label>
							<input type="text" class="form-control" required="required" name="name"
							 placeholder="Collateral Name">
						</div>
					</div>
				</div>
				<div class="row">
			    	<div class="col-md-6 pr-1">
			       		 <div class="form-group">
			       		 	<label>Model</label>
							<input type="text" class="form-control" required="required" name="model"
							 placeholder="Collateral Model">
						</div>
					</div>
				</div>
				<div class="row">
			    	<div class="col-md-6 pr-1">
			       		 <div class="form-group">
			       		 	<label>Serial Number</label>
							<input type="text" class="form-control" required="required" name="serial_number"
							 placeholder="Serial Number">
						</div>
					</div>
				</div>
				<div class="row">
			    	<div class="col-md-6 pr-1">
			       		 <div class="form-group">
			       		 	<label>Value</label>
							<input type="text" class="form-control" required="required" name="market_value"
							 placeholder="Market Value">
						</div>
					</div>
				</div>
				<br>
				<div class="row">
			    	<div class="col-md-6 pr-1">
		       		 	<label>Collateral Photo</label>
		       		 	<br>
		       		 	<?=CHtml::fileField('photo')?>
                </div>
				</div>
				<br>
				<div class="row">
			    	<div class="col-md-6 pr-1">
			       		 <div class="form-group">
							<input type="submit" class="btn btn-primary" value="Submit Collateral" name="loan_collateral_cmd">
						</div>
					</div>
				</div>
			</form>
        </div>
    </div>
</div>
