<?php
$this->pageTitle=Yii::app()->name . ' - New Member Group';
$this->breadcrumbs=array(
	'Groups'=>array('admin'),
	'Create'=>array('create'),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-body">
			<div class="card-header">
				<h5 class="title">Create Member Group</h5>
				<hr class="common_rule">
			</div>
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<form id="form" action="<?=Yii::app()->createUrl('borrowergroup/create');?>" method="POST">
	        			<div class="row">
						       <div class="col-md-4 col-lg-4 col-sm-12"> 
									<div class="form-group">
										<label>Name</label>
										<input type="text" class="form-control" placeholder="Group Name" required="required" name="group">
									</div>
								</div>
								<div class="col-md-4 col-lg-4 col-sm-12">
						          <div class="form-group">
										<label>Group Members</label>
										<select multiple="multiple" name="group_members[]" required="required" id="group_members_select" class="selectpicker">
											<option value="">-- GROUP MEMBERS --</option>
											<?php
											foreach($borrowers as $borrower){
												echo '<option value="';echo $borrower->id; echo'">';echo $borrower->ProfileFullName; '</option>';
											}
											?>
						        		</select>
									</div>
								</div>
								<div class="col-md-4 col-lg-4 col-sm-12">
									<div class="form-group">
										<label>Group Leader</label>
										<select name="group_leader" required="required" id="group_leader_select" class="selectpicker" required="required">
											<option value="">-- GROUP LEADER --</option>
										</select>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
							  <div class="col-md-12 col-lg-12 col-sm-12">
							  <label style="margin-bottom:-2%;">Account Manager</label>
							  <hr>
							    <?php
								    if(!empty($collectors)){
								    foreach($collectors as $collector){
								    	echo '<div class="col-md-3 col-lg-3 col-sm-12">
												<div class="form-check-radio">
													<label class="form-check-label">
										        <input class="form-check-input" type="radio" name="collector" value="';echo $collector->id;
										         echo'">
										         <span class="form-check-sign"></span>';
										         echo $collector->ProfileFullName; echo '
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
						        	<input type="submit" class="btn btn-primary" value="Create Group" id="group_cmd" name="group_cmd">
									</div>
								</div>
								<div class="col-md-6 col-lg-6 col-sm-12">
						      <div class="form-group">
						        	<a href="<?=Yii::app()->createUrl('borrowergroup/admin');?>" class="btn btn-default pull-right">Cancel Action</a>
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
        placeholder: "Select Members or Search by Name"
    });
  });
</script>