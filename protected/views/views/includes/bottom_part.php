<?php
  $profile      = Profiles::model()->findByPk(Yii::app()->user->user_id);
  $emailAddress = ProfileEngine::getProfileContactByTypeOrderDesc(Yii::app()->user->user_id,'EMAIL');
?>
<!-- Account modal -->
<div class="modal fade" id="accountModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:75% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-profile">
            <h5 class="title">
              Profile Details
            </h5>
          </div>
        </div>
        <div class="modal-body">
         <div class="row">
            <div class="col-md-12">
                <div class="card-body" style="text-align: left;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="text" class="form-control" disabled="" placeholder="Email Address" value="<?=$emailAddress;?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" disabled="" value="<?=$profile->ProfileUsername;?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" disabled="" value="<?=$profile->firstName;?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" disabled="" value="<?=$profile->lastName;?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Last Access Date</label>
                                    <input type="text" class="form-control" disabled="" value="<?=$profile->ProfileLastLoggedAt;?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Days to Password Expiring</label>
                                    <input type="text" class="form-control" disabled=""
                                     value="<?=User::calculateDaysToPasswordExpiry($profile->id);?> Days">
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
          Close</button>
        </div>
      </div>
    </div>
</div>
</html>
