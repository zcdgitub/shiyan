<?php

/**
 * This is the model class for table "{{signing}}".
 *
 * The followings are the available columns in table '{{signing}}':
 * @property integer $signing_id
 * @property integer $signing_member_id
 * @property integer $signing_is_verify
 * @property string $signing_date
 * @property integer $signing_is_refund
 * @property string $signing_verify_date
 * @property integer $signing_type
 *
 * The followings are the available model relations:
 * @property  */
class Signing extends Model
{
	//模型标题
	public $modelName='签约管理';
	//命名字段
	public $nameColumn='signing_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{signing}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('signing_is_verify, signing_date, signing_is_refund, signing_verify_date, signing_type', 'filter','filter'=>array($this,'empty2null')),
			array('signing_date', 'required','on'=>'verify'),
			array('signing_date','ext.validators.AbleSigning','on'=>'verify'),
			array('signing_member_id, signing_is_verify, signing_is_refund, signing_type', 'numerical', 'integerOnly'=>true),
			array('signing_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('signing_date, signing_verify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('signing_id, signing_member_id, signing_is_verify, signing_date, signing_is_refund, signing_verify_date, signing_type', 'safe', 'on'=>'search'),
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
			'signingMember' => array(Model::BELONGS_TO, 'Memberinfo', 'signing_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'signing_id' => t('epmms','Signing'),
			'signing_member_id' => t('epmms','会员'),
			'signing_is_verify' => t('epmms','签约状态'),
			'signing_date' => t('epmms','签约时间'),
			'signing_is_refund' => t('epmms','是否已退款'),
			'signing_verify_date' => t('epmms','签约时间'),
			'signing_type' => t('epmms','签约类型'),
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

		$sort=new Sort('Signing');
		$sort->defaultOrder=array('signing_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('signing_id',$this->signing_id);
		$criteria->compare('signing_member_id',$this->signing_member_id);
		$criteria->compare('signing_is_verify',$this->signing_is_verify);
		$criteria->compare('signing_date',$this->signing_date,true);
		$criteria->compare('signing_is_refund',$this->signing_is_refund);
		$criteria->compare('signing_verify_date',$this->signing_verify_date,true);
		$criteria->compare('signing_type',$this->signing_type);
		$criteria->compare('"signingMember".memberinfo_account',@$this->signingMember->memberinfo_account);
		$criteria->with=array('signingMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Signing the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
