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
class ImportMsCommand extends CConsoleCommand
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
				case 'awardaixin':
					$this->importAwardAixin();
					break;
				case 'updatestatus':
					$this->updateStatus();
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
		$sql_members="select $limit id,userid,password ,SecondaryPassword as password2,name,sex,mail,BankAccount as openbankno ,OpeningBank as openbank,
		mobile,IDNumber as cardno,address,postcode,regtime as add_date,
		ContactPerson as parent_id,Position as [order],RefereeMobile as recommend_id,FrozenState as is_verify,ActiveTime as verify_date,userlevel as membertype,
		IsBaoDan as is_agent,IsFreeze as is_enable,bandancenter as agent,RegistUser as reg_member,isempty
		 from [user] where id<>1 order by id asc";

		$cmd_members=webapp()->dbms->createCommand($sql_members);
		$res_members=$cmd_members->query();
		$cnt=0;
		$cnt_exist=0;
		$cnt_agent=0;
		$sex=['男'=>0,'女'=>1,'保密'=>2];
		$sql_info="insert into epmms_memberinfo(memberinfo_id,memberinfo_account,memberinfo_password,memberinfo_password2,memberinfo_name,
			memberinfo_nickname,memberinfo_email,memberinfo_mobi,memberinfo_qq,memberinfo_sex,
			memberinfo_address_provience,memberinfo_address_area,memberinfo_address_county,memberinfo_address_detail,memberinfo_bank_id,memberinfo_bank_name,
			memberinfo_bank_account,memberinfo_bank_provience,memberinfo_bank_area,memberinfo_bank_branch,memberinfo_is_agent,
			memberinfo_is_verify,memberinfo_idcard_type,memberinfo_idcard,memberinfo_add_date,memberinfo_is_enable)values(:id,:account,:password,:password2,:name,:nickname,:email,:mobi,:phone,:sex,
			:address_province,:address_area,:address_county,:address_detail,:bank_id,:bank_name,:bank_account,:bank_provience,:bank_area,:bank_branch,:is_agent,:is_verify,
			:cardtype,:cardno,:add_date,:is_enable)";
		$sql_map="insert into epmms_membermap(membermap_id,membermap_parent_id,membermap_order,membermap_recommend_id,
			membermap_agent_id,membermap_membertype_level,membermap_product_count,membermap_is_verify,membermap_verify_date,membermap_verify_member_id,
			membermap_add_date,membermap_is_empty,membermap_money,membermap_agent_type,membermap_reg_member_id,membermap_is_agent)values(
			:id,:parent_id,:order,:recommend_id,:agent_id,:membertype_level,:membermap_product_count,:is_verify,:verify_date,:verify_member_id,
			:add_date,:is_empty,:money,:agent_type,:reg_member_id,:is_agent
			)";
		$sql_agent="insert into epmms_agent(agent_memberinfo_id,agent_type,agent_province,agent_area,agent_county,agent_memo,
			agent_add_date,agent_verify_date,agent_is_verify)values(:member_id,:type,:province,:area,:county,:memo,
			:add_date,:verify_date,:is_verify)";
		$cmd_agent=webapp()->db->createCommand($sql_agent);
		$cmd_info=webapp()->db->createCommand($sql_info);
		$cmd_map=webapp()->db->createCommand($sql_map);
		foreach($res_members as $member)
		{
			if(Memberinfo::model()->exists('memberinfo_account=:account',[':account'=>$member['userid']]))
			{
				$cnt_exist++;
				continue;
			}
			$transaction=webapp()->db->beginTransaction();
			if($member['sex']!='男' && $member['sex']!='女')
				$member['sex']='保密';
			if(empty($member['openbankno']))
			{
				$member['openbankno'] = null;
			}
			if(!empty($member['agent']))
			{
				$member['agent']=Memberinfo::name2id($member['agent'],false);
			}
			if(!empty($member['reg_member']))
			{
				if($member['reg_member']=='系统')
					$member['reg_member']=null;
				else
					$member['reg_member']=Memberinfo::name2id($member['reg_member'],false);
			}
			if(empty($member['name']))
			{
				$member['name']=$member['userid'];
			}
			$mtype=[1=>['bill'=>1,'money'=>1300],2=>['bill'=>3,'money'=>3900],3=>['bill'=>3,'money'=>13000],4=>['bill'=>20,'money'=>26000]];
			echo $member['userid'] . " id:$member[id],parent:$member[parent_id],recommend:$member[recommend_id],type:$member[membertype]" . "\r\n";
			//Yii::log( '导入:' . $member['y_HyNumber'] . " parent:$member[h_HyParentNumber],recommend:$member[h_HyTjNumber],agent:$member[w_HyNumber])" . "\r\n",'info','import.member');
			$cmd_info->execute([':id'=>$member['id'],':account'=>$member['userid'],':password'=>$member['password'],
				':password2'=>$member['password2'],
				':name'=>$member['name'],':nickname'=>$member['name'],':email'=>$member['mail'],':mobi'=>$member['mobile'],
				':phone'=>null,':sex'=>$sex[$member['sex']],
				':address_province'=>null,':address_area'=>null,':address_county'=>null,':address_detail'=>$member['address'],
				':bank_id'=>$member['openbank'],':bank_name'=>$member['name'],
				':bank_account'=>$member['openbankno'],':bank_provience'=>null,':bank_area'=>null,':bank_branch'=>null,
				':is_agent'=>$member['is_agent'],':is_verify'=>$member['is_verify'],
				':cardtype'=>1,':cardno'=>$member['cardno'],':add_date'=>$member['add_date'],':is_enable'=>$member['is_enable']]);
				$cmd_map->execute([':id'=>$member['id'],':parent_id'=>Memberinfo::name2id($member['parent_id']),':order'=>$member['order'],
				':recommend_id'=>Memberinfo::name2id($member['recommend_id']),
				':agent_id'=>$member['agent'],
						':membertype_level'=>$member['membertype'],
						':membermap_product_count'=>$mtype[$member['membertype']]['bill'],
						':is_verify'=>$member['is_verify'],
				':verify_date'=>empty2null($member['verify_date']),':verify_member_id'=>null,
				':add_date'=>empty2null($member['add_date']),':is_empty'=>$member['isempty'],':money'=>$mtype[$member['membertype']]['money'],':agent_type'=>1,
				'reg_member_id'=>$member['reg_member'],':is_agent'=>$member['is_agent']]);
			if($member['is_agent']==1)
			{
				if(!Agent::model()->exists('agent_memberinfo_id=:id',[':id'=>$member['id']]))
				{
					$cmd_agent->execute([':member_id'=>$member['id'],':type'=>1,':province'=>null,
							':area'=>null,':county'=>null,':memo'=>null,':add_date'=>null,':verify_date'=>null,':is_verify'=>1]);
					echo "import agent $member[userid]\r\n";
					Yii::log( "导入代理中心$member[userid]\r\n",'info','import.agent');
					$cnt_agent++;
				}
			}
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
			if(!Memberinfo::model()->exists('memberinfo_account=:account',[':account'=>$member['userid']]))
				continue;
			$transaction=webapp()->db->beginTransaction();
			$mid=Memberinfo::name2id($member['userid'],false);
			$map=Membermap::model()->findByAttributes(['membermap_id'=>$mid]);
			if(!is_object($map))
			{
				echo "member " . $member['userid'] ." not found,id:$mid\r\n";
				Yii::log( "校正网络图关系:会员$member[y_HyNumber]找不到记录\r\n",'error','import.corr');
				continue;
			}
			if(is_null($map->membermap_parent_id))
			{
				$cnt_corr_parent++;
				$map->membermap_parent_id=Memberinfo::name2id($member['parent_id'],false);
				if(is_null($map->membermap_parent_id))
				{
					$cnt_corr_parent_skip++;
					echo "member $member[userid] parent not found\r\n";
					Yii::log("会员$member[userid] 接点人找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					$map->saveAttributes(['membermap_parent_id']);
					echo "write member $member[userid] parent:$member[parent_id]\r\n";
					Yii::log("写入会员$member[userid]接点人:$member[parent_id]\r\n", 'info', 'import.corr');
				}
			}
			if(is_null($map->membermap_recommend_id))
			{
				$cnt_corr_recommend++;
				$map->membermap_recommend_id=Memberinfo::name2id($member['recommend_id'],false);
				if(is_null($map->membermap_recommend_id))
				{
					$cnt_corr_recommend_skip++;
					echo "member $member[userid] recommend not found\r\n";
					Yii::log("会员$member[userid] 推荐人找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					$map->saveAttributes(['membermap_recommend_id']);
					echo "write member $member[userid] recommend:$member[recommend_id]\r\n";
					Yii::log("写入会员$member[userid]推荐人:$member[recommend_id]\r\n", 'info', 'import.corr');
				}
			}
			if(is_null($map->membermap_agent_id))
			{
				$cnt_corr_agent++;
				$map->membermap_agent_id==Memberinfo::name2id($member['agent'],false);;
				if(is_null($map->membermap_agent_id))
				{
					$cnt_corr_agent_skip++;
					echo "member $member[userid] agent not found\r\n";
					Yii::log("会员$member[userid] 报单中心找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					echo "write member $member[userid] agent:$member[userid]\r\n";
					$map->saveAttributes(['membermap_agent_id']);
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
		echo "import {$cnt_agent} agents success\r\n";

		Yii::log( "导入${cnt_agent}个代理中心\r\n",'info','import.agent');

		echo "**************** informations summary ********************\r\n";
		echo "import {$cnt} members success,${cnt_exist} members exist.\r\n";
		echo "import {$cnt_agent} agents success\r\n";
	}

	public function importWithdrawals()
	{
		$sql_agents="select WithdrawalID as id ,userid,WithdrawalAmount as withdrawals_currency,ServiceCharge as withdrawals_tax,
		FactReceive as withdrawals_real_currency,ProcessState as withdrawals_is_verify,PaymentDate as withdrawals_add_date,ConfirmedDate as withdrawals_verify_date from Withdrawal order by WithdrawalID asc";
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
			$aid=Memberinfo::name2id($agent['userid'],false);
			if(is_null($aid))
			{
				Yii::log( "会员$agent[userid]不存在，跳过导入\r\n",'warning','import.agent');
				echo "member $agent[userid] not exist,skip import.\r\n";
				$cnt_agent_skip++;
				continue;
			}
			if($agent['withdrawals_is_verify']=='交易完成')
				$agent['withdrawals_is_verify']=1;
			elseif($agent['withdrawals_is_verify']=='已转账')
				$agent['withdrawals_is_verify']=0;
			else
				continue;
			$cmd_ins->execute([':withdrawals_member_id'=>$aid,':withdrawals_currency'=>$agent['withdrawals_currency'],':withdrawals_add_date'=>$agent['withdrawals_add_date'],
			':withdrawals_is_verify'=>$agent['withdrawals_is_verify'],':withdrawals_verify_date'=>$agent['withdrawals_verify_date'],':withdrawals_remark'=>null,':withdrawals_finance_type_id'=>1,
			':withdrawals_tax'=>$agent['withdrawals_tax'],':withdrawals_real_currency'=>$agent['withdrawals_real_currency']]);
			echo "import withdrawals $agent[userid]\r\n";
			Yii::log( "导入提现记录 $agent[userid]\r\n",'info','import.withdrawals');
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
		$cmd_members=webapp()->dbms->createCommand($sql_members);
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
		$sql_award="select id,userid,Balance,DayGuangBonus as a348,ZhiTuiBonus as a349,CengPen as a350,PenDuiBonus as a352,GanEnBonus as a353,GuanLiBonus as a354,BaoDanBonus as a360,ZiBonus a355,HuanBao as a359,GuanLiFee as a358,ChongFu as a357,AiXinFee as a361,DealDate as ComputeTime from BonusDetail order by id asc";
		$cmd_award=webapp()->dbms->createCommand($sql_award);
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
		$sql_ins_sum="INSERT INTO 
			  public.epmms_award_period_sum
			(
			  award_period_sum_id,
			  award_period_sum_memberinfo_id,
			  award_period_sum_src_memberinfo_id,
			  award_period_sum_currency,
			  award_period_sum_period,
			  award_period_sum_add_date,
			  award_period_sum_type
			)
			VALUES (
			  :award_period_sum_id,
			  :award_period_sum_memberinfo_id,
			  :award_period_sum_src_memberinfo_id,
			  :award_period_sum_currency,
			  :award_period_sum_period,
			  :award_period_sum_add_date,
			  :award_period_sum_type
			);";
		$cmd_ins=webapp()->db->createCommand($sql_ins);
		$cmd_ins_sum=webapp()->db->createCommand($sql_ins_sum);
		$cnt_award=0;
		$cnt_award_skip=0;
		$cnt_award_exist=0;
		foreach($res_award as $award)
		{
			if($award['Balance']==0)
				continue;
			$member_id=Memberinfo::name2id($award['userid'],false);
			if(is_null($member_id))
			{
				Yii::log( "会员$award[userid]不存在，跳过导入\r\n",'error','import.award');
				echo "member $award[userid] not exist.\r\n";
				$cnt_award_skip++;
				continue;
			}
			$exist_cnt=AwardPeriodSum::model()->count('award_period_sum_id=:id',[':id'=>$award['id']]);
			if($exist_cnt==1)
			{
				$cnt_award_exist++;
				continue;
			}
			elseif($exist_cnt>1)
			{
				echo "member $award[userid] award record duplication,have $exist_cnt award records.\r\n";
				Yii::log( "会员 $award[userid] 奖金记录重复，有 $exist_cnt 条记录存在\r\n",'error','import.award');
				continue;
			}
			$transaction=webapp()->db->beginTransaction();
			if(!empty($award['a348']))
			{
				if($award['a348']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a348'],
					':award_period_type_id' => 348, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}

			if(!empty($award['a349']))
			{
				if($award['a349']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a349'],
					':award_period_type_id' => 349, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a350']))
			{
				if($award['a350']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a350'],
					':award_period_type_id' => 350, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a352']))
			{
				if($award['a352']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a352'],
					':award_period_type_id' => 352, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a353']))
			{
				if($award['a353']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a353'],
					':award_period_type_id' => 353, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a354']))
			{
				if($award['a354']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a354'],
						':award_period_type_id' => 354, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a355']))
			{
				if($award['a355']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a355'],
						':award_period_type_id' => 355, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a360']))
			{
				if($award['a360']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a360'],
						':award_period_type_id' => 360, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a357']))
			{
				if($award['a357']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a357'],
						':award_period_type_id' => 357, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a358']))
			{
				if($award['a358']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a358'],
						':award_period_type_id' => 358, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a359']))
			{
				if($award['a359']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a359'],
						':award_period_type_id' => 359, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			if(!empty($award['a361']))
			{
				if($award['a361']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a361'],
							':award_period_type_id' => 361, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			$cmd_ins_sum->execute([':award_period_sum_id'=>$award['id'],':award_period_sum_memberinfo_id'=>$member_id,':award_period_sum_src_memberinfo_id'=>null,
					':award_period_sum_currency'=>$award['Balance'],':award_period_sum_period'=>$award['id'],':award_period_sum_add_date'=>$award['ComputeTime'],
			':award_period_sum_type'=>1]);
			$transaction->commit();
			$cnt_award++;
		}
		$res_award->close();
		$sum_proc=new DbCall('setval',['award_period',$award['id']]);
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
	public function updateStatus()
	{
		$this->traversal_mid_parent(1);
	}
	/**
	 * 导入奖金记录
	 */
	public function importAwardAixin()
	{
		echo "开始导入奖金记录......\r\n";
		Yii::log( "开始导入奖金记录......\r\n",'info','import.award');
		$sql_award="select id,userid,Balance,DayGuangBonus as a348,ZhiTuiBonus as a349,CengPen as a350,PenDuiBonus as a352,GanEnBonus as a353,GuanLiBonus as a354,BaoDanBonus as a360,ZiBonus a355,HuanBao as a359,GuanLiFee as a358,ChongFu as a357,AiXinFee as a361,DealDate as ComputeTime from BonusDetail order by id asc";
		$cmd_award=webapp()->dbms->createCommand($sql_award);
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
		$sql_ins_sum="INSERT INTO
			  public.epmms_award_period_sum
			(
			  award_period_sum_id,
			  award_period_sum_memberinfo_id,
			  award_period_sum_src_memberinfo_id,
			  award_period_sum_currency,
			  award_period_sum_period,
			  award_period_sum_add_date,
			  award_period_sum_type
			)
			VALUES (
			  :award_period_sum_id,
			  :award_period_sum_memberinfo_id,
			  :award_period_sum_src_memberinfo_id,
			  :award_period_sum_currency,
			  :award_period_sum_period,
			  :award_period_sum_add_date,
			  :award_period_sum_type
			);";
		$cmd_ins=webapp()->db->createCommand($sql_ins);
		$cmd_ins_sum=webapp()->db->createCommand($sql_ins_sum);
		$cnt_award=0;
		$cnt_award_skip=0;
		$cnt_award_exist=0;
		foreach($res_award as $award)
		{
			$member_id=Memberinfo::name2id($award['userid'],false);
			if(is_null($member_id))
			{
				Yii::log( "会员$award[userid]不存在，跳过导入\r\n",'error','import.award');
				echo "member $award[userid] not exist.\r\n";
				$cnt_award_skip++;
				continue;
			}
			$exist_cnt=AwardPeriodSum::model()->count('award_period_sum_id=:id',[':id'=>$award['id']]);
			if($exist_cnt==0)
			{
				$cnt_award_exist++;
				continue;
			}
			if(!empty($award['a361']))
			{
				if($award['a361']>0)
					$cmd_ins->execute([':award_period_period' => $award['id'], ':award_period_memberinfo_id' => $member_id, ':award_period_currency' => $award['a361'],
						':award_period_type_id' => 361, ':award_period_add_date' => $award['ComputeTime'], ':award_period_sum_type' => 1]);
			}
			$cnt_award++;
		}
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
	/**
	 * 导入奖金记录
	 */
	public function importFinance()
	{
		echo "开始导入会员财务......\r\n";
		Yii::log( "开始导入会员财务......\r\n",'info','import.finance');
		$sql_award="select id,Balance,RegBalance,DianBalance,ShopBalance from [user] order by id asc";
		$cmd_award=webapp()->dbms->createCommand($sql_award);
		$res_award=$cmd_award->query();
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

		$cnt_award=0;
		$cnt_award_skip=0;
		foreach($res_award as $award)
		{
			$member_id=$award['id'];
			if(is_null($member_id))
			{
				$cnt_award_skip++;
				Yii::log( "会员$award[HyNumber1]不存在，跳过导入\r\n",'warning','import.finance');
				echo "member $award[HyNumber1] not exist,skip import finance.\r\n";
				continue;
			}
			if(is_null($award['Balance']))
				$award['Balance']=0;
			if(Finance::model()->exists('finance_memberinfo_id=:id and finance_type=:type',[':id'=>$member_id,':type'=>1]))
			{
				$cmd_update->execute([':finance_award'=> $award['Balance'], ':finance_type' =>1, ':finance_memberinfo_id' =>$member_id]);
			}
			else
			{
				$cmd_ins->execute([':finance_award'=> $award['Balance'], ':finance_type' =>1, ':finance_memberinfo_id' =>$member_id]);
			}
			if(is_null($award['RegBalance']))
				$award['RegBalance']=0;
			if(Finance::model()->exists('finance_memberinfo_id=:id and finance_type=:type',[':id'=>$member_id,':type'=>2]))
			{
				$cmd_update->execute([':finance_award'=> $award['RegBalance'], ':finance_type' =>2, ':finance_memberinfo_id' =>$member_id]);
			}
			else
			{
				$cmd_ins->execute([':finance_award'=> $award['RegBalance'], ':finance_type' =>2, ':finance_memberinfo_id' =>$member_id]);
			}
			if(is_null($award['ShopBalance']))
				$award['ShopBalance']=0;
			if(Finance::model()->exists('finance_memberinfo_id=:id and finance_type=:type',[':id'=>$member_id,':type'=>3]))
			{
				$cmd_update->execute([':finance_award'=> $award['ShopBalance'], ':finance_type' =>3, ':finance_memberinfo_id' =>$member_id]);
			}
			else
			{
				$cmd_ins->execute([':finance_award'=> $award['ShopBalance'], ':finance_type' =>3, ':finance_memberinfo_id' =>$member_id]);
			}
			$cnt_award++;
		}

		echo "import $cnt_award award finance record.\r\n";
		echo "skip $cnt_award_skip award finance record.\r\n";

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
	public function traversal_mid_parent($id)
	{
		//中序遍历
		$sql_child="select membermap_id,membermap_is_verify from epmms_membermap where membermap_parent_id=:id order by membermap_order asc";
		$cmd_child=webapp()->db->createCommand($sql_child);
		$member_layer=$cmd_child->queryAll(true,[':id'=>$id]);
		foreach($member_layer as $member)
		{
			if($member['membermap_is_verify']==1)
			{
				$this->cnt_members++;
				if(webapp()->name=='console')
				{
					if($this->cnt_members%100==0)
						echo "parent verify {$this->cnt_members}th member\t time:" .date('Y-m-d H:i:s') . "\r\n";
				}
				//echo "verify ${member['membermap_id']} ...\r\n";
				$model = Membermap::model()->findByPk($member['membermap_id']);
				$Proc=new DbCall('update_left_right_product_count',array((int)$model->membermap_id,$model->membermap_path,(int)$model->membermap_product_count));
				$Proc->run();
				/*
				if (($status = $model->reMapParent()) != EError::SUCCESS)
				{
					return $status;
				}*/
			}
			$this->traversal_mid_parent($member['membermap_id']);
		}
		return EError::SUCCESS;
	}
}