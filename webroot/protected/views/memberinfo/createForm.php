<?php

$form=array
(
	'activeForm'=>array(
		'id'=>'memberinfo-form',
		'class'=>'ActiveForm',
		'enableClientValidation'=>true,
		'enableAjaxValidation'=>true,
		'clientOptions'=>array(	'validateOnSubmit'=>true),
	),
	'showErrorSummary'=>true,
	'elements'=>array
	(
		'base'=>array(
				'type'=>'form',
				'title'=>t('epmms','个人信息'),
				'attributes'=>array('onlyFieldset'=>true),
				'elements'=>MemberinfoItem::model()->getRequireMemberinfoItems()
		),
		'membermap'=>array(
				'type'=>'form',
				'title'=>t('epmms','会员关系'),
				'attributes'=>array('onlyFieldset'=>true),
				'elements'=>MemberinfoItem::model()->getRequireMembermapItems()
		),
		'orders'=>array(
			'type'=>'OrdersForm',
			'title'=>t('epmms','订单'),
			'attributes'=>array('onlyFieldset'=>true),
			'visible'=>true
		),
		'other'=>array(
				'type'=>'form',
				'title'=>t('epmms','详细资料') . '(<span style="font-weight:normal;">' . t('epmms','点击展开或折叠') . '</span>)',
				'attributes'=>array(
						'collapsed'=>true,
						'legendHtmlOptions'=>array('title'=>t('epmms','点击展开或折叠')),
				),
				'elements'=>MemberinfoItem::model()->getMemberinfoItems()
		)
	),
	'buttons'=>array
	(
		array('type'=>'image','attributes'=>array('src'=>themeBaseUrl() . '/images/button/reg.gif'))
	)
);
if(!params('ordersForm'))
{
	unset($form['elements']['orders']);
}
return $form;
?>