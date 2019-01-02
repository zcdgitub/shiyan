<?php
Yii::app()->clientScript->registerPushFile(['/images/bg4.png'=>3,'/images/loginBG.jpg'=>3])
?>
<!DOCTYPE html>
<html lang="zh">
<head>
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
	background-image: url(/images/loginBG.jpg);
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-repeat:no-repeat;
	background-position: top;
	background-color: #760000;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #555;
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
		<div id="contents" ><?php echo $content; ?></div>
	<div class="clear"></div>
<!--		<div id="footer">
		Copyright &copy; <?php /*echo date('Y'); */?> 易众软件。<br /> 保留所有权利。<br />
		</div>-->
		<!-- footer -->
	</div>
</body>
</html>