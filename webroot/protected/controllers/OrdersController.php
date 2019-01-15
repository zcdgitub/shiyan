<?php

class OrdersController extends Controller
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
			//'postOnly + delete', // 只能通过POST请求删除
			//'authentic + index,update,create,delete',//需要二级密码
		);
	}
	public function allowedActions()
	{
		return '';
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id=null)
	{

			
       if(is_null($id))
       {
       		$model=Orders::model()->findByAttributes(['orders_member_id'=>user()->id,'orders_is_verify'=>0]);
   
       }else{

      		if(user()->isAdmin())
      		{
      			$model=$this->loadModel($id);
      		}
      		else
      		{
      		
      			$model=Orders::model()->findByAttributes(['orders_member_id'=>user()->id,'orders_id'=>$id]);
      		
      		}

	  }
		if(!user()->isAdmin() && $model->orders_member_id!=user()->id)
			throw new CHttpException('只能查看自己的订单',403);
 //$data['order']=$model->toArray();
		        
        if (webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(empty($model))
            {
                echo CJSON::encode(['success'=>false,'msg'=>'您还没有添加订单']);
                webapp()->end();
            }
            else
            {
		         $data['order']=$model->toArray();
		         $data['product_order']=yii::app()->db->createCommand()->select('*')->from('epmms_orders_product ')->leftjoin('epmms_product','epmms_product.product_id=epmms_orders_product.orders_product_product_id')->leftjoin('epmms_product_class','epmms_product.product_class_id=epmms_product_class.product_class_id')->where('epmms_orders_product.orders_product_orders_id='.$model['orders_id'])->queryAll(); 
		         echo CJSON::encode($data);
	             webapp()->end();
	             /*     $data['product_order']=yii::app()->db->createCommand()->select('*')->from('epmms_orders_product ')->leftjoin('epmms_product','epmms_product.product_id=epmms_orders_product.orders_product_product_id')->leftjoin('epmms_product_class','epmms_product.product_class_id=epmms_product_class.product_class_id')->where('epmms_orders_product.orders_product_orders_id='.$id)->queryAll(); 
		         echo "<Pre>";
		         var_dump($data);
		         die;*/
            }

        }
		$this->render('view',array(
			'model'=>$model,
		));
       
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

		$model=Orders::model()->findByAttributes(['orders_member_id'=>user()->id,'orders_is_verify'=>0]);
		$ordersProduct=null;
		$this->performAjaxValidation($model);
		$discount=1;
/*		if(user()->map->membermap_membertype_level==1)
			$discount=0.5;
		if(user()->map->membermap_membertype_level==2)
			$discount=0.75;
		if(user()->map->membermap_membertype_level==3)
			$discount=0.75;*/
		if(isset($_POST['OrdersProduct']))
		{

			$valid=true;
			$total_currency=0;
			$total_price=0;
			$transaction=webapp()->db->beginTransaction();
            if(!is_null($model))
            {
                $ops = OrdersProduct::model()->findAllByAttributes(['orders_product_orders_id' => $model->orders_id]);
                foreach($ops as $op)
                {
                    $op->delete();
                }
            }
            else
            {
                $model=new Orders('create');
                $model->orders_member_id=user()->id;
                $model->orders_currency=0;
                $model->orders_status='新订单';
                $model->orders_is_verify=0;
                if(!$model->save(false))
                {
                    if(webapp()->request->isAjaxRequest)
                    {
                        header('Content-Type: application/json');
                        $data['success']=false;
                        $data['msg']="创建购物车失败";
                        $data['errors']=$model->getErrors();
                        echo CJSON::encode($data);
                        return;
                    }
                    throw new EError("创建购物车失败");
                    return;
                }
                $model->refresh();
            }
            $ordersProduct=$_POST['OrdersProduct'];

            $errors=[];
			foreach($ordersProduct as $id=>$pCnt)
			{
                $oProduct=new OrdersProduct('create');
				$product=Product::model()->findByPk($id);
				$oProduct->orders_product_count=$pCnt['orders_product_count'];
				$oProduct->orders_product_price=$product->product_price;
				$oProduct->orders_product_orders_id=$model->orders_id;
				$oProduct->orders_product_product_id=$id;
				// $oProduct->orders_product_currency=$product->product_price*$oProduct->orders_product_count*$discount;
				$oProduct->orders_product_currency=$product->product_price*$oProduct->orders_product_count;
				$total_currency=$total_currency+$oProduct->orders_product_currency;
				$total_price+=$product->product_price*$oProduct->orders_product_count;
				$product->product_stock=$product->product_stock-$oProduct->orders_product_count;
				$product->product_sales_amount=$product->product_sales_amount+$oProduct->orders_product_count;
				$valid=$valid && $oProduct->save() && $product->save();
				$errors=array_merge($errors,$oProduct->getErrors(),$product->getErrors());
			}
			if($valid)
			{

			    $model->attributes=$_POST['Orders'];
				$model->orders_currency=$total_currency;
				$model->orders_price=$total_price;
				$model->save(true,['orders_currency','orders_price','orders_remark']);

				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
	
			   
                if (webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if (user()->hasFlash('success'))
                    {
                        $data['success'] = true;
                        $data['order'] = $model->toArray();
                    }

                    echo CJSON::encode($data);
                    return;
                }
				$this->redirect(array("pay/create","ordersId"=>$model->orders_id));
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
				$transaction->rollback();
                if (webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['success']=false;
                    $data['errors']=array_merge($errors,$model->getErrors());
                    echo CJSON::encode($data);
                    return;
                }
			}
		}
    



		if(!is_null($model))
		{
			$total_currency=0;
            $ordersProduct=OrdersProduct::model()->findAllByAttributes(['orders_product_orders_id' => $model->orders_id]);
			foreach($ordersProduct as $id=>$oProduct)
			{
				$product=$oProduct->ordersProductProduct;
				$total_currency+=$product->product_price*$oProduct->orders_product_count;
			}
			$model->orders_currency=$total_currency;
		}
		$this->render('create',array(
			'model'=>$model,
			'ordersProduct'=>$ordersProduct,
			'total_currency'=>@$total_currency
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='update';
		$this->performAjaxValidation($model);

		if(isset($_POST['Orders']))
		{
			$model->attributes=$_POST['Orders'];
			$this->log['target']=$model->orders_id;
			if($model->save(true,array('orders_member_id','ordrs_currency','orders_status','orders_add_date','orders_remark')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                if (webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if (user()->hasFlash('success'))
                    {
                        $data['success'] = true;
                        $data['order'] = $model->toArray();
                    }
                    echo CJSON::encode($data);
                    return;
                }
				$this->redirect(array('view','id'=>$model->orders_id));
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
                if (webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    if ($model->getErrors())
                        $data = $model->getErrors();
                    $data['success']=false;
                    echo CJSON::encode($data);
                    return;
                }

			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */




	public function actionDelete($id)
	{

		$model=$this->loadModel($id);
		$this->log['target']=$model->showName;
		if($model->orders_is_verify!=0)
        {
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['success']=false;
                $data['error']="只有未支付订单才能删除";
                echo CJSON::encode($data);
                return;
            }
            else
            {
                throw new EError("只有未支付订单才能删除");
            }
        }
		if($model->delete())
		{
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
		}
		else
		{
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
		}
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(user()->hasFlash('success'))
                $data['success']=true;
            if(user()->hasFlash('error'))
                $data['error']=user()->getFlash('success','',true);
            echo CJSON::encode($data);
            return;
        }
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('Orders');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new Orders('search');
		// $con=pg_connect("host=pgsql port=5432 user=epmms_181225 password=yi8jt1uBx4Wfb1HBeDe8Ph3j dbname=epmms_181225");
		
		// var_dump($con);
		// die;
		
		$model->unsetAttributes();  // clear any default values
		$model->ordersMember=new Memberinfo('search');

		if(isset($_GET['Orders']))
		{
			$model->attributes=$_GET['Orders'];
			if(isset($_GET['Orders']['ordersMember']))
				$model->ordersMember->attributes=$_GET['Orders']['ordersMember'];
		}
		if(!user()->isAdmin())
		{
			$model->orders_member_id=user()->id;
		}
		$model->orders_is_verify=(int)$selTab;
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['order']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            return;
        }
		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}


	public function actionVerify($id)
	{

		$model=$this->loadModel($id);
		if(($status=$model->verify())===EError::SUCCESS)
		{
			$this->log['status']=LogFilter::SUCCESS;
			user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
		}
		elseif($status===EError::DUPLICATE)
		{
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,请不要重复审核"));
		}
		$this->log();
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(user()->hasFlash('success'))
                $data['success']=true;
            if(user()->hasFlash('error'))
                $data['error']=user()->getFlash('success','',true);
            echo CJSON::encode($data);
            return;
        }
		$this->redirect(['index'],true);
	}
	public function actionSend($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='update';
		$this->performAjaxValidation($model);

		if(isset($_POST['Orders']))
		{
			$model->attributes=$_POST['Orders'];
			$this->log['target']=$model->orders_id;
			if($model->save(true,array('orders_logistics_name','orders_logistics_sn')) && $model->send()===EError::SUCCESS)
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(['index','selTab'=>1],true);
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
		}

		$this->render('send',array(
				'model'=>$model,
		));
	}
	public function actionAdd($id)
	{

		$product=Product::model()->findByPk($id);
		$cart=Orders::model()->findByAttributes(['orders_member_id'=>user()->id,'orders_is_verify'=>0]);
		if(is_null($cart)){
			$cart=new Orders('create');
			$cart->orders_member_id=user()->id;
			$cart->orders_currency=0;
			$cart->orders_status='新订单';
			$cart->orders_is_verify=0;
			if(!$cart->save())
			{
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['success']=false;
                    $data['msg']=$cart->getErrors();
                    echo CJSON::encode($data);
                    return;
                }
				echo '创建购物车失败';
				return;
			}
		}


		$product_model=OrdersProduct::model()->findByAttributes(['orders_product_orders_id'=>$cart->orders_id,'orders_product_product_id'=>$id]);
		if(is_null($product_model))//
		{
			$product_model=new OrdersProduct('create');
			$product_model->orders_product_product_id=$id;
			$product_model->orders_product_price=$product->product_price;
			$product_model->orders_product_orders_id=$cart->orders_id;
			$product_model->orders_product_count=1;
			$product_model->orders_product_currency+=$product->product_price;
		}
		else
		{
			$product_model->orders_product_count++;
			$product_model->orders_product_currency+=$product->product_price;
		}

		if($product_model->save())
		{
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['success']=true;
                $data['order']=$cart->toArray(['ordersMember']);
                echo CJSON::encode($data);
                return;
            }
			echo '添加到购物车成功';
			return;
		}
		else
		{
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['success']=false;
                echo CJSON::encode($data);
                return;
            }
			echo '添加到购物车失败';
			return;
		}
	}
	public function actionDelProduct($id)
	{
  
		$orderProduct=OrdersProduct::model()->findByPk($id);
		
		$cart=$orderProduct->ordersProductOrders;
		if(!user()->isAdmin() && $cart->orders_member_id!=user()->id)
		{
			throw new CHttpException(503,'没有权限');
		}
		$cart->orders_currency-=$orderProduct->orders_product_currency;
		$cart->save();
		$orderProduct->delete();
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['success']=true;
            $data['order']=$cart->toArray(['ordersMember']);
            echo CJSON::encode($data);
            return;
        }
		echo "删除订单产品成功";
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Orders::model()->findByPk($id);
		if(!user()->isAdmin() && $model->orders_member_id!=user()->id)
		{
			throw new CHttpException(503,'没有权限');
		}
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='orders-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionPrint($id){

           
          $data=yii::app()->db->createCommand()->select('*')->from('epmms_orders')->leftjoin('epmms_orders_product','epmms_orders.orders_id=epmms_orders_product.orders_product_orders_id')->leftjoin('epmms_product','epmms_product.product_id=epmms_orders_product.orders_product_product_id')->where('epmms_orders.orders_id='.$id)->queryAll(); 
       
     
           $member=Orders::model()->findByPk($id);
           $memberinfo=yii::app()->db->createCommand()->select('*')->from('epmms_memberinfo')->where('memberinfo_id='.$member->orders_member_id)->queryRow();
    
          	$this->render ( 'print', array(
				'data' => $data,
				'memberinfo'=>$memberinfo 		
		) );
          
   }
   public function actionPay(){
  

   	   /*  $data=yii::app()->db->createCommand()->select('*')->from('epmms_orders')->leftjoin('epmms_orders_product','epmms_orders.orders_id=epmms_orders_product.orders_product_orders_id')->leftjoin('epmms_product','epmms_product.product_id=epmms_orders_product.orders_product_product_id')->leftjoin('epmms_product_class','epmms_product_class.product_class_id=epmms_product.product_class_id')->where('orders_is_verify=0 and orders_id='.$id.' and orders_member_id='.user()->id)->queryAll();	 */ 
   	        $data=yii::app()->db->createCommand()->select('*')->from('epmms_orders')->leftjoin('epmms_orders_product','epmms_orders.orders_id=epmms_orders_product.orders_product_orders_id')->leftjoin('epmms_product','epmms_product.product_id=epmms_orders_product.orders_product_product_id')->leftjoin('epmms_product_class','epmms_product_class.product_class_id=epmms_product.product_class_id')->queryAll();	 
   	     /*   echo "<Pre>";
   	         var_dump($data);
   	         die;  */
   	     foreach ($data as $key => $value) {
   	     	 if($value['product_parent_id']!=3){
   	     	 	if($value['product_credit']!=0){
   	     	 	$data[$key]['jifen']=$value['product_credit'];
   	     	 	}
   	     	 }else{

   	     	 	$data[$key]['baodan']=$value['product_price'];
   	     	 }
   	         
   	    }
          if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $dat['success']=true;
            $dat['orderPay']=$data;
            echo CJSON::encode($dat);
            return;
        }
   }
 
}
