<?php

/**
 * This is the model class for table "{{userinfo}}".
 *
 * The followings are the available columns in table '{{userinfo}}':
 * @property string $userinfo_id
 * @property string $userinfo_account
 * @property string $userinfo_password
 * @property string $userinfo_password2
 * @property string $userinfo_name
 * @property integer $userinfo_sex
 * @property string $userinfo_email
 * @property string $userinfo_mobi
 * @property string $userinfo_add_date
 */
class Userinfo extends Model
{
	public $modelName='管理员';
	public $nameColumn='userinfo_account';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Userinfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return CDbConnection database connection
	 */
	public function getDbConnection()
	{
		return Yii::app()->db;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{userinfo}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userinfo_account,userinfo_email,userinfo_password,userinfo_password2','required','on'=>'update,create'),
			array('userinfo_account','ext.validators.Account','allowZh'=>true,'on'=>'update,create'),
			array('userinfo_account','unique'),
			array('userinfo_account','unique','className'=>'Memberinfo','attributeName'=>'memberinfo_account'),
			array('userinfo_account','unique','className'=>'Agent','attributeName'=>'agent_account'),
			array('userinfo_password,userinfo_password2','required','on'=>'create,updatePassword'),
			array('userinfo_role','required','on'=>'create,update'),
			array('userinfo_role','ext.validators.Role'),
			array('userinfo_password,userinfo_password2','length','min'=>6,'max'=>20,'on'=>'create,updatePassword'),
			array('userinfo_password,userinfo_password2','length','min'=>6,'max'=>20,'on'=>'create,updatePassword'),
			array('userinfo_sex', 'ext.validators.Sex','on'=>'update,create'),
			array('userinfo_account, userinfo_name,userinfo_jobtitle','length', 'max'=>50,'on'=>'update,create'),
			array('userinfo_email', 'email','allowEmpty'=>false, 'on'=>'update,create'),
			array('userinfo_mobi', 'ext.validators.Phone','on'=>'update,create'),
			array('userinfo_mobi', 'length', 'max'=>20,'on'=>'update,create'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('userinfo_id, userinfo_account,userinfo_name,userinfo_name, userinfo_sex, userinfo_email, userinfo_mobi,userinfo_jobtitle', 'safe', 'on'=>'search'),
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
			'userinfo_id' => t('epmms','帐号ID'),
			'userinfo_account' => t('epmms','帐号'),
			'userinfo_password' => t('epmms','密码'),
			'userinfo_password2' => t('epmms','二级 密码'),
			'userinfo_name' => t('epmms','姓名'),
			'userinfo_sex' => t('epmms','姓别'),
			'userinfo_email' => t('epmms','电子邮件'),
			'userinfo_mobi' => t('epmms','电话号码'),
			'userinfo_jobtitle'=> t('epmms','职务'),
			'userinfo_role'=> t('epmms','权限角色'),
			'userinfo_add_date' => t('epmms','注册日期')
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

		$criteria=new CDbCriteria;

		$criteria->compare('userinfo_id',$this->userinfo_id);
		$criteria->compare('userinfo_account',$this->userinfo_account);
		$criteria->compare('userinfo_name',$this->userinfo_name);
		$criteria->compare('userinfo_sex',$this->userinfo_sex);
		$criteria->compare('userinfo_email',$this->userinfo_email,true);
		$criteria->compare('userinfo_mobi',$this->userinfo_mobi,true);
		$criteria->compare('userinfo_jobtitle',$this->userinfo_jobtitle);
		$criteria->compare('userinfo_role',$this->userinfo_role,true);		
		$criteria->compare('userinfo_add_date',$this->userinfo_add_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function afterValidate()
	{
		!empty($this->userinfo_password)?$this->userinfo_password=webapp()->format->format($this->userinfo_password,'password'):'';
		!empty($this->userinfo_password2)?$this->userinfo_password2=webapp()->format->format($this->userinfo_password2,'password'):'';
	}
	public function getShowTitle()
	{
		return $this->userinfo_account;
	}
}