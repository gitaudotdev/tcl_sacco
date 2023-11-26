<?php
/* @var $this BorrowerController */
/* @var $model Borrower */
$this->pageTitle=Yii::app()->name . ' - Microfinance  Create Member';
$this->breadcrumbs=array(
  'Members'=>array('borrower/admin'),
  'Create'=>array('borrower/create')
);
?>
<div class="row">
  <div class=" col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Create Member</h5>
            <hr>
        </div>
        <div class="card-body">
          <div class=" col-md-12 col-lg-12 col-sm-12">
            <form id="regForm" enctype="multipart/form-data"  method="post" action="<?=Yii::app()->createUrl('borrower/createBorrower');?>">
                <!-- One "tab" for each step in the form: -->
                <div class="tab">
                  <h5 class="title">Personal Info</h5>
                  <hr class="modified_rule">
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_first_name" placeholder="First Name" required="required">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_last_name" placeholder="Last Name" required="required">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                           <select name="brw_gender" class="selectpicker" required="required">
                            <option value="">-- GENDER --</option>
                            <option value="female">Female</option>
                            <option value="male">Male</option>
                          </select>
                        </div>
                    </div>
                     <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_dob" placeholder="Date of Birth" required="required" id="datepicker">
                        </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_id_number" placeholder="ID Number" required="required">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_phone" placeholder="Phone Number" required="required">
                        </div>
                    </div>
                     <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="email"  name="brw_email" placeholder=" Personal Email Address" required="required">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_alt_phone" placeholder="Alternative Phone Number" required="required">
                        </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                           <select name="brw_segment" class="selectpicker" required="required">
                            <option value="">-- MEMBER SEGMENT --</option>
                            <option value="0">Small</option>
                            <option value="1">Premier</option>
                            <option value="2">Corporate</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                           <select name="brw_branch" class="selectpicker" required="required">
                            <option value="">-- BRANCHES --</option>
                            <?php
                            if(!empty($branches)){
                              foreach($branches as $branch){
                                echo "<option value='";echo $branch->branch_id;echo"'>$branch->name</option>";
                              }
                            }
                            ?>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                           <select name="brw_rm" class="selectpicker" required="required">
                            <option value="">-- RELATION MANAGERS --</option>
                            <?php
                            if(!empty($managers)){
                              foreach($managers as $manager){
                                echo "<option value='";echo $manager->user_id;echo"'>$manager->StaffFullName</option>";
                              }
                            }
                            ?>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_referred_by" placeholder="Name of Person Who Referred Borrower" required="required">
                        </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_referee_phone" placeholder="Phone Number of Person Referred Member" required="required">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_address" placeholder="Address" required="required">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_city" placeholder="City" required="required">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                          <input class="form-control" type="text"  name="brw_residence_land_mark" placeholder="Residence Land Mark" required="required">
                        </div>
                    </div>
                  </div>
                </div>
                <div class="tab">
                  <h5 class="title">Employment Info</h5>
                  <hr class="modified_rule">
                  <br>
                  <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <div class="form-group">
                         <select name="brw_working_status" class="selectpicker" required="required"
                          style="width: 100%!important;">
                          <option value="">-- EMPLOYMENT STATUS --</option>
                          <option value="0">Employee</option>
                          <option value="1">Owner</option>
                          <option value="2">Student</option>
                          <option value="3">Overseas Worker</option>
                        </select>
                        </div>
                    </div>
                     <div class="col-md-3 col-lg-3 col-sm-12">
                          <div class="form-group">
                            <input class="form-control" type="text"  name="brw_employer" placeholder="Employer" required="required">
                          </div>
                      </div>
                      <div class="col-md-3 col-lg-3 col-sm-12">
                          <div class="form-group">
                            <input class="form-control" type="text"  name="brw_date_employed" placeholder="Date Employed" required="required" id="start_date">
                          </div>
                      </div>
                      <div class="col-md-3 col-lg-3 col-sm-12">
                          <div class="form-group">
                            <input class="form-control" type="text"  name="brw_job_title" placeholder="Job Title" required="required">
                          </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                       <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="email"  name="brw_job_email" placeholder="Job Email" required="required">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="brw_office_phone" placeholder="Office Phone" required="required">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="brw_office_location" placeholder="Office Location" required="required">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="brw_office_land_mark" placeholder="Office Land Mark" required="required">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab">
                  <h5 class="title">Next of Kin Info</h5>
                  <hr class="modified_rule">
                  <br>
                  <div class="row">
                       <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="kin_first_name" placeholder="Kin First Name" required="required">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="kin_last_name" placeholder="Kin Last Name" required="required">
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="kin_phone" placeholder="0712335678" required="required">
                            </div>
                        </div>
                       <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="kin_relation" placeholder="Relationship to Kin" required="required">
                            </div>
                        </div>
                  </div>
                </div>

                <div class="tab">
                  <h5 class="title">Referee Info</h5>
                  <hr class="modified_rule">
                  <br>
                  <div class="row">
                       <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="ref_first_name" placeholder="Referee First Name" required="required">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="ref_last_name" placeholder="Referee Last Name" required="required">
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="ref_phone" placeholder="0712345678" required="required">
                            </div>
                        </div>
                  </div>
                  <br>
                  <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="ref_employer" placeholder="Referee Employer" required="required">
                            </div>
                        </div>
                       <div class="col-md-4 col-lg-4 col-sm-12">
                            <div class="form-group">
                              <input class="form-control" type="text"  name="ref_relation" placeholder="Relationship to Referee" required="required">
                            </div>
                        </div>
                  </div>
                </div>

                <div style="overflow:auto;">
                  <div style="float:right;">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)" class="btn btn-info">Previous</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)" class="btn btn-primary">Next</button>
                  </div>
                </div>
                <!-- Circles which indicates the steps of the form: -->
                <div style="text-align:center;margin-top:40px;margin-bottom:5% !important;">
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                  <span class="step"></span>
                </div>
            </form>
          </div>
        </div>
     </div>
  </div>
</div>
<script src="<?=Yii::app()->request->baseUrl;?>/scripts/jquery.min.js" ></script>
<script type="text/javascript">
  var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
      // This function will display the specified tab of the form ...
      var x = document.getElementsByClassName("tab");
      x[n].style.display = "block";
      // ... and fix the Previous/Next buttons:
      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Create Borrower";
      } else {
        document.getElementById("nextBtn").innerHTML = "Next";
      }
      // ... and run a function that displays the correct step indicator:
      fixStepIndicator(n)
    }

    function nextPrev(n) {
      // This function will figure out which tab to display
      var x = document.getElementsByClassName("tab");
      // Exit the function if any field in the current tab is invalid:
      if (n == 1 && !validateForm()) return false;
      // Hide the current tab:
      x[currentTab].style.display = "none";
      // Increase or decrease the current tab by 1:
      currentTab = currentTab + n;
      // if you have reached the end of the form... :
      if (currentTab >= x.length) {
        //...the form gets submitted:
        document.getElementById("regForm").submit();
        return false;
      }
      // Otherwise, display the correct tab:
      showTab(currentTab);
    }

  function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    // A loop that checks every input field in the current tab:
    for (i = 0; i < y.length; i++) {
      // If a field is empty...
      if (y[i].value == "") {
        // add an "invalid" class to the field:
        y[i].className += " invalid";
        // and set the current valid status to false:
        valid = false;
      }
    }
    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
      document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid; // return the valid status
  }

  function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
      x[i].className = x[i].className.replace(" active", "");
    }
    //... and adds the "active" class to the current step:
    x[n].className += " active";
  }
</script>