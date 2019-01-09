<?php

/**
 * This is the model class for table "{{mybank}}".
 *
 * The followings are the available columns in table '{{mybank}}':
 * @property integer $mybank_id
 * @property integer $mybank_bank_id
 * @property string $mybank_name
 * @property string $mybank_account
 * @property integer $mybank_memberinfo_id
 * @property string $mybank_add_date
 * @property integer $mybank_is_default
 * @property string $mybank_address
 * @property string $mybank_memo
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Mybank extends Model
{
	//模型标题
	public $modelName='我的银行卡';
	//命名字段
	public $nameColumn='mybank_account';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mybank}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mybank_account, mybank_add_date, mybank_is_default, mybank_address', 'filter','filter'=>array($this,'empty2null')),
			array('mybank_bank_id, mybank_name, mybank_memberinfo_id,mybank_account', 'required'),
			array('mybank_bank_id', 'numerical', 'integerOnly'=>true),
            //array('mybank_is_default', 'boolean','falseValue'=>'false','trueValue'=>'true'),
			array('mybank_address,mybank_memo', 'length', 'max'=>50),
			array('mybank_bank_id', 'exist', 'className'=>'Bank','attributeName'=>'bank_id'),
			array('mybank_name', 'ext.validators.Account','allowZh'=>true),
			array('mybank_account', 'ext.validators.Account','allowZh'=>false),
			array('mybank_add_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mybank_id, mybank_bank_id, mybank_name, mybank_account, mybank_memberinfo_id, mybank_add_date, mybank_is_default, mybank_address,mybank_memo', 'safe', 'on'=>'search'),
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
			'mybankBank' => array(Model::BELONGS_TO, 'Bank', 'mybank_bank_id'),
			'mybankMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'mybank_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'mybank_id' => t('epmms','Mybank'),
			'mybank_bank_id' => t('epmms','开户行'),
			'mybank_name' => t('epmms','开户名'),
			'mybank_account' => t('epmms','银行卡号'),
			'mybank_memberinfo_id' => t('epmms','会员'),
			'mybank_add_date' => t('epmms','添加日期'),
			'mybank_is_default' => t('epmms','是否默认'),
			'mybank_address' => t('epmms','开户支行'),
            'mybank_memo'=>t('epmms','备注')
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

		$sort=new Sort('Mybank');
		$sort->defaultOrder=array('mybank_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('mybank_id',$this->mybank_id);
		$criteria->compare('mybank_bank_id',$this->mybank_bank_id);
		$criteria->compare('mybank_name',$this->mybank_name,true);
		$criteria->compare('mybank_account',$this->mybank_account,true);
		$criteria->compare('mybank_memberinfo_id',$this->mybank_memberinfo_id);
		$criteria->compare('mybank_add_date',$this->mybank_add_date,true);
		$criteria->compare('mybank_is_default',$this->mybank_is_default);
		$criteria->compare('mybank_address',$this->mybank_address,true);
        $criteria->compare('mybank_memo',$this->mybank_memo,true);
		$criteria->compare('"mybankBank".bank_name',@$this->mybankBank->bank_name);
		$criteria->compare('"mybankMemberinfo".memberinfo_account',@$this->mybankMemberinfo->memberinfo_account);
		$criteria->with=array('mybankBank','mybankMemberinfo');

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
                'relations'=>['mybankBank','mybankMemberinfo'],
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
	 * @return Mybank the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
