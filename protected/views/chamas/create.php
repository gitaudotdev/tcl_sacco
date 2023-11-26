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
						        <div class="col-md-4 col-lg-4 col-sm-12"> 
									<div class="form-group">
										<label>Name</label>
										<input type="text" class="form-control" placeholder="Chama Name" required="required" name="group">
									</div>
								</div>
						        <div class="col-md-4 col-lg-4 col-sm-12"> 
									<div class="form-group">
										<label>Registration Status</label>
										<select name="isRegistered" required="required" class="selectpicker">
											<option value="">-- REGISTRATION STATUS --</option>
											<option value="0">Not Registered</option>
											<option value="1">Registered</option>
                                        </select>
									</div>
								</div>
							</div><br/>
							<div class="row">
						        <div class="col-md-4 col-lg-4 col-sm-12"> 
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
						        <div class="col-md-4 col-lg-4 col-sm-12"> 
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
							</div><br>
							<div class="row">
						        <div class="col-md-4 col-lg-4 col-sm-12"> 
						          <div class="form-group">
										<label>Members</label>
										<select multiple="multiple" name="group_members[]" required="required" id="group_members_select" class="selectpicker">
											<option value="">-- SELECT MEMBERS --</option>
											<?php
											foreach($members as $member){
												echo '<option value="';echo $member->id; echo'">';echo $member->ProfileSavingAccount; '</option>';
											}
											?>
						        		</select>
									</div>
								</div>
						        <div class="col-md-4 col-lg-4 col-sm-12"> 
									<div class="form-group">
										<label>Leader</label>
										<select name="group_leader" required="required" id="group_leader_select" class="selectpicker" required="required">
											<option value="">-- GROUP LEADER --</option>
										</select>
									</div>
								</div>
							</div><br>
							<div class="row">
						        <div class="col-md-4 col-lg-4 col-sm-12">
									<label>Branch</label>
									<select name="branch_id" required="required" class="selectpicker" id="branch">
										<option value="0">-- SELECT BRANCH --</option>
										<?php
										foreach($branches as $branch){
											echo '<option value="';echo $branch->branch_id; echo'">';echo strtoupper($branch->name); '</option>';
										}
										?>
									</select>
								</div>
						        <div class="col-md-4 col-lg-4 col-sm-12">  
						          <div class="form-group">
										<label>Relationship Manager</label>
										<select name="collector" required="required" class="selectpicker" id="collector">
											<option value="">-- SELECT MANAGER --</option>
						        		</select>
									</div>
								</div>
							</div>
							<hr class="common_rule">
							<div class="row">
								<div class="col-md-4 col-lg-4 col-sm-12">
						          <div class="form-group">
						        	<a href="<?=Yii::app()->createUrl('chamas/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
									</div>
								</div>
						        <div class="col-md-4 col-lg-4 col-sm-12">
						           <div class="form-group">
						        	<input type="submit" class="btn btn-primary pull-right" value="Create Chama" id="group_cmd" name="group_cmd">
									</div>
								</div>
						  </div><br>
	        	</form>
	        </div>
        </div>
     </div>
  </div>
</div>
<script>
  $(function() {
	LoadRelationshipManagers();
	$('#branch').on('change', function() {
		if(this.value == '0'){
		LoadRelationshipManagers();
		}else{
		LoadBranchManagers(this.value);
		}
	});

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

 function LoadRelationshipManagers(){
  $.ajax({
    type:"POST",
    dataType: "json",
    url: "<?=Yii::app()->createUrl('reports/loadRelationManagers');?>",
    success: function(response){
      var relationManager = $("#collector");
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
      var staff = $("#collector");
      staff.empty();
      var option = "<option value='0'>-- RELATION MANAGERS --</option>";
      for (i=0; i<response.length; i++) {
        option += "<option value='" + response[i].managerID + "'>" + response[i].managerName + "</option>";
      }
      staff.html(option);
    }
  });
}
</script>
<script type="text/javascript">
  $(function () {
    $("#group_members_select").select2({
        placeholder: "-- SELECT MEMBERS --"
    });
  });
</script>