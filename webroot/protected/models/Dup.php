<?php

/**
 * This is the model class for table "{{dup}}".
 *
 * The followings are the available columns in table '{{dup}}':
 * @property integer $dup_id
 * @property integer $dup_member_id
 * @property string $dup_money
 * @property integer $dup_is_verify
 * @property string $dup_add_date
 * @property string $dup_verify_date
 *
 * The followings are the available model relations:
 * @property  */
class Dup extends Model
{
	//模型标题
	public $modelName='重消记录';
	//命名字段
	public $nameColumn='dup_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{dup}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dup_is_verify, dup_verify_date', 'filter','filter'=>array($this,'empty2null')),
			array('dup_member_id, dup_money', 'required'),
			array('dup_member_id, dup_is_verify', 'numerical', 'integerOnly'=>true),
			array('dup_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('dup_money', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('dup_verify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('dup_id, dup_member_id, dup_money, dup_is_verify, dup_add_date, dup_verify_date', 'safe', 'on'=>'search'),
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
			'dupMember' => array(Model::BELONGS_TO, 'Memberinfo', 'dup_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'dup_id' => t('epmms','Dup'),
			'dup_member_id' => t('epmms','Dup Member'),
			'dup_money' => t('epmms','重消金额'),
			'dup_is_verify' => t('epmms','审核状态'),
			'dup_add_date' => t('epmms','添加日期'),
			'dup_verify_date' => t('epmms','审核日期'),
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

		$sort=new Sort('Dup');
		$sort->defaultOrder=array('dup_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('dup_id',$this->dup_id);
		$criteria->compare('dup_member_id',$this->dup_member_id);
		$criteria->compare('dup_money',$this->dup_money,true);
		$criteria->compare('dup_is_verify',$this->dup_is_verify);
		$criteria->compare('dup_add_date',$this->dup_add_date,true);
		$criteria->compare('dup_verify_date',$this->dup_verify_date,true);
		$criteria->compare('"dupMember".memberinfo_account',@$this->dupMember->memberinfo_account);
		$criteria->with=array('dupMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
