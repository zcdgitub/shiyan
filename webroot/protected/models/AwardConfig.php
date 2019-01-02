<?php
Yii::import('ext.yii-easyui.base.EuiActiveRecord');
/**
 * This is the model class for table "{{award_config}}".
 *
 * The followings are the available columns in table '{{award_config}}':
 * @property integer $award_config_id
 * @property string $award_config_type_id
 * @property string $award_config_currency
 * @property integer $award_config_is_percent
 * @property string $award_config_add_date
 *
 * The followings are the available model relations:
 * @property  */
class AwardConfig extends EuiActiveRecord
{
	public $modelName='基本奖金配置';
	public $nameColumn='award_config_id';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AwardConfig the static model class
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
		return '{{award_config}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('award_config_currency, award_config_is_percent', 'filter','filter'=>array($this,'empty2null')),
			array('award_config_type_id', 'required'),
			array('award_config_add_date', 'default', 'value'=>new CDbExpression('now()')),
			array('award_config_type_id', 'exist', 'className'=>'AwardType','attributeName'=>'award_type_id'),
			array('award_config_currency', 'ext.validators.Decimal','precision'=>16,'scale'=>4),
			array('award_config_is_percent', 'ext.validators.Enable'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('award_config_id, award_config_type_id, award_config_currency, award_config_percent, award_config_add_date', 'safe', 'on'=>'search'),
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
			'awardConfigType' => array(Model::BELONGS_TO, 'AwardType', 'award_config_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'award_config_id' => 'Award Config',
			'award_config_type_id' =>t('epmms','奖金类型'),
			'award_config_currency' => t('epmms','奖金额度'),
			'award_config_is_percent' => t('epmms','金额单位(￥/%)'),
			'award_config_add_date' => 'Award Config Add Date',
		);
	}
}