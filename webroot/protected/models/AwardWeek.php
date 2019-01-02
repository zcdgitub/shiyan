<?php

/**
 * This is the model class for table "{{award_week}}".
 *
 * The followings are the available columns in table '{{award_week}}':
 * @property string $award_week_id
 * @property string $award_week_date
 * @property string $award_week_memberinfo_id
 * @property string $award_week_currency
 * @property string $award_week_type_id
 * @property string $award_week_add_date
 *
 * The followings are the available model relations:
 * @property  */
class AwardWeek extends Model
{
	public $modelName='奖金明细周统计';
	public $nameColumn='award_week_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardWeek the static model class
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
		return '{{award_week}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_week_add_date', 'filter','filter'=>array($this,'empty2null')),
			array('award_week_date, award_week_memberinfo_id, award_week_currency, award_week_type_id', 'required'),
			array('award_week_add_date', 'length', 'max'=>3),
			array('award_week_date, award_week_memberinfo_id, award_week_type_id', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_week_id, award_week_date, award_week_memberinfo_id, award_week_currency, award_week_type_id, award_week_add_date', 'safe', 'on'=>'search'),
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
			'awardWeekMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_week_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_week_id' => 'Award Week',
			'award_week_date' => 'Award Week Date',
			'award_week_memberinfo_id' => 'Award Week Memberinfo',
			'award_week_currency' => 'Award Week Currency',
			'award_week_type_id' => 'Award Week Type',
			'award_week_add_date' => 'Award Week Add Date',
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
		$sort=new Sort('AwardWeek');
		$sort->defaultOrder=array('award_week_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_week_id',$this->award_week_id,true);
		$criteria->compare('award_week_date',$this->award_week_date,true);
		$criteria->compare('award_week_currency',$this->award_week_currency,true);
		$criteria->compare('award_week_type_id',$this->award_week_type_id,true);
		$criteria->compare('award_week_add_date',$this->award_week_add_date,true);
		$criteria->compare('awardWeekMemberinfo.memberinfo_account',@$this->awardWeekMemberinfo->memberinfo_account);
		$criteria->with=array('awardWeekMemberinfo');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}