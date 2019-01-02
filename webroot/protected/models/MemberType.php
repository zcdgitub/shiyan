<?php

/**
 * This is the model class for table "{{membertype}}".
 *
 * The followings are the available columns in table '{{membertype}}':
 * @property string $membertype_name
 * @property string $membertype_desc
 * @property integer $membertype_level
 * @property string $membertype_mod_date
 * @property string $membertype_color
 * @property string $membertype_money
 * @property string $membertype_bill
 *
 * The followings are the available model relations:
 * @property  */
class MemberType extends Model
{
	public $modelName='会员类型';
	public $nameColumn='membertype_name';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MemberType the static model class
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
		return '{{membertype}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('membertype_desc, membertype_mod_date, membertype_color, membertype_money', 'filter','filter'=>array($this,'empty2null')),
			array('membertype_name, membertype_level,membertype_bill', 'required'),
			array('membertype_level,membertype_bill', 'numerical', 'integerOnly'=>true),
			array('membertype_desc', 'length', 'max'=>200),
			array('membertype_mod_date', 'ext.validators.Date'),
			array('membertype_color', 'length', 'max'=>20),
			array('membertype_name, membertype_level', 'unique'),
			array('membertype_name', 'ext.validators.Account','allowZh'=>true),
			array('membertype_money', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('membertype_name, membertype_desc, membertype_level, membertype_mod_date, membertype_color, membertype_money', 'safe', 'on'=>'search'),
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
			'membermaps' => array(Model::HAS_MANY, 'Membermap', 'membermap_membertype_level'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'membertype_name' => t('epmms','类型名称'),
			'membertype_desc' => t('epmms','描述'),
			'membertype_level' => t('epmms','等级'),
			'membertype_mod_date' => t('epmms','修改日期'),
			'membertype_color' => t('epmms','图形颜色'),
			'membertype_money' => t('epmms','金额'),
			'membertype_bill'=>t('epmms','单数'),
			'membertype_agent_money'=>t('epmms','报单扣币')
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
        $sort=new Sort('MemberType');
        $sort->defaultOrder=array('membertype_level'=>Sort::SORT_ASC);
        $criteria=new CDbCriteria;
        $criteria->compare('membertype_name',$this->membertype_name,false);
        $criteria->compare('membertype_desc',$this->membertype_desc,false);
        $criteria->compare('membertype_level',$this->membertype_level);
        $criteria->compare('membertype_mod_date',$this->membertype_mod_date,false);
        $criteria->compare('membertype_color',$this->membertype_color,false);
        $criteria->compare('membertype_money',$this->membertype_money,false);
        $criteria->compare('membertype_bill',$this->membertype_bill,false);
        $criteria->compare('membertype_agent_money',$this->membertype_agent_money,false);

        return new JSonActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'pagination'=>array(
                'pageSize'=>10000,
            ),
        ));
	}
	public function getListData($conditions='')
	{
		return CHtml::listData($this->findAll($conditions),'membertype_level',$this->nameColumn);
	}
}