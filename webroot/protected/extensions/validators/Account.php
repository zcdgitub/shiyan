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
class Account extends CValidator
{
	/**
	 * 默认不允许用户帐号中包含中文
	 * @var unknown_type
	 */
	public $allowZh=false;
	/**
	 * 验证用户名，包含中文
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $patternZh='/^[\w_\-\x{4e00}-\x{9fa5}]+$/u';
	public $patternZhClient='/^[\w_\-\u4e00-\u9fa5]+$/';
	/**
	 * 验证用户名，不含中文
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^[\w_\-]+$/';

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$errorMsg=$this->allowZh?'只能包括数字,大小写字母,_,-,中文字符。':'只能包括数字,大小写字母,_,－。';
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
	 * Validates a static value to see if it is a valid email.
	 * Note that this method does not respect {@link allowEmpty} property.
	 * This method is provided so that you can call it directly without going through the model validation rule mechanism.
	 * @param mixed $value the value to be validated
	 * @return boolean whether the value is a valid email
	 * @since 1.1.1
	 */
	public function validateValue($value)
	{
		// make sure string length is limited to avoid DOS attacks
		$valid=is_string($value) && (preg_match($this->pattern,$value) || $this->allowZh && preg_match($this->patternZh,$value));
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
		$errorMsg=$this->allowZh?'只能包括数字,大小写字母,_,-,中文字符。':'只能包括数字,大小写字母,_,－。';
		$message=$this->message!==null ? $this->message : Yii::t('yii','{attribute} ' . $errorMsg);
		$message=strtr($message, array(
			'{attribute}'=>$object->getAttributeLabel($attribute),
		));
		$condition='';
		if($this->allowZh)
			$condition.=" !value.match({$this->patternZhClient})";
		else
			$condition.=" !value.match({$this->pattern})";
		return "
if($.trim(value)!='' && ".$condition.") {
	messages.push(".CJSON::encode($message).");
}
";
	}
	
}
