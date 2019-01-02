<?php

/**
 * This is the model class for table "{{announcement_class}}".
 *
 * The followings are the available columns in table '{{announcement_class}}':
 * @property integer $class_id
 * @property string $class_name
 * @property string $class_intro
 * @property string $class_add_date
 * @property integer $class_sort
 *
 * The followings are the available model relations:
 * @property  */
class AnnouncementClass extends Model
{
	//模型标题
	public $modelName='新闻分类';
	//命名字段
	public $nameColumn='class_name';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{announcement_class}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('class_intro, class_add_date, class_sort', 'filter','filter'=>array($this,'empty2null')),
			array('class_name', 'required'),
			array('class_sort', 'numerical', 'integerOnly'=>true),
			array('class_name', 'ext.validators.Account','allowZh'=>true),
			array('class_intro, class_add_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('class_id, class_name, class_intro, class_add_date, class_sort', 'safe', 'on'=>'search'),
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
			'announcements' => array(Model::HAS_MANY, 'Announcement', 'announcement_class'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'class_id' => t('epmms','Class'),
			'class_name' => t('epmms','分类标题'),
			'class_intro' => t('epmms','分类说明'),
			'class_add_date' => t('epmms','添加日期'),
			'class_sort' => t('epmms','排序'),
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

		$sort=new Sort('AnnouncementClass');
		$sort->defaultOrder=array('class_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('class_id',$this->class_id);
		$criteria->compare('class_name',$this->class_name,true);
		$criteria->compare('class_intro',$this->class_intro,true);
		$criteria->compare('class_add_date',$this->class_add_date,true);
		$criteria->compare('class_sort',$this->class_sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AnnouncementClass the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
