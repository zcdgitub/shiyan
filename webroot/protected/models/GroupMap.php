<?php

/**
 * This is the model class for table "{{groupmap}}".
 *
 * The followings are the available columns in table '{{groupmap}}':
 * @property integer $groupmap_id
 * @property integer $groupmap_group_id
 * @property integer $groupmap_order
 * @property string $groupmap_verify_date
 * @property string $groupmap_award_date
 * @property integer $groupmap_award_count
 * @property string $groupmap_group_date
 * @property integer $groupmap_award_period
 * @property integer $groupmap_is_award
 * @property integer $groupmap_member_id
 * @property string $groupmap_name
 *
 * The followings are the available model relations:
 * @property  */
class GroupMap extends Model
{
	public $modelName='B网';
	public $nameColumn='groupmap_name';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GroupMap the static model class
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
		return '{{groupmap}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('groupmap_group_id, groupmap_verify_date, groupmap_award_date, groupmap_award_count, groupmap_group_date', 'filter','filter'=>array($this,'empty2null')),
			array('groupmap_member_id', 'required'),
			array('groupmap_id, groupmap_group_id, groupmap_order, groupmap_award_count', 'numerical', 'integerOnly'=>true),
			array('groupmap_id', 'unique'),
			array('groupmap_name', 'ext.validators.Account','allowZh'=>false),
			array('groupmap_name', 'unique'),
			array('groupmap_group_id', 'exist', 'className'=>'Group','attributeName'=>'group_id'),
			array('groupmap_member_id', 'exist', 'className'=>'Memberinfo','attributeName'=>'memberinfo_id'),
			array('groupmap_verify_date, groupmap_group_date', 'default','value'=>new CDbExpression('now()')),
			array('groupmap_award_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('groupmap_id, groupmap_group_id, groupmap_order, groupmap_verify_date, groupmap_award_date, groupmap_award_count, groupmap_group_date', 'safe', 'on'=>'search'),
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
			'groupmapGroup' => array(Model::BELONGS_TO, 'Group', 'groupmap_group_id'),
			'groupmapmemberinfo' => array(Model::BELONGS_TO, 'Memberinfo', 'groupmap_member_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'groupmap_id' => 'Groupmap',
			'groupmap_group_id' => 'Groupmap Group',
			'groupmap_order' => '审核顺序',
			'groupmap_verify_date' => '审核日期',
			'groupmap_award_date' => 'Groupmap Award Date',
			'groupmap_award_count' => 'Groupmap Award Count',
			'groupmap_group_date' => 'Groupmap Group Date',
			'groupmap_is_award' => '出局',
			'groupmap_name'=>'编号',
			'groupmap_member_id'=>'关联会员'
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
		$sort=new Sort('GroupMap');
		$sort->defaultOrder=array('groupmap_order'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;
		$criteria->compare('groupmap_id',$this->groupmap_id);
		$criteria->compare('groupmap_group_id',$this->groupmap_group_id);
		$criteria->compare('groupmap_order',$this->groupmap_order);
		$criteria->compare('groupmap_award_count',$this->groupmap_award_count);
		$criteria->compare('groupmap_member_id',$this->groupmap_member_id);
		$criteria->with=array('groupmapGroup');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>$sort,
			'pagination'=>array(
				'pageSize'=>50
			),
		));
	}

	/**
	 * 审核会员
	 */
	public function verify($period)
	{
		$transaction=webapp()->db->beginTransaction();
		$group=Group::model()->find(['order'=>'group_seq asc','limit'=>1]);
		if(is_null($group))
			throw new Error('找不到组');

		$this->groupmap_group_id=$group->group_id;
		//组成员互换位置
		$db=webapp()->db;
		$group_count=$group->group_count;


		//计算奖金
		$awardCalc=new DbCall('award_group',array($group->group_id,$this->groupmap_id,$period,2),false);

		$awardCalc->run();
		if($group_count>5)
		{
			$sql="update epmms_groupmap set groupmap_order=case when groupmap_order<=5 then groupmap_order+$group_count-5 else groupmap_order-5 end where groupmap_group_id={$this->groupmap_group_id}";
			$db->createCommand($sql)->execute();
			$this->refresh();
		}
		$this->deleteAll('groupmap_group_id=:groupid and groupmap_award_count>=10',[':groupid'=>$this->groupmap_group_id]);
		$group->group_count=$this->count('groupmap_group_id=:groupid',[':groupid'=>$this->groupmap_group_id]);

		$this->refresh();
		$group->refresh();
		$this->addGroup($group);
		//计算会员在组中的位置
		$transaction->commit();
		return EError::SUCCESS;
	}
	protected function addGroup($group)
	{
		$db=webapp()->db;
		$sql="update epmms_groupmap set groupmap_order=groupmap_order+1 where groupmap_group_id={$this->groupmap_group_id} and groupmap_order>{$group->group_count}-5";
		$db->createCommand($sql)->execute();
		$this->refresh();
		$this->groupmap_order=($group->group_count>5?($group->group_count-5+1):1);
		$group->group_count=$group->group_count+1;
		$group->save();
		$this->save();
		if($group->group_count>=10)
		{
			//拆分组
			//创建两个新组
			$new_group1=new Group();
			$new_group2=new Group();
			$new_group1->save();
			$new_group2->save();
			$new_group1->refresh();
			$new_group2->refresh();
			$groupid1=$new_group1->group_id;
			$groupid2=$new_group2->group_id;
			$db=webapp()->db;
			$sql="update epmms_groupmap set groupmap_group_id=$groupid1,groupmap_group_date=now() where groupmap_order%2<>0 and groupmap_group_id={$group->group_id}";
			$db->createCommand($sql)->execute();
			$sql="update epmms_groupmap set groupmap_group_id=$groupid2,groupmap_group_date=now() where groupmap_order%2=0 and groupmap_group_id={$group->group_id}";
			$db->createCommand($sql)->execute();
			$sql="update epmms_groupmap as u set groupmap_order=g.group_order from (select groupmap_id,row_number() over(order by groupmap_order asc) as group_order from epmms_groupmap  where groupmap_group_id=$groupid1) as g
			 where g.groupmap_id=u.groupmap_id";
			$db->createCommand($sql)->execute();
			$sql="update epmms_groupmap as u set groupmap_order=g.group_order from (select groupmap_id,row_number() over( order by groupmap_order asc) as group_order from epmms_groupmap  where groupmap_group_id=$groupid2) as g
			 where g.groupmap_id=u.groupmap_id";
			$db->createCommand($sql)->execute();
			$new_group1->group_count=$this->count('groupmap_group_id=:groupid',[':groupid'=>$groupid1]);
			$new_group2->group_count=$this->count('groupmap_group_id=:groupid',[':groupid'=>$groupid2]);
			$new_group1->save();
			$new_group2->save();
			$group->delete();
		}
	}
	public function verify_b($verifyType=1)
	{
		$finance=Finance::getMemberFinance(user()->isAdmin()?1:user()->id,2);
		$deduct_money=AwardConfig::model()->findByAttributes(['award_config_type_id'=>141])->award_config_currency;
		if($verifyType==1)
			if(!$finance->deduct($deduct_money))
			{
				return EError::NOMONEY;//电子币不足
			}
		$this->groupmap_order=new CDbExpression("nextverify_groupmap()");
		$this->saveAttributes(['groupmap_order']);
		Yii::import('ext.award.MySystemb');
		$mysystem=new MySystemb($this);
		$mysystem->run(2,1,1);
		return EError::SUCCESS;
	}
	public function genUsername()
	{
		$lower="100000";
		$upper="999999";
		$model=GroupMap::model();
		do
		{
			$username='b_'. (string)rand($lower,$upper);
		}while(strpos($username,'4')!==false || $model->exists('groupmap_name=:username',[':username'=>$username]));
		return $username;
	}
	public function genNewMember()
	{
		$model=new GroupMap();
		$model->groupmap_member_id=$this->groupmap_member_id;
		$model->groupmap_name=$this->genUsername();
		if($model->save())
		{
			$status=$model->verify_b(30);
			if($status==EError::SUCCESS)
			{
				return $model;
			}
			else
			{
				return $status;
			}
		}
		else
		{
			//保存失败
			return EError::SAVE;
		}
	}
	public function getShowName()
	{
		return $this->groupmap_name;
	}
}