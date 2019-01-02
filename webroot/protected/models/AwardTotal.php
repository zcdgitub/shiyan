<?php

/**
 * This is the model class for table "{{award_total}}".
 * version id:1
 * The followings are the available columns in table '{{award_total}}':
 * @property string $award_total_id
 * @property string $award_total_memberinfo_id
 * @property string $award_total_currency
 * @property string $award_total_type_id
 * @property string $award_total_add_date
 *
 * The followings are the available model relations:
 * @property  */
class awardTotal extends Model
{
	public $modelName='奖金明细总统计';
	public $nameColumn='award_total_id';
	public $award_sum;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return awardTotal the static model class
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
		return '{{award_total}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_total_currency, award_total_add_date', 'filter','filter'=>array($this,'empty2null')),
			array('award_total_memberinfo_id, award_total_type_id', 'required'),
			array('award_total_add_date', 'length', 'max'=>3),
			array('award_total_currency', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_total_id, award_total_memberinfo_id, award_total_currency, award_total_type_id, award_total_add_date', 'safe', 'on'=>'search'),
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
			'awardTotalMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_total_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_total_id' => 'Award Total',
			'award_total_memberinfo_id' => 'Award Total Memberinfo',
			'award_total_currency' => 'Award Total Currency',
			'award_total_type_id' => 'Award Total Type',
			'award_total_add_date' => 'Award Total Add Date',
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
		$sort=new Sort('awardTotal');
		$sort->defaultOrder=array('award_total_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_total_id',$this->award_total_id,true);
		$criteria->compare('award_total_currency',$this->award_total_currency,true);
		$criteria->compare('award_total_type_id',$this->award_total_type_id,true);
		$criteria->compare('award_total_add_date',$this->award_total_add_date,true);
		$criteria->compare('awardTotalMemberinfo.memberinfo_account',@$this->awardTotalMemberinfo->memberinfo_account);
		$criteria->with=array('awardTotalMemberinfo');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}