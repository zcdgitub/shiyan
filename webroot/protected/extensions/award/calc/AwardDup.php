<?php

/** 
 * @author hetao
 * 游戏充值提成
 */
class AwardDup extends \Award
{
	protected $_name="重复消费";
	protected $_id=205;
	public function run()
	{
		$sumProc=new DbCall('award.award_dup',array((int)$this->map->membermap_id,$this->system->getPeriod(),$this->id,3,1));
		$sumProc->run();
	}
}
?>