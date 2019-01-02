<?php

/**
 * This is the model class for table "{{withdrawals}}".
 *
 * The followings are the available columns in table '{{withdrawals}}':
 * @property string $withdrawals_id
 * @property string $withdrawals_member_id
 * @property string $withdrawals_currency
 * @property string $withdrawals_add_date
 * @property integer $withdrawals_is_verify
 * @property string $withdrawals_verify_date
 * @property string $withdrawals_remark
 * @property string $withdrawals_finance_type_id
 * @property string $withdrawals_sn
 * @property string $withdrawals_tax
 * @property string $withdrawals_real_currency
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Withdrawals extends Model
{
	public $modelName='提款申请';
	public $nameColumn='withdrawals_sn';
	public $withdrawals_finance_type_id=1;
	 public $password2;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Withdrawals the static model class
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
		return '{{withdrawals}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('withdrawals_add_date, withdrawals_is_verify, withdrawals_verify_date, withdrawals_remark', 'filter','filter'=>array($this,'empty2null')),
		//	array('withdrawals_member_id, withdrawals_currency, withdrawals_finance_type_id', 'required'),
			array('withdrawals_is_verify', 'numerical', 'integerOnly'=>true),
			array('withdrawals_sn', 'length', 'max'=>10),
			array('withdrawals_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id','allowEmpty'=>false,'criteria'=>['condition'=>'memberinfo_is_enable=1'],'message'=>'输入的会员不存在或已锁定。'),
			array('withdrawals_finance_type_id', 'exist', 'className'=>'FinanceType','attributeName'=>'finance_type_id'),
			array('withdrawals_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('withdrawals_currency ', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'sign'=>0,'allowZero'=>false),
			array('withdrawals_currency','ext.validators.AbleWithdrawals'),
			//array('withdrawals_currency','ext.validators.AbleWithdrawals280'),
			array('withdrawals_currency','ext.validators.DecimalScale','scale'=>config('withdrawals','scale')),
			array('withdrawals_currency', 'numerical','min'=>config('withdrawals','min')),
			array('withdrawals_add_date, withdrawals_verify_date, withdrawals_remark', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('withdrawals_id, withdrawals_member_id, withdrawals_currency, withdrawals_add_date, withdrawals_is_verify, withdrawals_verify_date, withdrawals_remark, withdrawals_finance_type_id, withdrawals_sn', 'safe', 'on'=>'search'),
			array('password2', 'ext.validators.Password'),
            array('password2', 'ext.validators.TradePassword', 'allowEmpty'=>true),
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
			'withdrawalsMember' => array(Model::BELONGS_TO, 'Memberinfo', 'withdrawals_member_id'),
			'withdrawalsFinanceType' => array(Model::BELONGS_TO, 'FinanceType', 'withdrawals_finance_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'withdrawals_id' => 'Withdrawals',
			'withdrawals_member_id' => t('epmms','会员'),
			'withdrawals_currency' => t('epmms','金额'),
			'withdrawals_add_date' => t('epmms','申请日期'),
			'withdrawals_is_verify' => t('epmms','审核状态'),
			'withdrawals_verify_date' => t('epmms','审核日期'),
			'withdrawals_remark' => t('epmms','备注'),
			'withdrawals_finance_type_id' => t('epmms','帐户类型'),
			'withdrawals_sn' => t('epmms','提现流水号'),
			'withdrawals_tax'=>t('epmms','手续费'),
			'withdrawals_real_currency'=>config('withdrawals','type')==1?t('epmms','实收金额'):t('epmms','账户扣除')
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
        $sort=new Sort('Withdrawals');
        $sort->defaultOrder=array('withdrawals_id'=>Sort::SORT_DESC);
        $criteria=new CDbCriteria;
        $criteria->compare('withdrawals_id',$this->withdrawals_id);
        $criteria->compare('withdrawals_currency',$this->withdrawals_currency);
        $criteria->compare('withdrawals_add_date',$this->withdrawals_add_date);
        $criteria->compare('withdrawals_is_verify',$this->withdrawals_is_verify);
        $criteria->compare('withdrawals_verify_date',$this->withdrawals_verify_date);
        $criteria->compare('withdrawals_remark',$this->withdrawals_remark,true);
        $criteria->compare('withdrawals_sn',$this->withdrawals_sn);
        $criteria->compare('withdrawals_tax',$this->withdrawals_tax);
        $criteria->compare('withdrawals_real_currency',$this->withdrawals_real_currency);
        //$criteria->compare('withdrawals_jifen',$this->withdrawals_jifen);
        $criteria->compare('withdrawals_member_id',@$this->withdrawals_member_id);
        $criteria->compare('withdrawals_finance_type_id',@$this->withdrawals_finance_type_id);
        $criteria->compare('"withdrawalsMember".memberinfo_account',@$this->withdrawalsMember->memberinfo_account);
        $criteria->compare('"withdrawalsFinanceType".finance_type_name',@$this->withdrawalsFinanceType->finance_type_name);
        $criteria->with=array('withdrawalsMember','withdrawalsFinanceType');
        return new JSonActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'relations'=>['withdrawalsFinanceType']
        ));
	}

	/**
	 * 审核当前提款申请,成功返回true,失败抛出异常
	 * @return bool
	 * @throws Error
	 */
	public function verify()
	{
		$bak=new Backup();
		if(!$bak->autoBackup('提现:'.$this->withdrawalsMember->memberinfo_account,'提现时间：'.webapp()->format->formatdatetime(time()) . ' 提现金额:' . $this->withdrawals_currency))
		{
			throw new Error('备份失败');
		}
		$transaction=webapp()->db->beginTransaction();
	
		$finance=Finance::getMemberFinance($this->withdrawals_member_id,$this->withdrawals_finance_type_id);

		if($this->withdrawals_is_verify==1)
		{
			$transaction->rollback();
			throw new Error('不能重复审核。');
		}
		if($finance->finance_award<=0 || $this->deduct_money>$finance->finance_award)
		{
			$transaction->rollback();
			throw new Error('余额不足');
		}
		//提取现金并扣除手续费
		if($finance->deduct($this->deduct_money))
		{
			$transaction->commit();
		}
		else
		{
			$transaction->rollback();
			throw new Error('操作失败');
		}
		return true;
	}
	/**
	 * 审核当前提款申请,成功返回true,失败抛出异常
	 * @return bool
	 * @throws Error
	 */
	public function send()
	{
		$transaction=webapp()->db->beginTransaction();

		if($this->withdrawals_is_verify==1)
		{
			$transaction->rollback();
			throw new Error('不能重复审核。');
		}
		$status=SystemStatus::model()->find();
		$status->system_status_withdrawals=$status->system_status_withdrawals+$this->deduct_money;
		$status->save();
		$this->withdrawals_is_verify=1;
		$this->withdrawals_verify_date=new CDbExpression('now()');
		//提取现金并扣除手续费
		if($this->saveAttributes(array('withdrawals_is_verify','withdrawals_verify_date','withdrawals_tax')))
		{
			$transaction->commit();
		}
		else
		{
			$transaction->rollback();
			throw new Error('操作失败');
		}
		return true;
	}
	/**
	 * 发放提款
	 * @return bool
	 * @throws Error
	 */
	public function send2()
	{
		$transaction=webapp()->db->beginTransaction();

		if($this->withdrawals_is_verify==2)
		{
			$transaction->rollback();
			throw new Error('不能重复审核。');
		}
		$this->withdrawals_is_verify=2;
		$this->withdrawals_verify_date=new CDbExpression('now()');
		//提取现金并扣除手续费
		if($this->saveAttributes(array('withdrawals_is_verify')))
		{
			$transaction->commit();
		}
		else
		{
			$transaction->rollback();
			throw new Error('操作失败');
		}
		return true;
	}
	/**
	 * 已审核的不能删除
	 * @return bool
	 */
	public function delete()
	{
		if($this->withdrawals_is_verify==1)
		{
			return false;
		}
		$finance=Finance::getMemberFinance($this->withdrawals_member_id,$this->withdrawals_finance_type_id);
		if($finance->add($this->deduct_money))
			return parent::delete();
		else
			return false;
	}
	public function getDeduct_money()
	{
		return config('withdrawals','type')==1?$this->withdrawals_currency:$this->withdrawals_real_currency;
	}
}