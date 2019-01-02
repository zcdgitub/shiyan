<?php

/**
 * This is the model class for table "{{member_level}}".
 *
 * The followings are the available columns in table '{{member_level}}':
 * @property integer $member_level_level
 * @property string $member_level_name
 *
 * The followings are the available model relations:
 * @property  */
class MemberLevel extends Model
{
	//模型标题
	public $modelName='会员等级';
	//命名字段
	public $nameColumn='member_level_name';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{member_level}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('member_level_level, member_level_name', 'required'),
			array('member_level_level', 'numerical', 'integerOnly'=>true),
			array('member_level_level', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('member_level_level, member_level_name', 'safe', 'on'=>'search'),
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
			'membermaps' => array(Model::HAS_MANY, 'Membermap', 'membermap_level'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'member_level_level' => t('epmms','级别大小'),
			'member_level_name' => t('epmms','级别名称'),
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

		$sort=new Sort('MemberLevel');
		$sort->defaultOrder=array('member_level_level'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('member_level_level',$this->member_level_level);
		$criteria->compare('member_level_name',$this->member_level_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MemberLevel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getUpgradeLevel()
	{
		$sql='select * from get_upgrade_level(:id),epmms_membermap where membermap_id=:id and not (coalesce(membermap_level,0)=0 and member_level>1) order by member_level asc limit 1';
		$cmd=webapp()->db->createCommand($sql);
		$data=$cmd->queryAll(true,[':id'=>user()->id]);
		return CHtml::listData($data,'member_level','member_level_name');
	}
	public function getUpgradetoLevel()
	{
		$sql='select * from get_upgrade_level(:id) order by member_level asc limit 1';
		$cmd=webapp()->db->createCommand($sql);
		$data=$cmd->queryScalar([':id'=>user()->id]);
		return $data;
	}
}
