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
class Import720Command extends CConsoleCommand
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
					if(isset($args[1]))
					{
						$member_cnt=intval($args[1]);
						$limit="top $member_cnt";
					}
					$this->importMember($limit);
					break;
				case 'award':
					$this->importAward();
					break;
				case 'finance' :
					$this->importFinance();
					break;
				case 'ins_award':
					$this->insertAward();
					break;
				case 'delete':
					$this->delete();
					break;
				case 'count' :
					$this->correct_count();
					break;
				case 'agent':
					$this->importAgent();
					break;
				case 'withdrawals' :
					$this->importWithdrawals();
					break;
				case 'verify':
					if(isset($args[1]))
						$member_cnt=intval($args[1]);
					$this->verify($member_cnt);
					break;
				default:
					$member_cnt=intval($args[0]);
					$limit="top $member_cnt";
					$this->importMember($limit);
					$this->importAward();
					$this->importFinance();
					break;
			}
		}
		else
		{
			$this->importMember($limit);
			$this->importAward();
			$this->importFinance();
		}
		echo "start date:$sdate finish date:" . date('Y-m-d H:i:s');
		Yii::log("开始时间:$sdate 结束时间:" . date('Y-m-d H:i:s'),'info','import.cmd');
		return 1;
	}
	public function importMember($limit)
	{
		$sql_members="select $limit h.id as h_id,rtrim(y.HyNumber) as y_HyNumber,h.HyPassword1 as h_HyPassword,h.HyPassword2 as h_HyPassword2,h.HyName as h_HyName,h.HyMail as h_HyMail,
		h.HyMobile as h_HyMobile,h.HyTel as h_HyTel,h.HySex as h_HySex,h.HyAddress as h_HyAddress,h.HyOpenBank as h_HyOpenBank,
		h.HyOpenBankNo as h_HyOpenBankNo,h.HyOpenBankName as h_HyOpenBankName,y.IsApproved as y_IsApproved,y.AddTime as y_AddTime,h.HyCardNo as h_HyCardNo,
		y.HyParentNumber as h_HyParentNumber,h.HyLocation as h_order,y.HyTjNumber as h_HyTjNumber,w.HyNumber as w_HyNumber,y.ApprovedTime as y_ApprovedTime,w.WuLiuLevel as w_WuLiuLevel,
		y.IsWuliu as y_IsWuliu
		 from (HY as h inner join HyClub as y on y.HyID=h.ID) left join  WuliuInfo as w on y.WuliuNumber=w.WuliuNumber where h.ID<>126 order by h.ID asc";

		$sql_agents="select HyNumber,WuliuLevel,WuliuArea1 as WuliuProvince,WuliuArea2 as WuliuArea,AddTime,IsApproved,ApprovedTime from WuliuInfo order by id asc";
		$cmd_members=webapp()->dbaccess->createCommand($sql_members);
		$res_members=$cmd_members->query();
		$cmd_agents=webapp()->dbaccess->createCommand($sql_agents);
		$cnt=0;
		$cnt_exist=0;
		$sex=['男'=>0,'女'=>1,'保密'=>2];
		$bank=['中国农业银行'=>1,'中国工商银行'=>2,'中国建设银行'=>3,'中国邮政银行'=>4,'支付宝帐户'=>6,'财付通账户'=>7,
			'财付通帐户'=>7,'支付宝'=>6,'支付宝账户'=>6,'财付通'=>7,'工商银行'=>2,'建设银行'=>3,'建行'=>3,'农业银行'=>1,
			'农行'=>1,'邮政'=>4,'邮政银行'=>4,'支付宝账号'=>6,'支付宝帐号'=>6,'中国建行银行'=>3,'中国农行银行'=>1,
			'中国农业银行卡'=>1,'中国邮政'=>4,'中国邮政储蓄'=>4,'中国邮政储蓄银行'=>4,'中国邮政银行'=>4];
		//$agent_type=['省级'=>1,'市级'=>2,'县级'=>3];
		$sql_info="insert into epmms_memberinfo(memberinfo_account,memberinfo_password,memberinfo_password2,memberinfo_name,
			memberinfo_nickname,memberinfo_email,memberinfo_mobi,memberinfo_qq,memberinfo_sex,
			memberinfo_address_provience,memberinfo_address_area,memberinfo_address_county,memberinfo_address_detail,memberinfo_bank_id,memberinfo_bank_name,
			memberinfo_bank_account,memberinfo_bank_provience,memberinfo_bank_area,memberinfo_bank_branch,memberinfo_is_agent,
			memberinfo_is_verify,memberinfo_idcard_type,memberinfo_idcard,memberinfo_add_date)values(:account,:password,:password2,:name,:nickname,:email,:mobi,:phone,:sex,
			:address_province,:address_area,:address_county,:address_detail,:bank_id,:bank_name,:bank_account,:bank_provience,:bank_area,:bank_branch,:is_agent,:is_verify,
			:cardtype,:cardno,:add_date)";
		$sql_map="insert into epmms_membermap(membermap_id,membermap_parent_id,membermap_order,membermap_recommend_id,
			membermap_agent_id,membermap_membertype_level,membermap_product_count,membermap_is_verify,membermap_verify_date,membermap_verify_member_id,
			membermap_add_date,membermap_is_empty,membermap_money,membermap_agent_type,membermap_reg_member_id)values(
			:id,:parent_id,:order,:recommend_id,:agent_id,:membertype_level,:membermap_product_count,:is_verify,:verify_date,:verify_member_id,
			:add_date,:is_empty,:money,:agent_type,:reg_member_id
			)";

		$cmd_info=webapp()->db->createCommand($sql_info);
		$cmd_map=webapp()->db->createCommand($sql_map);
		//$cmd_agent=webapp()->db->createCommand($sql_agent);
		foreach($res_members as $member)
		{
			if($this->check_str($member['y_HyNumber'])>1)
				$member['y_HyNumber']=iconv('GB18030', 'UTF-8', $member['y_HyNumber']);
			if(Memberinfo::model()->exists('memberinfo_account=:account',[':account'=>$member['y_HyNumber']]))
			{
				$cnt_exist++;
				continue;
			}
			$transaction=webapp()->db->beginTransaction();
			$member['h_HyName']=@iconv('GBK', 'UTF-8', $member['h_HyName']);
			$member['h_HySex']=@iconv('GBK', 'UTF-8', $member['h_HySex']);
			if($member['h_HySex']!='男' && $member['h_HySex']!='女')
				$member['h_HySex']='保密';
			$member['h_HyAddress']=@iconv('GBK', 'UTF-8', $member['h_HyAddress']);
			$member['h_HyOpenBank']=@iconv('GBK', 'UTF-8', $member['h_HyOpenBank']);
			$mBank=null;
			if(empty($member['h_HyOpenBank']))
				$mBank=null;
			elseif(array_key_exists($member['h_HyOpenBank'],$bank))
				$mBank=$bank[$member['h_HyOpenBank']];
			$member['h_HyOpenBankName']=@iconv('GBK', 'UTF-8', $member['h_HyOpenBankName']);
			//$member['w_WuLiuLevel']=iconv('GBK', 'UTF-8', $member['w_WuLiuLevel']);
			if(!empty($member['h_HyCardNo']) && $this->check_str($member['h_HyCardNo'])>1)
			{
				Yii::log( "导入会员:会员 $member[y_HyNumber] 证件号码无效,已清空\r\n",'warning','import.member');
				$member['h_HyCardNo'] = null;
			}

			if($this->check_str($member['h_HyParentNumber'])>1)
				$member['h_HyParentNumber']=@iconv('GBK', 'UTF-8', $member['h_HyParentNumber']);
			if($this->check_str($member['h_HyTjNumber'])>1)
				$member['h_HyTjNumber']=@iconv('GBK', 'UTF-8', $member['h_HyTjNumber']);
			if(empty($member['w_HyNumber']))
			{
				$member['w_HyNumber']='100001';
			}
			else
			{
				if ($this->check_str($member['w_HyNumber']) > 1)
					$member['w_HyNumber'] = @iconv('GBK', 'UTF-8', $member['w_HyNumber']);
			}

			if(!empty($member['h_HyTel']) && $this->check_str($member['h_HyTel'])>1)
			{
				Yii::log( "导入会员:会员 $member[y_HyNumber] 电话号码无效,已清空\r\n",'warning','import.member');
				$member['h_HyTel'] = null;
			}
			if(!empty($member['h_HyMobile']) && $this->check_str($member['h_HyMobile'])>1)
			{
				Yii::log( "导入会员:会员 $member[y_HyNumber] 手机号码无效,已清空\r\n",'warning','import.member');
				$member['h_HyMobile'] = null;
			}
			if(!empty($member['h_HyMail']) && $this->check_str($member['h_HyMail'])>1)
			{
				Yii::log( "导入会员:会员 $member[y_HyNumber] EMail地址无效,已清空\r\n",'warning','import.cmd');
				$member['h_HyMail'] = null;
			}
			if(!empty($member['h_order']))
			{
				$member['h_order']=@iconv('GBK', 'UTF-8', $member['h_order']);
				if($member['h_order']!='左边' && $member['h_order']!='右边')
				{
					Yii::log("导入会员:会员 $member[y_HyNumber] 网络图位置无效,已设为右边\r\n", 'warning', 'import.member');
					$member['h_order'] = '右边';
				}
			}
			if(!empty($member['h_HyOpenBankNo']) && $this->check_str($member['h_HyOpenBankNo'])>1)
			{
				Yii::log( "导入会员:会员 $member[y_HyNumber] 银行账号无效，已清空\r\n",'warning','import.member');
				$member['h_HyOpenBankNo'] = null;
			}
			//无效密码处理
			if(empty($member['h_HyPassword']) || empty($member['h_HyPassword2']))
			{
				$member['h_HyPassword']='111111';
				$member['h_HyPassword2']='222222';
				Yii::log( "导入会员:会员 $member[y_HyNumber] 密码无效，已重置\r\n",'warning','import.member');
			}elseif(!mb_check_encoding($member['h_HyPassword'],'GBK') || !mb_check_encoding($member['h_HyPassword2'],'GBK') )
			{
				$member['h_HyPassword']='111111';
				$member['h_HyPassword2']='222222';
				Yii::log( "导入会员:会员 $member[y_HyNumber] 密码无效，已重置\r\n",'warning','import.member');
			}elseif($this->check_str($member['h_HyPassword'])>1 || $this->check_str($member['h_HyPassword2'])>1)
			{
				$member['h_HyPassword']=iconv('GBK', 'UTF-8', $member['h_HyPassword']);
				$member['h_HyPassword2']=iconv('GBK', 'UTF-8', $member['h_HyPassword2']);
			}

			echo $member['y_HyNumber'] . " id:$member[h_id],parent:$member[h_HyParentNumber],recommend:$member[h_HyTjNumber]" . "\r\n";
			//Yii::log( '导入:' . $member['y_HyNumber'] . " parent:$member[h_HyParentNumber],recommend:$member[h_HyTjNumber],agent:$member[w_HyNumber])" . "\r\n",'info','import.member');
			$cmd_info->execute([':account'=>$member['y_HyNumber'],':password'=>webapp()->format->password($member['h_HyPassword']),
				':password2'=>webapp()->format->password($member['h_HyPassword2']),
				':name'=>$member['h_HyName'],':nickname'=>$member['h_HyName'],':email'=>$member['h_HyMail'],':mobi'=>$member['h_HyMobile'],
				':phone'=>$member['h_HyTel'],':sex'=>$sex[$member['h_HySex']],
				':address_province'=>null,':address_area'=>null,':address_county'=>null,':address_detail'=>$member['h_HyAddress'],
				':bank_id'=>$mBank,':bank_name'=>$member['h_HyOpenBankName'],
				':bank_account'=>$member['h_HyOpenBankNo'],':bank_provience'=>null,':bank_area'=>null,':bank_branch'=>null,
				':is_agent'=>$member['y_IsWuliu'],':is_verify'=>$member['y_IsApproved'],
				':cardtype'=>1,':cardno'=>$member['h_HyCardNo'],':add_date'=>$member['y_AddTime']]);
			$lastid=(string)new DbEvaluate("currval('epmms_memberinfo_memberinfo_id_seq')");
			$cmd_map->execute([':id'=>$lastid,':parent_id'=>Memberinfo::name2id($member['h_HyParentNumber']),':order'=>$member['h_order']=='左边'?1:2,
				':recommend_id'=>Memberinfo::name2id($member['h_HyTjNumber']),
				':agent_id'=>Memberinfo::name2id($member['w_HyNumber']),':membertype_level'=>1,':membermap_product_count'=>1,':is_verify'=>$member['y_IsApproved'],
				':verify_date'=>empty2null($member['y_ApprovedTime']),':verify_member_id'=>Memberinfo::name2id($member['w_HyNumber']),
				':add_date'=>empty2null($member['y_AddTime']),':is_empty'=>0,':money'=>360,':agent_type'=>2,
				'reg_member_id'=>Memberinfo::name2id($member['w_HyNumber'])]);
			$transaction->commit();
			$cnt++;
		}
		Yii::log( "导入会员记录完成,共导入${cnt}个会员.\r\n",'info','import.member');
		echo "import {$cnt} members success,$cnt_exist member exist\r\n";
		$res_members->close();
		$res_members=$cmd_members->query();
		$cnt_corr_parent=0;
		$cnt_corr_recommend=0;
		$cnt_corr_agent=0;
		$cnt_corr_parent_skip=0;
		$cnt_corr_recommend_skip=0;
		$cnt_corr_agent_skip=0;
		Yii::log( "开始校正网络图关系......\r\n",'info','import.corr');
		echo "begin correction map relation.\r\n";
		foreach($res_members as $member)
		{
			if($this->check_str($member['y_HyNumber'])>1)
				$member['y_HyNumber']=@iconv('GBK', 'UTF-8', $member['y_HyNumber']);
			if(!Memberinfo::model()->exists('memberinfo_account=:account',[':account'=>$member['y_HyNumber']]))
				continue;
			$transaction=webapp()->db->beginTransaction();
			$mid=Memberinfo::name2id($member['y_HyNumber'],false);
			$map=Membermap::model()->findByAttributes(['membermap_id'=>$mid]);
			if(!is_object($map))
			{
				echo "member " . $member['y_HyNumber'] ." not found,id:$mid\r\n";
				Yii::log( "校正网络图关系:会员$member[y_HyNumber]找不到记录\r\n",'error','import.corr');
				continue;
			}
			if(is_null($map->membermap_parent_id))
			{
				$cnt_corr_parent++;
				if($this->check_str($member['h_HyParentNumber'])>1)
					$member['h_HyParentNumber']=@iconv('GBK', 'UTF-8', $member['h_HyParentNumber']);
				$map->membermap_parent_id=Memberinfo::name2id($member['h_HyParentNumber'],false);
				if(is_null($map->membermap_parent_id))
				{
					$cnt_corr_parent_skip++;
					echo "member $member[y_HyNumber] parent not found\r\n";
					Yii::log("会员$member[y_HyNumber] 接点人找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					$map->saveAttributes(['membermap_parent_id']);
					echo "write member $member[y_HyNumber] recommend:$member[h_HyParentNumber]\r\n";
					Yii::log("写入会员$member[y_HyNumber]接点人:$member[h_HyParentNumber]\r\n", 'info', 'import.corr');
				}
			}
			if(is_null($map->membermap_recommend_id))
			{
				$cnt_corr_recommend++;
				if($this->check_str($member['h_HyTjNumber'])>1)
					$member['h_HyTjNumber']=@iconv('GBK', 'UTF-8', $member['h_HyTjNumber']);
				$map->membermap_recommend_id=Memberinfo::name2id($member['h_HyTjNumber'],false);
				if(is_null($map->membermap_recommend_id))
				{
					$cnt_corr_recommend_skip++;
					echo "member $member[y_HyNumber] recommend not found\r\n";
					Yii::log("会员$member[y_HyNumber] 推荐人找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					$map->saveAttributes(['membermap_recommend_id']);
					echo "write member $member[y_HyNumber] recommend:$member[h_HyTjNumber]\r\n";
					Yii::log("写入会员$member[y_HyNumber]推荐人:$member[h_HyTjNumber]\r\n", 'info', 'import.corr');
				}
			}
			if(is_null($map->membermap_agent_id))
			{
				$cnt_corr_agent++;
				if($this->check_str($member['w_HyNumber'])>1)
					$member['w_HyNumber']=@iconv('GBK', 'UTF-8', $member['w_HyNumber']);
				$map->membermap_agent_id=Memberinfo::name2id($member['w_HyNumber'],false);
				if(is_null($map->membermap_agent_id))
				{
					$cnt_corr_agent_skip++;
					echo "member $member[y_HyNumber] agent not found\r\n";
					Yii::log("会员$member[y_HyNumber] 报单中心找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					echo "write member $member[y_HyNumber] agent:$member[w_HyNumber]\r\n";
					$map->membermap_reg_member_id=$map->membermap_agent_id;
					$map->saveAttributes(['membermap_agent_id','membermap_reg_member_id']);
					Yii::log("写入会员$member[y_HyNumber]报单中心:$member[w_HyNumber]\r\n", 'info', 'import.corr');
				}
			}
			$transaction->commit();
		}
		$res_members->close();
		Yii::log( "${cnt_corr_parent} 个接点人字段是空,${cnt_corr_parent_skip} 个无法校正......\r\n",'info','import.corr');
		Yii::log( "${cnt_corr_recommend} 个推荐人字段是空,${cnt_corr_recommend_skip} 个无法校正......\r\n",'info','import.corr');
		Yii::log( "${cnt_corr_agent} 个代理中心字段是空,${cnt_corr_agent_skip} 个无法校正......\r\n",'info','import.corr');
		echo "correction map relation finish.\r\n";
		echo "${cnt_corr_parent} parent fields is null,${cnt_corr_parent_skip} fields not correction......\r\n";
		echo "${cnt_corr_recommend} recommend fields is null,${cnt_corr_recommend_skip} fields not correction......\r\n";
		echo "${cnt_corr_agent} agent fields is null,${cnt_corr_agent_skip} fields not correction......\r\n";
		Yii::log( "开始导入代理中心......\r\n",'info','import.agent');
		echo "开始导入代理中心......\r\n";
		//导入代理中心
		$sql_agent="insert into epmms_agent(agent_memberinfo_id,agent_type,agent_province,agent_area,agent_county,agent_memo,
			agent_add_date,agent_verify_date,agent_is_verify)values(:member_id,:type,:province,:area,:county,:memo,
			:add_date,:verify_date,:is_verify)";
		$cmd_agent=webapp()->db->createCommand($sql_agent);
		$res_agents=$cmd_agents->query();
		$cnt_agent=0;
		$cnt_agent_skip=0;
		$cnt_agent_exist=0;
		foreach($res_agents as $agent)
		{
			if($this->check_str($agent['HyNumber'])>1)
				$agent['HyNumber']=@iconv('GBK', 'UTF-8', $agent['HyNumber']);
			$aid=Memberinfo::name2id($agent['HyNumber']);
			if(is_null($aid))
			{
				Yii::log( "会员$agent[HyNumber]不存在，跳过导入\r\n",'warning','import.agent');
				echo "member $agent[HyNumber] not exist,skip import.\r\n";
				$cnt_agent_skip++;
				continue;
			}
			if(Agent::model()->exists('agent_memberinfo_id=:id',[':id'=> $aid]))
			{
				$cnt_agent_exist++;
				continue;
			}
			$agent['WuliuProvince']=@iconv('GBK', 'UTF-8', $agent['WuliuProvince']);
			$agent['WuliuArea']=@iconv('GBK', 'UTF-8', $agent['WuliuArea']);
			$cmd_agent->execute([':member_id'=>$aid,':type'=>2,':province'=>$agent['WuliuProvince'],
				':area'=>$agent['WuliuArea'],':county'=>null,':memo'=>null,':add_date'=>$agent['AddTime'],':verify_date'=>$agent['ApprovedTime'],':is_verify'=>$agent['IsApproved']]);
			echo "import agent $agent[HyNumber]\r\n";
			Yii::log( "导入代理中心$agent[HyNumber]\r\n",'info','import.agent');
			$cnt_agent++;
		}
		$res_agents->close();
		echo "import {$cnt_agent} agents success\r\n";
		echo "skip {$cnt_agent_skip} agents\r\n";
		echo "{$cnt_agent_exist} agents alwasy exist.\r\n";
		Yii::log( "导入${cnt_agent}个代理中心\r\n",'info','import.agent');
		Yii::log( "${cnt_agent_skip}个代理中心无法导入\r\n",'info','import.agent');
		Yii::log( "${cnt_agent_exist}个代理中心已存在\r\n",'info','import.agent');
		echo "**************** informations summary ********************\r\n";
		echo "import {$cnt} members success,${cnt_exist} members exist.\r\n";
		echo "${cnt_corr_parent} parent fields is null,${cnt_corr_parent_skip} fields not correction......\r\n";
		echo "${cnt_corr_recommend} recommend fields is null,${cnt_corr_recommend_skip} fields not correction......\r\n";
		echo "${cnt_corr_agent} agent fields is null,${cnt_corr_agent_skip} fields not correction......\r\n";
		echo "import {$cnt_agent} agents success\r\n";
	}
	public function importAgent()
	{
		$sql_agents="select HyNumber,WuliuNumber,WuliuName,WuliuLevel,WuliuArea1 as WuliuProvince,WuliuArea2 as WuliuArea,AddTime,IsApproved,ApprovedTime from WuliuInfo order by id asc";
		$cmd_agents=webapp()->dbaccess->createCommand($sql_agents);
		Yii::log( "开始导入代理中心......\r\n",'info','import.agent');
		echo "开始导入代理中心......\r\n";
		//导入代理中心
		$sql_agent="insert into epmms_agent(agent_memberinfo_id,agent_account,agent_type,agent_province,agent_area,agent_county,agent_memo,
			agent_add_date,agent_verify_date,agent_is_verify)values(:member_id,:account,:type,:province,:area,:county,:memo,
			:add_date,:verify_date,:is_verify)";
		$cmd_agent=webapp()->db->createCommand($sql_agent);
		$res_agents=$cmd_agents->query();
		$cnt_agent=0;
		$cnt_agent_skip=0;
		$cnt_agent_exist=0;
		foreach($res_agents as $agent)
		{
			if($this->check_str($agent['HyNumber'])>1)
				$agent['HyNumber']=@iconv('GBK', 'UTF-8', $agent['HyNumber']);
			if($this->check_str($agent['WuliuNumber'])>1)
				$agent['WuliuNumber']=@iconv('GBK', 'UTF-8', $agent['WuliuNumber']);
			if($this->check_str($agent['WuliuName'])>1)
				$agent['WuliuName']=@iconv('GBK', 'UTF-8', $agent['WuliuName']);
			$aid=Memberinfo::name2id($agent['HyNumber']);
			if(is_null($aid))
			{
				Yii::log( "会员$agent[HyNumber]不存在，跳过导入\r\n",'warning','import.agent');
				echo "member $agent[HyNumber] not exist,skip import.\r\n";
				$cnt_agent_skip++;
				continue;
			}
			if(Agent::model()->exists('agent_memberinfo_id=:id',[':id'=> $aid]))
			{
				$cnt_agent_exist++;
				continue;
			}
			$agent['WuliuProvince']=@iconv('GBK', 'UTF-8', $agent['WuliuProvince']);
			$agent['WuliuArea']=@iconv('GBK', 'UTF-8', $agent['WuliuArea']);
			$cmd_agent->execute([':member_id'=>$aid,':account'=>$agent['WuliuNumber'],':type'=>2,':province'=>$agent['WuliuProvince'],
				':area'=>$agent['WuliuArea'],':county'=>null,':memo'=>$agent['WuliuName'],':add_date'=>$agent['AddTime'],':verify_date'=>$agent['ApprovedTime'],':is_verify'=>$agent['IsApproved']]);
			echo "import agent $agent[HyNumber]\r\n";
			Yii::log( "导入代理中心$agent[HyNumber]\r\n",'info','import.agent');
			$cnt_agent++;
		}
		$res_agents->close();
		echo "import {$cnt_agent} agents success\r\n";
		echo "skip {$cnt_agent_skip} agents\r\n";
		echo "{$cnt_agent_exist} agents alwasy exist.\r\n";
		Yii::log( "导入${cnt_agent}个代理中心\r\n",'info','import.agent');
		Yii::log( "${cnt_agent_skip}个代理中心无法导入\r\n",'info','import.agent');
		Yii::log( "${cnt_agent_exist}个代理中心已存在\r\n",'info','import.agent');
	}
	public function importWithdrawals()
	{
		$sql_agents="select HyNumber,PickupCount,PickupDate,PickupStatus,ApprovedDate,PayPalAccount from HyPickupMoneyLog order by id asc";
		$cmd_agents=webapp()->dbaccess->createCommand($sql_agents);
		Yii::log( "开始导入提现记录......\r\n",'info','import.withdrawals');
		echo "开始导入提现记录......\r\n";
		//导入代理中心
		$sql_ins="INSERT INTO
					  public.epmms_withdrawals
					(
					  withdrawals_member_id,
					  withdrawals_currency,
					  withdrawals_add_date,
					  withdrawals_is_verify,
					  withdrawals_verify_date,
					  withdrawals_remark,
					  withdrawals_finance_type_id,
					  withdrawals_tax,
					  withdrawals_real_currency
					)
					VALUES (
					  :withdrawals_member_id,
					  :withdrawals_currency,
					  :withdrawals_add_date,
					  :withdrawals_is_verify,
					  :withdrawals_verify_date,
					  :withdrawals_remark,
					  :withdrawals_finance_type_id,
					  :withdrawals_tax,
					  :withdrawals_real_currency
					);";
		$cmd_ins=webapp()->db->createCommand($sql_ins);
		$res_agents=$cmd_agents->query();
		$cnt_agent=0;
		$cnt_agent_skip=0;
		$cnt_agent_exist=0;
		foreach($res_agents as $agent)
		{
			if($this->check_str($agent['HyNumber'])>1)
				$agent['HyNumber']=@iconv('GBK', 'UTF-8', $agent['HyNumber']);
			if($this->check_str($agent['PayPalAccount'])>1)
				$agent['PayPalAccount']=@iconv('GBK', 'UTF-8', $agent['PayPalAccount']);
			$aid=Memberinfo::name2id($agent['HyNumber']);
			if(is_null($aid))
			{
				Yii::log( "会员$agent[HyNumber]不存在，跳过导入\r\n",'warning','import.agent');
				echo "member $agent[HyNumber] not exist,skip import.\r\n";
				$cnt_agent_skip++;
				continue;
			}
			$cmd_ins->execute([':withdrawals_member_id'=>$aid,':withdrawals_currency'=>$agent['PickupCount'],':withdrawals_add_date'=>$agent['PickupDate'],
			':withdrawals_is_verify'=>$agent['PickupStatus'],':withdrawals_verify_date'=>$agent['ApprovedDate'],':withdrawals_remark'=>$agent['PayPalAccount'],':withdrawals_finance_type_id'=>1,
			':withdrawals_tax'=>0,':withdrawals_real_currency'=>$agent['PickupCount']]);
			echo "import withdrawals $agent[HyNumber]\r\n";
			Yii::log( "导入提现记录 $agent[HyNumber]\r\n",'info','import.withdrawals');
			$cnt_agent++;
		}
		$res_agents->close();
		echo "import {$cnt_agent} withdrawals success\r\n";
		echo "skip {$cnt_agent_skip} withdrawals\r\n";
		echo "{$cnt_agent_exist} withdrawals alwasy exist.\r\n";
		Yii::log( "导入${cnt_agent}提现记录\r\n",'info','import.withdrawals');
		Yii::log( "${cnt_agent_skip}个提现记录无法导入\r\n",'info','import.withdrawals');
		Yii::log( "${cnt_agent_exist}个提现记录已存在\r\n",'info','import.withdrawals');
	}
	public function importPassword($limit)
	{
   		$sql_members="select $limit rtrim(HyNumber) as y_HyNumber,HyPassword1 as h_HyPassword,HyPassword2 as h_HyPassword2 from HyClub";
		$cmd_members=webapp()->dbaccess->createCommand($sql_members);
		$res_members=$cmd_members->query();
		$cnt=0;
		$cnt_exist=0;
		$sql_info="update epmms_memberinfo memberinfo_password=:p1,memberinfo_password2=:p2 where memberinfo_account=:account)";

		$cmd_info=webapp()->db->createCommand($sql_info);
		foreach($res_members as $member)
		{
			if($this->check_str($member['y_HyNumber'])>1)
				$member['y_HyNumber']=iconv('GB18030', 'UTF-8', $member['y_HyNumber']);
			if(Memberinfo::model()->exists('memberinfo_account=:account',[':account'=>$member['y_HyNumber']]))
			{
				$cnt_exist++;
				continue;
			}
			$transaction=webapp()->db->beginTransaction();
			//无效密码处理
			if(empty($member['h_HyPassword']) || empty($member['h_HyPassword2']))
			{
				$member['h_HyPassword']='111111';
				$member['h_HyPassword2']='222222';
				Yii::log( "导入会员:会员 $member[y_HyNumber] 密码无效，已重置\r\n",'warning','import.member');
			}elseif(!mb_check_encoding($member['h_HyPassword'],'GBK') || !mb_check_encoding($member['h_HyPassword2'],'GBK') )
			{
				$member['h_HyPassword']='111111';
				$member['h_HyPassword2']='222222';
				Yii::log( "导入会员:会员 $member[y_HyNumber] 密码无效，已重置\r\n",'warning','import.member');
			}elseif($this->check_str($member['h_HyPassword'])>1 || $this->check_str($member['h_HyPassword2'])>1)
			{
				$member['h_HyPassword']=iconv('GBK', 'UTF-8', $member['h_HyPassword']);
				$member['h_HyPassword2']=iconv('GBK', 'UTF-8', $member['h_HyPassword2']);
			}

			echo $member['y_HyNumber'] . " password:$member[h_HyPassword],password2:$member[h_HyPassword2]" . "\r\n";
			$cmd_info->execute([':account'=>$member['y_HyNumber'],':p1'=>webapp()->format->password($member['h_HyPassword']),
				':p2'=>webapp()->format->password($member['h_HyPassword2']),':account'=>$member['y_HyNumber']]);
			$transaction->commit();
			$cnt++;
		}
		Yii::log( "导入会员记录完成,共导入${cnt}个会员.\r\n",'info','import.member');
		echo "import {$cnt} members success\r\n";
		$res_members->close();
	}
	public function correct_count()
	{
		$sql="select HyNumber,iif(HyManageTotal_Left<HyManageTotal_Right,HyManageTotal_Left,HyManageTotal_Right) as lcount from HyMoneyAccount;";
		$sql2="update epmms_memberstatus set status_pair=:pair where status_id=:id";
		$cmd2=webapp()->db->createCommand($sql2);
		$cmd=webapp()->dbaccess->createCommand($sql);
		$res=$cmd->query();
		$cnt_skip=0;
		$cnt_all=0;
		$cnt_ok=0;
		foreach($res as $member)
		{
			$cnt_all++;
			if($this->check_str($member['HyNumber'])>1)
				$member['HyNumber']=@iconv('GBK', 'UTF-8', $member['HyNumber']);
			$id=Memberinfo::name2id($member['HyNumber'],true);
			if(is_null($id))
			{
				echo "member $member[HyNumber] not exist\r\n";
				$cnt_skip++;
				continue;
			}
			$cmd2->execute([':pair'=>$member['lcount'],':id'=>$id]);
			$cnt_ok++;
		}
		echo "process $cnt_all member,skip $cnt_skip,success $cnt_ok \r\n";
	}

	/**
	 * 导入奖金记录
	 */
	public function importAward()
	{
		echo "开始导入奖金记录......\r\n";
		Yii::log( "开始导入奖金记录......\r\n",'info','import.award');
		$sql_award="select rtrim(HyNumber) as HyNumber1,TeamManageMoney as award_252,TujianMoney as award_250,
		othermoney_2 as award_251,-RepeatMoney as award_253,-RepeatMoney2 as award_254,
		ComputeWeek as award_period,ComputeTime from HyMoneyLog order by id asc";
		$cmd_award=webapp()->dbaccess->createCommand($sql_award);
		$res_award=$cmd_award->query();
		$sql_ins="INSERT INTO
				  epmms_award_period
				(
				  award_period_period,
				  award_period_memberinfo_id,
				  award_period_currency,
				  award_period_type_id,
				  award_period_add_date,
				  award_period_sum_type
				)
				VALUES (
				  :award_period_period,
				  :award_period_memberinfo_id,
				  :award_period_currency,
				  :award_period_type_id,
				  :award_period_add_date,
				  :award_period_sum_type
				);";
		$cmd_ins=webapp()->db->createCommand($sql_ins);
		$cnt_award=0;
		$cnt_award_skip=0;
		$cnt_award_exist=0;
		foreach($res_award as $award)
		{
			if($this->check_str($award['HyNumber1'])>1)
				$award['HyNumber1']=@iconv('GBK', 'UTF-8', $award['HyNumber1']);
			$member_id=Memberinfo::name2id($award['HyNumber1']);
			if(is_null($member_id))
			{
				Yii::log( "会员$award[HyNumber1]不存在，跳过导入\r\n",'error','import.award');
				echo "member $award[HyNumber1] not exist.\r\n";
				$cnt_award_skip++;
				continue;
			}
			$exist_cnt=AwardPeriodSum::model()->count('award_period_sum_memberinfo_id=:id and award_period_sum_period=:period',[':id'=>$member_id,':period'=>$award['award_period']]);
			if($exist_cnt==1)
			{
				$cnt_award_exist++;
				continue;
			}
			elseif($exist_cnt>1)
			{
				echo "member $award[HyNumber1] award record duplication,have $exist_cnt award records.\r\n";
				Yii::log( "会员 $award[HyNumber1] 奖金记录重复，有 $exist_cnt 条记录存在\r\n",'error','import.award');
				continue;
			}
			$transaction=webapp()->db->beginTransaction();
			if(!empty($award['award_250']))
			{
				$cmd_ins->execute([':award_period_period' => $award['award_period'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['award_250'],
					':award_period_type_id' => 250, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}

			if(!empty($award['award_251']))
			{
				$cmd_ins->execute([':award_period_period' => $award['award_period'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['award_251'],
					':award_period_type_id' => 251, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['award_252']))
			{
				$cmd_ins->execute([':award_period_period' => $award['award_period'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['award_252'],
					':award_period_type_id' => 252, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['award_253']))
			{
				$cmd_ins->execute([':award_period_period' => $award['award_period'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['award_253'],
					':award_period_type_id' => 253, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['award_254']))
			{
				$cmd_ins->execute([':award_period_period' => $award['award_period'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['award_254'],
					':award_period_type_id' => 254, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			$sum_proc=new DbCall('epmms_award_sum_member',[$member_id,$award['award_period'],$award['ComputeTime']]);
			$sum_proc->run();
			$transaction->commit();
			$cnt_award++;
		}
		$res_award->close();
		$sum_proc=new DbCall('setval',['award_period',$award['award_period']]);
		$sum_proc->run();
		echo "import $cnt_award award record.\r\n";
		echo "skip $cnt_award_skip award record.\r\n";
		echo "$cnt_award_exist award record always exists.\r\n";
		Yii::log( "导入 $cnt_award 条奖金记录.\r\n",'info','import.award');
		Yii::log( "$cnt_award_skip 条奖金记录因为会员不存在，无法导入.\r\n",'info','import.award');
		Yii::log( "$cnt_award_exist 条奖金记录已经存在.\r\n",'info','import.award');
		echo "import award finish......\r\n";
		Yii::log( "导入奖金记录完成......\r\n",'info','import.award');

	}

	/**
	 * 导入奖金记录
	 */
	public function importFinance()
	{
		echo "开始导入会员财务......\r\n";
		Yii::log( "开始导入会员财务......\r\n",'info','import.finance');
		$sql_award="select rtrim(HyNumber) as HyNumber1,HyMoneyAccount as MoneyAccount from HyMoneyAccount order by id asc";
		$sql_reg="select  rtrim(HyNumber) as HyNumber1,MoneyAccount from WuliuInfo order by id asc";
		$cmd_award=webapp()->dbaccess->createCommand($sql_award);
		$cmd_reg=webapp()->dbaccess->createCommand($sql_reg);
		$res_award=$cmd_award->query();
		$res_reg=$cmd_reg->query();
		$sql_ins="INSERT INTO
				  public.epmms_finance
				(
				  finance_award,
				  finance_type,
				  finance_memberinfo_id
				)
				VALUES (
				  :finance_award,
				  :finance_type,
				  :finance_memberinfo_id
				);";
		$sql_update="update epmms_finance set finance_award=:finance_award where finance_type=:finance_type and finance_memberinfo_id=:finance_memberinfo_id and :finance_award<>finance_award";
		$cmd_update=webapp()->db->createCommand($sql_update);
		$cmd_ins=webapp()->db->createCommand($sql_ins);
		$cnt_reg=0;
		$cnt_reg_skip=0;
		foreach($res_reg as $award)
		{
			if($this->check_str($award['HyNumber1'])>1)
				$award['HyNumber1']=@iconv('GBK', 'UTF-8', $award['HyNumber1']);
			$member_id=Memberinfo::name2id($award['HyNumber1']);
			if(is_null($member_id))
			{
				Yii::log( "会员 $award[HyNumber1] 不存在，跳过导入\r\n",'warning','import.finance');
				echo "member $award[HyNumber1] not exist,skip import finance.\r\n";
				$cnt_reg_skip++;
				continue;
			}
			if(is_null($award['MoneyAccount']))
				$award['MoneyAccount']=0;
			if(Finance::model()->exists('finance_memberinfo_id=:id and finance_type=:type',[':id'=>$member_id,':type'=>2]))
			{
				$cmd_update->execute([':finance_award'=> $award['MoneyAccount'], ':finance_type' =>2, ':finance_memberinfo_id' =>$member_id]);
			}
			else
			{
				$cmd_ins->execute([':finance_award'=> $award['MoneyAccount'], ':finance_type' =>2, ':finance_memberinfo_id' =>$member_id]);
			}
			$cnt_reg++;
		}
		$res_reg->close();
		$cnt_award=0;
		$cnt_award_skip=0;
		foreach($res_award as $award)
		{
			if($this->check_str($award['HyNumber1'])>1)
				$award['HyNumber1']=@iconv('GBK', 'UTF-8', $award['HyNumber1']);
			$member_id=Memberinfo::name2id($award['HyNumber1']);
			if(is_null($member_id))
			{
				$cnt_award_skip++;
				Yii::log( "会员$award[HyNumber1]不存在，跳过导入\r\n",'warning','import.finance');
				echo "member $award[HyNumber1] not exist,skip import finance.\r\n";
				continue;
			}
			if(is_null($award['MoneyAccount']))
				$award['MoneyAccount']=0;
			if(Finance::model()->exists('finance_memberinfo_id=:id and finance_type=:type',[':id'=>$member_id,':type'=>1]))
			{
				$cmd_update->execute([':finance_award'=> $award['MoneyAccount'], ':finance_type' =>1, ':finance_memberinfo_id' =>$member_id]);
			}
			else
			{
				$cmd_ins->execute([':finance_award'=> $award['MoneyAccount'], ':finance_type' =>1, ':finance_memberinfo_id' =>$member_id]);
			}
			$cnt_award++;
		}
		echo "import $cnt_reg reg finance record.\r\n";
		echo "skip $cnt_reg_skip reg finance record.\r\n";
		echo "import $cnt_award award finance record.\r\n";
		echo "skip $cnt_award_skip award finance record.\r\n";
		Yii::log("导入 $cnt_reg 电子币财务记录.\r\n",'info','import.finance');
		Yii::log("$cnt_reg_skip 条电子币财务记录无法导入.\r\n",'info','import.finance');
		Yii::log("导入 $cnt_award 条奖金财务记录.\r\n",'info','import.finance');
		Yii::log("$cnt_award_skip 条奖金财务记录无法导入.\r\n",'info','import.finance');
		echo "导入会员财务完成......\r\n";
		Yii::log( "导入会员财务完成......\r\n",'info','import.finance');
	}
	public function insertAward()
	{
		echo "开始导入奖金记录......\r\n";
		Yii::log( "开始导入奖金记录......\r\n",'info','import.award');
		$sql_award="select ID,HyNumber,AllMoney,ComputeWeek as award_period,ComputeTime from HyMoneyLog order by id asc";
		$cmd_award=webapp()->dbaccess->createCommand($sql_award);
		$res_award=$cmd_award->query();
		$sql_ins='INSERT INTO
			  public."HyMoneyLog"
			(
			  id,
			  "HyNumber",
			  "AllMoney",
			  award_period,
			  "ComputeTime",
			  member_id
			)
			VALUES (
				:id,
			  :HyNumber,
			  :AllMoney,
			  :award_period,
			  :ComputeTime,
			  :member_id
			);';
		$cmd_ins=webapp()->db->createCommand($sql_ins);
		$cnt_award=0;
		$cnt_award_skip=0;
		$cnt_award_exist=0;
		//$trans=webapp()->db->beginTransaction();
		foreach($res_award as $award)
		{
			if($this->check_str($award['HyNumber'])>1)
				$award['HyNumber']=@iconv('GBK', 'UTF-8', $award['HyNumber']);
			$member_id=Memberinfo::name2id($award['HyNumber']);
			if(is_null($member_id))
			{
				Yii::log( "会员$award[HyNumber]不存在，跳过导入\r\n",'error','import.award');
				echo "member $award[HyNumber] not exist.\r\n";
				$cnt_award_skip++;
				continue;
			}
			$cmd_ins->execute([':id'=>$award['ID'],':HyNumber'=>$award['HyNumber'],':AllMoney'=>$award['AllMoney'],':award_period'=>$award['award_period'],':ComputeTime'=>$award['ComputeTime'],':member_id'=>$member_id]);
			$cnt_award++;
		}
		//$trans->commit();
		$res_award->close();
		echo "import $cnt_award award record.\r\n";
		echo "skip $cnt_award_skip award record.\r\n";
		echo "$cnt_award_exist award record always exists.\r\n";
		Yii::log( "导入 $cnt_award 条奖金记录.\r\n",'info','import.award');
		Yii::log( "$cnt_award_skip 条奖金记录因为会员不存在，无法导入.\r\n",'info','import.award');
		Yii::log( "$cnt_award_exist 条奖金记录已经存在.\r\n",'info','import.award');
		echo "import award finish......\r\n";
		Yii::log( "导入奖金记录完成......\r\n",'info','import.award');
	}
	public function delete()
	{
		$cmd_members=webapp()->dbaccess->createCommand("select rtrim(HyNumber) as y_HyNumber from HyClub where IsApproved=false");
		$sql_del_map="delete from epmms_membermap where membermap_id=:id";
		$sql_del_info="delete from epmms_memberinfo where memberinfo_id=:id";
		$cmd_del_map=webapp()->db->createCommand($sql_del_map);
		$cmd_del_info=webapp()->db->createCommand($sql_del_info);
		$res_members=$cmd_members->query();
		$cnt=0;
		foreach($res_members as $member)
		{
			if($this->check_str($member['y_HyNumber'])>1)
				$member['y_HyNumber']=iconv('GB18030', 'UTF-8', $member['y_HyNumber']);
			echo "delete $member[y_HyNumber] \r\n";
			$member_id=Memberinfo::name2id($member['y_HyNumber']);
			$t=webapp()->db->beginTransaction();
			$cmd_del_map->execute([':id'=>$member_id]);
			$cmd_del_info->execute([':id'=>$member_id]);
			$t->commit();
			$cnt++;
		}
		echo "delete $cnt members \r\n";
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
	public static function check_str($str='')
	{
		if(trim($str)==''){
			return '';
		}
		$m=mb_strlen($str,'GBK');
		$s=strlen($str);
		if($s==$m){
			return 1;
		}
		if($s%$m==0&&$s%3==0){
			return 2;
		}
		return 3;
	}
}