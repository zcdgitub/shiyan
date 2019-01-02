<?php

/**
 * This is the model class for table "{{log}}".
 *
 * The followings are the available columns in table '{{log}}':
 * @property string $log_id
 * @property string $log_category
 * @property string $log_source
 * @property string $log_operate
 * @property string $log_target
 * @property string $log_value
 * @property string $log_info
 * @property string $log_ip
 * @property string $log_user
 * @property string $log_role
 * @property string $log_add_date
 * @property integer $log_status
 */
class Log extends Model
{
	public $modelName='操作日志';
	public $nameColumn='log_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Log the static model class
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
		return '{{log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('log_target, log_value, log_info, log_ip, log_status', 'filter','filter'=>array($this,'empty2null')),
			array('log_category', 'required'),
			array('log_status', 'numerical', 'integerOnly'=>true),
			array('log_category, log_source, log_operate, log_target, log_ip, log_user, log_role', 'length', 'max'=>50),
			array('log_value', 'safe'),
			array('log_info', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('log_id, log_category, log_source, log_operate, log_target, log_value, log_info, log_ip, log_user, log_role, log_add_date, log_status', 'safe', 'on'=>'search'),
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
			'log_id' => 'Log',
			'log_category' => t('epmms','类型'),
			'log_source' => t('epmms','来源'),
			'log_operate' => t('epmms','操作'),
			'log_target' => t('epmms','操作对象'),
			'log_value' => t('epmms','数据'),
			'log_info' => t('epmms','详情'),
			'log_ip' => t('epmms','IP地址'),
			'log_user' => t('epmms','帐号'),
			'log_role' => t('epmms','帐号角色'),
			'log_add_date' => t('epmms','日期'),
			'log_status' => t('epmms','结果'),
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
        $sort=new Sort('Log');
        $sort->defaultOrder=array('log_id'=>Sort::SORT_DESC);
        $criteria=new CDbCriteria;
        $criteria->compare('log_id',$this->log_id);
        $criteria->compare('log_category',$this->log_category);
        $criteria->compare('log_source',$this->log_source);
        $criteria->compare('log_operate',$this->log_operate);
        $criteria->compare('log_target',$this->log_target);
        $criteria->compare('log_value',$this->log_value);
        $criteria->compare('log_info',$this->log_info,true);
        $criteria->compare('log_ip',$this->log_ip);
        $criteria->compare('log_user',$this->log_user);
        /*		if(user()->name!=='admin')
                {
                    $criteria->compare('log_user','<>admin');
                }*/
        $criteria->compare('log_role',$this->log_role);
        $criteria->compare('log_add_date',$this->log_add_date);
        $criteria->compare('log_status',$this->log_status);
        if (webapp()->request->isAjaxRequest)
        {
            $page=0;
            $pageSize=20;
            if(isset($_GET['page']))
                $page=$_GET['page']-1;
            if(isset($_GET['limit']))
                $pageSize=$_GET['limit'];
            return new JSonActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                ),
                'includeDataProviderInformation'=>true
            ));
        } else
        {
            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort' => $sort,
            ));
        }
    }
}