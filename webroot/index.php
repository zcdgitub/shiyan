<?php
ini_set('date.timezone','Asia/Shanghai');
mb_internal_encoding("UTF-8");
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
if(defined('YII_DEBUG') && YII_DEBUG==true)
{
	define('ADMIN_USER','epmms');
}
else
{
	define('ADMIN_USER','');
}
// change the following paths if necessary
$framwwork_path=dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;


$global_function=dirname(__FILE__) . '/protected/functions/yii.php';
$yii=$framwwork_path . '/yii-1.1.20.6ed384/framework/yii.php';

$config=dirname(__FILE__).'/protected/config/main.php';

// specify how many levels of call stack should be shown in each log message
$allow_ips = array ('127.0.0.0/8','192.168.0.0/16','::1');
//require_once dirname(__FILE__) . '/protected/extensions/firephp/firephp/lib/FirePHPCore/fb.php';
require_once($global_function);
require_once(dirname(__FILE__) . '/protected/functions/epmms.php');
require_once($yii);


Yii::createWebApplication($config)->run();


