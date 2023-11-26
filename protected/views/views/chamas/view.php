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
                            <td><?=$model->ChamaName;?></td>
                          </tr>
                          <tr>
                            <td>Status</td>
                            <td><?=$model->ChamaStatus;?></td>
                          </tr>
                          <tr>
                            <td>Location</td>
                            <td><?=$model->ChamaLocation;?></td>
                          </tr>
                          <tr>
                            <td>Organization</td>
                            <td><?=$model->ChamaOrganization;?></td>
                          </tr>
                          <tr>
                            <td>Leader</td>
                            <td><?=$model->GroupLeaderName;?></td>
                          </tr>
                          <tr>
                            <td>Relation Manager</td>
                            <td><?=$model->GroupCollectorName;?></td>
                          </tr>
                        </table>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                            <table class="table table-bordered table-hover">
                              <tr>
                                <td>Branch</td>
                                <td><?=$model->ChamaBranch;?></td>
                              </tr>
                              <tr>
                                <td>Membership Count</td>
                                <td><?=$model->ChamaMembershipCount;?></td>
                              </tr>
							  <tr>
                                <td>Created By</td>
                                <td><?=$model->ChamaCreatedBy;?></td>
                              </tr>
							  <tr>
                                <td>Created At</td>
                                <td><?=$model->ChamaCreatedAt;?></td>
                              </tr>
                            </table>
                          </div>
                      </div>
                  </div>
				  <br><br>
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
                  <?php if($members !=0):?>
                    <?php Tabulate::getChamaMembersTabulation($members);?>
                  <?php else:?>
                    <p style='border-bottom: 1px solid #000;font-size:1.2em;color:#00933b;'>
                      <strong>NO MEMBERS FOUND</strong></p><br>
                      <p style='color:#f90101;font-size:1.30em;'>*** No available members onbnoarded to the chama ****
                    </p>
                  <?php endif;?>
                  <br><br>
              </div>
          </div>
      </div>
    </div>
</div>