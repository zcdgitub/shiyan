<?php

/**
 * This is the model class for table "{{award_month}}".
 *
 * The followings are the available columns in table '{{award_month}}':
 * @property string $award_month_id
 * @property string $award_month_date
 * @property string $award_month_memberinfo_id
 * @property string $award_month_currency
 * @property string $award_month_type_id
 * @property string $award_month_add_date
 *
 * The followings are the available model relations:
 * @property  */
class AwardMonth extends Model
{
	public $modelName='奖金明细月统计';
	public $nameColumn='award_month_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardMonth the static model class
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
		return '{{award_month}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_month_add_date', 'filter','filter'=>array($this,'empty2null')),
			array('award_month_date, award_month_memberinfo_id, award_month_currency, award_month_type_id', 'required'),
			array('award_month_add_date', 'length', 'max'=>3),
			array('award_month_date, award_month_memberinfo_id, award_month_type_id', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_month_id, award_month_date, award_month_memberinfo_id, award_month_currency, award_month_type_id, award_month_add_date', 'safe', 'on'=>'search'),
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
			'awardMonthMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_month_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_month_id' => 'Award Month',
			'award_month_date' => 'Award Month Date',
			'award_month_memberinfo_id' => 'Award Month Memberinfo',
			'award_month_currency' => 'Award Month Currency',
			'award_month_type_id' => 'Award Month Type',
			'award_month_add_date' => 'Award Month Add Date',
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
		$sort=new Sort('AwardMonth');
		$sort->defaultOrder=array('award_month_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_month_id',$this->award_month_id,true);
		$criteria->compare('award_month_date',$this->award_month_date,true);
		$criteria->compare('award_month_currency',$this->award_month_currency,true);
		$criteria->compare('award_month_type_id',$this->award_month_type_id,true);
		$criteria->compare('award_month_add_date',$this->award_month_add_date,true);
		$criteria->compare('awardMonthMemberinfo.memberinfo_account',@$this->awardMonthMemberinfo->memberinfo_account);
		$criteria->with=array('awardMonthMemberinfo');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}