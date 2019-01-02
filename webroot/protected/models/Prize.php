<?php

/**
 * This is the model class for table "{{prize}}".
 *
 * The followings are the available columns in table '{{prize}}':
 * @property integer $prize_id
 * @property string $prize_info
 * @property integer $prize_is_verify
 * @property string $prize_verify_date
 * @property string $prize_date
 * @property integer $prize_member_id
 *
 * The followings are the available model relations:
 * @property  */
class Prize extends Model
{
	//模型标题
	public $modelName='我的奖品';
	//命名字段
	public $nameColumn='prize_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{prize}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prize_is_verify, prize_verify_date', 'filter','filter'=>array($this,'empty2null')),
			array('prize_info, prize_date, prize_member_id', 'required'),
			array('prize_is_verify, prize_member_id', 'numerical', 'integerOnly'=>true),
			array('prize_info', 'length', 'max'=>50),
			array('prize_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('prize_verify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('prize_id, prize_info, prize_is_verify, prize_verify_date, prize_date, prize_member_id', 'safe', 'on'=>'search'),
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
			'prizeMember' => array(Model::BELONGS_TO, 'Memberinfo', 'prize_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'prize_id' => t('epmms','Prize'),
			'prize_info' => t('epmms','奖品内容'),
			'prize_is_verify' => t('epmms','发货状态'),
			'prize_verify_date' => t('epmms','发货日期'),
			'prize_date' => t('epmms','发放日期'),
			'prize_member_id' => t('epmms','获奖会员'),
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

		$sort=new Sort('Prize');
		$sort->defaultOrder=array('prize_is_verify'=>Sort::SORT_ASC,'prize_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		if(!user()->isAdmin())
			$this->prize_member_id=user()->id;
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('prize_info',$this->prize_info,true);
		$criteria->compare('prize_is_verify',$this->prize_is_verify);
		$criteria->compare('prize_verify_date',$this->prize_verify_date,true);
		$criteria->compare('prize_date',$this->prize_date,true);
		$criteria->compare('prize_member_id',$this->prize_member_id);
		$criteria->compare('"prizeMember".memberinfo_account',@$this->prizeMember->memberinfo_account);
		$criteria->with=array('prizeMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Prize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
