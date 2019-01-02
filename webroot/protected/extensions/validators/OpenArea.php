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
class OpenArea extends CValidator
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
		$membertype=$object->membermap_membertype_level;
		if($this->isEmpty($membertype))
		{
			$message=t('epmms','请选择会员类型');
			$this->addError($object,$attribute,$message);
			return;
		}
		if($membertype<=3)
		{
			if($value>=3)
			{
				$message=t('epmms','非代理商不能选择C区');
				$this->addError($object,$attribute,$message);
				return;
			}
		}
		/*
		if($value>=2 && $member->membermap_recommend_number<1)
		{
			$message=t('epmms','B区未开通,推荐1个以上会员以后才能开通B区');
			$this->addError($object,$attribute,$message);
		}
		*/
		//判断5：6条件
		/*
		if($value==3)
		{
			$memberA=$member->findByAttributes(['membermap_order'=>1,'membermap_parent_id'=>$member_id,'membermap_is_verify'=>1]);
			$memberB=$member->findByAttributes(['membermap_order'=>2,'membermap_parent_id'=>$member_id,'membermap_is_verify'=>1]);

			if(is_null($memberA) || $memberA->membermap_under_number+1<5)
			{
				$message=t('epmms','A区当前不足5人,A区5个人以上并且B区6个人以上才能开通。');
				$this->addError($object,$attribute,$message);
				return;
			}
			if(is_null($memberB) && $memberB->membermap_under_number+1<6)
			{
				$message=t('epmms','B区当前不足6人,A区5个人以上并且B区6个人以上才能开通。');
				$this->addError($object,$attribute,$message);
				return;
			}
		}*/

	}
}
