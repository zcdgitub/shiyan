<?php

/** 
 * @author hetao
 * 制度方案
 */
class MySystem_calc extends \AwardSystem
{
	protected $_name='141120';
	public function __construct()
	{
		$dbPeriod=new DbEvaluate("nextval('award_period')");
		$this->_period=$dbPeriod->run();
	}
	public function calc($group,$calc,$calc_type)
	{
		$sumProc=new DbCall('epmms_verify_award_group',array(null,$this->getPeriod(),$group,$calc,$calc_type));
		$sumProc->run();
	}
	public function sum()
	{
	}
}

?>