<style type="text/css">
html {
	overflow-y: auto;
	overflow-x: hidden
	background-color:red;
}
</style>
<script type="text/javascript">
	$(function()
    {
	    tab(0);
	    $("#menubar").css("height",
	            (document.documentElement.clientHeight - 1) + "px")
    });
    var tab = function(a)
    {
	    var el = $("ul.MM").hide();
	    $(el[a]).show();
	    $("div.main_top").click(function()
	    {
		    var index = $("div.main_top").index(this);
		    for ( var i = 0; i < el.length; i++)
		    {
			    el[i].style.display = i == index ? "block" : "none";
		    }
	    });
    };
</script>
<table width="168" height="auto" border="0" align="right"
	cellpadding="0" cellspacing="0" id="menubar" style="margin-left:1%;">
	<tr>
		<td width="168" valign="top" bgcolor="#e5f4fd">
		<div class="menu_m"><?php echo t('epmms','管理菜单')?></div>
			<div class="main">
			<?php
			foreach($menu_items as $item)
			{
				
				?>
				<div class="main_top">
					<img src="<?php echo themeBaseUrl()?>/images/1-01.gif" /><a href="javascript:void(0)"><?php echo Yii::t('epmms',$item['label'])?></a>
				</div>
				<ul class="MM">
				<?php
				foreach($item['items'] as $child_item)
				{
				?>
					<li><a href="<?php echo createUrl($child_item['url'][0])?>" target="<?=strncmp($child_item['url'][0],'http',4)!=0?'main':'_parent'?>"><?php echo t('epmms',$child_item['label'])?></a></li>
				<?php
				}
				?>
				</ul>
			<?php
			}
			?>

			</div>
		</td>
	</tr>
</table>
