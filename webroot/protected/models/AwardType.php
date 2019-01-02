<?php

/**
 * This is the model class for table "{{award_type}}".
 *
 * The followings are the available columns in table '{{award_type}}':
 * @property string $award_type_id
 * @property string $award_type_name
 * @property string $award_type_desc
 * @property integer $award_type_is_enable
 * @property string $award_type_add_date
 * @property integer $award_type_sum_type
 * @property string $award_type_class
 * @property string $award_type_order
 *
 * The followings are the available model relations:
 * @property  * @property  */
class AwardType extends Model
{
	public $modelName='奖金类型';
	public $nameColumn='award_type_name';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardType the static model class
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
		return '{{award_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_type_desc, award_type_memo, award_type_add_date, award_type_sum_type, award_type_class, award_type_order', 'filter','filter'=>array($this,'empty2null')),
			array('award_type_id, award_type_name, award_type_is_enable', 'required'),
			array('award_type_is_enable, award_type_sum_type', 'numerical', 'integerOnly'=>true),
			array('award_type_name, award_type_class', 'length', 'max'=>50),
			array('award_type_add_date', 'length', 'max'=>3),
			array('award_type_id', 'unique'),
			array('award_type_desc,award_type_order', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_type_id, award_type_name, award_type_desc, award_type_is_enable, award_type_add_date, award_type_sum_type, award_type_class, award_type_order', 'safe', 'on'=>'search'),
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
			'awardPeriods' => array(Model::HAS_MANY, 'AwardPeriod', 'award_period_type_id'),
			'awardGroups' => array(Model::HAS_MANY, 'AwardGroup', 'award_group_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_type_id' => t('epmms','奖金类型'),
			'award_type_name' => t('epmms','奖金名称'),
			'award_type_desc' => 'Award Type Desc',
			'award_type_is_enable' => 'Award Type Is Enable',
			'award_type_add_date' => 'Award Type Add Date',
			'award_type_sum_type' => 'Award Type Sum Type',
			'award_type_class' => 'Award Type Class',
			'award_type_order' => 'Award Type Order',
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
		$sort=new Sort('AwardType');
		$sort->defaultOrder=array('award_type_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_type_id',$this->award_type_id,true);
		$criteria->compare('award_type_name',$this->award_type_name,true);
		$criteria->compare('award_type_desc',$this->award_type_desc,true);
		$criteria->compare('award_type_is_enable',$this->award_type_is_enable);
		$criteria->compare('award_type_add_date',$this->award_type_add_date,true);
		$criteria->compare('award_type_sum_type',$this->award_type_sum_type);
		$criteria->compare('award_type_class',$this->award_type_class,true);
		$criteria->compare('award_type_order',$this->award_type_order,true);
		//$criteria->with=array('awardPeriods');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
	public function getTypes($curSumType=null)
	{
	
		$cri=new CDbCriteria(['condition'=>'award_type_is_enable=1','order'=>'award_type_order asc']);
		if(!is_null($curSumType))
		{
			$cri->together=true;
			$cri->with=['awardGroups'];
			$cri->addColumnCondition(['award_group_group' => $curSumType]);
		}
		/*
		if(!user()->isAdmin())
		{
			if(user()->map->membermap_level==1)
			{
				$cri->addNotInCondition('award_type_id',[43,44]);
			}
			else if(user()->map->membermap_level==2)
			{
				$cri->addNotInCondition('award_type_id',[42]);
			}
			else
			{
				$cri->addNotInCondition('award_type_id',[42,43,44]);
			}
		}*/
		$awardType=$this->findAll($cri);
		return $awardType;
	}
	public function getFilterListData($condition = '')
	{
		return CHtml::listData($this->types,$this->nameColumn,$this->nameColumn);
	}
}