<?php

/**
 * This is the model class for table "{{config_jackpot}}".
 *
 * The followings are the available columns in table '{{config_jackpot}}':
 * @property integer $config_jackpot_id
 * @property string $config_jackpot_start_balance
 * @property string $config_jackpot_lucky_balance
 * @property string $config_jackpot_end_balance
 * @property string $config_jackpot_fund
 * @property integer $config_jackpot_start_order_ratio
 * @property integer $config_jackpot_lucky_order_ratio
 * @property integer $config_jackpot_end_order_ratio
 * @property integer $config_jackpot_start_time
 * @property integer $config_jackpot_end_time
 * @property integer $config_jackpot_number
 * @property integer $config_jackpot_addmember_money
 */
class ConfigJackpot extends Model
{
	//模型标题
	public $modelName='竞买抽奖奖池配置';
	//命名字段
	public $nameColumn='config_jackpot_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{config_jackpot}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('config_jackpot_start_balance, config_jackpot_lucky_balance, config_jackpot_end_balance, config_jackpot_start_order_ratio, config_jackpot_lucky_order_ratio, config_jackpot_end_order_ratio', 'filter','filter'=>array($this,'empty2null')),
			array('config_jackpot_fund, config_jackpot_start_time,config_jackpot_start_order_ratio, config_jackpot_lucky_order_ratio, config_jackpot_end_order_ratio,config_jackpot_addmember_money', 'required'),

//			array('config_jackpot_start_order_ratio, config_jackpot_lucky_order_ratio, config_jackpot_end_order_ratio', 'numerical', 'integerOnly'=>true),

			array('config_jackpot_start_balance, config_jackpot_lucky_balance, config_jackpot_end_balance, config_jackpot_fund ,config_jackpot_start_order_ratio, config_jackpot_lucky_order_ratio, config_jackpot_end_order_ratio,config_jackpot_addmember_money', 'ext.validators.Decimal','precision'=>16,'scale'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('config_jackpot_id, config_jackpot_start_balance, config_jackpot_lucky_balance, config_jackpot_end_balance, config_jackpot_fund, config_jackpot_start_order_ratio, config_jackpot_lucky_order_ratio, config_jackpot_end_order_ratio, config_jackpot_start_time, config_jackpot_end_time,config_jackpot_addmember_money', 'safe', 'on'=>'search'),
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
			'config_jackpot_id' => t('epmms','主键id'),
			'config_jackpot_start_balance' => t('epmms','首单奖池金额'),
			'config_jackpot_lucky_balance' => t('epmms','幸运奖池金额'),
			'config_jackpot_end_balance' => t('epmms','尾单奖池金额'),
			'config_jackpot_fund' => t('epmms','每轮开始拨款金额'),
			'config_jackpot_start_order_ratio' => t('epmms','首单比例'),
			'config_jackpot_lucky_order_ratio' => t('epmms','幸运比例'),
			'config_jackpot_end_order_ratio' => t('epmms','尾单比例'),
			'config_jackpot_start_time' => t('epmms','开始时间'),
			'config_jackpot_end_time' => t('epmms','结束时间'),
			'config_jackpot_jackpot' => t('epmms','期数'),
			'config_jackpot_addmember_money' => t('epmms','激活金卡拨款金钱'),
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

		$sort=new Sort('ConfigJackpot');
		$sort->defaultOrder=array('config_jackpot_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('config_jackpot_id',$this->config_jackpot_id);
		$criteria->compare('config_jackpot_start_balance',$this->config_jackpot_start_balance,true);
		$criteria->compare('config_jackpot_lucky_balance',$this->config_jackpot_lucky_balance,true);
		$criteria->compare('config_jackpot_end_balance',$this->config_jackpot_end_balance,true);
		$criteria->compare('config_jackpot_fund',$this->config_jackpot_fund,true);
		$criteria->compare('config_jackpot_start_order_ratio',$this->config_jackpot_start_order_ratio);
		$criteria->compare('config_jackpot_lucky_order_ratio',$this->config_jackpot_lucky_order_ratio);
		$criteria->compare('config_jackpot_end_order_ratio',$this->config_jackpot_end_order_ratio);
		$criteria->compare('config_jackpot_start_time',$this->config_jackpot_start_time);
		$criteria->compare('config_jackpot_end_time',$this->config_jackpot_end_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConfigJackpot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 激活金卡调用，增加奖池余额以及本期活动结束时间
     */
	public function updateJackpot(){
        // 更改配置文件
        $model     = (new ConfigJackpot())->findByPk(1);
        $fundMoney =  $model->config_jackpot_addmember_money;
//        $model->config_jackpot_fund     -= $fundMoney;  // 更改发展基金
        $model->config_jackpot_end_time += 60;          // 结束时间推迟一分钟
        $model->config_jackpot_start_balance += $fundMoney *  $model->config_jackpot_start_order_ratio/100;  // 首单奖池
        $model->config_jackpot_end_balance   += $fundMoney * $model->config_jackpot_end_order_ratio/100;     // 尾单奖池
        $model->config_jackpot_lucky_balance += $fundMoney * $model->config_jackpot_lucky_order_ratio/100;   // 幸运奖池
        return $model->save();
    }
}
