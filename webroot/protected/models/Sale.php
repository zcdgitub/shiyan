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
 * @property integer $sale_type
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Sale extends Model
{
	//模型标题
	public $modelName='提供记录';
	//命名字段
	public $nameColumn='sale_id';
	public $password2;
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
			array('sale_date, sale_money, sale_status, sale_verify_date,sale_type', 'filter','filter'=>array($this,'empty2null')),
			array('sale_currency, sale_remain_currency,sale_member_id', 'required','message'=>'输入不正确或不可为空'),
            array('password2', 'ext.validators.Password'),
            array('password2', 'ext.validators.TradePassword', 'allowEmpty'=>false),
			//array('sale_member_id, sale_status,sale_type', 'numerical', 'integerOnly'=>true),
			array('sale_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('sale_currency, sale_money, sale_remain_currency,sale_tax', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'sign'=>0),
			array('sale_currency','ext.validators.AbleSale2','on'=>'create'),
            array('sale_type', 'in', 'range'=>[0,1]),
			array('sale_date, sale_verify_date,sale_type', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sale_id, sale_member_id, sale_currency, sale_date, sale_money, sale_remain_currency, sale_status, sale_verify_date,sale_type', 'safe', 'on'=>'search'),
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
			'sale_member_id' => t('epmms','会员'),
			'sale_currency' => t('epmms','金额'),
			'sale_date' => t('epmms','日期'),
			'sale_money' => t('epmms','金额'),
			'sale_remain_currency' => t('epmms','剩余金额'),
			'sale_status' => t('epmms','状态'),
			'sale_verify_date' => t('epmms','配对日期'),
			'sale_tax'=>'手续费',
			'sale_dup'=>'重复消费',
			'sale_aixin'=>'爱心基金',
            'sale_type'=>'交易方式'
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
		$criteria->compare('sale_currency',$this->sale_currency);
		$criteria->compare('sale_date',$this->sale_date,true);
		$criteria->compare('sale_money',$this->sale_money);
		$criteria->compare('sale_remain_currency',$this->sale_remain_currency);
		$criteria->compare('sale_status',$this->sale_status);
        $criteria->compare('sale_type',$this->sale_type);
		$criteria->compare('sale_verify_date',$this->sale_verify_date,true);
		$criteria->compare('"saleMember".memberinfo_account',@$this->saleMember->memberinfo_account);
		$criteria->with=array('saleMember','saleMember.membermap.membermapRecommend.memberinfo');

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
                'relations' => ['saleMember']
            ));
        } else
        {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => $sort,
            ));
        }
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
// 		$finance=Finance::getMemberFinance($this->sale_member_id,3);
// 		if($finance->finance_award>=$this->sale_currency && $finance->deduct($this->sale_currency))
// 		{
// /*			$finance=Finance::getMemberFinance($this->sale_member_id,5);
// 			$finance->add(abs($this->sale_dup));*/
// 			return true;
// 		}
/*		switch($this->sale_currency)
        {
            case 3000:
                $debuct_currency=1;
                break;
            default:
                $debuct_currency=$this->sale_currency/5000;
                break;
        }*/
/*        if($this->sale_type==1)
        {
            $debuct_currency=$this->sale_currency;
            $finance=Finance::getMemberFinance($this->sale_member_id,2);
            if($finance->finance_award>=$debuct_currency && $finance->deduct($debuct_currency))
            {
                return true;
            }
            else
                return false;
        }*/

		//throw new EError('余额不足');
        return true;

	}
	public function deal1()
    {
        $connection=webapp()->db;
/*        $sales=Sale::model()->findAll(['condition'=>'sale_status=0','order'=>'sale_date asc']);
        foreach($sales as $sale)
        {
            $command=$connection->createCommand("select setdeal_two({$sale->sale_id});");
            $command->execute();
        }*/
        $buys=Buy::model()->findAll(['condition'=>'buy_status<2','order'=>'buy_date asc']);
        foreach($buys as $buy)
        {
            $command=$connection->createCommand("select setdeal_one({$buy->buy_id});");
            $command->execute();
        }
        Deal::dealConfirm();
        return true;
    }
	public function delete()
	{
		$member=$this->saleMember;
		$finance=$member->getFinance(2);
        switch($this->sale_currency)
        {
            case 3000:
                $debuct_currency=1;
                break;
            default:
                $debuct_currency=$this->sale_currency/5000;
                break;
        }
		$finance->add($debuct_currency);
		return parent::delete();
	}
}
