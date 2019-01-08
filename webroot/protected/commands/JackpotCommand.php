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
 * 分奖池
 * @property string $help The command description.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.console
 * @since 1.0
 */
class JackpotCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
        $config = [
            'config_jackpot_start_order_ratio' => 20,     // 首单比例
            'config_jackpot_lucky_order_ratio' => 50,     // 幸运比例
            'config_jackpot_end_order_ratio'   => 30,     // 尾单比例
            'config_jackpot_fund'               => 100000, // 发展基金
            'config_jackpot_start_balance'     => 0,      // 首单余额
            'config_jackpot_lucky_balance'     => 0,      // 幸运余额
            'config_jackpot_end_balance'       => 0,      // 尾单余额
            'config_jackpot_start_time'        => 1545972695,  // 开始时间
            'config_jackpot_end_time'          => 1546059095,  // 结束时间
        ];
        $model  = (new ConfigJackpot())->findByPk(1);
        $startTime = $model->config_jackpot_start_time;
        $endTime   = $model->config_jackpot_end_time;
//        if($endTime-1 <= time()){
        if(true){
            $fundMoney = 10000;   // 扣除发展基金
            $model->config_jackpot_fund      -= $fundMoney;
            $model->config_jackpot_start_time = time();
            $model->config_jackpot_end_time   = time()+180;
            // 数据库取数据：大于开始时间 小于当前时间
//            $data = array('12312',2321,55,56,57,4,1,1,4,5,10,3);
            $data = MemberUpgrade::model()->findAll([
                'select'=>'member_upgrade_member_id',
                'condition' => "member_upgrade_type = 2 and member_upgrade_add_date >='".date('Y-m-d H:i:s',$startTime)."' and member_upgrade_add_date <='".date('Y-m-d H:i:s',$endTime)."' and member_upgrade_is_verify = 1",
                'order' => 'member_upgrade_add_date asc',
            ]);
            $data  = array(1);
//            $data = array('12312','1313',13123,2222,1,2,3,5,4,10,121,11);
            if(!empty($data)){
                switch (count($data)){
                    case 1:  // 尾单奖池
                        // 获奖人增加奖金
//                        $this->addEndJackpot($data[count($data)-1]->member_upgrade_member_id,$model->config_jackpot_end_balance,$startTime,$endTime,3,6);
                        $this->addEndJackpot(1,$model->config_jackpot_end_balance,$startTime,$endTime,3,6);
                        // 奖池奖金为10000 * 比例 + 上期首单+幸运奖池 余额比例
                        $sumMoney =  $model->config_jackpot_start_balance + $model->config_jackpot_lucky_balance;
                        break;
                    case 2:  // 首单奖池与尾单奖池
                         // 奖池奖金为10000 * 比例 + 幸运奖池 余额的比例
                        $sumMoney = $model->config_jackpot_lucky_balance; // 幸运奖池金额
                        // 获奖人增加奖金
                        //  尾单
                        $this->addEndJackpot($data[count($data)-1]->member_upgrade_member_id,$model->config_jackpot_end_balance,$startTime,$endTime,3,6);
                        //   首单
                        $this->addEndJackpot($data[0]->member_upgrade_member_id,$model->config_jackpot_start_balance,$startTime,$endTime,1,4);

                        break;
                    default: // 幸运奖池
                        $sumMoney = $model->config_jackpot_lucky_balance;
                        //  尾单
                        $this->addEndJackpot($data[count($data)-1]->member_upgrade_member_id,$model->config_jackpot_end_balance,$startTime,$endTime,3,6);
                        //   首单
                        $this->addEndJackpot($data[0]->member_upgrade_member_id,$model->config_jackpot_start_balance,$startTime,$endTime,1,4);
                        $count = count($data)>=12 ? 11:(count($data)-1);
                        for ($i=1;$i<$count;$i++){
                            if(isset($data[$i]) && !empty($data[$i])){
                                $this->addEndJackpot($data[$i]->member_upgrade_member_id,$model->config_jackpot_lucky_balance/10,$startTime,$endTime,2,5);
                                // 幸运奖池金额减少
                                $sumMoney -= $model->config_jackpot_lucky_balance/10;
                            }
                        }
                        break;
                }
            }else{
                // 奖池奖金为 10000 * 比例 (期间内没有激活金卡的 累加)
                $sumMoney = $model->config_jackpot_start_balance+$model->config_jackpot_end_balance+$model->config_jackpot_lucky_balance;
            }
            $model->config_jackpot_start_balance = round($sumMoney*$model->config_jackpot_start_order_ratio/100 + $fundMoney * $model->config_jackpot_start_order_ratio/100,2);
            $model->config_jackpot_end_balance   = round($sumMoney*$model->config_jackpot_end_order_ratio/100 + $fundMoney * $model->config_jackpot_end_order_ratio/100,2);
            $model->config_jackpot_lucky_balance = round($sumMoney*$model->config_jackpot_lucky_order_ratio/100 + $fundMoney * $model->config_jackpot_lucky_order_ratio/100,2);
            $model->save();
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

    /**添加获奖记录
     * @return int
     * @throws CDbException
     * @throws CException
     */
	public function addRecord($group,$jackpotId){
        $transaction=webapp()->db->beginTransaction();
        try
        {
            Yii::import('ext.award.MySystem_calc');
            $mysystem=new MySystem_calc();
            //$mysystem->run($group,$calc,$calc_type);
            // 尾单奖 2 0 20   首单奖3 0 20  幸运奖4 0 20
            $mysystem->run($group,0,$jackpotId); //日返利 推荐奖日结 存入现金钱包10  10
            $Proc = new DbCall('gen_finance_log');
            $Proc->run();
//
//            //发送短信
//            Sms::model()->sendAward($mysystem->getPeriod());
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
    }
    /**添加 奖池获奖记录
     * @param $uid
     * @param $money
     * @param $type
     * @param $startTime
     * @param $endTime
     * @return bool
     */
    public function addJackpotRecord($uid,$money,$type,$startTime,$endTime){
        $jackpot = new JackpotRecord();
        $jackpot->jackpot_member_id  = $uid;
        $jackpot->jackpot_money      = $money;
        $jackpot->jackpot_type       = $type;
        $jackpot->jackpot_start_time = $startTime;
        $jackpot->jackpot_end_time   = $endTime;
        $jackpot->save();
        return $jackpot->attributes['jackpot_id'];
    }

    /**
     * 添加尾单获奖记录并增加获奖者余额
     */
    public function addEndJackpot($uid,$money,$startTime,$endTime,$type,$group){
        $transaction=webapp()->db->beginTransaction();
        try{
            // 增加奖池获奖者记录
            $ret = $this->addJackpotRecord($uid,$money,$type,$startTime,$endTime);
            // 转账明细
            $this->addRecord($group,$ret);
            $transaction->commit();
        }catch(Exception $e)
        {
            $transaction->rollback();
            throw $e;
            return EError::ERROR;
        }
    }
}