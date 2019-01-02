<?php

/**
 * This is the model class for table "{{award_month_sum}}".
 *
 * The followings are the available columns in table '{{award_month_sum}}':
 * @property string $award_month_sum_id
 * @property string $award_month_sum_memberinfo_id
 * @property string $award_month_sum_currency
 * @property string $award_month_sum_date
 * @property string $award_month_sum_add_date
 * @property string $award_month_sum_type
 * @property string $award_month_sum_is_withdrawals
 *
 * The followings are the available model relations:
 * @property  * @property  */
class AwardMonthSum extends Model
{
	public $modelName='奖金明细月统计及小计';
	public $nameColumn='award_month_sum_id';
	private $_withdrawals_tax;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardMonthSum the static model class
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
			$award=AwardMonth::model()->find(
				array('condition'=>"award_month_memberinfo_id=:member_id
				and award_month_date=:month_date
				and award_month_type_id=:type_id
				and award_month_sum_type=:sum_type",
					'params'=>[':member_id'=>$this->award_month_sum_memberinfo_id,':month_date'=>$this->award_month_sum_date,':type_id'=>$type_id,':sum_type'=>$this->award_month_sum_type])
			);
			if(is_object($award))
				return $award->award_month_currency;
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
			$this->_withdrawals_tax=-$this->award_month_sum_currency*$award_config_currency;
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
		return config('withdrawals','type')==1?$this->award_month_sum_currency+$this->withdrawalsTax:$this->award_month_sum_currency;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{award_month_sum}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_month_sum_add_date, award_month_sum_type', 'filter','filter'=>array($this,'empty2null')),
			array('award_month_sum_memberinfo_id, award_month_sum_currency, award_month_sum_date', 'required'),
			array('award_month_sum_memberinfo_id ', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('award_month_sum_type', 'exist',  'className'=>'SumType','attributeName'=>'sum_type_id'),
			array('award_month_sum_is_withdrawals','numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_month_sum_id, award_month_sum_memberinfo_id, award_month_sum_currency, award_month_sum_date, award_month_sum_add_date, award_month_sum_type', 'safe', 'on'=>'search'),
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
			'awardMonthSumMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_month_sum_memberinfo_id'),
			'awardMonthSumType' => array(Model::BELONGS_TO, 'SumType', 'award_month_sum_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_month_sum_id' => 'Award Month Sum',
			'award_month_sum_memberinfo_id' => t('epmms','会员账号'),
			'award_month_sum_currency' => t('epmms','小计'),
			'award_month_sum_date' => t('epmms','日期(月份第1天)'),
			'award_month_sum_add_date' => 'Award Month Sum Add Date',
			'award_month_sum_type' => t('epmms','汇总类型'),
			'award_month_sum_is_withdrawals' => t('epmms','是否已提现'),
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
		$sort=new Sort('AwardMonthSum');
		$sort->defaultOrder=array('award_month_sum_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_month_sum_id',$this->award_month_sum_id);
		$criteria->compare('award_month_sum_memberinfo_id',$this->award_month_sum_memberinfo_id);
		$criteria->compare('award_month_sum_currency',$this->award_month_sum_currency);
		$criteria->compare('award_month_sum_date',$this->award_month_sum_date);
		$criteria->compare('award_month_sum_add_date',$this->award_month_sum_add_date);
		$criteria->compare('award_month_sum_is_withdrawals',$this->award_month_sum_is_withdrawals);
		$criteria->compare('"awardMonthSumMemberinfo".memberinfo_account',@$this->awardMonthSumMemberinfo->memberinfo_account);
		$criteria->compare('"awardMonthSumType".sum_type_id',@$this->awardMonthSumType->sum_type_id);
		$criteria->with=array('awardMonthSumMemberinfo','awardMonthSumType');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}