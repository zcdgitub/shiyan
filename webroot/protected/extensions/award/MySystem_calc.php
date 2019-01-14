<?php

/** 
 * @author hetao
 * 制度方案
 */
class MySystem_calc extends \AwardSystem
{
	protected $_name='141120';
	public function __construct()
	{
		$dbPeriod=new DbEvaluate("nextval('award_period')");
		$this->_period=$dbPeriod->run();
	}
	public function calc($group,$calc,$calc_type)
	{
		$sumProc=new DbCall('epmms_verify_award_group',array(null,$this->getPeriod(),$group,$calc,$calc_type));
		$sumProc->run();
        $this->genMember();
	}
	public function sum()
	{
	}
    public function genMember()
    {
        //拿600的见点奖送点位
        $connection=Yii::app()->db;
        $transaction=webapp()->db->beginTransaction();
        $sql="select membermap_id as id from epmms_finance,epmms_membermap where membermap_id=finance_memberinfo_id and finance_type=5 and current_date-coalesce(membermap_futou_date,current_date-30)=30 and finance_award>=iif(membermap_membertype_level=1,3000,9000);";

        $command=$connection->createCommand($sql);
        $datareader=$command->query();
        foreach($datareader as $row)
        {
            if($mymember=Membermap::model()->findByPk($row['id']))
            {
                $newMember=$this->genNewMember($mymember);
                if(is_object($newMember))
                {
                    if(!Messages::send('生成会员','生成的会员:'.$newMember->memberinfo_account,$mymember->membermap_id))
                    {
                        $transaction->rollback();
                        throw new Error('发送邮件失败');
                    }
                }
                else
                {
                    $transaction->rollback();
                    throw new Error('自动注册会员失败');
                }

                $mymember->membermap_is_active=0;
                $mymember->saveAttributes(['membermap_is_active']);
            }
        }
        $transaction->commit();
    }
    /**
     * @param Membermap $root_map
     * @param null $order
     */
    public function genNewMember($root_map,$order=null)
    {
        $root_info=$root_map->memberinfo;
        $transaction=webapp()->db->beginTransaction();
        $info=new Memberinfo('create');
        $info->attributes=$root_info->attributes;
        $info->unsetAttributes(['memberinfo_id','memberinfo_is_verify','memberinfo_last_date','memberinfo_last_ip','memberinfo_nickname']);
        $sys=SystemStatus::model()->find();
        $info->memberinfo_account=$root_info->memberinfo_account . '_auto_' . $sys->system_status_verify_seq;
        $info->memberinfo_nickname=$info->memberinfo_account . '_' . $root_info->memberinfo_nickname;
        $info->memberinfo_password_repeat=$info->memberinfo_password;
        $info->memberinfo_password_repeat2=$info->memberinfo_password2;
        $info->memberinfo_add_date=new CDbExpression('now()');
        $info->memberinfo_is_verify=0;
        if($info->save()&&$info->refresh())
        {
            $map=new Membermap('create');
            $map->membermap_id=$info->memberinfo_id;
            $parent=Membermap::model()->find(["condition"=>"membermap_path like :path || '%'","order"=>'membermap_verify_seq desc','params'=>[':path'=>$root_map->membermap_path]]);
            if(is_null($parent))
            {
                throw new EError('没有找到接点人');
            }
            $pid=$parent->membermap_id;
            $map->membermap_recommend_id=$root_map->membermap_id;
            $map->membermap_membertype_level=$root_map->membermap_membertype_level;
            $map->membermap_is_verify=0;
            $map->membermap_agent_id=$pid;
            $map->membermap_bond_id=$root_map->membermap_id;
            if(is_null($map->membermap_agent_id))
                $map->membermap_agent_id=1;
            //自动排网
            $map->membermap_parent_id=$pid;
            $map->membermap_order=1;

            if(!$map->save())
            {
                $transaction->rollback();
                return false;
            }
            if($info->verify(true,3)==EError::SUCCESS)
            {
                $transaction->commit();
                return $info;
            }
            else
            {
                $transaction->rollback();
                throw new Error('生成会员时审核失败或电子币不足');
            }
        }
        $transaction->rollback();
        return false;
    }
}

?>