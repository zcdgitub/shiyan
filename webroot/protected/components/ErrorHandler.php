<?php
/**
 * Created by PhpStorm.
 * User: 涛
 * Date: 14-3-17
 * Time: 上午11:14
 */

class ErrorHandler extends CErrorHandler
{
	protected function getVersionInfo()
	{
		if(YII_DEBUG)
		{
			$version='<a href="http://www.youtuosoft.com/">EP-MMS</a>';
		}
		else
			$version='';
		return $version;
	}
}