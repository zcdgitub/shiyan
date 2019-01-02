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
class AbleTransfer extends CValidator
{

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$config=TransferConfig::model()->find();
		$value=$object->$attribute;
		$member_id=$value;
		$message=t('epmms','未正确填写会员');
		if(is_null($member_id))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		/*
		if(user()->id==$member_id)
			return;*/
		$member=Memberinfo::model()->findByPk($member_id);
		if(is_null($member))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		if($config->transfer_config_relation!=1)
		{
			$membermap = $member->membermap;
			if (!$membermap->exists(":path2 like :path || '%'", [':path' => $membermap->membermap_recommend_path, ':path2' => user()->map->membermap_recommend_path]) &&
				!$membermap->exists(":path like :path2 || '%'", [':path' => $membermap->membermap_recommend_path, ':path2' => user()->map->membermap_recommend_path])
			)
			{
				$this->addError($object, $attribute, t('epmms', '非上下级之间不能转账'));
				return;
			}
		}
		if(MemberinfoItem::model()->itemVisible('membermap_agent_id') && $config->transfer_config_member_able!=1)
		{
			if (user()->info->memberinfo_is_agent == 0 && $member->memberinfo_is_agent == 0)
			{
				$this->addError($object, $attribute, t('epmms', '会员与会员之间不能转账'));
				return;
			}
		}

	}
}
