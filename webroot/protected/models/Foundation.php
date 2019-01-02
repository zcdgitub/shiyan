<?php

/**
 * This is the model class for table "{{foundation}}".
 *
 * The followings are the available columns in table '{{foundation}}':
 * @property integer $foundation_id
 * @property integer $foundation_member_id
 * @property string $foundation_currency
 * @property string $foundation_tax
 * @property string $foundation_real_currency
 * @property string $foundation_reamrk
 * @property integer $foundation_is_verify
 * @property string $foundation_add_date
 * @property string $foundation_verify_date
 */
class Foundation extends Model
{
	//模型标题
	public $modelName='信用基金申请';
	//命名字段
	public $nameColumn='foundation_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{foundation}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('foundation_reamrk, foundation_is_verify, foundation_verify_date', 'filter','filter'=>array($this,'empty2null')),
			array('foundation_member_id, foundation_currency, foundation_tax, foundation_real_currency, foundation_add_date', 'required'),
			array('foundation_member_id, foundation_is_verify', 'numerical', 'integerOnly'=>true),
			array('foundation_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('foundation_currency, foundation_tax, foundation_real_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('foundation_currency','ext.validators.AbleFoundation'),
			array('foundation_reamrk, foundation_verify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('foundation_id, foundation_member_id, foundation_currency, foundation_tax, foundation_real_currency, foundation_reamrk, foundation_is_verify, foundation_add_date, foundation_verify_date', 'safe', 'on'=>'search'),
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
			'foundationMember' => array(Model::BELONGS_TO, 'Memberinfo', 'foundation_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'foundation_id' => t('epmms','Foundation'),
			'foundation_member_id' => t('epmms','会员'),
			'foundation_currency' => t('epmms','申请金额'),
			'foundation_tax' => t('epmms','手续费'),
			'foundation_real_currency' => t('epmms','实发金额'),
			'foundation_reamrk' => t('epmms','备注'),
			'foundation_is_verify' => t('epmms','审核状态'),
			'foundation_add_date' => t('epmms','申请时间'),
			'foundation_verify_date' => t('epmms','审核时间'),
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

		$sort=new Sort('Foundation');
		$sort->defaultOrder=array('foundation_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('foundation_id',$this->foundation_id);
		$criteria->compare('foundation_member_id',$this->foundation_member_id);
		$criteria->compare('foundation_currency',$this->foundation_currency,true);
		$criteria->compare('foundation_tax',$this->foundation_tax,true);
		$criteria->compare('foundation_real_currency',$this->foundation_real_currency,true);
		$criteria->compare('foundation_reamrk',$this->foundation_reamrk,true);
		$criteria->compare('foundation_is_verify',$this->foundation_is_verify);
		$criteria->compare('foundation_add_date',$this->foundation_add_date,true);
		$criteria->compare('foundation_verify_date',$this->foundation_verify_date,true);
		$criteria->compare('"foundationMember".memberinfo_account',@$this->foundationMember->memberinfo_account);
		$criteria->with=array('foundationMember');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
	public function verify()
	{
		$status=SystemStatus::model()->find();
		if($this->foundation_real_currency<=$status->system_status_foundation)
		{
			$transaction=webapp()->db->beginTransaction();
			$status->system_status_foundation=$status->system_status_foundation-$this->foundation_real_currency;
			$status->saveAttributes(['system_status_foundation']);
			$this->foundation_is_verify=1;
			$finance_fund=Finance::getMemberFinance($this->foundation_member_id,3);
			if(!$finance_fund->deduct($this->foundation_currency/100))
			{
				$transaction->rollback();
				throw new Error(t('epmms','扣除积分失败'));
			}
			$this->foundation_verify_date=new CDbExpression('now()');
			$this->saveAttributes(['foundation_is_verify','foundation_verify_date']);
			Yii::import('ext.award.MySystemFoundation');
			$mysystem=new MySystemFoundation($this->foundationMember->membermap);
			$mysystem->run(1,1,1,$this->foundation_currency);
			$transaction->commit();
			return true;
		}
		else
		{
			throw new Error('信用基金余额不足');
		}
		return false;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Foundation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
