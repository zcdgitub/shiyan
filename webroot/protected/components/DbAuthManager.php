<?php

/** 
 * @author hetao
 * 
 */
class DbAuthManager extends \RDbAuthManager
{
	public function checkAccessRecursive($itemName,$userId,$params,$assignments)
	{
		return parent::checkAccessRecursive($itemName,$userId,$params,$assignments);
	}
}
?>