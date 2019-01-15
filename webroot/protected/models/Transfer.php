<?php

/**
 * This is the model class for table "{{transfer}}".
 *
 * The followings are the available columns in table '{{transfer}}':
 * @property string $transfer_id
 * @property string $transfer_src_member_id
 * @property string $transfer_src_finance_type
 * @property string $transfer_dst_member_id
 * @property string $transfer_dst_finance_type
 * @property string $transfer_currency
 * @property string $transfer_remark
 * @property integer $transfer_is_verify
 * @property string $transfer_add_date
 * @property string $transfer_verify_date
 * @property string $transfer_sn
 * @property string $transfer_tax
 *
 * The followings are the available model relations:
 * @property  * @property  * @property  * @property  */
class Transfer extends Model
{
	public $modelName='转帐申请';
	public $nameColumn='transfer_sn';
	public $password2;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Transfer the static model class
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
		return '{{transfer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('transfer_remark, transfer_is_verify, transfer_add_date, transfer_verify_date', 'filter','filter'=>array($this,'empty2null')),
			//array('transfer_src_member_id, transfer_dst_member_id', 'exist', 'className'=>'Membermap','attributeName'=>'membermap_id','allowEmpty'=>false,'criteria'=>['condition'=>'membermap_is_verify=1'],'message'=>'输入的会员不存在或未审核。'),
			array('transfer_src_member_id, transfer_dst_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id','allowEmpty'=>false,'criteria'=>['condition'=>'memberinfo_is_enable=1'],'message'=>'输入的会员不存在或已锁定。'),
			array('transfer_src_member_id, transfer_src_finance_type, transfer_dst_member_id, transfer_dst_finance_type, transfer_currency', 'required'),
			array('transfer_dst_member_id', 'ext.validators.AbleTransfer'),
			array('transfer_src_member_id', 'ext.validators.AbleTransferSrcRole'),
			array('transfer_dst_member_id', 'ext.validators.AbleTransferDstRole'),
			array('transfer_src_finance_type', 'ext.validators.AbleTransferSrcType'),
			array('transfer_dst_finance_type', 'ext.validators.AbleTransferDstType'),
			array('transfer_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'sign'=>0,'allowZero'=>false),
			array('transfer_currency', 'ext.validators.TransferBalance'),
			array('transfer_tax', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'allowZero'=>true),
			array('transfer_is_verify', 'numerical', 'integerOnly'=>true),
			array('transfer_sn', 'length', 'max'=>10),
			array('transfer_sn', 'unique'),
			array('transfer_src_finance_type, transfer_dst_finance_type', 'exist', 'className'=>'FinanceType','attributeName'=>'finance_type_id'),

			array('transfer_remark, transfer_add_date, transfer_verify_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('transfer_id, transfer_src_member_id, transfer_src_finance_type, transfer_dst_member_id, transfer_dst_finance_type, transfer_currency, transfer_remark, transfer_is_verify, transfer_add_date, transfer_verify_date, transfer_sn', 'safe', 'on'=>'search'),
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
			'transferSrcMember' => array(Model::BELONGS_TO, 'Memberinfo', 'transfer_src_member_id'),
			'transferSrcFinanceType' => array(Model::BELONGS_TO, 'FinanceType', 'transfer_src_finance_type'),
			'transferDstMember' => array(Model::BELONGS_TO, 'Memberinfo', 'transfer_dst_member_id'),
			'transferDstFinanceType' => array(Model::BELONGS_TO, 'FinanceType', 'transfer_dst_finance_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'transfer_id' => 'Transfer',
			'transfer_src_member_id' => t('epmms','转出会员'),
			'transfer_src_finance_type' => t('epmms','转出帐户类型'),
			'transfer_dst_member_id' => t('epmms','转入会员'),
			'transfer_dst_finance_type' => t('epmms','转入帐户类型'),
			'transfer_currency' => t('epmms','转帐金额'),
			'transfer_remark' => t('epmms','备注'),
			'transfer_is_verify' => t('epmms','审核状态'),
			'transfer_add_date' => t('epmms','申请日期'),
			'transfer_verify_date' => t('epmms','审核日期'),
			'transfer_sn' => t('epmms','流水号'),
			'transfer_tax' => t('epmms','手续费')
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
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $sort=new Sort('Transfer');
        $sort->defaultOrder=array('transfer_id'=>Sort::SORT_DESC);
        $criteria=new CDbCriteria;
        $criteria->compare('transfer_id',$this->transfer_id,false);
        $criteria->compare('transfer_currency',$this->transfer_currency,false);
        $criteria->compare('transfer_tax',$this->transfer_currency,false);
        $criteria->compare('transfer_remark',$this->transfer_remark,true);
        $criteria->compare('transfer_is_verify',$this->transfer_is_verify);
        $criteria->compare('transfer_add_date',$this->transfer_add_date,false);
        $criteria->compare('transfer_verify_date',$this->transfer_verify_date,false);
        $criteria->compare('transfer_sn',$this->transfer_sn,false);
        $criteria->compare('transfer_src_member_id',$this->transfer_src_member_id);
        $criteria->compare('transfer_dst_member_id',$this->transfer_dst_member_id);

        $criteria->compare('transfer_src_finance_type',$this->transfer_src_finance_type);
        $criteria->compare('transfer_dst_finance_type',$this->transfer_dst_finance_type);
        $criteria->compare('"transferSrcMember".memberinfo_account',@$this->transferSrcMember->memberinfo_account);
        $criteria->compare('"transferSrcFinanceType".finance_type_name',@$this->transferSrcFinanceType->finance_type_name);
        $criteria->compare('"transferDstMember".memberinfo_account',@$this->transferDstMember->memberinfo_account);
        $criteria->compare('"transferDstFinanceType".finance_type_name',@$this->transferDstFinanceType->finance_type_name);

        if(!user()->isAdmin())
        {
            $criteria->addCondition('(transfer_src_member_id='. user()->id . ' or transfer_dst_member_id=' . user()->id . ')');
        }
        $criteria->with=array('transferSrcMember','transferSrcFinanceType','transferDstMember','transferDstFinanceType');

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
                'relations'=>['transferSrcMember','transferSrcFinanceType','transferDstMember','transferDstFinanceType'],
                'includeDataProviderInformation'=>true,
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
	 * 审核转帐申请
	 * @return bool
	 * @throws Error
	 */
	public function verify()
	{
		if($this->transfer_is_verify==1)
		{
			throw new Error('不能重复审核');
			return false;
		}
		if($this->transfer_currency<=0)
		{
			throw new Error('转帐金额不符合要求');
			return false;
		}
// 		var_dump($this->transfer_src_finance_type);
// 		var_dump($this->transfer_dst_finance_type);

// 		if($this->transfer_src_finance_type!==$this->transfer_dst_finance_type){
// 			echo 1;
// 			throw new Error('不同账户类型不能互转');
// 			return false;
// 		}else{
// 			echo 2;
// 		}
// die;
		$transaction=webapp()->db->beginTransaction();
		$fin=Finance::getMemberFinance($this->transfer_src_member_id,$this->transfer_src_finance_type);
		$fin->deduct(abs($this->transfer_tax));
		Finance::transfer($this->transfer_src_member_id,$this->transfer_src_finance_type,$this->transfer_dst_member_id,$this->transfer_dst_finance_type,$this->transfer_currency);
		$this->transfer_is_verify=1;
		$this->transfer_verify_date=new CDbExpression('now()');
		$this->saveAttributes(array('transfer_is_verify','transfer_verify_date'));
		$transaction->commit();
		return true;
	}

	/**
	 * 已审核的不能删除
	 * @return bool
	 */
	public function delete()
	{
		if($this->transfer_is_verify==1)
		{
			return false;
		}
		return parent::delete();
	}

}