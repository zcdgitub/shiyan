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
class VerifyMidNewCommand extends CConsoleCommand
{
	public $cnt_members=1;
	public $cnt_skip=0;
	public $cnt_verify=0;
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
		Yii::log( "开始时间 $sdate\r\n",'info','import.cmd');
		$member_cnt=null;
		if(isset($args[0]))
			$member_cnt=intval($args[0]);
		$this->traversal_mid(1,$member_cnt);
		echo "traversal $this->cnt_members members,verify $this->cnt_verify members,$this->cnt_skip always verify.\r\n";
		echo "start date:$sdate finish date:" . date('Y-m-d H:i:s');
		Yii::log("开始时间:$sdate 结束时间:" . date('Y-m-d H:i:s'),'info','import.cmd');
		return 1;
	}
	public function traversal_mid($id,$member_cnt)
	{
		//中序遍历
		$sql_child="select membermap_id,membermap_is_verify from epmms_membermap where membermap_parent_id=:id order by membermap_order asc";
		$cmd_child=webapp()->db->createCommand($sql_child);
		$member_layer=$cmd_child->queryAll(true,[':id'=>$id]);
		foreach($member_layer as $member)
		{
			$this->cnt_members++;
			if($member['membermap_is_verify']==1)
			{
				echo "verify ${member['membermap_id']} ...\r\n";
				$model = Memberinfo::model()->findByPk($member['membermap_id']);
				if (($status = $model->verify(false,10)) === EError::SUCCESS)
				{
					echo "verify {$model->memberinfo_account} success.\r\n";
					$this->cnt_verify++;
				}
				else
				{
					Yii::log("{$model->memberinfo_account} 审核失败\r\n", 'error', 'import.verify');
					echo "verify {$model->memberinfo_account} failed.\r\n";
					return;
				}
			}
			else
			{
				//echo "id ${member['membermap_id']} always verify\r\n";
				$this->cnt_skip++;
			}
			if(!is_null($member_cnt) && $this->cnt_members>=$member_cnt)
				break;
			$this->traversal_mid($member['membermap_id'],$member_cnt);
		}
	}

	/**
	 * Provides the command description.
	 * @return string the command description.
	 */
	public function getHelp()
	{
		return parent::getHelp().' [command-name]';
	}
	/*
	*function：检测字符串是否由纯英文，纯中文，中英文混合组成
	*param string
	*return 1:纯英文;2:纯中文;3:中英文混合
	*/
}