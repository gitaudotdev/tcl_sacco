<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Create New Account';
$this->breadcrumbs=array(
 'Register Account',
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
<div class="card card-login card-plain">
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
  <form method="post">
    <center><h4 class="authTitle">REGISTER ACCOUNT</h4><hr class="authHR"></center>
    <div class="card-body">
       <div class="row">
          <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="First Name" class="form-control"
                 required="required" name="first_name">
              </div>
          </div>
        </div>
        <br>
         <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                  <input type="text" placeholder="Last Name" class="form-control"
                   required="required" name="last_name">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
               <select name="branch" required="required" class="selectpicker" style="width: 100% !important;">
                    <option value="">--Please Select A Branch-</option>
                    <?php if(!empty($branches)):?>
                      <?php foreach($branches as $branch):?>
                         <option value="<?=$branch->branch_id;?>"><?=$branch->name;?></option>
                      <?php endforeach;?>   
                    <?php endif;?>
                </select>
               </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                <input type="email" placeholder="Email Address..." class="form-control"
                 required="required" name="email">
               </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="form-group">
                  <input type="text" placeholder="Username" class="form-control"
                   required="required" name="username">
                </div>
            </div>
        </div>
        <br>
         <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <input type="password" placeholder="Password" class="form-control"
                 required="required" name="password">
               </div>
            </div>
        </div>
    </div>
    <div class="card-footer col-md-12 col-lg-12 col-sm-12">
      <div class="col-md-6 col-lg-6 col-sm-12">
        <input type="submit" name="register_cmd" class="btn btn-primary mb-3 pull-left" value="Register">
      </div>
    	<div class="col-md-6 col-lg-6 col-sm-12">
        <a href="<?=Yii::app()->createUrl('site/login');?>" class="btn btn-default mb-3 pull-right">Cancel</a>
      </div>
    </div>
</div>
</form>
