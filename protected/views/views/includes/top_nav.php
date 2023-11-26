 <?php $leaveID=leavesManager::getLoggedStaffLeaveID();?>
 <nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute bg-primary fixed-top">
        <div class="container-fluid">
            <div class="navbar-wrapper">
              <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                  <span class="navbar-toggler-bar bar1"></span>
                  <span class="navbar-toggler-bar bar2"></span>
                  <span class="navbar-toggler-bar bar3"></span>
                </button>
              </div>
              <!--BEGIN BREADCRUMB-->
              <?php if(isset($this->breadcrumbs)):?>
                <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                  'links'=>$this->breadcrumbs,
                )); ?>
              <?php endif?>
              <!--END BREADCRUMB-->
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-bar navbar-kebab"></span>
              <span class="navbar-toggler-bar navbar-kebab"></span>
              <span class="navbar-toggler-bar navbar-kebab"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navigation">
              <?php if(Navigation::checkIfAuthorized(260) === 1):?>
                <a href="<?=Yii::app()->createUrl('dashboard/admin');?>"><i class="now-ui-icons ui-1_settings-gear-63"></i>Control Panel</a>
              <?php endif;?>
              <ul class="navbar-nav" style="margin-top:-0.5% !important;">
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?=strtoupper(Yii::app()->user->lastname);?><i class="now-ui-icons users_single-02"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" data-toggle="modal" data-target="#accountModal">
                      <i class="now-ui-icons users_single-02"></i>ACCOUNT
                    </a>
                     <?php if($leaveID > 0 && (Navigation::checkIfAuthorized(187) === 1)):?>
                      <a class="dropdown-item" href="<?=Yii::app()->createUrl('leaves/'.$leaveID);?>"><i class="now-ui-icons education_atom"></i>LEAVE</a>
                     <?php endif;?>
                    <?php if(Navigation::checkIfAuthorized(211) === 1):?>
                      <a class="dropdown-item" href="<?=Yii::app()->createUrl('folders/admin');?>"><i class="now-ui-icons files_box"></i>FILE VAULT</a>
                    <?php endif;?>
                    <a class="dropdown-item" data-toggle="modal" data-target="#passwordModal">
                      <i class="now-ui-icons ui-1_settings-gear-63"></i>PASSWORD</a>
                    <a class="dropdown-item" data-toggle="modal" data-target="#confirmLogout">
                      <i class="now-ui-icons media-1_button-power"></i>LOG OUT
                    </a>
                  </div>
                </li>
              </ul>
            </div>
        </div>
      </nav>