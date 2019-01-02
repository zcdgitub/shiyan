<?php

/**
 * This is the model class for table "{{config_smtp}}".
 *
 * The followings are the available columns in table '{{config_smtp}}':
 * @property string $config_smtp_id
 * @property string $config_smtp_server
 * @property integer $config_smtp_port
 * @property string $config_smtp_account
 * @property string $config_smtp_password
 * @property string $config_smtp_email
 * @property integer $config_smtp_enable
 */
class ConfigSmtp extends Model
{
	public $modelName='EMail通知设置';
	public $nameColumn='config_smtp_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConfigSmtp the static model class
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
		return '{{config_smtp}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('config_smtp_port, config_smtp_enable', 'filter','filter'=>array($this,'empty2null')),
			array('config_smtp_server, config_smtp_account, config_smtp_password, config_smtp_email', 'required'),
			array('config_smtp_port, config_smtp_enable', 'numerical', 'integerOnly'=>true),
			array('config_smtp_server, config_smtp_account, config_smtp_password, config_smtp_email', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('config_smtp_id, config_smtp_server, config_smtp_port, config_smtp_account, config_smtp_password, config_smtp_email, config_smtp_enable', 'safe', 'on'=>'search'),
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
			'config_smtp_id' => 'Config Smtp',
			'config_smtp_server' => t('epmms','SMTP服务器'),
			'config_smtp_port' => t('epmms','SMTP端口'),
			'config_smtp_account' => t('epmms','SMTP登录账号'),
			'config_smtp_password' => t('epmms','SMTP密码'),
			'config_smtp_email' => t('epmms','Email地址'),
			'config_smtp_enable' => t('epmms','是否启用Email通知'),
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
		$sort=new Sort('ConfigSmtp');
		$sort->defaultOrder=array('config_smtp_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('config_smtp_id',$this->config_smtp_id,true);
		$criteria->compare('config_smtp_server',$this->config_smtp_server,true);
		$criteria->compare('config_smtp_port',$this->config_smtp_port);
		$criteria->compare('config_smtp_account',$this->config_smtp_account,true);
		$criteria->compare('config_smtp_password',$this->config_smtp_password,true);
		$criteria->compare('config_smtp_email',$this->config_smtp_email,true);
		$criteria->compare('config_smtp_enable',$this->config_smtp_enable);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}