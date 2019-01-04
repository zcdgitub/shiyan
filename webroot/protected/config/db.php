<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 19-1-4
 * Time: 11:46
 * To change this template use File | Settings | File Templates.
 */
return array(
	'database' => 'epmms_181225',
	'host'=>'pgsql',
	'port'=>5432,
	'dump_cmd'=>'pg_dump',
	'restore_cmd'=>'pg_restore',
	'connectionString' => 'pgsql:host=pgsql;port=5432;dbname=epmms_181225;application_name=ep_web',
	'emulatePrepare' => true,
	'username' => 'epmms_181225',
	'password' => 'yi8jt1uBx4Wfb1HBeDe8Ph3j',
	'charset' => 'utf8',
	'tablePrefix'=>'epmms_',
	'pdoClass' => 'NestedPDO',
	'enableProfiling'=>false,
	'enableParamLogging'=>false,
	'class'=>'DbConnection'
);
?>
