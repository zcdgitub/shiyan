<?php

/**
 * This is the model class for table "{{pay_log}}".
 *
 * The followings are the available columns in table '{{pay_log}}':
 * @property integer $type
 * @property string $currency
 * @property string $add_date
 * @property integer $result
 * @property integer $orders_id
 *
 * The followings are the available model relations:
 * @property  */
class Pay extends CFormModel
{
	public $modelName='订单支付';
	public $type;
	public $currency;
	public $remark;
	public $result;
	public $orders_id;
    public $password2;
        /*public $trade_password;*/
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, currency, orders_id', 'required'),
            array('currency',  'numerical'/*,'min'=>12000,'max'=>'12000','message'=>'订单总额必须是12000元'*/),
			array('type, result, orders_id', 'numerical', 'integerOnly'=>true),
			array('type', 'in', 'range'=>[3,6]),
			array('orders_id', 'exist', 'className'=>'Orders','attributeName'=>'orders_id'),
			array('currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
		/*	array('trade_password', 'ext.validators.Password'),
            array('trade_password', 'ext.validators.TradePassword', 'allowEmpty'=>false),*/
            array('password2', 'ext.validators.Password'),
            array('password2', 'ext.validators.TradePassword', 'allowEmpty'=>false),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'type' => '支付方式',
			'currency' => '支付金额',
			'remark' => '备注',
			'add_date' => '支付时间',
			'result' => '支付结果',
			'orders_id' => '订单流水号',
		);
	}
	public function getPayTypeList()
	{
		return CHtml::encodeArray([3=>'购物钱包']);
		
	}
}