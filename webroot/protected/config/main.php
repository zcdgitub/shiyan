<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__) . DIRECTORY_SEPARATOR .'..'  ,
	'name'=>'易软市场管理系统',
	'id'=>'181127',
	'language'=>'zh_cn',
	'sourceLanguage'=>'zh_cn',
	'theme'=>'classic',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		//'application.modules.rights.*',
		//'application.modules.rights.components.*', // Correct paths if necessary.
		'application.modules.privilege.*',
		'application.modules.privilege.components.*', // Correct paths if necessary.
		'ext.mediawiki.MediaWiki',
		'ext.EExcelView.*',
	),

	'modules'=>array(
	/*	'user'=>array(
			'class'=>'AdminWebUser',
			'loginUrl'=>['user/login'],
		),*/
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'generatorPaths'=>array('application.gii'),
			'password'=>'nnk1r08p2344',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1','192.168.5.2','*.*.*.*'),
		),
		'giix' => array(
			'class' => 'system.gii.GiiModule',
			'generatorPaths' => array(
				'ext.giix-core', // giix generators
			),
			'ipFilters'=>array('127.0.0.1','::1','192.168.5.3'),
		),
		'rights'=>array( 
			'install'=>false, // Enables the installer.
			'userClass'=>'Userinfo',
			'userNameColumn'=>'userinfo_account',
			'userIdColumn'=>'userinfo_id',
			'appLayout'=>'webroot.themes.classic.views.layouts.main',
			'superuserName'=>ADMIN_USER, // 超级用户角色名,安装时会添加 superuser(就是这里配置的,当前用户会自动添加进来),authenticated(所有登录用户),guest(未登录)这3个角色
			'authenticatedName'=>'authenticated', // 所有登录用户角色
			'enableBizRule'=>true, // Whether to enable authorization item business rules.
			'enableBizRuleData'=>false, // Whether to enable data for business rules. 
			'displayDescription'=>true, // Whether to use item description instead of name. 
			'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested. 
			'layout'=>'rights.views.layouts.main', // Layout to use for displaying Rights. 
			'install'=>false, // Whether to enable installer. 
			'debug'=>true,
		),
		'privilege'=>array(
			'install'=>false, // Enables the installer.
			'userClass'=>'Userinfo',
			'userNameColumn'=>'userinfo_account',
			'userIdColumn'=>'userinfo_id',
			'appLayout'=>'webroot.themes.classic.views.layouts.main',
			'superuserName'=>ADMIN_USER, // 超级用户角色名,安装时会添加 superuser(就是这里配置的,当前用户会自动添加进来),authenticated(所有登录用户),guest(未登录)这3个角色
			'authenticatedName'=>'authenticated', // 所有登录用户角色
			'enableBizRule'=>true, // Whether to enable authorization item business rules.
			'enableBizRuleData'=>false, // Whether to enable data for business rules.
			'displayDescription'=>true, // Whether to use item description instead of name.
			'baseUrl'=>'/privilege', // Base URL for Rights. Change if module is nested.
			'layout'=>'privilege.views.layouts.main', // Layout to use for displaying Rights.
			'install'=>false, // Whether to enable installer.
			'debug'=>true,
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'class'=>'WebUser',
			'loginRequiredAjaxResponse' => 'YII_LOGIN_REQUIRED',
			'loginUrl'=>['site/login'],
            'identityCookie'=>['domain'=>'localhost:9201', 'httpOnly' => true]
		),
		'format'=>array(
		    'class'=>'Formatter',
		),
		'coreMessages'=>array('basePath'=>'protected/messages','language'=>'en_us'),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'urlSuffix'=>'.html',
			'rules'=>array(
				'<controller:product>/<action:index>/<class:\d+>'=>'product/index',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'db'=>require('db.php'),
        // Mysql部分的配置
        'dbMysql' => [
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=127.0.0.1;dbname=180929-shop',
            'username' => 'root',
            'password' => '1987',
            'charset' => 'utf8',
        ],
		'pgagent'=>[
			'database'=>'postgres',
			'host'=>'127.0.0.1',
			'connectionString' => 'pgsql:host=127.0.0.1;dbname=postgres',
			'emulatePrepare' => true,
			'username' => 'postgres',
			'password' => '1987',
			'charset' => 'utf8',
			'tablePrefix'=>'pga_',
			'class'=>'CDbConnection',
			'pdoClass' => 'NestedPDO',
			'enableProfiling'=>true,
			'enableParamLogging'=>true,
			'class'=>'DbConnection'
		],
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			//'errorAction'=>'site/error',
			'adminInfo'=>'',
		),
		'log' => require('log.php'),
		'authManager'=>array(
				//'class'=>'application.modules.srbac.components.SDbAuthManager',
				'class'=>'DbAuthManager',
				// The database component used
				'connectionID'=>'db',
				'defaultRoles'=>array('Guest'),
				'rightsTable'=>'epmms_rights',
				'itemChildTable'=>'epmms_authitemchild',
				'itemTable'=>'epmms_authitem',
				'assignmentTable'=>'epmms_authassignment',
				'cachingDuration' =>'0',
				'cacheID' =>'cache'
		),
		'logOperation'=>array(
				'class'=>'CPhpMessageSource',
				'language'=>'zh_cn',
				'forceTranslation'=>true
		),
		'clientScript' => array(
			'class' => 'ext.minify.EClientScript',
			'combineScriptFiles' => !YII_DEBUG, // By default this is set to true, set this to true if you'd like to combine the script files
			'combineCssFiles' => false, // By default this is set to true, set this to true if you'd like to combine the css files
			'optimizeScriptFiles' => !YII_DEBUG, // @since: 1.1
			'optimizeCssFiles' => !YII_DEBUG, // @since: 1.1
		),
		'pay'=>array(
			'class'=>'ext.chinabank.ChinaBankProxy',
			'partner'=>'22681394', // your partner id
			'key'=>'w2mmydxA2AAN', // your key
			'return_url'=>['pay/returnPay'],
		),
		'sessionCache' => array(
			'class' => 'CRedisCache',
			'keyPrefix'=>'session_',
            'hostname'=>'redis',
			'database'=>1,
		),
		'cache' => array(
			'class' => 'CRedisCache',
			'hostname'=>'redis',
			'database'=>0,
			//'keyPrefix'=>'cache'
		),
		'session'=>array(
			'class' => 'CCacheHttpSession',
			'cacheID' => 'sessionCache',
			//'sessionName'=>'EPMMSSESSIONID',
			//'cookieParams'=>['domain'=>'localhost:9112', 'httpOnly' => true]
			//'sessionName'=>'PHPSESSID'
			//'timeout'=>3600,        // Session data can be cleared after this time
		),
		'errorHandler'=>array(
			'class'=>'ErrorHandler'
		),
        'mobileDetect' => array(
            'class' => 'ext.MobileDetect.MobileDetect'
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'haohetao@gmail.com',
		'upload'=>DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR,
		'product'=>array('all'=>DIRECTORY_SEPARATOR .   'upload'  . DIRECTORY_SEPARATOR .  'product' . DIRECTORY_SEPARATOR,
						'image'=>DIRECTORY_SEPARATOR .  'upload'  . DIRECTORY_SEPARATOR .  'product' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR,
						'image2'=> 'upload'  . DIRECTORY_SEPARATOR .  'product' . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR,
						'info'=>DIRECTORY_SEPARATOR .  'upload'  . DIRECTORY_SEPARATOR .  'product' . DIRECTORY_SEPARATOR . 'info' . DIRECTORY_SEPARATOR,
						'info2'=> 'upload'  . DIRECTORY_SEPARATOR .  'product' . DIRECTORY_SEPARATOR . 'info' . DIRECTORY_SEPARATOR),
		'upload_tmp'=>'upload' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR,
		'os_charset'=>'UTF-8',
		'web_charset'=>'UTF-8',
		'db_backup'=> '..' . DIRECTORY_SEPARATOR . 'db_backup' . DIRECTORY_SEPARATOR,
		'multi_language'=>false,
		'versionConfig'=>'enterprise',
		'licence'=>require('licence.php'),
		'spaceQuota'=>'1024',
		'money_unit'=>'',
		'sms'=>require("sms.php"),
		'game_charge_key'=>'140809',
		'accountType'=>0,//1自动生成账号，0人工输入账号,
		'accountLength'=>8,//自动生成账号长度
		'ordersForm'=>false,
		'map2_branch4'=>3,
		'send_email'=>false,
		'send_sms'=>true,
        'shop_url'=>'http://192.168.31.31:9201/',
        'shop_login_url'=>'http://192.168.31.31:9201/customer/account/login',
        'shop_logout_url'=>'http://192.168.31.31:9201/customer/account/logout',
        'mobile_url'=>'http://1211.youtuoapp.com/',
        'fecshop'=>false,
        'regAgent'=>true,
        'autoVerify'=>false,
       //'img_host'=>'http://192.168.31.107:3000/', 
        'img_host'=>'http://zhang.youtuoapp.com/',

	),
);
