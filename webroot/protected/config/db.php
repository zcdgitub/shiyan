<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 19-1-4
 * Time: 11:46
 * To change this template use File | Settings | File Templates.
 */

return array(
	'database' => 'epmms_190122',
	'host'=>'pgsql',
	'port'=>5432,
	'dump_cmd'=>'pg_dump',
	'restore_cmd'=>'pg_restore',
	'connectionString' => 'pgsql:host=pgsql;port=5432;dbname=epmms_181225;application_name=ep_web',
	'emulatePrepare' => true,
	'username' => 'postgres',
	'password' => '123456',
	'charset' => 'utf8',
	'tablePrefix'=>'epmms_',
	'pdoClass' => 'NestedPDO',
	'enableProfiling'=>false,
	'enableParamLogging'=>false,
	'class'=>'DbConnection'
);
?>


