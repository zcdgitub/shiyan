<?php

/**
 * This is the model class for table "{{system_status}}".
 *
 * The followings are the available columns in table '{{system_status}}':
 * @property integer $system_status_id
 * @property string $system_status_expenses
 * @property string $system_status_income
 * @property string $system_status_last_verify
 * @property string $system_status_start_date
 * @property string $system_status_withdrawals
 */
class SystemStatus extends Model
{
	public $modelName='系统状态';
	public $nameColumn='system_status_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SystemStatus the static model class
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
		return '{{system_status}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('system_status_expenses, system_status_income, system_status_last_verify, system_status_start_date, system_status_withdrawals', 'filter','filter'=>array($this,'empty2null')),
			array('system_status_expenses, system_status_income, system_status_withdrawals', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('system_status_last_verify, system_status_start_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('system_status_id, system_status_expenses, system_status_income, system_status_last_verify, system_status_start_date, system_status_withdrawals', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'system_status_id' => 'System Status',
			'system_status_expenses' =>t('epmms', '总支出'),
			'system_status_income' =>t('epmms', '总收入'),
			'system_status_last_verify' => t('epmms','最后审核日期'),
			'system_status_start_date' =>t('epmms', '系统启动日期'),
			'system_status_withdrawals' => t('epmms','提现金额'),
			'system_status_foundation' =>t('epmms', '信用基金'),
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
		$sort=new Sort('SystemStatus');
		$sort->defaultOrder=array('system_status_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('system_status_id',$this->system_status_id);
		$criteria->compare('system_status_expenses',$this->system_status_expenses,false);
		$criteria->compare('system_status_income',$this->system_status_income,false);
		$criteria->compare('system_status_last_verify',$this->system_status_last_verify,false);
		$criteria->compare('system_status_start_date',$this->system_status_start_date,false);
		$criteria->compare('system_status_withdrawals',$this->system_status_withdrawals,false);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
	public function getRatio()
	{
		return $this->system_status_income==0?'':webapp()->format->formatPercentage($this->system_status_expenses/$this->system_status_income);
	}
}