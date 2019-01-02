<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-8-31
 * Time: 下午1:17
 * To change this template use File | Settings | File Templates.
 */
?>
<div id="award_sum" class="mod" style="margin-top:20px;">
	<h2 style="margin-bottom: 0px">
		<span class="title" ><?=t('epmms','奖金总计')?></span>
	</h2>
	<table>
		<thead>
		<tr style="height: 30px;"  align="center">
			<?php foreach($awardType as $award):?>
				<td style="height: 30px; padding:5px; border-bottom:1px solid #ccc; border-right:1px solid #ccc" align="center">
					<?=t('epmms',$award->award_type_name)?>
				</td>
			<?endforeach;?>
			<td style="height: 30px; padding:5px; border-bottom:1px solid #ccc;" align="center">
				<?=t('epmms','合计')?>
			</td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<?php foreach($awardType as $award):?>
				<td style="height: 26px; padding:5px;border-bottom:1px solid #ccc; border-right:1px solid #ccc" >
					<?php
					$award_attr=user()->isAdmin()?'_awardSum_' . $award->award_type_id:'_awardSumMember_' . $award->award_type_id;
					?>
					<?=number_format(@$model->$award_attr,2)?>
				</td>
			<?php endforeach;?>
			<td style="height: 26px; padding:5px;  border-bottom:1px solid #ccc;">
				<?=number_format($award_sum['total'],2)?>
			</td>
		</tr>
		</tbody>
	</table>
</div>