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
	//Yii::app()->clientScript->registerCoreScript('jquery');
	//Yii::app()->clientScript->registerCoreScript('cookie');
	?>
	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<style type="text/css">
	body
	{
		margin: 0;
		padding: 0;
		color: #555;
		font: normal 10pt Arial,Helvetica,sans-serif;
		background: white;
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
<body>
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