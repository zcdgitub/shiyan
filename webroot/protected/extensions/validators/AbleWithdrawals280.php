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
class AbleWithdrawals280 extends CValidator
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
		$message=t('epmms','未正确填写金额');
		if(is_null($value))
		{
			$this->addError($object,$attribute,$message);
			return;
		}
		//第一个月不能提现
		$first_cmd=webapp()->db->createCommand("select date_trunc('month',now())=date_trunc('month',membermap_verify_date) from epmms_membermap where membermap_id=:id");
		$first=$first_cmd->queryScalar([':id'=>user()->id]);
		if($first)
		{
			$this->addError($object,$attribute,'第一个月不能提现,请下月再提');
			return;
		}
		//第二个月提现第一个月不限制
		$first_cmd=webapp()->db->createCommand("select date_trunc('month',now()-interval'1 month')=date_trunc('month',membermap_verify_date) from epmms_membermap where membermap_id=:id");
		$first=$first_cmd->queryScalar([':id'=>user()->id]);
		if($first)
			return;
		$finance=Finance::getMemberFinance(user()->id,1);

		$charge_cmd=webapp()->db->createCommand("select sum(charge_currency) from epmms_charge where charge_memberinfo_id=:id and charge_is_verify=1 and charge_finance_type_id=3 and date_trunc('month',charge_verify_date)=date_trunc('month', now()) - '1 mon'::interval");
		$charge=$charge_cmd->queryScalar([':id'=>user()->id]);
		if($charge<280)
		{
			$this->addError($object,$attribute,'上月重消未充值,不能提现');
			return;
		}
		//上月奖金
		$month_cmd=webapp()->db->createCommand("select sum(award_month_sum_currency) from epmms_award_month_sum where award_month_sum_memberinfo_id=:id and award_month_sum_date=(date_trunc('month', now()) - '1 mon'::interval)");
		$month=$month_cmd->queryScalar([':id'=>user()->id]);
		$month_withdrawals_cmd=webapp()->db->createCommand("select sum(withdrawals_currency) from epmms_withdrawals where withdrawals_member_id=:id and withdrawals_finance_type_id=1 and date_trunc('month',withdrawals_add_date)=(date_trunc('month', now()) )");
		$month_withdrawals=$month_withdrawals_cmd->queryScalar([':id'=>user()->id]);
		if($month_withdrawals+$value>$month)
		{
			$this->addError($object,$attribute,'上月所得奖金为' . $month . ',已超出');
			return;
		}
	}
}
