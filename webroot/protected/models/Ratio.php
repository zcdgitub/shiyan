<?php

/**
 * This is the model class for table "{{ratio}}".
 *
 * The followings are the available columns in table '{{ratio}}':
 * @property integer $ratio_id
 * @property string $ratio_value
 * @property string $ratio_add_date
 */
class Ratio extends Model
{
	//模型标题
	public $modelName='拨比趋势';
	//命名字段
	public $nameColumn='ratio_id';
	public $datetype='period';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ratio}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ratio_add_date', 'filter','filter'=>array($this,'empty2null')),
			array('ratio_value', 'required'),
			array('ratio_value', 'ext.validators.Decimal','precision'=>6,'scale'=>4),
			array('ratio_add_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ratio_id, ratio_value, ratio_add_date', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ratio_id' => t('epmms','Ratio'),
			'ratio_value' => t('epmms','拨出比值'),
			'ratio_add_date' => t('epmms','日期'),
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

		$sort=new Sort('Ratio');
		$sort->defaultOrder=array('ratio_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		switch($this->datetype)
		{
			case 'day':
				$criteria->group="date_trunc('day',ratio_add_date)";
				$criteria->select="avg(ratio_value) as ratio_value,date_trunc('day',ratio_add_date) as ratio_add_date";
				break;
			case 'week':
				$criteria->group="date_trunc('week',ratio_add_date)";
				$criteria->select="avg(ratio_value) as ratio_value,date_trunc('week',ratio_add_date) as ratio_add_date";
				break;
			case 'month':
				$criteria->group="date_trunc('month',ratio_add_date)";
				$criteria->select="avg(ratio_value) as ratio_value,date_trunc('month',ratio_add_date) as ratio_add_date";
				break;
			case 'year':
				$criteria->group="date_trunc('year',ratio_add_date)";
				$criteria->select="avg(ratio_value) as ratio_value,date_trunc('year',ratio_add_date) as ratio_add_date";
				break;
		}

		$criteria->compare('ratio_id',$this->ratio_id);
		$criteria->compare('ratio_value',$this->ratio_value,true);
		$criteria->compare('ratio_add_date',$this->ratio_add_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ratio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	protected  function afterFind()
	{
		//$this->ratio_value=webapp()->format->formatPercentage($this->ratio_value);
	}
}
