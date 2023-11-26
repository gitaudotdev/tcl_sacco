<?php $this->renderPartial('//layouts/partials/site_top');?>
  <!--BEGIN TOP CONTENT PANEL-->
  <div class="panel-header panel-header-lg">
      <canvas id="bigDashboardChart"></canvas>
  </div>
  <!--START MAIN CONTENT-->
  <div class="content">
    <?=$content;?>
  </div>
<?php $this->renderPartial('//layouts/partials/site_bottom');?>
