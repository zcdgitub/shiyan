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
class OpenArea_first_recommend extends CValidator
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
		$member_id=$object->membermap_parent_id;
		$message=t('epmms','未正确填写接点人');
		if(is_null($member_id))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		$member=Membermap::model()->findByPk($member_id);
		if(is_null($member))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		if(Membermap::model()->exists('membermap_order=:order and membermap_parent_id=:id',[':order'=>$value,'id'=>$member_id]))
		{
			$message=t('epmms','该位置已有人，请选择其它位置或接点人 ');
			$this->addError($object,$attribute,$message);
			return;
		}

		if($value>=2 && $member->membermap_recommend_number<1)
		{
			$message=t('epmms','B区未开通,有推荐人才能开通B区');
			$this->addError($object,$attribute,$message);
		}

	}
}
