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
class QQ extends CValidator
{
	/**
	 * 默认允许使用邮件
	 * @var unknown_type
	 */
	public $allowEmail=true;
	/**
	 * 默认允许使用手机号
	 * @var unknown_type
	 */
	public $allowTel=true;
	/**
	 * 默认允许使用QQ
	 */
	public $allowQQ=true;

	/**
	 * 验证邮件地址
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $patternEmail='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
	/**
	 * 验证QQ号
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^[1-9][0-9]+$/';
	/**
	 * 验证中国手机号(13,14,15,18,19号段)
	 * @var unknown_type
	 */
	public $patternTel='/^((1[34589]{1}[0-9]{1})\d{8})$/';

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$errorMsg='不正确,可使用QQ号码，电话号码，邮件地址等';
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
		//限制长度，避免DOS攻击
		if($valid=strlen($value)<=20)
			$valid=($this->allowQQ && preg_match($this->pattern,$value)) || ($this->allowEmail && preg_match($this->patternEmail,$value)) || ($this->allowTel && preg_match($this->patternTel,$value));
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
		$errorMsg='不正确,可使用QQ号码，电话号码，邮件地址等';
		$message=$this->message!==null ? $this->message : Yii::t('yii','{attribute} ' . $errorMsg);
		$message=strtr($message, array(
			'{attribute}'=>$object->getAttributeLabel($attribute),
		));
		$condition='';
		if($this->allowEmail)
			$condition.= ($condition===''?'':'&&') . (" !value.match({$this->patternEmail})");
		if($this->allowQQ)
			$condition.= ($condition===''?'':'&&') . (" !value.match({$this->pattern})");
		if($this->allowTel)
			$condition.= ($condition===''?'':'&&') . (" !value.match({$this->patternTel})");
		$condition.= $condition===''?'false':'';
		$condition='(' . $condition . ')';
		return "
if($.trim(value)!='' && " . $condition . ") {
	messages.push(".CJSON::encode($message).");
}
";
	}
	
}
