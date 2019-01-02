<?php
/**
 * CHelpCommand class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 *
 * 发送奖金通知短信
 * @property string $help The command description.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.console
 * @since 1.0
 */
class SendFutouCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
		Yii::import('ext.YiiMailer.YiiMailer');
		$connection=Yii::app()->db;
		$sql="select memberinfo_id,memberinfo_account,memberinfo_nickname,memberinfo_email,award_sum_total_currency,membermap_money from award_sum_total,epmms_membermap,epmms_memberinfo
		where award_sum_total_memberinfo_id=membermap_id
		and membermap_id=memberinfo_id
		and award_sum_total_currency%2000>=1500
		and award_sum_total_currency>(select coalesce(count(*),0) from epmms_futou where futou_member_id=memberinfo_id)*2000;";
		$command=$connection->createCommand($sql);
		$datareader=$command->query();
		$mail = new YiiMailer();
		Yii::log('Mail sent start:' . date('Y-m-d H:i:s') .  "\r\n",'info','mail.cmd');
		foreach($datareader as $row)
		{
			if(empty($row['memberinfo_email']))
			{
				Yii::log( $row['memberinfo_account'] . ":邮件为空: \r\n",'warning','mail.cmd');
				continue;
			}
			$mail->setSubject('请及时复投');
			$mail->setBody('尊敬的会员，你的奖金已经达到' . $row['award_sum_total_currency'] . ',请及时复投,达到2000时，将停发奖金');
			$mail->setTo($row['memberinfo_email']);
			if ($mail->send()) {
				echo $row['memberinfo_email'] .  ':Mail sent successfuly' . "\r\n";
				Yii::log($row['memberinfo_email'] .  ':Mail sent successfuly' . "\r\n",'info','mail.cmd');
			} else {
				echo $row['memberinfo_email'] . ':Error while sending email: '.$mail->getError() . "\r\n";
				Yii::log( $row['memberinfo_email'] . ':Error while sending email: '.$mail->getError() . "\r\n",'warning','mail.cmd');
			}
		}
		Yii::log('Mail sent end:' . date('Y-m-d H:i:s') .  "\r\n",'info','mail.cmd');
		echo PHP_EOL;
	}
	/**
	 * Provides the command description.
	 * @return string the command description.
	 */
	public function getHelp()
	{
		return parent::getHelp().' [command-name]';
	}
}