<?php

/** 
 * @author hetao
 * 奖金制度
 */
class AwardSystem extends \CComponent
{
	protected $_name;
	protected $_map;
	protected $_period;
	public $sum;

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
	public function run($group,$calc,$calc_type)
	{
		$this->init();
	

		$this->calc($group,$calc,$calc_type);
		$this->sum();
	}
}
?>