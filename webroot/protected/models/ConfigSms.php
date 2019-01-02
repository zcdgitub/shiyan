<?php

/**
 * This is the model class for table "{{config_sms}}".
 *
 * The followings are the available columns in table '{{config_sms}}':
 * @property integer $config_sms_id
 * @property integer $config_sms_is_verify
 * @property integer $config_sms_is_award
 * @property string $config_sms_award
 * @property string $config_sms_verify
 * @property integer $config_sms_is_register
 * @property string $config_sms_register
 */
class ConfigSms extends Model
{
	public $modelName='短信配置';
	public $nameColumn='config_sms_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConfigSms the static model class
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
		return '{{config_sms}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('config_sms_is_verify, config_sms_is_award, config_sms_award, config_sms_verify, config_sms_is_register, config_sms_register', 'filter','filter'=>array($this,'empty2null')),
			array('config_sms_is_verify, config_sms_is_award, config_sms_is_register', 'numerical', 'integerOnly'=>true),
			array('config_sms_award, config_sms_verify, config_sms_register', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('config_sms_id, config_sms_is_verify, config_sms_is_award, config_sms_award, config_sms_verify, config_sms_is_register, config_sms_register', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'config_sms_id' => 'Config Sms',
			'config_sms_is_verify' =>t('epmms', '是否发送激活短信'),
			'config_sms_is_award' => t('epmms','是否发送奖金短信'),
			'config_sms_award' => t('epmms','奖金短信模板'),
			'config_sms_verify' => t('epmms','激活短信模板'),
			'config_sms_is_register' => t('epmms','是否发送注册短信'),
			'config_sms_register' => t('epmms','注册短信模板'),
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
		$sort=new Sort('ConfigSms');
		$sort->defaultOrder=array('config_sms_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('config_sms_id',$this->config_sms_id);
		$criteria->compare('config_sms_is_verify',$this->config_sms_is_verify);
		$criteria->compare('config_sms_is_award',$this->config_sms_is_award);
		$criteria->compare('config_sms_award',$this->config_sms_award,false);
		$criteria->compare('config_sms_verify',$this->config_sms_verify,false);
		$criteria->compare('config_sms_is_register',$this->config_sms_is_register);
		$criteria->compare('config_sms_register',$this->config_sms_register,false);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}