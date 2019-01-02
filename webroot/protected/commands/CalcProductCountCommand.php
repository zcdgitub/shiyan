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
class CalcProductCountCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{

		$bak=new Backup();
		if(!$bak->autoBackup('计算业绩','结算时间：'.webapp()->format->formatdatetime(time())))
		{
			throw new Error('备份失败');
		}
		$transaction=webapp()->db->beginTransaction();
		try
		{
            if($ret=MapEdit::model()->remapAll()==EError::SUCCESS)
            {
                $ss=SystemStatus::model()->find();
                $ss->system_status_mapedit=0;
                $ss->saveAttributes(['system_status_mapedit']);

                echo "计算业绩成功";
            }
            else
            {
                echo "计算业绩失败";
            }
		}
		catch(Error $e)
		{
			$transaction->rollback();
			throw $e;
			return EError::ERROR;
		}
		catch(CDbException $e)
		{
			$transaction->rollback();
			throw $e;
			return EError::ERROR;
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
			return EError::ERROR;
		}
		$transaction->commit();

		return 0;
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