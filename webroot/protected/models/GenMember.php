<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-8-26
 * Time: 下午5:17
 * To change this template use File | Settings | File Templates.
 */

class GenMember extends CFormModel
{
	public $root;
	public $count=10;

	public function rules()
	{
		return array(
			array('root,count','required'),
			array('count', 'numerical','min'=>1,'max'=>100000000),
			array('root','exist','className'=>'Memberinfo','attributeName'=>'memberinfo_account'),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'root' => t('epmms','起始于'),
			'count' => t('epmms','生成会员个数'),
		);
	}
}