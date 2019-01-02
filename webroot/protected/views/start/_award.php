<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-8-29
 * Time: 下午10:41
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="mod TopicList" id="HomeJobTopicPanel"  style="margin-left: 5px;">
	<h2>
		<a class="title" href="/question?catalog=100"><?=t('epmms','奖金')?></a>
	</h2>
	<div class="body">
		<table width="100%" cellspacing="0" cellpadding="0" id="award_list">
			<tbody>
			<?php
				foreach($models as $item):
			?>
			<tr>
				<td class="thread" align="center">
					<?=$item->awardPeriodMemberinfo->showName?>
				</td>
				<td class="thread">
					<?=t('epmms',$item->awardPeriodType->award_type_name)?>
				</td>
				<td class="thread">
					<?=$item->award_period_currency?>
				</td>
				<td class="thread">
					<?=$item->award_period_add_date?>
				</td>
			</tr>
			<?php
				endforeach;
			?>
			</tbody>
		</table>
		<div id="jobs_area" class="area_more">
			<a class="more" href="<?=$this->createUrl('awardPeriod/list')?>"><?=t('epmms','更多')?>&nbsp;»</a>
			<div class="clear"></div>
		</div>
	</div>
</div>