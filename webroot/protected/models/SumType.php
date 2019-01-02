<?php

/**
 * This is the model class for table "{{sum_type}}".
 *
 * The followings are the available columns in table '{{sum_type}}':
 * @property string $sum_type_id
 * @property string $sum_type_name
 * @property string $sum_finance_type_id
 *
 * The followings are the available model relations:
 * @property  * @property  * @property  */
class SumType extends Model
{
    public $modelName='汇总类型';
    public $nameColumn='sum_type_name';
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return sumType the static model class
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
        return '{{sum_type}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sum_type_name, sum_finance_type_id', 'filter','filter'=>array($this,'empty2null')),
            array('sum_type_name', 'length', 'max'=>50),
            array('sum_finance_type_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('sum_type_id, sum_type_name, sum_finance_type_id', 'safe', 'on'=>'search'),
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
            //'capSums' => array(Model::HAS_MANY, 'CapSum', 'cap_sum_sum_type'),
            //'sumFinanceType' => array(Model::BELONGS_TO, 'FinanceType', 'sum_finance_type_id'),
            'awardPeriodSums' => array(Model::HAS_MANY, 'AwardPeriodSum', 'award_period_sum_type'),
            'awardTotal' => array(Model::HAS_MANY, 'AwardTotal', 'award_total_type_id'),
            'sumConfigs' => array(Model::HAS_MANY, 'SumConfig', 'sum_type_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'sum_type_id' => 'Sum Type',
            'sum_type_name' => 'Sum Type Name',
            'sum_finance_type_id' => 'Sum Finance Type',
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
        $sort=new Sort('sumType');
        $sort->defaultOrder=array('sum_type_id'=>Sort::SORT_ASC);
        $criteria=new CDbCriteria;
        //$criteria->compare('sum_type_id',$this->sum_type_id,true);
        //$criteria->compare('sum_type_name',$this->sum_type_name,true);
        //$criteria->compare('sumFinanceType.finance_type_id',@$this->sumFinanceType->finance_type_id);
        $criteria->with=array('awardTotal');
        return new JSonActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'relations'=>['awardTotal']
        ));
    }
}