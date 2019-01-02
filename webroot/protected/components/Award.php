<?php

/** 
 * @author hetao
 * 奖金结算算法
 */
abstract class Award extends \CComponent
{
	/**
	 * 奖金名称
	 * 
	 * @var string
	 */
	protected $_name;
	/**
	 * 奖金id，与award_type中的id对应
	 * 
	 * @var mixed
	 */
	protected $_id;
	/**
	 * 算法应用的制度
	 * 
	 * @var AwardSystem
	 */
	protected $_system;
	/**
	 * 当前审核的会员map
	 * 
	 * @var Membermap
	 */
	protected $_map;
	protected $_db;
	/**
	 * 执行结算
	 */
	public function __construct($system)
	{
		$this->_system = $system;
		$this->_map = $system->map;
	}

	public function getName()
	{
		return $this->_name;
	}
	public function getId()
	{
		return $this->_id;
	}
	public function getSystem()
	{
		return $this->_system;
	}
	public function getMap()
	{
		return $this->_map;
	}
	/**
	 * 返回结算奖金的sql
	 * @return string
	 */
	protected function getAwardSql()
	{
		return '';
	}
	protected function getAwardProc()
	{
	
	}	
	/**
	 * 得到先族的where条件
	 * 
	 * @param boolean $full
	 *        	是否包含自己
	 * @return string
	 */
	public function getAncestry($my,$node,$full = false)
	{
		if ($full)
			return "{$my} like concat($node,'%')";
		else
			return "{$my} like concat($node,'/%')";
	}
	/**
	 * 得到派生族的where条件
	 * 
	 * @param boolean $full
	 *        	是否包含自己
	 * @return string
	 */
	public function getProgeny($my,$node,$full = false)
	{
		if ($full)
			return "$node like concat($my,'%')";
		else
			return "$node like concat($my,'/%')";
	}
	public function getAwardCmd()
	{
		$insertSql=<<<SQL
insert into epmms_award_period
				(
					award_period_period,
					award_period_memberinfo_id,
					award_period_currency,
					award_period_type_id
				)

SQL;
		$cmd=$this->db->createCommand($insertSql . $this->getAwardSql());
		return $cmd;
	}
	public function getDb()
	{
		if(is_null($this->_db))
			$this->_db=webapp()->db;
		return $this->_db;
	}
	public function setDb($db)
	{
		$this->_db=$db;
	}
}

?>