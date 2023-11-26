<?php
$this->pageTitle=Yii::app()->name . ' - Update Member Group';
$this->breadcrumbs=array(
	'Groups'=>array('admin'),
	'Update'=>array('borrowergroup/update/'.$model->group_id),
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
<style type="text/css">
	.mini_actions{
		color:#ffb236; 
		cursor: pointer;
	}
	.remove_member{
		color:#f96332;
		cursor:pointer;
		font-size:16px;
		font-weight:bold;
		vertical-align:middle;
	}
  h4{
    font-size: 0.95em !important;
  }
  h5{
    font-size:1.05em !important;
  }
</style>
<div class="row">
  <div class="col-md-12">
    <div class="card">
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
        <div class="card-body">
            <div class="card-header">
              <h5 class="title">Update Group Details</h5>
              <hr class="common_rule">
            </div>
            <br>
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <div style="margin-top: -2% !important;">
              <?php if(!empty($borrowers) && Navigation::checkIfAuthorized(134) === 1 ):?>
              <button class="btn btn-info" onclick="AddBorrowers()"> Add Member</button>
              <?php endif;?>
              <?php if(Navigation::checkIfAuthorized(136) === 1):?>
              <button class="btn btn-primary" onclick="ChangeGroupName()">Update Group</button>
              <?php endif;?>
              <?php if(Navigation::checkIfAuthorized(135) === 1):?>
                <button class="btn btn-warning" onclick="RemoveMembers()">Remove Members</button>
              <?php endif;?>
            </div>
            <hr class="common_rule">
        		<h5>Group Name: <span  id="group_name_reload"><?=$model->name;?></span></h5>
        		<h5>Group Leader: <span><?=$model->getGroupLeaderName();?></span></h5>
        		<h5>Group Collector: <span><?=$model->getGroupCollectorName();?></span></h5>
            <hr class="common_rule">
	        	<form>
	        		<div class="row">
	        			<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom: 1.2%;">
							  	<h5 class="title">Group Members</h5>
							  </div>
							</div>
							<div class="row">
								<?php ProfileEngine::displayGroupMembers($members);?>
							</div>
							<hr style="border-top: 0px !important;margin-bottom: 5% !important;">
	        	</form>
	        </div>
        </div>
     </div>
  </div>
<script type="text/javascript">
	function ChangeGroupName(){
		$('#groupNameModal').modal({show:true});
	}

	function AddBorrowers(){
		$('#AddBorrowers').modal({backdrop:'static',keyboard: false,show:true});

	}
	function RemoveMembers(){
		$('#removeMember').modal({backdrop:'static',keyboard: false,show:true});
	}
</script>
<!-- Change Group Name modal -->
<div class="modal fade" id="groupNameModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:35% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center" style="padding:2.5% !important;">
        <h4 class="title">
          Update Group Details
        </h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?=Yii::app()->createUrl('borrowergroup/changename');?>">
        	<div class="row">
            <input type="hidden" name="group" value="<?=$model->group_id;?>">
        		<div class="col-sm-12 col-lg-12 col-md-12">
        			<div class="form-group">
                <h5>Group Name</h5>
	        			<input type="text" name="group_name" class="form-control" value="<?=$model->name;?>" required="required">
	        		</div>
        		</div>
            <div class="col-sm-12 col-lg-12 col-md-12">
              <div class="form-group">
                <h5>Group Leader</h5>
                <select class="selectpicker" required="required" name="group_leader" style="width:100% !important;">
                  <?php if(!empty($members)):?>
                    <?php foreach($members as $member):?>
                       <option value="<?=$member->id;?>"<?php if($model->group_leader === $member->id) echo 'selected="selected"';?>>
                         <?=$member->ProfileFullName;?>
                       </option>
                    <?php endforeach;?>
                  <?php endif;?>
                </select>
              </div>
            </div>
            <div class="col-sm-12 col-lg-12 col-md-12">
              <div class="form-group">
                <h5>Group Collector</h5>
                <select class="selectpicker" required="required" name="group_collector" style="width:100% !important;">
                  <?php if(!empty($collectors)):?>
                    <?php foreach($collectors as $collector):?>
                       <option value="<?=$collector->id;?>"<?php if($model->collector_id === $collector->id) echo 'selected="selected"';?>>
                         <?=$collector->ProfileFullName;?>
                       </option>
                    <?php endforeach;?>
                  <?php endif;?>
                </select>
              </div>
            </div>
        	</div>
      </div>
      <div class="modal-footer">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <button type="submit" class="btn btn-primary" name="update_name_cmd">
          Update</button>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <button type="button" class="btn btn-default" data-dismiss="modal">
          Cancel </button>
        </div>
      </div>
    </form>
    </div>
    </div>
  </div>
<!-- End Modal-->
<!-- Add Borrowers modal -->
<div class="modal fade" id="AddBorrowers" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center" style="padding:2.5% !important;">
        <h4 class="title">
          Add Group Members
        </h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?=Yii::app()->createUrl('borrowergroup/newMembers');?>">
        	<div class="row">
            <input type="hidden" name="group" value="<?=$model->group_id;?>">
        		<div class="col-sm-12 col-lg-12 col-md-12">
	        		<h5>Select Members</h5>
	        	</div>
            <br>
        		<div class="col-sm-12 col-lg-12 col-md-12">
              <select multiple="multiple" class="selectpicker" required="required" name="group_members[]" style="width:100% !important;">
                <?php if(!empty($borrowers)):?>
                  <?php foreach($borrowers as $borrower):?>
                     <option value="<?=$borrower->id;?>">
                        <?=$borrower->ProfileFullName;?>
                     </option>
                  <?php endforeach;?>
                <?php endif;?>
              </select>
        		</div>
        	</div>
      </div>
      <div class="modal-footer">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <button type="submit" class="btn btn-primary pull-left" name="add_borrower_cmd">
            Add Member
          </button>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
          Cancel Action</button>
        </div>
      </div>
     </form>
    </div>
    </div>
  </div>
<!-- End Modal-->
<!-- Remove Member modal -->
<div class="modal fade" id="removeMember" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center" style="padding:2.5% !important;">
        <h4 class="title">
         	Remove Members
      	</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?=Yii::app()->createUrl('borrowergroup/remove');?>">
      	<input type="hidden" name="group_id" value="<?=$model->group_id;?>">
        <div class="col-sm-12 col-lg-12 col-md-12">
          <label>Select Member</label>
          <select multiple="multiple" class="selectpicker" required="required" name="borrowers[]" style="width:100% !important;">
            <?php if(!empty($memberswithoutLeader)):?>
              <?php foreach($memberswithoutLeader as $borrower):?>
                 <option value="<?=$borrower->id;?>">
                    <?=$borrower->ProfileFullName;?>
                 </option>
              <?php endforeach;?>
            <?php endif;?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <button type="submit" name="remove_cmd" class="btn btn-primary">
            Remove Member
          </button>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancel Action
          </button>
        </div>
      </div>
    </form>
    </div>
    </div>
  </div>
<!-- End Modal-->