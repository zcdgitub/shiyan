<?php

/**
 * This is the model class for table "{{finance}}".
 *
 * The followings are the available columns in table '{{finance}}':
 * @property string $finance_id
 * @property string $finance_award
 * @property string $finance_mod_date
 * @property integer $finance_type
 * @property string $finance_memberinfo_id
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Finance extends Model
{
	public $modelName='财务';
	public $nameColumn='finance_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Finance the static model class
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
		return '{{finance}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('finance_award, finance_mod_date, finance_type, finance_memberinfo_id', 'filter','filter'=>array($this,'empty2null')),
			array('finance_type', 'numerical', 'integerOnly'=>true),
			//array('finance_award', 'length', 'max'=>16),
			array('finance_id', 'unique'),
			array('finance_memberinfo_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('finance_memberinfo_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('finance_id, finance_award, finance_mod_date, finance_type, finance_memberinfo_id', 'safe', 'on'=>'search'),
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
			'financeType' => array(Model::BELONGS_TO, 'FinanceType', 'finance_type'),
			'financeMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'finance_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'finance_id' => 'Finance',
			'finance_award' => t('epmms','金额'),
			'finance_mod_date' => t('epmms','修改日期'),
			'finance_type' => t('epmms','帐户类型'),
			'finance_memberinfo_id' => t('epmms','会员帐号'),
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
        $sort=new Sort('Finance');
        $sort->defaultOrder=array('finance_award'=>Sort::SORT_DESC);
        $criteria=new CDbCriteria;
        $criteria->compare('finance_id',$this->finance_id);
        $criteria->compare('finance_award',$this->finance_award);
        $criteria->compare('finance_type',$this->finance_type);
        $criteria->compare('finance_memberinfo_id',$this->finance_memberinfo_id);
        $criteria->compare('finance_mod_date',$this->finance_mod_date);
        $criteria->compare('"financeType".finance_type_id',@$this->financeType->finance_type_id);
        $criteria->compare('"financeType".finance_type_name',@$this->financeType->finance_type_name);
        $criteria->compare('"financeMemberinfo".memberinfo_account',@$this->financeMemberinfo->memberinfo_account);
        $criteria->with=array('financeType','financeMemberinfo');
        return new JSonActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'relations'=>['financeType','financeMemberinfo']
        ));
	}
	/**
	 * 扣除电子币
	 * @param mixed $amount
	 * @return boolean true扣除成功,false,扣除失败或电子币不足
	 */
	public function deduct($amount)
	{

        if($amount<0){
        	throw new Error('不能为负值');
        }
		if($this->finance_award>=$amount)
		{
			
			/*
			$bak=new Backup();
			if(!$bak->autoBackup('账户扣款:'.$this->financeMemberinfo->memberinfo_account,'扣款时间：'.webapp()->format->formatdatetime(time()) . "\r\n" . '金额:' . $amount . "\r\n" . '类型:' . $this->financeType->showName))
			{
				throw new Error('备份失败');
			}*/
			$transaction=webapp()->db->beginTransaction();
			try
			{

				$this->finance_award=new CDbExpression('finance_award-:amount',array(':amount'=>$amount));
				if($this->save())
				{
					
					$transaction->commit();
					$this->refresh();
					LogFilter::log(['category'=>'finance','source'=>'财务','operate'=>'扣除','user'=>$this->financeMemberinfo->memberinfo_account,'role'=>null,'target'=>$this->financeMemberinfo->memberinfo_account,'status'=>LogFilter::SUCCESS,'info'=>$this->financeType->finance_type_name,'value'=>$amount]);
					return true;
				}
				else
				{
					$transaction->rollback();
					return false;
				}
				$transaction->commit();
				$this->refresh();
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				$this->refresh();
				return false;
			}
			return true;
		}
		else{
			return false;
		}

	}
	/**
	 * 增加电子币
	 * @param mixed $amount
	 * @return boolean true成功,false,失败
	 */
	public function add($amount)
	{


		$transaction=webapp()->db->beginTransaction();
		try
		{
			$this->finance_award=new CDbExpression('finance_award+:amount',array(':amount'=>$amount));
			if($this->save())
			{
				$transaction->commit();
				$this->refresh();
				LogFilter::log(['category'=>'finance','source'=>'财务','operate'=>'增加','user'=>$this->financeMemberinfo->memberinfo_account,'role'=>null,'target'=>$this->financeMemberinfo->memberinfo_account,'status'=>LogFilter::SUCCESS,'info'=>$this->financeType->finance_type_name,'value'=>$amount]);
				return true;
			}
			else
			{
				$transaction->rollback();
				$this->refresh();
				return false;
			}

		}
		catch(CDbException $e)
		{
			$transaction->rollback();
			throw $e;
			return false;
		}
		return true;
	}

	/**
	 * 取得Finance模型
	 * @param $memberinfo_id
	 * @param int $type
	 * @return Finance|object
	 */
	public static function getMemberFinance($memberinfo_id,$type=1)
	{
		

		$finance=Finance::model()->find('finance_memberinfo_id=:memberinfo_id and finance_type=:type',[':memberinfo_id'=>$memberinfo_id,':type'=>$type]);
		/*var_dump($finance->toArray());
		*/
	/*	echo "<pre>";
		var_dump($finance->finance_award);
		die;*/
		if(!is_object($finance))
		{
			$finance=new Finance();
			$finance->finance_memberinfo_id=$memberinfo_id;
			$finance->finance_type=$type;
			$finance->save();
		}
		return $finance;
	}

	/**
	 * 转帐
	 * @param $member_src
	 * @param $type_src
	 * @param $member_dst
	 * @param $type_dst
	 * @param $currency
	 * @return boolean true成功,false,失败
	 */
	public static function transfer($member_src,$type_src,$member_dst,$type_dst,$currency)
	{
		$transaction=webapp()->db->beginTransaction();
		try
		{
			$finance_src=Finance::model()->getMemberFinance($member_src,$type_src);
			$finance_dst=Finance::model()->getMemberFinance($member_dst,$type_dst);
			if($finance_src->finance_award<0 || $finance_src->finance_award<$currency)
			{
				$transaction->rollback();
				throw new Error('余额不足');
				return false;
			}
			$finance_src->finance_award=new CDbExpression('finance_award-:currency',[':currency'=>$currency]);
			$finance_dst->finance_award=new CDbExpression('finance_award+:currency',[':currency'=>$currency]);
			if($finance_src->save() && $finance_dst->save())
			{
				$transaction->commit();
				$finance_src->refresh();
				$finance_dst->refresh();
				LogFilter::log(['category'=>'finance','source'=>'财务','operate'=>'转账','user'=>$finance_src->financeMemberinfo->memberinfo_account,'role'=>null,'target'=>$finance_dst->financeMemberinfo->memberinfo_account,'status'=>LogFilter::SUCCESS,'info'=>"转出：{$finance_dst->financeType->finance_type_name}",'value'=>$currency]);
				LogFilter::log(['category'=>'finance','source'=>'财务','operate'=>'转账','user'=>$finance_dst->financeMemberinfo->memberinfo_account,'role'=>null,'target'=>$finance_src->financeMemberinfo->memberinfo_account,'status'=>LogFilter::SUCCESS,'info'=>"转入：{$finance_src->financeType->finance_type_name}",'value'=>$currency]);
				return true;
			}
			else
			{
				$transaction->rollback();
				return false;
			}
			$transaction->commit();
		}
		catch(CDbException $e)
		{
			$transaction->rollback();
			throw $e;
			return false;
		}

		return true;
	}
	/**
	 * 转帐扣手续费
	 * @param $member_src
	 * @param $type_src
	 * @param $member_dst
	 * @param $type_dst
	 * @param $currency
	 * @return boolean true成功,false,失败
	 */
	public static function transfer2($member_src,$type_src,$member_dst,$type_dst,$currency)
	{
		$transaction=webapp()->db->beginTransaction();
		try
		{
			$finance_src=Finance::model()->getMemberFinance($member_src,$type_src);
			$finance_dst=Finance::model()->getMemberFinance($member_dst,$type_dst);
			$fee=0;
			if($type_src==1 && $type_dst==2)
			{
				$fee=$currency*0.1;
			}
			if($finance_src->finance_award<0 || $finance_src->finance_award<$currency+$fee)
			{
				$transaction->rollback();
				throw new Error('余额不足');
				return false;
			}
			$finance_src->finance_award=new CDbExpression('finance_award-:currency',[':currency'=>$currency+$fee]);
			$finance_dst->finance_award=new CDbExpression('finance_award+:currency',[':currency'=>$currency]);
			if($finance_src->save() && $finance_dst->save())
			{
				$transaction->commit();
				$finance_src->refresh();
				$finance_dst->refresh();
				LogFilter::log(['category'=>'finance','source'=>'财务','operate'=>'转账','user'=>$finance_src->financeMemberinfo->memberinfo_account,'role'=>null,'target'=>$finance_dst->financeMemberinfo->memberinfo_account,'status'=>LogFilter::SUCCESS,'info'=>"转出：{$finance_dst->financeType->finance_type_name}",'value'=>$currency]);
				if($fee>0)
				{
					LogFilter::log(['category'=>'finance','source'=>'财务','operate'=>'转账手续费','user'=>$finance_src->financeMemberinfo->memberinfo_account,'role'=>null,'target'=>$finance_dst->financeMemberinfo->memberinfo_account,'status'=>LogFilter::SUCCESS,'info'=>"转出：{$finance_dst->financeType->finance_type_name}",'value'=>-$fee]);
				}
				LogFilter::log(['category'=>'finance','source'=>'财务','operate'=>'转账','user'=>$finance_dst->financeMemberinfo->memberinfo_account,'role'=>null,'target'=>$finance_src->financeMemberinfo->memberinfo_account,'status'=>LogFilter::SUCCESS,'info'=>"转入：{$finance_src->financeType->finance_type_name}",'value'=>$currency]);
				return true;
			}
			else
			{
				$transaction->rollback();
				return false;
			}
			$transaction->commit();
		}
		catch(CDbException $e)
		{
			$transaction->rollback();
			throw $e;
			return false;
		}
		return true;
	}
	/**
	 * 根据会员和汇总组查询财务记录
	 * @param $member_id
	 * @param null $sumtype_id 如果为null则为第1个可提现财务类型
	 */
	public static function getFinanceSumType($member_id,$sumtype_id=null)
	{
		if(is_null($sumtype_id))
			$sumtype_id=FinanceType::model()->find(['condition'=>'finance_type_withdrawals=1','order'=>'finance_type_id'])->finance_type_id;
		$finance=Finance::model()->findByAttributes(['finance_memberinfo_id'=>$member_id,'finance_type'=>$sumtype_id]);
		return $finance;
	}
}