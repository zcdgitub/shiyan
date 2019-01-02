<?php
/**
 * CExistValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CExistValidator validates that the attribute value exists in a table.
 *
 * This validator is often used to verify that a foreign key contains a value
 * that can be found in the foreign table.
 *
 * When using the {@link message} property to define a custom error message, the message
 * may contain additional placeholders that will be replaced with the actual content. In addition
 * to the "{attribute}" placeholder, recognized by all validators (see {@link CValidator}),
 * CExistValidator allows for the following placeholders to be specified:
 * <ul>
 * <li>{value}: replaced with value of the attribute.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.validators
 */
class EmailPassword extends CValidator
{

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=false;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 * @throws CException if given table does not have specified column name
	 */
	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;

		$account=$object->account;
		if(!Memberinfo::model()->exists('memberinfo_account=:account and memberinfo_email=:email',[':account'=>$account,':email'=>$value]))
		{
			$message=$this->message!==null?$this->message:Yii::t('epmms','{attribute} 不存在.');
			$this->addError($object,$attribute,$message);
		}
	}

}
