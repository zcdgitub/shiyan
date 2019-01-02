<?php

/** 
 * @author hetao
 * 代理中心报单费
 */
class AwardFoundation extends \Award
{
	protected $_name="信用基金申请佣金";
	protected $_id=152;
	public function run($foundation)
	{
		$recommend=$this->map->membermapRecommend;
		if($this->map->membermap_id!=1)
		{
			$award=new AwardPeriod();
			$award->award_period_currency=$foundation*0.05;
			$award->award_period_type_id=$this->id;
			$award->award_period_memberinfo_id=$recommend->membermap_id;
			$award->award_period_period=$this->system->period;
			$award->award_period_sum_type=3;
			$award->award_period_add_date=new CDbExpression('now()');
			if(!$award->save())
			{
				throw new Error('保存奖金失败');
			}
		}
	}
}
?>