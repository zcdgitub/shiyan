<?php

/**
 * This is the model class for table "{{bank}}".
 *
 * The followings are the available columns in table '{{bank}}':
 * @property string $bank_id
 * @property string $bank_name
 * @property integer $bank_is_preset
 * @property integer $bank_is_enable
 *
 * The followings are the available model relations:
 * @property  */
class Bank extends Model
{
	public $modelName='银行';
	public $nameColumn='bank_name';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Bank the static model class
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
		return '{{bank}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bank_name', 'required'),
			array('bank_is_preset, bank_is_enable', 'numerical', 'integerOnly'=>true),
			array('bank_name', 'length', 'max'=>50),
			array('bank_name', 'unique'),
			array('bank_is_enable', 'ext.validators.Enable'),
			array('bank_is_preset', 'ext.validators.Preset'),
			array('bank_name', 'ext.validators.Account','allowZh'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('bank_id, bank_name, bank_is_preset, bank_is_enable', 'safe', 'on'=>'search'),
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
			'bankaccounts' => array(Model::HAS_MANY, 'Bankaccount', 'bankaccount_bank_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'bank_id' => 'Bank',
			'bank_name' => t('epmms','银行名称'),
			'bank_is_preset' =>t('epmms', '类型'),
			'bank_is_enable' => t('epmms','开启'),
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
        $sort=new Sort('Bank');
        $sort->defaultOrder=array('bank_id'=>Sort::SORT_ASC);
        $criteria=new CDbCriteria;
        $criteria->compare('bank_id',$this->bank_id,true);
        $criteria->compare('bank_name',$this->bank_name,true);
        $criteria->compare('bank_is_preset',$this->bank_is_preset);
        $criteria->compare('bank_is_enable',$this->bank_is_enable);
        return new JSonActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'pagination'=>array(
                'pageSize'=>10000,
            ),
        ));
    }

}