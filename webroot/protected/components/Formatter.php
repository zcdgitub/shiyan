<?php

/**
 *
 * @author hetao
 *        
 */
class Formatter extends \CFormatter
{
	/**
	 * @var string the format string to be used to format a date using PHP date() function. Defaults to 'Y/m/d'.
	 */
	public $dateFormat='Y-m-d';
	/**
	 * @var string the format string to be used to format a time using PHP date() function. Defaults to 'h:i:s A'.
	 */
	public $timeFormat='H:i:s';
	/**
	 * @var string the format string to be used to format a date and time using PHP date() function. Defaults to 'Y/m/d h:i:s A'.
	 */
	public $datetimeFormat='Y-m-d H:i:s';
	public $numberFormat=['decimals'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','];
	/**
	 * 格式化性别
	 * @param unknown_type $value
	 * @return string
	 */
	public function formatSex($value)
	{
		if(is_null($value))
		{
			return '';
		}
		else if($value==0)
		{
			return t('epmms','男');
		}
		else if($value==1)
		{
			return t('epmms','女');
		}
		else if($value==2)
		{
			return t('epmms','保密');
		}		
		else
		{
			return '';
		}
	}
	/**
	 * 格式化预置设置
	 * @param unknown_type $value
	 * @return string
	 */
	public function formatPreset($value)
	{
		if(is_null($value))
		{
			return '';
		}
		else if($value==0)
		{
			return t('epmms','自定义');
		}
		else if($value==1)
		{
			return t('epmms','预置');
		}
		else
		{
			return t('epmms','自定义');
		}
	}
	/**
	 * 格式化启用设置
	 * @param unknown_type $value
	 * @return string
	 */
	public function formatEnable($value)
	{
		if(is_null($value))
		{
			return '';
		}
		else if($value==0)
		{
			return t('epmms','禁用');
		}
		else if($value==1)
		{
			return t('epmms','启用');
		}
		else
		{
			return t('epmms','禁用');
		}
	}
	/**
	 * 格式化是否设置
	 * @param unknown_type $value
	 * @return string
	 */
	public function formatYesno($value)
	{
		if(is_null($value))
		{
			return '';
		}
		else if($value==0)
		{
			return t('epmms','否');
		}
		else if($value==1)
		{
			return t('epmms','是');
		}
		else
		{
			return '';
		}
	}
	/**
	 * 格式化身份证件类型
	 * @param unknown_type $value
	 * @return string
	 */
	public function formatIdCardType($value)
	{
		if(is_null($value))
		{
			return '';
		}
		else if($value==0)
		{
			return t('epmms','居民身份证');
		}
		else if($value==1)
		{
			return t('epmms','士兵证');
		}
		else if($value==2)
			return t('epmms','军官证');
		else if($value==3)
			return t('epmms','警官证');
		else if($value==4)
			return t('epmms','护照');
		else if($value==5)
			return t('epmms','其它');
		else
			return '';
	}
	public function formatPassword($value)
	{
		if(empty($value))
			return;
		return CPasswordHelper::hashPassword($value);
	}

	/**
	 * Formats the value as a date.
	 * @param mixed $value the value to be formatted
	 * @return string the formatted result
	 * @see dateFormat
	 */
	/*
	public function formatDate($value)
	{
		if(isEmpty($value))
		{
			return;
		}
		return date($this->dateFormat,CDateTimeParser::parse($value,'yyyy-MM-dd'));
	}
	*/
	/**
	 * Formats the value as a time.
	 * @param mixed $value the value to be formatted
	 * @return string the formatted result
	 * @see timeFormat
	 */
	/*
	public function formatTime($value)
	{
		if(isEmpty($value))
		{
			return;
		}
		return date($this->timeFormat,CDateTimeParser::parse($value,'hh:mm:ss'));
	}
	*/
	/**
	 * Formats the value as a date and time.
	 * @param mixed $value the value to be formatted
	 * @return string the formatted result
	 * @see datetimeFormat
	 */
	/*
	public function formatDatetime($value)
	{
		if(isEmpty($value))
		{
			return;
		}
		return date($this->datetimeFormat,CDateTimeParser::parse($value,'yyyy-MM-dd hh:mm:ss'));
	}
	*/
	public function formatRole($value)
	{
		static $roles;
		if(is_null($value))
		{
			return '';
		}
		if(!is_string($value))
			return '';
		$rbac=new Rights();
		if(is_null($roles))
			$roles=$rbac->getAuthItemSelectOptions(2,array('Guest','authenticated','admin'));
		return isset($roles[$value])?$roles[$value]:'';
	}
	/**
	 * 格式化审核状态
	 * @param unknown_type $value
	 * @return string
	 */
	public function formatVerify($value)
	{
		if(is_null($value))
		{
			return t('epmms','未审核');
		}
		else if($value==0)
		{
			return t('epmms','未审核');
		}
		else if($value==1)
		{
			return t('epmms','已审核');
		}
		else
		{
			return t('epmms','未审核');
		}
	}
	public function formatWithdrawalsStatus($value)
	{
		if(is_null($value))
		{
			return t('epmms','未审核');
		}
		else if($value==0)
		{
			return t('epmms','未审核');
		}
		else if($value==1)
		{
			return t('epmms','已审核');
		}
		else if($value==2)
		{
			return t('epmms','已发放');
		}
		else
		{
			return t('epmms','未审核');
		}
	}
	/**
	 * 格式化审核状态
	 * @param unknown_type $value
	 * @return string
	 */
	public function formatSend($value)
	{
		if(is_null($value))
		{
			return t('epmms','未发货');
		}
		else if($value==0)
		{
			return t('epmms','未发货');
		}
		else if($value==1)
		{
			return t('epmms','已发货');
		}
		else
		{
			return t('epmms','未发货');
		}
	}
	public function formatLogcategory($value)
	{
		if(is_null($value))
		{
			return t('epmms','操作日志');
		}
		elseif($value=='operate')
		{
			return t('epmms','操作日志');
		}
		elseif($value=='finance')
		{
			return t('epmms','财务日志');
		}
		elseif($value=='login')
		{
			return t('epmms','登录日志');
		}
		else
		{
			return t('epmms','操作日志');
		}
	}
	public function formatLogsource($value)
	{
		static $tasks;
		if(is_null($value))
		{
			return '';
		}
		if(!is_string($value))
			return '';
		$source=ucfirst($value). '.*';
		$rbac=new Rights();
		if(is_null($tasks))
			$tasks=$rbac->getAuthItemSelectOptions(1);
		return isset($tasks[$source]) && $tasks[$source]!==$source?$tasks[$source]:t('log',$value,array(),'logOperation');
	}
	public function formatLogoperate($value)
	{
		static $operate;
		if(is_null($value))
		{
			return '';
		}
		if(!is_object($value))
			return '';
		$source=ucfirst($value->log_source) . '.' . ucfirst($value->log_operate);
		$rbac=new Rights();
		if(is_null($operate))
			$operate=$rbac->getAuthItemSelectOptions(0);
		if(isset($operate[$source]) && ($operate[$source]!==$source))
			return $operate[$source];
		else
			return t('log',$value->log_operate,array(),'logOperation');
	}
	public function formatAgent($value)
	{
		if(is_null($value))
		{
			return t('epmms','否');
		}
		else if($value==0)
		{
			return t('epmms','否');
		}
		else if($value==1)
		{
			return t('epmms','是');
		}
		else
		{
			return t('epmms','否');
		}
	}
	public function formatContentThumb($value)
	{
		$str= htmlspecialchars_decode($value);
		$str_code= preg_replace("/<(.*?)>/","",$str);
		$str_ret=mb_substr($str_code,0,20);
		if(mb_strlen($str_code)>20)
			$str_ret=$str_ret . '……';
		return $str_ret;
	}
	public function formatPercentage($value)
	{
		return sprintf("%01.2f", $value*100).'%';
	}
	public function formatLogStatus($value)
	{
		return $value==1?t('epmms','失败'):t('epmms','成功');
	}
	public function formatMapOrder($value)
	{
		if(is_null($value))
			return null;
		return chr($value+64);
	}
	public function formatSigning_type($value)
	{
		if(is_null($value))
			return null;
		if($value==0)
			return '注册签约';
		elseif($value==1)
			return '购物签约';
	}
	public function formatSigning($value)
	{
		if(is_null($value))
			return null;
		if($value==0)
			return '未签约';
		elseif($value==1)
			return '已签约';
		elseif($value==2)
			return '已退款';
	}
	public function formatColor($value)
	{
		return "<div style=\"margin:auto auto;border: 1px solid #CCCCCC;width:60px;height:20px;background-color:#$value;\"></div>";
	}
	public function formatAppropriateType($value)
	{
		return $value==0?t('epmms','拨款'):t('epmms','扣款');
	}
	public function formatMapOperate($value)
	{
		$form[1]='删除';
		$form[2]='添加';
		$form[3]='移动';
		$form[4]='交换';
		return $form[$value];
	}
	public function formatMapType($value)
	{
		if(is_null($value))
			return null;
		if($value==1)
			return '接点关系';
		elseif($value==2)
			return '推荐关系';
	}
	public function formatJobstatus($value)
	{
		if(is_null($value))
			return null;
		switch($value)
		{
			case 'r':
				return '正在运行';
				break;
			case 's':
				return '成功';
				break;
			case 'f':
				return '失败';
				break;
			case 'i':
				return '无法执行';
				break;
			case 'd':
				return '执行中断';
				break;
		}

	}
	public function formatMoney($value)
	{
		return $value . params('money_unit');
	}
}

?>