<?php

/**
 * This is the model class for table "{{supplement}}".
 *
 * The followings are the available columns in table '{{supplement}}':
 * @property integer $supplement_id
 * @property string $supplement_currency
 * @property integer $supplement_member_id
 * @property string $supplement_date
 *
 * The followings are the available model relations:
 * @property  */
class Supplement extends Model
{
	//模型标题
	public $modelName='升级';
	//命名字段
	public $nameColumn='supplement_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{supplement}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplement_date', 'filter','filter'=>array($this,'empty2null')),
			array('supplement_currency, supplement_member_id', 'required'),
			array('supplement_member_id', 'numerical', 'integerOnly'=>true),
			array('supplement_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('supplement_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2),
			array('supplement_member_level','numerical','min'=>1),
			array('supplement_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('supplement_id, supplement_currency, supplement_member_id, supplement_date', 'safe', 'on'=>'search'),
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
			'supplementMember' => array(Model::BELONGS_TO, 'Memberinfo', 'supplement_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'supplement_id' => t('epmms','Supplement'),
			'supplement_currency' => t('epmms','充值金额'),
			'supplement_member_id' => t('epmms','充值会员'),
			'supplement_date' => t('epmms','充值日期'),
			'supplement_member_level'=> t('epmms','会员级别')
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

		$sort=new Sort('Supplement');
		$sort->defaultOrder=array('supplement_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('supplement_id',$this->supplement_id);
		$criteria->compare('supplement_currency',$this->supplement_currency,true);
		$criteria->compare('supplement_member_id',$this->supplement_member_id);
		$criteria->compare('supplement_date',$this->supplement_date,true);
		$criteria->compare('"supplementMember".memberinfo_account',@$this->supplementMember->memberinfo_account);
		$criteria->with=array('supplementMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Supplement the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function charge()
	{
		if($finance=user()->info->getFinance(2))
		{
			for($i=user()->map->membermap_level+1;$i<=$this->supplement_member_level;$i++)
			{
				if($finance->deduct($this->supplement_currency))
				{
					user()->map->membermap_level=$this->supplement_member_level;
					if(user()->map->saveAttributes(['membermap_level']))
					{
						Yii::import('ext.award.MySystem');
						$mysystem=new MySystem(user()->map);
						$mysystem->run('{1}','{2}',2);
					}
				}
				else
					return false;
			}
			return true;
		}
		return false;
	}
}
