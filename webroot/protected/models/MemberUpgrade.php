<?php

/**
 * This is the model class for table "{{member_upgrade}}".
 *
 * The followings are the available columns in table '{{member_upgrade}}':
 * @property integer $member_upgrade_id
 * @property integer $member_upgrade_member_id
 * @property integer $member_upgrade_old_type
 * @property integer $member_upgrade_type
 * @property integer $member_upgrade_is_verify
 * @property string $member_upgrade_add_date
 * @property string $member_upgrade_verify_date
 * @property integer $member_upgrade_period
 * @property integer $member_upgrade_money
 *
 * The followings are the available model relations:
 * @property  * @property  */
class MemberUpgrade extends Model
{
	//模型标题
	public $modelName='会员升级';
	//命名字段
	public $nameColumn='member_upgrade_id';
	public $password2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{member_upgrade}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_upgrade_is_verify, member_upgrade_add_date, member_upgrade_verify_date, member_upgrade_period', 'filter','filter'=>array($this,'empty2null')),
			array('member_upgrade_member_id, member_upgrade_type,member_upgrade_old_type', 'required'),
			array('member_upgrade_member_id, member_upgrade_type,member_upgrade_old_type, member_upgrade_is_verify, member_upgrade_period', 'numerical', 'integerOnly'=>true),
			array('member_upgrade_member_id', 'exist', 'className'=>'Membermap','attributeName'=>'membermap_id'),
			array('member_upgrade_money', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('member_upgrade_add_date, member_upgrade_verify_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('member_upgrade_id, member_upgrade_member_id, member_upgrade_type, member_upgrade_is_verify, member_upgrade_add_date, member_upgrade_verify_date, member_upgrade_period,member_upgrade_old_type', 'safe', 'on'=>'search'),
			array('password2', 'ext.validators.Password'),
            array('password2', 'ext.validators.TradePassword', 'allowEmpty'=>false),
		
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
			'memberUpgradeMember' => array(Model::BELONGS_TO, 'Membermap', 'member_upgrade_member_id'),
			'memberUpgradeType' => array(Model::BELONGS_TO, 'MemberType', 'member_upgrade_type'),
			'memberUpgradeOldType' => array(Model::BELONGS_TO, 'MemberType', 'member_upgrade_old_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'member_upgrade_id' => t('epmms','Member Upgrade'),
			'member_upgrade_member_id' => t('epmms','会员'),
			'member_upgrade_old_type' => t('epmms','原'),
			'member_upgrade_type' => t('epmms','升级到'),
			'member_upgrade_is_verify' => t('epmms','审核状态'),
			'member_upgrade_add_date' => t('epmms','申请日期'),
			'member_upgrade_verify_date' => t('epmms','审核日期'),
			'member_upgrade_period' => t('epmms','期次'),
			'member_upgrade_money' => t('epmms','升级金额')
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

		$sort=new Sort('MemberUpgrade');
		$sort->defaultOrder=array('member_upgrade_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('member_upgrade_id',$this->member_upgrade_id);
		$criteria->compare('member_upgrade_member_id',$this->member_upgrade_member_id);
		$criteria->compare('member_upgrade_type',$this->member_upgrade_type);
		$criteria->compare('member_upgrade_is_verify',$this->member_upgrade_is_verify);
		$criteria->compare('member_upgrade_add_date',$this->member_upgrade_add_date,true);
		$criteria->compare('member_upgrade_verify_date',$this->member_upgrade_verify_date,true);
		$criteria->compare('member_upgrade_period',$this->member_upgrade_period);
		$criteria->compare('"memberUpgradeMember".showName',@$this->memberUpgradeMember->showName);
		$criteria->compare('"memberUpgradeOldType".membertype_name',@$this->memberUpgradeOldType->membertype_name);
		$criteria->compare('"memberUpgradeType".membertype_name',@$this->memberUpgradeType->membertype_name);
		$criteria->with=array('memberUpgradeMember','memberUpgradeType');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MemberUpgrade the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function verify()
	{
		if($this->member_upgrade_is_verify==1)
			return false;
	
		$status=$this->memberUpgradeMember->upgrade($this->member_upgrade_type);


		if($status===EError::SUCCESS)
		{

			$this->refresh();
			$this->member_upgrade_is_verify=1;
			$this->member_upgrade_verify_date=new CDbExpression('now()');
			$this->save();
			// 为竞买奖池添加结束时间
			$jackpotModel = new ConfigJackpot();
            $jackpotModel->updateJackpot();

			$bak=new Backup();
			if(!$bak->autoBackup('升级'.$this->memberUpgradeMember->showName . '到' . $this->memberUpgradeType->showName ,'审核时间：'.webapp()->format->formatdatetime(time())))
			{
				throw new Error('注册成功,但备份失败');
				return EError::ERROR;
			}
		}
		return $status;
	}
	public function delete()
	{
		if($this->member_upgrade_is_verify==1)
			return false;
		return parent::delete();
	}
}
