<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
$this->pageTitle=Yii::app()->name . ' - System Authentication';
$this->breadcrumbs=array(
	'Login',
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
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'login-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>
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
    <center><h4  class="authTitle"><i class="fa fa-lock"></i> LOG IN</h4><hr class="authHR"></center>
    <div class="card-body">
       <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="form-group">
              <?=$form->textField($model,'username',array('class'=>'form-control','placeholder'=>'Username','autocomplete' => 'off')); ?>
            </div>
          </div>
      </div>
      <br>
       <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="form-group">
            <?=$form->passwordField($model,'password',array('class'=>'form-control','placeholder'=>'Password','autocomplete' => 'off')); ?>
          </div>
          </div>
      </div>
    </div>
    <div class="card-footer">
    		<?=CHtml::submitButton('Login',array('class'=>'btn btn-primary mb-3')); ?>
        <hr class="authHR">
        <div>
          <center>
            <h6><a href="<?=Yii::app()->createUrl('site/forgot');?>" class="link footer-link">Forgotten Password?</a></h6>
          </center>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
