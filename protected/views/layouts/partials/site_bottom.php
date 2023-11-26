		</div>
  </div>
  <!--Page Footer-->
  <?php
    $organization = Organization::model()->findByPk(1);
    $orgName      = $organization->name;
    $orgWebsite   = 'https://'.$organization->website;
  ?>
  <?php SystemParts::displayFooterContent($orgName,$orgWebsite);?>
  </div>
</div>
<!-- Password modal -->
<div class="modal fade" id="passwordModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center">
        <h4 class="title">
         Change Password
      	</h4>
      </div>
      <div class="modal-body">
      	<div id="password_form">
        </div>
        <form method="post" action=""  autocomplete="off">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" class="form-control" placeholder="Current Password" name="current_password" id="current_password">
                        <br>
                        <span class="error" id="current_error">This field is required</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control" placeholder="New Password"
                         id="new_password">
                         <br>
                        <span class="error" id="new_error">This field is required</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm Password" id="confirm_password">
                        <br>
                        <span class="error" id="confirm_error">This field is required</span>
                        <span class="error" id="match_error">Passwords do not match.</span>
                    </div>
                </div>
          </div>
          <div class="modal-footer">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancel</button>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
              <button type="submit" class="btn btn-primary pull-right" id="change_password_cmd">
      	        Change Password
      	      </button>
            </div>
          </div>
      </form>
    </div>
    </div>
  </div>
</div>
<!-- End Modal-->
<!-- Confirm Logout modal -->
<div class="modal fade" id="confirmLogout" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center">
        <h4 class="title">
         	Confirm
      	</h4>
      </div>
      <div class="modal-body">
      	<h4 style="text-align: center;">Are you sure you want to log out?</h4>
      </div>
      <div class="modal-footer">
        <div class="col-md-6 col-lg-6 col-sm-12">
          <button type="button" class="btn btn-default" data-dismiss="modal">
          Cancel</button>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12">
          <a href="<?=Yii::app()->createUrl('site/logout');?>" class="btn btn-primary pull-right">
            Log Out
          </a>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- End Modal-->
<!-- Confirm Logout modal -->
<div class="modal fade" id="authenticateUser" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:50% !important;border-radius:0px !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center">
        <h4 class="title">Authentication Required</h4>
      </div>
      <div class="modal-body" style="margin-top: -7%;">
        <br>
        <h4 style="font-size:0.95em !important;">Please provide your password</h4>
        <form  autocomplete="off">
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <input type="text" class="form-control" value="<?=Yii::app()->user->username;?>" readonly="readonly">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="form-group">
                <input type="password" class="form-control" required="required" name="password" placeholder="Password" id="auth_password">
                <br>
                <span class="error" id="password_error">Password is required</span>
                <span class="error" id="wrong_password_error" style="margin-top: 2%!important;font-size:0.85em !important;">Wrong password provided.Try again with the correct password.</span>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="col-md-6 col-lg-6 col-sm-12">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancel
          </button>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12">
           <button type="submit" class="btn btn-primary pull-right" id="proceed_cmd">
            Proceed
          </button>
        </div>
      </div>
    </form>
    </div>
    </div>
  </div>
</div>
<!-- End Modal-->
<!-----------  Time Out Modal  --------->
<div class="modal fade" id="timeOutDialog" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: center;">
      <div class="modal-header justify-content-center">
        <h4 class="title">
          <i class="now-ui-icons ui-1_bell-53"></i>
          Session Timeout
        </h4>
      </div>
      <div class="modal-body" style="padding-top:5% !important;">
          You will be logged out in
         <br><strong id="timeOutCounter"></strong><br>Seconds
      </div>
      <div class="modal-footer">
        <div style="margin-right:20% !important;">
          <div class="col-md-6 col-lg-6 col-sm-12">
            <button class="btn btn-default" id="keepActive">Keep Active</button>
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12">
            <button class="btn btn-success" id="forceLogout">Log Out</button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
<!----------End Timeout Modal ------------->
</body>
<?php SystemParts::displayFooterInformation();?>
<?php $this->renderPartial('//includes/bottom_part');?>
<script>
  $(()=> {
    $('.error').hide();
    $("#change_password_cmd").click(() => {
      // validate and process form here
      $('.error').hide();
      var current_password = $("input#current_password").val();
      if(current_password === ""){
        $("span#current_error").show();
        $("input#current_password").focus();
        return false;
      }
      var new_password = $("input#new_password").val();
      if(new_password === ""){
        $("span#new_error").show();
        $("input#new_password").focus();
        return false;
      }
      var confirm_password = $("input#confirm_password").val();
      if(confirm_password === ""){
        $("span#confirm_error").show();
        $("input#confirm_password").focus();
        return false;
      }
      if(new_password != confirm_password){
        $("span#match_error").show();
        $("input#new_password").focus();
        $("input#confirm_password").focus();
        return false;
      }
      var dataString = 'current_password='+ current_password + '&new_password=' + new_password + '&confirm_password=' + confirm_password;
      $.ajax({
        type:"POST",
        url: "<?=Yii::app()->createUrl('dashboard/password');?>",
        data: dataString,
        success: function(response) {
          switch(response){
            case "no_match":
            displayMessage('Passwords do not match. Please try again with matching passwords.','Operation Failure',response);
            clearInputFields();
            break;

            case "success":
            displayMessage('Password successfully changed. Use the changed password on next system log in.','Operation Success',response);
            clearInputFieldsAndHideSubmitButton();
            break;

            case "incorrect_password":
            displayMessage('Incorrect current password provided. Please try again with a valid current password.','Operation Failure',response);
            clearInputFields();
            break;

            case "no_change":
            displayMessage('New password cannot be the same as current password.','Operation Failure',response);
            clearInputFields();
            break;
          }
        }
      });
      return false;
    });
  });

  let displayMessage= (message,subject,divID) => {
      $('#password_form').html("<div id='"+divID+"'></div>");
        $('#'+divID).html("<h5>"+subject+"</h5>")
        .append("<p>"+message+"</p>")
        .hide()
        .fadeIn(1500, function() {
          $('#'+divID);
        }).fadeOut(3150, function() {
          $('#'+divID);
        });
  }

  let clearInputFields = () => {
    $("input#current_password").val('');
    $("input#new_password").val('');
    $("input#confirm_password").val('');
  }

  let clearInputFieldsAndHideSubmitButton= () => {
   clearInputFields();
   $("#change_password_cmd").hide();
  }

  let validateFixedPaymentInitiationForm = (value) =>{
    let expenseSelector = $("#supplier_expense_"+value).val();
    let expenseAmount = $("#supplier_amount_"+value).val();
    if(expenseSelector === ''){
      isValid = 1001;
    }else{
      isValid = expenseAmount === '' ? 1003 : 1000;
    }
    return isValid;
  }

  let clearFixedPaymentFormErrors= (value) =>{
    $("#supplier_expense_error_"+value).html("");
    $("#supplier_amount_error_"+value).html("");
  }

  /*PLACE AT EOF*/
  $(document).ready(function(){

    $('#select_suppliers').click(function(){
        if($(this).is(":checked")){
          $(".supplier_user_check").prop("checked",true); 
        }else if($(this).is(":not(:checked)")){
          $(".supplier_user_check").prop("checked",false); 
        }
    });

    $("#initiate_fixed_cmd").click((event)=>{
      event.preventDefault();
      $(".supplier_user_check").each(function(){
          if($(this).is(':checked')){
            $('.error').show();
            var value=$(this).val();
            clearFixedPaymentFormErrors(value);
            switch(validateFixedPaymentInitiationForm(value)){
              case 1000:
              $("#initiate_fixed_form").submit();
              break;

              case 1001:
              $("#supplier_expense_error_"+value).html("Please select an expense type");
              return false;
              break;

              case 1003:
              $("#supplier_amount_error_"+value).html("Please provide an expense amount");
              return false;
              break;
            }
          }
      });
    });

    $('.daterange').daterangepicker();
  });

  $(".selectpicker").select2({
      width: 'resolve'
  });
  
  var newDate= new Date();
  var subtract=12*18;
  newDate.setMonth(newDate.getMonth()-subtract);
  
  $('#datepicker').datepicker({
    format: 'yyyy-mm-dd',
    orientation: 'bottom',
    endDate:newDate,
  });

  $('#month_date').datepicker({
    format: 'mm-yyyy',
    orientation: 'bottom'
  });

  $('#normaldatepicker').datepicker({
    format: 'yyyy-mm-dd',
    orientation: 'bottom',
  });
  $('#start_date').datepicker({
    format: 'yyyy-mm-dd',
    orientation: 'bottom',
  });
  $('#end_date').datepicker({
    format: 'yyyy-mm-dd',
    orientation: 'bottom',
  });
</script>
<script type="text/javascript">
  $(".modal").on("hidden.bs.modal", function(){
    $("input#auth_password").val("");
  });

  let Authenticate = (url) =>{
    $('#authenticateUser').modal({backdrop: 'static',keyboard: false,show:true});
    $('.error').hide();
    $('#proceed_cmd').click(function() {
      $('.error').hide();
        var password = $("input#auth_password").val();
        if(password === ""){
          $("span#password_error").show();
          $("input#auth_password").focus();
          return false;
        }
        var dataString = 'password='+ password;
        $.ajax({
          type:"POST",
          url: "<?=Yii::app()->createUrl('dashboard/confirmPassword');?>",
          data: dataString,
          success: (response) => {
            switch(response){
              case "failed":
              $("span#wrong_password_error").show();
              return false;
              break;

              case "authorized":
              $('#authenticateUser').modal({
                show:false
              });
              window.location.href =url;
              url='';
              break;
            }
          }
        });
      return false;
  });
}
</script>
<script type="text/javascript">
    var totalSessionTimeout = <?=Yii::app()->session->getTimeout();?>;
    var countDown = 60;
    var secondsIdle = 2100; 
    var logoutURL = <?="'".Yii::app()->createUrl('site/logout')."'"; ?>;

    var idleTimeCountDown = countDown;
    var idleTimeInSeconds = secondsIdle;
    var showTimeoutModal = true;
    function sessionTimer(){
      totalSessionTimeout = totalSessionTimeout-1; idleTimeInSeconds = idleTimeInSeconds-1;
      if (totalSessionTimeout <= 0 ){ 
        clearInterval(sessionCounter);
        window.location.href = logoutURL;
        return;
      }

      if(totalSessionTimeout <= countDown || idleTimeInSeconds <= 0){
        totalSessionTimeout = totalSessionTimeout<=countDown?totalSessionTimeout:countDown;
        if(showTimeoutModal){
            $('#timeOutDialog').modal({backdrop: 'static',keyboard: false,show:true});
            showTimeoutModal = false;
        }
        $('#timeOutCounter').text(totalSessionTimeout);
      }

      $('#keepActive').onclick(){
          if(this.click) {
            location.reload();
          }
      };

      $('#forceLogout').onclick(){
          if(this.click){
            window.location.href = logoutURL;
            return;
          }
      };
  }
  var sessionCounter = setInterval(sessionTimer, 1000); 

</script>
<script type="text/javascript">
  $(document).ready(()=> {
    $('#example tfoot th').each( () => {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" class="form-control" style="height:20px;"/>' );
    });
    var table = $('#example').DataTable();
    table.columns().every(() => {
        hideTopDataTableSearchDiv();
        var that = this;
        $('#example_filter input', this.footer() ).on('keyup change', function () {
            if(that.search() !== this.value ) {
                that
                    .search(this.value)
                    .draw();
            }
        } );
    } );
    $('#example tfoot tr').appendTo('#example thead');
    history.pushState(null, null, location.href);
    window.onpopstate = () => {
      history.go(1);
    };
});

let hideTopDataTableSearchDiv= () => {
  $('#example_filter').hide();
  $('#example_length').hide();
}
</script>