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
class AbleMove extends CValidator
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
		$member_id=$value;
		$message=t('epmms','未正确填写会员');
		if(is_null($member_id))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		$member=Memberinfo::model()->findByPk($member_id);
		if(is_null($member))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		$edit_type=$object->map_edit_type;
		if(is_null($edit_type))
		{
			$this->addError($object,$attribute,t('epmms','未正确选择图谱类型'));
			return;
		}
		$src_member_id=$object->map_edit_src_member_id;
		if(is_null($src_member_id))
		{
			$this->addError($object,$attribute,t('epmms','未正确填写源会员'));
			return;
		}
		if($edit_type==1)
		{
			if(MapEdit::isBelong($src_member_id,$member_id))
			{
				$this->addError($object,$attribute,t('epmms','不能向自己接点关系下面移动'));
				return;
			}
			if(MapEdit::existChild($member_id,1))
			{
				$this->addError($object,$attribute,t('epmms','目标位置非空'));
				return;
			}
		}
		if($edit_type==2)
		{
			if(MapEdit::isBelong_recommend($src_member_id,$member_id))
			{
				$this->addError($object,$attribute,t('epmms','不能向自己推荐关系下面移动'));
				return;
			}
/*			if(MapEdit::existChild($member_id,2))
			{
				$this->addError($object,$attribute,t('epmms','目标位置非空'));
				return;
			}*/
		}

	}
}
