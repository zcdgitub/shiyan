<?php

/**
 * This is the model class for table "{{award_total_sum}}".
 *version id:1
 * The followings are the available columns in table '{{award_total_sum}}':
 * @property string $award_total_sum_id
 * @property string $award_total_sum_memberinfo_id
 * @property string $award_total_sum_currency
 * @property string $award_total_sum_add_date
 * @property string $award_total_sum_type
 *
 * The followings are the available model relations:
 * @property  * @property  */
class AwardTotalSum extends Model
{
	public $modelName='奖金明细总统计及小计';
	public $nameColumn='award_total_sum_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return awardTotalSum the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 设置各奖金字段的属性
	 * @param string $name
	 * @return mixed|null
	 */
	public function __get($name)
	{
		if(strncmp('_award_',$name,7)==0)
		{
			if($this->isNewRecord)
				return '0.00';
			$type_id=(int)substr($name,7);
			$award=AwardTotal::model()->find(
				array('condition'=>"award_total_memberinfo_id=:member_id
				and award_total_type_id=:type_id
				and award_total_sum_type=:sum_type",
					'params'=>[':member_id'=>$this->award_total_sum_memberinfo_id,':type_id'=>$type_id,':sum_type'=>$this->award_total_sum_type])
			);
			if(is_object($award))
				return $award->award_total_currency;
			else
				return '0.00';
		}
		elseif(strncmp('_awardSum_',$name,10)==0)
		{
			if($this->isNewRecord)
				return 0;
			$type_id=(int)substr($name,10);
			$award=AwardTotal::model()->find(
				array('select'=>'sum(award_total_currency) award_sum','condition'=>"award_total_type_id=:type_id",
					'params'=>[':type_id'=>$type_id])
			);
			if(is_object($award))
				return $award->award_sum;
			else
				return '0.00';
		}
		elseif(strncmp('_awardSumMember_',$name,16)==0)
		{
			if($this->isNewRecord)
				return 0;
			$type_id=(int)substr($name,16);
			$award=AwardTotal::model()->find(
				array('select'=>'sum(award_total_currency) award_sum','condition'=>"award_total_memberinfo_id=:member_id and award_total_type_id=:type_id",
					'params'=>[':type_id'=>$type_id,':member_id'=>$this->award_total_sum_memberinfo_id])
			);
			if(is_object($award))
				return $award->award_sum;
			else
				return '0.00';
		}
		return parent::__get($name);
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{award_total_sum}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_total_sum_add_date, award_total_sum_type', 'filter','filter'=>array($this,'empty2null')),
			array('award_total_sum_memberinfo_id, award_total_sum_currency', 'required'),
			array('award_total_sum_memberinfo_id ', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('award_total_sum_type', 'exist', 'className'=>'SumType','attributeName'=>'sum_type_id'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_total_sum_id, award_total_sum_memberinfo_id, award_total_sum_currency, award_total_sum_add_date, award_total_sum_type', 'safe', 'on'=>'search'),
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
			'awardTotalSumMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_total_sum_memberinfo_id'),
			'awardTotalSumType' => array(Model::BELONGS_TO, 'SumType', 'award_total_sum_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_total_sum_id' => 'Award Total Sum',
			'award_total_sum_memberinfo_id' => t('epmms','会员账号'),
			'award_total_sum_currency' => t('epmms','小计'),
			'award_total_sum_add_date' => 'Award Total Sum Add Date',
			'award_total_sum_type' => t('epmms','汇总类型'),
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
		$sort=new Sort('awardTotalSum');
		$sort->defaultOrder=array('award_total_sum_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;
		$criteria->compare('award_total_sum_id',$this->award_total_sum_id);
		$criteria->compare('award_total_sum_memberinfo_id',$this->award_total_sum_memberinfo_id);
		$criteria->compare('award_total_sum_currency',$this->award_total_sum_currency);
		$criteria->compare('award_total_sum_add_date',$this->award_total_sum_add_date);
		$criteria->compare('"awardTotalSumMemberinfo".memberinfo_account',@$this->awardTotalSumMemberinfo->memberinfo_account);
		$criteria->compare('"awardTotalSumType".sum_type_id',@$this->awardTotalSumType->sum_type_id);
		$criteria->with=array('awardTotalSumMemberinfo','awardTotalSumType');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}