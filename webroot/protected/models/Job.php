<?php

/**
 * This is the model class for table "pgagent.{{job}}".
 *
 * The followings are the available columns in table 'pgagent.{{job}}':
 * @property integer $jobid
 * @property integer $jobjclid
 * @property string $jobname
 * @property string $jobdesc
 * @property string $jobhostagent
 * @property boolean $jobenabled
 * @property string $jobcreated
 * @property string $jobchanged
 * @property integer $jobagentid
 * @property string $jobnextrun
 * @property string $joblastrun
 */
class Job extends Model
{
	//模型标题
	public $modelName='定时任务';
	//命名字段
	public $nameColumn='jobid';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pgagent.{{job}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('jobdesc, jobhostagent, jobenabled, jobagentid, jobnextrun, joblastrun', 'filter','filter'=>array($this,'empty2null')),
			array('jobjclid, jobname, jobcreated, jobchanged, jobenabled, jobdesc', 'required'),
			array('jobjclid, jobagentid', 'numerical', 'integerOnly'=>true),
			array('jobdesc, jobhostagent, jobenabled, jobnextrun, joblastrun', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('jobid, jobjclid, jobname, jobdesc, jobhostagent, jobenabled, jobcreated, jobchanged, jobagentid, jobnextrun, joblastrun', 'safe', 'on'=>'search'),
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
			'joblog' => array(Model::HAS_MANY, 'Joblog', 'jlgjobid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'jobid' => t('epmms','Jobid'),
			'jobjclid' => t('epmms','任务类型'),
			'jobname' => t('epmms','任务名称'),
			'jobdesc' => t('epmms','任务描述'),
			'jobhostagent' => t('epmms','主机代理'),
			'jobenabled' => t('epmms','启用状态'),
			'jobcreated' => t('epmms','创建日期'),
			'jobchanged' => t('epmms','修改日期'),
			'jobagentid' => t('epmms','Jobagentid'),
			'jobnextrun' => t('epmms','下次运行'),
			'joblastrun' => t('epmms','上次运行'),
			'lastStatus' => t('epmms','上次结果'),
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

		$sort=new Sort('Job');
		$sort->defaultOrder=array('jobid'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('jobid',$this->jobid);
		$criteria->compare('jobjclid',$this->jobjclid);
		$criteria->compare('jobname',$this->jobname,true);
		$criteria->compare('jobdesc',$this->jobdesc,true);
		$criteria->compare('jobhostagent',$this->jobhostagent,true);
		$criteria->compare('jobenabled',$this->jobenabled);
		$criteria->compare('jobcreated',$this->jobcreated,true);
		$criteria->compare('jobchanged',$this->jobchanged,true);
		$criteria->compare('jobagentid',$this->jobagentid);
		$criteria->compare('jobnextrun',$this->jobnextrun,true);
		$criteria->compare('joblastrun',$this->joblastrun,true);
		$criteria->addCondition("jobid =any (select jstjobid from pgagent.pga_jobstep as s where jstdbname='" . webapp()->db->database . "')");
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
	 * @return Job the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 上次执行的结果
	 */
	public function getLastStatus()
	{
		if($status=Joblog::model()->findByAttributes(['jlgjobid'=>$this->jobid],['order'=>'jlgid desc']))
			return $status->jlgstatus;
		return null;
	}
}
