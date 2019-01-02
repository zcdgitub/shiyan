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
class SendMailCommand extends CConsoleCommand
{
	private $_mail;
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
		$this->mail->setTo('254220959@qq.com');
		$content="新密码\r\n";
		$content.="一级密码:1,二级密码2";
		$this->mail->setBody($content);
		$this->mail->send();
		return 1;
	}

	/**
	 * Provides the command description.
	 * @return string the command description.
	 */
	public function getHelp()
	{
		return parent::getHelp().' [command-name]';
	}
	public function getMail()
	{
		if(is_null($this->_mail))
		{
			Yii::import('ext.YiiMailer.YiiMailer');
			$this->_mail = new YiiMailer();
			$this->_mail->clearLayout();
		}
		return $this->_mail;
	}
}