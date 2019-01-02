<?php

/**
 * This is the model class for table "{{award_period}}".
 *
 * The followings are the available columns in table '{{award_period}}':
 * @property string $award_period_id
 * @property integer $award_period_period
 * @property string $award_period_memberinfo_id
 * @property string $award_period_currency
 * @property integer $award_period_type_id
 * @property string $award_period_add_date
 * @property integer $award_period_sum_type
 *
 * The followings are the available model relations:
 * @property  * @property  */
class AwardPeriod extends Model
{
	public $modelName='每期奖金明细';
	public $nameColumn='award_period_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return awardPeriod the static model class
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
		return '{{award_period}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_period_period, award_period_memberinfo_id, award_period_currency, award_period_type_id, award_period_add_date', 'required'),
			array('award_period_period, award_period_type_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_period_id, award_period_period, award_period_memberinfo_id, award_period_currency, award_period_type_id, award_period_add_date', 'safe', 'on'=>'search'),
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
			'awardPeriodType' => array(Model::BELONGS_TO, 'AwardType', 'award_period_type_id'),
			'awardPeriodMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_period_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_period_id' => 'Award Period',
			'award_period_period' =>t('epmms','奖金期次') ,
			'award_period_memberinfo_id' =>t('epmms','会员') ,
			'award_period_currency' =>t('epmms', '奖金金额'),
			'award_period_type_id' =>t('epmms', '奖金类型'),
			'award_period_add_date' =>t('epmms', '日期'),
			'award_period_sum_type' =>t('epmms', '汇总类型')
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
        $sort=new Sort('awardPeriod');
        $sort->defaultOrder=array('award_period_period'=>Sort::SORT_DESC,'award_period_id'=>Sort::SORT_ASC);
        $criteria=new CDbCriteria;
        $criteria->compare('award_period_id',$this->award_period_id);
        $criteria->compare('award_period_memberinfo_id',$this->award_period_memberinfo_id);
        $criteria->compare('award_period_currency',$this->award_period_currency);
        $criteria->compare('award_period_type_id',$this->award_period_type_id);
        $criteria->compare('award_period_period',$this->award_period_period);
        $criteria->compare('award_period_add_date',$this->award_period_add_date);
        $criteria->compare('award_period_sum_type',$this->award_period_sum_type);
        $criteria->compare('"awardPeriodMemberinfo".memberinfo_account',@$this->awardPeriodMemberinfo->memberinfo_account);
        $criteria->compare('"awardPeriodType".award_type_name',@$this->awardPeriodType->award_type_name);
        $criteria->with=array('awardPeriodType','awardPeriodMemberinfo');
        return new JSonActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'pagination'=>array(
                'pageSize'=>20,
            ),
            'relations'=>['awardPeriodType']
        ));
	}
}