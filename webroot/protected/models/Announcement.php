<?php

/**
 * This is the model class for table "{{announcement}}".
 *
 * The followings are the available columns in table '{{announcement}}':
 * @property integer $announcement_id
 * @property string $announcement_title
 * @property string $announcement_content
 * @property string $announcement_add_date
 * @property string $announcement_mod_date
 * @property string $announcement_userinfo_id
 * @property integer $announcement_class
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Announcement extends Model
{
	//模型标题
	public $modelName='新闻公告';
	//命名字段
	public $nameColumn='announcement_title';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{announcement}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('announcement_userinfo_id', 'filter','filter'=>array($this,'empty2null')),
			array('announcement_title, announcement_content, announcement_mod_date, announcement_class', 'required'),
			array('announcement_class', 'numerical', 'integerOnly'=>true),
			array('announcement_userinfo_id', 'exist', 'className'=>'Userinfo','attributeName'=>'userinfo_id'),
			array('announcement_userinfo_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('announcement_id, announcement_title, announcement_content, announcement_add_date, announcement_mod_date, announcement_userinfo_id, announcement_class', 'safe', 'on'=>'search'),
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
			'announcementUserinfo' => array(Model::BELONGS_TO, 'Userinfo', 'announcement_userinfo_id'),
			'announcementClass' => array(Model::BELONGS_TO, 'AnnouncementClass', 'announcement_class'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'announcement_id' => t('epmms','Announcement'),
			'announcement_title' => t('epmms','公告标题'),
			'announcement_content' => t('epmms','公告内容'),
			'announcement_add_date' => t('epmms','发布日期'),
			'announcement_mod_date' => t('epmms','修改日期'),
			'announcement_userinfo_id' => t('epmms','公告作者'),
			'announcement_class' => t('epmms','新闻分类'),
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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Announcement the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
