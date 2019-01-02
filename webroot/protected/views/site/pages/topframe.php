<div class="top">
		<div class="logo" style="color:	#909090;font-family:MingLiU;font-size: 34px; margin-top:18px;">
			&nbsp;&nbsp;<?php echo t('epmms',config('site','title'))?>
		</div>
		<div class="types">EP-MMS</div>
		<div class="lang_select">
			<?php
			if(params('multi_language'))
			{
				$this->beginWidget ( 'zii.widgets.jui.CJuiButton', array(
						'buttonType' => 'buttonset',
						'name' => 'user-lang',
				) );
				// $this->widget ( 'zii.widgets.jui.CJuiButton', array(
				// 		'buttonType' => 'radio',
				// 		'name' => 'lang',
				// 		'id' => 'zh_cn',
				// 		'value' =>Yii::app()->language=='zh_cn'?true:false,
				// 		'caption' => '简体',
				// 		'htmlOptions'=>array('value'=>'zh_cn'),
				// 		'onclick'=>new CJavaScriptExpression('function(){$.cookie("lang","zh_cn",{ path: "/"});parent.location.reload();}')
				// ) );
				$this->widget ( 'zii.widgets.jui.CJuiButton', array(
						'buttonType' => 'radio',
						'name' => 'lang',
						'id' => 'zh_tw',
						'value' =>Yii::app()->language=='zh_tw'?true:false,
						'caption' => '繁体',
						'htmlOptions'=>array('value'=>'zh_tw'),
						'onclick'=>new CJavaScriptExpression('function(){$.cookie("lang","zh_tw",{ path: "/"});parent.location.reload();}')
				) );

				$this->widget ( 'zii.widgets.jui.CJuiButton', array(
						'buttonType' => 'radio',
						'name' => 'lang',
						'id' => 'en',
						'value' => Yii::app()->language=='en'?true:false,
						'caption' => '英文',
						'htmlOptions'=>array('value'=>'en'),
						'onclick'=>new CJavaScriptExpression('function(){$.cookie("lang","en",{ path: "/"});parent.location.reload();}')
				) );
				$this->endWidget ();
			}
			?>
		</div>
	</div>
	<div class="info" >
		<table style="float:left" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="left"><img src="<?php echo themeBaseUrl()?>/images/2_09.gif" alt=""
					height="32" style="margin-top: 1px;" /></td>
				<td align="center"><img src="<?php echo themeBaseUrl()?>/images/2_05.png" alt="" height="14" /></td>
				<td align="left">
					&nbsp;&nbsp;<?php echo t('epmms','欢迎您')?>
					<?php
					echo user()->info->showTitle;
					?>
					<?=t('epmms','身份')?>:<?php
					$role=webapp()->getAuthManager()->getAuthItem(webapp()->user->getRole());
					if(MemberinfoItem::model()->itemVisible('membermap_agent_id') || user()->isAdmin())
					{
						echo t('epmms',$role->description);
						if($role->name=='agent' && !is_null(user()->map->membermap_agent_type))
						{
							echo '&nbsp;&nbsp;';
							echo t('epmms','类型' . ':' . user()->map->agenttype->showName);
						}
					}
					else
					{
						if($role->name=='agent' || $role->name=='member')
							echo t('epmms',Memberinfo::model()->modelName);
						else
						{
							echo t('epmms',$role->description);
						}
					}
					if(!user()->isAdmin() && MemberType::model()->count()>1)
					{
						echo '&nbsp;&nbsp;';
						echo t('epmms',Memberinfo::model()->modelName . '类型') .':'. user()->map->membertype->showName;
					}
					if(!user()->isAdmin())
					{
						$item_model = MemberinfoItem::model();
						if ($item_model->getViewItem('membermap_level'))
						{
							if (!is_null(user()->map->memberlevel))
							{
								echo '&nbsp;&nbsp;';
								echo t('epmms', Memberinfo::model()->modelName . '等级' . ':' . user()->map->memberlevel->showName);
							}
							else
							{
								echo '&nbsp;&nbsp;';
								echo t('epmms', Memberinfo::model()->modelName . '等级' . ':无');
							}
						}
					}
					?>
				</td>
			</tr>
		</table>
		<table style="float:right" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" ><?php echo t('epmms','当前时间')?>:</td>
				<td align="left"><span id="ShowTime"></span>&nbsp;&nbsp;</td>
				<td align="center"><img src="<?php echo themeBaseUrl()?>/images/2_16.gif" alt=""
					height="14" /></td>
				<td>&nbsp;&nbsp;</td>
				<td>
					<a href="<?php echo yii::app()->homeUrl ?>" target="_top">
					<img src="<?php echo themeBaseUrl()?>/images/2_25.png" alt="" width="15" height="15" />
					</a>
				</td>
				<td align="left">
					<a href="<?php echo yii::app()->homeUrl ?>" target="_top"><?php echo t('epmms','首页')?></a>&nbsp;&nbsp;&nbsp;&nbsp;
				</td>
				<td ><a href="#" onclick="parent.frames['main'].history.go(-1);"><img
						src="<?php echo themeBaseUrl()?>/images/2_27.png" alt="" width="15" height="14" /></a></td>
				<td align="left"><a href="#"
					onclick="parent.frames['main'].history.go(-1);"><?php echo t('epmms','后退')?></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td ><a href="#"
					onclick="parent.frames['main'].history.go(1);"><img
						src="<?php echo themeBaseUrl()?>/images/2_03.png" alt="" width="14" height="14" /></a></td>
				<td align="left"><a href="#"
					onclick="parent.frames['main'].history.go(1);"><?php echo t('epmms','前进')?></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td ><a href="#" onclick="parent.window.close();"><img
						src="<?php echo themeBaseUrl()?>/images/2_8.png" alt="" width="14" height="14" /></a></td>
				<td align="left"><a href="#" id="logout-link"><?php echo t('epmms','退出')?></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td align="right"><img src="<?php echo themeBaseUrl()?>/images/2_12.gif" alt="" height="32" /></td>
			</tr>
		</table>
	</div>
<script>
$(
	function()
	{
		$("#ShowTime").clock({timestamp:<?php echo time()*1000?>,datestr:'<?=t('epmms',"yyyy年mm月dd日")?>',timestr:"HH:MM:ss","calendar":"true"});
		$("#logout-link").click(function(){
			$("#logout-link").click(function(){
             top.location.href="<?php echo $this->createUrl('logout')?>";
            
          });
        });
	}
);
</script>