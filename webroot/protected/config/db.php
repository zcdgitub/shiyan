<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-9-26
 * Time: 下午7:51
 * To change this template use File | Settings | File Templates.
 */
return array(
	'database'=>'epmms_181225',
	'host'=>'pgsql',
	'port'=>5432,
	'dump_cmd'=>'pg_dump',
	'restore_cmd'=>'pg_restore',
	'connectionString' => 'pgsql:host=pgsql;port=5432;dbname=epmms_181225',
	'emulatePrepare' => true,
	'username' => 'postgres',
	'password' => 'root',
	'charset' => 'utf8',
	'tablePrefix'=>'epmms_',
	'pdoClass' => 'NestedPDO',
	'enableProfiling'=>false,
	'enableParamLogging'=>false,
	'class'=>'DbConnection'
);
?>
