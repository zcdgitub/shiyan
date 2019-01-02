<?php

/** 
 * @author hetao
 * 制度方案
 */
class MySystem extends \AwardSystem
{
	protected $_name='140920';
	public function __construct($map)
	{
		$this->_map=$map;
		$dbPeriod=new DbEvaluate("nextval('award_period')");
		$this->_period=$dbPeriod->run();
	}
	public function calc($group,$calc,$calc_type)
	{
		// $group=2;
		// $calc=1;
		// $calc_type=0;
		$sumProc=new DbCall('epmms_verify_award_group',array((int)$this->map->membermap_id,$this->getPeriod(),$group,$calc,$calc_type));

		$sumProc->run();
		$Proc=new DbCall('gen_finance_log');
		
		$Proc->run();
		$this->map->membermap_period=$this->period;
		$this->genMember();
	}
	public function sum()
	{

	}
	public function genMember()
	{
		//拿600的见点奖送点位
		$connection=Yii::app()->db;
		$transaction=webapp()->db->beginTransaction();
		$sql="select membermap_id as id from epmms_award_total,epmms_membermap where membermap_id=award_total_memberinfo_id and award_total_type_id=409 and award_total_currency>=10000";

		$command=$connection->createCommand($sql);
		$datareader=$command->query([':id'=>$this->map->membermap_id]);
		foreach($datareader as $row)
		{
			if($mymember=Membermap::model()->findByPk($row['id']))
			{
				$newMember=$this->genNewMember($mymember);
				if(is_object($newMember))
				{
					if(!Messages::send('生成会员','生成的会员:'.$newMember->memberinfo_account,$mymember->membermap_id))
					{
						$transaction->rollback();
						throw new Error('发送邮件失败');
					}
				}
				else
				{
					$transaction->rollback();
					throw new Error('自动注册会员失败');
				}
				$mymember->membermap_is_active=0;
				$mymember->saveAttributes(['membermap_is_active']);
			}
		}
		$transaction->commit();
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
		$info->memberinfo_nickname=$root_info->memberinfo_nickname;;
		$info->memberinfo_password_repeat=$info->memberinfo_password;
		$info->memberinfo_password_repeat2=$info->memberinfo_password2;
		$info->memberinfo_add_date=new CDbExpression('now()');
		$info->memberinfo_is_verify=0;
		if($info->save()&&$info->refresh())
		{
			$map=new Membermap('create');
			$map->membermap_id=$info->memberinfo_id;
			$sql_child="select award.get_little_loc(:id)";
			$cmd_child=webapp()->db->createCommand($sql_child);
			$pid=$cmd_child->queryScalar([':id'=>$root_map->membermap_id]);
			$map->membermap_recommend_id=$root_map->membermap_id;
			$map->membermap_membertype_level=1;
			$map->membermap_is_verify=0;
			$map->membermap_agent_id=$root_map->membermap_agent_id;
			if(is_null($map->membermap_agent_id))
				$map->membermap_agent_id=1;
			//自动排网
			$map->membermap_parent_id=$pid;
			$map->membermap_order=1;

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
}

?>