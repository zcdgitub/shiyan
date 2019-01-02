<?php

/**
 * This is the model class for table "{{config_site}}".
 *
 * The followings are the available columns in table '{{config_site}}':
 * @property string $config_site_id
 * @property string $config_site_title
 * @property string $config_site_domain
 * @property string $config_site_keyword
 * @property string $config_site_desc
 * @property integer $config_site_deny_robots
 * @property integer $config_site_is_enable
 */
class ConfigSite extends Model
{
	public $modelName='站点设置';
	public $nameColumn='config_site_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConfigSite the static model class
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
		return '{{config_site}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('config_site_title, config_site_domain, config_site_keyword, config_site_desc, config_site_deny_robots', 'filter','filter'=>array($this,'empty2null')),
			array('config_site_deny_robots', 'numerical', 'integerOnly'=>true),
			array('config_site_is_enable','ext.validators.Enable'),
			array('config_site_is_enable,config_site_title, config_site_domain, config_site_deny_robots','required'),
			array('config_site_title, config_site_domain', 'length', 'max'=>50),
			array('config_site_keyword, config_site_desc', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('config_site_id, config_site_title, config_site_domain, config_site_keyword, config_site_desc, config_site_deny_robots', 'safe', 'on'=>'search'),
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
			'config_site_id' => 'Config Site',
			'config_site_title' =>t('epmms', '网站标题'),
			'config_site_domain' => t('epmms','网站域名'),
			'config_site_keyword' => t('epmms','搜索关键字'),
			'config_site_desc' => t('epmms','网站描述'),
			'config_site_deny_robots' => t('epmms','搜索引擎'),
			'config_site_is_enable'=>t('epmms','是否启用系统')
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
		$sort=new Sort('ConfigSite');
		$sort->defaultOrder=array('config_site_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('config_site_id',$this->config_site_id,true);
		$criteria->compare('config_site_title',$this->config_site_title,true);
		$criteria->compare('config_site_domain',$this->config_site_domain,true);
		$criteria->compare('config_site_keyword',$this->config_site_keyword,true);
		$criteria->compare('config_site_desc',$this->config_site_desc,true);
		$criteria->compare('config_site_deny_robots',$this->config_site_deny_robots);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}