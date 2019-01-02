<?php

/** 
 * @author hetao
 * 制度方案
 */
class MySystemOrder extends \AwardSystem
{
	protected $_name='131104A';
	public function __construct($map)
	{
		$this->_map=$map;
		$dbPeriod=new DbEvaluate("nextval('award_period')");
		$this->_period=$dbPeriod->run();
	}
	public function calc($group,$calc,$calc_type,$order_id)
	{
		$sumProc=new DbCall('epmms_verify_award_order',array($this->map->membermap_id,$this->getPeriod(),$group,$calc,$calc_type,$order_id));
		$sumProc->run();
	}
	public function run1($group,$calc,$calc_type,$order_money)
	{
		$this->init();
		$this->calc($group,$calc,$calc_type,$order_money);
		$this->sum();
		$this->genMember();
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
		$sql="select * from epmms_finance,epmms_membermap where membermap_id=finance_memberinfo_id and finance_type=3 and finance_award>= case membermap_membertype_level when 1 then 20000 when 2 then 36000 else null end;";

		$command=$connection->createCommand($sql);
		$datareader=$command->query();
		foreach($datareader as $row)
		{
			$upgrade=new MemberUpgrade('create');
			$upgrade->member_upgrade_member_id=$row['membermap_id'];
			$upgrade->member_upgrade_money=$row['membermap_membertype_level']==1?20000:36000;
			$upgrade->member_upgrade_is_verify=0;
			$upgrade->member_upgrade_old_type=$row['membermap_membertype_level'];
			$upgrade->member_upgrade_type=$row['membermap_membertype_level']+1;
			$upgrade->save();
			$upgrade->verify();
		}
		$transaction->commit();
	}
}
?>