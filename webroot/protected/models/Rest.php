<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hetao
 * Date: 13-10-1
 * Time: 下午3:47
 * To change this template use File | Settings | File Templates.
 */

class Rest extends CModel
{

	public static function model()
	{
		if(is_null(self::$_model))
		{
			self::$_model=new Sms();
		}
		return self::$_model;
	}
	public function attributeNames()
	{
		return [];
	}
	public static function myinfo()
    {
        $data=[];
        $data['memberinfo']=user()->info->toArray(['memberinfoBank']);
        $data['membermap']=user()->map->toArray();
        $member_count=Membermap::model()->countByAttributes(['membermap_agent_id'=>user()->id]);
        $data['member_count']=$member_count;

        return $data;
    }
    public static function announcement()
    {
        $dataProvider=new JSonActiveDataProvider('Announcement');
        $data['announcement']=$dataProvider->getArrayData();
         /*   echo "<pre>";
     var_dump($data);*/
   
        return $data;
    }
    public static function finance()
    {
        $model=new Finance('search');
        $model->unsetAttributes();  // clear any default values
        $model->financeType=new FinanceType('search');
        $model->financeMemberinfo=new Memberinfo('search');
        $model->finance_memberinfo_id=user()->id;
        $data['financeType']=FinanceType::model()->search()->getArrayData();
        foreach($data['financeType'] as $k=>$v)
        {
            $f = Finance::model()->findByAttributes(['finance_memberinfo_id' => user()->id, 'finance_type' => $v['finance_type_id']]);
            if ($f)
            {
                $fa=$f->toArray();
            }
            else
            {
                $fa=new Finance('create');
                $fa->finance_memberinfo_id=user()->id;
                $fa->finance_award=0;
            }
            $data['financeType'][$k]['finances']=$fa;
        }

        $data['finance']=$model->search()->getArrayData();
        foreach($data['finance'] as $k=>$v)
        {
            $data['finance'][$k]['financeMemberinfo']['memberinfoBank']=Bank::model()->find()->toArray();
        }
            
        return $data;
    }
    public static function sumType($tree=true)
    {
        $model=new SumType('search');
        $model->unsetAttributes();  // clear any default values
        $prod=$data=$model->search();
        $data=$prod->getArrayData();
        $data_tree=[];

        foreach($data as $k=>$d)
        {
            if($tree)
            {
                $sTotal=0;
                $connection=Yii::app()->db;
                $sql="select a.* from epmms_award_type a inner join epmms_award_group g on award_type_id=award_group_type where award_group_group=:group";
                $command=$connection->createCommand($sql);
                $datareader=$command->query([':group'=>$d['sum_type_id']]);
                foreach($datareader as $ak=>$ad)
                {
                    $totalModel=AwardTotal::model()->findByAttributes(['award_total_memberinfo_id'=>user()->id,'award_total_type_id'=>$ad['award_type_id']]);
                    if(!$totalModel)
                    {
                        $totalModel=new AwardTotal('create');
                        $totalModel->award_total_currency=0;
                    }
                    $totalArray=$totalModel->toArray();
                    $sTotal+=$totalArray['award_total_currency'];
                    $node=[];
                    $node['text']=$ad['award_type_name'] . '<span style="float:right;">' . $totalArray['award_total_currency'] . '</span>';
                    $node['leaf']=true;
                    $data_tree[$k]['children'][$ak]=$node;
                }
                $data_tree[$k]['text']=$data[$k]['sum_type_name'] . '<span style="float:right;">' . $sTotal . '</span>';
            }

        }
        return [$data_tree];
    }
    public static function bank()
    {
        $model=new Bank('search');
        $model->unsetAttributes();  // clear any default values

        $model->bank_is_enable=1;
        $data['bank']=$model->search()->getArrayData();

        return $data;
    }
    public static function memberType()
    {
        $model=new MemberType('search');
        $model->unsetAttributes();  // clear any default values
        $data['memberType']=$model->search()->getArrayData();

        return $data;
    }
}