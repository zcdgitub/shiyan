<?php

/** 
 * @author hetao
 * 
 */
Yii::import('zii.widgets.CMenu');
class Menu extends CMenu
{
	protected function normalizeItems($items,$route,&$active)
	{
		$s_route=$route;
		foreach($items as $i=>$item)
		{
			if(isset($item['url']))
			{
				$url=$item['url'];
				if(is_array($url))
					$route=$url[0];
				if(is_string($url))
					$route=$url;
				if($route==='')
				{
					$controllerID=webapp()->controller->getId();
					$actionID=webapp()->controller->action->getId();
				}
				else if(strpos($route,'/')===false)
				{
					$controllerID=webapp()->controller->getId();
					$actionID=$route;
				}
				else if($route[0]!=='/')
				{
					webapp()->user->checkAccess($this->parseUrl($route));
					continue;
				}
				if(webapp()->controller->checkAccess($actionID,$controllerID))
				{
					$items[$i]['visible']=true;
				}
				else
				{
					$items[$i]['visible']=false;
				}
			}
		}
		return parent::normalizeItems($items, $s_route, $active);
	}
	public function parseUrl($url)
	{
		$url=trim($url);
		$pos=strpos($url,'&');
		if($pos)
		{
			$url=substr($url,0,$pos);
		}
		$pos=strpos($url,'?');
		if($pos)
		{
			$url=substr($url,0,$pos);
		}
		if($url=='')
			return $url;
		$pos=strpos($url,'/');
		if($pos===false)
			return ucfirst($url) . '.Index';
		else if( $pos+1===strlen($url))
			return ucfirst(left($url,$pos)) . '.Index';
		else if($pos===0)
			return ucfirst(substr($url,1)) . '.Index';
		else
		{
			$items=explode('/',$url);
			foreach($items as $key=>$item)
			{
				$items[$key]=ucfirst($item);
			}
			return implode('.',$items);
		}
	}
}

?>