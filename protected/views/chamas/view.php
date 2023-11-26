<?php
$this->pageTitle=Yii::app()->name . ' : Chama Details';
$this->breadcrumbs=array(
	'Chamas'=>array('admin'),
	'Details'=>array('chamas/'.$model->id)
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
  <div class="col-md-12 col-lg-12 col-sm-12">
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
        <div class="card">
            <div class="card-body">
                <div class="card-header">
                  <h5 class="title">Chama Details</h5>
                  <hr class="common_rule">
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <table class="table table-bordered table-hover">
                          <tr>
                            <td>Name</td>
                            <td><strong><?=$model->ChamaName;?></strong></td>
                          </tr>
                          <tr>
                            <td>Status</td>
                            <td><strong><?=$model->ChamaStatus;?></strong></td>
                          </tr>
                          <tr>
                            <td>Location</td>
                            <td><strong><?=$model->ChamaLocation;?></strong></td>
                          </tr>
                          <tr>
                            <td>Organization</td>
                            <td><strong><?=$model->ChamaOrganization;?></strong></td>
                          </tr>
                          <tr>
                            <td>Leader</td>
                            <td><strong><?=$model->GroupLeaderName;?></strong></td>
                          </tr>
                          <tr>
                            <td>Leader's #</td>
                            <td><strong><?=$model->GroupLeaderPhoneNumber;?></strong></td>
                          </tr>
                          <tr>
                            <td>Relation Manager</td>
                            <td><strong><?=$model->GroupCollectorName;?></strong></td>
                          </tr>
                        </table>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                            <table class="table table-bordered table-hover">
                              <tr>
                                <td>Branch</td>
                                <td><strong><?=$model->ChamaBranch;?></strong></td>
                              </tr>
                              <tr>
                                <td>Membership Count</td>
                                <td><strong><?=$model->ChamaMembershipCount;?></strong></td>
                              </tr>
                              <tr>
                                <td>Total Savings</td>
                                <td><strong> KES <?=number_format($model->ChamaTotalSavings,2);?> /-</strong></td>
                              </tr>
                              <tr>
                                <td>Total Loans</td>
                                <td><strong> KES <?=number_format($model->ChamaTotalLoans,2);?> /-</strong></td>
                              </tr>
							                <tr>
                                <td>Created By</td>
                                <td><strong><?=$model->ChamaCreatedBy;?></strong></td>
                              </tr>
							                <tr>
                                <td>Created On</td>
                                <td><strong><?=$model->ChamaCreatedAt;?></strong></td>
                              </tr>
                            </table>
                          </div>
                         <div class="col-md-12 col-lg-12 col-sm-12">
                           <br>
                           <hr class="common_rule"/>
                          </div>
                          <div class="col-md-2 col-lg-2 col-sm-12">
                            <a href="<?=Yii::app()->createUrl('chamas/admin');?>" class="btn btn-info pull-left"><i class="fa fa-arrow-left"></i> Previous</a>
                          </div>
                          <?php if(!empty($borrowers) && Navigation::checkIfAuthorized(296) === 1 ):?>
                            <div class="col-md-2 col-lg-2 col-sm-12">
                              <button class="btn btn-success pull-left" onclick="AddBorrowers()"> Add Member</button>
                            </div>
                          <?php endif;?>
                          <?php if(Navigation::checkIfAuthorized(297) === 1):?>
                            <div class="col-md-2 col-lg-2 col-sm-12">
                              <button class="btn btn-primary pull-left" onclick="RemoveMembers()">Remove Members</button>
                            </div>
                          <?php endif;?>
                      </div>
                  </div><br>
            </div>
        </div>
        <!--MEMBERS TABULATED-->
        <div class="card">
          <div class="card-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                  <h5 class="title">Member Details</h5>
                  <hr class="common_rule">
              </div>     
              <div class="col-md-12 col-lg-12 col-sm-12">
                  <?php if($members !=0 && Navigation::checkIfAuthorized(298) === 1 ):?>
                    <?php Tabulate::getChamaMembersTabulation($members);?>
                  <?php else:?>
                      <br>
                      <p style='color:#f90101;font-size:1.30em;'>
                      *** No available members onboarded to the chama or you do not have sufficient permissions to view chama members****
                      </p>
                  <?php endif;?>
                  <br><br>
              </div>
          </div>
      </div>
    </div>
</div>
<script type="text/javascript">
	function AddBorrowers(){
		$('#AddBorrowers').modal({backdrop:'static',keyboard: false,show:true});

	}
	function RemoveMembers(){
		$('#removeMember').modal({backdrop:'static',keyboard: false,show:true});
	}
</script>
<!-- Add Borrowers modal -->
<div class="modal fade" id="AddBorrowers" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center" style="padding:2.5% !important;">
        <h4 class="title">
          Add Chama Members
        </h4>
      </div>
      <div class="modal-body">
        <form method="post" action="<?=Yii::app()->createUrl('chamas/newMembers');?>">
        	<div class="row">
            <input type="hidden" name="group" value="<?=$model->id;?>">
        		<div class="col-sm-12 col-lg-12 col-md-12">
	        		<h5>Select Members</h5>
	        	</div>
            <br>
        		<div class="col-sm-12 col-lg-12 col-md-12">
              <select multiple="multiple" class="selectpicker" required="required" name="group_members[]" style="width:100% !important;">
                <?php if(!empty($borrowers)):?>
                  <?php foreach($borrowers as $borrower):?>
                     <option value="<?=$borrower->id;?>">
                        <?=$borrower->ProfileSavingAccount;?>
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
        <form method="post" action="<?=Yii::app()->createUrl('chamas/remove');?>">
      	<input type="hidden" name="group_id" value="<?=$model->id;?>">
        <div class="col-sm-12 col-lg-12 col-md-12">
          <label>Select Member</label>
          <select multiple="multiple" class="selectpicker" required="required" name="borrowers[]" style="width:100% !important;">
            <?php if(!empty($memberswithoutLeader)):?>
              <?php foreach($memberswithoutLeader as $borrower):?>
                 <option value="<?=$borrower->id;?>">
                    <?=$borrower->ProfileSavingAccount;?>
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