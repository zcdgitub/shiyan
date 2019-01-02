<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-8-19
 * Time: 下午7:29
 * To change this template use File | Settings | File Templates.
 */

class DbConnection extends CDbConnection
{
	public $database='';
	public $host='localhost';
	public $port='5432';
	public $dump_cmd='pg_dump';
	public $restore_cmd='pg_restore';
}