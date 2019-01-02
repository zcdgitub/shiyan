<?php
/**
 * CNumberValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CNumberValidator validates that the attribute value is a number.
 *
 * In addition to the {@link message} property for setting a custom error message,
 * CNumberValidator has a couple custom error messages you can set that correspond to different
 * validation scenarios. To specify a custom message when the numeric value is too big,
 * you may use the {@link tooBig} property. Similarly with {@link tooSmall}.
 * The messages may contain additional placeholders that will be replaced
 * with the actual content. In addition to the "{attribute}" placeholder, recognized by all
 * validators (see {@link CValidator}), CNumberValidator allows for the following placeholders
 * to be specified:
 * <ul>
 * <li>{min}: when using {@link tooSmall}, replaced with the lower limit of the number {@link min}.</li>
 * <li>{max}: when using {@link tooBig}, replaced with the upper limit of the number {@link max}.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.validators
 * @since 1.0
 */
class OpenMemberType extends CValidator
{

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;
		if($this->isEmpty($value))
			return;
		if($this->isEmpty($object->membermap_recommend_id))
		{
			$message=t('epmms','请填写正确推荐人');
			$this->addError($object,$attribute,$message);
			return;
		}
		$r=Membermap::model()->findByPk($object->membermap_recommend_id);
		if($r->membermap_membertype_level==4)
		{
			if($value!=4)
			{
				$this->addError($object,$attribute,t('epmms','代理商只能推荐代理商'));
				return;
			}
		}
		/*
		if($value>=2)
		{
			if($r->membermap_membertype_level<>1)
			{
				$message=t('epmms','推荐人必须是VIP会员,才能注册代理商会员');
				$this->addError($object,$attribute,$message);
			}
		}
		if($value==2)
		{
			if($this->isEmpty($_POST['Memberinfo']['memberinfo_address_county']))
			{
				$message=t('epmms','地址-县不能为空');
				$this->addError($object,$attribute,$message);
			}
			if($this->isEmpty($_POST['Memberinfo']['memberinfo_address_area']))
			{
				$message=t('epmms','地址-市不能为空');
				$this->addError($object,$attribute,$message);
			}
			if($r->memberinfo->exists(['condition'=>'memberinfo_address_county=:county and memberinfo_address_area=:area and memberinfo_address_provience=:provience and membermap_membertype_level=2','with'=>'membermap',
				'params'=>[':county'=>$_POST['Memberinfo']['memberinfo_address_county'],':area'=>$_POST['Memberinfo']['memberinfo_address_area'],':provience'=>$_POST['Memberinfo']['memberinfo_address_provience']]]))
			{
				$message=t('epmms','本县县级代理商已存在');
				$this->addError($object,$attribute,$message);
			}
		}
		if($value==3)
		{
			if($this->isEmpty($_POST['Memberinfo']['memberinfo_address_area']))
			{
				$message=t('epmms','地址-市不能为空');
				$this->addError($object,$attribute,$message);
			}
			if($r->memberinfo->exists(['condition'=>'memberinfo_address_area=:area and memberinfo_address_provience=:provience and membermap_membertype_level=3','with'=>'membermap',
				'params'=>[':area'=>$_POST['Memberinfo']['memberinfo_address_area'],':provience'=>$_POST['Memberinfo']['memberinfo_address_provience']]]))
			{
				$message=t('epmms','本市市级代理商已存在');
				$this->addError($object,$attribute,$message);
			}
		}
		if($value==4)
		{
			if($r->memberinfo->exists(['condition'=>'memberinfo_address_provience=:provience and membermap_membertype_level=4','with'=>'membermap',
				'params'=>[':provience'=>$_POST['Memberinfo']['memberinfo_address_provience']]]))
			{
				$message=t('epmms','本省省级代理商已存在');
				$this->addError($object,$attribute,$message);
			}
		}*/
	}
}
