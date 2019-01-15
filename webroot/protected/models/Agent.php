<?php

/**
 * This is the model class for table "{{agent}}".
 *
 * The followings are the available columns in table '{{agent}}':
 * @property string $agent_id
 * @property string $agent_memberinfo_id
 * @property string $agent_memo
 * @property integer $agent_is_verify
 * @property string $agent_add_date
 * @property string $agent_verify_date
 * @property string $agent_type
 * @property string $agent_province
 * @property string $agent_area
 * @property string $agent_county
 * @property string $agent_account
 *
 * The followings are the available model relations:
 * @property  * @property  */
class Agent extends Model
{
	public $modelName='报单中心';
	public $nameColumn='agentTitle';
	public $password2;
	protected $_agentTitle;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Agent the static model class
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
		return '{{agent}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('agent_memo, agent_is_verify, agent_add_date, agent_verify_date', 'filter','filter'=>array($this,'empty2null')),
			array('agent_memberinfo_id', 'required','message'=>t('epmms','会员输入错误或不存在')),
           /* array('agent_type', 'required','message'=>t('epmms','代理中心类型必选')),
            array('agent_province', 'required','message'=>t('epmms','省份必选')),*/
           // array('agent_area', 'ext.validators.AbleAgentArea'),
           // array('agent_county', 'ext.validators.AbleAgentCounty'),
			array('agent_is_verify', 'numerical', 'integerOnly'=>true),
			array('agent_memberinfo_id,agent_account', 'length', 'max'=>50),
			array('agent_memo', 'length', 'max'=>200),
			array('agent_account', 'ext.validators.Account','allowZh'=>false),
			array('agent_account', 'unique'),
			array('agent_account','unique','className'=>'Userinfo','attributeName'=>'userinfo_account'),
			array('agent_account','unique','className'=>'Memberinfo','attributeName'=>'memberinfo_account'),
			array('agent_memberinfo_id', 'unique','message'=>t('epmms','该会员已申请代理中心'),'on'=>'create,register'),
			array('agent_memberinfo_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			//array('agent_memberinfo_id', 'ext.validators.AbleAgent','on'=>'create,register'),
			array('agent_province, agent_area, agent_county', 'length', 'max'=>20),
			array('agent_type', 'exist', 'className'=>'AgentType','attributeName'=>'agent_type_level'),
			array('agent_add_date, agent_verify_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('agent_id, agent_memberinfo_id, agent_memo, agent_is_verify, agent_add_date, agent_verify_date', 'safe', 'on'=>'search'),
			array('password2', 'ext.validators.Password'),
            array('password2', 'ext.validators.TradePassword', 'allowEmpty'=>true),
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
			'agentMembermap' => array(Model::BELONGS_TO, 'Membermap', 'agent_memberinfo_id'),
			'agentMemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'agent_memberinfo_id'),
			'agenttype'=>array(Model::BELONGS_TO,'AgentType','agent_type')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'agent_id' => 'Agent',
			'agent_memberinfo_id' => t('epmms','会员'),
			'agent_account' => t('epmms','编号'),
			'agent_memo' => t('epmms','备注'),
			'agent_is_verify' => t('epmms','审核状态'),
			'agent_add_date' => t('epmms','申请日期'),
			'agent_verify_date' => t('epmms','审核日期'),
			'agent_type'=>t('epmms','代理中心类型'),
			'agent_province'=>t('epmms','省'),
			'agent_area'=>t('epmms','市/地区'),
			'agent_county'=>t('epmms','县')
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
		$sort=new Sort('Agent');
		$sort->defaultOrder=array('agent_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('agent_id',$this->agent_id);
		$criteria->compare('agent_account',$this->agent_account);
		$criteria->compare('agent_memo',$this->agent_memo,true);
		$criteria->compare('agent_is_verify',$this->agent_is_verify);
		$criteria->compare('agent_add_date',$this->agent_add_date,true);
		$criteria->compare('agent_verify_date',$this->agent_verify_date,true);
		$criteria->compare('agent_province',$this->agent_province);
		$criteria->compare('agent_area',$this->agent_area);
		$criteria->compare('agent_county',$this->agent_county);
		$criteria->compare('"agentMemberinfo".memberinfo_account',@$this->agentMemberinfo->memberinfo_account);
		$criteria->compare('"agentMembermap".membermap_agent_id',@$this->agentMembermap->membermap_agent_id);
		$criteria->with=['agentMembermap','agentMemberinfo','agenttype'];
		if (webapp()->request->isAjaxRequest){
			$page = 0;
            $pageSize = 20;
            if (isset($_GET['page']))
                $page = $_GET['page'] - 1;
            if (isset($_GET['limit']))
                $pageSize = $_GET['limit'];
           //$criteria->addCondition('"agentMembermap".membermap_agent_id='.user()->id);
            return new JSonActiveDataProvider($this, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                ),
                'relations'=>['agentMembermap','agentMemberinfo','agenttype'],
                'includeDataProviderInformation'=>true,
            ));
        }else{
        	return new JSonActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort
			));
        }
		
	}
	public function getAgentTitle()
	{
		if(is_null($this->_agentTitle))
		{
			if($info=$this->findByPk($this->agent_memberinfo_id,array('with'=>'agentMemberinfo')))
			{
				$this->_agentTitle=$info->agentMemberinfo->memberinfo_account;
			}
		}
		return $this->_agentTitle;
	}
	/**
	 * 审核代理中心
	 */
	public function verifyAgent()
	{

		if($this->agent_is_verify==0)
		{
  
			$this->agent_is_verify=1;

			$this->agent_verify_date=new CDbExpression('now()');

			$memberinfo=Memberinfo::model()->find('memberinfo_id=:id',[':id'=> $this->agent_memberinfo_id]);
		
	
			$membermap=Membermap::model()->find('membermap_id=:id',[':id'=> $this->agent_memberinfo_id]);
		if($memberinfo)
			{
				$memberinfo->memberinfo_is_agent=1;
			}
			if($membermap)
			{

				$membermap->membermap_is_agent=1;
				$membermap->membermap_agent_type=$this->agent_type;
				//$membermap->membermap_membertype_level_old=$membermap->membermap_membertype_level;
				/*switch($this->agent_type)
                {
                    case 3:
                        $membermap->membermap_membertype_level=4;
                        $membermap->membermap_money=50000;
                        break;
                    case 2:
                        $membermap->membermap_membertype_level=5;
                        $membermap->membermap_money=100000;
                        break;
                    case 1:
                        $membermap->membermap_membertype_level=6;
                        $membermap->membermap_money=300000;
                        break;
                }*/
			}
		
	
			if($this->save(false) && $membermap->saveAttributes(array('membermap_is_agent','membermap_agent_type','membermap_membertype_level','membermap_money')) && $memberinfo->saveAttributes(array('memberinfo_is_agent')))
            {
                //ii::import('ext.award.MySystem');
                //$mysystem=new MySystem($membermap);
                //$mysystem->run(1,1,0);
                return EError::SUCCESS;
            }
			else
				return EError::ERROR;
		}
		else
			return EError::DUPLICATE;
	}
}