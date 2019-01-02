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
class AbleWithdrawals extends CValidator
{

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
/*	    if(date('w')!=1 && date('w')!=2)
        {
            $this->addError($object,$attribute,'今天不是提现日');
            return;
        }*/
		$value=$object->$attribute;
		$message=t('epmms','未正确填写金额');
		if(is_null($value))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		if(is_null($object->withdrawals_finance_type_id))
		{
			$this->addError($object,$attribute,t('epmms','请先选择账户类型'));
			return;
		}
		if(is_null($object->withdrawals_member_id))
		{
			$this->addError($object,$attribute,t('epmms','请先选择会员'));
			return;
		}
		$finance=Finance::getMemberFinance($object->withdrawals_member_id,$object->withdrawals_finance_type_id);
		//手续费
		$fee_config=config('withdrawals','tax');
		$fee_currency=abs($value*$fee_config);
		$deduct_currency=config('withdrawals','type')==0?$value+$fee_currency:$value;
		if($finance->finance_award<=0 || $deduct_currency>$finance->finance_award)
		{
			$this->addError($object,$attribute,t('epmms','余额不足'));
		}

	}
}