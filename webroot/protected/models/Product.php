<?php

/**
 * This is the model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property integer $product_id
 * @property string $product_name
 * @property string $product_title
 * @property string $product_price
 * @property string $product_info
 * @property string $product_image_url
 * @property string $product_mod_date
 * @property integer $product_stock
 * @property integer $product_sale_status
 * @property integer $product_sales_amount
 * @property integer $product_class_id
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Product extends Model
{
	public $modelName='产品';
	public $nameColumn='product_name';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
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
		return '{{product}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_stock, product_sale_status, product_sales_amount', 'filter','filter'=>array($this,'empty2null')),
			array('product_name, product_price, product_image_url,product_stock, product_sale_status,product_title','required'),
			array('product_stock, product_sale_status, product_sales_amount,product_credit', 'numerical', 'integerOnly'=>true),
			array('product_info','length','max'=>32768),
			array('product_title', 'length', 'max'=>255),
			array('product_image_url', 'length', 'max'=>1024),
			//array('product_name', 'unique'),
			array('product_cost', 'ext.validators.Decimal','precision'=>10,'scale'=>2),
			//array('product_name', 'ext.validators.Account','allowZh'=>true),
			array('product_price', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('product_class_id','exist', 'className'=>'ProductClass','attributeName'=>'product_class_id'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('product_id, product_name, product_title, product_price, product_info, product_image_url, product_mod_date, product_stock, product_sale_status, product_sales_amount,product_cost, product_credit,product_class_id', 'safe', 'on'=>'search'),
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
			'ordersProducts' => array(Model::HAS_MANY, 'OrdersProduct', 'orders_product_product_id'),
			'membermaps' => array(Model::HAS_MANY, 'Membermap', 'membermap_product_id'),
			'productClass' => array(Model::BELONGS_TO, 'ProductClass', 'product_class_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'ID',
			'product_name' => t('epmms','名称'),
			'product_title'=> t('epmms','标题'),
			/*'product_mark'=> t('epmms','备注'),*/
			'product_price' => t('epmms','价格'),
			'product_info' => t('epmms','介绍'),
			'product_image_url' => t('epmms','图片'),
			'product_mod_date' => t('epmms','更新'),
			'product_stock'=>t('epmms','库存'),
			'product_sale_status'=>t('epmms','是否上架'),
			'product_sales_amount'=>t('epmms','销量'),
			'product_class_id'=>t('epmms','分类'),
			'product_credit'=>t('epmms','积分'),
			'product_cost'=>t('epmms','成本'),
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
		$sort=new Sort('Product');
		$sort->defaultOrder=array('product_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('product_name',$this->product_name,false);
		$criteria->compare('product_title',$this->product_title,false);
		$criteria->compare('product_price',$this->product_price,false);
		$criteria->compare('product_info',$this->product_info,false);
		$criteria->compare('product_image_url',$this->product_image_url,false);
		$criteria->compare('product_mod_date',$this->product_mod_date,false);
		$criteria->compare('product_stock',$this->product_stock);
		$criteria->compare('product_sale_status',$this->product_sale_status);
		$criteria->compare('product_sales_amount',$this->product_sales_amount);
		$criteria->compare('product_cost',$this->product_cost);
		$criteria->compare('product_credit',$this->product_credit);
		$criteria->compare('"productClass".product_name',$this->productClass->product_name,true);
		$criteria->with=array('productClass');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
            'pagination' => [
                'pageSize' => 20,
            ],
		));
	}
}