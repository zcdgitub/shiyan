<?php

/**
 * This is the model class for table "{{jackpot_record}}".
 *
 * The followings are the available columns in table '{{jackpot_record}}':
 * @property integer $jackpot_id
 * @property integer $jackpot_member_id
 * @property string $jackpot_money
 * @property integer $jackpot_type
 * @property integer $jackpot_start_time
 * @property integer $jackpot_end_time
 *
 * The followings are the available model relations:
 * @property  */
class JackpotRecord extends Model
{
    //模型标题
    public $modelName='竞买抽奖奖池记录';
    //命名字段
    public $nameColumn='jackpot_id';
    public $startTime;
    public $endTime;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{jackpot_record}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('jackpot_money, jackpot_type, jackpot_start_time, jackpot_end_time', 'filter','filter'=>array($this,'empty2null')),
            array('jackpot_member_id', 'required'),
            array('jackpot_member_id, jackpot_type, jackpot_start_time, jackpot_end_time', 'numerical', 'integerOnly'=>true),
            array('jackpot_money', 'ext.validators.Decimal','precision'=>16,'scale'=>4),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('jackpot_id, jackpot_member_id, jackpot_money, jackpot_type, jackpot_start_time, jackpot_end_time', 'safe', 'on'=>'search'),
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
            'jackpot' => array(Model::BELONGS_TO, 'Memberinfo', 'jackpot_member_id'),
        );
    }
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'jackpot_id' => t('epmms','主键id'),
            'jackpot_member_id' => t('epmms','会员id'),
            'jackpot_money' => t('epmms','获得金额'),
            'jackpot_type' => t('epmms','类型'),
//            'jackpot_type' => t('epmms','类型 1 首单奖 2 幸运奖 3 尾单奖'),
            'jackpot_start_time' => t('epmms','开始时间'),
            'jackpot_end_time' => t('epmms','结束时间'),
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

        $sort=new Sort('JackpotRecord');
//        $sort->defaultOrder=array('jackpot_id'=>Sort::SORT_ASC);
        $sort->defaultOrder=array('jackpot_end_time'=>Sort::SORT_DESC);
        $criteria=new CDbCriteria;

        $criteria->compare('jackpot_id',$this->jackpot_id);
        $criteria->compare('jackpot_member_id',$this->jackpot_member_id);
        $criteria->compare('jackpot_money',$this->jackpot_money);
        $criteria->compare('jackpot_type',$this->jackpot_type);
        $criteria->compare('jackpot_start_time',$this->jackpot_start_time);
        $criteria->compare('jackpot_end_time',$this->jackpot_end_time);
        $criteria->compare('"jackpot".memberinfo_account',@$this->jackpot->memberinfo_account);
        $criteria->with=array('jackpot');
        $this->jackpot_start_time = $this->startTime? ">".$this->startTime :'';
        $this->jackpot_end_time = $this->endTime? ">".$this->endTime :'';
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return JackpotRecord the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
