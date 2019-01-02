<?php
$formItem=array
(
	'activeForm'=>array(
		'id'=>'memberinfo-form',
		'class'=>'ActiveForm',
		'enableClientValidation'=>true,
		'enableAjaxValidation'=>true,
		'clientOptions'=>array(	'validateOnSubmit'=>true),
	),
	'showErrorSummary'=>true,
	'elements'=>MemberinfoItem::model()->getMyUpdateItems(),
	'buttons'=>array
	(
		array('type'=>'image','attributes'=>array('src'=>themeBaseUrl() . '/images/button/save.gif'))
	)
);
return $formItem;
?>