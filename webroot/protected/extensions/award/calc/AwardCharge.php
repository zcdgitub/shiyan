<?php

/** 
 * @author hetao
 * 游戏充值提成
 */
class AwardCharge extends \Award
{
	protected $_name="游戏充值提成";
	protected $_id=158;
	public function run($foundation)
	{
		$award=new AwardPeriod();
		switch($this->map->membermap_membertype_level)
		{
			case 1:
				$percent=0.05;
				break;
			case 2:
				$percent=0.15;
				break;
			case 3:
				$percent=0.3;
				break;
			case 4:
				$percent=0.5;
				break;
		}
		$award->award_period_currency=$foundation*$percent;
		$award->award_period_type_id=$this->id;
		$award->award_period_memberinfo_id=$this->map->membermap_id;
		$award->award_period_period=$this->system->period;
		$award->award_period_sum_type=4;
		$award->award_period_add_date=new CDbExpression('now()');
		if(!$award->save())
		{
			throw new Error('保存奖金失败');
		}
	}
}
?>