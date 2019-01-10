<?php

/**
 * This is the model class for table "{{jackpot_info}}".
 *
 * The followings are the available columns in table '{{jackpot_info}}':
 * @property integer $info_id
 * @property string $info_start_time
 * @property string $info_end_time
 * @property string $info_start_balance
 * @property string $info_lucky_balance
 * @property string $info_end_balance
 * @property integer $info_number
 */
class JackpotInfo extends Model
{
	//模型标题
	public $modelName='奖池信息记录';
	//命名字段
	public $nameColumn='info_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{jackpot_info}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('info_start_balance, info_lucky_balance, info_end_balance', 'filter','filter'=>array($this,'empty2null')),
			array('info_start_time, info_end_time, info_number', 'required'),
			array('info_number', 'numerical', 'integerOnly'=>true),
			array('info_start_balance, info_lucky_balance, info_end_balance', 'ext.validators.Decimal','precision'=>16,'scale'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('info_id, info_start_time, info_end_time, info_start_balance, info_lucky_balance, info_end_balance, info_number', 'safe', 'on'=>'search'),
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
			'info_id' => t('epmms','主键id'),
			'info_start_time' => t('epmms','开始时间'),
			'info_end_time' => t('epmms','结束时间'),
			'info_start_balance' => t('epmms','首单奖池金额'),
			'info_lucky_balance' => t('epmms','幸运奖池金额'),
			'info_end_balance' => t('epmms','尾单奖池金额'),
			'info_number' => t('epmms','期数'),
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

		$sort=new Sort('JackpotInfo');
		$sort->defaultOrder=array('info_number'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;

		$criteria->compare('info_id',$this->info_id);
		$criteria->compare('info_start_time',$this->info_start_time,false);
		$criteria->compare('info_end_time',$this->info_end_time,false);
		$criteria->compare('info_start_balance',$this->info_start_balance,true);
		$criteria->compare('info_lucky_balance',$this->info_lucky_balance,true);
		$criteria->compare('info_end_balance',$this->info_end_balance,true);
		$criteria->compare('info_number',$this->info_number);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return JackpotInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
