<?php

/**
 * This is the model class for table "{{award_group}}".
 *
 * The followings are the available columns in table '{{award_group}}':
 * @property integer $award_group_id
 * @property integer $award_group_group
 * @property integer $award_group_type
 *
 * The followings are the available model relations:
 * @property  * @property  */
class AwardGroup extends Model
{
	//模型标题
	public $modelName='奖金组';
	//命名字段
	public $nameColumn='award_group_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{award_group}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_group_group', 'filter','filter'=>array($this,'empty2null')),
			array('award_group_type', 'required'),
			array('award_group_group, award_group_type', 'numerical', 'integerOnly'=>true),
			array('award_group_group', 'exist', 'className'=>'SumType','attributeName'=>'sum_type_id'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('award_group_id, award_group_group, award_group_type', 'safe', 'on'=>'search'),
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
			'awardGroupGroup' => array(Model::BELONGS_TO, 'SumType', 'award_group_group'),
			'awardGroupType' => array(Model::BELONGS_TO, 'AwardType', 'award_group_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_group_id' => t('epmms','Award Group'),
			'award_group_group' => t('epmms','Award Group Group'),
			'award_group_type' => t('epmms','Award Group Type'),
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

		$sort=new Sort('AwardGroup');
		$sort->defaultOrder=array('award_group_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('award_group_id',$this->award_group_id);
		$criteria->compare('award_group_group',$this->award_group_group);
		$criteria->compare('award_group_type',$this->award_group_type);
		$criteria->compare('"awardGroupGroup".sum_type_name',@$this->awardGroupGroup->sum_type_name);
		$criteria->compare('"awardGroupType".award_type_name',@$this->awardGroupType->award_type_name);
		$criteria->with=array('awardGroupGroup','awardGroupType');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AwardGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
