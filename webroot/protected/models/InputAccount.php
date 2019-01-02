<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class InputAccount extends CFormModel {
	public $account;

	public function rules() {
		return array(
			array('account', 'required'),
			array('account', 'ext.validators.Account','allowZh'=>false),
			array('account', 'ext.validators.Exist','allowEmpty'=>false,'className'=>'Memberinfo','attributeName'=>'memberinfo_account')
		);
	}

	public function attributeLabels() {
		return array(
			'account'=>'要找回密码的账号',
		);
	}

	public function getForm() {
		return new CForm(array(
			'showErrorSummary'=>true,
			'attributes'=>array('id'=>'account'),
			'elements'=>array(
				'account'=>array(
					'type'=>'text',
				),
			),
			'buttons'=>array(
				'submit'=>array(
					'type'=>'submit',
					'label'=>t('epmms','下一步')
				)
			)
		), $this);
	}
}