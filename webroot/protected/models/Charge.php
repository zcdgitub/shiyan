<?php

/**
 * This is the model class for table "{{charge}}".
 *
 * The followings are the available columns in table '{{charge}}':
 * @property string $charge_id
 * @property string $charge_sn
 * @property string $charge_memberinfo_id
 * @property string $charge_currency
 * @property integer $charge_is_verify
 * @property string $charge_add_date
 * @property string $charge_bank_id
 * @property string $charge_bank_account
 * @property string $charge_bank_address
 * @property string $charge_bank_sn
 * @property string $charge_bank_date
 * @property string $charge_bank_account_name
 * @property string $charge_finance_type_id
 * @property string $charge_remark
 * @property string $charge_verify_date
 * @property integer $charge_type
 *
 * The followings are the available model relations:
 * @property  * @property  * @property  */
class Charge extends Model
{
	public $modelName='充值';
	public $nameColumn='charge_sn';
	public $sum_currency;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Charge the static model class
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
		return '{{charge}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('charge_add_date, charge_bank_address, charge_bank_sn, charge_bank_date, charge_bank_account_name, charge_finance_type_id, charge_remark, charge_verify_date', 'filter','filter'=>array($this,'empty2null')),
			array('charge_memberinfo_id, charge_currency, charge_bank_id, charge_bank_account,charge_bank_account_name,charge_finance_type_id', 'required','on'=>'create'),
			array('charge_memberinfo_id, charge_currency,charge_finance_type_id', 'required','on'=>'pay'),
			array('charge_is_verify,charge_type', 'numerical', 'integerOnly'=>true),
			array('charge_sn', 'length', 'max'=>10),
			array('charge_add_date', 'length'),
			array('charge_bank_account', 'length', 'max'=>20),
			array('charge_bank_account_name', 'length', 'max'=>50),
			array('charge_memberinfo_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id','allowEmpty'=>false,'criteria'=>['condition'=>'memberinfo_is_enable=1'],'message'=>'输入的会员不存在或已锁定。'),
			array('charge_finance_type_id', 'exist', 'className'=>'FinanceType','attributeName'=>'finance_type_id','criteria'=>['condition'=>'finance_type_charge=1']),
			array('charge_bank_id', 'exist', 'className'=>'Bank','attributeName'=>'bank_id'),
			array('charge_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'sign'=>0,'allowZero'=>false),
			array('charge_bank_address, charge_bank_sn, charge_bank_date, charge_finance_type_id, charge_remark, charge_verify_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('charge_id, charge_sn, charge_memberinfo_id, charge_currency, charge_is_verify, charge_add_date, charge_bank_id, charge_bank_account, charge_bank_address, charge_bank_sn, charge_bank_date, charge_bank_account_name, charge_finance_type_id, charge_remark, charge_verify_date', 'safe', 'on'=>'search'),
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
			'chargeMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'charge_memberinfo_id'),
			'chargeFinanceType' => array(Model::BELONGS_TO, 'FinanceType', 'charge_finance_type_id'),
			'chargeBank' => array(Model::BELONGS_TO, 'Bank', 'charge_bank_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'charge_id' => 'Charge id',
			'charge_sn' =>t('epmms', '充值流水号'),
			'charge_memberinfo_id' =>t('epmms', '充值会员'),
			'charge_currency' =>t('epmms','充值金额'),
			'charge_is_verify' =>t('epmms',  '审核状态'),
			'charge_add_date' =>t('epmms',  '申请时间'),
			'charge_bank_id' =>t('epmms',  '汇款银行'),
			'charge_bank_account' =>t('epmms', '银行帐号'),
			'charge_bank_address' =>t('epmms', '银行地址'),
			'charge_bank_sn' =>t('epmms', '银行流水号'),
			'charge_bank_date' =>t('epmms', '汇款日期'),
			'charge_bank_account_name' =>t('epmms', '银行户名'),
			'charge_finance_type_id' =>t('epmms', '帐户类型'),
			'charge_remark' =>t('epmms',  '备注'),
			'charge_verify_date' => t('epmms','审核日期'),
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
        $sort=new Sort('Charge');
        $sort->defaultOrder=array('charge_id'=>Sort::SORT_DESC);
        $criteria=new CDbCriteria;
        $criteria->compare('charge_id',$this->charge_id,false);
        $criteria->compare('charge_sn',$this->charge_sn,false);
        $criteria->compare('charge_currency',$this->charge_currency,false);
        $criteria->compare('charge_is_verify',$this->charge_is_verify);
        $criteria->compare('charge_type',$this->charge_type);
        $criteria->compare('charge_add_date',$this->charge_add_date,false);
        $criteria->compare('charge_bank_account',$this->charge_bank_account,false);
        $criteria->compare('charge_bank_address',$this->charge_bank_address,true);
        $criteria->compare('charge_bank_sn',$this->charge_bank_sn,false);
        $criteria->compare('charge_bank_date',$this->charge_bank_date,false);
        $criteria->compare('charge_bank_account_name',$this->charge_bank_account_name,false);
        $criteria->compare('charge_remark',$this->charge_remark,true);
        $criteria->compare('charge_verify_date',$this->charge_verify_date,false);
        $criteria->compare('charge_memberinfo_id',@$this->charge_memberinfo_id);
        $criteria->compare('charge_finance_type_id',$this->charge_finance_type_id);
        $criteria->compare('charge_bank_id',$this->charge_bank_id);
        $criteria->compare('"chargeMemberinfo".memberinfo_account',@$this->chargeMemberinfo->memberinfo_account);
        $criteria->compare('"chargeFinanceType".finance_type_name',@$this->chargeFinanceType->finance_type_name);
        $criteria->compare('"chargeBank".bank_name',@$this->chargeBank->bank_name);
        $criteria->with=array('chargeMemberinfo','chargeFinanceType','chargeBank');
        return new JSonActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'relations'=>['chargeMemberinfo','chargeFinanceType','chargeBank']
        ));
	}

	/**
	 * 审核充值审请
	 * @return bool
	 * @throws Error
	 */
	public function verify()
	{
		if($this->charge_is_verify==1)
		{
			throw new Error('不能重复审核');
			return false;
		}
		if($this->charge_currency<=0)
		{
			throw new Error('金额不符合要求');
			return false;
		}
		$bak=new Backup();
		if(!$bak->autoBackup('充值:'.$this->chargeMemberinfo->memberinfo_account,'充值时间：'.webapp()->format->formatdatetime(time()) . ' 充值金额:' . $this->charge_currency))
		{
			throw new Error('备份失败');
		}
		$transaction=webapp()->db->beginTransaction();
		$finance=Finance::getMemberFinance($this->charge_memberinfo_id,$this->charge_finance_type_id);
		if($finance->add($this->charge_currency))
		{
			$this->charge_is_verify=1;
			$this->charge_verify_date=new CDbExpression('now()');
			$this->saveAttributes(['charge_is_verify','charge_verify_date']);
			$transaction->commit();
		}
		else
		{
			$transaction->rollback();
			return false;
		}

		return true;
	}
	public function payVerify()
	{
		if($this->charge_is_verify==1)
		{
			return false;
		}
		if($this->charge_currency<=0)
		{
			return false;
		}
		$transaction=webapp()->db->beginTransaction();
		$finance=Finance::getMemberFinance($this->charge_memberinfo_id,$this->charge_finance_type_id);
		if($finance->add($this->charge_currency))
		{
			$this->charge_is_verify=1;
			$this->charge_verify_date=new CDbExpression('now()');
			$this->saveAttributes(['charge_is_verify','charge_verify_date']);
			$transaction->commit();
		}
		else
		{
			$transaction->rollback();
			return false;
		}

		return true;
	}

	/**
	 * 已审核的不能删除
	 * @return bool
	 */
	public function delete()
	{
		if($this->charge_is_verify==1)
		{
			return false;
		}
		return parent::delete();
	}
}