<?php

/** 
 * @author hetao
 * 代理中心报单费
 */
class AwardLeader extends \Award
{
	protected $_name="领导奖";
	protected $_id=3;
	public function run()
	{
		$this->getAwardCmd()->execute();
	}
	protected function getAwardSql()
	{
		$awardSql=<<<SQL
select {$this->system->period},node.membermap_id,0.1*my.membermap_product_money,{$this->id} from epmms_membermap as node,epmms_membermap as my
where
	my.membermap_id={$this->map->membermap_id}
	and {$this->getAncestry('my.membermap_path','node.membermap_path')}
SQL;
		return $awardSql;
	}
}
?>