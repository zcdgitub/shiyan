<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'console',

	// preloading 'log' component
	'preload'=>array('log'),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.mediawiki.MediaWiki',
	),
	// application components
	'components'=>array(
		'db'=>require('db.php'),
		'dbms'=>require('dbms.php'),
		'dbaccess'=>['connectionString'=>'pgsql:host=127.0.0.1;port=5432;dbname=150720'],
        // Mysql部分的配置
        'dbMysql' => [
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=127.0.0.1;dbname=180501-shop',
            'username' => 'root',
            'password' => '1987',
            'charset' => 'utf8',
        ],
		// uncomment the following to use a MySQL database
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
					'logFile'=>'mail.log',
					'categories'=>'mail.*'
				),
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'trace,error, warning, info',
					'logFile'=>'import.log',
					'categories'=>'import.*'
				),
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error,warning',
					'logFile'=>'import_error.log',
					'categories'=>'import.*'
				),
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
                [
                    'class'=>'CFileLogRoute',
                    'levels'=>'error,trace,info,warning',
                    'categories'=>'system.db.*',
                    'logFile'=>'sql.log',
                ]
			),
		),
		'format'=>array(
			'class'=>'Formatter',
		),
		'coreMessages'=>array('basePath'=>'protected/messages','language'=>'en_us'),
		'cache' => array(
			'class' => 'CRedisCache',
			'database'=>0,
            'hostname'=>'redis',
			//'keyPrefix'=>'cache'
		),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'haohetao@gmail.com',
		'os_charset'=>'UTF-8',
		'web_charset'=>'UTF-8',
		'db_backup'=>dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'db_backup' . DIRECTORY_SEPARATOR,
		'sms'=>[]
	),
);
