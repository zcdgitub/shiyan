<?php
class PayController extends Controller
{
	public $layout = '//layouts/column2';

	public function filters()
	{
		return array(
			'cors',
			'closeSite',
			'rights', // rights rbac filter
		);
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($ordersId)
	{


		$order=Orders::model()->findByPk($ordersId);
		if(is_null($order))
			throw new Error(t('epmms','订单错误'));
		$model=new Pay('create');
		$model->currency=$order->orders_currency;
		$model->orders_id=$order->orders_id;
		$this->performAjaxValidation($model);
		if(isset($_POST['Pay']))
		{
	     $model->attributes=$_POST['Pay'];
		
			if($model->validate())
			{
				
				$this->log['target']=$order->orders_sn;
				if($model->type==2)
				{
					$this->redirect(array('pay/pay','order_sn'=>$order->orders_sn,'order_currency'=>$order->orders_currency),true);
				}
				else if($model->type==3||$model->type==6)
				{
                    $transaction = webapp()->db->beginTransaction();

               /*   if(Orders::model()->findByAttributes())*/
                     
                   
                     $member_finace=Finance::getMemberFinance($order->orders_member_id,4);
                
                    
           


				/*	$member_finace=Finance::getMemberFinance($order->orders_member_id,2);//电子币*/

					if($member_finace->deduct($order->orders_currency))
					{

						$order=Orders::model()->findByAttributes(['orders_id'=>$ordersId],'orders_is_verify=0');
                          
						if(!$order){
						/*	$this->log['status']=LogFilter::FAILED;
						    $result_str='重复支付';
						    return;*/
							if(webapp()->request->isAjaxRequest){
                                  header('Content-Type: application/json');
                                  echo CJSON::encode(['success'=>false,'msg'=>'已支付,不能重复支付']);
                                  webapp()->end();
							}
						}else{
							$order->verify();
						}


                        $transaction->commit();


						$this->log['status']=LogFilter::SUCCESS;
						$result_str='支付成功';
						$this->log['info']=$result_str;
						$this->log['value']=$order->orders_currency;
						$this->log();
						LogFilter::log(['category'=>'finance','source'=>'订单','operate'=>'支付','user'=>user()->name,'role'=>user()->getRoleName(),'target'=>$order->orders_sn,'status'=>LogFilter::SUCCESS,'value'=>$order->orders_currency,'info'=>'电子币支付']);
						if(webapp()->request->isAjaxRequest)
                        {
                            header('Content-Type: application/json');
                            $data['success']=true;
                            $data['order']=$order->toArray(['ordersMember']);
                            echo CJSON::encode($data);
                            return;
                        }
					}
					else
					{
						$transaction->rollback();
						$this->log['status']=LogFilter::FAILED;
						$result_str='支付失败或金额不足';
						$this->log['info']=$result_str;
						$this->log['value']=$order->orders_currency;
						$this->log();
                        if(webapp()->request->isAjaxRequest)
                        {
                            header('Content-Type: application/json');
                            $data['success']=false;
                            $data['msg']=$result_str;
                            echo CJSON::encode($data);
                            return;
                        }
					}
					$this->render('return',['result_str'=>$result_str,'result'=>['order_sn'=>$order->orders_sn,'order_currency'=>$order->orders_currency]]);
				}
				else
				{


					// var_dump($model->getErrors());
					// die;
					$this->log['status']=LogFilter::FAILED;
					$this->log['info']='支付类型错误';
					$this->log['value']=$order->orders_currency;
					$this->log();
                    if(webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success']=false;
                        $data['msg']=$this->log['info'];
                        echo CJSON::encode($data);
                        return;
                    }
					throw new Error('支付类型错误');
				}
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$order->showName}”" . t('epmms',"失败"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['msg']=$model->getErrors();
                    $data['success']=false;
                    echo CJSON::encode($data);
                    return;
                }
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'order'=>$order
		));
	}
	/**
	 * 发送支付请求
	 * @param $order
	 */
	public function actionPay($order_sn, $order_currency,$subject)
	{
		$pay = Yii::app()->pay;
		$pay->order_sn = $order_sn;
		$pay->order_currency = $order_currency;
		$pay->subject=$subject;
		// Set other optional params if needed
		$this->render('pay', ['pay' => $pay]);
	}

	/**
	 * 支付后的回调页面，显示支付结果
	 * @throws CHttpException
	 */
	public function actionReturnPay()
	{
		$pay = Yii::app()->pay;
		$result=$pay->verifyReturn();
		$order=Orders::model()->findByAttributes(['orders_sn'=>$result['order_sn']],'orders_is_verify=0');
		$this->log['target']=$result['order_sn'];
		if(is_null($order))
		{
			$this->log['status']=LogFilter::FAILED;
			$this->log['info']='订单不存在或已支付';
			$this->log['value']=$result['order_currency'];
			$this->log();
			throw new Error('订单不存在或已支付');
		}

		if($result)
		{
			if ($result['order_currency']>=$order->orders_currency)
			{
				//支付成功
				$order->verify();
				$result_str=t('epmms','支付成功');
				$this->log['status']=LogFilter::SUCCESS;
				$this->log['info']=$result_str;
				$this->log['value']=$result['order_currency'];
				$this->log();
				LogFilter::log(['category'=>'finance','source'=>'订单','operate'=>'支付','user'=>user()->name,'role'=>user()->getRoleName(),'target'=>$result['order_sn'],'status'=>LogFilter::SUCCESS,'value'=>$result['order_currency'],'info'=>'在线支付']);
			}
			else
			{
				$result_str=t('epmms','支付金额不足');
				$this->log['status']=LogFilter::FAILED;
				$this->log['info']=$result_str;
				$this->log['value']=$result['order_currency'];
				$this->log();
			}
		}
		else
		{
			$result_str=t('epmms','交易失败');
			$this->log['status']=LogFilter::FAILED;
			$this->log['info']=$result_str;
			$this->log();
		}
		$this->render('return',['result_str'=>$result_str,'result'=>$result]);
	}
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pay-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

?>
