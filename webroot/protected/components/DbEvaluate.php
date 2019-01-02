<?php
/**
 *
 * @author hetao
 *
 */
class DbEvaluate extends \CDbExpression
{
	public function __toString()
	{
		return (string)$this->run();
	}
	public function run()
	{
		$cmd=webapp()->db->createCommand('select ' . $this->expression);
		$value=$cmd->queryScalar($this->params);
		return $value;
	}
}
?>