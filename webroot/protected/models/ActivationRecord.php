<?php

/**
 * This is the model class for table "{{activation_record}}".
 *
 * The followings are the available columns in table '{{activation_record}}':
 * @property integer $activation_id
 * @property integer $activation_member_id
 * @property string $activation_add_time
 *
 * The followings are the available model relations:
 * @property  */
class ActivationRecord extends Model
{
	//模型标题
	public $modelName='激活金卡记录表';
	//命名字段
	public $nameColumn='activation_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{activation_record}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('activation_member_id, activation_add_time', 'required'),
			array('activation_member_id', 'numerical', 'integerOnly'=>true),
			array('activation_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('activation_id, activation_member_id, activation_add_time', 'safe', 'on'=>'search'),
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
			'activationMember' => array(Model::BELONGS_TO, 'Memberinfo', 'activation_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'activation_id' => t('epmms','主键id'),
			'activation_member_id' => t('epmms','会员id'),
			'activation_add_time' => t('epmms','激活时间'),
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

		$sort=new Sort('ActivationRecord');
		$sort->defaultOrder=array('activation_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('activation_id',$this->activation_id);
		$criteria->compare('activation_member_id',$this->activation_member_id);
		$criteria->compare('activation_add_time',$this->activation_add_time,true);
		$criteria->compare('"activationMember".memberinfo_account',@$this->activationMember->memberinfo_account);
		$criteria->with=array('activationMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActivationRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
