<?php
$this->pageTitle=Yii::app()->name . ' - Initiate Auth Level SMS Message';
$this->breadcrumbs=array(
	'Auth_Level'=>array('auths'),
	'Initiate'=>array('authsCreate')
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
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <h5 class="title">Initiate Auth Level SMS</h5>
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
        	<div class="col-md-11 col-lg-11 col-sm-12">
           <form  action="<?=Yii::app()->createUrl('groupSMS/authsCreate');?>" method="POST">
              <div class="row">
	        			<div class="col-md-6 col-lg-6 col-sm-12">
									<div class="form-group">
										<h5 class="title">Draft Message</h5>
			              <textarea class="form-control" cols="15" rows="5" name="textMessage" placeholder="Draft brief message..." required="required"></textarea>
									</div>
                </div>
							</div><br>
	        		<div class="row">
	        			<div class="col-md-6 col-lg-6 col-sm-12">
                  <h5 class="title">Select Authorization</h5>
                  <select name="auths[]" required="required" class="selectpicker">
                    <option value=""></option>
                    <?php
                    foreach($auths as $auth){
                      echo '<option value="';echo (int)$auth->AuthLevel; echo'">';echo $auth->level; '</option>';
                    }
                    ?>
                  </select>
                </div>
							</div><br><br/>
							<hr class="common_rule">
              <div class="row">
						    <div class="col-md-6 col-lg-6 col-sm-12">
						        <div class="form-group">
						        	<input type="submit" class="btn btn-primary" value="Initiate" name="initiate_sms_cmd">
									</div>
								</div>
								<div class="col-md-6 col-lg-6 col-sm-12">
						      <div class="form-group">
						        	<a href="<?=Yii::app()->createUrl('groupSMS/auths');?>" class="btn btn-default pull-right">Cancel Action</a>
									</div>
								</div>
							</div>
	        	</form>
	        </div>
        </div>
     </div>
  </div>