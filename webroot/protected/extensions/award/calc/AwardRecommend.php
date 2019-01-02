<?php

/** 
 * @author hetao
 * 代理中心报单费
 */
class AwardRecommend extends \Award
{
	protected $_name="推荐奖";
	protected $_id=2;
	public function run()
	{
		$recommend=$this->map->membermapRecommend;
		$award=new AwardPeriod();
		$award->award_period_currency=20;
		$award->award_period_type_id=$this->id;
		$award->award_period_memberinfo_id=$recommend->membermap_id;
		$award->award_period_period=$this->system->period;
		$award->save();
	}
}
?>