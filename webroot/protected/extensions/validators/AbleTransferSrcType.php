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
class AbleTransferSrcType extends CValidator
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
		$message=t('epmms','未正确选择账户类型');
		if(is_null($value))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		
		$finance=FinanceType::model()->findByPk($value);
		if(is_null($finance))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		$config=TransferConfig::model()->find();
		// var_dump($config);
		// die;
		if(array_search($value,$config->transfer_config_src_type)===false)
		{
			$this->addError($object,$attribute,t('epmms','此账户类型不能转账'));
			return;
		}

	}
}
