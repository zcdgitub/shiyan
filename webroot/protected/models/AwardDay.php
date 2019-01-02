<?php

/**
 * This is the model class for table "{{award_day}}".
 *
 * The followings are the available columns in table '{{award_day}}':
 * @property string $award_day_id
 * @property string $award_day_date
 * @property string $award_day_memberinfo_id
 * @property string $award_day_currency
 * @property string $award_day_type_id
 * @property string $award_day_add_date
 *
 * The followings are the available model relations:
 * @property  */
class AwardDay extends Model
{
	public $modelName='奖金明细日统计';
	public $nameColumn='award_day_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardDay the static model class
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
		return '{{award_day}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_day_add_date', 'filter','filter'=>array($this,'empty2null')),
			array('award_day_date, award_day_memberinfo_id, award_day_currency, award_day_type_id', 'required'),
			array('award_day_add_date', 'length', 'max'=>3),
			array('award_day_date, award_day_memberinfo_id, award_day_type_id', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_day_id, award_day_date, award_day_memberinfo_id, award_day_currency, award_day_type_id, award_day_add_date', 'safe', 'on'=>'search'),
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
			'awardDayMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_day_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_day_id' => 'Award Day',
			'award_day_date' => 'Award Day Date',
			'award_day_memberinfo_id' => 'Award Day Memberinfo',
			'award_day_currency' => 'Award Day Currency',
			'award_day_type_id' => 'Award Day Type',
			'award_day_add_date' => 'Award Day Add Date',
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
		$sort=new Sort('AwardDay');
		$sort->defaultOrder=array('award_day_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_day_id',$this->award_day_id,true);
		$criteria->compare('award_day_date',$this->award_day_date,true);
		$criteria->compare('award_day_currency',$this->award_day_currency,true);
		$criteria->compare('award_day_type_id',$this->award_day_type_id,true);
		$criteria->compare('award_day_add_date',$this->award_day_add_date,true);
		$criteria->compare('awardDayMemberinfo.memberinfo_account',@$this->awardDayMemberinfo->memberinfo_account);
		$criteria->with=array('awardDayMemberinfo');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}