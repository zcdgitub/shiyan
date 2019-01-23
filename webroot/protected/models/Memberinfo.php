<?php

/**
 * This is the model class for table "{{memberinfo}}".
 *
 * The followings are the available columns in table '{{memberinfo}}':
 * @property string $memberinfo_id
 * @property string $memberinfo_account
 * @property string $memberinfo_password
 * @property string $memberinfo_password2
 * @property string $memberinfo_name
 * @property string $memberinfo_nickname
 * @property string $memberinfo_email
 * @property string $memberinfo_mobi
 * @property string $memberinfo_phone
 * @property string $memberinfo_qq
 * @property string $memberinfo_msn
 * @property integer $memberinfo_sex
 * @property integer $memberinfo_idcard_type
 * @property string $memberinfo_idcard
 * @property string $memberinfo_zipcode
 * @property string $memberinfo_birthday
 * @property string $memberinfo_address_provience
 * @property string $memberinfo_address_area
 * @property string $memberinfo_address_county
 * @property string $memberinfo_address_detail
 * @property string $memberinfo_bank_id
 * @property string $memberinfo_bank_name
 * @property string $memberinfo_bank_account
 * @property string $memberinfo_bank_provience
 * @property string $memberinfo_bank_area
 * @property string $memberinfo_bank_branch
 * @property string $memberinfo_question
 * @property string $memberinfo_answer
 * @property string $memberinfo_memo
 * @property integer $memberinfo_is_enable
 * @property string $memberinfo_register_ip
 * @property string $memberinfo_last_ip
 * @property string $memberinfo_last_date
 * @property string $memberinfo_add_date
 * @property string $memberinfo_mod_date
 * @property integer $memberinfo_is_agent
 * @property integer $memberinfo_is_verify
 * @property string $memberinfo_postoffice
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Memberinfo extends Model
{
	public $modelName='会员';
	public $nameColumn='memberinfo_account';
	public $parentInfo_account;
	public $recommendInfo_account;
    public $memberinfo_password='123456';
    public $memberinfo_password_repeat='123456';
	public $memberinfo_password2='222222';
	public $memberinfo_password_repeat2='222222';
	public $memberinfo_msg_verify;
	// public $member
    private $_useLibreSSL;
    private $_randomFile;

    /**
     * @var int Default cost used for password hashing.
     * Allowed value is between 4 and 31.
     * @see generatePasswordHash()
     * @since 2.0.6
     */
    public $passwordHashCost = 13;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Memberinfo the static model class
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
		return '{{memberinfo}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$required_field='memberinfo_name, memberinfo_nickname, memberinfo_email, memberinfo_mobi, memberinfo_phone, memberinfo_qq, memberinfo_msn, memberinfo_sex, memberinfo_idcard_type, memberinfo_idcard, memberinfo_zipcode, memberinfo_birthday, memberinfo_address_provience, memberinfo_address_area, memberinfo_address_county, memberinfo_address_detail, memberinfo_bank_id, memberinfo_bank_name, memberinfo_bank_account, memberinfo_bank_provience, memberinfo_bank_area, memberinfo_bank_branch, memberinfo_question, memberinfo_answer,memberinfo_is_enable';
		$fields=explode(',',$required_field);
		$new_required_field=[];
		$model_item=MemberinfoItem::model();
		foreach($fields as $fieldName)
		{
			$fieldName=trim($fieldName);
			if($model_item->itemRequired($fieldName))
			{
			    if(params('regAgent')==false)
                {
                    continue;
                }
				$new_required_field[]=$fieldName;
			}
		}
		$new_required_field_rule=',' . implode(',',$new_required_field);
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$rules=array(
				array('memberinfo_name, memberinfo_bank_name,memberinfo_email, memberinfo_mobi, memberinfo_phone, memberinfo_qq, memberinfo_msn, memberinfo_sex, memberinfo_idcard_type, memberinfo_idcard, memberinfo_zipcode, memberinfo_birthday, memberinfo_address_provience, memberinfo_address_area, memberinfo_address_county, memberinfo_address_detail, memberinfo_bank_id, memberinfo_bank_account, memberinfo_bank_provience, memberinfo_bank_area, memberinfo_bank_branch, memberinfo_memo, memberinfo_is_enable, memberinfo_register_ip, memberinfo_last_ip, memberinfo_last_date, memberinfo_add_date, memberinfo_mod_date', 'filter','filter'=>array($this,'empty2null')),
				array('memberinfo_account, memberinfo_password, memberinfo_password2, memberinfo_nickname' . $new_required_field_rule , 'required','on'=>'create,update,updateMy'),
				array('memberinfo_password,memberinfo_password_repeat, memberinfo_password2,memberinfo_password_repeat2' , 'required','on'=>'create,updatePassword'),
				array('memberinfo_password,memberinfo_password_repeat, memberinfo_password2,memberinfo_password_repeat2' , 'length','min'=>6,'max'=>256,'tooShort'=>'密码长度要大于6','tooLong'=>'密码输入过长','on'=>'create,updatePassword'),
				array('memberinfo_password_repeat', 'compare', 'compareAttribute'=>'memberinfo_password' ,'on'=>'create,updatePassword'),
				array('memberinfo_password_repeat2', 'compare', 'compareAttribute'=>'memberinfo_password2' ,'on'=>'create,updatePassword'),
				array('memberinfo_idcard_type,memberinfo_is_agent', 'numerical', 'integerOnly'=>true),
				array('memberinfo_nickname, memberinfo_idcard, memberinfo_address_detail, memberinfo_bank_name, memberinfo_bank_account, memberinfo_bank_branch,memberinfo_postoffice', 'length', 'max'=>200),
				array('memberinfo_email, memberinfo_question', 'length', 'max'=>256),
				array('memberinfo_email', 'email'),
				//array('memberinfo_email', 'ext.validators.PhoneOrEmail'),
				array('memberinfo_address_provience, memberinfo_address_area, memberinfo_address_county, memberinfo_bank_provience, memberinfo_bank_area', 'length', 'max'=>20),
				array('memberinfo_bank_id', 'length', 'max'=>11),
				array('memberinfo_account','unique','on'=>'create,update,updateMy'),
				array('memberinfo_account','unique','className'=>'Userinfo','attributeName'=>'userinfo_account','on'=>'create,update,updateMy'),
				array('memberinfo_account','unique','className'=>'Agent','attributeName'=>'agent_account','on'=>'create,update,updateMy'),
		        //array('memberinfo_nickname','unique','on'=>'create,update,updateMy'),
//		        array('memberinfo_name','match','allowEmpty'=>false,'pattern'=>"/^[\x7f-\xff]+$/",'message'=>'姓名必须为汉字'),
                array('memberinfo_nickname','match','allowEmpty'=>false,'pattern'=>"/^[\x7f-\xff]+$/",'message'=>'姓名必须为汉字'),
		    	//array('memberinfo_account','match','allowEmpty'=>false,'pattern'=>"/([0-9a-zA-Z]+$)/" ,'message'=>'账户名必须为数字或字母或组合'),
				array('memberinfo_bank_id', 'exist', 'className'=>'Bank','attributeName'=>'bank_id'),
				array('memberinfo_sex', 'ext.validators.Sex'),
				array('memberinfo_is_enable', 'ext.validators.Enable'),
				array('memberinfo_phone', 'ext.validators.Phone'),
				//array('memberinfo_mobi', 'ext.validators.Phone','allowTel'=>false),
				array('memberinfo_zipcode', 'ext.validators.Zipcode'),
				array('memberinfo_qq', 'ext.validators.QQ'),
				array('memberinfo_msn', 'ext.validators.QQ','allowTel'=>false,'allowQQ'=>false),
				array('memberinfo_name', 'ext.validators.Account','allowZh'=>true),
			   //array('memberinfo_name', 'ext.validators.Account1','allowZh'=>true),
               // array('memberinfo_bank_name', 'ext.validators.Account','allowZh'=>true),
                //array('memberinfo_bank_name','compare', 'compareAttribute'=>'memberinfo_name'),
				array('memberinfo_account', 'ext.validators.Account','allowZh'=>false),
				array('memberinfo_account', 'ext.validators.Exist','allowEmpty'=>false,'on'=>'choose'),
				array('memberinfo_password, memberinfo_password2, memberinfo_answer', 'ext.validators.Password','on'=>'create,updatePassword'),
				//array('memberinfo_birthday', 'ext.validators.Date'),
				array('memberinfo_add_date', 'default','value'=>date(Model::$mysqlDatetimeFormat),'on'=>'update'),
				array('memberinfo_register_ip, memberinfo_last_ip', 'default','value'=>webapp()->request->userHostAddress),
				array('memberinfo_memo, memberinfo_last_date, memberinfo_mod_date,memberinfo_phone,memberinfo_mobi,memberinfo_is_verify', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that shouodeld not be searched.
				array('memberinfo_id, memberinfo_account, memberinfo_password, memberinfo_password2, memberinfo_name, memberinfo_nickname, memberinfo_email, memberinfo_mobi, memberinfo_phone, memberinfo_qq, memberinfo_msn, memberinfo_sex, memberinfo_idcard_type, memberinfo_idcard, memberinfo_zipcode, memberinfo_birthday, memberinfo_address_provience, memberinfo_address_area, memberinfo_address_county, memberinfo_address_detail, memberinfo_bank_id, memberinfo_bank_name, memberinfo_bank_account, memberinfo_bank_provience, memberinfo_bank_area, memberinfo_bank_branch, memberinfo_question, memberinfo_answer, memberinfo_memo, memberinfo_is_enable, memberinfo_register_ip, memberinfo_last_ip, memberinfo_last_date, memberinfo_add_date, memberinfo_mod_date', 'safe', 'on'=>'search'),
		);
		/*
		if(!user()->isAdmin())
		{
			$rules[]=array('memberinfo_msg_verify','required','on'=>'update');
			$rules[]=array('memberinfo_msg_verify','ext.validators.VerifyCode','on'=>'update');
		}*/
		return $rules;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'memberinfoBank' => array(Model::BELONGS_TO, 'Bank', 'memberinfo_bank_id'),
            'memberinfoBank2' => array(Model::BELONGS_TO, 'Bank', 'memberinfo_bank_id'),
			'membermap' => array(Model::HAS_ONE, 'Membermap', 'membermap_id'),
			'finance' => array(Model::HAS_MANY, 'Finance', 'finance_memberinfo_id'),
			'groupmap'=>array(Model::HAS_ONE,'GroupMap','groupmap_id'),
			'memberinfoMemberType'=>array(Model::BELONGS_TO, 'MemberType', 'memberinfo_membertype_level'),
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
			if(strncmp('memberinfo_',$itemKey,11)==0)
				$new_labels[$itemKey]=$item['memberinfo_item_title'];
		}
		$labels=array(
			'memberinfo_id' => 'Memberinfo',
			'memberinfo_account' => t('epmms','登录帐号'),
			'memberinfo_password' => t('epmms','一级密码'),
			'memberinfo_password2' => t('epmms','二级密码'),
			'memberinfo_type' => t('epmms','类型'),
			'memberinfo_name' => t('epmms','姓名'),
			'memberinfo_nickname' => t('epmms','昵称'),
			'memberinfo_email' => 'Email',
			'memberinfo_mobi' => t('epmms','手机号码'),
			'memberinfo_phone' => t('epmms','电话'),
			'memberinfo_qq' => t('epmms','QQ号码'),
			'memberinfo_msn' => t('epmms','MSN帐号'),
			'memberinfo_sex' => t('epmms','姓别'),
			'memberinfo_idcard_type' => t('epmms','证件类型'),
			'memberinfo_idcard' => t('epmms','证件号码'),
			'memberinfo_zipcode' => t('epmms','邮政编码'),
			'memberinfo_birthday' => t('epmms','生日'),
			'memberinfo_address_provience' => t('epmms','居住省'),
			'memberinfo_address_area' => t('epmms','居住市'),
			'memberinfo_address_county' => t('epmms','居住县'),
			'memberinfo_address_detail' => t('epmms','居住详细地地'),
			'memberinfo_bank_id' => t('epmms','银行'),
			'memberinfo_bank_name' => t('epmms','银行户名'),
			'memberinfo_bank_account' => t('epmms','银行帐号'),
			'memberinfo_bank_provience' => t('epmms','银行-省'),
			'memberinfo_bank_area' => t('epmms','银行-市/地区'),
			'memberinfo_bank_branch' => t('epmms','银行-详细地址'),
			'memberinfo_question' => t('epmms','密码保护问题？'),
			'memberinfo_answer' => t('epmms','密码保护答案？'),
			'memberinfo_memo' => t('epmms','备注'),
			'memberinfo_is_enable' => t('epmms','是否启用'),
			'memberinfo_register_ip' => t('epmms','注册IP'),
			'memberinfo_last_ip' => t('epmms','最后登录IP'),
			'memberinfo_last_date' => t('epmms','最后登录日期'),
			'memberinfo_add_date' => t('epmms','注册日期'),
			'memberinfo_mod_date' => t('epmms','修改日期'),
			'memberinfo_is_agent' => t('epmms','是否代理中心'),
			'memberinfo_is_verify' => t('epmms','审核状态'),
			'showName'=>t('epmms','会员'),
			'memberinfo_msg_verify'=>t('epmms','验证码'),
			'memberinfo_postoffice'=>t('epmms','邮局'),
			
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
        $sort=new Sort('Memberinfo');
        $sort->defaultOrder=array('memberinfo_id'=>$this->memberinfo_is_verify==1?Sort::SORT_DESC:Sort::SORT_DESC);
        $criteria=new CDbCriteria;
        $criteria->compare('memberinfo_id',$this->memberinfo_id);
        $criteria->compare('memberinfo_account',$this->memberinfo_account);
        $criteria->compare('memberinfo_name',$this->memberinfo_name);
        $criteria->compare('memberinfo_nickname',$this->memberinfo_nickname);
        $criteria->compare('memberinfo_email',$this->memberinfo_email);
        $criteria->compare('memberinfo_mobi',$this->memberinfo_mobi);
        $criteria->compare('memberinfo_phone',$this->memberinfo_phone);
        
        $criteria->compare('memberinfo_qq',$this->memberinfo_qq);
        $criteria->compare('memberinfo_msn',$this->memberinfo_msn);
        $criteria->compare('memberinfo_sex',$this->memberinfo_sex);
        $criteria->compare('memberinfo_idcard_type',$this->memberinfo_idcard_type);
        $criteria->compare('memberinfo_idcard',$this->memberinfo_idcard);
        $criteria->compare('memberinfo_zipcode',$this->memberinfo_zipcode);
        $criteria->compare('memberinfo_birthday',$this->memberinfo_birthday);
        $criteria->compare('memberinfo_address_provience',$this->memberinfo_address_provience);
        $criteria->compare('memberinfo_address_area',$this->memberinfo_address_area,true);
        $criteria->compare('memberinfo_address_county',$this->memberinfo_address_county);
        $criteria->compare('memberinfo_address_detail',$this->memberinfo_address_detail,true);
        $criteria->compare('memberinfo_bank_name',$this->memberinfo_bank_name);
        $criteria->compare('memberinfo_bank_account',$this->memberinfo_bank_account);
        $criteria->compare('memberinfo_bank_provience',$this->memberinfo_bank_provience);
        $criteria->compare('memberinfo_bank_area',$this->memberinfo_bank_area,true);
        $criteria->compare('memberinfo_bank_branch',$this->memberinfo_bank_branch,true);
        $criteria->compare('memberinfo_question',$this->memberinfo_question,true);
        $criteria->compare('memberinfo_answer',$this->memberinfo_answer,true);
        $criteria->compare('memberinfo_memo',$this->memberinfo_memo,true);
        $criteria->compare('memberinfo_is_enable',$this->memberinfo_is_enable);
        $criteria->compare('memberinfo_register_ip',$this->memberinfo_register_ip);
        $criteria->compare('memberinfo_last_ip',$this->memberinfo_last_ip);
        $criteria->compare('memberinfo_last_date',$this->memberinfo_last_date);
        $criteria->compare('memberinfo_add_date',$this->memberinfo_add_date);
        $criteria->compare('memberinfo_mod_date',$this->memberinfo_mod_date);
        $criteria->compare('memberinfo_is_agent',$this->memberinfo_is_agent);
        $criteria->compare('memberinfo_is_verify',$this->memberinfo_is_verify);
        $criteria->compare('"memberinfoBank".bank_name',@$this->memberinfoBank->bank_name);
        //$criteria->compare('"memberinfoBank".bank_id',@$this->memberinfoBank->bank_id);
        $criteria->compare('"membermap".membermap_is_verify',@$this->membermap->membermap_is_verify);
        $criteria->compare('"membermap".membermap_recommend_id',@$this->membermap->membermap_recommend_id);
        $criteria->compare('"membermap".membermap_parent_id',@$this->membermap->membermap_parent_id);
        $criteria->compare('"membermap".membermap_agent_id',@$this->membermap->membermap_agent_id);
        $criteria->compare('"membermap".membermap_membertype_level',@$this->membermap->membermap_membertype_level);
        $criteria->compare('"membermap".membermap_level',@$this->membermap->membermap_level);
        $criteria->compare('"membermap".membermap_is_empty',@$this->membermap->membermap_is_empty);
        $criteria->compare('"membermap".membermap_is_delete',@$this->membermap->membermap_is_delete);
        $criteria->compare('"membermap".membermap_is_active',@$this->membermap->membermap_is_active);
        $criteria->compare('"membermap".membermap_verify_date',@$this->membermap->membermap_verify_date);
        $criteria->compare('"membermap".membermap_recommend_layer',@$this->membermap->membermap_recommend_layer);
        //$criteria->compare('"membermap".membermap_bond_id',@$_GET['bond_id']);
        $criteria->compare('"membermap".membermap_bond_id',@$this->membermap->membermap_bond_id);
        if(!empty($this->membermap->membermap_recommend_path))
        {
            $criteria->addCondition('"membermap".membermap_recommend_path like ' . $this->membermap->membermap_recommend_path);
        }
        $criteria->with=array('memberinfoBank',
            'membermap',
            'membermap.membermapParent',
            'membermap.membermapRecommend',
            'membermap.membermapAgent',
            'membermap.membermapbond',
            'membermap.membermapMembertypeLevel',
            'membermap.memberlevel');
        if(webapp()->request->isAjaxRequest)
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
                'includeDataProviderInformation'=>true,
                'relations'=>['memberinfoBank',
                    'memberinfoBank',
                    'membermap'=>['relations'=>[
                        'membermapParent'=>['relations'=>'memberinfo'],
                        'membermapRecommend'=>['relations'=>'memberinfo'],
                        'membermapAgent'=>['relations'=>'memberinfo'],
                        //'membermapbond'=>['relations'=>'memberinfo'],
                        'membermapMembertypeLevel',
                        'memberlevel'
                    ]]
                ]
            ));
        }
        else
        {
            return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
            ));
        }
	}

	public function getShowTitle()
	{
		if(!isEmpty($this->memberinfo_nickname))
			return $this->memberinfo_nickname;
		else
			return $this->memberinfo_account;
	}
	public function getShowName()
	{
		return $this->memberinfo_account;
	}
	public function findAllAgent()
	{
		return $this->findAll(array('with'=>array('membermap'),'condition'=>'membermap.membermap_is_agent=1 and membermap_is_verify=1'));
	}

	public function getListData($condition=array('with'=>array('membermap'),'condition'=>'membermap_is_verify=1'))
	{
		return CHtml::listData($this->findAll($condition),$this->tableSchema->primaryKey,$this->nameColumn);
	}
	public function getListDataAgent()
	{
		return $this->getListData(array('with'=>array('membermap'),'condition'=>'membermap_is_verify=1 and membermap_is_agent=1'));
	}

	/**
	 * 获取财务数据
	 * @param $type 财务类型
	 * @return CActiveRecord 财务模型
	 */
	public function getFinance($type)
	{
		return Finance::getMemberFinance($this->memberinfo_id,$type);
	}

	/**
	 * 转换帐号名到id
	 * @param $name
	 * @return integer
	 */
	public static function name2id($name=null,$case=true)
	{
		if(is_null($name))
		{
			return null;
		}
		if($case)
			$model=self::model()->find('memberinfo_account=:name',[':name'=>$name]);
		
		else
			$model=self::model()->find('lower(memberinfo_account)=lower(:name)',[':name'=>$name]);
		if(is_object($model))
		{
			return $model->primaryKey;
		}
		else
			return null;
	}
	/**
	 * 转换帐号名到id
	 * @param $name
	 * @return string
	 */
	public static function id2name($id=null)
	{
		if(empty($id))
		{
			return '';
		}
		$model=self::model()->find('memberinfo_id=:id',[':id'=>$id]);
		if(is_object($model))
		{
			return $model->memberinfo_account;
		}
		else
			return '';
	}
	public function afterValidate()
	{
		!empty($this->memberinfo_password) && left($this->memberinfo_password,6)!='$2a$13'?$this->memberinfo_password=webapp()->format->format($this->memberinfo_password,'password'):'';
		!empty($this->memberinfo_password2) && left($this->memberinfo_password2,6)!='$2a$13'?$this->memberinfo_password2=webapp()->format->format($this->memberinfo_password2,'password'):'';
	}

	/**
	 * 审核会员
	 * @isAll
	 * @verifyType 1表示注册会员,3表示自动生成会员,8重新审核,5批量生成,10只审核不结算
	 */
	public function verify($isAll=false,$verifyType=1)
	{
		if($this->memberinfo_is_verify==0 || $verifyType==8)
		{
			if($verifyType==1 && webapp()->id!='150608')
			{

				$bak=new Backup();
				if(!$bak->autoBackup('审核：'.$this->memberinfo_account,'审核时间：'.webapp()->format->formatdatetime(time())))
				{
					throw new Error('备份失败');
				}
			}
			$transaction=webapp()->db->beginTransaction();
			try
			{
				if($this->memberinfo_is_agent==2)
				{
					$this->memberinfo_is_verify=1;
					$this->saveAttributes(['memberinfo_is_verify']);
					$status=SystemStatus::model()->find();
					$status->system_status_last_verify=new CDbExpression('now()');
					webapp()->db->createCommand('insert into epmms_finance(finance_award,finance_type,finance_memberinfo_id) select 0,finance_type_id,' . $this->memberinfo_id . ' from epmms_finance_type')->execute();
					$transaction->commit();
					return EError::SUCCESS;
				}
				Yii::import('ext.award.MySystem');
				Yii::import('ext.award.MySystemb');
				$mysystem=new MySystem($this->membermap);
				$membermap=$this->membermap;
				if(is_null($membermap))
					throw new Error('无效的会员');
				$this->memberinfo_is_verify=1;
				$this->saveAttributes(['memberinfo_is_verify']);

			    $parents = Membermap::model()->findByPk($membermap->membermap_recommend_id);

//                $res=Membermap::model()->find('membermap_order='.$membermap->membermap_order.'and membermap_parent_id='.$parents->membermap_id);
//
//                if($res){
//                    if($membermap->membermap_order==1){
//
//                        $parent=Membermap::model()->find(['order'=>'membermap_layer desc,membermap_path asc','condition'=>"membermap_child_number<2  and membermap_path like '$res->membermap_path%'"]);
//                    }else{
//                        $parent=Membermap::model()->find(['order'=>'membermap_layer desc,membermap_path desc','condition'=>"membermap_child_number<2 and membermap_path like '$res->membermap_path%'"]);
//                    }
//                        $membermap->membermap_parent_id=$parent->membermap_id;
//                }else{
//
//                       $membermap->membermap_parent_id=$parents->membermap_id;
//               }
                $this->membermap->saveAttributes(['membermap_order']);
				$status=$membermap->verify($verifyType);//去membermap模型里面验证
				if($status!=EError::SUCCESS)
				{
					$transaction->rollback();
					return $status;
				}
				if(webapp()->id=='141203')
				{
					if($membermap->membermap_membertype_level<=3)
					{
						$member2 = Membermap2::model()->findByAttributes(['membermap_bond_id' => $this->memberinfo_id]);
						if(is_null($member2))
						{
							throw new Error('双轨中没有该会员');
						}
						$status = $member2->verify($verifyType);
						if ($status != EError::SUCCESS)
						{
							$transaction->rollback();
							return $status;
						}
					}
					else
					{
						$member4 = Membermap4::model()->findByAttributes(['membermap_member_id' => $this->memberinfo_id]);
						if(is_null($member4))
						{
							throw new Error('三轨中没有该会员');
						}
						$status = $member4->verify($verifyType);
						if ($status != EError::SUCCESS)
						{
							$transaction->rollback();
							return $status;
						}
					}
				}
				$status=new MemberStatus();
				$status->status_id=$this->memberinfo_id;
				$status->save();

				if($verifyType==1 || $verifyType==3 || $verifyType==5 || $verifyType==10)
				{

					$status=SystemStatus::model()->find();
					$status->system_status_last_verify=new CDbExpression('now()');
					//webapp()->db->createCommand('insert into epmms_finance(finance_award,finance_type,finance_memberinfo_id) select iif(finance_type_id=3,:money,0::numeric),finance_type_id,' . $this->memberinfo_id . ' from epmms_finance_type')->execute([':money'=>$membermap->membermap_money/6000*5000]);
                    webapp()->db->createCommand('insert into epmms_finance(finance_award,finance_type,finance_memberinfo_id) select iif(finance_type_id=4,:money,0::numeric),finance_type_id,' . $this->memberinfo_id . ' from epmms_finance_type')->execute([':money'=>
                    0]);

                    // 首次激活给10电子币(新增)
                    $member_finace = Finance::getMemberFinance($this->memberinfo_id,2);
                    $member_finace->add(10);
                    
// $membermap->membermap_is_empty==1?0:6800]);
	                // if($this->membermap->membermap_is_empty==0){

	                    if($this->membermap->membermap_membertype_level==1){
	                    	 $type_money=MemberType::model()->findByAttributes(['membertype_level'=>$this->membermap->membermap_membertype_level]);	                    	
	                                if($finance2=Finance::getMemberFinance($this->memberinfo_id,4)){
	                                
	                                    $finance2->add($type_money->membertype_money);
	                                }else{
	                                	throw new EError('找不到币种');
	                                }                                                                     	                    	
	                    }elseif($this->membermap->membermap_membertype_level==2){	                    	
	                          $type_money=MemberType::model()->findByAttributes(['membertype_level'=>$this->membermap->membermap_membertype_level]);	                     	                    	
	                               if($finance2=Finance::getMemberFinance($this->memberinfo_id,4)){
	                                
	                                    $finance2->add($type_money->membertype_money);
	                                }else{
	                                	throw new EError('找不到币种');
	                                }   	                    	
	                    }
      
				}
				if($verifyType==1 || $verifyType==8|| $verifyType==5)
				{
//				    $mysystem->run();
//					$mysystem->run(1,0,0);//运行分组1中的奖金 见点奖秒结进入现金钱包
//					$mysystem->run(3,0,0);//运行分组1中的奖金 见点奖秒结进入游戏币
					// $mysystem->run(1,1,0);//运行分组1中的奖金  日结
                    //$mysystem->run(2,0,0);//运行分组2中的奖金  抵扣秒结
                    // $mysystem->run(2,1,0);//运行分组2中的奖金   抵扣日结
            
                    //秒结算 0 日结1 月结 2
                    //$mysystem->run(2,0,0);
                    //$mysystem->run(3,0,0);
                    //$mysystem->run(4,0,0);
                    //$mysystem->run(2,1,0);
					//$mysystem->run(2,1,1);
					//$mysystemb=new MySystemb($this->membermap);
					//$mysystemb->run(4,1,1);
				}
				elseif($verifyType==3)
				{
					//$mysystem->run(1,1,3);
				}
				//发送短信
				if(!$isAll && $verifyType==1)
				{
					Sms::model()->sendVerify($this);
					//Sms::model()->sendAward($mysystem->getPeriod());
				}
				if(webapp()->id=='141203')
				{
					for($i=1;$i<=8;$i++)
					{
/*						$new_signing=new Signing('create');
						$new_signing->signing_member_id=$this->memberinfo_id;
						$new_signing->signing_is_verify=0;
						$new_signing->signing_type=0;
						$new_signing->signing_is_refund=0;
						if(!$new_signing->save())
						{
							$transaction->rollback();
							throw new Error('处理签约错误');
						}*/
						$conn=webapp()->db;
						$sql="insert into epmms_signing(signing_member_id,signing_is_verify,signing_type,signing_is_refund) values(:id,:verify,:type,:refund)";
						$cmd=$conn->createCommand($sql);
						$cmd->execute([':id'=>$this->memberinfo_id,':verify'=>0,':type'=>0,':refund'=>0]);
					}
				}
			}
			catch(EError $e)
			{
				$transaction->rollback();
				 throw $e;
				return $e;
			}
			catch(CDbException $e)
			{
				$transaction->rollback();
				 throw $e;
				return $e;
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				throw $e;
				return EError::ERROR;
			}
			$transaction->commit();
			return EError::SUCCESS;
		}
		else
			return EError::DUPLICATE;
	}
	public function genMember($cnt)
	{
		$root_member=$this;
		$root_map=$root_member->membermap;
		$gen_count=0;
		$real_count=0;
		$is_parent=MemberinfoItem::model()->itemVisible('membermap_parent_id');
		$branch=config('map','branch');
		//webapp()->db->createCommand("ALTER SEQUENCE gen_member restart;")->execute();
		for($i=0;$i<28;$i++)
		{
			if($is_parent)
			{

				$layer_members = Membermap::model()->findAll(['condition' => "membermap_layer-:layer=:layer_count and membermap_path like :path || '%'",
						'params' => [':layer' => $root_map->membermap_layer, ':layer_count' => $i, ':path' => $root_map->membermap_path]]
				);
				/*
				$sql_members="select * from epmms_membermap where membermap_layer=:layer and membermap_path like :path || '%';";
				$cmd_members=webapp()->db->createCommand($sql_members);
				$res_members=$cmd_members->query([':layer'=>$root_map->membermap_layer+$i,':path'=>$root_map->membermap_path]);*/
				//$res_members->setFetchMode(PDO::FETCH_ASSOC);
			}
			else
			{
				$layer_members = Membermap::model()->findAll(['condition' => "membermap_recommend_layer-:layer=:layer_count and membermap_recommend_path like :path || '%'",
					'params' => [':layer' => $root_map->membermap_recommend_layer, ':layer_count' => $i, ':path' => $root_map->membermap_recommend_path]]);

			}
			foreach($layer_members as $member)
			{
				if($is_parent)
					for($j=1;$j<=$branch;$j++)
					{
						if($this->genOneMember($member,$j)==EError::SUCCESS)
							$real_count++;
						$gen_count++;
						if($gen_count>=$cnt)
							return $real_count;
					}
				else
					for($j=1;$j<=mt_rand(0,10);$j++)
					{
						if($this->genOneMember($member,$j)==EError::SUCCESS)
							$real_count++;
						$gen_count++;
						if($gen_count>=$cnt)
							return $real_count;
					}
				unset($member);
			}
		}
		return $gen_count;
	}

	/**
	 * @param Membermap $root_map
	 * @param null $order
	 */
	public function genOneMember($root_map,$order=null,$isParent=true)
	{
		$sql_info="select * from epmms_memberinfo where memberinfo_id=:id;";
		$cmd_info=webapp()->db->createCommand($sql_info);
		$root_info=$cmd_info->queryRow(true,[':id'=>$root_map['membermap_id']]);
		if($isParent)
			$member_exist=Membermap::model()->exists('membermap_parent_id=:id and membermap_order=:order',[':id'=>$root_map['membermap_id'],':order'=>$order]);
		else
			$member_exist=Membermap::model()->exists('membermap_id=:id and membermap_recommend_number=:order',[':id'=>$root_map->membermap_id,':order'=>$order]);

		if(!$member_exist)
		{
			$transaction=webapp()->db->beginTransaction();
			$info=new Memberinfo('create');
			$info->attributes=$root_info;
			$info->unsetAttributes(['memberinfo_id','memberinfo_is_verify','memberinfo_last_date','memberinfo_last_ip','memberinfo_nickname']);
			$info->memberinfo_account=$this->memberinfo_account . '_' . (new DbEvaluate("nextval('gen_member')"))->run();
			$info->memberinfo_nickname=$info->memberinfo_account;
			$info->memberinfo_password_repeat=$info->memberinfo_password;
			$info->memberinfo_password_repeat2=$info->memberinfo_password2;
			$info->memberinfo_add_date=new CDbExpression('now()');
			$info->memberinfo_is_verify=0;
			if($info->save()&&$info->refresh())
			{
				$map=new Membermap('create');
				$map->attributes=array('membermap_membertype_level'=>$root_map['membermap_membertype_level'],'membermap_membertype_level_old'=>$root_map['membermap_membertype_level_old'],'membermap_agent_id'=>$root_map['membermap_agent_id'],'membermap_is_agent'=>$root_map['membermap_is_agent'],'membermap_reg_member_id'=>$root_map['membermap_reg_member_id']);
				$map->membermap_id=$info->memberinfo_id;
				$mtype=MemberType::model()->find(['order'=>'random()']);
				$map->membermap_membertype_level=$mtype->membertype_level;
				$map->membermap_membertype_level_old=$mtype->membertype_level;
				$map->membermap_recommend_id=$root_map['membermap_id'];
				if($isParent)
				{
					$map->membermap_parent_id=$root_map['membermap_id'];
					$map->membermap_order=$order;
				}
				$map->membermap_is_verify=0;
				if(is_null($map->membermap_agent_id))
					$map->membermap_agent_id=1;
				if(!$map->save())
				{
					$transaction->rollback();
					return EError::ERROR;
				}
				if($info->verify(true,5)==EError::SUCCESS)
				{
					$transaction->commit();
					return EError::SUCCESS;
				}
				else
				{
					$transaction->rollback();
					throw new Error('生成会员时审核失败或电子币不足');
				}
			}
			$transaction->rollback();
			unset($map,$info,$mtype);
			return EError::ERROR;
		}
		else
			return EError::ERROR;
	}
	/**
	 * @param Membermap $root_map
	 * @param null $order
	 */
	public function genNewMember($root_map,$order=null)
	{
		$root_info=$root_map->memberinfo;
		$transaction=webapp()->db->beginTransaction();
		$info=new Memberinfo('create');
		$info->attributes=$root_info->attributes;
		$info->unsetAttributes(['memberinfo_id','memberinfo_is_verify','memberinfo_last_date','memberinfo_last_ip','memberinfo_nickname']);
		$info->memberinfo_account='auto_' . Memberinfo::genUsername();
		$info->memberinfo_nickname=$root_info->memberinfo_nickname;
		$info->memberinfo_password_repeat=$info->memberinfo_password;
		$info->memberinfo_password_repeat2=$info->memberinfo_password2;
		$info->memberinfo_add_date=new CDbExpression('now()');
		$info->memberinfo_is_verify=0;
		if($info->save()&&$info->refresh())
		{
			$map=new Membermap('create');
			$map->membermap_id=$info->memberinfo_id;
			//$map->attributes=$root_map->getAttributes(['membermap_id','membermap_agent_id','membermap_is_agent','membermap_bond_id']);
			$map->membermap_recommend_id=$root_map->membermap_id;
			$map->membermap_membertype_level=1;
			$map->membermap_is_verify=0;
			if(is_null($map->membermap_agent_id))
				$map->membermap_agent_id=1;
			//自动排网
			$parent=Membermap::model()->find(['condition'=>"membermap_child_number<:br and membermap_is_verify=1 and membermap_path like :rpath || '%'",'order'=>'membermap_layer asc,membermap_layer_order asc','params'=>[':br'=>config('map','branch'),':rpath'=>$root_map->membermap_path]]);
			$map->membermap_parent_id=$parent->membermap_id;
			//$layer_order=Membermap::model()->count('membermap_is_verify=1 and membermap_layer=:layer',[':layer'=>$parent->membermap_layer+1]);
			//$map->membermap_layer_order=$layer_order+1;
			$map->membermap_order=$parent->membermap_child_number+1;

			if(!$map->save())
			{
				$transaction->rollback();
				return false;
			}
			if($info->verify(true,3)==EError::SUCCESS)
			{
				$transaction->commit();
				return $info;
			}
			else
			{
				$transaction->rollback();
				throw new Error('生成会员时审核失败或电子币不足');
			}
		}
		$transaction->rollback();
		return false;
	}
	public function delete()
	{
		if($this->memberinfo_is_verify==1)
			return false;
		return parent::delete();
	}
	public static function genUsername()
	{
		$lower=left("1000000000",params('accountLength'));
		$upper=left("9999999999",params('accountLength'));
		$model=Memberinfo::model();
		do
		{
			$username=(string)rand((int)$lower,(int)$upper);
		}while(strpos($username,'4')!==false || $model->exists('memberinfo_account=:username',[':username'=>$username]));
		return 'yy' . $username;
	}
	public function resetPassword()
	{
		$p1=rand(10000000,99999999);
		$p2=rand(10000000,99999999);
		$this->memberinfo_password=CPasswordHelper::hashPassword($p1);
		$this->memberinfo_password2=CPasswordHelper::hashPassword($p2);
		$this->saveAttributes(['memberinfo_password','memberinfo_password2']);
		return ['password1'=>$p1,'password2'=>$p2];
	}
	public function afterFind()
	{
		//$this->memberinfo_is_enable=$this->memberinfo_is_enable===0?false:true;
	}
    /**
     * Generates a secure hash from a password and a random salt.
     *
     * The generated hash can be stored in database.
     * Later when a password needs to be validated, the hash can be fetched and passed
     * to [[validatePassword()]]. For example,
     *
     * ```php
     * // generates the hash (usually done during user registration or when the password is changed)
     * $hash = Yii::$app->getSecurity()->generatePasswordHash($password);
     * // ...save $hash in database...
     *
     * // during login, validate if the password entered is correct using $hash fetched from database
     * if (Yii::$app->getSecurity()->validatePassword($password, $hash)) {
     *     // password is good
     * } else {
     *     // password is bad
     * }
     * ```
     *
     * @param string $password The password to be hashed.
     * @param int $cost Cost parameter used by the Blowfish hash algorithm.
     * The higher the value of cost,
     * the longer it takes to generate the hash and to verify a password against it. Higher cost
     * therefore slows down a brute-force attack. For best protection against brute-force attacks,
     * set it to the highest value that is tolerable on production servers. The time taken to
     * compute the hash doubles for every increment by one of $cost.
     * @return string The password hash string. When [[passwordHashStrategy]] is set to 'crypt',
     * the output is always 60 ASCII characters, when set to 'password_hash' the output length
     * might increase in future versions of PHP (http://php.net/manual/en/function.password-hash.php)
     * @throws Exception on bad password parameter or cost parameter.
     * @see validatePassword()
     */
    public function generatePasswordHash($password, $cost = null)
    {
        if ($cost === null) {
            $cost = $this->passwordHashCost;
        }
        if (function_exists('password_hash')) {
            /* @noinspection PhpUndefinedConstantInspection */
            return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
        }

        $salt = $this->generateSalt($cost);
        $hash = crypt($password, $salt);
        // strlen() is safe since crypt() returns only ascii
        if (!is_string($hash) || strlen($hash) !== 60) {
            throw new Exception('Unknown error occurred while generating hash.');
        }

        return $hash;
    }

    /**
     * Generates a salt that can be used to generate a password hash.
     *
     * The PHP [crypt()](http://php.net/manual/en/function.crypt.php) built-in function
     * requires, for the Blowfish hash algorithm, a salt string in a specific format:
     * "$2a$", "$2x$" or "$2y$", a two digit cost parameter, "$", and 22 characters
     * from the alphabet "./0-9A-Za-z".
     *
     * @param int $cost the cost parameter
     * @return string the random salt value.
     * @throws InvalidArgumentException if the cost parameter is out of the range of 4 to 31.
     */
    protected function generateSalt($cost = 13)
    {
        $cost = (int) $cost;
        if ($cost < 4 || $cost > 31) {
            throw new InvalidArgumentException('Cost must be between 4 and 31.');
        }

        // Get a 20-byte random string
        $rand = $this->generateRandomKey(20);
        // Form the prefix that specifies Blowfish (bcrypt) algorithm and cost parameter.
        $salt = sprintf('$2y$%02d$', $cost);
        // Append the random salt data in the required base64 format.
        $salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));

        return $salt;
    }
    /**
     * Generates specified number of random bytes.
     * Note that output may not be ASCII.
     * @see generateRandomString() if you need a string.
     *
     * @param int $length the number of bytes to generate
     * @return string the generated random bytes
     * @throws InvalidArgumentException if wrong length is specified
     * @throws Exception on failure.
     */
    public function generateRandomKey($length = 32)
    {
        if (!is_int($length)) {
            throw new InvalidArgumentException('First parameter ($length) must be an integer');
        }

        if ($length < 1) {
            throw new InvalidArgumentException('First parameter ($length) must be greater than 0');
        }

        // always use random_bytes() if it is available
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }

        // The recent LibreSSL RNGs are faster and likely better than /dev/urandom.
        // Parse OPENSSL_VERSION_TEXT because OPENSSL_VERSION_NUMBER is no use for LibreSSL.
        // https://bugs.php.net/bug.php?id=71143
        if ($this->_useLibreSSL === null) {
            $this->_useLibreSSL = defined('OPENSSL_VERSION_TEXT')
                && preg_match('{^LibreSSL (\d\d?)\.(\d\d?)\.(\d\d?)$}', OPENSSL_VERSION_TEXT, $matches)
                && (10000 * $matches[1]) + (100 * $matches[2]) + $matches[3] >= 20105;
        }

        // Since 5.4.0, openssl_random_pseudo_bytes() reads from CryptGenRandom on Windows instead
        // of using OpenSSL library. LibreSSL is OK everywhere but don't use OpenSSL on non-Windows.
        if (function_exists('openssl_random_pseudo_bytes')
            && ($this->_useLibreSSL
                || (
                    DIRECTORY_SEPARATOR !== '/'
                    && substr_compare(PHP_OS, 'win', 0, 3, true) === 0
                ))
        ) {
            $key = openssl_random_pseudo_bytes($length, $cryptoStrong);
            if ($cryptoStrong === false) {
                throw new Exception(
                    'openssl_random_pseudo_bytes() set $crypto_strong false. Your PHP setup is insecure.'
                );
            }
            if ($key !== false && mb_strlen($key,'8bit') === $length) {
                return $key;
            }
        }

        // mcrypt_create_iv() does not use libmcrypt. Since PHP 5.3.7 it directly reads
        // CryptGenRandom on Windows. Elsewhere it directly reads /dev/urandom.
        if (function_exists('mcrypt_create_iv')) {
            $key = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if (mb_strlen($key,'8bit') === $length) {
                return $key;
            }
        }

        // If not on Windows, try to open a random device.
        if ($this->_randomFile === null && DIRECTORY_SEPARATOR === '/') {
            // urandom is a symlink to random on FreeBSD.
            $device = PHP_OS === 'FreeBSD' ? '/dev/random' : '/dev/urandom';
            // Check random device for special character device protection mode. Use lstat()
            // instead of stat() in case an attacker arranges a symlink to a fake device.
            $lstat = @lstat($device);
            if ($lstat !== false && ($lstat['mode'] & 0170000) === 020000) {
                $this->_randomFile = fopen($device, 'rb') ?: null;

                if (is_resource($this->_randomFile)) {
                    // Reduce PHP stream buffer from default 8192 bytes to optimize data
                    // transfer from the random device for smaller values of $length.
                    // This also helps to keep future randoms out of user memory space.
                    $bufferSize = 8;

                    if (function_exists('stream_set_read_buffer')) {
                        stream_set_read_buffer($this->_randomFile, $bufferSize);
                    }
                    // stream_set_read_buffer() isn't implemented on HHVM
                    if (function_exists('stream_set_chunk_size')) {
                        stream_set_chunk_size($this->_randomFile, $bufferSize);
                    }
                }
            }
        }

        if (is_resource($this->_randomFile)) {
            $buffer = '';
            $stillNeed = $length;
            while ($stillNeed > 0) {
                $someBytes = fread($this->_randomFile, $stillNeed);
                if ($someBytes === false) {
                    break;
                }
                $buffer .= $someBytes;
                $stillNeed -= mb_strlen($someBytes,'8bit');
                if ($stillNeed === 0) {
                    // Leaving file pointer open in order to make next generation faster by reusing it.
                    return $buffer;
                }
            }
            fclose($this->_randomFile);
            $this->_randomFile = null;
        }

        throw new Exception('Unable to generate a random key');
    }
}
