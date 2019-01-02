<?php

/**
 * This is the model class for table "pgagent.{{joblog}}".
 *
 * The followings are the available columns in table 'pgagent.{{joblog}}':
 * @property integer $jlgid
 * @property integer $jlgjobid
 * @property string $jlgstatus
 * @property string $jlgstart
 * @property string $jlgduration
 */
class Joblog extends Model
{
	//模型标题
	public $modelName='执行日志';
	//命名字段
	public $nameColumn='jlgid';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pgagent.{{joblog}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('jlgstatus, jlgduration', 'filter','filter'=>array($this,'empty2null')),
			array('jlgjobid, jlgstart', 'required'),
			array('jlgjobid', 'numerical', 'integerOnly'=>true),
			array('jlgstatus', 'length', 'max'=>1),
			array('jlgduration', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('jlgid, jlgjobid, jlgstatus, jlgstart, jlgduration', 'safe', 'on'=>'search'),
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
			'job' => array(Model::BELONGS_TO, 'Job', 'jlgjobid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'jlgid' => t('epmms','Jlgid'),
			'jlgjobid' => t('epmms','任务'),
			'jlgstatus' => t('epmms','执行结果'),
			'jlgstart' => t('epmms','执行时间'),
			'jlgduration' => t('epmms','执行时长'),
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

		$sort=new Sort('Joblog');
		$sort->defaultOrder=array('jlgid'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;


		$criteria->compare('jlgid',$this->jlgid);
		$criteria->compare('jlgjobid',$this->jlgjobid);
		$criteria->compare('jlgstatus',$this->jlgstatus);
		$criteria->compare('jlgstart',$this->jlgstart,true);
		$criteria->compare('jlgduration',$this->jlgduration,true);
		$criteria->addCondition("jlgjobid =any (select jstjobid from pgagent.pga_jobstep as s where jstdbname='" . webapp()->db->database . "')");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->pgagent;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Joblog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
