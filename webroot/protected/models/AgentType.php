<?php

/**
 * This is the model class for table "{{agent_type}}".
 *
 * The followings are the available columns in table '{{agent_type}}':
 * @property integer $agent_type_level
 * @property string $agent_type_name
 *
 * The followings are the available model relations:
 * @property  * @property  */
class AgentType extends Model
{
	//模型标题
	public $modelName='代理中心级名';
	//命名字段
	public $nameColumn='agent_type_name';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{agent_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agent_type_level, agent_type_name', 'required'),
			array('agent_type_level', 'numerical', 'integerOnly'=>true),
			array('agent_type_name', 'length', 'max'=>20),
			array('agent_type_level', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('agent_type_level, agent_type_name', 'safe', 'on'=>'search'),
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
			'agents' => array(Model::HAS_MANY, 'Agent', 'agent_type'),
			'membermaps' => array(Model::HAS_MANY, 'Membermap', 'membermap_agent_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'agent_type_level' => t('epmms','代理中心类型'),
			'agent_type_name' => t('epmms','代理中心类型名'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$sort=new Sort('AgentType');
		$sort->defaultOrder=array('agent_type_level'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('agent_type_level',$this->agent_type_level);
		$criteria->compare('agent_type_name',$this->agent_type_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getListData($condition='')
	{
		return CHtml::listData($this->findAll(['order'=>'agent_type_level']),$this->tableSchema->primaryKey,$this->nameColumn);
	}
}
