<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-10-12
 * Time: 下午2:09
 * To change this template use File | Settings | File Templates.
 */

define("MEDIAWIKI_PATH",__DIR__ . DIRECTORY_SEPARATOR . "mediawiki-1.15.5" . DIRECTORY_SEPARATOR);
require_once "mediawiki-zhconverter.inc.php";
class MediaWiki  extends CComponent
{
	public static function convert($str,$language='zh-tw')
	{
		/* Convert it, valid variants such as zh, zh-cn, zh-tw, zh-sg & zh-hk */
		return MediaWikiZhConverter::convert($str,$language);
	}
}