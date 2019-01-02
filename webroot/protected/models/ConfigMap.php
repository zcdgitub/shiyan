<?php

/**
 * This is the model class for table "{{config_map}}".
 *
 * The followings are the available columns in table '{{config_map}}':
 * @property string $config_map_id
 * @property integer $config_map_levels
 * @property integer $config_map_branch
 * @property string $config_map_orientation
 * @property integer $config_map_tree_levels
 */
class ConfigMap extends Model
{
	public $modelName='网络图谱配置';
	public $nameColumn='config_map_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConfigMap the static model class
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
		return '{{config_map}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('config_map_levels, config_map_branch, config_map_orientation, config_map_tree_levels', 'filter','filter'=>array($this,'empty2null')),
			array('config_map_levels, config_map_branch, config_map_tree_levels', 'numerical', 'integerOnly'=>true),
			array('config_map_levels, config_map_branch, config_map_orientation, config_map_tree_levels', 'required'),
			array('config_map_orientation', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('config_map_id, config_map_levels, config_map_branch, config_map_orientation, config_map_tree_levels', 'safe', 'on'=>'search'),
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
			'config_map_id' => 'Config Map',
			'config_map_levels' => t('epmms','组织结构图默认显示层数'),
			'config_map_branch' => t('epmms','组织结构图分支数'),
			'config_map_orientation' => t('epmms','组织结构图显示方向'),
			'config_map_tree_levels' => t('epmms','树状结构图默认显示层数'),
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
		$sort=new Sort('ConfigMap');
		$sort->defaultOrder=array('config_map_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('config_map_id',$this->config_map_id,true);
		$criteria->compare('config_map_levels',$this->config_map_levels);
		$criteria->compare('config_map_branch',$this->config_map_branch);
		$criteria->compare('config_map_orientation',$this->config_map_orientation,true);
		$criteria->compare('config_map_tree_levels',$this->config_map_tree_levels);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}