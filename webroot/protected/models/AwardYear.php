<?php

/**
 * This is the model class for table "{{award_year}}".
 *
 * The followings are the available columns in table '{{award_year}}':
 * @property integer $award_year_id
 * @property string $award_year_date
 * @property integer $award_year_memberinfo_id
 * @property string $award_year_currency
 * @property integer $award_year_type_id
 * @property string $award_year_add_date
 *
 * The followings are the available model relations:
 * @property  */
class AwardYear extends Model
{
	public $modelName='奖金年明细统计';
	public $nameColumn='award_year_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardYear the static model class
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
		return '{{award_year}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_year_currency, award_year_add_date', 'filter','filter'=>array($this,'empty2null')),
			array('award_year_date, award_year_memberinfo_id, award_year_type_id', 'required'),
			array('award_year_memberinfo_id, award_year_type_id', 'numerical', 'integerOnly'=>true),
			array('award_year_memberinfo_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('award_year_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('award_year_add_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_year_id, award_year_date, award_year_memberinfo_id, award_year_currency, award_year_type_id, award_year_add_date', 'safe', 'on'=>'search'),
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
			'awardYearMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_year_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_year_id' => 'Award Year',
			'award_year_date' => 'Award Year Date',
			'award_year_memberinfo_id' => 'Award Year Memberinfo',
			'award_year_currency' => 'Award Year Currency',
			'award_year_type_id' => 'Award Year Type',
			'award_year_add_date' => 'Award Year Add Date',
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
		$sort=new Sort('AwardYear');
		$sort->defaultOrder=array('award_year_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_year_id',$this->award_year_id);
		$criteria->compare('award_year_date',$this->award_year_date,false);
		$criteria->compare('award_year_currency',$this->award_year_currency,false);
		$criteria->compare('award_year_type_id',$this->award_year_type_id);
		$criteria->compare('award_year_add_date',$this->award_year_add_date,false);
		$criteria->compare('"awardYearMemberinfo".memberinfo_account',@$this->awardYearMemberinfo->memberinfo_account);
		$criteria->with=array('awardYearMemberinfo');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}