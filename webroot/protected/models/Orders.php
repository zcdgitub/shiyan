<?php

/**
 * This is the model class for table "{{orders}}".
 *
 * The followings are the available columns in table '{{orders}}':
 * @property integer $orders_id
 * @property integer $orders_member_id
 * @property string $orders_currency
 * @property string $orders_status
 * @property string $orders_add_date
 * @property string $orders_is_verify
 * @property string $orders_verify_date
 * @property string $orders_remark
 * @property string $orders_sn
 * @property string $orders_logistics_name
 * @property string $orders_logistics_sn
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Orders extends Model
{
	public $modelName='订单';
	public $nameColumn='orders_sn';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Orders the static model class
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
		return '{{orders}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('orders_member_id, orders_currency, orders_status', 'required'),
			array('orders_member_id', 'numerical', 'integerOnly'=>true),
			array('orders_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			//array('orders_currency,orders_price', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'sign'=>0,'allowZero'=>true,'message'=>'订单总额必须超过0元'),
			//array('orders_currency', 'ext.validators.AbleOrders','on'=>'reg'),
			array('orders_remark,orders_sn,orders_logistics_name,orders_logistics_sn','length'),
			array('orders_verify_date','ext.validators.Date'),
			array('orders_is_verify','numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('orders_id, orders_member_id, orders_currency, orders_status, orders_add_date,orders_price,orders_remark', 'safe', 'on'=>'search'),
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
			'ordersMember' => array(Model::BELONGS_TO, 'Memberinfo', 'orders_member_id'),
			'ordersProducts' => array(Model::HAS_MANY, 'OrdersProduct', 'orders_product_orders_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'orders_id' => '订单ID',
			'orders_member_id' => t('epmms','会员账号'),
			'orders_currency' => t('epmms','订单金额'),
			'orders_remark'=>t('epmms','备注'),
			'orders_status' => t('epmms','订单状态'),
			'orders_add_date' => t('epmms','下单时间'),
			'orders_is_verify'=> t('epmms','审核状态'),
			'orders_verify_date'=> t('epmms','执行时间'),
			'orders_sn' => t('epmms','订单流水号'),
			'orders_logistics_name'=>'物流公司',
			'orders_logistics_sn'=>'运单号'
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
		$sort=new Sort('Orders');
		$sort->defaultOrder=array('orders_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;
		$criteria->compare('orders_id',$this->orders_id);
		$criteria->compare('orders_currency',$this->orders_currency);
		$criteria->compare('orders_status',$this->orders_status);
		$criteria->compare('orders_add_date',$this->orders_add_date,false);
		$criteria->compare('orders_logistics_name',$this->orders_logistics_name,false);
		$criteria->compare('orders_logistics_sn',$this->orders_logistics_sn,false);
		$criteria->compare('orders_is_verify',$this->orders_is_verify);
		$criteria->compare('orders_member_id',$this->orders_member_id);
		$criteria->compare('orders_sn',$this->orders_sn,false);
		$criteria->compare('"ordersMember".memberinfo_account',@$this->ordersMember->memberinfo_account);
		$criteria->compare('"ordersMember".memberinfo_name',@$this->ordersMember->memberinfo_name,true);
		$criteria->compare('"ordersMember".memberinfo_address_provience',@$this->ordersMember->memberinfo_address_provience,true);
		$criteria->compare('"ordersMember".memberinfo_address_detail',@$this->ordersMember->memberinfo_address_detail,true);
		$criteria->compare('"ordersMember".memberinfo_address_area',@$this->ordersMember->memberinfo_address_area,true);
		$criteria->compare('"ordersMember".memberinfo_phone',@$this->ordersMember->memberinfo_phone,true);
	
		$criteria->with=array('ordersMember');
        if (webapp()->request->isAjaxRequest)
        {
            $page=0;
            $pageSize=20;
            if(isset($_GET['page']))
                $page=$_GET['page']-1;
            if(isset($_GET['limit']))
                $pageSize=$_GET['limit'];
            return new JSonActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                ),
                'includeDataProviderInformation'=>true,
                'relations' => ['ordersMember']
            ));
        } else
        {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => $sort,
            ));
        }
	}
	public function verify()
	{
		if($this->orders_is_verify==0)
		{

			$tra=webapp()->db->beginTransaction();
			$this->orders_is_verify=1;
			$this->orders_verify_date=new CDbExpression('now()');
			$this->orders_status="已支付";
			$this->save(false,['orders_is_verify','orders_verify_date','orders_status']);

			/*$membermap=$this->ordersMember->membermap;
            $membermap->membermap_membertype_level=2;
            $membermap->membermap_money=12000;
            $membermap->membermap_membertype_level_old=$membermap->membermap_membertype_level;
            $membermap->saveAttributes(['membermap_membertype_level','membermap_money','membermap_membertype_level_old']);
            Yii::import('ext.award.MySystem');
            $mysystem=new MySystem($membermap);*/
           /* $mysystem->run(1,0,0);*/
/*			Yii::import('ext.award.MySystemOrder');
			$mysystemb=new MySystemOrder($membermap);
			$mysystemb->run1(1,0,0,$this->orders_id);*/
			//$mysystemb->run1(2,2,1,$this->orders_id);
			//LogFilter::log(['category'=>'finance','source'=>'订单','operate'=>'支付','user'=>user()->name,'role'=>user()->getRoleName(),'target'=>$this->orders_sn,'status'=>LogFilter::SUCCESS,'value'=>$this->orders_currency]);
			$tra->commit();
			return EError::SUCCESS;
		}
		else
			return EError::DUPLICATE;
	}
	public function send()
	{
		if($this->orders_is_verify==1)
		{
			$this->orders_is_verify=2;
			//$this->orders_verify_date=new CDbExpression('now()');
			$this->orders_status="已发货";
			$this->save(false,['orders_is_verify','orders_verify_date','orders_status']);
			LogFilter::log(['category'=>'finance','source'=>'订单','operate'=>'发货','user'=>user()->name,'role'=>user()->getRoleName(),'target'=>$this->orders_sn,'status'=>LogFilter::SUCCESS]);
			return EError::SUCCESS;
		}
		else
			return EError::DUPLICATE;
	}
	public function delete()
    {
        if($this->orders_is_verify!=0)
        {
            return false;
        }
        $ops = OrdersProduct::model()->findAllByAttributes(['orders_product_orders_id' => $this->orders_id]);

        foreach($ops as $op)
        {
        	
            $op->delete();
        }
        return parent::delete();
    }
}