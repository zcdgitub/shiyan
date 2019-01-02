<?php

/** 
 * @author hetao
 * 存储过程或函数
 * 用法：
 * $fun='return concat(pa,pb,now());';
 * $myfun=new DbProcedure($fun,array('pa'=>'varchar(20)','pb'=>'varchar(20)'),'varchar(20)');
 * $value=$myfun->run(array('pa'=>'abcd','pb'=>'1234'));
 * echo var_dump($value);
 */
class DbProcedure extends \CComponent
{
	const DETERMINISTIC='DETERMINISTIC';
	const NONDETERMINISTIC='NOT DETERMINISTIC';
	const READSSQL='READS SQL DATA';
	const CONTAINSSQL='CONTAINS SQL';
	const NOSQL='NO SQL';
	const MODIFIESQL='MODIFIES SQL DATA';
	
	public $sqlDeterministic=self::NONDETERMINISTIC;
	public $dataCharacteristic=self::MODIFIESQL;
	public $body;
	public $params;
	public $return;
	protected $_id;
	protected $_cmd;

	/**
	 * 构造函数
	 * @param unknown_type $函数体
	 * @param unknown_type $函数参数
	 * @param unknown_type $函数返回值
	 */
	function __construct($body,$params=array(),$return=null)
	{
		$this->body=$body;
		$this->params=$params;
		$this->return=$return;
	}
	function getDefBody()
	{
		$output=<<<PROC
CREATE {$this->routine} {$this->id}({$this->defParams})
{$this->defReturn}
{$this->sqlDeterministic}
{$this->dataCharacteristic}
BEGIN
	$this->body
END
PROC;
		return $output;
	}
	function getId()
	{
		if(is_null($this->_id))
			$this->_id=uniqid('_epmms_');
		return $this->_id;
	}
	function setId($id)
	{
		$this->_id=$id;
	}
	function getDefParams()
	{
		$strArray='';
		foreach($this->params as $key=>$value)
		{
			if(is_string($value))
			{
				$strParam=$key . ' ' . $value;
			}
			else
			{
				$strParam='';
				$strParam.=isset($value['io'])?$value['io']:'in';
				$strParam.=' ';
				$strParam.=$key;
				$strParam.=' ';
				$strParam.=isset($value['type'])?$value['type']:'varchar(50)';
			}
			$strArray.=$strArray!==''?",$strParam":$strParam;
		}
		return $strArray;
	}
	function getRoutine()
	{
		return is_null($this->return)?'PROCEDURE':'FUNCTION';
	}
	function getDefReturn()
	{
		return is_null($this->return)?'':('RETURNS ' . $this->return);
	}
	/**
	 * 创建存储过程
	 */
	function create()
	{
		$this->cmd->text=$this->getDefBody();
		$this->cmd->execute();
	}
	/**
	 * 删除存储过程
	 */
	function drop()
	{
		$this->cmd->text=$this->defDrop;
		$this->cmd->execute();
	}
	function getCmd()
	{
		if(is_null($this->_cmd))
			$this->_cmd=Yii::app()->db->createCommand();
		return $this->_cmd;
	}
	protected function getDefDrop()
	{
		return 'DROP ' . $this->routine . ' IF EXISTS ' . $this->id;
	}
	/**
	 * 执行存储过程
	 * @param unknown_type $params
	 */
	public function call($params=array())
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
			$this->cmd->text="select {$this->id}({$callParams})";
			return $this->cmd->queryScalar($bindParams);
		}
		else
		{
			$this->cmd->text="call {$this->id}({$callParams})";
			return $this->cmd->execute($bindParams);
		}
	}
	/**
	 * 运行存储过程，创建，执行，删除一次性执行
	 * @param unknown_type $params
	 * @return unknown
	 */
	public function run($params=array())
	{
		$ret=null;
		$this->create();
		$ret=$this->call($params);
		$this->drop();
		return $ret;
	}
}

?>