<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-8-26
 * Time: 下午5:17
 * To change this template use File | Settings | File Templates.
 */

class CleanData extends CFormModel
{
	public $includeMember=0;
	public $isClean=1;
	public $isCharge=0;

	public function rules()
	{
		return array(
			array('isClean,includeMember,isCharge', 'in','range'=>[0,1]),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'includeMember' => t('epmms','是否删除会员'),
			'isClean' => t('epmms','清空数据'),
			'isCharge'=>t('epmms','自动充电子币')
		);
	}
	/**
	 * 清空数据
	 */
	public function clean()
	{
		//清空系统备份
		$bak=new Backup();
		$bak->cleanSys();
		//清空前备份
		$bak->autoBackup('清空数据',webapp()->format->formatdatetime(time()));
		//清空数据
		$proc=new DbCall('epmms_clean_data',[(int)$this->includeMember,(int)$this->isCharge]);
		$proc->run();
		$status=SystemStatus::model()->find();
		$status->system_status_start_date=new CDbExpression('now()');
		$status->save();
	}
}