<?php
/**
 * Created by PhpStorm.
 * User: ��
 * Date: 2015/7/10
 * Time: 10:39
 */
return array(
	'class' => 'CLogRouter',
	'routes' =>[[
		'class' => 'ext.phpconsole.PhpConsoleLogRoute',
		'ipMasks' => array('192.168.*.*','127.0.0.1','::1'),
		'isEnabled' => false,
		/* Default options:
		'isEnabled' => true,
		'handleErrors' => true,
		'handleExceptions' => true,
		'sourcesBasePath' => $_SERVER['DOCUMENT_ROOT'],
		'phpConsolePathAlias' => 'application.vendors.PhpConsole.src.PhpConsole',
		'registerHelper' => true,
		'serverEncoding' => null,
		'headersLimit' => null,
		'password' => null,
		'enableSslOnlyMode' => false,
		'ipMasks' => array(),
		'dumperLevelLimit' => 5,
		'dumperItemsCountLimit' => 100,
		'dumperItemSizeLimit' => 5000,
		'dumperDumpSizeLimit' => 500000,
		'dumperDetectCallbacks' => true,
		'detectDumpTraceAndSource' => true,
		'isEvalEnabled' => false,
		*/
	],
	[
		'class'=>'CFileLogRoute',
		'levels'=>'error,trace,info,warning',
		'categories'=>'system.db.*',
		'logFile'=>'sql.log',
        'enabled' => false,
	]
	],
)
?>