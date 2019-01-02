<?php
Yii::app()->clientScript->registerPushFile(['/images/bg20.png'=>3])
?>
<!DOCTYPE html>
<html lang="zh">
<head>
	<meta name="renderer" content="webkit">
	<meta charset="utf-8">
	<link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
	<?php
	registerCssFile(Yii::app()->request->baseUrl . '/css/screen.css');
	registerCssFile(Yii::app()->request->baseUrl . '/css/form.css');
	registerCssFile(themeBaseUrl() . '/css/epmms.css');
	Yii::app()->clientScript->registerCoreScript('jquery');
	Yii::app()->clientScript->registerCoreScript('cookie');
	?>
	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<style type="text/css">
	.browser-download
	{
		float: left;
		margin:2px 5px;
		width:56px;
	}
	.browser-icon
	{
		border: none;
		width:48px;
		height:48px;
	}
	.browser-intro
	{
		text-align:center;
	}
	body
	{
		margin: 0;
		padding: 0;
		color: black;
		font: normal 10pt Arial,Helvetica,sans-serif;
		background: #b4dd96;
	}
	.container {
		min-height:100%;
		height: 100% !important;
		position: relative;
		margin: 0px auto;
		width:40%;
	}
	#footer {
	position: absolute;
	bottom:0px;
	background-color: #ccc;
	width: 100%;
	height:50px;
	margin:0;
	padding:3px 0px 0px 0px;
	font-size: 0.8em;
	text-align: center;
	border-top: 1px solid #C9E0ED;
	}
	#content {
	padding-bottom:54px;
	/* 注意这个红色的高度：页脚内容总高度(字体大小,行距,padding等) 避免覆盖#content的文字*/
	width:40% ;margin:0 auto;
	}
	</style>
</head>
<body style="background:url(/images/bg6.png) no-repeat top center white; margin:0 auto;">
		<!--[if lt IE 9]>
		  <div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 85px; position: relative;z-index:20'>
		    <div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'><img src='<?php echo themeBaseUrl()?>/images/browser/ie6nomore-cornerx.jpg' style='border: none;' alt='关闭本提示'/></a></div>
		    <div style='width: 910px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
		      <div style='width: 75px; float: left;'><img src='<?php echo themeBaseUrl()?>/images/browser/ie6nomore-warning.jpg' alt='提示您升级浏览器'/></div>
		      <div style='width: 235px; float: left; font-family: Arial, sans-serif;'>
		        <div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>提示：您还在用过时的浏览器吗？</div>
		        <div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>为了获得更漂亮的外观，更快的速度，更高的安全性，请升级您的浏览器！<br/>(任选一个升级就不再出现本提示,Windows XP系统请使用IE以外的浏览器)</div>
		      </div>
		      <div class="browser-download" ><a href='http://rj.baidu.com/search/index/?kw=%25E8%25B0%25B7%25E6%25AD%258C%25E6%25B5%258F%25E8%25A7%2588%25E5%2599%25A8' target='_blank'><img  class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/chrome.png' alt='升级为Chrome浏览器'/><div class="browser-intro" >谷歌</div></a></div>
		      <div class="browser-download" <?=preg_match('/NT\s5/',$_SERVER['HTTP_USER_AGENT'])?'style="display:none" ':''?> ><a href='http://ie.microsoft.com/' target='_blank'><img class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/ie.png' alt='升级到微软公司的最新版Internet Explorer'/><div class="browser-intro" >最新版IE</div></a></div>
		      <div class="browser-download" ><a href='http://www.firefox.com.cn/' target='_blank'><img  class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/firefox.png' alt='升级为Firefox浏览器'/><div class="browser-intro" >Firefox</div></a></div>
		      <div class="browser-download" ><a href='http://chrome.360.cn/' target='_blank'><img  class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/360chrome.png' alt='升级为360极速浏览器'/><div class="browser-intro" >360极速</div></a></div>
		      <div class="browser-download" ><a href='http://se.360.cn/' target='_blank'><img  class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/360se.png' alt='升级为360安全浏览器'/><div class="browser-intro" >360安全</div></a></div>
		      <div class="browser-download" ><a href='http://www.maxthon.cn/' target='_blank'><img  class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/maxthon.png' alt='升级为傲游浏览器'/><div class="browser-intro" >傲游</div></a></div>
		      <div class="browser-download" ><a href='http://ie.sogou.com/' target='_blank'><img  class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/sogou.png' alt='升级为搜狗浏览器'/><div class="browser-intro" >搜狗</div></a></div>
		      <div class="browser-download" ><a href='http://www.liebao.cn/' target='_blank'><img  class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/liebao.png' alt='升级为猎豹浏览器'/><div class="browser-intro" >猎豹</div></a></div>
		      <div class="browser-download" ><a href='http://www.opera.com/' target='_blank'><img  class="browser-icon" src='<?php echo themeBaseUrl()?>/images/browser/opera.png' alt='升级为Opera浏览器'/><div class="browser-intro" >Opera</div></a></div>
		</div>
		  </div>
		<![endif]--> 
		<div class="lang_select">
		<?php
		if(params('multi_language'))
		{
			$this->beginWidget ( 'zii.widgets.jui.CJuiButton', array(
					'buttonType' => 'buttonset',
					'name' => 'user-lang',
			) );
			$this->widget ( 'zii.widgets.jui.CJuiButton', array(
					'buttonType' => 'radio',
					'name' => 'lang',
					'id' => 'zh_cn',
					'value' =>Yii::app()->language=='zh_cn'?true:false,
					'caption' => '简体',
					'htmlOptions'=>array('value'=>'zh_cn'),
					'onclick'=>new CJavaScriptExpression('function(){$.cookie("lang","zh_cn",{ path: "/"});location.reload();}')
			) );
			
			// $this->widget ( 'zii.widgets.jui.CJuiButton', array(
			// 		'buttonType' => 'radio',
			// 		'name' => 'lang',
			// 		'id' => 'zh_tw',
			// 		'value' =>Yii::app()->language=='zh_tw'?true:false,
			// 		'caption' => '繁體',
			// 		'htmlOptions'=>array('value'=>'zh_tw'),
			// 		'onclick'=>new CJavaScriptExpression('function(){$.cookie("lang","zh_tw",{ path: "/"});location.reload();}')
			// ) );

			$this->widget ( 'zii.widgets.jui.CJuiButton', array(
					'buttonType' => 'radio',
					'name' => 'lang',
					'id' => 'en',
					'value' => Yii::app()->language=='en'?true:false,
					'caption' => 'ENGLISH',
					'htmlOptions'=>array('value'=>'en'),
					'onclick'=>new CJavaScriptExpression('function(){$.cookie("lang","en",{ path: "/"});location.reload();}')
			) );
			$this->endWidget ();
		}
		?>
		</div>
	<div class="container">
		<div id="content" ><?php echo $content; ?></div>
	<div class="clear"></div>
<!--		<div id="footer">
		Copyright &copy; <?php /*echo date('Y'); */?> 易众软件。<br /> 保留所有权利。<br />
		</div>-->
		<!-- footer -->
	</div>
</body>
</html>