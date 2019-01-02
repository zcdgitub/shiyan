<?php

/**
 * This is the model class for table "{{award_week_sum}}".
 *
 * The followings are the available columns in table '{{award_week_sum}}':
 * @property string $award_week_sum_id
 * @property string $award_week_sum_memberinfo_id
 * @property string $award_week_sum_currency
 * @property string $award_week_sum_date
 * @property string $award_week_sum_add_date
 * @property string $award_week_sum_type
 * @property string $award_week_sum_is_withdrawals
 *
 * The followings are the available model relations:
 * @property  */
class AwardWeekSum extends Model
{
	public $modelName='奖金明细周统计及小计';
	public $nameColumn='award_week_sum_id';
	private $_withdrawals_tax;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardWeekSum the static model class
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
			$award=AwardWeek::model()->find(
				array('condition'=>"award_week_memberinfo_id=:member_id
				and award_week_date=:week_date
				and award_week_type_id=:type_id
				and award_week_sum_type=:sum_type",
					'params'=>[':member_id'=>$this->award_week_sum_memberinfo_id,':week_date'=>$this->award_week_sum_date,':type_id'=>$type_id,':sum_type'=>$this->award_week_sum_type])
			);
			if(is_object($award))
				return $award->award_week_currency;
			else
				return '0.00';
		}
		return parent::__get($name);
	}
	public function getWithdrawalsTax()
	{
		if(is_null($this->_withdrawals_tax))
		{
			$fee_config=config('withdrawals','tax');
			$award_config_currency=abs($fee_config);
			$this->_withdrawals_tax=-$this->award_week_sum_currency*$award_config_currency;
			//提现封顶
			$tax_cap=config('withdrawals','tax_cap');
			if(!empty($tax_cap))
			{
				if(abs($this->_withdrawals_tax)>abs($tax_cap))
					$this->_withdrawals_tax=-abs($tax_cap);
			}
		}
		return $this->_withdrawals_tax;
	}
	public function getRealCurrency()
	{
		return config('withdrawals','type')==1?$this->award_week_sum_currency+$this->withdrawalsTax:$this->award_week_sum_currency;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{award_week_sum}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_week_sum_add_date, award_week_sum_type', 'filter','filter'=>array($this,'empty2null')),
			array('award_week_sum_memberinfo_id, award_week_sum_currency, award_week_sum_date', 'required'),
			//array('award_week_sum_memberinfo_id, award_week_sum_date, award_week_sum_type', 'unique'),
			array('award_week_sum_memberinfo_id ', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('award_week_sum_type', 'exist', 'className'=>'SumType','attributeName'=>'sum_type_id'),
			array('award_week_sum_is_withdrawals','numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_week_sum_id, award_week_sum_memberinfo_id, award_week_sum_currency, award_week_sum_date, award_week_sum_add_date, award_week_sum_type', 'safe', 'on'=>'search'),
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
			'awardWeekSumMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_week_sum_memberinfo_id'),
			'awardWeekSumType' => array(Model::BELONGS_TO, 'SumType', 'award_week_sum_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_week_sum_id' => 'Award Week Sum',
			'award_week_sum_memberinfo_id' => t('epmms','会员账号'),
			'award_week_sum_currency' => t('epmms','小计'),
			'award_week_sum_date' => t('epmms','日期'),
			'award_week_sum_add_date' => 'Award Week Sum Add Date',
			'award_week_sum_type' => t('epmms','汇总类型'),
			'award_week_sum_is_withdrawals' => t('epmms','是否已提现'),
			'withdrawalsTax' => t('epmms','手续费'),
			'realCurrency' => t('epmms','实发'),
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
		$sort=new Sort('AwardWeekSum');
		$sort->defaultOrder=array('award_week_sum_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_week_sum_id',$this->award_week_sum_id);
		$criteria->compare('award_week_sum_memberinfo_id',$this->award_week_sum_memberinfo_id);

		$criteria->compare('award_week_sum_currency',$this->award_week_sum_currency);
		$criteria->compare('award_week_sum_date',$this->award_week_sum_date);
		$criteria->compare('award_week_sum_add_date',$this->award_week_sum_add_date);
		$criteria->compare('award_week_sum_is_withdrawals',$this->award_week_sum_is_withdrawals);
		$criteria->compare('award_week_sum_type',$this->award_week_sum_type);
		$criteria->compare('"awardWeekSumMemberinfo".memberinfo_account',@$this->awardWeekSumMemberinfo->memberinfo_account);
		$criteria->compare('"awardWeekSumType".sum_type_id',@$this->awardWeekSumType->sum_type_id);
		$criteria->with=array('awardWeekSumMemberinfo','awardWeekSumType');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}