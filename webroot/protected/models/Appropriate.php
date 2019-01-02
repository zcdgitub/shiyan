<?php

/**
 * This is the model class for table "{{appropriate}}".
 *
 * The followings are the available columns in table '{{appropriate}}':
 * @property integer $appropriate_id
 * @property string $appropriate_currency
 * @property integer $appropriate_finance_type_id
 * @property string $appropriate_add_date
 * @property integer $appropriate_memberinfo_id
 * @property integer $appropriate_type
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Appropriate extends Model
{
	//模型标题
	public $modelName='公司拨款';
	//命名字段
	public $nameColumn='appropriate_id';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{appropriate}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('appropriate_memberinfo_id', 'filter','filter'=>array($this,'empty2null')),
			array('appropriate_memberinfo_id', 'ext.validators.Exist', 'className'=>'Memberinfo',
				'attributeName'=>'memberinfo_id','criteria'=>['condition'=>"memberinfo_is_verify=1"],
				'allowEmpty'=>false,'except'=>'root'),
			array('appropriate_currency, appropriate_finance_type_id, appropriate_type', 'required'),
			array('appropriate_finance_type_id, appropriate_type', 'numerical', 'integerOnly'=>true),
			array('appropriate_finance_type_id', 'exist', 'className'=>'FinanceType','attributeName'=>'finance_type_id'),
			array('appropriate_finance_type_id', 'in', 'range'=>[1,2,3,4,5,6],'message'=>'{attribute} ' . t('epmms','不允许')),
			array('appropriate_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>2,'sign'=>0,'allowZero'=>false),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('appropriate_id, appropriate_currency, appropriate_finance_type_id, appropriate_add_date, appropriate_memberinfo_id, appropriate_type', 'safe', 'on'=>'search'),
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
			'appropriateFinanceType' => array(Model::BELONGS_TO, 'FinanceType', 'appropriate_finance_type_id'),
			'appropriateMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'appropriate_memberinfo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'appropriate_id' => t('epmms','Appropriate'),
			'appropriate_currency' => t('epmms','拨款金额'),
			'appropriate_finance_type_id' => t('epmms','账户类型'),
			'appropriate_add_date' => t('epmms','日期'),
			'appropriate_memberinfo_id' => t('epmms','拨款会员'),
			'appropriate_type' => t('epmms','操作'),
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

		$sort=new Sort('Appropriate');
		$sort->defaultOrder=array('appropriate_id'=>Sort::SORT_DESC);
		$criteria=new CDbCriteria;

		$criteria->compare('appropriate_id',$this->appropriate_id);
		$criteria->compare('appropriate_currency',$this->appropriate_currency);
		$criteria->compare('appropriate_finance_type_id',$this->appropriate_finance_type_id);
		$criteria->compare('appropriate_add_date',$this->appropriate_add_date,true);
		$criteria->compare('appropriate_memberinfo_id',$this->appropriate_memberinfo_id);
		$criteria->compare('appropriate_type',$this->appropriate_type);
		$criteria->compare('"appropriateFinanceType".finance_type_name',@$this->appropriateFinanceType->finance_type_name);
		$criteria->compare('"appropriateMemberinfo".memberinfo_account',@$this->appropriateMemberinfo->memberinfo_account);
		$criteria->with=array('appropriateFinanceType','appropriateMemberinfo');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Appropriate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function verify()
	{
		$finance=Finance::model()->findByAttributes(['finance_type'=>$this->appropriate_finance_type_id,'finance_memberinfo_id'=>$this->appropriate_memberinfo_id]);
		if($this->appropriate_type==0)
		{
			$bak=new Backup();
			if(!$bak->autoBackup('公司拨款:'.$this->appropriateMemberinfo->memberinfo_account,'时间:'.webapp()->format->formatdatetime(time()) . "\r\n" . '金额:' . $this->appropriate_currency . "\r\n" . '类型:' . $this->appropriateFinanceType->showName))
			{
				throw new Error('备份失败');
			}
			$finance->finance_award=$finance->finance_award+$this->appropriate_currency;
			return $finance->saveAttributes(['finance_award']);
		}
		else
		{
			$bak=new Backup();
			if(!$bak->autoBackup('公司扣款:'.$this->appropriateMemberinfo->memberinfo_account,'时间:'.webapp()->format->formatdatetime(time()) . "\r\n" . '金额:' . $this->appropriate_currency . "\r\n" . '类型:' . $this->appropriateFinanceType->showName))
			{
				throw new Error('备份失败');
			}
			$finance->finance_award=$finance->finance_award-$this->appropriate_currency;
			return $finance->saveAttributes(['finance_award']);
		}
	}
}
