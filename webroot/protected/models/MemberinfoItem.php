<?php

/**
 * This is the model class for table "{{memberinfo_item}}".
 *
 * The followings are the available columns in table '{{memberinfo_item}}':
 * @property string $memberinfo_item_id
 * @property string $memberinfo_item_field
 * @property string $memberinfo_item_title
 * @property integer $memberinfo_item_visible
 * @property integer $memberinfo_item_required
 * @property integer $memberinfo_item_is_enable
 * @property integer $memberinfo_item_update
 * @property integer $memberinfo_item_view
 * @property integer $memberinfo_item_admin
 * @property integer $memberinfo_item_order
 */
class MemberinfoItem extends Model
{
	public $modelName='注册参数';
	public $nameColumn='memberinfo_item_title';
	public $_items;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MemberinfoItem the static model class
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
		return '{{memberinfo_item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('memberinfo_item_id, memberinfo_item_field,memberinfo_item_title,memberinfo_item_order', 'required'),
			array('memberinfo_item_visible, memberinfo_item_required,memberinfo_item_view,memberinfo_item_admin,memberinfo_item_update,memberinfo_item_order', 'numerical', 'integerOnly'=>true),
			array('memberinfo_item_field, memberinfo_item_title', 'length', 'max'=>50),
			array('memberinfo_item_id, memberinfo_item_field', 'unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('memberinfo_item_id, memberinfo_item_field, memberinfo_item_title, memberinfo_item_visible, memberinfo_item_required', 'safe', 'on'=>'search'),
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
			'memberinfo_item_id' => 'Memberinfo Item',
			'memberinfo_item_field' => t('epmms','项目'),
			'memberinfo_item_title' => t('epmms','项目标题'),
			'memberinfo_item_visible' => t('epmms','注册项'),
			'memberinfo_item_required' => t('epmms','必选'),
			'memberinfo_item_update' => t('epmms','可修改'),
			'memberinfo_item_view' => t('epmms','可查看'),
			'memberinfo_item_admin' => t('epmms','可管理'),
			'memberinfo_item_order' => t('epmms','排序'),
			
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
		$sort=new Sort('MemberinfoItem');
		$sort->defaultOrder=array('memberinfo_item_order'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('memberinfo_item_id',$this->memberinfo_item_id);
		$criteria->compare('memberinfo_item_field',$this->memberinfo_item_field);
		$criteria->compare('memberinfo_item_title',$this->memberinfo_item_title,true);
		$criteria->compare('memberinfo_item_visible',$this->memberinfo_item_visible);
		$criteria->compare('memberinfo_item_required',$this->memberinfo_item_required);
		$criteria->compare('memberinfo_item_admin',$this->memberinfo_item_admin);
		$criteria->compare('memberinfo_item_update',$this->memberinfo_item_update);
		$criteria->compare('memberinfo_item_view',$this->memberinfo_item_view);
		$criteria->compare('memberinfo_item_order',$this->memberinfo_item_order);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>array(
				'pageSize'=>50,
			),
		));
	}

	/**
	 * 返回item的数组
	 * @return array
	 */
	public function getItems()
	{
		if(is_null($this->_items))
		{
			$items=MemberinfoItem::model()->findAll(['condition'=>'memberinfo_item_is_enable=1','order'=>'memberinfo_item_order asc']);
			$ret=array();
		
			foreach($items as $each)
			{
                $each->memberinfo_item_title=t('epmms',$each->memberinfo_item_title);
				$ret[$each->memberinfo_item_field]=$each->attributes;
			}
			$this->_items=$ret;
		}
		return $this->_items;
	}
	public static function getItemsConfig()
	{
	
		$items=[
			'memberinfo_account' => array('type'=>params('accountType')==0?'text':'autoaccount','visible'=>true),
			'memberinfo_type' => array('type'=>'text','visible'=>false),
			'memberinfo_password' => array('type'=>'password','autocomplete'=>'off','visible'=>true),
			'memberinfo_password_repeat' => array('type'=>'password','autocomplete'=>'off','visible'=>true),
			'memberinfo_password2' => array('type'=>'password','autocomplete'=>'off','visible'=>true),
			'memberinfo_password_repeat2' => array('type'=>'password','autocomplete'=>'off','visible'=>true),
			'memberinfo_nickname' => array('type'=>'text'),
			'memberinfo_mobi' => array('type'=>'text','hint'=>'为方便联系，请尽可能填写正确的手机'),
			'membermap_parent_id' => array('type'=>'RelativeFormInputElement2','relativeName'=>'membermapParent','visible'=>true,/*'suffix'=>CHtml::ajaxButton(t('epmms','自动填写'),'/membermap/autoParent',[
				'data'=>new CJavaScriptExpression('"name=" + $("#Membermap_membermap_recommend_id").val()'),
				'success'=>"function(data){\$('#Membermap_membermap_parent_id').attr('value',data.parent);$('input[name=\"Membermap[membermap_order]\"]').eq(data.order).attr('checked', 'true')}"],
				['style'=>"width:80px;"]),*/
                'htmlOptions'=>['onkeyup'=>new CJavaScriptExpression("
					$.get('/memberinfo/getName?account=' + this.value,function(data){
                           $('.field_membermap_parent_id .hint').html('接点人姓名：' + data);
					});
			")]),
			'membermap_recommend_id' => array('type'=>'RelativeFormInputElement2','relativeName'=>'membermapRecommend','visible'=>true,/*'htmlOptions'=>['onblur'=>new CJavaScriptExpression("
					$.get('/membermap/getMemberType?username=' + this.value,function(data){
						if(parseInt(data)==4)
						{
							$('#Membermap_membermap_membertype_level').empty();
							$('#Membermap_membermap_membertype_level').append('<option>请选择会员类型</option>');
							$('#Membermap_membermap_membertype_level').append('<option value=\"4\">代理商</option>');
						}
						else
						{
							$('#Membermap_membermap_membertype_level').empty();
							$('#Membermap_membermap_membertype_level').append('<option>请选择会员类型</option>');
							$('#Membermap_membermap_membertype_level').append('<option value=\"1\">铜卡会员</option>');
							$('#Membermap_membermap_membertype_level').append('<option value=\"2\">银卡会员</option>');
							$('#Membermap_membermap_membertype_level').append('<option value=\"3\">金卡会员</option>');
							$('#Membermap_membermap_membertype_level').append('<option value=\"4\">代理商</option>');
						}
					});
			")]*/
                'htmlOptions'=>['onkeyup'=>new CJavaScriptExpression("
					$.get('/memberinfo/getName?account=' + this.value,function(data){
                           $('.field_membermap_recommend_id .hint').html('推荐人姓名：' + data);
					});
			")]),
			'membermap_product_count' => array('type'=>'spinner'),
			'membermap_agent_id' => array('type'=>'RelativeFormInputElement2','relativeName'=>'membermapAgent','visible'=>true),
			'membermap_bond_id' =>array('type'=>'RelativeFormInputElement2','relativeName'=>'membermapbond','visible'=>true),
			'membermap_membertype_level' => array('type'=>'RelativeFormInputElement','relativeName'=>'membermapMembertypeLevel','visible'=>true,/*'htmlOptions'=>['onchange'=>new CJavaScriptExpression("
				if(this.value<=3)
				{
					$('#Membermap_membermap_order').html('" . MemberinfoItem::mapOrder(Membermap::model(),'membermap_order',[],2) . "');
				}
				else
				{
					$('#Membermap_membermap_order').html('" . MemberinfoItem::mapOrder(Membermap::model(),'membermap_order',[],3) . "');
				}
			")]*/),
			'membermap_order' => array('type'=>'mapOrder'),
			'membermap_level' => array('type'=>'RelativeFormInputElement','relativeName'=>'memberlevel','visible'=>true),
			'membermap_is_empty' => array('type'=>'yesno','visible'=>user()->id==1 || user()->isAdmin()),
			'membermap_is_goods' => array('type'=>'yesno','visible'=>true),
			'memberinfo_is_enable' => array('type'=>'yesno','visible'=>user()->isAdmin()),
			'memberinfo_is_enable' => array('type'=>'yesno','visible'=>user()->isAdmin()),
			'memberinfo_is_agent' => array('type'=>'yesno','visible'=>user()->isAdmin()),
			'membermap_is_agent' => array('type'=>'yesno','visible'=> user()->isAdmin()),
			'memberinfo_bank_id' => array('type'=>'bank'),
			'memberinfo_bank_name' => array('type'=>'text'),
			'memberinfo_bank_account' => array('type'=>'text'),
			'memberinfo_email' => array('type'=>'email'),
			'memberinfo_name' => array('type'=>'text'),
			'memberinfo_phone' => array('type'=>'text'),
			'memberinfo_qq' => array('type'=>'text'),
			'memberinfo_msn' => array('type'=>'text'),
			'memberinfo_sex' => array('type'=>'sex'),
			'memberinfo_idcard_type' => array('type'=>'idCardType'),
			'memberinfo_idcard' => array('type'=>'text'),
			'memberinfo_address_provience' => array('type'=>'dropdownlist','items'=>array()),
			'memberinfo_address_area' => array('type'=>'dropdownlist','items'=>array()),
			'memberinfo_address_county' => array('type'=>'dropdownlist','items'=>array()),
			'memberinfo_address_detail' => array('type'=>'text'),
			'memberinfo_zipcode' => array('type'=>'text'),
			'memberinfo_birthday' => array('type'=>'date'),
			'memberinfo_bank_provience' => array('type'=>'dropdownlist','items'=>array()),
			'memberinfo_bank_area' => array('type'=>'dropdownlist','items'=>array()),
			'memberinfo_bank_branch' => array('type'=>'text'),
			'memberinfo_question' => array('type'=>'text'),
			'memberinfo_answer' => array('type'=>'text'),
			'memberinfo_memo' => array('type'=>'text'),
			'memberinfo_register_ip'=>['type'=>'text'],
			'memberinfo_last_ip'=>['type'=>'text'],
			'memberinfo_last_date'=>['type'=>'datetime'],
			'memberinfo_add_date'=>['type'=>'datetime'],
			'membermap_verify_date'=>['type'=>'datetime'],
			'membermap_money' => array('type'=>'text'),
			'membermap_percent1'=>['type'=>'spinner','min'=>50,'max'=>50,'hint'=>t('epmms',''),'suffix'=>'%','hint'=>'输入百分比50%'],
			'membermap_percent2'=>['type'=>'spinner','min'=>50,'max'=>50,'hint'=>t('epmms',''),'suffix'=>'%','hint'=>'输入百分比50%'],
			'memberinfo_postoffice'=>array('type'=>'text')
		];
		return $items;
	}
	public static function mapOrder($model,$attribute,$htmlOptions=array(),$max_order)
	{
		$order=[];
		for($i=1;$i<=$max_order;$i++)
		{
			$order[$i]=chr(64+$i);
		}
		return CJavaScript::quote(CHtml::activeRadioButtonList($model,$attribute,$order,array_merge($htmlOptions,['separator'=>' ','style'=>'width:13px;'])));
	}
	/**
	 * 判断一个item是否可见
	 * @param $item
	 * @return bool
	 */
	public function itemVisible($item)
	{
		if($this->items===null)
			$this->items=$this->getItems();
		
		if(isset($this->items[$item]))
		{   
			return (int)$this->items[$item]['memberinfo_item_visible']===0?false:true;
		}
		return true;
	}

	/**
	 * 判断item是否必选
	 * @param $item
	 * @return bool
	 */
	public function itemRequired($item)
	{
		if($this->items===null)
			$this->items=$this->getItems();
		if($this->itemVisible($item))
		{
			if(isset($this->items[$item]))
			{
				return $this->items[$item]['memberinfo_item_required']==0?false:true;
			}
		}
		else
			return false;
		return true;
	}
	public function getRequireMemberinfoItems()
	{
		
		$memberinfoItems=[];
		foreach($this->items as $field_name=>$field)
		{
			if(strncasecmp($field_name,'memberinfo_',11)==0 && $field['memberinfo_item_required']==1)
			{
				$memberinfoItems[$field_name]=$this->itemsConfig[$field_name];
			}
		}
		
		return $memberinfoItems;
	}
	public function getRequireMembermapItems()
	{
		$memberinfoItems=[];

		foreach($this->items as $field_name=>$field)
		{
			if($field_name=='membermap_membertype_level')
			{
				if(MemberType::model()->count()<=1)
					continue;
			}
            if(params('regAgent')==false && $field_name=='membermap_agent_id')
            {
                continue;
            }
			if(strncasecmp($field_name,'membermap_',10)==0 && $field['memberinfo_item_visible']==1)
			{
				$memberinfoItems[$field_name]=$this->itemsConfig[$field_name];
			}
		}
		
		return $memberinfoItems;
	}
	public function getMemberinfoItems()
	{
		$memberinfoItems=[];

		foreach($this->items as $field_name=>$field)
		{
			
			
			if(strncasecmp($field_name,'memberinfo_',11)==0 && $field['memberinfo_item_required']==0  && $field['memberinfo_item_visible']==1)
			{
				
				$memberinfoItems[$field_name]=$this->itemsConfig[$field_name];
			
			}

		}
	
		return $memberinfoItems;
	}
	public function getUpdateItems()
	{
		$updateItems=[];
		$updateItems['memberinfo']['type']='form';
		foreach($this->items as $field_name=>$field)
		{
			if(strncasecmp($field_name,'memberinfo_',11)==0 && $field['memberinfo_item_update']==1)
			{
				if(strncasecmp($field_name,'memberinfo_password',19)!=0)
					$memberinfoItems[$field_name]=$this->itemsConfig[$field_name];
			}
		}
		$updateItems['memberinfo']['elements']=$memberinfoItems;

		$memberinfoItems=[];
		$updateItems['membermap']['type']='form';
		foreach($this->items as $field_name=>$field)
		{
			if($field_name=='membermap_membertype_level')
			{
				if(MemberType::model()->count()<=1)
					continue;
			}
			if(strncasecmp($field_name,'membermap_',10)==0 && $field['memberinfo_item_update']==1)
			{
				$memberinfoItems[$field_name]=$this->itemsConfig[$field_name];
			}
		}
		$updateItems['membermap']['elements']=$memberinfoItems;
		return $updateItems;
	}
	public function getVerifyUpdateItems()
	{
		$updateItems=[];
		$updateItems['memberinfo']['type']='form';
		foreach($this->items as $field_name=>$field)
		{
			if(!user()->isAdmin())
			{
				if(strncasecmp($field_name,'memberinfo_account',18)==0)
					continue;
			}
			if(strncasecmp($field_name,'memberinfo_',11)==0 && $field['memberinfo_item_update']==1)
			{
				if(strncasecmp($field_name,'memberinfo_password',19)!=0)
					$memberinfoItems[$field_name]=$this->itemsConfig[$field_name];
			}
		}
		$updateItems['memberinfo']['elements']=$memberinfoItems;

		$memberinfoItems=[];
		$updateItems['membermap']['type']='form';
		foreach($this->items as $field_name=>$field)
		{
			if($field_name=='membermap_membertype_level')
			{
				if(MemberType::model()->count()<=1)
					continue;
			}
			if(user()->isAdmin())
				if(strncasecmp($field_name,'membermap_',10)==0 && $field['memberinfo_item_update']==1)
				{
					$memberinfoItems[$field_name]=$this->itemsConfig[$field_name];
				}
		}
		$updateItems['membermap']['elements']=$memberinfoItems;
		
		return $updateItems;
	}
	public function getViewItem($item)
	{
		if($item=='membermap_membertype_level')
		{
			if(MemberType::model()->count()<=1)
				return false;
		}
		foreach($this->items as $field_name=>$field)
		{
			if($field_name==$item && $field['memberinfo_item_view']==1)
			{
				return true;
			}
		}
		return false;
	}
	public function getAdminItem($item)
	{
		if($item=='membermap_membertype_level')
		{
			if(MemberType::model()->count()<=1)
				return false;
		}
		foreach($this->items as $field_name=>$field)
		{
			if($field_name==$item && $field['memberinfo_item_admin']==1)
			{
				return true;
			}
		}
		return false;
	}

	public function getMyUpdateItems()
	{
		$updateItems=[];
		$updateItems['memberinfo']['type']='form';
		$memberinfoItems=[];
		foreach($this->items as $field_name=>$field)
		{
			if(strncasecmp($field_name,'memberinfo_account',18)==0)
				continue;
			if(strncasecmp($field_name,'memberinfo_',11)==0 && $field['memberinfo_item_update']==1)
			{
				if(strncasecmp($field_name,'memberinfo_password',19)!=0)
					$memberinfoItems[$field_name]=$this->itemsConfig[$field_name];
			}
		}
		/*
		$get_verify='&nbsp;&nbsp;' . CHtml::ajaxButton('获取短信验证码',CHtml::normalizeUrl(['memberinfo/getVerify','type'=>1]),['update'=>'.field_memberinfo_msg_verify div.hint'],['style'=>'width:110px;']);
		$get_verify.='&nbsp;&nbsp;' . CHtml::ajaxButton('获取邮件验证码',CHtml::normalizeUrl(['memberinfo/getVerify','type'=>2]),['update'=>'.field_memberinfo_msg_verify div.hint'],['style'=>'width:110px;']);
		$memberinfoItems['memberinfo_msg_verify']=['name'=>'memberinfo_msg_verify','type'=>'text','visible'=>true,'hint'=>'获取验证码','suffix'=>$get_verify,'attributes'=>['style'=>'width:80px;']];*/
		$updateItems['memberinfo']['elements']=$memberinfoItems;
		return $updateItems;
	}
	public static function getLeafs($id)
	{
		$r_member=Membermap::model()->findbyPk($id);
		$leafs='';
		$leafs_member=[];
		$models=Membermap::model()->findAll(['condition'=>"(membermap_child_number=0 or (membermap_child_number=1 and membermap_recommend_number>=1)) and membermap_path like :r_path || '%'  and membermap_path like :r_path2 || '%' ",'limit'=>3,'order'=>'membermap_layer asc','params'=>[':r_path'=>$r_member->membermap_path,':r_path2'=>user()->map->membermap_path]]);
		foreach($models as $model)
		{
			$leafs_member[]=$model->showName;
		}
		$leafs.='推荐人或以下可用会员：' . implode(',',$leafs_member);
		$leafs.=CHtml::link('...' . t('epmms','更多'),['membermap/leafs','id'=>$id],['target'=>'_blank']);
		return $leafs;
	}
}