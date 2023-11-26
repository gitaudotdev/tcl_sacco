<?php
date_default_timezone_set("Africa/Nairobi");
ini_set('max_execution_time',30000);
ini_set('memory_limit', '2048M');
ini_set("auto_detect_line_endings", true);
$config_destination ='charts/config/auto_load.php';
include_once $config_destination;
// change the following paths if necessary
$yii=dirname(__FILE__).'/sacco_back/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',false); //production
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


require_once($yii);
Yii::createWebApplication($config)->run();
