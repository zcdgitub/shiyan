<?php

/**
 * This is the model class for table "{{orders_product}}".
 *
 * The followings are the available columns in table '{{orders_product}}':
 * @property integer $orders_product_id
 * @property integer $orders_product_product_id
 * @property string $orders_product_price
 * @property integer $orders_product_orders_id
 * @property integer $orders_product_count
 * @property string $orders_product_currency
 *
 * The followings are the available model relations:
 * @property  * @property  */
class OrdersProduct extends Model
{
	public $modelName='订单产品';
	public $nameColumn='orders_product_id';
	public $stock=null;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OrdersProduct the static model class
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
		return '{{orders_product}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('orders_product_product_id, orders_product_price, orders_product_orders_id, orders_product_count, orders_product_currency', 'required'),
			array('orders_product_product_id, orders_product_orders_id, orders_product_count', 'numerical', 'integerOnly'=>true),
			array('orders_product_product_id', 'exist', 'className'=>'Product','attributeName'=>'product_id'),
			array('orders_product_price, orders_product_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			//array('orders_product_count','ext.validators.BuyCount','min'=>0),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('orders_product_id, orders_product_product_id, orders_product_price, orders_product_orders_id, orders_product_count, orders_product_currency', 'safe', 'on'=>'search'),
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
			'ordersProductProduct' => array(Model::BELONGS_TO, 'Product', 'orders_product_product_id'),
			'ordersProductOrders' => array(Model::BELONGS_TO, 'Orders', 'orders_product_orders_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'orders_product_id' => 'Orders Product',
			'orders_product_product_id' => '订购产品',
			'orders_product_price' => '产品价格',
			'orders_product_orders_id' => '订单',
			'orders_product_count' => '购买数量',
			'orders_product_currency' => '小计金额',
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
		$sort=new Sort('OrdersProduct');
		$sort->defaultOrder=array('orders_product_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('orders_product_id',$this->orders_product_id);
		$criteria->compare('orders_product_price',$this->orders_product_price,false);
		$criteria->compare('orders_product_count',$this->orders_product_count);
		$criteria->compare('orders_product_currency',$this->orders_product_currency,false);
		$criteria->compare('"ordersProductProduct".product_name',@$this->ordersProductProduct->product_name);
		$criteria->compare('"ordersProductOrders".orders_id',@$this->ordersProductOrders->orders_id);
		$criteria->with=array('ordersProductProduct','ordersProductOrders');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}