<?php
$this->breadcrumbs=array(
	'Organization'=>array('admin'),
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
  <div class="col-md-12 col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h5 class="title">Organization Details</h5>
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
        </div>
        <div class="card-body">
        	<?php if(!empty($model)):?>
        	<?php if(Navigation::checkIfAuthorized(220) == 1):?>
	      		<div class="col-md-6 col-lg-6 col-sm-12" style="padding:2% auto !important;">
	      			<a href="<?=Yii::app()->createUrl('organization/update');?>" class="btn btn-info">
	      			 Update Settings
	      			</a>
	      			<hr class="common_rule">
	        	</div>
	        <?php endif;?>
        	<div class="col-md-8 col-lg-8 col-sm-12">
           <div class="card card-user" style="padding:10px 20px;">
						<div class="row">
					    <div class="col-md-6 col-lg-6 col-sm-12">
					        <div class="form-group">
					        	<label >Organization Name</label>
										<input class="form-control" name="email" readonly="readonly" value="<?=$model->name;?>">
								</div>
							</div>
					    <div class="col-md-6 col-lg-6 col-sm-12">
					        <div class="form-group">
					        	<label >Email Address</label>
										<input class="form-control" name="email" readonly="readonly" value="<?=$model->email;?>">
								</div>
							</div>
						</div>
						<div class="row">
					    <div class="col-md-6 col-lg-6 col-sm-12">
					        <div class="form-group">
					        	<label >Phone Number</label>
										<input class="form-control" name="email" readonly="readonly" value="<?=$model->phone;?>">
								</div>
							</div>
					    <div class="col-md-6 col-lg-6 col-sm-12">
					        <div class="form-group">
					        	<label >Physical Address</label>
										<input class="form-control" name="email" readonly="readonly" value="<?=$model->address;?>">
								</div>
							</div>
						</div>
						<div class="row">
					    <div class="col-md-6 col-lg-6 col-sm-12">
					        <div class="form-group">
					        	<label >Website Link</label>
										<input class="form-control" name="email" readonly="readonly" value="<?=$model->website;?>">
								</div>
							</div>
					    <div class="col-md-6 col-lg-6 col-sm-12">
					        <div class="form-group">
					        	<label >M-PESA Status</label>
									<input class="form-control" name="enable_mpesa_b2c" readonly="readonly" value="<?=$model->enable_mpesa_b2c;?>">
								</div>
							</div>
						</div>

						<div class="row">
					    <div class="col-md-6 col-lg-6 col-sm-12">
					        <div class="form-group">
					        	<label>Automated Payroll Status</label>
									<input class="form-control" name="automated_payroll" readonly="readonly" value="<?=strtoupper($model->automated_payroll);?>">
								</div>
							</div>
						</div>

					</div>
					</div>
					<div class="col-md-4">
              <div class="card card-user">
                  <div class="card-body">
                    <div class="author">
                         <a href="#">
                         	<?php if($model->logo === '0'):?>
                            <img class="avatar border-gray" src="<?=Yii::app()->baseUrl;?>/images/users/avatar.png" alt="Login page background image">
                          <?php else:?>
                          	<img class="avatar border-gray" src="<?=Yii::app()->baseUrl;?>/images/site/<?=$model->logo?>" alt="Login page background image">
                          <?php endif;?>
                          <h5 class="title" style="color:#000!important;">Login Background Image</h5>
                    			<hr class="common_rule"/>
                        </a>
                    </div>
                    <div class="col-md-12">
                    	<form method="post" action="<?=Yii::app()->createUrl('organization/logo');?>"
                    	 enctype='multipart/form-data'><br><br>
                    	<?php
												echo CHtml::fileField('logo');
												echo '<br>';
												echo CHtml::submitButton('Upload',array('class'=>'btn btn-primary'));
												echo '</form>';
											?>
											<br/>
                    </div>
                  </div>
              </div>
          </div> 
        	<?php endif;?>
        	<?php if(empty($model)):?>
        		<a href="<?=Yii::app()->createUrl('organization/create');?>" class="btn btn-success pull-left"><i class='now-ui-icons ui-1_simple-add'></i>  Create Records</a>
        	<?php endif;?>
        </div>
  </div>
</div>