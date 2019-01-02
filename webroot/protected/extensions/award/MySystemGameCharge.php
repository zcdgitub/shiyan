<?php

/** 
 * @author hetao
 * 制度方案
 */
class MySystemGameCharge extends CComponent
{
	protected $_name='140809';
	protected $_map;
	protected $_period;
	public $sum;

	public function calc($group,$calc,$calc_type,$foundation)
	{
		Yii::import('ext.award.calc.AwardCharge');
		$calc152=new AwardCharge($this);
		$calc152->run($foundation);
		$sumProc=new DbCall('epmms_award_sum',array($this->map->membermap_id,$this->period));
		$sumProc->run();
	}
	public function run($group,$calc,$calc_type,$foundation)
	{
		$this->init();
		$this->calc($group,$calc,$calc_type,$foundation);
		$this->sum();
	}
	public function __construct($map)
	{
		$this->_map=$map;
		$dbPeriod=new DbEvaluate("nextval('award_period')");
		$this->_period=$dbPeriod->run();
	}
	public function getName()
	{
		return $this->_name;
	}
	public function getMap()
	{
		return $this->_map;
	}
	public function getPeriod()
	{
		if(is_null($this->_period))
			throw new Error('期次为空');
		return $this->_period;
	}
	public function init()
	{
	}
	public function sum()
	{
	}

}

?>