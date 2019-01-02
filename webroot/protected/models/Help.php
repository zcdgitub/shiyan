<?php

/**
 * This is the model class for table "{{help}}".
 *
 * The followings are the available columns in table '{{help}}':
 * @property string $help_id
 * @property string $help_type
 * @property string $help_title
 * @property string $help_content
 * @property string $help_mod_date
 */
class Help extends Model
{
	public $modelName='帮助及其它';
	public $nameColumn='help_title';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Help the static model class
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
		return '{{help}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('help_type, help_title, help_content, help_mod_date', 'filter','filter'=>array($this,'empty2null')),
			array('help_type', 'length', 'max'=>20),
			array('help_title, help_content, help_mod_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('help_id, help_type, help_title, help_content, help_mod_date', 'safe', 'on'=>'search'),
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
			'help_id' => 'Help',
			'help_type' => t('epmms','类型'),
			'help_title' => t('epmms','标题'),
			'help_content' => t('epmms','内容'),
			'help_mod_date' => t('epmms','修改日期'),
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
		$sort=new Sort('Help');
		$sort->defaultOrder=array('help_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('help_id',$this->help_id,false);
		$criteria->compare('help_type',$this->help_type,false);
		$criteria->compare('help_title',$this->help_title,false);
		$criteria->compare('help_content',$this->help_content,false);
		$criteria->compare('help_mod_date',$this->help_mod_date,false);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
}