<?php
/* @var $this DashboardController */
$this->pageTitle=Yii::app()->name . ' - Microfinance Status Landing Page';
$this->breadcrumbs=array(
  'Welcome'=>array('default'),
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

$element=Yii::app()->user->user_level;
$array=array('4');
?>
<style type="text/css">
    .card-raised-modified{
        border-radius: 0px !important;
        border-top:3px solid #00c0ef !important;
    }
    .card_stats{
        font-size:2.8em !important;
    }
    .password_div{
        background:#f9633e !important; 
        padding: 1px 1px 1px 45px !important;
        margin-bottom: 2% !important;
    }
    .password_message{
        color:#fff !important;
        margin-top: 2% !important;
        font-size: 14px !important;
        font-style: italic !important;
    }
    .pwd_force{
        color:#000 !important; 
    }
    .special_rule{
        border-top: 2px dotted #dedede !important;
        width:100% !important;
    }
    .special_bold{
        font-style: italic !important;
    }
    .special_div{
        font-size:15px !important;
    }
    .special_head{
        font-size:20px !important;
    }
    .special_content{
        padding:10px 10px !important;
    }
    .special_announcement{
        border-left:2px solid #dedede !important; 
        box-shadow: 0px 2px 5px #fff !important;
    }
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
        <div class="card card-stats">
            <div class="card-body">
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
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card-header">
                        <h4 class="title special_head">Hi <?=ucfirst($profile->firstName);?>, Welcome!</h4>
                    </div>
                    <div class="card-body">
                        <?php if(User::checkIfPasswordHasExpired(Yii::app()->user->user_id) == 1):?>
                            <div class="password_div">
                                <p class="password_message">
                                    Your password has expired. Please click <a class="pwd_force" onclick='ForceChangePassword()'>change password</a> to change your password and avoid your account being locked.
                                </p>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card card-stats card-raised-modified">
            <div class="card-body">
            <div class="col-md-7 col-lg-7 col-sm-12 special_content">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12 place-border">
                        <div class="statistics">
                            <div class="info">
                                <?php if(Navigation::checkIfAuthorized(116) == 1):?>
                                <a href="<?=Yii::app()->createUrl('dashboard/index')?>">
                                <?php else:?>
                                <a href="#">
                                <?php endif;?>
                                <div class="icon icon-primary">
                                    <i class="fa fa-dashboard fa-5x"></i>
                                </div>
                                </a>
                                <h3 class="info-title enhanced">Dashboard</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-12 place-border">
                        <div class="statistics">
                            <div class="info">
                                <?php if(CommonFunctions::searchElementInArray($element,$array) === 0 ):?>
                                <a href="<?=Yii::app()->createUrl('loanaccounts/admin')?>">
                                <?php else:?>
                                <a href="#">
                                <?php endif;?>
                                <div class="icon icon-info">
                                    <i class="now-ui-icons objects_diamond"></i>
                                </div>
                                </a>
                                <h3 class="info-title enhanced">Loans</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="statistics">
                            <div class="info">
                                <?php if(CommonFunctions::searchElementInArray($element,$array) === 0 ):?>
                                <a href="<?=Yii::app()->createUrl('savingaccounts/admin')?>">
                                <?php else:?>
                                <a href="#">
                                <?php endif;?>
                                <div class="icon icon-success">
                                    <i class="now-ui-icons business_bank"></i>
                                </div>
                                </a>
                                <h3 class="info-title enhanced">Savings</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12 place-border">
                        <div class="statistics">
                            <div class="info">
                                <a data-toggle="modal" data-target="#accountModal">
                                <div class="icon icon-primary">
                                    <i class="now-ui-icons users_circle-08"></i>
                                </div>
                                </a>
                                <h3 class="info-title enhanced">My Account</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-12 place-border">
                        <div class="statistics">
                            <div class="info">
                                <a data-toggle="modal" data-target="#passwordModal">
                                <div class="icon icon-info">
                                    <i class="now-ui-icons ui-1_settings-gear-63"></i>
                                </div>
                                </a>
                                <h3 class="info-title enhanced"> Password</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="statistics">
                            <div class="info">
                                <a data-toggle="modal" data-target="#confirmLogout">
                                <div class="icon icon-success">
                                    <i class="now-ui-icons media-1_button-power"></i>
                                </div>
                                </a>
                                <h3 class="info-title enhanced"> Log Out</h3>
                            </div>
                        </div>
                    </div>
                    </div><br><br>
                    </div>
                    <?php if(!empty($notices)):?>
                    <div class="col-md-5 col-lg-5 col-sm-12 special_announcement">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <center><h2 class="title">ANNOUNCEMENTS</h2></center>
                            <hr>
                        </div>
                        <?php foreach($notices AS $notice):?>
                        <div class="col-md-12 col-lg-12 col-sm-12 special_div">
                            <p class="special_bold"><?=$notice->message;?></p>
                            <hr class="special_rule">
                        </div>
                        <?php endforeach;?>
                    </div>
                   <?php else:?>
                    <div class="col-md-5 col-lg-5 col-sm-12 special_announcement">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <center><h2 class="title">ANNOUNCEMENTS</h2></center>
                            <hr>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12 special_div">
                            <strong>There are no available notices at the moment.</strong>
                        </div>
                    </div>
                   <?php endif;?>

                </div>
            </div>
</div>
<script type="text/javascript">
    function ForceChangePassword(){
     $('#passwordModal').modal({backdrop: 'static',keyboard: false,show:true});
    }
</script>