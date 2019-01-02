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
class AwardConfigTable extends EuiActiveRecord
{
	public $modelName='基本奖金配置';
	public $nameColumn='award_config_id';
	public $selIndex=0;
	private $_config;
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
		return $this->_config[$this->selIndex]['table'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
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
		);
	}
	public static function getTableConfig()
	{
		//return [];
		foreach (MemberType::model()->findAll(['order'=>'membertype_level asc']) as $model) {
			$memberTypeData[] = ['membertype_level'=>$model->membertype_level,'membertype_name'=>$model->membertype_name];
		}
		foreach (MemberLevel::model()->findAll(['order'=>'member_level_level asc']) as $model) {
			$memberLevelData[] = ['member_level_level'=>$model->member_level_level,'member_level_name'=>$model->member_level_name];
		}
        return [
            0=>['table'=>'public.epmms_award_config_share','title'=>t('epmms','推荐奖'),'idField'=>'award_config_id',
                'columns'=>[
                     ['field'=>'award_config_member_count','title'=>'分享会员数','editor'=>['type'=>'text']],
                     ['field'=>'award_config_currency','title'=>'奖金额度','editor'=>['type'=>'numberbox','options'=>['precision'=>4]]]
                ]
            ],
            1=>['table'=>'public.epmms_award_config_bonus','title'=>t('epmms','分红奖'),'idField'=>'award_config_id',
                'columns'=>[
                   /* ['field'=>'award_config_membertype','title'=>'注册会员类型','editor'=>['type'=>'combobox',
                        'options'=>['valueField'=>'membertype_level','textField'=>'membertype_name','editable'=>false,'data'=>$memberTypeData]],
                        'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.membertype_level==value)showname=n.membertype_name;});return showname;}")
                    ],*/
                    ['field'=>'award_config_recommend_count','title'=>'推荐人数','editor'=>['type'=>'text']],
                    ['field'=>'award_config_recommend_layer','title'=>'层数','editor'=>['type'=>'text']],
                    ['field'=>'award_config_currency','title'=>'奖金额度','editor'=>['type'=>'numberbox','options'=>['precision'=>4]]],
                    //['field'=>'award_config_percent','title'=>'百分比','editor'=>['type'=>'numberbox','options'=>['precision'=>4]]]
                ]
            ],
 
        ];
		return [
				0=>['table'=>'award.epmms_award_config_dif','title'=>t('epmms','招商补贴'),'idField'=>'award_config_id',
						'columns'=>[
								['field'=>'director_level','title'=>'会员等级','editor'=>['type'=>'combobox',
										'options'=>['valueField'=>'member_level_level','textField'=>'member_level_name','editable'=>false,'data'=>$memberLevelData]],
										'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.member_level_level==value)showname=n.member_level_name;});return showname;}")
								],
								['field'=>'dif_award','title'=>'奖金额度','editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
						]
				],
				1=>['table'=>'award.award_config_org_up_down','title'=>t('epmms','管理津贴'),'idField'=>'award_config_id',
						'columns'=>[
								['field'=>'award_config_membertype','title'=>'注册会员类型','editor'=>['type'=>'combobox',
										'options'=>['valueField'=>'membertype_level','textField'=>'membertype_name','editable'=>false,'data'=>$memberTypeData]],
										'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.membertype_level==value)showname=n.membertype_name;});return showname;}")
								],
								['field'=>'award_config_layer','title'=>'层数','editor'=>['type'=>'numberbox']],
								['field'=>'award_config_currency','title'=>'奖金额度','editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
						]
				],
			2=>['table'=>'award.award_config_dot_recommend','title'=>t('epmms','三级分销'),'idField'=>'award_config_id',
				'columns'=>[
					['field'=>'award_config_membertype','title'=>'注册会员类型','editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'membertype_level','textField'=>'membertype_name','editable'=>false,'data'=>$memberTypeData]],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.membertype_level==value)showname=n.membertype_name;});return showname;}")
					],
					['field'=>'award_config_layer','title'=>'层数','editor'=>['type'=>'numberbox']],
					['field'=>'award_config_currency','title'=>'奖金额度','editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
				]
			],
		];
		return [
			0=>['table'=>'award.static_fenhong','title'=>t('epmms','静态分红'),
				'columns'=>[
					['field'=>'membertype','title'=>'注册会员类型','editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'membertype_level','textField'=>'membertype_name','editable'=>false,'data'=>$memberTypeData]],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.membertype_level==value)showname=n.membertype_name;});return showname;}")
					],
					['field'=>'award_currency','title'=>'奖金额度','editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
				]
			],
			1=>['table'=>'award.dynamic_fenhong','title'=>t('epmms','动态分红条件'),
				'columns'=>[
						['field'=>'membertype','title'=>'注册会员类型','editor'=>['type'=>'combobox',
							'options'=>['valueField'=>'membertype_level','textField'=>'membertype_name','editable'=>false,'data'=>$memberTypeData]],
							'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.membertype_level==value)showname=n.membertype_name;});return showname;}")
						],
					    ['field'=>'billcount','title'=>'单数范围','editor'=>['type'=>'text']],
						['field'=>'layers','title'=>'层数','editor'=>['type'=>'numberbox']]
					]
			],
			2=>['table'=>'award.dynmmic_fenhong_percent','title'=>t('epmms','动态分红奖金'),
				'columns'=>[
					['field'=>'layers','title'=>'层数范围','editor'=>['type'=>'text']],
					['field'=>'award_currency','title'=>'奖金额度','editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
				]
			],
		];
	}
	public function getConfig()
	{
		return $this->_config;
	}
	public function setConfig($cfg)
	{
		$this->_config=$cfg;
	}
	public static function getLimitConfig()
	{
		foreach (MemberType::model()->findAll(['order'=>'membertype_level asc']) as $model) {
			$memberTypeData[] = ['membertype_level'=>$model->membertype_level,'membertype_name'=>$model->membertype_name];
		}
		foreach (AwardType::model()->findAll(['condition'=>'award_type_is_enable=1','order'=>'award_type_order asc']) as $model) {
			$awardTypeData[] = ['award_type_id'=>$model->award_type_id,'award_type_name'=>$model->award_type_name];
		}
		foreach (SumType::model()->findAll(['order'=>'sum_type_id asc']) as $model) {
			$sumTypeData[] = ['sum_type_id'=>$model->sum_type_id,'sum_type_name'=>$model->sum_type_name];
		}
		$dateTypeData=[
			['date_type_id'=>'period','date_type_name'=>'每期'],
			['date_type_id'=>'day','date_type_name'=>'每日'],
			['date_type_id'=>'week','date_type_name'=>'每周'],
			['date_type_id'=>'month','date_type_name'=>'每月'],
			['date_type_id'=>'year','date_type_name'=>'每年'],
			['date_type_id'=>'total','date_type_name'=>'总计'],
		];
		return [
			0=>['table'=>'epmms_cap_award','title'=>t('epmms','按奖金类型封顶'),'idField'=>'cap_award_id','sortName'=>'cap_award_id',
				'columns'=>[
					['field'=>'cap_award_type','title'=>t('epmms','奖金类型'),'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'award_type_id','textField'=>'award_type_name','editable'=>false,'data'=>$awardTypeData]
					],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.award_type_id==value)showname=n.award_type_name;});return showname;}")
					],
					['field'=>'cap_award_date_type','title'=>t('epmms','日期类型'),'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'date_type_id','textField'=>'date_type_name','editable'=>false,'data'=>$dateTypeData]
					],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.date_type_id==value)showname=n.date_type_name;});return showname;}")
					],
					['field'=>'cap_award_money','title'=>t('epmms','封顶额度'),'editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
				]
			],
			1=>['table'=>'epmms_cap_member_award','title'=>t('epmms','按会员类型和奖金封顶'),'idField'=>'cap_member_id','sortName'=>'cap_member_id',
				'columns'=>[
					['field'=>'cap_member_type_id','title'=>t('epmms','会员类型'),'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'membertype_level','textField'=>'membertype_name','editable'=>false,'data'=>$memberTypeData]],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.membertype_level==value)showname=n.membertype_name;});return showname;}")
					],
					['field'=>'cap_member_award_type','title'=>t('epmms','奖金类型'),'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'award_type_id','textField'=>'award_type_name','editable'=>false,'data'=>$awardTypeData]
					],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.award_type_id==value)showname=n.award_type_name;});return showname;}")
					],
					['field'=>'cap_member_date_type','title'=>t('epmms','日期类型'),'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'date_type_id','textField'=>'date_type_name','editable'=>false,'data'=>$dateTypeData]
					],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.date_type_id==value)showname=n.date_type_name;});return showname;}")
					],
					['field'=>'cap_member_award_money','title'=>t('epmms','封顶额度'),'editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
				],
			],
			2=>['table'=>'epmms_cap_sum','title'=>t('epmms','奖金合计封顶'),'idField'=>'cap_sum_id','sortName'=>'cap_sum_id',
				'columns'=>[
					['field'=>'cap_sum_sum_type','title'=>t('epmms','合计类型'),'width'=>180,'fixed'=>true,'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'sum_type_id','textField'=>'sum_type_name','editable'=>false,'multiple'=>true,'data'=>$sumTypeData]],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){
							if(typeof value=='undefined')
								return '';
							var arr_showname=[];
							var showname;
							var arr_value=[];
							var i;
							arr_value=value.split(',');
							\$.each(this.editor.options.data,function(i,n){
								for(i in arr_value)
								{
									if(arr_value[i]==n.sum_type_id)
										arr_showname.push(n.sum_type_name);
								}
							})
							showname=arr_showname.join(',');
							return showname;
						}")
					],
					['field'=>'cap_sum_date_type','title'=>t('epmms','日期类型'),'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'date_type_id','textField'=>'date_type_name','editable'=>false,'data'=>$dateTypeData]
					],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.date_type_id==value)showname=n.date_type_name;});return showname;}")
					],
					['field'=>'cap_sum_money','title'=>t('epmms','封顶额度'),'editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
					],
			],
			3=>['table'=>'epmms_cap_member_sum','title'=>t('epmms','按会员类型奖金合计封顶'),'idField'=>'cap_member_id','sortName'=>'cap_member_id',
				'columns'=>[
					['field'=>'cap_member_type_id','title'=>t('epmms','会员类型'),'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'membertype_level','textField'=>'membertype_name','editable'=>false,'data'=>$memberTypeData]],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.membertype_level==value)showname=n.membertype_name;});return showname;}")
					],
					['field'=>'cap_member_sum_type','title'=>t('epmms','合计类型'),'width'=>180,'fixed'=>true,'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'sum_type_id','textField'=>'sum_type_name','editable'=>false,'multiple'=>true,'data'=>$sumTypeData]],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){
							if(typeof value=='undefined')
								return '';
							var arr_showname=[];
							var showname;
							var arr_value=[];
							var i;
							arr_value=value.split(',');
							\$.each(this.editor.options.data,function(i,n){
								for(i in arr_value)
								{
									if(arr_value[i]==n.sum_type_id)
										arr_showname.push(n.sum_type_name);
								}
							})
							showname=arr_showname.join(',');
							return showname;
						}")
					],
					['field'=>'cap_member_date_type','title'=>t('epmms','日期类型'),'editor'=>['type'=>'combobox',
						'options'=>['valueField'=>'date_type_id','textField'=>'date_type_name','editable'=>false,'data'=>$dateTypeData]
					],
						'formatter'=>new CJavaScriptExpression("function(value,row,index){var showname=value;\$.each(this.editor.options.data,function(i,n){if(n.date_type_id==value)showname=n.date_type_name;});return showname;}")
					],
					['field'=>'cap_member_money','title'=>t('epmms','封顶额度'),'editor'=>['type'=>'numberbox','options'=>['precision'=>2]]]
				],
			],
		];
	}
	public function __construct( $scenario='insert', $selIndex=0,$config=[])
	{
		$this->selIndex=$selIndex;
		$this->_config=$config;
		parent::__construct($scenario);
	}
}