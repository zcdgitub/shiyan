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
class ImportOdbcCommand extends CConsoleCommand
{
	private $_connect=null;
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
		$member_cnt=null;
		$limit='';
		if(isset($args[0]))
		{
			$member_cnt=intval($args[0]);
			$limit="top $member_cnt";
		}
		$sql_members="select $limit h.id as h_id,y.HyNumber as y_HyNumber,h.HyPassword1 as h_HyPassword,h.HyPassword2 as h_HyPassword2,h.HyName as h_HyName,h.HyMail as h_HyMail,
		h.HyMobile as h_HyMobile,h.HyTel as h_HyTel,h.HySex as h_HySex,h.HyAddress as h_HyAddress,h.HyOpenBank as h_HyOpenBank,
		h.HyOpenBankNo as h_HyOpenBankNo,h.HyOpenBankName as h_HyOpenBankName,y.IsApproved as y_IsApproved,y.AddTime as y_AddTime,h.HyCardNo as h_HyCardNo,
		h.HyParentNumber as h_HyParentNumber,h.HyLocation as h_order,h.HyTjNumber as h_HyTjNumber,w.HyNumber as w_HyNumber,y.ApprovedTime as y_ApprovedTime,w.WuLiuLevel as w_WuLiuLevel,
		y.IsWuliu as y_IsWuliu
		 from HY as h,HyClub as y,WuliuInfo as w where h.ID<>126 and y.HyID=h.ID and y.WuliuNumber=w.WuliuNumber order by h.ID asc";

		$sql_agents="select HyNumber,WuliuLevel,WuliuArea1 as WuliuProvince,WuliuArea2 as WuliuArea,AddTime,IsApproved,ApprovedTime from WuliuInfo order by id asc";
		$cmd_members=odbc_exec($this->getConnect(),$sql_members);
		$cmd_agents=odbc_exec($this->getConnect(),$sql_agents);
		ini_set("max_execution_time", 0);
		set_time_limit(0);
		echo "begin start.....\r\n";
		$sdate=date('Y-m-d H:i:s');
		echo $sdate;
		echo "\r\n";
		Yii::log( "导入数据开始......\r\n",'info','import.cmd');
		Yii::log( "开始时间$sdate\r\n",'info','import.cmd');
		$cnt=0;
		$sex=['男'=>0,'女'=>1,'保密'=>2];
		$bank=['中国农业银行'=>1,'中国工商银行'=>2,'中国建设银行'=>3,'中国邮政银行'=>4,'支付宝帐户'=>6,'财付通账户'=>7,
			'财付通帐户'=>7,'支付宝'=>6,'支付宝账户'=>6,'财付通'=>7,'工商银行'=>2,'建设银行'=>3,'建行'=>3,'农业银行'=>1,
			'农行'=>1,'邮政'=>4,'邮政银行'=>4,'支付宝账号'=>6,'支付宝帐号'=>6,'中国建行银行'=>3,'中国农行银行'=>1,
			'中国农业银行卡'=>1,'中国邮政'=>4,'中国邮政储蓄'=>4,'中国邮政储蓄银行'=>4,'中国邮政银行'=>4];
		//$agent_type=['省级'=>1,'市级'=>2,'县级'=>3];
		$sql_info="insert into epmms_memberinfo(memberinfo_account,memberinfo_password,memberinfo_password2,memberinfo_name,
			memberinfo_nickname,memberinfo_email,memberinfo_mobi,memberinfo_phone,memberinfo_sex,
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
		while($member=odbc_fetch_array($cmd_members))
		{
			if($this->check_str($member['y_HyNumber'])>1)
				$member['y_HyNumber']=@iconv('GBK', 'UTF-8', $member['y_HyNumber']);
			if(Memberinfo::model()->exists('memberinfo_account=:account',[':account'=>$member['y_HyNumber']]))
				continue;
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
			if($this->check_str($member['w_HyNumber'])>1)
				$member['w_HyNumber']=@iconv('GBK', 'UTF-8', $member['w_HyNumber']);

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
			Yii::log( '导入:' . $member['y_HyNumber'] . " parent:$member[h_HyParentNumber],recommend:$member[h_HyTjNumber],agent:$member[w_HyNumber])" . "\r\n",'info','import.member');
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
			':agent_id'=>Memberinfo::name2id($member['w_HyNumber']),':membertype_level'=>1,':membermap_product_count'=>1,':is_verify'=>1,
				':verify_date'=>empty2null($member['y_ApprovedTime']),':verify_member_id'=>Memberinfo::name2id($member['w_HyNumber']),
			':add_date'=>empty2null($member['y_AddTime']),':is_empty'=>0,':money'=>360,':agent_type'=>2,
				'reg_member_id'=>Memberinfo::name2id($member['w_HyNumber'])]);
			$transaction->commit();
			$cnt++;
		}
		Yii::log( "导入会员记录完成,共导入${cnt}个会员.\r\n",'info','import.member');
		echo "import {$cnt} members success\r\n";
		odbc_free_result($cmd_members);
		$cmd_members=odbc_exec($this->getConnect(),$sql_members);
		$cnt1=0;
		$cnt2=0;
		Yii::log( "开始校正网络图关系......\r\n",'info','import.corr');
		while($member=odbc_fetch_array($cmd_members))
		{
			if($this->check_str($member['y_HyNumber'])>1)
				$member['y_HyNumber']=@iconv('GBK', 'UTF-8', $member['y_HyNumber']);
			if(!Memberinfo::model()->exists('memberinfo_account=:account',[':account'=>$member['y_HyNumber']]))
				continue;
			$transaction=webapp()->db->beginTransaction();
			$mid=Memberinfo::name2id($member['y_HyNumber']);
			$map=Membermap::model()->findByAttributes(['membermap_id'=>$mid]);
			if(!is_object($map))
			{
				echo "member " . $member['y_HyNumber'] ." not found,id:$mid\r\n";
				Yii::log( "校正网络图关系:会员$member[y_HyNumber]找不到记录\r\n",'error','import.corr');
				continue;
			}
			else
			{
				echo "correction $member[y_HyNumber]" . "\r\n";
			}
			if(is_null($map->membermap_parent_id))
			{
				$cnt2++;
				if($this->check_str($member['h_HyParentNumber'])>1)
					$member['h_HyParentNumber']=@iconv('GBK', 'UTF-8', $member['h_HyParentNumber']);
				$map->membermap_parent_id=Memberinfo::name2id($member['h_HyParentNumber']);
				if(is_null($map->membermap_parent_id))
				{
					echo "member $member[y_HyNumber] parent not found\r\n";
					Yii::log("会员$member[y_HyNumber]接点人找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					$map->saveAttributes(['membermap_parent_id']);
					echo "write member $member[y_HyNumber] recommend:$member[h_HyParentNumber]\r\n";
					Yii::log("写入会员$member[y_HyNumber]接点人:$member[h_HyParentNumber]\r\n", 'info', 'import.corr');
					$cnt1++;
				}
			}
			if(is_null($map->membermap_recommend_id))
			{
				$cnt2++;
				if($this->check_str($member['h_HyTjNumber'])>1)
					$member['h_HyTjNumber']=@iconv('GBK', 'UTF-8', $member['h_HyTjNumber']);
				$map->membermap_recommend_id=Memberinfo::name2id($member['h_HyTjNumber']);
				if(is_null($map->membermap_recommend_id))
				{
					echo "member $member[y_HyNumber] recommend not found\r\n";
					Yii::log("会员$member[y_HyNumber]接点人找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					$map->saveAttributes(['membermap_recommend_Id']);
					echo "write member $member[y_HyNumber] recommend:$member[h_HyTjNumber]\r\n";
					Yii::log("写入会员$member[y_HyNumber]推荐人:$member[h_HyTjNumber]\r\n", 'info', 'import.corr');
					$cnt1++;
				}
			}
			if(is_null($map->membermap_agent_id))
			{
				$cnt2++;
				if($this->check_str($member['w_HyNumber'])>1)
					$member['w_HyNumber']=@iconv('GBK', 'UTF-8', $member['w_HyNumber']);
				$map->membermap_agent_id=Memberinfo::name2id($member['w_HyNumber']);
				if(is_null($map->membermap_agent_id))
				{
					echo "member $member[y_HyNumber] agent not found\r\n";
					Yii::log("会员$member[y_HyNumber]报单中心找不到或无效\r\n", 'error', 'import.corr');
				}
				else
				{
					echo "write member $member[y_HyNumber] agent:$member[w_HyNumber]\r\n";
					$map->membermap_reg_member_id=$map->membermap_agent_id;
					$map->saveAttributes(['membermap_agent_id','membermap_reg_member_id']);
					Yii::log("写入会员$member[y_HyNumber]报单中心:$member[w_HyNumber]\r\n", 'info', 'import.corr');
					$cnt1++;
				}
			}
			$transaction->commit();
		}
		odbc_free_result($cmd_members);
		Yii::log( "校正网络图关系完成,${cnt2}个字段是空,校正${cnt1}个字段......\r\n",'info','import.corr');
		echo "${cnt2} fields is null,correction {$cnt1} map field success\r\n";
		Yii::log( "开始导入代理中心......\r\n",'info','import.agent');
		echo "开始导入代理中心......\r\n";
		//导入代理中心
		$sql_agent="insert into epmms_agent(agent_memberinfo_id,agent_type,agent_province,agent_area,agent_county,agent_memo,
			agent_add_date,agent_verify_date,agent_is_verify)values(:member_id,:type,:province,:area,:county,:memo,
			:add_date,:verify_date,:is_verify)";
		$cmd_agent=webapp()->db->createCommand($sql_agent);
		$cnt_agent=0;
		while($agent=odbc_fetch_array($cmd_agents))
		{
			if($this->check_str($agent['HyNumber'])>1)
				$agent['HyNumber']=@iconv('GBK', 'UTF-8', $agent['HyNumber']);
			$aid=Memberinfo::name2id($agent['HyNumber']);
			if(is_null($aid))
			{
				Yii::log( "会员$agent[HyNumber]不存在，跳过导入\r\n",'warning','import.agent');
				echo "member $agent[HyNumber] not exist,skip import.\r\n";
				continue;
			}
			if(Agent::model()->exists('agent_memberinfo_id=:id',[':id'=>$aid]))
				continue;
			$agent['WuliuProvince']=@iconv('GBK', 'UTF-8', $agent['WuliuProvince']);
			$agent['WuliuArea']=@iconv('GBK', 'UTF-8', $agent['WuliuArea']);
			$cmd_agent->execute([':member_id'=>$aid,':type'=>2,':province'=>$agent['WuliuProvince'],
				':area'=>$agent['WuliuArea'],':county'=>null,':memo'=>null,':add_date'=>$agent['AddTime'],':verify_date'=>$agent['ApprovedTime'],':is_verify'=>$agent['IsApproved']]);
			echo "import agent $agent[HyNumber]\r\n";
			Yii::log( "导入代理中心$agent[HyNumber]\r\n",'info','import.agent');
			$cnt_agent++;
		}
		odbc_free_result($cmd_agents);
		echo "import {$cnt_agent} agents success\r\n";
		Yii::log( "导入代理中心完成,导入${cnt_agent}个代理中心\r\n",'info','import.agent');

		echo "**************** informations summary ********************\r\n";
		echo "import {$cnt} members success\r\n";
		echo "${cnt2} fields is null,correction {$cnt1} map field success\r\n";
		echo "import {$cnt_agent} agents success\r\n";
		echo "start date:$sdate finish date:" . date('Y-m-d H:i:s');
		Yii::log("开始时间:$sdate 结束时间:" . date('Y-m-d H:i:s'),'info','import.cmd');
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

	/**
	 * 取得ODBC连接
	 * @return null|resource
	 */
	public function getConnect()
	{
		if(is_null($this->_connect))
			$this->_connect=odbc_connect("DRIVER={MDBTools};DBQ=../db/#softdb41.mdb",'','');
		return $this->_connect;
	}
}