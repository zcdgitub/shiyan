<style type="text/css">
	#tb_window td
	{
		vertical-align:top;
	}
	.mod {
		border: 1px solid #CFCFCF;
	}
	.mod h2 {
		background: none repeat scroll 0 0 #EEEEEE;
		border-bottom: 1px solid #CFCFCF;
		font-size: 10.5pt;
		height: 26px;
		line-height: 26px;
		padding: 0 0 0 10px;
	}
	.mod .body {
		padding: 5px;
	}

	.mod h2 a {
		text-decoration: none;
	}

	.mod h2 a.title {
		color: #000000;
		font-size: 10.5pt;
	}

	.TopicList table {
		margin: 5px 0 10px;
	}

	.TopicList table td {
		border-bottom: 1px dashed #CCCCCC;
		padding: 3px 0 4px;
		white-space: nowrap;
	}
	.TopicList table td.last_post {
		color: #888888;
		font-family: Arial,宋体;
		font-size: 9pt;
		text-align: right;
	}

	.TopicList table td.thread a {
		height: 18px;
		line-height: 18px;
		padding-left: 20px;
		text-decoration: none;
	}
	.thread_type_1 {
		background: url("<?php echo themeBaseUrl();?>/images/thread_1.gif") no-repeat scroll left center transparent;
	}

	.area_more a.more {
		color: #AA0000;
		float: right;
	}
	.status_view
	{
		margin-bottom: 0px;
	}
	.status_view .view_td
	{
		border-right: 1px solid #CCCCCC;
		font-size:12px;
	}
	.status_view .right_border
	{
		border-right:none;
	}
	.status_grid tr td,.status_grid tr td.right
	{
		border-bottom: 1px solid #cccccc;
	}

	.status_grid tr td.right
	{
		border-bottom: 1px solid #cccccc;
		padding:10px;
	}
	.status_grid tr td.right
	{
		text-align: right;
	}
	.status_grid tr td.left
	{
		text-align: left;
	}
	.status_grid tr td
	{
		font-size:20px;
		padding:10px;
		height:26px;
	}
    .status_grid tr:nth-child(odd)
    {
        background-color: #33FF33;
    }
    .status_grid tr:nth-child(even)
    {
        background-color: #66FFFF;
    }
	#content
	{
		width:90%;
	}
	.news_banner
	{
		border-collapse:collapse;
	}
	.news_none_leftborder,.news_none_leftborder div
	{
		border-left:0px;
	}
	.news_height
	{
		height:140px;
	}
	.area_more
	{
		position: absolute;
		bottom: 5px;
		right:5px;
	}
	.news_content
	{
		position: relative;
	}
</style>
<table id="tb_window" width="100%" cellspacing="0" cellpadding="0" >
	<tbody>
	<?php if(webapp()->id!='150608'):?>
	<tr>
		<td>
			<?php
			echo $this->renderPartial('_announcement',['models'=>$announcement]);
			?>
		</td>
	</tr>
	<?endif;?>
	<?php if(webapp()->id!='150228'):?>
	<tr style="">
		<td style="">
			<table>
				<tbody>
				<tr><td><?=$this->renderPartial('_awardSum',['model'=>$award_total,'awardType'=>$award_type,'award_sum'=>$award_sum])?></td></tr>
				</tbody>
			</table>
		</td>
	</tr>
	<?endif;?>
	<tr>
		<td  style="">
			<table>
				<tbody>
				<tr><td>
						<?php
						if(user()->isAdmin())
							echo $this->renderPartial('_info',[
								'sys_status'=>$sys_status,
								'member_count'=>$member_count,
								'baodan'=>$baodan,
								'dianzi'=>$dianzi,
								'jifen'=>$jifen,
								'productCount'=>$productCount,
								'agent_count'=>$agent_count,
								'last_verify_date'=>$last_verify_date,
								'transfer_count'=>$transfer_count,
								'charge_count'=>$charge_count,
								'agent_count2'=>$agent_count2,
								'member_count2'=>$member_count2,
								'withdrawals_count'=>$withdrawals_count,
								'dirSize'=>$dirSize,
								'spaceExpiry'=>$spaceExpiry,
								'domainExpiry'=>$domainExpiry,
								'tryDate'=>$tryDate,
								'spaceQuota'=>$spaceQuota,
								'day_cnt'=>$day_cnt,
								'day_money'=>$day_money
							]);
						else
							echo $this->renderPartial('_info2',[
								'unread_count'=>$unread_count,
								'member_count3'=>$member_count3,
								'member_count4'=>$member_count4,
								'user'=>$user,
							   'productCount'=>$productCount,
								'financeType'=>$financeType,
								'day_cnt'=>$day_cnt,
								'day_money'=>$day_money
							]);
						?>
					</td></tr>
				</tbody>
			</table>
		</td>
	</tr>
	</tbody>
</table>
