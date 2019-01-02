<?php

/**
 * This is the model class for table "{{group}}".
 *
 * The followings are the available columns in table '{{group}}':
 * @property integer $group_id
 * @property integer $group_count
 * @property string $group_seq
 * @property string $group_add_date
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Group extends Model
{
	public $modelName='分组';
	public $nameColumn='group_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Group the static model class
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
		return '{{group}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_count, group_add_date', 'filter','filter'=>array($this,'empty2null')),
			array('group_count', 'numerical', 'integerOnly'=>true),
			array('group_add_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('group_id, group_count, group_seq, group_add_date', 'safe', 'on'=>'search'),
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
			'groupmaps' => array(Model::HAS_MANY, 'Groupmap', 'groupmap_group_id'),
			'group' => array(Model::BELONGS_TO, 'Memberinfo', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'group_id' => 'Group',
			'group_count' => 'Group Count',
			'group_seq' => 'Group Seq',
			'group_add_date' => 'Group Add Date',
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
		$sort=new Sort('Group');
		$sort->defaultOrder=array('group_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('group_count',$this->group_count);
		$criteria->compare('group_seq',$this->group_seq,false);
		$criteria->compare('group_add_date',$this->group_add_date,false);
		$criteria->compare('"group".memberinfo_account',@$this->group->memberinfo_account);
		$criteria->with=array('group');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}