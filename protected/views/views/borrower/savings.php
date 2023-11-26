<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance : Member Savings';
$this->breadcrumbs=array(
	'Members'=>array('borrower/admin'),
	'Savings'=>array('borrower/savings/'.$model->borrower_id)
);
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
    font-size: 1.35em !important;
	}
	.modified_card{
		padding:0px 20px 20px 0px!important;
	}
</style>
<div class="row">
  <div class=" col-md-12 col-lg-12 col-sm-12">
  	  <!--MEMBER DETAILS-->
        <div class="card card-stats card-raised modified_card col-md-12 col-lg-12 col-sm-12">
	        	<h4 class="header_details">
	        		<?=$model->getFullName();?> : View All Savings
	        	</h4>
	        	<hr>
            <div class="card-body">
                <div class="row">
                  <div class="col-md-9">
                  		<div class="col-md-2">
                  		 <?php if($model->photo === '0'):?>
                         <img class="avatar border-gray" src="<?=Yii::app()->baseUrl;?>/images/users/avatar.png" alt="..."> 
                       <?php else:?>
                       	 <img class="avatar border-gray" src="<?=Yii::app()->baseUrl;?>/images/users/$model->photo" alt="...">
                       <?php endif;?>
                      </div> 
                      <div class="col-md-10">
                      	<h6 class="borrower_details"><?=$model->getFullName();?> </h6>
                      	<h6 class="borrower_details"><?=$model->id_number;?></h6>
                      	<h6 class="borrower_status"><?=$model->getBorrowerWorkingStatus();?></h6>
                        <h6 class="borrower_details"><?=ucfirst($model->employer);?></h6>
                        <h6 class="borrower_details">Worked For: <?=$model->getYearsWorked();?> Years</h6>
                        
                      </div>
                    </div>
                    <div class="col-md-3">
                        <h6 class="borrower_details">Mobile: <?=$model->phone;?></h6>
                        <h6 class="borrower_details"><?=$model->address;?></h6>
                        <a href="#" class="btn btn-success" style="margin-left: -2.75%;">Send SMS</a>
                        <div class="dropdown" style="margin-left: -25%;">
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
</div>