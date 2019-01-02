<?php

/** 
 * @author hetao
 * 制度方案
 */
class Upgrade extends \AwardSystem
{
	protected $_name='131104A';
	public function __construct($map)
	{
		$this->_map=$map;
		$dbPeriod=new DbEvaluate("nextval('award_period')");
		$this->_period=$dbPeriod->run();
	}
	public function calc($group,$calc,$calc_type)
	{
		$sumProc=new DbCall('epmms_upgrade_member',array((int)$this->map->membermap_id,$this->getPeriod(),$group,$calc,$calc_type));
		$sumProc->run();
		$this->map->membermap_period=$this->period;
	}
	public function sum()
	{

	}

}

?>