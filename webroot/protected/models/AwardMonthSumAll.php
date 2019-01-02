<?php

/**
 * This is the model class for table "{{award_month_sum_all}}".
 *
 * The followings are the available columns in table '{{award_month_sum_all}}':
 * @property integer $award_month_sum_id
 * @property integer $award_month_sum_memberinfo_id
 * @property string $award_month_sum_currency
 * @property string $award_month_sum_date
 * @property string $award_month_sum_add_date
 * @property integer $award_month_sum_is_withdrawals
 *
 * The followings are the available model relations:
 * @property  */
class AwardMonthSumAll extends Model
{
	//模型标题
	public $modelName='月工资';
	//命名字段
	public $nameColumn='award_month_sum_id';
	private $_withdrawals_tax;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{award_month_sum_all}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_month_sum_add_date, award_month_sum_is_withdrawals', 'filter','filter'=>array($this,'empty2null')),
			array('award_month_sum_memberinfo_id, award_month_sum_currency, award_month_sum_date', 'required'),
			array('award_month_sum_memberinfo_id, award_month_sum_is_withdrawals', 'numerical', 'integerOnly'=>true),
			array('award_month_sum_memberinfo_id, award_month_sum_date', 'unique'),
			array('award_month_sum_memberinfo_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('award_month_sum_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('award_month_sum_add_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('award_month_sum_id, award_month_sum_memberinfo_id, award_month_sum_currency, award_month_sum_date, award_month_sum_add_date, award_month_sum_is_withdrawals', 'safe', 'on'=>'search'),
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
			'award_month_sum_is_withdrawals' => t('epmms','是否已提现'),
			'withdrawalsTax' => t('epmms','手续费'),
			'realCurrency' => t('epmms','实发'),
		);
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function payoff()
	{
		$model=new Withdrawals('create');
		$model->withdrawals_currency=$this->award_month_sum_currency;

		//$sumtype-id为null,为第1个财务类型
		$finance=Finance::getFinanceSumType($this->award_month_sum_memberinfo_id);
		$model->withdrawals_member_id=$finance->finance_memberinfo_id;
		$model->withdrawalsMember=Memberinfo::model()->findByPk($finance->finance_memberinfo_id);
		$model->withdrawals_finance_type_id=$finance->finance_type;
		$model->withdrawalsFinanceType=FinanceType::model()->findByPk($finance->finance_type);
		$model->withdrawals_currency=$this->award_month_sum_currency;

		$fee_config=config('withdrawals','tax');
		$award_config_currency=abs($fee_config);
		$model->withdrawals_tax=-$model->withdrawals_currency*$award_config_currency;
		//提现封顶
		$tax_cap=config('withdrawals','tax_cap');
		if(!empty($tax_cap))
		{
			if(abs($model->withdrawals_tax)>abs($tax_cap))
				$model->withdrawals_tax=-abs($tax_cap);
		}
		if(config('withdrawals','type')==1)
			$model->withdrawals_real_currency=$model->withdrawals_currency-abs($model->withdrawals_tax);
		else
			$model->withdrawals_real_currency=$model->withdrawals_currency+abs($model->withdrawals_tax);
		if($model->deduct_money>$finance->finance_award)
		{
			return false;
		}
		$transaction=webapp()->db->beginTransaction();
		if($model->save(false,array('withdrawals_member_id','withdrawals_currency',
				'withdrawals_add_date','withdrawals_is_verify',
				'withdrawals_verify_date','withdrawals_remark',
				'withdrawals_finance_type_id','withdrawals_sn',
				'withdrawals_tax','withdrawals_real_currency')) && $model->verify() && $model->send()
		)
		{
			$this->award_month_sum_is_withdrawals=1;
			$this->saveAttributes(['award_month_sum_is_withdrawals']);
			$transaction->commit();
			return true;
		}
		else
		{
			$transaction->rollback();
			return false;
		}
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

		$sort=new Sort('AwardMonthSumAll');
		$sort->defaultOrder=array('award_month_sum_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('award_month_sum_id',$this->award_month_sum_id);
		$criteria->compare('award_month_sum_memberinfo_id',$this->award_month_sum_memberinfo_id);
		$criteria->compare('award_month_sum_currency',$this->award_month_sum_currency,true);
		$criteria->compare('award_month_sum_date',$this->award_month_sum_date,true);
		$criteria->compare('award_month_sum_add_date',$this->award_month_sum_add_date,true);
		$criteria->compare('award_month_sum_is_withdrawals',$this->award_month_sum_is_withdrawals);
		$criteria->compare('"awardMonthSumMemberinfo".memberinfo_account',@$this->awardMonthSumMemberinfo->memberinfo_account);
		$criteria->with=array('awardMonthSumMemberinfo');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AwardMonthSumAll the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
