<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property string $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property integer $is_subscribed
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password
 * @property string $access_token
 * @property string $birth_date
 * @property integer $favorite_product_count
 * @property string $type
 * @property integer $access_token_created_at
 * @property integer $allowance
 * @property integer $allowance_updated_at
 */
class Customer extends Model
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_subscribed, status, created_at, updated_at, favorite_product_count, access_token_created_at, allowance, allowance_updated_at', 'numerical', 'integerOnly'=>true),
			array('password_hash', 'length', 'max'=>80),
			array('password_reset_token, email, auth_key, access_token', 'length', 'max'=>60),
			array('firstname, lastname', 'length', 'max'=>100),
			array('password', 'length', 'max'=>50),
			array('type', 'length', 'max'=>35),
			array('birth_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, password_hash, password_reset_token, email, firstname, lastname, is_subscribed, auth_key, status, created_at, updated_at, password, access_token, birth_date, favorite_product_count, type, access_token_created_at, allowance, allowance_updated_at', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'password_hash' => '密码',
			'password_reset_token' => '密码token',
			'email' => '邮箱',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'is_subscribed' => '1代表订阅，2代表不订阅邮件',
			'auth_key' => 'Auth Key',
			'status' => '状态',
			'created_at' => '创建时间',
			'updated_at' => '更新时间',
			'password' => '密码',
			'access_token' => 'Access Token',
			'birth_date' => '出生日期',
			'favorite_product_count' => '用户收藏的产品的总数',
			'type' => '默认为default，如果是第三方登录，譬如google账号登录注册，那么这里的值为google',
			'access_token_created_at' => '创建token的时间',
			'allowance' => '限制次数访问',
			'allowance_updated_at' => 'Allowance Updated At',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('password_hash',$this->password_hash,true);
		$criteria->compare('password_reset_token',$this->password_reset_token,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('is_subscribed',$this->is_subscribed);
		$criteria->compare('auth_key',$this->auth_key,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('birth_date',$this->birth_date,true);
		$criteria->compare('favorite_product_count',$this->favorite_product_count);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('access_token_created_at',$this->access_token_created_at);
		$criteria->compare('allowance',$this->allowance);
		$criteria->compare('allowance_updated_at',$this->allowance_updated_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbMysql;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Customer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
