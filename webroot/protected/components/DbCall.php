<?php

/** 
 * @author hetao
 * 调用存储过程或函数
 */
class DbCall extends \CComponent
{
	protected $_cmd;
	public $procName;
	public $params=array();
	public $return=false;

	/**
	 * 构造函数
	 * @param string $procName 过程名
	 * @param array $params 调用参数
	 * @param boolean $return true是函数,false是存储过程,默认为false
	 */
	function __construct($procName,$params=array(),$return=false)
	{
		$this->procName=$procName;
		$this->params=$params;
		$this->return=$return;
	}
	/**
	 * 执行存储过程
	 * @param array $params
	 */
	protected function call($params=array())
	{
		$params2=array();
		$bindParams=array();
		foreach($params as $name=>$value)
		{
			$paramName=':call_' . $name;
			$params2[]=$paramName;
			$bindParams[$paramName]=$value;
		}
		$callParams=implode(',', $params2);
		if($this->return)
		{
			$this->cmd->text="select {$this->procName}({$callParams})";
			return $this->cmd->queryScalar($bindParams);
		}
		else
		{

			return $this->callProc($this->procName,$callParams,$bindParams);
		}
	}
	/**
	 * 运行存储过程或函数
	 * @return mixed 如果是存储过程返回影响的行数，如果是函数返回值
	 */
	public function run()
	{

		return $this->call($this->params);
	}
	protected function getCmd()
	{
		if(is_null($this->_cmd))
			$this->_cmd=Yii::app()->db->createCommand();
		return $this->_cmd;
	}
	protected function callProc($procName,$callParams,$bindParams)
	{
		$callCmd=webapp()->db->getDriverName()=='pgsql'?'select':'call';
		$this->cmd->text="$callCmd {$procName}({$callParams})";
	
		return $this->cmd->execute($bindParams);
	}
}
?>