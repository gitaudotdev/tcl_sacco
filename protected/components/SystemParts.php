<?php

class SystemParts{

	public static function displayHeadInformation($pageTitle){
		echo '<!DOCTYPE html>
					<html lang="en">
					<head>
					<meta charset="utf-8" />
					<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
					<title>';echo CHtml::encode($pageTitle);
		echo '</title>
					<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport" />
					<link href="';echo Yii::app()->request->baseUrl;
		echo '/styles/font.css" rel="stylesheet" />
					<link href="';echo Yii::app()->request->baseUrl; 
		echo '/styles/fontawesome.css" rel="stylesheet">
					<link href="';echo Yii::app()->request->baseUrl;
		echo '/font-awesome/css/font-awesome.min.css" rel="stylesheet">
					<link href="';echo Yii::app()->request->baseUrl;
		echo '/styles/bootstrap.min.css" rel="stylesheet" />
		      <link rel="icon" type="image/png" href="';echo Yii::app()->request->baseUrl;
		echo '/images/site/favicon.png" />
					<link href="';echo Yii::app()->request->baseUrl; 
		echo '/styles/ui_dashboard.css" rel="stylesheet" />
					<link href="'; echo Yii::app()->request->baseUrl; 
		echo '/styles/style.css" rel="stylesheet" />
					<link href="'; echo Yii::app()->request->baseUrl; 
		echo '/dist/css/select2.min.css" rel="stylesheet" />
					<link href="'; echo Yii::app()->request->baseUrl; 
		echo '/dist/css/dataTable.css" rel="stylesheet" />
					<link href="'; echo Yii::app()->request->baseUrl; 
		echo '/dist/css/datepicker.min.css" rel="stylesheet" />
					<link href="'; echo Yii::app()->request->baseUrl; 
		echo '/styles/daterangepicker.css" rel="stylesheet" />
					<link href="'; echo Yii::app()->request->baseUrl; 
		echo '/styles/filemanager.css" rel="stylesheet"/>
					<link href="'; echo Yii::app()->request->baseUrl; 
		echo '/styles/style.css" rel="stylesheet" />
					</head>';
	}
	
	public static function displayFooterContent($orgName,$orgWebsite){
		echo '<footer class="footer">
				      <div class="container-fluid">
				          <div class="copyright">
				            <br>
				              &copy; <script>document.write(new Date().getFullYear())</script> All Rights Reserved.
				               <a href="';echo $orgWebsite;echo'" target="_blank" style="text-transform:none;">';echo ucfirst($orgName);echo '</a>.
				          </div>
				      </div>
				  </footer>';
	}
	public static function displayFooterInformation(){
		echo '<script src="';echo Yii::app()->request->baseUrl; 
		echo '/scripts/jquery.min.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl; 
		echo '/scripts/popper.min.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/bootstrap.min.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/perfect-scrollbar.min.js" ></script>
					<script async defer src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/buttons.js"></script>
					<script src="';echo Yii::app()->request->baseUrl; 
		echo '/scripts/bootstrap-notify.js"></script>
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/chart.min.js" ></script>	
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/client.js" ></script>	
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/ui_dashboard.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/sacco.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/dropzone.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/moment.min.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/scripts/daterangepicker.js" ></script>		
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/dist/js/select2.min.js" ></script>
			  	<script src="';echo Yii::app()->request->baseUrl;
		echo '/dist/js/dataTable.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl;
		echo '/dist/js/datepicker.min.js" ></script>
					<script src="';echo Yii::app()->request->baseUrl; 
		echo '/scripts/url.js" ></script>';
	}

}