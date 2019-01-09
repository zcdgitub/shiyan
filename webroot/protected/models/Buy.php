<?php

/**
 * This is the model class for table "{{buy}}".
 *
 * The followings are the available columns in table '{{buy}}':
 * @property integer $buy_id
 * @property integer $buy_member_id
 * @property string $buy_currency
 * @property string $buy_date
 * @property string $buy_money
 * @property integer $buy_status
 * @property string $buy_tax
 * @property string $buy_real_currency
 * @property string $buy_remain_currency
 * @property string $buy_type
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Buy extends Model
{
	//模型标题
	public $modelName='接受记录';
	//命名字段
	public $nameColumn='buy_id';
	public $password2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{buy}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buy_member_id, buy_currency, buy_date, buy_money, buy_status, buy_tax, buy_real_currency', 'filter','filter'=>array($this,'empty2null')),
			array('buy_member_id,buy_currency,buy_type', 'required','message'=>'输入不正确或不可为空'),
             array('password2', 'ext.validators.Password'),
            array('password2', 'ext.validators.TradePassword', 'allowEmpty'=>false),
			//array('buy_member_id, buy_status,buy_type', 'numerical', 'integerOnly'=>true),
			array('buy_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id','message'=>'账户不正确'),
			array('buy_currency, buy_money, buy_real_currency,buy_remain_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'sign'=>0),
			array('buy_currency','ext.validators.AbleBuy2'),
            array('buy_type', 'in', 'range'=>[0,1]),
			array('buy_tax', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('buy_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('buy_id, buy_member_id, buy_currency, buy_date, buy_money, buy_status, buy_tax, buy_real_currency,buy_type', 'safe', 'on'=>'search'),
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
			'buyMember' => array(Model::BELONGS_TO, 'Memberinfo', 'buy_member_id'),
			'deals' => array(Model::HAS_MANY, 'Deal', 'deal_buy_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'buy_id' => t('epmms','Buy'),
			'buy_member_id' => t('epmms','会员'),
			'buy_currency' => t('epmms','金额'),
			'buy_date' => t('epmms','日期'),
			'buy_money' => t('epmms','现金'),
			'buy_status' => t('epmms','状态'),
			'buy_tax' => t('epmms','手续费'),
			'buy_real_currency' => t('epmms','实际买入'),
			'buy_type'=>'交易方式',
            'buy_remain_currency'=>t('epmms','剩余金额'),
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

		$sort=new Sort('Buy');
		$sort->defaultOrder=array('buy_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;

		$criteria->compare('buy_id',$this->buy_id);
		$criteria->compare('buy_member_id',$this->buy_member_id);
		$criteria->compare('buy_currency',$this->buy_currency);
		$criteria->compare('buy_date',$this->buy_date,true);
		$criteria->compare('buy_money',$this->buy_money);
		$criteria->compare('buy_status',$this->buy_status);
		$criteria->compare('buy_tax',$this->buy_tax);
		$criteria->compare('buy_real_currency',$this->buy_real_currency);
		$criteria->compare('"buyMember".memberinfo_account',@$this->buyMember->memberinfo_account);
		$criteria->with=array('buyMember','buyMember.membermap.membermapRecommend.memberinfo');

        if(webapp()->request->isAjaxRequest)
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
                'relations' => ['buyMember']
            ));
        }
        else
        {
            return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
            ));
        }
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Buy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function verify()
	{
		if($this->buy_status>0)
		{
			return false;
		}
		if($this->buy_type==1)
        {

        }
        $finance=Finance::getMemberFinance(user()->id,3);//店补积分减少
		if($finance->deduct($this->buy_currency) )
		{
			//$this->buy_status=1;
			//$this->saveAttributes(['buy_status']);
			//配对
/*			$connection=webapp()->db;
			$command=$connection->createCommand("select setdeal({$this->buy_id});");
			$command->execute();
			$deals=Deal::model()->findAll('deal_buy_id=:id',[':id'=>$this->buy_id]);
			foreach($deals as $deal)
			{
				$faward=Finance::getMemberFinance($deal->dealSale->sale_member_id,1);
				$faward->add($deal->deal_currency*getAwardConfig(351));
			}*/
			return true;
		}
		else
		{
			throw new EError('金币余额不足');
			return false;
		}
	}
    public function deal()
    {
        $connection=webapp()->db;
        $buys=Buy::model()->findAll(['condition'=>'buy_status<2','order'=>'buy_date asc']);
        foreach($buys as $buy)
        {
            $command=$connection->createCommand("select setdeal_one({$buy->buy_id});");
            $command->execute();
        }
/*        $sales=Sale::model()->findAll(['condition'=>'sale_status=0','order'=>'sale_date asc']);
        foreach($sales as $sale)
        {
            $command=$connection->createCommand("select setdeal_two({$sale->sale_id});");
            $command->execute();
        }*/
        Deal::dealConfirm();
        return true;
    }
	public static function getAllFenhong($member_id)
	{
		$all=new DbEvaluate(" coalesce(sum(buy_currency),0) as fenhong from epmms_buy where buy_member_id=:id",[':id'=>$member_id]);
		$reg_fenhong=user()->map->membermap_jifen;
		return $all->run() + $reg_fenhong;
	}
	public function delete()
	{
		$member=$this->buyMember;
		$finance=$member->getFinance($this->buy_type==0?2:2);
		$finance->add($this->buy_currency);
		return parent::delete();
	}

}
