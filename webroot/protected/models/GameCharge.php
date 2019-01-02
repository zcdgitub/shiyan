<?php

/**
 * This is the model class for table "{{game_charge}}".
 *
 * The followings are the available columns in table '{{game_charge}}':
 * @property integer $charge_id
 * @property integer $charge_recommend
 * @property string $charge_name
 * @property string $charge_phone
 * @property integer $charge_age
 * @property string $charge_account
 * @property string $charge_money
 * @property string $charge_add_date
 * @property integer $charge_is_verify
 * @property string $charge_verify_date
 *
 * The followings are the available model relations:
 * @property  */
class GameCharge extends Model
{
	//模型标题
	public $modelName='游戏充值';
	//命名字段
	public $nameColumn='charge_account';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{game_charge}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('charge_phone, charge_age, charge_is_verify', 'filter','filter'=>array($this,'empty2null')),
			array('charge_recommend, charge_name, charge_account, charge_money', 'required'),
			array('charge_recommend, charge_age, charge_is_verify', 'numerical', 'integerOnly'=>true),
			array('charge_recommend', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('charge_phone', 'ext.validators.Phone'),
			array('charge_name', 'ext.validators.Account','allowZh'=>true),
			array('charge_account', 'ext.validators.Account','allowZh'=>false),
			array('charge_money', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('charge_id, charge_recommend, charge_name, charge_phone, charge_age, charge_account, charge_money, charge_add_date, charge_is_verify, charge_verify_date', 'safe', 'on'=>'search'),
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
			'chargeRecommend' => array(Model::BELONGS_TO, 'Memberinfo', 'charge_recommend'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'charge_id' => t('epmms','Charge'),
			'charge_recommend' => t('epmms','推荐人'),
			'charge_name' => t('epmms','姓名'),
			'charge_phone' => t('epmms','电话'),
			'charge_age' => t('epmms','年龄'),
			'charge_account' => t('epmms','游戏账号'),
			'charge_money' => t('epmms','充值金额'),
			'charge_add_date' => t('epmms','日期'),
			'charge_is_verify' => t('epmms','审核状态'),
			'charge_verify_date' => t('epmms','审核日期'),
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

		$sort=new Sort('GameCharge');
		$sort->defaultOrder=array('charge_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('charge_id',$this->charge_id);
		$criteria->compare('charge_recommend',$this->charge_recommend);
		$criteria->compare('charge_name',$this->charge_name,true);
		$criteria->compare('charge_phone',$this->charge_phone,true);
		$criteria->compare('charge_age',$this->charge_age);
		$criteria->compare('charge_account',$this->charge_account,true);
		$criteria->compare('charge_money',$this->charge_money,true);
		$criteria->compare('charge_add_date',$this->charge_add_date,true);
		$criteria->compare('charge_is_verify',$this->charge_is_verify);
		$criteria->compare('charge_verify_date',$this->charge_verify_date,true);
		$criteria->compare('"chargeRecommend".memberinfo_account',@$this->chargeRecommend->memberinfo_account);
		$criteria->with=array('chargeRecommend');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GameCharge the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
