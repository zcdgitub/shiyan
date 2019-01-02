<?php

/**
 * This is the model class for table "{{memberstatus}}".
 *
 * The followings are the available columns in table '{{memberstatus}}':
 * @property integer $status_id
 * @property integer $status_award_period
 *
 * The followings are the available model relations:
 * @property  */
class MemberStatus extends Model
{
	public $modelName='会员状态';
	public $nameColumn='status_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MemberStatus the static model class
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
		return '{{memberstatus}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status_award_period', 'filter','filter'=>array($this,'empty2null')),
			array('status_id', 'required'),
			array('status_id, status_award_period', 'numerical', 'integerOnly'=>true),
			array('status_id', 'unique'),
			array('status_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('status_id, status_award_period', 'safe', 'on'=>'search'),
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
			'status' => array(Model::HAS_ONE, 'Memberinfo', 'memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'status_id' => 'Status',
			'status_award_period' => 'Status Award Period',
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
		$sort=new Sort('MemberStatus');
		$sort->defaultOrder=array('status_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('status_award_period',$this->status_award_period);
		$criteria->compare('"status".memberinfo_account',@$this->status->memberinfo_account);
		$criteria->with=array('status');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}