<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
	<link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
	<?php
	registerCssFile(Yii::app()->request->baseUrl . '/css/screen.css');
	registerCssFile(Yii::app()->request->baseUrl . '/css/form.css');
	registerCssFile(themeBaseUrl() . '/css/epmms.css');
	registerCssFile(themeBaseUrl() . '/css/style2.css');
	?>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php
	registerCssFile(themeBaseUrl() . '/css/epmms.css');
	registerCssFile(themeBaseUrl() . '/css/style2.css');
	?>

</head>

<body onload="window.status='Welcome!'" onmouseover="self.status='Welcome!';return true">
<script language="javascript">
	function qiehuan(num){
		for(var id = 0;id<=9;id++)
		{
			if(id==num)
			{
				document.getElementById("qh_con"+id).style.display="block";
				document.getElementById("mynav"+id).className="nav_on";
			}
			else
			{
				document.getElementById("qh_con"+id).style.display="none";
				document.getElementById("mynav"+id).className="";
			}
		}
	}
</script>
<?php
$menus=MenuNav::model()->getItems();
$cnt=0;
?>
<div id=menu>
	<table width="1003" height="82" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td width="37" valign="top"  class="tit1"><img src="<?php echo themeBaseUrl()?>/images/ind_1.jpg" width="30" height="81" /></td>
			<td width="233"  class="tit1">
				&nbsp;&nbsp;<?php echo t('epmms','欢迎您')?>
				<?php
				echo user()->info->showTitle;
				?>
				<?=t('epmms','身份')?>:<?php

				if(MemberinfoItem::model()->itemVisible('membermap_agent_id') || user()->isAdmin())
				{
					$role=webapp()->getAuthManager()->getAuthItem(webapp()->user->getRole());
					echo t('epmms',$role->description);
					if($role->name=='agent' && !is_null(user()->map->membermap_agent_type))
					{
						echo '&nbsp;&nbsp;';
						echo t('epmms','类型') . ':' .  t('epmms',user()->map->agenttype->showName);
					}
				}
				else
				{
					echo t('epmms',Memberinfo::model()->modelName);
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
							echo t('epmms', Memberinfo::model()->modelName) . t('tpmms', '等级')  . ':' . user()->map->memberlevel->showName;
						}
						else
						{
							echo '&nbsp;&nbsp;';
							echo t('epmms', Memberinfo::model()->modelName) . t('epmms','等级') . ':' . t('epmms','无');
						}
					}
				}
				?>
				<a href="<?php echo $this->createUrl('site/logout')?>" ><?=t('epmms','退出')?></a></td>
			<td width="733" valign="bottom">
				<div id="navdiv">
				<ul id=nav>
					<?php foreach($menus as $menu):?>
						<li><a href="#" onmouseover="javascript:qiehuan(<?=$cnt?>)" id="mynav<?=$cnt?>" class="nav_off"><?=t('epmms',$menu['label'])?></a></li>
						<?php
						$cnt++;
						?>
					<?endforeach;?>
				</ul>
				</div>
			</td>
		</tr>
	</table>
	<div id="subnav">
		<table width="987" border="0" align="center" cellpadding="0" cellspacing="0" class="bian2">
			<tr>
				<td height="30" bgcolor="#240200">
					<!--子菜单开始-->
					<?php
					$cnt=0;
					?>
					<?php foreach($menus as $menu):?>
						<div id=qh_con<?=$cnt?> style="display: none">
							<ul>
								<?php foreach($menu['items'] as $one_menu):?>
								<li><a href="<?=createUrl($one_menu['url'][0])?>" class="topa"><?=t('epmms',$one_menu['label'])?></a>　|</li>
								<?endforeach;?>
							</ul>
						</div>
						<?php
						$cnt++;
						?>
					<?endforeach;?>
					<!--子菜单结束-->
				</td>
			</tr>
		</table>
	</div>
</div>
<div id=banner>
	<table width="987" border="0" align="center" cellspacing="0" cellpadding="0" class="bian2">
		<tr>
			<td height="207" align="center" background="<?php echo themeBaseUrl()?>/images/Banner.jpg" style="background-position:center"><img src="<?php echo themeBaseUrl()?>/images/ind_gg.jpg" width="987" height="207" /></td>
		</tr>
	</table>
</div>
<div id=contents class="ind_b2">
	<div class="ep-container">

		<?php if(isset($this->breadcrumbs)):?>
			<?php $this->widget('zii.widgets.CBreadcrumbs', array(
				'links'=>$this->breadcrumbs,
				'homeLink'=>CHtml::link(Yii::t('zii','Home'),$this->createUrl('start/index')),
			)); ?><!-- breadcrumbs -->
		<?php endif?>
		<?php $this->widget('ext.Flashes.Flashes'); ?>
		<?php echo $content; ?>
		<div class="clear"></div>
	</div><!-- page -->
<div id=foot>
	<table width="987" border="0" align="center" cellpadding="0" cellspacing="0" background="<?php echo themeBaseUrl()?>/images/ind_bg2.jpg">
		<tr>
			<td height="21" ><table width="925" height="26" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td width="925" height="26" valign="bottom">2013 - 2015 &copy; 版权所有</td>
					</tr>
				</table></td>
		</tr>
	</table>
</div>

<?php
if (Yii::app()->components['user']->loginRequiredAjaxResponse){
	Yii::app()->clientScript->registerScript('ajaxLoginRequired', '
            jQuery("body").ajaxComplete(
                function(event, request, options) {
                    if (request.responseText == "'.Yii::app()->components['user']->loginRequiredAjaxResponse.'") {
                        window.location.href ="' . webapp()->createUrl(user()->loginUrl[0]) . '";
                    }else if(request.responseText=="password2")
                    {
                    		window.location.href="' . webapp()->createUrl(user()->authenticUrl[0]) . '"
                    }
                }
            );
        ');
}
?>
</body>
</html>