<?php

/**
 * This is the model class for table "{{stock_trend}}".
 *
 * The followings are the available columns in table '{{stock_trend}}':
 * @property integer $stock_trend_id
 * @property string $stock_trend_value
 * @property string $stock_trend_date
 * @property integer $stock_trend_memberinfo_id
 *
 * The followings are the available model relations:
 * @property  */
class StockTrend extends Model
{
	//模型标题
	public $modelName='电子股趋势';
	//命名字段
	public $nameColumn='showName';
	public $datetype='period';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{stock_trend}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('stock_trend_date', 'filter','filter'=>array($this,'empty2null')),
			array('stock_trend_value, stock_trend_memberinfo_id', 'required'),
			array('stock_trend_memberinfo_id', 'numerical', 'integerOnly'=>true),
			array('stock_trend_memberinfo_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('stock_trend_value', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('stock_trend_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('stock_trend_id, stock_trend_value, stock_trend_date, stock_trend_memberinfo_id', 'safe', 'on'=>'search'),
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
			'stockTrendMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'stock_trend_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stock_trend_id' => t('epmms','Stock Trend'),
			'stock_trend_value' => t('epmms','电子股'),
			'stock_trend_date' => t('epmms','日期'),
			'stock_trend_memberinfo_id' => t('epmms','会员'),
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

		$sort=new Sort('StockTrend');
		$sort->defaultOrder=array("date_trunc('day',stock_trend_date)"=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		switch($this->datetype)
		{
			case 'day':
				$criteria->group="date_trunc('day',stock_trend_date),stock_trend_memberinfo_id";
				$criteria->select="stock_trend_memberinfo_id,avg(stock_trend_value) as stock_trend_value,date_trunc('day',stock_trend_date) as stock_trend_date";
				break;
			case 'week':
				$criteria->group="date_trunc('week',stock_trend_date),stock_trend_memberinfo_id";
				$criteria->select="stock_trend_memberinfo_id,avg(stock_trend_value) as stock_trend_value,date_trunc('week',stock_trend_date) as stock_trend_date";
				break;
			case 'month':
				$criteria->group="date_trunc('month',stock_trend_date),stock_trend_memberinfo_id";
				$criteria->select="stock_trend_memberinfo_id,avg(stock_trend_value) as stock_trend_value,date_trunc('month',stock_trend_date) as stock_trend_date";
				break;
			case 'year':
				$criteria->group="date_trunc('year',stock_trend_date),stock_trend_memberinfo_id";
				$criteria->select="stock_trend_memberinfo_id,avg(stock_trend_value) as stock_trend_value,date_trunc('year',stock_trend_date) as stock_trend_date";
				break;
		}

		$criteria->compare('stock_trend_id',$this->stock_trend_id);
		$criteria->compare('stock_trend_value',$this->stock_trend_value,true);
		$criteria->compare('stock_trend_date',$this->stock_trend_date,true);
		if(!user()->isAdmin())
			$this->stock_trend_memberinfo_id=user()->id;
		$criteria->compare('stock_trend_memberinfo_id',$this->stock_trend_memberinfo_id);
		$criteria->compare('"stockTrendMemberinfo".memberinfo_account',@$this->stockTrendMemberinfo->memberinfo_account);
		//$criteria->with=array('stockTrendMemberinfo');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StockTrend the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
