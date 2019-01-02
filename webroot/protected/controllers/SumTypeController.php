<?php

class SumTypeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
            'cors',
			'rights', // rights rbac filter
			'postOnly + delete', // 只能通过POST请求删除
			//'authentic + update,create,delete',//需要二级密码
		);
	}
    public function allowedActions()
    {
        return 'index';
    }
	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0,$tree=false)
	{
		$model=new SumType('search');
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['SumType']))
		{
			$model->attributes=$_GET['SumType'];
		}

        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
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
                    $data_tree[$k]['expanded']='true';
                }


            }
            echo CJSON::encode($tree?$data_tree:$data);
            webapp()->end();
        }
        else
        {
            $prod=$data=$model->search();
            $data=$prod->getArrayData();
            foreach($data as $k=>$d)
            {
                $total=AwardTotal::model()->search();
                $data[$k]['awardTotal']=$total->getArrayData();
                $data[$k]['sum_type_total']=0;
                foreach($data[$k]['awardTotal'] as $ak=>$ad)
                {
                    $data[$k]['sum_type_total']+=$ad['award_total_currency'];
                }
            }
            header('Content-Type: text/plain');
            //print_r($data);
            //echo CJSON::encode($data);
            echo json_encode(['a'=>2147483647]);
            webapp()->end();
        }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=SumType::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sum-type-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
