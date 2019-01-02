<?php

/**
 * This is the model class for table "{{bankaccount}}".
 *
 * The followings are the available columns in table '{{bankaccount}}':
 * @property string $bankaccount_id
 * @property string $bankaccount_bank_id
 * @property string $bankaccount_account
 * @property string $bankaccount_provience
 * @property string $bankaccount_area
 * @property string $bankaccount_branch
 * @property string $bankaccount_mobi
 * @property string $bankaccount_phone
 * @property string $bankaccount_qq
 * @property integer $bankaccount_is_enable
 * @property string $bankaccount_name
 *
 * The followings are the available model relations:
 * @property  */
class Bankaccount extends Model
{
	public $modelName='银行帐号';
	public $nameColumn='bankaccount_account';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Bankaccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{bankaccount}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bankaccount_bank_id, bankaccount_account,bankaccount_name', 'required'),
			array('bankaccount_is_enable', 'numerical', 'integerOnly'=>true),
			array('bankaccount_bank_id', 'length', 'max'=>11),
			array('bankaccount_account, bankaccount_provience, bankaccount_area, bankaccount_mobi, bankaccount_phone', 'length', 'max'=>20),
			array('bankaccount_branch, bankaccount_qq', 'length', 'max'=>50),
			array('bankaccount_account', 'unique'),
			array('bankaccount_bank_id', 'exist', 'className'=>'Bank','attributeName'=>'bank_id'),
			array('bankaccount_is_enable', 'ext.validators.Enable'),
			array('bankaccount_phone', 'ext.validators.Phone'),
			array('bankaccount_mobi', 'ext.validators.Phone','allowTel'=>false),
			array('bankaccount_qq', 'ext.validators.QQ'),
			array('bankaccount_account', 'ext.validators.Account','allowZh'=>false),
			array('bankaccount_name', 'ext.validators.Account','allowZh'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('bankaccount_id, bankaccount_bank_id, bankaccount_account, bankaccount_provience, bankaccount_area, bankaccount_branch, bankaccount_mobi, bankaccount_phone, bankaccount_qq, bankaccount_is_enable', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'bankaccountBank' => array(Model::BELONGS_TO, 'Bank', 'bankaccount_bank_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bankaccount_id' => t('epmms','Bankaccount'),
			'bankaccount_bank_id' => t('epmms','银行'),
			'bankaccount_account' => t('epmms','银行帐号'),
			'bankaccount_provience' => t('epmms','省'),
			'bankaccount_area' => t('epmms','市'),
			'bankaccount_branch' => t('epmms','地址'),
			'bankaccount_mobi' => t('epmms','手机号码'),
			'bankaccount_phone' => t('epmms','电话号码'),
			'bankaccount_qq' => t('epmms','QQ号码'),
			'bankaccount_is_enable' => t('epmms','开启'),
			'bankaccount_name'=>t('epmms','开户名')
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$sort=new Sort('Bankaccount');
		$sort->defaultOrder=array('bankaccount_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('bankaccount_id',$this->bankaccount_id,true);
		$criteria->compare('bankaccount_account',$this->bankaccount_account,true);
		$criteria->compare('bankaccount_provience',$this->bankaccount_provience,true);
		$criteria->compare('bankaccount_area',$this->bankaccount_area,true);
		$criteria->compare('bankaccount_branch',$this->bankaccount_branch,true);
		$criteria->compare('bankaccount_mobi',$this->bankaccount_mobi,true);
		$criteria->compare('bankaccount_phone',$this->bankaccount_phone,true);
		$criteria->compare('bankaccount_qq',$this->bankaccount_qq,true);
		$criteria->compare('bankaccount_is_enable',$this->bankaccount_is_enable);
		$criteria->compare('bankaccount_name',$this->bankaccount_name);
		$criteria->compare('"bankaccountBank".bank_name',$this->bankaccountBank->bank_name);
		$criteria->with=array('bankaccountBank');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}