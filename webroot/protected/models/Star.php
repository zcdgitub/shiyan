<?php

/**
 * This is the model class for table "{{star}}".
 *
 * The followings are the available columns in table '{{star}}':
 * @property integer $star_id
 * @property integer $star_product_id
 * @property integer $star_member_id
 * @property string $star_grade
 * @property string $star_date
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Star extends Model
{
	//模型标题
	public $modelName='产品评级';
	//命名字段
	public $nameColumn='star_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{star}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('star_product_id, star_member_id, star_grade', 'required'),
			array('star_product_id, star_member_id', 'numerical', 'integerOnly'=>true),
			array('star_product_id', 'exist', 'className'=>'Product','attributeName'=>'product_id'),
			array('star_grade', 'ext.validators.Decimal','precision'=>8,'scale'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('star_id, star_product_id, star_member_id, star_grade, star_date', 'safe', 'on'=>'search'),
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
			'starProduct' => array(Model::BELONGS_TO, 'Product', 'star_product_id'),
			'starMember' => array(Model::BELONGS_TO, 'Memberinfo', 'star_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'star_id' => t('epmms','Star'),
			'star_product_id' => t('epmms','产品'),
			'star_member_id' => t('epmms','会员'),
			'star_grade' => t('epmms','评级'),
			'star_date' => t('epmms','Star Date'),
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

		$sort=new Sort('Star');
		$sort->defaultOrder=array('star_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('star_id',$this->star_id);
		$criteria->compare('star_product_id',$this->star_product_id);
		$criteria->compare('star_member_id',$this->star_member_id);
		$criteria->compare('star_grade',$this->star_grade,true);
		$criteria->compare('star_date',$this->star_date,true);
		$criteria->compare('"starProduct".product_name',@$this->starProduct->product_name);
		$criteria->compare('"starMember".memberinfo_account',@$this->starMember->memberinfo_account);
		$criteria->with=array('starProduct','starMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Star the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
