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
class TransferBalance extends CValidator
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
		$message=t('epmms','余额不足');
		if($this->isEmpty($object->transfer_src_member_id))

			return;
		if($this->isEmpty($object->transfer_src_finance_type))
			return;
		$config=TransferConfig::model()->find()->transfer_config_tax;
		$fin=Finance::getMemberFinance($object->transfer_src_member_id,$object->transfer_src_finance_type);
		if(is_null($fin))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		if($fin->finance_award<$value+abs($config*$value))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		if($value<100 || $value%10!=0)
		{
			$this->addError($object,$attribute,'转账金额需要大于等于100且为10的整数倍');
			return;
		}
	}
}
