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
class ImportShopCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
		ini_set("max_execution_time", 0);
		set_time_limit(0);
		echo "begin start.....\r\n";
		$sdate=date('Y-m-d H:i:s');
		echo $sdate;
		echo "\r\n";
		Yii::log( "导入数据开始......\r\n",'info','import.cmd');
		Yii::log( "开始时间$sdate\r\n",'info','import.cmd');
		$member_cnt=null;
		$limit='';
		if(isset($args[0]))
		{
			switch($args[0])
			{
				case 'member':
					$this->importMember($limit);
					break;

				default:
					$this->importMember($limit);
					break;
			}
		}
		else
		{
			$this->importMember($limit);
		}
		echo "start date:$sdate finish date:" . date('Y-m-d H:i:s');
		Yii::log("开始时间:$sdate 结束时间:" . date('Y-m-d H:i:s'),'info','import.cmd');
		return 1;
	}
	public function importMember($limit)
	{
		$sql_members="select * from epmms_memberinfo";

		$cmd_members=webapp()->db->createCommand($sql_members);
		$res_members=$cmd_members->query();
		$cnt=0;
		$cnt_exist=0;
		foreach($res_members as $member)
		{
			if(Customer::model()->exists('email=:account',[':account'=>$member['memberinfo_account']]))
			{
				$cnt_exist++;
				continue;
			}
			else
            {
                $model=new Customer('create');
            }
			$transaction=webapp()->db->beginTransaction();

            $model->id=$member['memberinfo_id'];
            $model->email=$member['memberinfo_account'];
            $model->password_hash=$member['memberinfo_password'];
            $model->firstname=$member['memberinfo_name'];
            $model->lastname=$member['memberinfo_nickname'];
            $model->save();

			$transaction->commit();
			$cnt++;
		}
		Yii::log( "导入会员记录完成,共导入${cnt}个会员.\r\n",'info','import.member');
		echo "import {$cnt} members success,$cnt_exist member exist\r\n";
		$res_members->close();

	}

}