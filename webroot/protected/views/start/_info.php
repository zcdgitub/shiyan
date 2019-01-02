<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-9-1
 * Time: 下午3:12
 * To change this template use File | Settings | File Templates.
 */
?>
<style type="text/css">
	.right,.left{
		background: white;
	}

</style>
<div class="mod">
	<h2 style="margin-bottom: 0px">
		<span class="title" ><?=t('epmms','状态')?></span>
	</h2>
<table class="status_view" >
	<tr>
		<td class="view_td">
			<table class="status_grid">
				 <tr  >
					<td  class="right"><?=t('epmms','业绩')?>:</td><td class="left"><?=$productCount?></td>
				</tr> 	
				<tr >
					<td  class="right" ><?=t('epmms','激活币')?>:</td><td class="left"><?=$dianzi['sum']?></td>
				</tr>	
				<tr  >
					<td  class="right"><?=t('epmms','总积分')?>:</td><td class="left"><?=$jifen['sum']?></td>
				</tr>
				<tr  >
					<td  class="right"><?=$sys_status->getAttributeLabel('system_status_expenses')?>:</td><td class="left"><?=$sys_status->system_status_expenses?></td>
				</tr>
				<tr  >
					<td  class="right"><?=$sys_status->getAttributeLabel('system_status_income')?>:</td><td class="left"><?=$sys_status->system_status_income?></td>
				</tr>
				<tr  >
					<td  class="right"><?=$sys_status->getAttributeLabel('system_status_withdrawals')?>:</td><td class="left"><?=$sys_status->system_status_withdrawals?></td>
				</tr>
				<?if(webapp()->id=='140805'):?>
					<tr  >
						<td  class="right"><?=$sys_status->getAttributeLabel('system_status_foundation')?>:</td><td class="left"><?=$sys_status->system_status_foundation?></td>
					</tr>
				<?endif;?>
				<tr  >
					<td  class="right"><?=t('epmms','拨出比')?>:</td><td class="left"><?=$sys_status->system_status_income==0?'':webapp()->format->formatPercentage($sys_status->system_status_expenses/$sys_status->system_status_income)?></td>
				</tr>
			</table>
		</td>
		<td class="view_td">
			<table class="status_grid">
				<tr  >
					<td  class="right"><?=t('epmms','系统启动日期')?>:</td><td class="left"><?=$sys_status->system_status_start_date?></td>
				</tr>
				<tr  >
					<td  class="right"><?=t('epmms','会员总数')?>:</td><td class="left"><?=$member_count?></td>
				</tr>
				<?if(MemberinfoItem::model()->itemVisible('membermap_agent_id')):?>
				<tr  >
					<td  class="right"><?=t('epmms','代理中心数')?>:</td><td class="left"><?=$agent_count?></td>
				</tr>
				<?endif;?>
				<tr  >
					<td  class="right"><?=t('epmms','最后审核会员日期')?>:</td><td class="left"><?=$last_verify_date?></td>
				</tr>						
			</table>
		</td>
		<td class="view_td">
			<table class="status_grid">
				<tr  >
					<td  class="right"><?=t('epmms','未审核转帐')?>:</td><td class="left"><?=$transfer_count?></td>
				</tr>
				<tr  >
					<td  class="right"><?=t('epmms','未审核充值')?>:</td><td class="left"><?=$charge_count?></td>
				</tr>
				<tr  >
					<td  class="right"><?=t('epmms','未审核提现')?>:</td><td class="left"><?=$withdrawals_count?></td>
				</tr>
				<tr  >
					<td  class="right"><?=t('epmms','未审核会员')?>:</td><td class="left"><?=$member_count2?></td>
				</tr>
				<?if(MemberinfoItem::model()->itemVisible('membermap_agent_id')):?>
				<tr  >
					<td  class="right"><?=t('epmms','未审核代理中心')?>:</td><td class="left"><?=$agent_count2?></td>
				</tr>
				<?endif;?>
			</table>
		</td>
		<td class="view_td right_border">
			<table class="status_grid">
				<tr  >
					<td  class="right"><?=t('epmms','空间到期')?>:</td><td class="left"><?=$spaceExpiry?></td>
				</tr>
				<tr  >
					<td  class="right"><?=t('epmms','域名到期')?>:</td><td class="left"><?=$domainExpiry?></td>
				</tr>
				<tr  >
					<td  class="right"><?=t('epmms','空间使用')?>:</td><td class="left"><?=$dirSize?></td>
				</tr>
				<tr  >
					<td  class="right"><?=t('epmms','空间限额')?>:</td><td class="left"><?=$spaceQuota?></td>
				</tr>
				<tr  >
					<td  class="right"><?=t('epmms','运行模式')?>:</td><td class="left"><?=defined('YII_DEBUG')&&YII_DEBUG==true?t('epmms','测试模式'):t('epmms','产品模式')?></td>
				</tr>
				<?if(!empty($tryDate)):?>
				<tr  >
					<td  class="right"><?=t('epmms','试用到期')?>:</td><td class="left"><?=$tryDate?></td>
				</tr>
				<?endif;?>
			</table>
		</td>
	</tr>
</table>
</div>