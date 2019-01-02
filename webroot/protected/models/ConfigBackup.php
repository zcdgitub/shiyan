<?php

/**
 * This is the model class for table "{{config_backup}}".
 *
 * The followings are the available columns in table '{{config_backup}}':
 * @property integer $config_backup_id
 * @property integer $config_backup_days
 * @property integer $config_backup_count
 */
class ConfigBackup extends Model
{
	public $modelName='备份配置';
	public $nameColumn='config_backup_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConfigBackup the static model class
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
		return '{{config_backup}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('config_backup_days, config_backup_count', 'filter','filter'=>array($this,'empty2null')),
			array('config_backup_days, config_backup_count', 'required'),
			array('config_backup_days, config_backup_count', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('config_backup_id, config_backup_days, config_backup_count', 'safe', 'on'=>'search'),
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
			'config_backup_id' => 'Config Backup',
			'config_backup_days' => t('epmms','保留天数'),
			'config_backup_count' => t('epmms','保留个数'),
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
		$sort=new Sort('ConfigBackup');
		$sort->defaultOrder=array('config_backup_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('config_backup_id',$this->config_backup_id);
		$criteria->compare('config_backup_days',$this->config_backup_days);
		$criteria->compare('config_backup_count',$this->config_backup_count);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}