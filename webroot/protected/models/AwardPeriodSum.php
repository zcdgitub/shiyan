<?php

/**
 * This is the model class for table "{{award_period_sum}}".
 *
 * The followings are the available columns in table '{{award_period_sum}}':
 * @property string $award_period_sum_id
 * @property string $award_period_sum_memberinfo_id
 * @property string $award_period_sum_src_memberinfo_id
 * @property string $award_period_sum_currency
 * @property integer $award_period_sum_period
 * @property string $award_period_sum_add_date
 * @property integer $award_period_sum_type
 * @property interger $award_period_sum_src_memberinfo_id_b
 *
 * The followings are the available model relations:
 * @property  * @property  * @property  */
class awardPeriodSum extends Model
{
	public $modelName='每期奖金明细及小计';
	public $nameColumn='award_period_sum_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return awardPeriodSum the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function __get($name)
	{
		if(strncmp('_award_',$name,7)==0)
		{
			if($this->isNewRecord)
				return '0.00';
			$type_id=(int)substr($name,7);
			$award=AwardPeriod::model()->find(
				array('condition'=>"award_period_memberinfo_id=:id
				and award_period_period=:period
				and award_period_type_id=:type and award_period_sum_type=:sum_type",
					'params'=>[':id'=>$this->award_period_sum_memberinfo_id,':period'=>$this->award_period_sum_period,':type'=>$type_id,':sum_type'=>$this->award_period_sum_type]));
			if(is_object($award))
				return $award->award_period_currency ;
			else
				return '0.00';
		}
		return parent::__get($name);
	}
	public function getPlusSum()
	{
		$cmd=new DbEvaluate("sum(award_period_currency) from epmms_award_period where award_period_memberinfo_id=:id and award_period_sum_type=:stype and award_period_period=:period and award_period_currency>0",
				[':id'=>$this->award_period_sum_memberinfo_id,':stype'=>$this->award_period_sum_type,':period'=>$this->award_period_sum_period]);
		return $cmd->run();
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{award_period_sum}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_period_sum_src_memberinfo_id, award_period_sum_currency, award_period_sum_add_date, award_period_sum_type', 'filter','filter'=>array($this,'empty2null')),
			array('award_period_sum_memberinfo_id, award_period_sum_period', 'required'),
			array('award_period_sum_period, award_period_sum_type,award_period_sum_src_memberinfo_id_b', 'numerical', 'integerOnly'=>true),
			array('award_period_sum_memberinfo_id, award_period_sum_period, award_period_sum_type', 'unique'),
			array('award_period_sum_src_memberinfo_id,award_period_sum_memberinfo_id ', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('award_period_sum_type', 'exist',  'className'=>'SumType','attributeName'=>'sum_type_id'),
			array('award_period_sum_src_memberinfo_id, award_period_sum_currency', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_period_sum_id, award_period_sum_memberinfo_id, award_period_sum_src_memberinfo_id, award_period_sum_currency, award_period_sum_period, award_period_sum_add_date, award_period_sum_type', 'safe', 'on'=>'search'),
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
			'awardPeriodSumMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_period_sum_memberinfo_id'),
			'awardPeriodSumSrcMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'award_period_sum_src_memberinfo_id'),
			'awardPeriodSumSrcMemberinfo_b' => array(Model::BELONGS_TO, 'Membermap3', 'award_period_sum_src_memberinfo_id_b'),
			'awardPeriodSumType' => array(Model::BELONGS_TO, 'SumType', 'award_period_sum_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_period_sum_id' => 'Award Period Sum',
			'award_period_sum_memberinfo_id' => t('epmms','会员帐号'),
			'award_period_sum_src_memberinfo_id' => t('epmms','来源会员账号'),
			'award_period_sum_currency' => t('epmms','奖金小计'),
			'award_period_sum_period' => t('epmms','结算期次'),
			'award_period_sum_add_date' => t('epmms','结算日期'),
			'award_period_sum_type' => t('epmms','汇总类型'),
			'plusSum'=>t('epmms','收入')
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
        $sort=new Sort('awardPeriodSum');
        $sort->defaultOrder=array('award_period_sum_id'=>Sort::SORT_DESC);
        $criteria=new CDbCriteria;
        $criteria->compare('award_period_sum_id',$this->award_period_sum_id);
        $criteria->compare('award_period_sum_memberinfo_id',$this->award_period_sum_memberinfo_id);
        $criteria->compare('award_period_sum_currency',$this->award_period_sum_currency);
        $criteria->compare('award_period_sum_period',$this->award_period_sum_period);
        $criteria->compare('award_period_sum_add_date',$this->award_period_sum_add_date);
        $criteria->compare('"awardPeriodSumMemberinfo".memberinfo_account',@$this->awardPeriodSumMemberinfo->memberinfo_account);
        $criteria->compare('"awardPeriodSumMemberinfo".memberinfo_nickname',@$this->awardPeriodSumMemberinfo->memberinfo_nickname);
        $criteria->compare('"awardPeriodSumSrcMemberinfo".memberinfo_account',@$this->awardPeriodSumSrcMemberinfo->memberinfo_account);
        $criteria->compare('"awardPeriodSumType".sum_type_id',@$this->awardPeriodSumType->sum_type_id);
        $criteria->with=array('awardPeriodSumMemberinfo','awardPeriodSumSrcMemberinfo','awardPeriodSumType');


        if (webapp()->request->isAjaxRequest)
        {
            $page=0;
            $pageSize=20;
            if(isset($_GET['page']))
                $page=$_GET['page']-1;
            if(isset($_GET['limit']))
                $pageSize=$_GET['limit'];
            if($this->awardPeriodSumType->sum_type_id == 6){
//                $criteria->compare('"awardPeriodSumType".sum_type_id',@$this->awardPeriodSumType->sum_type_id);
                $criteria->addInCondition('"awardPeriodSumType".sum_type_id',array(3,4,5));
            }else{
                $criteria->compare('"awardPeriodSumType".sum_type_id',@$this->awardPeriodSumType->sum_type_id);
            }
            return new JSonActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'includeDataProviderInformation'=>true,
                'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                ),
                'relations'=>['awardPeriodSumSrcMemberinfo']
            ));
        } else
        {
            $criteria->compare('"awardPeriodSumType".sum_type_id',@$this->awardPeriodSumType->sum_type_id);
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => $sort,
            ));
        }
	}
}