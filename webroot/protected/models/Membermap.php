<?php

/**
 * This is the model class for table "{{membermap}}".
 *
 * The followings are the available columns in table '{{membermap}}':
 * @property string $membermap_id
 * @property string $membermap_seq
 * @property string $membermap_parent_id
 * @property string $membermap_recommend_id
 * @property integer $membermap_membertype_level
 * @property integer $membermap_layer
 * @property integer $membermap_order
 * @property string $membermap_path
 * @property string $membermap_recommend_path
 * @property integer $membermap_recommend_number
 * @property integer $membermap_recommend_under_number
 * @property integer $membermap_recommend_layer
 * @property integer $membermap_child_number
 * @property integer $membermap_sub_number
 * @property integer $membermap_sub_product_count
 * @property integer $membermap_recommend_under_product_count
 * @property integer $membermap_product_count
 * @property string $membermap_agent_id
 * @property integer $membermap_is_verify
 * @property integer $membermap_is_agent
 * @property string $membermap_verify_date
 * @property string $membermap_verify_seq
 * @property string $membermap_verify_seq2
 * @property string $membermap_verify_member_id
 * @property string $membermap_add_date
 * @property string $membermap_money
 * @property integer $membermap_bond_id
 * @property integer $membermap_is_empty
 * @property integer $membermap_period
 * @property integer $membermap_membertype_level_old
 * @property integer $membermap_level
 * @property integer $membermap_is_delete
 * @property integer $membermap_under_product_count
 * @property integer $membermap_under_number
 * @property integer $membermap_agent_number
 * @property integer $membermap_agent_product_count
 * @property integer $membermap_agent_type
 * @property string $membermap_layer_order
 * @property integer $membermap_reg_member_id
 * @property integer $membermap_expire_date
 *
 * The followings are the available model relations:
 * @property  * @property  * @property  * @property  * @property  * @property  * @property  * @property  * @property  * @property  */
class Membermap extends Model
{
	public $modelName='网络图';
	public $nameColumn='showName';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Membermap the static model class
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
		return '{{membermap}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$required_field='membermap_recommend_id,,membermap_is_goods,membermap_membertype_level,membermap_agent_id,membermap_parent_id,membermap_order,membermap_percent1,membermap_percent2';
		$fields=explode(',',$required_field);
		$model_item=MemberinfoItem::model();
		foreach($fields as $fieldName)
		{
			$fieldName=trim($fieldName);
			if($model_item->itemRequired($fieldName))
			{
				if(webapp()->name!='console')
				{
					if($fieldName=='membermap_is_empty' && !user()->isAdmin())
						continue;
				}
				$new_required_field[]=$fieldName;
			}
		}
		$new_required_field_rule= ',' . implode(',',$new_required_field);
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('membermap_parent_id, membermap_recommend_id,membermap_is_goods, membermap_membertype_level, membermap_layer, membermap_path, membermap_recommend_path, membermap_recommend_number, membermap_recommend_under_number, membermap_child_number, membermap_sub_number, membermap_sub_product_count, membermap_recommend_under_product_count,  membermap_product_count, membermap_agent_id, membermap_is_verify, membermap_is_agent, membermap_verify_date,membermap_verify_seq,membermap_verify_seq2, membermap_verify_member_id, membermap_add_date,membermap_is_empty', 'filter','filter'=>array($this,'empty2null'),'on'=>'create,update'),
			array('membermap_membertype_level, membermap_is_goods,membermap_layer, membermap_path, membermap_recommend_path, membermap_recommend_number, membermap_recommend_under_number, membermap_child_number, membermap_sub_number, membermap_sub_product_count, membermap_recommend_under_product_count, membermap_product_count, membermap_is_verify, membermap_is_agent, membermap_verify_date,membermap_verify_seq,membermap_verify_seq2, membermap_verify_member_id, membermap_add_date,membermap_percent1,membermap_percent2', 'filter','filter'=>array($this,'empty2null'),'on'=>'root'),
			array('membermap_recommend_id,membermap_membertype_level' . $new_required_field_rule, 'required','on'=>'update,create'),
			array('membermap_membertype_level, membermap_is_goods,membermap_layer,membermap_order, membermap_recommend_number, membermap_recommend_under_number, membermap_child_number, membermap_sub_number, membermap_sub_product_count, membermap_recommend_under_product_count, membermap_product_count, membermap_is_verify, membermap_is_agent,membermap_percent1,membermap_percent2', 'numerical', 'integerOnly'=>true,'on'=>'create,update'),
			array('membermap_membertype_level, membermap_is_goods,membermap_layer, membermap_recommend_number, membermap_recommend_under_number, membermap_child_number, membermap_sub_number, membermap_sub_product_count, membermap_recommend_under_product_count, membermap_product_count, membermap_is_verify, membermap_is_agent', 'numerical', 'integerOnly'=>true,'on'=>'root'),
			array('membermap_parent_id, membermap_recommend_id, membermap_agent_id', 'length', 'max'=>50,'on'=>'create,update'),
			array('membermap_path, membermap_recommend_path', 'length', 'max'=>10000),
			array('membermap_is_empty', 'ext.validators.Enable'),
			array('membermap_is_empty', 'ext.validators.AbleEmpty'),

			
			 array('membermap_is_goods', 'in','range'=>[0,1]),

			array('membermap_is_agent', 'ext.validators.Enable'),
			//array('membermap_percent1','ext.validators.AblePercent1'),
			//array('membermap_percent2','ext.validators.AblePercent2'),
			array('membermap_verify_member_id', 'length', 'max'=>50,'except'=>'root'),
			array('membermap_seq','default','value'=>new CDbExpression("nextval('membermap_add')")),
			array('membermap_level', 'ext.validators.Exist', 'className'=>'MemberLevel','attributeName'=>'member_level_level','allowEmpty'=>true),
			array('membermap_membertype_level', 'ext.validators.Exist', 'className'=>'MemberType','attributeName'=>'membertype_level','allowEmpty'=>false),
			array('membermap_recommend_id', 'ext.validators.Exist', 'className'=>'Membermap',
				'attributeName'=>'membermap_id','criteria'=>['condition'=>"membermap_is_verify=1"],
				'allowEmpty'=>false,'except'=>'root'),

			//array('membermap_recommend_id', 'exist', 'className'=>'Membermap','attributeName'=>'membermap_id','message'=>'推荐人不存在'),
			
			array('membermap_parent_id', 'ext.validators.Exist', 'className'=>'Membermap','attributeName'=>'membermap_id',
				'allowEmpty'=>true,'criteria'=>['condition'=>'membermap_is_verify=1'],'except'=>'root'),
			//array('membermap_order','ext.validators.ExistArea','except'=>'root'),
			array('membermap_agent_id', 'ext.validators.Exist', 'className'=>'Membermap',
				'attributeName'=>'membermap_id','allowEmpty'=>true,
				'criteria'=>['condition'=>'membermap_is_verify=1 and membermap_is_agent=1'],'except'=>'root'),
			array('membermap_bond_id', 'ext.validators.Exist', 'className'=>'Membermap',
				'attributeName'=>'membermap_id','allowEmpty'=>true,'criteria'=>['condition'=>'membermap_is_verify=1']),
			array('membermap_agent_type', 'exist', 'className'=>'AgentType','attributeName'=>'agent_type_level'),
			array('membermap_verify_date, membermap_add_date,membermap_reg_member_id,membermap_layer_order,membermap_is_delete,membermap_buy_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('membermap_id, membermap_parent_id, membermap_recommend_id, membermap_membertype_level, membermap_is_goods,membermap_layer, membermap_path, membermap_recommend_path, membermap_recommend_number, membermap_recommend_under_number, membermap_child_number, membermap_sub_number, membermap_sub_product_count, membermap_recommend_under_product_count, membermap_product_count, membermap_agent_id, membermap_is_verify, membermap_is_agent, membermap_verify_date, membermap_verify_member_id, membermap_add_date', 'safe', 'on'=>'search'),
			//注册条件验证
            //array('membermap_order','ext.validators.OpenArea_first_a','except'=>'root'),
			//array('membermap_order','ext.validators.OpenArea_a2_recommend','except'=>'root'),
			//array('membermap_recommend_id', 'ext.validators.OpenRecommend_141203'),
			//array('membermap_parent_id','ext.validators.OpenParent'),
			//array('membermap_membertype_level', 'ext.validators.OpenMemberType'),
			//array('membermap_order', 'ext.validators.OpenArea_141203'),
			//array('membermap_percent1', 'ext.validators.AblePercent1'),
			//array('membermap_percent2', 'ext.validators.AblePercent2'),
			//注册条件验证END
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
			'memberinfo'=>array(Model::BELONGS_TO,'Memberinfo','membermap_id'),
			'membermap' => array(Model::HAS_ONE, 'Memberinfo', 'memberinfo_id'),
			//'product' => array(Model::BELONGS_TO, 'Product', 'membermap_product_id'),
			'membermapParent' => array(Model::BELONGS_TO, 'Membermap', 'membermap_parent_id'),
			'parentInfo' => array(Model::BELONGS_TO, 'Memberinfo', 'membermap_parent_id'),
			'membermaps' => array(Model::HAS_MANY, 'Membermap', 'membermap_parent_id'),
			'membermapRecommend' => array(Model::BELONGS_TO, 'Membermap', 'membermap_recommend_id'),
			'recommendInfo' => array(Model::BELONGS_TO, 'Memberinfo', 'membermap_recommend_id'),
			'membermaps1' => array(Model::HAS_MANY, 'Membermap', 'membermap_recommend_id'),
			'membermapMembertypeLevel' => array(Model::BELONGS_TO, 'MemberType', 'membermap_membertype_level'),
			//'membermapProduct' => array(Model::BELONGS_TO, 'Product', 'membermap_product_id'),
			'membermapAgent' => array(Model::BELONGS_TO, 'Membermap', 'membermap_agent_id'),
			'membermaps2' => array(Model::HAS_MANY, 'Membermap', 'membermap_agent_id'),
			'membermapVerifyMember' => array(Model::BELONGS_TO, 'Memberinfo', 'membermap_verify_member_id'),
			'membertype' => array(Model::BELONGS_TO, 'MemberType', 'membermap_membertype_level'),
			'memberlevel' => array(Model::BELONGS_TO, 'MemberLevel', 'membermap_level'),
			'membermapbond'=>array(Model::BELONGS_TO, 'Membermap', 'membermap_bond_id'),
			'agenttype'=>array(Model::BELONGS_TO,'AgentType','membermap_agent_type')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$items=MemberinfoItem::model()->items;
		$new_labels=[];
		foreach($items as $itemKey=>$item)
		{
			if(strncmp('membermap_',$itemKey,10)==0)
				$new_labels[$itemKey]=$item['memberinfo_item_title'];
		}
		$labels=array(
			'membermap_id' => t('epmms','市场'),
			'membermap_parent_id' => t('epmms','接点人'),
			'membermap_recommend_id' => t('epmms','推荐人'),
			'membermap_membertype_level' => t('epmms','会员类型'),
			'membermap_layer' => t('epmms','接点层次'),
			'membermap_order' => t('epmms','接点位置'),
			'membermap_path' => t('epmms','接点路径'),
			'membermap_recommend_path' => t('epmms','推荐路径'),
			'membermap_recommend_number' => t('epmms','推荐个数'),
			'membermap_recommend_under_number' => t('epmms','推荐族人数'),
			'membermap_child_number' => t('epmms','接点下人数'),
			'membermap_sub_number' => t('epmms','接点族人数'),
			'membermap_sub_product_count' => t('epmms','接点下购买的产品'),
			'membermap_recommend_under_product_count' => t('epmms','推荐族下购买的产品'),
			//'membermap_product_id' => t('epmms','购买产品'),
			'membermap_product_count' => t('epmms','购买单数'),
			'membermap_agent_id' => t('epmms','代理中心'),
			'membermap_is_verify' => t('epmms','审核状态'),
			'membermap_is_agent' => t('epmms','是否代理中心'),
			'membermap_verify_date' => t('epmms','审核时间'),
			'membermap_verify_member_id' => t('epmms','审核会员'),
			'membermap_add_date' => t('epmms','注册日期'),
			'membermap_money'=> t('epmms','注册金额'),
			'membermap_bond_id'=> t('epmms','绑定会员'),
			'membermap_is_empty'=>t('epmms','是否空单'),
			'membermap_is_goods'=>t('epmms','提货方式'),
			'showName'=>t('epmms','登录账号'),
			'membermap_level'=>t('epmms','会员等级'),
			'membermap_is_delete'=>t('epmms','图谱删除'),
			'membermap_buyall'=>'购买累计',
            'membermap_verify_seq2'=>'金卡审核顺序',
            'membermap_expire_date'=>'会员到期时间'
		);
		return array_merge($labels,$new_labels);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$sort=new Sort('Membermap');
		$sort->defaultOrder=array('membermap_id'=>Sort::SORT_ASC);
		
		$criteria=new CDbCriteria;
		$criteria->compare('t.membermap_id',$this->membermap_id);
		$criteria->compare('membermap_layer',$this->membermap_layer);
		$criteria->compare('membermap_order',$this->membermap_order);
		$criteria->compare('membermap_path',$this->membermap_path,true);
		$criteria->compare('membermap_recommend_path',$this->membermap_recommend_path,true);
		$criteria->compare('membermap_recommend_number',$this->membermap_recommend_number);
		$criteria->compare('membermap_recommend_under_number',$this->membermap_recommend_under_number);
		$criteria->compare('membermap_recommend_product_count',$this->membermap_recommend_product_count);
		$criteria->compare('membermap_recommend_under_product_count',$this->membermap_recommend_under_product_count);
		$criteria->compare('membermap_child_number',$this->membermap_child_number);
		$criteria->compare('membermap_sub_number',$this->membermap_sub_number);
		$criteria->compare('membermap_under_number',$this->membermap_under_number);
		$criteria->compare('membermap_sub_product_count',$this->membermap_sub_product_count);
		$criteria->compare('membermap_under_product_count',$this->membermap_under_product_count);
		$criteria->compare('membermap_product_count',$this->membermap_product_count);
		$criteria->compare('membermap_agent_number',$this->membermap_agent_number);
		$criteria->compare('membermap_agent_product_count',$this->membermap_agent_product_count);
		$criteria->compare('membermap_is_verify',$this->membermap_is_verify);
		$criteria->compare('membermap_is_agent',$this->membermap_is_agent);
		$criteria->compare('membermap_verify_date',$this->membermap_verify_date,true);
		$criteria->compare('membermap_add_date',$this->membermap_add_date,true);
		$criteria->compare('memberinfo.memberinfo_account',@$this->memberinfo->memberinfo_account);
		$criteria->compare('membermapParent.membermap_id',@$this->membermapParent->membermap_id);
		$criteria->compare('membermapRecommend.membermap_id',@$this->membermapRecommend->membermap_id);
		$criteria->compare('membermapMembertypeLevel.membertype_name',@$this->membermapMembertypeLevel->membertype_name);
		$criteria->compare('membermapAgent.membermap_id',@$this->membermapAgent->membermap_id);
		$criteria->compare('membermapbond.membermap_id',@$this->membermapbond->membermap_id);
		$criteria->compare('membermapVerifyMember.memberinfo_account',@$this->membermapVerifyMember->memberinfo_account);
		$criteria->with=array('membermap','membermapParent','membermapRecommend');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
		));
	}
	public function getShowName()
	{
		if(is_null($this->_showName))
		{
			if(!empty($this->memberinfo))
			{
				$this->_showName=$this->memberinfo->showName;
			}
			elseif($model=Memberinfo::model()->findByPk($this->membermap_id))
			{
				$this->_showName=$model->showName;
			}
		}
		return $this->_showName;
	}
	/**
	 * 审核会员
	 */
	public function verify($verifyType)
	{

		if($this->membermap_is_verify==0 || $verifyType==8)
		{
			$transaction=webapp()->db->beginTransaction();
			try
			{
				if(webapp()->id=='141203'&& $this->exists('membermap_is_verify=1 and membermap_parent_id=:parent and membermap_order=:order',array(':parent'=>$this->membermap_parent_id,':order'=>$this->membermap_order)))
					throw new Error('位置已有人，请选择其它位置。',102);
				$parent=$this->membermapParent;

				$recommend=$this->membermapRecommend;
				$money=$money=$this->membermap_is_empty==1?0:$this->membertype->membertype_money;

				if(webapp()->id=='180501')
                {
                    $money=$this->membermap_is_empty==1?0:6800;
                }
				$agent=$this->membermapAgent;
				if($verifyType!=10 && webapp()->id != '150608')
				{
					if($agent->membermap_is_verify==0)
					{
						return EError::NOVERIFY_AGENT;//代理中心未审核
					}
				}

				//扣除电子币
				if($verifyType==1||$verifyType==5)
				{
					if($this->membermap_is_empty!=1)
					{
					    if($this->membermap_membertype_level==2)
                        {
                            $this->membermap_verify_seq2=new CDbExpression("nextverify2()");
                        }
						if(webapp()->id=='170621')
						{
							$agentFinance1 = $agent->memberinfo->getFinance(2);
							$agentFinance2 = $agent->memberinfo->getFinance(5);
						}
						else
							$agentFinance=$agent->memberinfo->getFinance(2);
						if(webapp()->id=='170621')
						{
							if (!$agentFinance1->deduct($money*$this->membermap_percent1/100))
							{
								throw new Error(t('epmms','电子币不足'));
								return EError::NOMONEY;//电子币不足
							}
							if (!$agentFinance2->deduct($money*$this->membermap_percent2/100))
							{
								throw new Error(t('epmms','注册币不足'));
								return EError::NOMONEY;//电子币不足
							}
						}
						elseif(!$agentFinance->deduct($money))
						{
							/*PC::debug($agentFinance->attributes);*/
							return EError::NOMONEY;//电子币不足
						}
					}
				}
				else if($verifyType==3)
                {
                    //自动复消
                    $bondFinance=Finance::getMemberFinance($this->membermap_bond_id,5);
                    if(!$bondFinance->deduct($money))
                    {
                        return EError::NOMONEY;//复消币不足
                    }
                }
				$this->membermap_money=$money;//扣除电子币的钱等于
				if(webapp()->id=='180501')
                {
                    $this->membermap_money=$this->membermap_is_empty==1?0:6000;
                }
				$status=SystemStatus::model()->find();
				if(webapp()->id=='180501')
                {
                    $status->system_status_income+=$this->membermap_is_empty==1?0:6800;
                }
                else
                {
                    $status->system_status_income+=$this->membermap_money;
                }


				$status->save();
				$this->membermap_is_verify=1;
				if($verifyType!=8)
				{
					if($verifyType!=10)
						$this->membermap_verify_date=new CDbExpression('now()');

					   $this->membermap_buy_date=new CDbExpression('now()');
					//使用序列不支持事务可能导致不连续，所以使用表来生成审核序列
					$this->membermap_verify_seq=new CDbExpression("nextverify()");
					if(webapp()->name!='console')
					{
						$this->membermap_verify_member_id=user()->isAdmin()?null:user()->id;
					}
					else
					{
						$this->membermap_verify_member_id=1;
					}
				}
				$this->membermap_product_count=$this->membermap_is_empty==1?0:$this->membertype->membertype_bill;

				$items=MemberinfoItem::model();
				//推荐关系
				if($items->itemVisible('membermap_recommend_id')==true)
				{
					

					if(is_null($recommend))
					{
						$this->membermap_recommend_layer=1;
						$this->membermap_recommend_path='/1';
						$this->saveAttributes(['membermap_recommend_layer','membermap_recommend_path']);
					}
					else
					{
						
						if($recommend->membermap_is_verify==0 || is_null($recommend->membermap_recommend_path))
						{
							
							/*
							$recommend=Membermap::model()->findByPk(1);
							$this->membermap_recommend_id=1;*/

							$transaction->rollback();
							return EError::NORECOMMEND;
						}
						$this->membermap_recommend_layer=$recommend->membermap_recommend_layer+1;
						//推荐关系计算
						//推荐个数加1
						$recommend->membermap_recommend_number=$recommend->membermap_recommend_number+1;

						$recommend->membermap_recommend_product_count=$recommend->membermap_recommend_product_count+$this->membermap_product_count;
						$recommend->saveAttributes(array('membermap_recommend_number','membermap_recommend_product_count'));
						$this->membermap_recommend_path=$recommend->membermap_recommend_path . '/' . $recommend->membermap_recommend_number;
						//membermap_recommend_under_number每个推荐先族的推荐派生族个数加1
						//部门人数

						$updateRecommendUnderNumber=new CDbCriteria();
						$updateRecommendUnderNumber->addCondition($this->getAncestryCondition2(false,'membermap_recommend_path'));
						$this->updateAll(array('membermap_recommend_under_number'=>new CDbExpression('membermap_recommend_under_number+1')),$updateRecommendUnderNumber);
						//membermap_recommend_under_product_count 推荐派生族总单数

						$updateUnderProductCount_sql='update epmms_membermap as u set membermap_recommend_under_product_count=u.membermap_recommend_under_product_count+:count
						where u.membermap_id in ( select membermap_id from get_upper_member_recommend2(:layer,:path))';
						$cmd=webapp()->db->createCommand($updateUnderProductCount_sql);
						$cmd->execute([':count'=>$this->membermap_product_count,':layer'=>$this->membermap_recommend_layer,':path'=>$this->membermap_recommend_path]);
						$Proc=new DbCall('gen_recommend_relation',array($this->membermap_id,$this->membermap_recommend_id));
						$Proc->run();
					}
				}

				//接点关系计算
			
				if ($items->itemVisible('membermap_parent_id') == false)
				{

					if (is_null($parent))
					{

						$this->membermap_layer = 1;
						$this->membermap_path = '/1';
						$this->saveAttributes(['membermap_layer', 'membermap_path']);
					}
					else
					{
						if ($parent->membermap_is_verify == 0 || is_null($parent->membermap_path))
						{
							$transaction->rollback();
							return EError::NOPARENT;
						}
						$parent->membermap_child_number = $parent->membermap_child_number + 1;
						$parent->membermap_sub_number = $parent->membermap_child_number;
						$parent->membermap_sub_product_count = $parent->membermap_sub_product_count + $this->membermap_product_count;

						// if (MemberinfoItem::model()->itemVisible('membermap_parent_id') == true && MemberinfoItem::model()->itemVisible('membermap_order') == false)
						// {
						if (MemberinfoItem::model()->itemVisible('membermap_parent_id') == false && MemberinfoItem::model()->itemVisible('membermap_order') == true)
						{
							//太阳线，自动分配位置
							// $this->membermap_order = $parent->membermap_child_number;
							//$this->saveAttributes(['membermap_order']);
						}
						$parent->saveAttributes(array('membermap_child_number', 'membermap_sub_number', 'membermap_sub_product_count'));
						$this->membermap_layer = $parent->membermap_layer + 1;
						$this->membermap_path = $parent->membermap_path . '/' . $this->membermap_order;
						$Proc=new DbCall('gen_parent_relation',array($this->membermap_id,$this->membermap_parent_id));
						$Proc->run();
						$bcnt=config('map','branch');
						if($bcnt==2)
						{
							if(webapp()->id=='170329')
							{
								$layer_proc=new DbCall('update_left_right_layer', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
								$layer_proc->run();
								$layer_proc=new DbCall('update_left_right_layer_count', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
								$layer_proc->run();
								$count_proc=new DbCall('update_left_right_count', array($this->membermap_id, $this->membermap_path));
								$count_proc->run();
                                //$Proc = new DbCall('update_left_right_product_count', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count));
                                $Proc = new DbCall('update_left_right_product_count_non_first', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
							}
							else
                            {
                                $count_proc=new DbCall('update_left_right_count', array($this->membermap_id, $this->membermap_path));
                                $count_proc->run();
                                $Proc=new DbCall('update_left_right_product_count',array($this->membermap_id,$this->membermap_path,$this->membermap_product_count));
                                $Proc->run();
                                $Proc=new DbCall('update_left_right_product_count2',array($this->membermap_id,$this->membermap_path,$this->membermap_product_count));
                                $Proc->run();
                                $Proc=new DbCall('update_left_right_product_count3',array($this->membermap_id,$this->membermap_path,$this->membermap_product_count));
                                $Proc->run();
                            }
						}
						//部门人数

						$updateUnderNumber = new CDbCriteria();
						$updateUnderNumber->addCondition($this->getAncestryCondition2(false, 'membermap_path'));
						$this->updateAll(array('membermap_under_number' => new CDbExpression('membermap_under_number+1')), $updateUnderNumber);

						$updateUnderProductCount_sql='update epmms_membermap as u set membermap_under_product_count=u.membermap_under_product_count+:count
						from epmms_parent_relation as p where p.parent_relation_member_id=:id and u.membermap_id=p.parent_relation_upper_id';
						$cmd=webapp()->db->createCommand($updateUnderProductCount_sql);
						$cmd->execute([':id'=>$this->membermap_id,':count'=>$this->membermap_product_count]);
					}
				}

				if(!$this->save(false))
				{
					throw new Error('审核出错',EError::ERROR);
				}
			}
			catch(Error $e)
			{
				$transaction->rollback();
				throw $e;
			}
			catch(CDbException $e)
			{
				$transaction->rollback();
				throw $e;
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				throw $e;
			}
			$transaction->commit();
			return EError::SUCCESS;
		}
		else
			return EError::DUPLICATE;
	}

	/**
	 * 重新计算网络图
	 */
	public function reMapRecommend()
	{
		if($this->membermap_is_verify==1)
		{
			$transaction = webapp()->db->beginTransaction();
			$recommend = $this->membermapRecommend;

			$items = MemberinfoItem::model();
			//推荐关系
			if ($items->itemVisible('membermap_recommend_id') == true)
			{
				if (is_null($recommend))
				{
					$this->membermap_recommend_layer = 1;
					$this->membermap_recommend_path = '/1';
					$this->saveAttributes(['membermap_recommend_layer', 'membermap_recommend_path']);
				}
				else
				{
					if ($recommend->membermap_is_verify == 0 || is_null($recommend->membermap_recommend_path))
					{
						/*
						$recommend=Membermap::model()->findByPk(1);
						$this->membermap_recommend_id=1;*/

						$transaction->rollback();
						throw new Error('没有推荐人', EError::NORECOMMEND);
						return EError::NORECOMMEND;
					}
					$this->membermap_recommend_layer = $recommend->membermap_recommend_layer + 1;
					//推荐关系计算
					//推荐个数加1
					$recommend->membermap_recommend_number = $recommend->membermap_recommend_number + 1;
					$recommend->membermap_recommend_product_count = $recommend->membermap_sub_product_count + $this->membermap_product_count;
					$recommend->saveAttributes(array('membermap_recommend_number', 'membermap_recommend_product_count'));
					$this->membermap_recommend_path = $recommend->membermap_recommend_path . '/' . $recommend->membermap_recommend_number;
					//membermap_recommend_under_number每个推荐先族的推荐派生族个数加1
					//部门人数

					$updateRecommendUnderNumber=new CDbCriteria();
					$updateRecommendUnderNumber->addCondition($this->getAncestryCondition2(false,'membermap_recommend_path'));
					$this->updateAll(array('membermap_recommend_under_number'=>new CDbExpression('membermap_recommend_under_number+1')),$updateRecommendUnderNumber);
					//membermap_recommend_under_product_count 推荐派生族总单数

/*					$updateUnderProductCount_sql='update epmms_membermap as u set membermap_recommend_under_product_count=u.membermap_recommend_under_product_count+:count
						where u.membermap_id in ( select membermap_id from get_upper_member_recommend2(:layer,:path))';
					$cmd=webapp()->db->createCommand($updateUnderProductCount_sql);
					$cmd->execute([':count'=>$this->membermap_product_count,':layer'=>$this->membermap_recommend_layer,':path'=>$this->membermap_recommend_path]);*/
/*					$Proc=new DbCall('gen_recommend_relation',array($this->membermap_id,$this->membermap_recommend_id));
					$Proc->run();*/
				}
				if (!$this->save(false))
				{
					throw new Error('审核出错', EError::NOSAVE);
				}
				$transaction->commit();
				return EError::SUCCESS;
			}
		}
		return EError::SUCCESS;
	}
	/**
	 * 重新计算网络图
	 */
	public function reMapParent()
	{
		//echo $this->membermap_id . "\r\n";
		if($this->membermap_is_verify==1)
		{
			$transaction = webapp()->db->beginTransaction();
			$parent = $this->membermapParent;
			//echo $this->memberinfo->memberinfo_account . "\r\n";
			$items = MemberinfoItem::model();
			//接点关系计算
			if ($items->itemVisible('membermap_parent_id') == true)
			{
				if (is_null($parent))
				{
					$this->membermap_layer = 1;
					$this->membermap_path = '/1';
					$this->saveAttributes(['membermap_layer', 'membermap_path']);
				}
				else
				{
					if ($parent->membermap_is_verify == 0 || is_null($parent->membermap_path))
					{
						$transaction->rollback();
						throw new Error('没有接点人', EError::NOPARENT);
						return EError::NOPARENT;
					}
					$parent->membermap_child_number = $parent->membermap_child_number + 1;
					$parent->membermap_sub_number = $parent->membermap_child_number;
					$parent->membermap_sub_product_count = $parent->membermap_sub_product_count + $this->membermap_product_count;

					if (MemberinfoItem::model()->getAdminItem('membermap_parent_id') == true && MemberinfoItem::model()->getAdminItem('membermap_order') == false)
					{
						//太阳线，自动分配位置
						$this->membermap_order = $parent->membermap_child_number;
						$this->saveAttributes(['membermap_order']);
					}
					$parent->saveAttributes(array('membermap_child_number', 'membermap_sub_number', 'membermap_sub_product_count'));
					$this->membermap_layer = $parent->membermap_layer + 1;
					$this->membermap_path = $parent->membermap_path . '/' . $this->membermap_order;
					$Proc=new DbCall('gen_parent_relation',array($this->membermap_id,$this->membermap_parent_id));
					$Proc->run();
					$bcnt=config('map','branch');
					if($bcnt==2)
					{
						if(webapp()->id=='170329')
						{
							$layer_proc=new DbCall('update_left_right_layer', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
							$layer_proc->run();
							$layer_proc=new DbCall('update_left_right_layer_count', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
							$layer_proc->run();
							$count_proc=new DbCall('update_left_right_count', array($this->membermap_id, $this->membermap_path));
							$count_proc->run();
                            //$Proc = new DbCall('update_left_right_product_count', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count));
                            $Proc = new DbCall('update_left_right_product_count_non_first', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
						}
						else
							$Proc=new DbCall('update_left_right_product_count',array($this->membermap_id,$this->membermap_path,$this->membermap_product_count));
						$Proc->run();
					}
					//部门人数

					$updateUnderNumber=new CDbCriteria();
					$updateUnderNumber->addCondition($this->getAncestryCondition2(false,'membermap_path'));
					$this->updateAll(array('membermap_under_number'=>new CDbExpression('membermap_under_number+1')),$updateUnderNumber);

					$updateUnderProductCount_sql='update epmms_membermap as u set membermap_under_product_count=u.membermap_under_product_count+:count
					from epmms_parent_relation as p where p.parent_relation_member_id=:id and u.membermap_id=p.parent_relation_upper_id';
					$cmd=webapp()->db->createCommand($updateUnderProductCount_sql);
					$cmd->execute([':id'=>$this->membermap_id,':count'=>$this->membermap_product_count]);
				}
			}

			if (!$this->save(false))
			{
				throw new Error('审核出错', EError::NOSAVE);
			}
			$transaction->commit();
			return EError::SUCCESS;
		}
		return EError::SUCCESS;
	}
	public function upgrade($level=null)
	{

	
	
		if($this->membermap_is_verify==0)
		{

			return EError::NOVERIFY;
			throw new Error('未审核的会员不能执行这项操作');
		}
	
		
		if($this->membermap_membertype_level<$level)
		{
				
			$membertype=MemberType::model()->findByPk($level);
			$transaction=webapp()->db->beginTransaction();
			try
			{
				if($this->membermap_id==1)
				{

					$this->membermap_membertype_level=$level;
					$this->membermap_product_count=$membertype->membertype_bill;
					$this->membermap_money=$membertype->membertype_money;
					$this->membermap_buy_date=new CDbExpression('now()');
					if($this->saveAttributes(['membermap_product_count','membermap_money','membermap_membertype_level','membermap_buy_date']))
					{
						$transaction->commit();
						return EError::SUCCESS;
					}
					else
					{

						$transaction->rollback();
						return EError::NOSAVE;
					}
				}
			
				$recommend=$this->membermapRecommend;
				$parent=$this->membermapParent;
				$agent=$this->membermapAgent;
				//扣除电子币

				$agentFinance=$this->memberinfo->getFinance(2);

				 $type_money=MemberType::model()->findByAttributes(['membertype_level'=>$level]);
				// Finance::getMemberFinance($this->membermap_id,1)->add($type_money->membertype_money);//送报单币
				// if($level==3){
				 Finance::getMemberFinance($this->membermap_id,3)->add($type_money->membertype_money);//送积分
                
    //                  $model=new Agent('create');
    //                  $model->agent_memberinfo_id=user()->id;
                  
    //                  $model->agent_add_date=new CDbExpression('now()');

    //                 if($model->save(false)){
    //                 	if($model->verifyAgent()!=EError::SUCCESS)
    //                 	{
                    		
				// 			throw new EError('审核店铺失败');
    //                 	}

    //                 }else{
    //                     throw new EError('升级店铺失败');
    //                 }

                                     

				// }

			    $money=$membertype->membertype_money;
			 
				if(webapp()->id=='140805')
				{
					$agent_money=$membertype->membertype_agent_money-$this->membermap_agent_money;
                    
				}
				
				// if(!$agentFinance->deduct(webapp()->id=='140805'?$agent_money:$money))//扣电子币
				if(!$agentFinance->deduct(webapp()->id=='140805'?$agent_money:2997))//扣电子币
				{
					return EError::NOMONEY;
					throw new Error('电子币不足或扣除电子币失败');
				}
				
				//计算差额单
				$status=SystemStatus::model()->find();
				$status->system_status_income+=$money;
				$status->save();

				$this->membermap_membertype_level=$level;
	
				$this->membermap_product_count=$membertype->membertype_bill/*-$this->membermap_product_count*/;

				$this->membermap_money=$money;
				//升级补发股权
/*				$finance=Finance::getMemberFinance($this->membermap_id,3);
				$finance->finance_award=$membertype->membertype_money/6*5;
				$finance->saveAttributes(['finance_award']);*/
                //$finance=Finance::getMemberFinance($this->membermap_id,3);
                //$finance->finance_award=$finance->finance_award+$money*5;
                //$finance->saveAttributes(['finance_award']);
				$this->saveAttributes(['membermap_product_count','membermap_money','membermap_membertype_level']);
				$items=MemberinfoItem::model();
				//推荐关系
				if($items->itemVisible('membermap_recommend_id')==true)
				{
					//membermap_recommend_under_product_count 推荐派生族总单数
					$updateRecommendUnderProductCount=new CDbCriteria();
					$updateRecommendUnderProductCount->addCondition("'{$this->membermap_recommend_path}' like membermap_recommend_path || '%'");
					$this->updateAll(
						array(
						'membermap_recommend_under_product_count'=>
						new CDbExpression(
							'membermap_recommend_under_product_count+:product_count',
							array(':product_count'=>$this->membermap_product_count)
						)
					),$updateRecommendUnderProductCount
					);
					$recommend->membermap_recommend_product_count=$recommend->membermap_recommend_product_count+$this->membermap_product_count;
					$recommend->saveAttributes(['membermap_recommend_product_count']);
				}

				//接点关系计算
				if($items->itemVisible('membermap_parent_id')==true)
				{
					//membermap_sub_product_count派生族总单数
					$parent->membermap_sub_product_count=$parent->membermap_sub_product_count+$this->membermap_product_count;
					$parent->saveAttributes(['membermap_sub_product_count']);
					$updateSubProductCount=new CDbCriteria();
					$updateSubProductCount->addCondition($this->getAncestryCondition());
					$this->updateAll(array('membermap_under_product_count'=>new CDbExpression(
							'membermap_under_product_count+:product_count',
							array(':product_count'=>$this->membermap_product_count))),
						$updateSubProductCount);
				}
				$agent->membermap_agent_product_count=$agent->membermap_agent_product_count+$this->membermap_product_count;

				$agent->saveAttributes(['membermap_agent_product_count','membermap_agent_number']);
				$bcnt=config('map','branch');
				if($bcnt==2)
				{
					if(webapp()->id=='170329')
					{
						$layer_proc=new DbCall('update_left_right_layer', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
						$layer_proc->run();
						$layer_proc=new DbCall('update_left_right_layer_count', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
						$layer_proc->run();
						$count_proc=new DbCall('update_left_right_count', array($this->membermap_id, $this->membermap_path));
						$count_proc->run();
						//$Proc = new DbCall('update_left_right_product_count', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count));
                        $Proc = new DbCall('update_left_right_product_count_non_first', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count,$this->membermap_layer));
                        $Proc->run();
					}
					else
                    {
                        $Proc = new DbCall('update_left_right_product_count', array($this->membermap_id, $this->membermap_path, $this->membermap_product_count));
                        $Proc->run();
                        $Proc=new DbCall('update_left_right_product_count2',array($this->membermap_id,$this->membermap_path,$this->membermap_product_count));
                        $Proc->run();
                        $Proc=new DbCall('update_left_right_product_count3',array($this->membermap_id,$this->membermap_path,$this->membermap_product_count));
                        $Proc->run();
                    }

				}
				Yii::import('ext.award.Upgrade');
				$mysystem=new Upgrade($this);
                $mysystem->run(1,0,0);
				$upgrade=MemberUpgrade::model()->findByAttributes(['member_upgrade_member_id'=>$this->membermap_id]);
				//$upgrade->member_upgrade_period=$mysystem->period;
				$upgrade->save();

				$this->membermap_product_count=$membertype->membertype_bill;
				$this->membermap_money=$membertype->membertype_money;
				$this->saveAttributes(['membermap_product_count','membermap_money']);
				$transaction->commit();
			}
			catch(EError $e)
			{
				$transaction->rollback();
				throw $e;
				//return EError::ERROR;
			}
			catch(CDbException $e)
			{
                if($e->getCode()=='40001')
                {
                    if (webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['error'] = "当前系统繁忙，请重试";
                        echo CJSON::encode($data);
                        return;
                    }
                    else
                    {
                        return EError::DUPLICATE;
                    }
                }
				$transaction->rollback();
				throw $e;
				//return EError::ERROR;
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				throw $e;
				return EError::ERROR;
			}
			return EError::SUCCESS;
		}

		else

			return EError::DUPLICATE;
	}
	/**
	 * 生成会员的树结构jit
	 */
	public static function getTree($node,$treeLevels,$dataType,$isAjax=false,$branch)
	{

		$top_node=new stdClass();

		
		$top_node=Membermap::getNode($dataType,$node);

		$top_node['children']=Membermap::queryTree($node,$treeLevels,$treeLevels,$dataType,1,$isAjax,$branch);
		return $top_node;
	}
	/**
	 * 生成会员的树结构ztree
	 */
	public static function getTree2($node,$treeLevels,$dataType,$isAjax=false)
	{

		if($isAjax)
		{
			$json_tree=Membermap::queryTree($node,$treeLevels,$treeLevels,$dataType,2,$isAjax);

			return $json_tree;
		}
		else
		{
			$top_node=new stdClass();
			$top_node=Membermap::getNode2($isAjax,$node);
			$top_node['children']=Membermap::queryTree($node,$treeLevels,$treeLevels,$dataType,2,$isAjax);
			return $top_node;
		}

	}
	/**
	 * 递归查询构建树
	 * $getType 图形类型
	 */
	public static function queryTree($node,$treeLevels,$level,$dataType,$getType=1,$isAjax,$branch=0)
	{

		$tree=array();
		if(webapp()->id=='161207' && !user()->isAdmin())
		{
			$mynode=user()->getMap();
			if($node->membermap_recommend_layer-$mynode->membermap_recommend_layer>5)
				return $tree;
		}
		$subnode_cnt=0;//nodeId指父节点id
		if($level>=1)
		{
			$query_user=Membermap::model()->findAll(['condition'=>$dataType=='parent'?'t.membermap_parent_id=:nodeId':
					't.membermap_recommend_id=:nodeId','order'=>'t.membermap_order asc','with'=>['membermap','membertype'],
				'params'=>[':nodeId'=>$node->membermap_id]]);

			if($dataType=='parent')
			{
				$items=MemberinfoItem::model();
				if($items->getAdminItem('membermap_parent_id')==true && $items->getAdminItem('membermap_order')==false)
				{
					//太阳线，有多个点位显示多少
					$branch=$node->membermap_child_number;
				}
				for($i=1;$i<=$branch;$i++)
				{
					if($user=Membermap::model()->find('membermap_parent_id=:parent and membermap_order=:order',
						[':parent'=>$node->membermap_id,':order'=>$i]))
					{
						$user_node=$getType==1?Membermap::getNode($dataType,$user,$node,$i):Membermap::getNode2($isAjax,$user);
						if(Membermap::model()->exists('membermap_parent_id=:pid',[':pid'=>$user->membermap_id]))
						{
							$children=Membermap::queryTree($user,$treeLevels,$level-1,$dataType,$getType,$isAjax,$branch);
							$user_node['children']=$children;
						}
						if(isset($children) && $children!=[])
							$user_node['isParent']=true;
						$tree[]=$user_node;
					}
					elseif($items->getAdminItem('membermap_parent_id')==true && $items->getAdminItem('membermap_order')==true)
					{
						//空点位
						$empty_node=Membermap::getNode($dataType,null,$node,$i);
						$empty_node['data']['$user_order']=chr($i+64);
						$tree[]=$empty_node;
					}
				}
				if($items->getAdminItem('membermap_parent_id')==true && $items->getAdminItem('membermap_order')==false)
				{
					//太阳线，最后一个点位可注册新会员
					$empty_node=Membermap::getNode($dataType,null,$node,$i);
					$empty_node['data']['$user_order']=$i;
					$tree[]=$empty_node;
				}
				/*
				while($subnode_cnt<$branch&&$subnode_cnt>0&&$dataType=='parent'&&$getType==1)
				{
					$empty_node=Membermap::getNode($dataType);
					$empty_node['data']['$user_order']=chr($subnode_cnt+1+64);
					$tree[]=$empty_node;
					$subnode_cnt++;
				}*/
			}
			else
			{

				foreach($query_user as $user)
				{
					// echo "<pre>";
					// var_dump($node);
					// var_dump($user->toArray());
					// die;
				
					$user_node=$getType==1?Membermap::getNode($dataType,$user,$node):Membermap::getNode2($isAjax,$user);
				
					$children=Membermap::queryTree($user,$treeLevels,$level-1,$dataType,$getType,$isAjax,$branch);
					$user_node['children']=$children;
					if($children!=[])
						$user_node['isParent']=true;
					if( $level==1 && self::haveChildren($user,$dataType) )
					{
						$user_node['isParent']=true;
					}
					$tree[]=$user_node;
					$subnode_cnt++;
				}
			}
		}
		return $tree;
	}
	public static function haveChildren($node,$dataType)
	{
		$query_user=Membermap::model()->exists(['condition'=>$dataType=='parent'?'t.membermap_parent_id=:nodeId':
				't.membermap_recommend_id=:nodeId','order'=>'t.membermap_order asc',
			'params'=>[':nodeId'=>$node->membermap_id]]);
		return $query_user;
	}

	/**
	 * 把model转换为jit的json节点
	 */
	public static function getNode($dataType,$user=null,$parent=null,$order=null)
	{

		
		$node=array();
		$node['id']=is_null($user)?uniqid('jit_',true): ('jit_' . $user->membermap_id);
		$node['name']=is_null($user)?'空点位':$user->memberinfo->memberinfo_account;
		$node['data']=array();
		$node['children']=[];

		if($dataType=='parent')
		{
			if(is_null($user))
			{

				$html_reg=CHtml::link('注册',['memberinfo/create','Membermap[membermap_parent_id]'=>$parent->membermap_id,'Membermap[membermap_order]'=>$order],['class'=>'orgmap_reg_link']);
			
				$node['data']['info']=$html_reg;
				$node['data']['$type']='ellipse';
			}
			elseif($user['membermap_is_verify']==0)
			{
				$node['data']['info']=$user->memberinfo->memberinfo_account . '<br/>' . t('epmms','未审核');
				$node['data']['$type']='ellipse';
			}
			else
			{
				$level_str='';
				$item_model=MemberinfoItem::model();
				if($item_model->getViewItem('membermap_level'))
				{
					if(!is_null($user))
						$level_str='<br/>'. t('epmms','等级') . ':'  . (is_null($user->membermap_level)?'无':$user->memberlevel->showName);
				}
				$str_group='';
				if(webapp()->id=='140712')
					$str_group='<br/>' . '组:' . $user->membermapbond->showName;

				$area_data=[];
				$str_table='<br/><table class="map">';
/*                $str_table.='<tr>';
                $str_table.='<td>人</td>';
                for($i=1;$i<=config('map','branch');$i++)
                {
                    $str_table.='<td>';
                    $product_count=new DbEvaluate('iif(:order=1,left_count,right_count) from epmms_memberstatus where status_id=:id',[':id'=>$user->membermap_id,':order'=>$i]);
                    $str_table.=$product_count;
                    $str_table.='</td>';
                }
                $str_table.='</tr>';*/
				$str_table.='<tr>';
                $str_table.='<td>总</td>';
				for($i=1;$i<=config('map','branch');$i++)
				{
					$str_table.='<td>';
					$product_count=new DbEvaluate('get_order_under_product_count(:id,:order)',[':id'=>$user->membermap_id,':order'=>$i]);
					$str_table.=$product_count;
					$area_data[$i]=(string)$product_count;
					$str_table.='</td>';
				}
				$str_table.='</tr>';
/*                $str_table.='<tr>';
                $str_table.='<td>余</td>';
                for($i=1;$i<=config('map','branch');$i++)
                {
                    $str_table.='<td>';
                    $product_count=new DbEvaluate('iif(:order=1,left_product_count-status_pair,right_product_count-status_pair) from epmms_memberstatus where status_id=:id',[':id'=>$user->membermap_id,':order'=>$i]);
                    $str_table.=$product_count;
                    $str_table.='</td>';
                }
				$str_table.='</tr>';*/
				$str_table.='</table>';

				//$str_count='<br/>部门人数：'. $user->membermap_under_number;
				$node['data']['$type']='rectangle';
				$node['data']['area_data']=$area_data;
				$node['data']['levelName']=(is_null($user->membermap_level)?'无':$user->memberlevel->showName);
				$node['data']['typeName']=$user->membermapMembertypeLevel->membertype_name;
				$str_nickname='&nbsp;&nbsp;(' . $user->memberinfo->memberinfo_nickname . ')';
				$str_nickname.="<br/>" . $user->membermapMembertypeLevel->membertype_name;
				$str_table="<br/>";
				$node['data']['info']=$user->memberinfo->memberinfo_account  . $str_nickname  . $str_table .  '<span class="infotime">' . date('y-m-d H:i',strtotime($user->membermap_verify_date)) . '</span>';
			}
		}
		else
		{

			$node['data']['$type']=is_null($user)?'ellipse':'rectangle';
			$node['data']['info']=is_null($user)?'空点位':($user->membermap->memberinfo_account);
			if(!is_null($user))
			{
				$node['data']['info'].='<br/>推荐单数：'. $user->membermap_recommend_product_count;
				$node['data']['info'].='<br/>部门单数：'. $user->membermap_recommend_under_product_count;
			}
		}
		$items=MemberinfoItem::model();
		if($items->getAdminItem('membermap_parent_id')==true && $items->getAdminItem('membermap_order')==false)
		{
			//太阳线，有多个点位显示多少
			$node['data']['$user_order']=is_null($user)?null:$user->membermap_order;
		}
		else
		{
			$node['data']['$user_order'] = is_null($user) ? null : chr($user->membermap_order + 64);
		}
		$node['data']['$color']=is_null($user)?'#959595':'#'. $user->membertype->membertype_color;
		if(is_null($user))
		{
			$node['data']['tip']='空点位';
		}
		elseif($user['membermap_is_verify']==0)
		{
			$node['data']['tip']='未审核';
		}
		else
		{
			$r=$user->membermapRecommend;
			$str_r='';
			if(!is_null($r))
			{
				$str_r='<br/>推荐人:' . $r->showName;
			}
			$str_r_number='<br/>推荐数:' . $user->membermap_recommend_number;
			$str_name='昵称:' . $user->memberinfo->memberinfo_nickname;
			$str_verify_date= '<br/>审核时间:' . date('y-m-d H:i:s',strtotime($user->membermap_verify_date));
			$mtype='<br/>会员类型:' . $user->membertype->showName;
			$mlevel='<br/>会员级别:' . (is_null($user->memberlevel)?'':$user->memberlevel->member_level_name);
			$mtype2='<br/>注册金额:' . $user->membermap_money;
			$node['data']['tip']=$str_name . $mtype . $mtype2 . $mlevel . $str_r . $str_r_number  . $str_verify_date;
		}
		return $node;
	}
	/**
	 * 把model转换为tree的json节点
	 */
	public static function getNode2($isAjax,$user=null)
	{


		$node=[];
		$node['id']=is_null($user)?uniqid('jit_',true):$user->membermap_id;

		$node['open']=$isAjax==true?false:true;
		if(!is_null($user) && $user['membermap_is_verify']==0)
		{
		
			$node['name']=$user->memberinfo->memberinfo_account;
			$node['tip']='未审核';
		}
		else
		{
			// echo "<pre>";
			// var_dump($user);// 对象可直接访问membermap表，关联memberinfo和membertype表通过 $user->membertype->membertype_level访问 _related下面的attributes
			
			
			
			 // $name=Memberinfo::model()->find('memberinfo_id='.$user->membermap_parent_id);
		
			// $node['name']=is_null($user)?'空点位':'账号:'.$user->memberinfo->memberinfo_account.'昵称:'.$user->memberinfo->memberinfo_nickname.'节点人:'.$name->memberinfo_nickname.'节点人位置:'.$user->membermap_order;
				$node['name']=is_null($user)?'空点位':$user->memberinfo->memberinfo_account;
			$node['tip']=is_null($user)?'空点位':'昵称:' . $user->memberinfo->memberinfo_nickname . '<br/>推荐数:' . $user->membermap_recommend_number . "<br/>审核时间:" . date('Y-m-d H:i:s',strtotime($user->membermap_verify_date));
		}
		return $node;
	}
	public static function getColor($level)
	{
		if(is_null($level) || $level==0)
		{
			return '5c5c5c';
		}
		else if($level>=1 && $level<4)
		{
			return '92D050';
		}
		else if($level>=4 && $level<7)
		{
			return '00B0F0';
		}
		else if($level>=7 && $level<10)
		{
			return 'FF0000';
		}
		else
		{
			return '7030A0';
		}
	}
	/**
	 * 得到先族的where条件
	 * @param boolean $full 是否包含自己
	 * @return string
	 */
	public function getAncestryCondition($full=false)
	{
		if($full)
			return "'{$this->membermap_path}' like membermap_path || '%'";
		else
			return "'{$this->membermap_path}' like membermap_path || '/%'";
	}
	/**
	 * 得到派生族的where条件
	 * @param boolean $full 是否包含自己
	 * @return string
	 */
	public function getProgenyCondition($full=false)
	{
		if($full)
			return "membermap_path like '{$this->membermap_path}%'";
		else
			return "membermap_path like '{$this->membermap_path}/%'";
	}
	/**
	 * 得到先族的where条件
	 * @param boolean $full 是否包含自己
	 * @return string
	 */
	public function getAncestryCondition2($full=false,$fieldName='membermap_recommend_path')
	{
		if($full)
			return "'{$this->$fieldName}' like $fieldName || '%'";
		else
			return "'{$this->$fieldName}' like $fieldName || '/%'";
	}
	/**
	* 得到派生族的where条件
	* @param boolean $full 是否包含自己
	* @return string
	*/
	public function getProgenyCondition2($full=false,$fieldName='membermap_recommend_path')
	{
		if($full)
			return "$fieldName like '{$this->$fieldName}%'";
		else
			return "$fieldName like '{$this->$fieldName}/%'";
	}

	public function getListData($condition=array('with'=>array('memberinfo'),'condition'=>'membermap_is_verify=1'))
	{
		return CHtml::listData($this->findAll($condition),$this->tableSchema->primaryKey,'showName');
	}
	/**
	 * Saves the current record.
	 *
	 * The record is inserted as a row into the database table if its {@link isNewRecord}
	 * property is true (usually the case when the record is created using the 'new'
	 * operator). Otherwise, it will be used to update the corresponding row in the table
	 * (usually the case if the record is obtained using one of those 'find' methods.)
	 *
	 * Validation will be performed before saving the record. If the validation fails,
	 * the record will not be saved. You can call {@link getErrors()} to retrieve the
	 * validation errors.
	 *
	 * If the record is saved via insertion, its {@link isNewRecord} property will be
	 * set false, and its {@link scenario} property will be set to be 'update'.
	 * And if its primary key is auto-incremental and is not set before insertion,
	 * the primary key will be populated with the automatically generated key value.
	 *
	 * @param boolean $runValidation whether to perform validation before saving the record.
	 * If the validation fails, the record will not be saved to database.
	 * @param array $attributes list of attributes that need to be saved. Defaults to null,
	 * meaning all attributes that are loaded from DB will be saved.
	 * @return boolean whether the saving succeeds
	 */
	public function save($runValidation=true,$attributes=null)
	{
		if(!$runValidation || $this->validate($attributes))
		{
			return $this->getIsNewRecord() ? $this->insert($attributes) : $this->update($attributes);
		}
		else
			return false;
	}
	public function delete()
	{
		if($this->membermap_is_verify==1)
			return false;
		return parent::delete();
	}
	public function afterFind()
	{
		//$this->membermap_is_empty=$this->membermap_is_empty===0?false:true;
	}
	/*public function getS($json_tree,$id){
       static $list=array();
     
       foreach ($json_tree['data'] as $key => $value) {
        
       }
	}*/
}
