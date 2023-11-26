<?php
$this->pageTitle=Yii::app()->name . ' -  Create Leave Application';
$this->breadcrumbs=array(
	'Requests'=>array('admin'),
	'Apply'=>array('create')
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
  <div class="col-md-12 col-sm-12 col-lg-12">
    <div class="card">
            <?php if($succesStatus === 1):?>
            <div class="col-lg-6 col-md-6 col-sm-6">
              <?=CommonFunctions::displayFlashMessage($successType);?>
            </div>
            <?php endif;?>
            <?php if($infoStatus === 1):?>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <?=CommonFunctions::displayFlashMessage($infoType);?>
              </div>
            <?php endif;?>
            <?php if($warningStatus === 1):?>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <?=CommonFunctions::displayFlashMessage($warningType);?>
              </div>
            <?php endif;?>
            <?php if($dangerStatus === 1):?>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <?=CommonFunctions::displayFlashMessage($dangerType);?>
              </div>
            <?php endif;?>
        <div class="card-body">
            <div class="card-header">
              <h5 class="title">Leave Application</h5>
              <hr class="common_rule">
            </div>
        	<div class="col-md-12">
          <form method="post" action="<?=Yii::app()->createUrl('leaveApplications/submitApplication');?>">
                    <br>
                    <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <div class="form-group">
                        <label>SELECT STAFF</label>
                        <select name="user" required="required" class="selectpicker" style="width:100%;">
                        <option value="">-- SELECT STAFF--</option>
                        <?php
                          foreach($profiles as $profile){
                            echo '<option value="';echo $profile->id; echo'">';echo $profile->ProfileFullName;'</option>';
                          }
                        ?>
                      </select>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <div class="form-group">
                        <label >Start Date</label>
                        <input type="text" class="form-control" placeholder="Start Date" name="start_date" id="start_date">
                      </div>
                    </div>
                    </div><br>
                    <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <div class="form-group">
                        <label >End Date</label>
                        <input type="text" class="form-control" name="end_date" id="end_date" placeholder="End Date">
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <div class="form-group">
                        <label >SELECT APPROVER</label>
                        <select name="directed_to" required="required" class="selectpicker" style="width:100%;">
                          <option value="">-- SELECT APPROVER--</option>
                          <?php
                          foreach($admins as $admin){
                            echo '<option value="';echo $admin->id; echo'">';echo $admin->ProfileFullName;'</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    </div>
                    <br>
                    <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <div class="form-group">
                        <label >SELECT STAFF TO HANDOVER TO</label>
                        <select name="handover_to" required="required" class="selectpicker" style="width:100%;">
                        <option value="">-- SELECT STAFF TO HANDOVER--</option>
                        <?php
                        foreach($handovers as $handover){
                          echo '<option value="';echo $handover->id; echo'">';echo $handover->ProfileFullName;'</option>';
                        }
                        ?>
                      </select>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12">
                      <div class="form-group">
                        <label >Handover Notes</label>
                        <textarea class="form-control" cols="5" rows="7" name="handover_notes" placeholder="Brief Handover Notes..." required="required"></textarea>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
				  	        <div class="col-md-2 col-lg-2 col-sm-12">
                      <div class="form-group">
				                  <a href="<?=Yii::app()->createUrl('leaveApplications/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                      </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-12">
                      <div class="form-group">
                        <input type="submit" class="btn btn-primary pull-right" value="Request" name="apply_cmd">
                      </div>
                    </div>
                  </div>
              </form>
              <br>
              <br>
	        </div>
        </div>
     </div>
  </div>