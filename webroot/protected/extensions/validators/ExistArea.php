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
class ExistArea extends CValidator
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
		// if(is_null($member_id))
		// {
		// 	$this->addError($object,$attribute,$message);
		// 	return;
		// }
		$membertype=$object->membermap_membertype_level;
		if(webapp()->id=='141203')
		{
			if ($this->isEmpty($membertype))
			{
				$this->addError($object, $attribute, '未正确选择会员类型');
				return;
			}
			if($membertype<=3)
			{
				$parent=Membermap2::model()->findByAttributes(['membermap_bond_id'=>$member_id]);
				if(is_null($parent))
				{
					$this->addError($object,$attribute,$message);
					return;
				}
				if(Membermap2::model()->exists('membermap_order=:order and membermap_parent_id=:id',[':order'=>$value,'id'=>$parent->membermap_id]))
				{
					$message=t('epmms','该位置已有人，请选择其它位置或接点人(双轨) ');
					$this->addError($object,$attribute,$message);
				}
			}
			else
			{
				$parent=Membermap4::model()->findByAttributes(['membermap_member_id'=>$member_id]);
				if(is_null($parent))
				{
					$this->addError($object,$attribute,$message);
					return;
				}
				if(Membermap4::model()->exists('membermap_order=:order and membermap_parent_id=:id',[':order'=>$value,'id'=>$parent->membermap_id]))
				{
					$message=t('epmms','该位置已有人，请选择其它位置或接点人(三轨) ');
					$this->addError($object,$attribute,$message);
					return;
				}
			}
			return;
		}
		// $member=Membermap::model()->findByPk($member_id);
		// // if(is_null($member))
		// // {
		// // 	$this->addError($object,$attribute,$message);
		// // 	return;
		// // }
		// $parents = Membermap::model()->findByPk($object->membermap_recommend_id);
	
		
              
  //       $parent=Membermap::model()->find(['order'=>'membermap_verify_seq asc ','condition'=>"membermap_child_number<2  and membermap_path like '$parents->membermap_path%'"]);

		// if(Membermap::model()->exists('membermap_order=:order and membermap_parent_id=:id',[':order'=>$value,'id'=>$parent->membermap_id]))
		// {

		// 	$message=t('epmms','该位置已有人，请选择其它位置或接点人。');
		// 	$this->addError($object,$attribute,$message);
		// 	return;
		// }
	}
}
