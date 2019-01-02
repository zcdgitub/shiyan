<?php

/** 
 * @author hetao
 * 代理中心报单费
 */
class AwardAgent extends \Award
{
	protected $_name="代理中心报单费";
	protected $_id=1;
	public function run()
	{
		$agent=$this->map->membermapAgent;
		$award=new AwardPeriod();
		$award->award_period_currency=100;
		$award->award_period_type_id=$this->id;
		$award->award_period_memberinfo_id=$agent->membermap_id;
		$award->award_period_period=$this->system->period;
		$award->save();
	}
}
?>