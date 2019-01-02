<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class Emailphone extends CFormModel {
	public $type;

	public function rules() {
		return array(
			array('type', 'required'),
		);
	}

	public function attributeLabels() {
		return array(
			'type'=>'找回密码方式',
		);
	}

	public function getForm() {
		$items=[];
		if(params('send_email'))
			$items['email']='Email';
		if(params('send_sms'))
			$items['phone']='手机';
		return new CForm(array(
			'showErrorSummary'=>true,
			'attributes'=>array('id'=>'emailphone'),
			'elements'=>array(
				'type'=>array(
					'type'=>'radiolist',
					'items'=>$items,
					'layout'=>'{label}<br/>{input}<br/>{error}'
				),
			),
			'buttons'=>array(
				'submit'=>array(
					'type'=>'submit',
					'label'=>'下一步'
				)
			)
		), $this);
	}
}