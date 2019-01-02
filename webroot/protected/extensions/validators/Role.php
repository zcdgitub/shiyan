<?php
/**
 * @author hetao
 * 
 */

/**
 *
 * @author hetao
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class Role extends CValidator
{

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$errorMsg='不正确，必须为正确的角色';
		$value=$object->$attribute;
		if($this->isEmpty($value))
			return;
		if(!$this->validateValue($value))
		{
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} ' . $errorMsg);
			$this->addError($object,$attribute,$message);
		}
	}

	/**
	 * 验证角色是否存在
	 * 
	 * @param string $value the value to be validated
	 * @return boolean whether the value is a valid 
	 * @since 1.1.1
	 */
	public function validateValue($value)
	{
		$rbac=new Rights();
		$roles=$rbac->getAuthItemSelectOptions(2,array('Guest','authenticated','admin'));
		// make sure string length is limited to avoid DOS attacks
		if(!isEmpty($value))
		{
			$valid=isset($roles[$value]);
			return $valid;
		}
		return false;
	}
}
