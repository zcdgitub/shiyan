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
class OpenParent extends CValidator
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
		if(webapp()->id=='141203')
		{
			$membertype=$object->membermap_membertype_level;
			if ($this->isEmpty($membertype))
			{
				$this->addError($object, $attribute, '未正确选择会员类型');
				return;
			}
			if($membertype<=3)
			{
				$parent=Membermap2::model()->findByAttributes(['membermap_bond_id'=>$value]);
				if(is_null($parent))
				{
					$this->addError($object,$attribute,'网络图（双轨）中接点人不存在');
					return;
				}
			}
			else
			{
				$parent=Membermap4::model()->findByAttributes(['membermap_member_id'=>$value]);
				if(is_null($parent))
				{
					$this->addError($object,$attribute,'网络图（三轨）中接点人不存在');
					return;
				}
			}
			return;
		}
		$member_id=isset($_POST['Membermap']['membermap_recommend_id'])?$_POST['Membermap']['membermap_recommend_id']:null;
		$message=t('epmms','未正确填写推荐人');
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
		if(!Membermap::model()->exists("membermap_is_verify=1 and membermap_id=:id and membermap_path like :rpath || '%' ",[':rpath'=>$member->membermap_path,'id'=>$value]))
		{
			$message=t('epmms','不是推荐人的下级');
			$this->addError($object,$attribute,$message);
		}
	}
}
