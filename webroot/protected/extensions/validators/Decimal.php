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
class Decimal extends CValidator
{
	const PLUS=0;
	const MINUS=1;
	const RATIONAL=2;
	protected $patternSign=array(self::PLUS=>'\+?',self::MINUS=>'-',self::RATIONAL=>'[\+,-]?');
	protected $messageSign=array(self::PLUS=>'正数',self::MINUS=>'负数',self::RATIONAL=>'数');
	/**
	 * 符号,正数PLUS，负数minus,有理数RATIONAL(正负皆可)
	 * @var string
	 */
	public $sign=self::RATIONAL;
	/**
	 * 默认允许０
	 * @var allowZero
	 */
	public $allowZero=true;
	/**
	 * 精度
	 * @var integer
	 */
	public $precision=16;
	/**
	 * 小数位数
	 * @var integer
	 */
	public $scale=2;

	/**
	 * 验证小数
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^(sign\d{1,precision})(\.\d{0,scale})?$/';
	public $patternZero='/^[\+,-]?(0+)(\.0+)?$/';


	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$errorMsg=$this->errorMessage;
		$value=$object->$attribute;
		if($this->isEmpty($value))
			return;
		if(!$this->validateValue($value))
		{
			$message=$this->message!==null?$this->message:'{attribute} ' . $errorMsg;
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
			$pattern=strtr($this->pattern,array('sign'=>$this->patternSign[$this->sign],'precision'=>$this->precision-$this->scale,'scale'=>$this->scale));
			if($this->allowZero)
				$valid=(preg_match($this->patternZero,$value)) || (preg_match($pattern,$value));
			else
				$valid=(!preg_match($this->patternZero,$value)) && (preg_match($pattern,$value));
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
		$pattern=strtr($this->pattern,array('sign'=>$this->patternSign[$this->sign],'precision'=>$this->precision-$this->scale,'scale'=>$this->scale));
		$errorMsg=$this->errorMessage;
		$message=$this->message!==null ? $this->message : '{attribute} ' . $errorMsg;
		$message=strtr($message, array(
			'{attribute}'=>$object->getAttributeLabel($attribute),
		));
		$condition='';
		if(!$this->allowZero)
		{
			$condition= " value.match({$this->patternZero}) || !value.match({$pattern})";
		}
		else
		{
			$condition= " !value.match({$this->patternZero}) && !value.match({$pattern})";
		}
		
		$condition.= $condition===''?'false':'';
		$condition='(' . $condition . ')';
		return "
if($.trim(value)!='' && " . $condition . ") {
	messages.push(".CJSON::encode($message).");
}
";
	}
	public function getErrorMessage()
	{
		return t('epmms','不正确,必须是精度为{precision}位,小数点后至多{scale}位',array('{precision}'=>$this->precision,'{scale}'=>$this->scale)) . ($this->allowZero?'':t('epmms','不为零的')) . t('epmms',$this->messageSign[$this->sign]);
	}
}
