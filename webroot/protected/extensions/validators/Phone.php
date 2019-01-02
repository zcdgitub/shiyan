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
class Phone extends CValidator
{
	/**
	 * 默认不允许国外号码
	 * @var unknown_type
	 */
	public $allowIntl=false;
	/**
	 * 默认允许国内固话
	 * @var unknown_type
	 */
	public $allowTel=true;
	/**
	 * 默认允许国内手机号
	 * @var unknown_type
	 */
	public $allowMobi=true;
	/**
	 * 任意国家的固话和手机号码,松散的格式验证
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $patternIntl='/^(\+?[0-9]{1,3}(\-|\s)?)?([0-9](\-|\s)?)+$/';
	/**
	 * 验证中国手机号(13,14,15,18,19号段)
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^(\+?86(\-|\s)?)?((1[0-9]{1}[0-9]{1})\d{8})$/';
	/**
	 * 验证中国固话
	 * @var unknown_type
	 */
	public $patternTel='/^(\+?86(\-|\s)?)?(\d{3,4}[\-\s]?)?\d{7,9}$/';

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$errorMsg='不正确,必须为正确的电话号码';
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
			$valid=($this->allowIntl && preg_match($this->patternIntl,$value)) || ($this->allowMobi && preg_match($this->pattern,$value)) || ($this->allowTel && preg_match($this->patternTel,$value));
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
		$errorMsg='不正确,必须为正确的电话号码';
		$message=$this->message!==null ? $this->message : Yii::t('yii','{attribute} ' . $errorMsg);
		$message=strtr($message, array(
			'{attribute}'=>$object->getAttributeLabel($attribute),
		));
		$condition='';
		if($this->allowIntl)
			$condition.= ($condition===''?'':'&&') . (" !value.match({$this->patternIntl})");
		if($this->allowMobi)
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
