<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-9-1
 * Time: 下午3:12
 * To change this template use File | Settings | File Templates.
 */
?>
<div class="mod">
	<h2 style="margin-bottom: 0px">
		<span class="title" ><?=t('epmms','状态')?></span>
	</h2>
<table class="status_view">
	<tr style="height: 30px;">
		<td class="view_td">
			<table class="status_grid">
				<?php foreach($financeType as $finance):?>
				<tr  class="finance">
					<td  class="right"><?=t('epmms',$finance->showName)?>:<td  class="left"><?=webapp()->format->formatNumber(@$finance->getMemberFinance(user()->id)->finance_award)?></td>
				</tr>
				<?php endforeach;?>
			</table>
		</td>
		<td class="view_td">
			<table class="status_grid">
				 <tr  >
					<td  class="right"><?=t('epmms','业绩')?>:</td><td class="left"><?=$productCount?></td>
				</tr> 
				<tr>
					<td  class="right"><?=t('epmms','未读消息')?>:</td><td  class="left"><?=$unread_count?></td>
				</tr>
				<tr>
					<td  class="right"><?=t('epmms','未审核业务员')?>:</td><td  class="left"><?=$member_count3?></td>
				</tr>
				<tr>
					<td  class="right"><?=t('epmms','已审核业务员')?>:</td><td  class="left"><?=$member_count4?></td>
				</tr>

			</table>
		</td>
		<td class="view_td right_border">
			<table class="status_grid">
				<tr>
					<td class="right"><?=t('epmms','注册时间')?>:</td><td class="left"><?=$user->membermap_add_date?></td>
				</tr>
				<tr>
					<td class="right"><?=t('epmms','激活时间')?>:</td><td  class="left"><?=$user->membermap_verify_date?></td>
				</tr>
				<tr>
					<td class="right"><?=t('epmms','推荐数')?>:</td><td  class="left"><?=$user->membermap_recommend_number?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>