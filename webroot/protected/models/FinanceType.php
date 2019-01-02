<?php

/**
 * This is the model class for table "{{finance_type}}".
 *
 * The followings are the available columns in table '{{finance_type}}':
 * @property string $finance_type_id
 * @property string $finance_type_name
 * @property integer $finance_type_withdrawals
 *
 * The followings are the available model relations:
 * @property  */
class FinanceType extends Model
{
	public $modelName='财务类型';
	public $nameColumn='finance_type_name';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FinanceType the static model class
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
		return '{{finance_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('finance_type_name', 'filter','filter'=>array($this,'empty2null')),
			array('finance_type_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('finance_type_id, finance_type_name', 'safe', 'on'=>'search'),
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
			'finances' => array(Model::HAS_MANY, 'Finance', 'finance_type'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'finance_type_id' => t('epmms','财务类型'),
			'finance_type_name' => t('epmms','财务类型'),
			'finance_type_withdrawals'=>t('epmms','提现')
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
        $sort=new Sort('FinanceType');
        $sort->defaultOrder=array('finance_type_id'=>Sort::SORT_ASC);
        $criteria=new CDbCriteria;
        $criteria->compare('finance_type_id',$this->finance_type_id,true);
        $criteria->compare('finance_type_withdrawals',$this->finance_type_withdrawals);
        $criteria->compare('finance_type_name',$this->finance_type_name,true);
        return new JSonActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'relations'=>['finances']
        ));
	}
	public function getMemberFinance($memberid)
	{
		return Finance::model()->find('finance_memberinfo_id=:id and finance_type=:type',[':id'=>$memberid,':type'=>$this->finance_type_id] );
	}
	public function getTransferSrcListData()
	{
		$condition=['join'=>', epmms_transfer_config c','condition'=>'finance_type_id=any(transfer_config_dst_type)'];
		//$condition=['join'=>', epmms_transfer_config c','condition'=>'finance_type_id=2 or finance_type_id=4'];
		return CHtml::listData($this->findAll($condition),$this->tableSchema->primaryKey,$this->nameColumn);
	}
	public function getTransferDstListData()
	{
		$condition=['join'=>', epmms_transfer_config c','condition'=>'finance_type_id=any(transfer_config_dst_type)'];
		return CHtml::listData($this->findAll($condition),$this->tableSchema->primaryKey,$this->nameColumn);
	}
	public function getListData($condition='')
	{
		return CHtml::listData($this->findAll($condition),$this->tableSchema->primaryKey,t('epmms',$this->nameColumn));
	}
}