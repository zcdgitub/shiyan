<?php

/**
 * This is the model class for table "{{transfer_config}}".
 *
 * The followings are the available columns in table '{{transfer_config}}':
 * @property integer $transfer_config_id
 * @property string $transfer_config_src_type
 * @property string $transfer_config_dst_type
 * @property string $transfer_config_src_role
 * @property string $transfer_config_dst_role
 * @property integer $transfer_config_member_able
 * @property integer $transfer_config_relation
 * @property integer $transfer_config_tax
 */
class TransferConfig extends Model
{
	//模型标题
	public $modelName='转账配置';
	//命名字段
	public $nameColumn='transfer_config_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{transfer_config}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('transfer_config_src_type, transfer_config_dst_type, transfer_config_src_role, transfer_config_dst_role,transfer_config_tax', 'filter','filter'=>array($this,'empty2null')),
			array('transfer_config_src_type, transfer_config_dst_type, transfer_config_src_role, transfer_config_dst_role', 'safe'),
			array('transfer_config_member_able,transfer_config_relation','ext.validators.Enable'),
			array('transfer_config_tax', 'required'),
			array('transfer_config_tax', 'ext.validators.Decimal','precision'=>4,'scale'=>3,'sign'=>0,'allowZero'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('transfer_config_id, transfer_config_src_type, transfer_config_dst_type, transfer_config_src_role, transfer_config_dst_role,transfer_config_relation', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'transfer_config_id' => t('epmms','Transfer Config'),
			'transfer_config_src_type' => t('epmms','转出账户类型'),
			'transfer_config_dst_type' => t('epmms','转入账户类型'),
			'transfer_config_src_role' => t('epmms','转出会员身份'),
			'transfer_config_dst_role' => t('epmms','转入会员身份'),
			'transfer_config_member_able' => t('epmms','会员间转账'),
			'transfer_config_relation'=> t('epmms','非上下级间转账'),
			'transfer_config_tax'=> t('epmms','转账手续费')
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

		$sort=new Sort('TransferConfig');
		$sort->defaultOrder=array('transfer_config_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('transfer_config_id',$this->transfer_config_id);
		$criteria->compare('transfer_config_src_type',$this->transfer_config_src_type,true);
		$criteria->compare('transfer_config_dst_type',$this->transfer_config_dst_type,true);
		$criteria->compare('transfer_config_src_role',$this->transfer_config_src_role,true);
		$criteria->compare('transfer_config_dst_role',$this->transfer_config_dst_role,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TransferConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	protected function afterFind()
	{
		parent::afterFind();
		$this->transfer_config_src_type=self::array_pg2php($this->transfer_config_src_type);
		$this->transfer_config_dst_type=self::array_pg2php($this->transfer_config_dst_type);
		$this->transfer_config_src_role=self::array_pg2php($this->transfer_config_src_role);
		$this->transfer_config_dst_role=self::array_pg2php($this->transfer_config_dst_role);
	}
	protected function beforeSave()
	{
		$this->transfer_config_src_type=self::array_php2pg($this->transfer_config_src_type);
		$this->transfer_config_dst_type=self::array_php2pg($this->transfer_config_dst_type);
		$this->transfer_config_src_role=self::array_php2pg($this->transfer_config_src_role);
		$this->transfer_config_dst_role=self::array_php2pg($this->transfer_config_dst_role);
		return parent::beforeSave();
	}
	public static function array_pg2php($arr)
	{
		$arr=trim($arr,'{} ');
		return explode(',',$arr);
	}
	public static function array_php2pg($arr)
	{
		if(empty($arr))
			return '{}';
		$arr='{' . implode(',',$arr) . '}';
		return $arr;
	}
}
