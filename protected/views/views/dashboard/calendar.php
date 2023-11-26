<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance Sacco: Repayments Calendar';
$this->breadcrumbs=array(
	'Calendar'=>array('dashboard/calendar'),
);
?>
<script>
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
      },
      defaultDate: new Date(),
      navLinks: true, 
      editable: true,
      eventLimit: true,
    });

  });
</script>
<style>
  #calendar {
    max-width: 900px;
    margin: 20px 20px 80px 20px !important;
  }
</style>
<div class="row">
  <div class="col-md-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5 class="title">Calendar Overview</h5>
            <hr>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-sm-12 col-lg-12">
            <div id='calendar'></div>
	        </div>
        </div>
     </div>
  </div>
