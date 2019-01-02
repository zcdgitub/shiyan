<?php

/**
 * This is the model class for table "{{config_auth}}".
 *
 * The followings are the available columns in table '{{config_auth}}':
 * @property string $config_auth_id
 * @property integer $config_auth_autologin
 * @property integer $config_auth_timeout
 * @property integer $config_auth_autologin2
 * @property integer $config_auth_timeout2
 * @property integer $config_auth_captcha
 */
class ConfigAuth extends Model
{
	public $modelName='认证设置';
	public $nameColumn='config_auth_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConfigAuth the static model class
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
		return '{{config_auth}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('config_auth_autologin, config_auth_timeout, config_auth_autologin2, config_auth_timeout2, config_auth_captcha', 'filter','filter'=>array($this,'empty2null')),
			array('config_auth_autologin, config_auth_timeout, config_auth_autologin2, config_auth_timeout2, config_auth_captcha','required'),
			array('config_auth_autologin, config_auth_timeout, config_auth_autologin2, config_auth_timeout2, config_auth_captcha', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('config_auth_id, config_auth_autologin, config_auth_timeout, config_auth_autologin2, config_auth_timeout2, config_auth_captcha', 'safe', 'on'=>'search'),
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
			'config_auth_id' => 'Config Auth',
			'config_auth_autologin' => t('epmms','自动登录'),
			'config_auth_timeout' => t('epmms','登录超时'),
			'config_auth_autologin2' => t('epmms','二级密码自动登录'),
			'config_auth_timeout2' => t('epmms','二级密码超时'),
			'config_auth_captcha' => t('epmms','验证码'),
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
		$sort=new Sort('ConfigAuth');
		$sort->defaultOrder=array('config_auth_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('config_auth_id',$this->config_auth_id,true);
		$criteria->compare('config_auth_autologin',$this->config_auth_autologin);
		$criteria->compare('config_auth_timeout',$this->config_auth_timeout);
		$criteria->compare('config_auth_autologin2',$this->config_auth_autologin2);
		$criteria->compare('config_auth_timeout2',$this->config_auth_timeout2);
		$criteria->compare('config_auth_captcha',$this->config_auth_captcha);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}