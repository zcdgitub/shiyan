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
class AbleBuy2 extends CValidator
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
		$message=t('epmms','未正确填写会员');
		if(is_null($value))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		$buy_type=$object->buy_type;
		if(webapp()->id=='180821')
        {
            if($value%500!=0)
            {
                $this->addError($object,$attribute,'必须是500的整数倍');
            }
        }

/*		if($buy_type==1 && $value<1000)
		{
            //throw new EError('管理钱包不能低于1000');
			$this->addError($object,$attribute,'管理钱包不能低于1000');
		}*/
/*		if(Buy::model()->exists('buy_date::date=current_date and buy_member_id=:id',[':id'=>user()->id]))
        {
            $this->addError($object,$attribute,'一天内只能卖出一次');
        }*/

	}
}
