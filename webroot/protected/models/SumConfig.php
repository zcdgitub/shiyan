<?php

/**
 * This is the model class for table "{{sum_config}}".
 *
 * The followings are the available columns in table '{{sum_config}}':
 * @property integer $sum_config_id
 * @property integer $sum_type_id
 * @property string $sum_config_date
 *
 * The followings are the available model relations:
 * @property  */
class SumConfig extends Model
{
	//模型标题
	public $modelName='结算周期配置';
	//命名字段
	public $nameColumn='sum_config_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sum_config}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sum_type_id, sum_config_date', 'required'),
			array('sum_type_id', 'numerical', 'integerOnly'=>true),
			array('sum_config_date', 'length', 'max'=>10),
			array('sum_type_id', 'exist', 'className'=>'SumType','attributeName'=>'sum_type_id'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sum_config_id, sum_type_id, sum_config_date', 'safe', 'on'=>'search'),
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
			'sumType' => array(Model::BELONGS_TO, 'SumType', 'sum_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sum_config_id' => t('epmms','Sum Config'),
			'sum_type_id' => t('epmms','汇总类型'),
			'sum_config_date' => t('epmms','汇总日期'),
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

		$sort=new Sort('SumConfig');
		$sort->defaultOrder=array('sum_config_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('sum_config_id',$this->sum_config_id);
		$criteria->compare('sum_type_id',$this->sum_type_id);
		$criteria->compare('sum_config_date',$this->sum_config_date,true);
		$criteria->compare('"sumType".sum_type_name',@$this->sumType->sum_type_name);
		$criteria->with=array('sumType');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SumConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
