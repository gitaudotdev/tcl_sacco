<?php
 $organization      = Organization::model()->findByPk(1);
 $organization_logo = $organization->logo;
 SystemParts::displayHeadInformation($this->pageTitle);
?>
<style type="text/css">
  .imageHolder{
    background: url(images/site/tcl_logo.jpg); 
    background-repeat: no-repeat !important;
  }
  .login_background{
    background-image: url(images/site/<?=$organization_logo;?>) !important;
  }
</style>
<body class="login_background">
  <div class="wrapper wrapper-full-page">
    <div class="full-page login-page section-image" filter-color="black">
        <div class="content">
            <div class="container">
              <div class="col-md-4 col-lg-4 col-sm-12" id="authSection">
                <div class="top-content-style">
                  <img alt="" class="imageHolder"/>
                </div>
                <?=$content;?>
              </div>
            </div>
        </div>
    </div>
  </div>