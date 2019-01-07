<?php

/**
 * This is the model class for table "{{sale}}".
 *
 * The followings are the available columns in table '{{sale}}':
 * @property integer $sale_id
 * @property integer $sale_member_id
 * @property string $sale_currency
 * @property string $sale_date
 * @property string $sale_money
 * @property string $sale_remain_currency
 * @property integer $sale_status
 * @property string $sale_verify_date
 * @property float $sale_tax
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Sale extends Model
{
	//模型标题
	public $modelName='卖出';
	//命名字段
	public $nameColumn='sale_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sale}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sale_date, sale_money, sale_status, sale_verify_date', 'filter','filter'=>array($this,'empty2null')),
			array('sale_member_id, sale_currency, sale_remain_currency', 'required'),
			array('sale_member_id, sale_status', 'numerical', 'integerOnly'=>true),
			array('sale_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('sale_currency, sale_money, sale_remain_currency,sale_tax', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('sale_date, sale_verify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sale_id, sale_member_id, sale_currency, sale_date, sale_money, sale_remain_currency, sale_status, sale_verify_date', 'safe', 'on'=>'search'),
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
			'saleMember' => array(Model::BELONGS_TO, 'Memberinfo', 'sale_member_id'),
			'deals' => array(Model::HAS_MANY, 'Deal', 'deal_sale_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'sale_id' => t('epmms','Sale'),
			'sale_member_id' => t('epmms','卖出会员'),
			'sale_currency' => t('epmms','卖出数量'),
			'sale_date' => t('epmms','卖出日期'),
			'sale_money' => t('epmms','卖出金额'),
			'sale_remain_currency' => t('epmms','剩余数量'),
			'sale_status' => t('epmms','卖出状态'),
			'sale_verify_date' => t('epmms','配对日期'),
			'sale_tax'=>'手续费',
			'sale_dup'=>'重复消费',
			'sale_aixin'=>'爱心基金'
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

		$sort=new Sort('Sale');
		$sort->defaultOrder=array('sale_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;

		$criteria->compare('sale_id',$this->sale_id);
		$criteria->compare('sale_member_id',$this->sale_member_id);
		$criteria->compare('sale_currency',$this->sale_currency,true);
		$criteria->compare('sale_date',$this->sale_date,true);
		$criteria->compare('sale_money',$this->sale_money,true);
		$criteria->compare('sale_remain_currency',$this->sale_remain_currency,true);
		$criteria->compare('sale_status',$this->sale_status);
		$criteria->compare('sale_verify_date',$this->sale_verify_date,true);
		$criteria->compare('"saleMember".memberinfo_account',@$this->saleMember->memberinfo_account);
		$criteria->with=array('saleMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>array(
				'pageSize'=>5,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sale the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function verify()
	{
		$finance=Finance::getMemberFinance($this->sale_member_id,3);
		if($finance->finance_award>=$this->sale_currency && $finance->deduct($this->sale_currency))
		{
/*			$finance=Finance::getMemberFinance($this->sale_member_id,5);
			$finance->add(abs($this->sale_dup));*/
			return true;
		}
		return false;
	}
	public function delete()
	{
		$member=$this->saleMember;
		$finance=$member->getFinance(3);
		$finance->add($this->sale_currency);
		return parent::delete();
	}
}
