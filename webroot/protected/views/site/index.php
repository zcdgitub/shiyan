<?php
$role=webapp()->getAuthManager()->getAuthItem(webapp()->user->getRole());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<frameset rows="93,*" cols="*" border="0">
	<frame src="<?php echo $this->createUrl('page',array('view'=>'topframe'))?>" name="top" frameborder="0" scrolling="no" noresize="noresize" id="topFrame" title="topFrame"/>
	<frameset cols="170,9,*" id="myFrame" >
		<frame src="<?php echo $this->createUrl('menuNav/list')?>" name="left" frameborder="0" scrolling="auto"  noresize="noresize" id="leftFrame" title="leftFrame"/>
		<frame src="<?php echo $this->createUrl('page',array('view'=>'switchframe'))?>" name="mid" frameborder="0" scrolling="no"  noresize="noresize" id="midFrame" title="switchframe"/>
		<frame src="<?php echo $this->createUrl($role->name=='shop'?'announcement/list':'start/index')?>" name="main" id="main" frameborder="0" scrolling="auto"  noresize="noresize" id="mainframe" title="mainframe"/>
</frameset><noframes></noframes>
</html>