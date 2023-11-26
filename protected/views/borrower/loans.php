<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Member Loans';
$this->breadcrumbs=array(
	'Members'=>array('borrower/admin'),
	'Loans'=>array('borrower/loans/'.$model->borrower_id)
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
  h6{
    text-transform: unset !important;
  }
	.borrower_details{
		font-weight: normal;
		margin-bottom: 1.8%;
    font-size: 0.85em !important;
	}
	.borrower_status{
		font-weight: normal;
		margin-bottom: 1.8%;
		color:blue;
    font-size: 0.85em !important;
	}
	.header_details{
		margin-left: 3% !important;
    font-size: 1.05em !important;
	}
	.modified_card{
		padding:0px 20px 20px 0px!important;
	}
  .modified_row{
    border-bottom: 1px solid #ddd !important;
    margin-bottom: 1% !important;
  }
</style>
<div class="row">
  <div class="col-md-12">
  	  <!--MEMBER DETAILS-->
        <div class="card card-stats card-raised modified_card">
          <div class="col-md-12 col-lg-12 col-sm-12 modified_row">
  	        	<div class="col-lg-12 col-md-12 col-sm-12">
  		        	<h4 class="header_details">
  		        		<?=$model->getFullName();?> : View All Loans
  		        	</h4>
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
            <div class="card-body col-md-12 col-lg-12 col-sm-12">
                <div class="row">
                  <div class="col-md-9 col-lg-9 col-sm-12">
                  		<div class="col-md-2">
                  		 <?php if($model->photo === '0'):?>
                         <img class="avatar border-gray" src="<?=Yii::app()->baseUrl;?>/images/users/avatar.png" alt="..."> 
                       <?php else:?>
                       	 <img class="avatar border-gray" src="<?=Yii::app()->baseUrl;?>/images/users/$model->photo" alt="...">
                       <?php endif;?>
                      </div> 
                      <div class="col-md-10 col-lg-10 col-sm-12">
                      	<h6 class="borrower_details"><?=$model->getFullName();?> </h6>
                      	<h6 class="borrower_details"><?=$model->id_number;?></h6>
                      	<h6 class="borrower_status"><?=$model->getBorrowerWorkingStatus();?></h6>
                        <h6 class="borrower_details"><?=ucfirst($model->employer);?></h6>
                        <h6 class="borrower_details">Worked For: <?=$model->getYearsWorked();?> Years</h6>
                        
                      </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <h6 class="borrower_details">Mobile: <?=$model->phone;?></h6>
                        <h6 class="borrower_details"><?=$model->address;?></h6>
                        <a href="#" class="btn btn-success">Send SMS</a>
                        <div class="dropdown">
                            <button class="dropdown-toggle btn btn-info" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Statement
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">PDF</a>
                                <a class="dropdown-item" href="#">Excel</a>
                                <a class="dropdown-item" href="#">Email</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--LOAN DETAILS TABULATED-->
        <div class="card card-stats card-raised">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                  	<?php
                  	  if(!empty($loans)){
                  	  	Tabulate::displayMemberLoansTable($loans);
	                  	}else{
	                  		echo '<div class="col-md-8 col-lg-8 col-sm-8" style="padding:10px 10px 10px 10px !important;">
                              <p style="border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;">
                                  <strong style="margin-left:20% !important;">NO LOAN ACCOUNTS</strong></p><br>
                              <p style="color:#f90101;font-size:1.30em;">*** THERE ARE NO LOANS FOR THIS BORROWER. ****</p>
                          </div>';
	                  	}
                      ?>
                    </div>
                </div>
            </div>
        </div>
  </div>
