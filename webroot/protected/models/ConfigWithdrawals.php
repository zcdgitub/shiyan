<?php

/**
 * This is the model class for table "{{config_withdrawals}}".
 *
 * The followings are the available columns in table '{{config_withdrawals}}':
 * @property integer $config_withdrawals_id
 * @property integer $config_withdrawals_type
 * @property integer $config_withdrawals_scale
 * @property float $config_withdrawals_tax
 * @property float $config_withdrawals_tax_cap
 * @property float $config_withdrawals_min
 */
class ConfigWithdrawals extends Model
{
	//模型标题
	public $modelName='提现配置';
	//命名字段
	public $nameColumn='config_withdrawals_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{config_withdrawals}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('config_withdrawals_type, config_withdrawals_scale,config_withdrawals_tax_cap', 'filter','filter'=>array($this,'empty2null')),
			array('config_withdrawals_type, config_withdrawals_scale', 'numerical', 'integerOnly'=>true),
			array('config_withdrawals_tax ', 'ext.validators.Decimal','precision'=>3,'scale'=>2,'sign'=>0,'allowZero'=>true),
			array('config_withdrawals_tax ', 'numerical','max'=>0.99),
			array('config_withdrawals_tax_cap,config_withdrawals_min ', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'sign'=>0,'allowZero'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('config_withdrawals_id, config_withdrawals_type, config_withdrawals_scale', 'safe', 'on'=>'search'),
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
			'config_withdrawals_id' => t('epmms','Config Withdrawals'),
			'config_withdrawals_type' => t('epmms','手续费方式'),
			'config_withdrawals_scale' => t('epmms','提现倍数'),
			'config_withdrawals_tax' => t('epmms','提现手续费'),
			'config_withdrawals_tax_cap' => t('epmms','手续费封顶'),
			'config_withdrawals_min' => t('epmms','起提金额'),
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

		$sort=new Sort('ConfigWithdrawals');
		$sort->defaultOrder=array('config_withdrawals_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('config_withdrawals_id',$this->config_withdrawals_id);
		$criteria->compare('config_withdrawals_type',$this->config_withdrawals_type);
		$criteria->compare('config_withdrawals_scale',$this->config_withdrawals_scale);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConfigWithdrawals the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
