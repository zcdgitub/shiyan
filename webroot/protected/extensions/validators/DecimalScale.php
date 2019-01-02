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
class DecimalScale extends CValidator
{
	/**
	 * 小数位数
	 * @var integer
	 */
	public $scale=100;

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
		if(!$this->validateValue($value))
		{
			$message=$this->message!==null?$this->message:('{attribute} ' . $this->errorMessage);
			$this->addError($object,$attribute,$message);
		}
	}

	/**
	 * Validates a static value to see if it is a valid email.
	 * Note that this method does not respect {@link allowEmpty} property.
	 * This method is provided so that you can call it directly without going through the model validation rule mechanism.
	 * @param mixed $value the value to be validated
	 * @return boolean whether the value is a valid email
	 * @since 1.1.1
	 */
	public function validateValue($value)
	{
		//限制长度，避免DOS攻击
		if($valid=strlen($value)<=20)
		{
			$valid=$value%$this->scale==0?true:false;
		}
		return $valid;
	}

	/**
	 * Returns the JavaScript needed for performing client-side validation.
	 * @param CModel $object the data object being validated
	 * @param string $attribute the name of the attribute to be validated.
	 * @return string the client-side validation script.
	 * @see CActiveForm::enableClientValidation
	 * @since 1.1.7
	 */
	public function clientValidateAttribute($object,$attribute)
	{
		$errorMsg=$this->errorMessage;
		$message=$this->message!==null ? $this->message : '{attribute} ' . $errorMsg;
		$message=strtr($message, array(
			'{attribute}'=>$object->getAttributeLabel($attribute),
		));
		$condition= "(parseInt(value)%$this->scale==0)?false:true";
		$condition='(' . $condition . ')';
		return "
if($.trim(value)!='' && " . $condition . ") {
	messages.push(".CJSON::encode($message).");
}
";
	}
	public function getErrorMessage()
	{
		return t('epmms','不正确,必须是{scale}的倍数',array('{scale}'=>$this->scale));
	}
}
