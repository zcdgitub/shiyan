<?php

/**
 * This is the model class for table "{{award_year_sum}}".
 *
 * The followings are the available columns in table '{{award_year_sum}}':
 * @property integer $award_year_sum_id
 * @property integer $award_year_sum_memberinfo_id
 * @property string $award_year_sum_currency
 * @property string $award_year_sum_date
 * @property string $award_year_sum_add_date
 * @property integer $award_year_sum_type
 *
 * The followings are the available model relations:
 * @property  * @property  */
class AwardYearSum extends Model
{
	public $modelName='奖金明细年统计及小计';
	public $nameColumn='award_year_sum_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardYearSum the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 设置各奖金字段的属性
	 * @param string $name
	 * @return mixed|null
	 */
	public function __get($name)
	{
		if(strncmp('_award_',$name,7)==0)
		{
			if($this->isNewRecord)
				return '0.00';
			$type_id=(int)substr($name,7);
			$award=Awardyear::model()->find(
				array('condition'=>"award_year_memberinfo_id=:member_id
				and award_year_date=:year_date
				and award_year_type_id=:type_id
				and award_year_sum_type=:sum_type",
					'params'=>[':member_id'=>$this->award_year_sum_memberinfo_id,':year_date'=>$this->award_year_sum_date,':type_id'=>$type_id,':sum_type'=>$this->award_year_sum_type])
			);
			if(is_object($award))
				return $award->award_year_currency;
			else
				return '0.00';
		}
		return parent::__get($name);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{award_year_sum}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_year_sum_currency, award_year_sum_add_date', 'filter','filter'=>array($this,'empty2null')),
			array('award_year_sum_memberinfo_id, award_year_sum_date, award_year_sum_type', 'required'),
			array('award_year_sum_memberinfo_id, award_year_sum_type', 'numerical', 'integerOnly'=>true),
			array('award_year_sum_memberinfo_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('award_year_sum_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('award_year_sum_add_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_year_sum_id, award_year_sum_memberinfo_id, award_year_sum_currency, award_year_sum_date, award_year_sum_add_date, award_year_sum_type', 'safe', 'on'=>'search'),
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
			'awardYearSumMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_year_sum_memberinfo_id'),
			'awardYearSumType' => array(Model::BELONGS_TO, 'SumType', 'award_year_sum_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_year_sum_id' => 'Award Year Sum',
			'award_year_sum_memberinfo_id' => t('epmms','会员账号'),
			'award_year_sum_currency' => t('epmms','小计'),
			'award_year_sum_date' => t('epmms','日期(一月一日)'),
			'award_year_sum_add_date' => t('epmms','汇总日期'),
			'award_year_sum_type' => t('epmms','汇总类型'),
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
		$sort=new Sort('AwardYearSum');
		$sort->defaultOrder=array('award_year_sum_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_year_sum_id',$this->award_year_sum_id);
		$criteria->compare('award_year_sum_memberinfo_id',$this->award_year_sum_memberinfo_id);
		$criteria->compare('award_year_sum_memberinfo_id',$this->award_year_sum_memberinfo_id);
		$criteria->compare('award_year_sum_currency',$this->award_year_sum_currency,false);
		$criteria->compare('award_year_sum_date',$this->award_year_sum_date,false);
		$criteria->compare('award_year_sum_add_date',$this->award_year_sum_add_date,false);
		$criteria->compare('"awardYearSumMemberinfo".memberinfo_account',@$this->awardYearSumMemberinfo->memberinfo_account);
		$criteria->compare('"awardYearSumType".sum_type_name',@$this->awardYearSumType->sum_type_name);
		$criteria->with=array('awardYearSumMemberinfo','awardYearSumType');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}