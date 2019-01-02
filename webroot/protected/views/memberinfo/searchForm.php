<?php
$item=new MemberinfoItem();
$form= array
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
			'memberinfo_account' => array('type'=>'text'),
			'memberinfo_name' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_name')),
			'memberinfo_nickname' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_nickname')),
			'memberinfo_email' => array('type'=>'email','visible'=>$item->itemVisible('memberinfo_email')),
			'memberinfo_mobi' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_mobi')),
			'memberinfo_phone' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_phone')),
			'memberinfo_qq' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_qq')),
			'memberinfo_msn' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_msn')),
			'memberinfo_sex' => array('type'=>'sex','visible'=>$item->itemVisible('memberinfo_sex')),
			'memberinfo_idcard_type' => array('type'=>'idCardType','visible'=>$item->itemVisible('memberinfo_idcard_type')),
			'memberinfo_idcard' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_idcard')),
			'memberinfo_zipcode' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_zipcode')),
			/*
			'memberinfo_birthday' => array('type'=>'date','visible'=>$item->itemVisible('memberinfo_birthday')),
			'memberinfo_address_provience' => array('type'=>'dropdownlist','items'=>array(),'visible'=>$item->itemVisible('memberinfo_address_provience')),
			'memberinfo_address_area' => array('type'=>'dropdownlist','items'=>array(),'visible'=>$item->itemVisible('memberinfo_address_area')),
			'memberinfo_address_county' => array('type'=>'dropdownlist','items'=>array(),'visible'=>$item->itemVisible('memberinfo_address_county')),
			'memberinfo_address_detail' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_address_detail')),
			'memberinfo_bank_id' => array('type'=>'bank','visible'=>$item->itemVisible('memberinfo_bank_id')),
			'memberinfo_bank_name' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_bank_name')),
			'memberinfo_bank_account' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_bank_account')),
			'memberinfo_bank_provience' => array('type'=>'dropdownlist','items'=>array(),'visible'=>$item->itemVisible('memberinfo_bank_provience')),
			'memberinfo_bank_area' => array('type'=>'dropdownlist','items'=>array(),'visible'=>$item->itemVisible('memberinfo_bank_area')),
			'memberinfo_bank_branch' => array('type'=>'text','visible'=>$item->itemVisible('memberinfo_bank_branch')),*/
			'memberinfo_is_enable' => array('type'=>'enable'),
		),
		'buttons'=>array
		(
			array('type'=>'submit','label'=>t('epmms','搜索'))
		)
);
return $form;
?>