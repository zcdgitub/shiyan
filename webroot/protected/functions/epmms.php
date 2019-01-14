<?php
/**
 * @return CTheme|string
 */
function themeBaseUrl()
{
	return (Yii::app()->theme ? Yii::app()->theme->baseUrl :baseUrl());
}

/**
 * @return mixed|string
 */
function themeBasePath()
{
	return (Yii::app()->theme ? Yii::app()->theme->basePath :webapp()->basePath);
}

/**
 * @return CTheme|string
 */
function viewBasePath()
{
	return (Yii::app()->theme ? Yii::app()->theme->viewPath :webapp()->viewPath);
}
/**
 * 显示消息并跳转
 * @param unknown_type $popmsg 消息文本
 * @param unknown_type $url 跳转的url route
 * @param unknown_type $target frameset中指定哪个frame,可以为self,parent,frame名字,默认为self
 */
function client_redirect($url,$popmsg,$target='self')
{
	if(!empty($popmsg))
		$msg="alert('$popmsg')";
	else
		$msg='';
	$target_url=$url;
	$title=webapp()->controller->getPageTitle();
	echo <<<EOT
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
	<script type="text/javascript" >
		$msg;
		$target.location.href='$target_url';
	</script>
<title>$title</title>
</head>
</html>
EOT;
	exit ();
}

/**
 * @param $path
 * @return string
 */
function path2url($path)
{
	return strtr($path,DIRECTORY_SEPARATOR,'/');
}

/**
 * @param $string
 * @param $count
 * @return string
 */
function right($string, $count)
{
	return substr($string,-$count, $count);
}

/**
 * @param $string
 * @param $count
 * @return string
 */
function left($string, $count)
{
	return substr($string, 0, $count);
}
/**
 * 解析字符串表示的关系属性,返回属性的直接model
 * @param Model $model
 * @param string $attribute
 * @param boolean $type true表示点分格式解析，false方框号格式解析
 * @return Model
 */
function resolveModel($model,$attribute,$type=true)
{
	if($type)
		$fmt='.';
	else
		$fmt='][';
	if($type)
	{
		foreach(explode($fmt, $attribute) as $name)
		{
			if(is_null($model))
			{
				break;
			}
			if($model->hasRelated($name))
			{
				$model=$model->$name;
			}
			elseif($relation=$model->getActiveRelation($name))
			{
				$className=$relation->className;
				$model=$className::model();
			}
			else
				break;
		}
	}
	return $model;
}
/**
 * 解析字符串表示的关系属性，返回属性名
 * @param string $attribute
 * @param boolean $type
 * @return string
 */
function resolveAttribute($attribute,$type=true)
{
	if($type)
		$fmt='.';
	else
		$fmt='][';
	$name='';
	if($type)
	{
		if(is_relation($attribute))
		{
			$attributes=explode($fmt, $attribute);
			$name=$attributes[count($attributes)-1];
			return $name;
		}
		else
			return $attribute;
	}
}

/**
 * @param $attribute
 * @param bool $type
 * @return bool
 */
function is_relation($attribute,$type=true)
{
	if($type)
		$fmt='.';
	else
		$fmt='[';
	if(strpos($attribute,$fmt)!==false)
			return true;
	else
		return false;
}
function dot2square($name)
{
	if(is_relation($name))
	{
		$pos=strpos($name,'.');
		$relationName=substr($name,0,$pos);
		$attributeName=substr($name,$pos+1);
		$names=explode('.',$attributeName);
		$squareName=$relationName . '['. implode('][', $names) . ']';
		return $squareName;
	}
	else
		return $name;
}
/**
 * 判断变量是否为空，变量需要已定义
 * @param unknown_type $value
 * @param unknown_type $trim
 * @return boolean
 */
function isEmpty($value,$trim=false)
{
    return $value==='null' || $value===null || $value===array() || $value==='' || $trim && is_scalar($value) && trim($value)==='';
}

/**
 * @param $category
 * @param $name
 * @return string
 */
function config2($category,$name)
{
	static $model=array();
	if(isEmpty($category) || isEmpty($name))
		return '';
	$cname='config_' . $category . '_' . $name;
	if(!isset($model[$category]))
		$model[$cname]=Model::model('Config'. ucfirst($category))->find();
	return $model[$category]->$cname;
}
function config($category,$name)
{
	static $model=array();
	if(isEmpty($category) || isEmpty($name))
		return '';
	$cname='config_' . $category . '_' . $name;
	if(($value=webapp()->cache->get($cname))===false)
	{
		
		if (!isset($model[$category]))
		{
			$cmd = webapp()->db->createCommand("select * from epmms_config_$category");
			$model[$category] = $cmd->queryRow();
		}
		$value=$model[$category][$cname];
		//$dep=new CGlobalStateCacheDependency('updateConfig');
		$dep=new CExpressionDependency('Yii::app()->cache->get("updateConfig")');
		$dep->reuseDependentData=true;
		webapp()->cache->set($cname,$value,3600,$dep);
	}
	return $value;
}
function config3($category,$name)
{
	$db=new PDO(webapp()->db->connectionString,webapp()->db->username,webapp()->db->password);
	static $model=array();
	if(isEmpty($category) || isEmpty($name))
		return '';
	$cname='config_' . $category . '_' . $name;
	if(!isset($model[$cname]))
	{
		$cmd=$db->query("select * from epmms_config_$category");
		$model[$category] =$cmd->fetch();
	}
	return $model[$category][$cname];
}
/**
 * @param $value
 * @return mixed
 */
function empty2null($value)
{
	return isEmpty($value)?null:$value;
}

/**
 * @param $value
 * @param $type
 * @return mixed
 */
function format($value,$type)
{
	return webapp()->format->format($value,$type);
}

/**
 * 设置pgsql连接的环境变量
 */
function setPgsqlEnv()
{
	putenv('PGHOST=' . webapp()->db->host);
	putenv('PGPORT=' . webapp()->db->port);
	putenv('PGDATABASE=' . webapp()->db->database);
	putenv('PGUSER=' . webapp()->db->username);
	putenv('PGPASSWORD=' . webapp()->db->password);
}
function getDirectorySize($path)
{
	$totalsize = 0;
	$totalcount = 0;
	$dircount = 0;
	if ($handle = opendir ($path))
	{
		while (false !== ($file = readdir($handle)))
		{
			$nextpath = $path . '/' . $file;
			if ($file != '.' && $file != '..' && !is_link ($nextpath))
			{
				if (is_dir ($nextpath))
				{
					$dircount++;
					$result = getDirectorySize($nextpath);
					$totalsize += $result['size'];
					$totalcount += $result['count'];
					$dircount += $result['dircount'];
				}
				elseif (is_file ($nextpath))
				{
					$totalsize += filesize ($nextpath);
					$totalcount++;
				}
			}
		}
	}
	closedir ($handle);
	$total['size'] = $totalsize;
	$total['count'] = $totalcount;
	$total['dircount'] = $dircount;
	return $total;
}
function sizeFormat($size)
{
	$sizeStr='';
	if($size<1024)
	{
		return $size." bytes";
	}
	else if($size<(1024*1024))
	{
		$size=round($size/1024,1);
		return $size." KB";
	}
	else if($size<(1024*1024*1024))
	{
		$size=round($size/(1024*1024),1);
		return $size." MB";
	}
	else
	{
		$size=round($size/(1024*1024*1024),1);
		return $size." GB";
	}
}
function joinUrl($url='',$params=array())
{
	$flag=false;
	if(empty($url))
		$url=CHtml::normalizeUrl('');
	if(strpos($url,'?')===false)
	{
		$url=$url . '?';
		foreach($params as $name=>$value)
		{
			if($flag)
				$url.='&' . "$name=$value";
			else
				$url.="$name=$value";
			if($flag==false)
				$flag=true;
		}
	}
	else
	{
		foreach($params as $name=>$value)
		{
			$url.='&' . "$name=$value";
		}
	}
	return $url;
}
function maskEmail($email)
{
	$a=str_split($email);
	$c=0;
	$at_flag=false;
	foreach($a as $k=>$v)
	{
		if($v=='@')
			$at_flag=true;
		$c++;
		if($c>2 && $at_flag==false)
			$a[$k]='*';
	}
	return implode($a);
}
function maskPhone($phone)
{
	$a=str_split($phone);
	$c=0;
	foreach($a as $k=>$v)
	{
		$c++;
		if($c<8)
			$a[$k]='*';
	}
	return implode($a);
}
?>