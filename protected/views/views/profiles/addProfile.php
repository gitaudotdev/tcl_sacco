<?php
$this->pageTitle=Yii::app()->name . ' Create New Profile and Account';
$this->breadcrumbs=array(
    'Profiles' => array('admin'),
    'Create'   => array('addProfile'),
);
?>
<style type="text/css">
	 .span-error{
        color: red;
        margin: 3% 0% 0% 0% !important;
        display: none;
    }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card">
              <?php if(CommonFunctions::checkIfFlashMessageSet('success') === 1):?>
				    <div class="col-lg-12 col-md-12 col-sm-12">
				      <?=CommonFunctions::displayFlashMessage('success');?>
				    </div>
				    <?php endif;?>
				    <?php if(CommonFunctions::checkIfFlashMessageSet('info') === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage('info');?>
				      </div>
				    <?php endif;?>
				    <?php if(CommonFunctions::checkIfFlashMessageSet('warning') === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage('warning');?>
				      </div>
				    <?php endif;?>
				    <?php if(CommonFunctions::checkIfFlashMessageSet('danger') === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage('danger');?>
				      </div>
				    <?php endif;?>
                    <div class="card-body">
                    <div class="card-header">
                        <h5 class="title">New Profile Account</h5>
                        <hr class="common_rule">
                    </div>
                    <form method="POST">
				                <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select name="branchId" class="selectpicker form-control" required="required"  id="branchId">
                                            <option value="">-- SELECT BRANCH --</option>
                                            <?php if(!empty($branches)):?>
                                                <?php foreach($branches as $branch):?>
                                                    <option value="<?=$branch->branch_id;?>">
                                                        <?=$branch->name;?>
                                                    </option>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Manager</label>
                                        <select class="selectpicker form-control-changed" name="managerId" required="required" id="managerId">
                                            <option value="">-- RELATION MANAGER --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender" class="selectpicker form-control" required="required">
                                            <option value="">-- Select Gender --</option>
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <?php if(in_array(Yii::app()->user->user_level,array('0'))):?>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Profile Type</label>
                                        <select name="profileType" class="selectpicker form-control" required="required">
                                            <option value="">-- SELECT PROFILE --</option>
                                            <option value="MEMBER">Member/Client</option>
                                            <option value="STAFF">Staff Member</option>
                                            <option value="SUPPLIER">Supplier</option>
                                        </select>
                                    </div>
                                </div>
                                <?php endif;?>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Id Number</label>
                                        <input type="text" name="idNumber" class="form-control" required="required" minlength="6" maxlength="15" id="idNumber"/>
                                        <span class='span-error' id="idNumberError"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="firstName" class="form-control" required="required" placeholder="First Name"/>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="lastName" class="form-control" required="required" placeholder="Last Name"/>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="text" name="birthDate" class="form-control" required="required" placeholder="YYYY-MM-DD" id="start_date"/>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phoneNumber" class="form-control" required="required" minlength="9" maxlength="15" id="phoneNumber"/>
                                        <span class='span-error' id="phoneNumberError"></span>
                                    </div>
                                </div>
                                <?php if(in_array(Yii::app()->user->user_level,array('0'))):?>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Authorization</label>
                                        <select name="level" class="selectpicker form-control" required="required">
                                            <option value="">-- SELECT LEVEL --</option>
                                            <option value="SUPERADMIN">SuperAdmin</option>
                                            <option value="ADMIN">Admin</option>
                                            <option value="STAFF">Staff</option>
                                            <option value="USER">User</option>
                                        </select>
                                    </div>
                                </div>
                                <?php endif;?>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control" required="required" minlength="5" maxlength="50" id="username" />
                                        <span class='span-error' id="usernameError"></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required="required" minlength="5" maxlength="12"/>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <a href="<?=Yii::app()->createUrl('profiles/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <input type="submit" name="addProfileCmd" class="btn btn-primary pull-right" value="Create Profile" id="addProfileCmd">
                                    </div>
                                </div>
                            </div>
                            <br/><br/>
                      </div>
                    </form>
            </div>
        </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
  var typingTimer;
  var doneTypingInterval=1020;

  LoadRelationshipManagers(); 

  $('#branchId').on('change', function() {
    if(this.value == '0'){
      LoadRelationshipManagers();
    }else{
      LoadBranchManagers(this.value);
    }
  });


$("#idNumber").on('keyup keydown change', function () {
    clearTimeout(typingTimer);
    if($("#idNumber").val()){
       typingTimer = setTimeout(idNumberDoneTyping, doneTypingInterval); 
    }
  });

$("#phoneNumber").on('keyup keydown change', function () {
    clearTimeout(typingTimer);
    if($("#phoneNumber").val()){
        typingTimer = setTimeout(phoneNumberDoneTyping, doneTypingInterval);
    }
});

$("#username").on('keyup keydown change', function () {
    clearTimeout(typingTimer);
    if($("#username").val()){
        typingTimer = setTimeout(usernameDoneTyping, doneTypingInterval);
    }
});

function phoneNumberDoneTyping(){
    clearRestrictionMessage('phoneNumber');
    var phoneNumber  = $("#phoneNumber").val();
    if(phoneNumber === "") {
        displayRestrictionMessage('idNumber','Phone Number is required...'); 
    }else{
        $.ajax({
            type:"POST",
            dataType: "json",
            url: "<?=Yii::app()->createUrl('profiles/checkPhoneNumberExistence');?>",
            data: {'phoneNumber':phoneNumber.substr(-9)},
            success: function(response){
                switch(response){
                    case 1000:
                    break;

                    case 1001:
                    var message = 'Phone Number already exists...';
                    displayRestrictionMessage('phoneNumber',message);
                    break;
                }
            }
        });
    }
}


function idNumberDoneTyping(){
    clearRestrictionMessage('idNumber');
    var idNumber = $("#idNumber").val();
    if(idNumber === ""){ 
        displayRestrictionMessage('idNumber','Id Number is required');
    }else{
        $.ajax({
            type:"POST",
            dataType: "json",
            url: "<?=Yii::app()->createUrl('profiles/checkIdNumberExistence');?>",
            data: {'idNumber':idNumber},
            success: function(response){
                switch(response){
                    case 0:
                    break;

                    case 1:
                    var message = 'Id Number already exists...';
                    displayRestrictionMessage('idNumber',message);
                    break;
                }
            }
        });
    }
}

function usernameDoneTyping(){
    clearRestrictionMessage('username');
    var username = $("#username").val();
    if(idNumber.length === ""){ 
        displayRestrictionMessage('username','Username is required'); 
    }else{
        $.ajax({
            type:"POST",
            dataType: "json",
            url: "<?=Yii::app()->createUrl('profiles/checkUsernameExistence');?>",
            data: {'username':username},
            success: function(response){
                switch(response){
                    case 1000:
                    break;

                    case 1001:
                    var message = 'Username already exists...';
                    displayRestrictionMessage('username',message);
                    break;
                }
            }
        });
    }
}

function displayRestrictionMessage(divId,message){
    $('#'+divId+'Error').show();
    $('#'+divId+'Error').html(message);
    $('#addProfileCmd').prop('disabled',true);
    document.getElementById(divId).style.borderColor     = 'red';
    document.getElementById(divId+'Error').style.display = "inline-block";
}

function clearRestrictionMessage(divId){
    $('#'+divId+'Error').hide();
    $('#addProfileCmd').prop('disabled',false);
    document.getElementById(divId).style.borderColor = "#e3e3e3";
}

function LoadRelationshipManagers(){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
    success: function(response) {
      var relationManager = $("#managerId");
      relationManager.empty();
      var option = "<option value='0'>-- RELATION MANAGERS --</option>";
      for(i=0; i<response.length; i++){
        option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
      }
      relationManager.html(option);
    }
  });
}

function LoadBranchManagers(branch){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadBranchRelationManagers');?>",
    data: {'branch':branch},
    success: function(response) {
      var staff = $("#managerId");
      staff.empty();
      var option = "<option value='0'>-- RELATION MANAGERS --</option>";
      for (i=0; i<response.length; i++) {
        option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
      }
      staff.html(option);
    }
  });
}

});
</script>