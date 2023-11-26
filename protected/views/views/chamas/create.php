<?php
$this->pageTitle=Yii::app()->name . ' - New Chama';
$this->breadcrumbs=array(
	'Chamas'=>array('admin'),
	'Create'=>array('create'),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-body">
			<div class="card-header">
				<h5 class="title">Create Chama</h5>
				<hr class="common_rule">
			</div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<form id="form" action="<?=Yii::app()->createUrl('chamas/create');?>" method="POST">
	        			<div class="row">
						        <div class="col-md-2 col-lg-2 col-sm-12"> 
									<div class="form-group">
										<label>Name</label>
										<input type="text" class="form-control" placeholder="Chama Name" required="required" name="group">
									</div>
								</div>
						        <div class="col-md-2 col-lg-2 col-sm-12"> 
									<div class="form-group">
										<label>Registration Status</label>
										<select name="isRegistered" required="required" class="selectpicker">
											<option value="">-- REGISTRATION STATUS --</option>
											<option value="0">Not Registered</option>
											<option value="1">Registered</option>
                                        </select>
									</div>
								</div>
						        <div class="col-md-2 col-lg-2 col-sm-12"> 
									<div class="form-group">
										<label>Organization</label>
										<select name="organizationId" required="required" class="selectpicker">
											<option value="">-- GROUP ORGANIZATION --</option>
											<?php
											foreach($organizations as $organization){
												echo '<option value="';echo $organization->id; echo'">';echo $organization->name; '</option>';
											}
											?>
                                        </select>
									</div>
								</div>
						        <div class="col-md-2 col-lg-2 col-sm-12"> 
									<div class="form-group">
										<label>Location</label>
										<select name="locationId" required="required" class="selectpicker">
											<option value="">-- GROUP LOCATION --</option>
											<?php
											foreach($locations as $location){
												echo '<option value="';echo $location->id; echo'">';echo $location->name.'-'.$location->town; '</option>';
											}
											?>
                                        </select>
									</div>
								</div>
						        <div class="col-md-2 col-lg-2 col-sm-12"> 
						          <div class="form-group">
										<label>Members</label>
										<select multiple="multiple" name="group_members[]" required="required" id="group_members_select" class="selectpicker">
											<option value="">-- GROUP MEMBERS --</option>
											<?php
											foreach($members as $member){
												echo '<option value="';echo $member->id; echo'">';echo $member->ProfileSavingAccount; '</option>';
											}
											?>
						        		</select>
									</div>
								</div>
						        <div class="col-md-2 col-lg-2 col-sm-12"> 
									<div class="form-group">
										<label>Leader</label>
										<select name="group_leader" required="required" id="group_leader_select" class="selectpicker" required="required">
											<option value="">-- GROUP LEADER --</option>
										</select>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
							  <div class="col-md-12 col-lg-12 col-sm-12">
							  <label style="margin-bottom:-2%;">Relationship Manager</label>
							  <hr>
							    <?php
								    if(!empty($managers)){
								    foreach($managers as $manager){
								    	echo '<div class="col-md-3 col-lg-3 col-sm-12">
												<div class="form-check-radio">
													<label class="form-check-label">
										        <input class="form-check-input" type="radio" name="collector" value="';echo $manager->id;
										         echo'">
										         <span class="form-check-sign"></span>';
										         echo $manager->ProfileFullName; echo '
													</label>
												</div>
											</div>';
								    }
							    }
							    ?>
							  </div>
							</div>
							<hr class="common_rule">
							<div class="row">
						    <div class="col-md-6 col-lg-6 col-sm-12">
						        <div class="form-group">
						        	<input type="submit" class="btn btn-primary" value="Create Chama" id="group_cmd" name="group_cmd">
									</div>
								</div>
								<div class="col-md-6 col-lg-6 col-sm-12">
						      <div class="form-group">
						        	<a href="<?=Yii::app()->createUrl('chamas/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
									</div>
								</div>
							</div>
	        	</form>
	        </div>
        </div>
     </div>
  </div>
</div>
<script>
  $(function() {
    $('#group_members_select').on('change',function() {
        var selected_val = $( "#group_leader_select" ).val();
        var selected_text = $( "#group_leader_select" ).text();
        $('#group_leader_select option[value!=selected_val]').remove();
        $("#group_members_select option:selected").each(function () {
          var $this = $(this);
          if ($this.length) {
              var optionExists = ($('#group_leader_select option[value=' + $(this).val() + ']').length > 0);
              if(!optionExists){
                  $('#group_leader_select').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
                  var exists = 0 != $('#group_leader_select option[value='+selected_val+']').length;
                 
                  if (exists){
                      $("#group_leader_select").val(selected_val);
                  }
                  else{
                   $("#group_leader_select").val($("#group_leader_select option:first").val());
                  }
              }
          }
      });
    });
  });
</script>
<script type="text/javascript">
  $(function () {
    $("#group_members_select").select2({
        placeholder: "Select Members"
    });
  });
</script>