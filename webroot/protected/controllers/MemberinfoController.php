<?php

class MemberinfoController extends Controller
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
			'closeSite',
			'rights -getName',
			//'postOnly + delete', // we only allow deletion via POST request
			//'authentic + index,updatePassword,updatePasswordMy,verify,clean,verifyAll,update,updateMy,view',
            'accessControl +getName'
		);
	}
    public function allowedActions()
    {
        return 'login,error,logout,captcha,authentic,adminLogin,Adminlogin';
    }
    public function accessRules()
    {
        return [['deny',
            'actions'=>array('getName'),
            'users'=>array('?'),
        ]];
    }
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
    public function actionView($id=null)
    {
   
         $id=user()->id;
        $model=$this->loadModel($id);
 
        if (webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['success'] = true;
            $data['data']=$model->toArray(['memberinfoBank']);
            echo CJSON::encode($data);
            return;
        }
        $this->render('view',array(
            'model'=>$model
        ));
    }
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewShop($id=null)
	{
		if(!user()->isAdmin() && is_null($id))
			$id=user()->id;
		$this->render('viewShop',array(
			'model'=>$this->loadModel($id),
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{

       
		$model=new Memberinfo('create');

		$is_orders=params('ordersForm');
		if($is_orders)
			$model_order=new Orders('reg');

		if(!MemberinfoItem::model()->itemVisible('memberinfo_account'))
		{
            
			$model->memberinfo_account=$model->genUsername();
		}

		if(!MemberinfoItem::model()->itemVisible('memberinfo_password'))
		{
			$model->memberinfo_password='111111';
		}
		if(!MemberinfoItem::model()->itemVisible('memberinfo_password2'))
		{
			$model->memberinfo_password='222222';
		}
		$model->membermap=new Membermap('create');
		if(!user()->isAdmin())
		{
			//$model->membermap->membermap_parent_id=user()->id;
			//$model->membermap->membermap_recommend_id=user()->id;
		}
		if(isset($_POST['Membermap']))
		{

			$_POST['Membermap']['membermap_parent_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_parent_id']);
			$_POST['Membermap']['membermap_recommend_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_recommend_id']);
			if(params('regAgent'))
			{
               
				$agent_model = Agent::model()->findByAttributes(['agent_account' => $_POST['Membermap']['membermap_agent_id']]);

				if (is_null($agent_model))
				{
					$agent_id = Memberinfo::name2id(@$_POST['Membermap']['membermap_agent_id']);
                  
				}
				else
				{

					$agent_id = $agent_model->agent_memberinfo_id;
                   
				}
				$_POST['Membermap']['membermap_agent_id'] = $agent_id;
			}
			$_POST['Membermap']['membermap_bond_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_bond_id']);
			if(MemberType::model()->count()<=1)
			{
				$_POST['Membermap']['membermap_membertype_level']=MemberType::model()->find()->membertype_level;
			}
			if(webapp()->id=='180929')
            {
                $_POST['Membermap']['membermap_membertype_level']=1;
            }
        }

        if($is_orders)

            $this->performAjaxValidation([new Memberinfo('create'),new Membermap('create'),new Orders('reg')]);
        else
            $this->performAjaxValidation([new Memberinfo('create'),new Membermap('create')]);


        if(isset($_POST['Memberinfo']))
        {

  
            $transaction=webapp()->db->beginTransaction();
            $model->attributes=$_POST['Memberinfo'];
            $res=Memberinfo::model()->find('memberinfo_nickname='."'".$_POST['Memberinfo']['memberinfo_nickname']."'");
            if($res){
                 $model->memberinfo_type='会员号';
            }else{
                 $model->memberinfo_type='会员工资号';
            }
           // $model->memberinfo_name=$_POST['Memberinfo']['memberinfo_nickname'];

            if($model->save(true,array('memberinfo_account','memberinfo_password','memberinfo_type','memberinfo_password2','memberinfo_name','memberinfo_nickname','memberinfo_email','memberinfo_mobi','memberinfo_phone','memberinfo_qq','memberinfo_msn','memberinfo_sex','memberinfo_idcard_type','memberinfo_idcard','memberinfo_zipcode','memberinfo_birthday','memberinfo_address_provience','memberinfo_address_area','memberinfo_address_county','memberinfo_address_detail','memberinfo_bank_id','memberinfo_bank_name','memberinfo_bank_account','memberinfo_bank_provience','memberinfo_bank_area','memberinfo_bank_branch','memberinfo_question','memberinfo_answer','memberinfo_memo','memberinfo_is_enable','memberinfo_register_ip','memberinfo_last_ip','memberinfo_last_date','memberinfo_add_date','memberinfo_init_password','memberinfo_init_password2')))
            {
                if(isset($_POST['Membermap']))
                {

                    $model->membermap->attributes=$_POST['Membermap'];

                    $model->membermap->membermap_membertype_level_old=$model->membermap->membermap_membertype_level;
                    $model->membermap->membermap_reg_member_id=user()->isAdmin()?null:user()->id;
                    if(webapp()->id=='180821')
                    {
                        $parent_id=$model->membermap->membermap_parent_id;
                        $order=$model->membermap->membermap_order;
                    }
                    else
                    {
                       
                        if (MemberinfoItem::model()->getAdminItem('membermap_parent_id') == true && MemberinfoItem::model()->getAdminItem('membermap_order') == true)//false
                        {  

                            //太阳线，自动分配位置
                                      
                        }
                     
                    }

                    if(!params('regAgent'))
                    {
          
                        $model->memberinfo_is_agent=1;
                        $model->membermap->membermap_is_agent=1;
                        if(user()->isAdmin())
                            $model->membermap->membermap_agent_id=$model->membermap->membermap_recommend_id;
                        else
                        {
                            if(webapp()->id=='181128')
                            {
                               
                                $model->membermap->membermap_agent_id=$model->membermap->membermap_recommend_id;
                            }
                            else
                            {

                                $model->membermap->membermap_agent_id=user()->id;
                                // 推荐人审核 $model->membermap->membermap_agent_id=$model->membermap->membermap_recommend_id;
                            }
                        }

                    }
                    $model->membermap->membermap_id=$model->memberinfo_id;
                    if(webapp()->id=='180821')
                    {
                        $model->membermap->membermap_money = 10;
                        $model->membermap->membermap_membertype_level=1;
                    }

                    if($model->saveAttributes(['memberinfo_is_agent']) && $model->membermap->save())
                    {

                        if(webapp()->id=='141203')
                        {
                            $m2=new Membermap2('create');
                            $m4=new Membermap4('create');
                            if($model->membermap->membermap_membertype_level<=3)
                            {
                                $m2->membermap_bond_id=$model->memberinfo_id;
                                $m2->membermap_bond_info=$model->memberinfo_id;
                                $parent=Membermap2::model()->findByAttributes(['membermap_bond_id'=>$parent_id]);
                                $m2->membermap_parent_id=$parent->membermap_id;
                                $m2->membermap_order=$order;
                                $m2->membermap_name=$model->memberinfo_account;
                                $m2->membermap_membertype=$model->membermap->membermap_membertype_level;
                                if(!$m2->save())
                                {
                                    throw new EError('网络图生成错误');
                                }
                            }
                            else
                            {
                                $m4->membermap_member_id=$model->memberinfo_id;
                                $parent=Membermap4::model()->findByAttributes(['membermap_member_id'=>$parent_id]);
                                $m4->membermap_parent_id=$parent->membermap_id;
                                $m4->membermap_order=$order;
                                $m4->membermap_name=$model->memberinfo_account;
                                if(!$m4->save())
                                {
                                    throw new EError('网络图生成错误');
                                }
                            }
                        }
                        if($is_orders)
                        {
                           
                            //订单模式
                            if(isset($_POST['Orders']))
                            {
                                $model_order->attributes=$_POST['Orders'];
                                $model_order->orders_member_id=$model->memberinfo_id;
                                $model_order->orders_status="新订单";
                                $this->log['target']=$model_order->orders_id;
                                if($model_order->save(true,array('orders_status','orders_member_id','orders_remark')))
                                {
                                    if(isset($_POST['OrdersProduct']))
                                    {
                                        $valid=true;
                                        $total_currency=0;
                                        foreach($_POST['OrdersProduct'] as $id=>$product)
                                        {
                                            $product_model=new OrdersProduct('create');
                                            $product_model->attributes=$product;
                                            $product_model->orders_product_product_id=$id;
                                            $product=Product::model()->find('product_id=:id',[':id'=>$id]);
                                            $product_model->orders_product_price=$product->product_price;
                                            $product_model->orders_product_currency=$product->product_price*$product_model->orders_product_count;
                                            $total_currency+=$product_model->orders_product_currency;
                                            $product_model->orders_product_orders_id=$model_order->orders_id;
                                            $product->product_stock=$product->product_stock-$product_model->orders_product_count;
                                            $product->product_sales_amount=$product->product_sales_amount+$product_model->orders_product_count;
                                            $valid=$valid && $product_model->save() && $product->save();
                                        }
                                        if($valid)
                                        {
                                            $model_order->orders_currency=$total_currency;
                                            $model_order->scenario='reg';
                                            if($model_order->save(true,['orders_currency']) && $model_order->verify()==EError::SUCCESS)
                                            {
                                                $transaction->commit();
                                                user()->setFlash('success', "{$this->actionName}“{$model_order->showName}”" . t('epmms', "成功"));
                                                Sms::model()->sendRegister($model,$_POST['Memberinfo']['memberinfo_password'],$_POST['Memberinfo']['memberinfo_password2']);
                                                if(webapp()->request->isAjaxRequest)
                                                {
                                                    header('Content-Type: application/json');
                                                    $data['success']=user()->getFlash('success','注册成功',true);
                                                    $data['memberinfo']=$model->toArray();
                                                    unset($data['memberinfo']['memberinfo_password']);
                                                    unset($data['memberinfo']['memberinfo_password2']);
                                                    echo CJSON::encode($data);
                                                    webapp()->end();
                                                }
                                                $this->redirect(['memberinfo/verify','id'=>$model->memberinfo_id]);
                                                $this->render('view',array(
                                                    'model'=>$model,
                                                ));
                                                Yii::app()->end();
                                            }
                                            else
                                            {
                                                $this->log['status']=LogFilter::FAILED;
                                                $this->log();
                                                user()->setFlash('error',"{$this->actionName}“{$model_order->showName}”" . t('epmms',"失败"));
                                                $transaction->rollback();
                                            }
                                        }
                                        else
                                        {
                                            $this->log['status']=LogFilter::FAILED;
                                            $this->log();
                                            user()->setFlash('error',"{$this->actionName}“{$model_order->showName}”" . t('epmms',"失败"));
                                            $transaction->rollback();
                                        }
                                    }
                                }
                                else
                                {
                                    $this->log['status']=LogFilter::FAILED;
                                    $this->log();
                                    user()->setFlash('error',"{$this->actionName}“{$model_order->showName}”" . t('epmms',"失败"));
                                }
                            }
                            //处理订单结束
                        }
                        else
                        {

                            if(params('autoVerify') && webapp()->request->isAjaxRequest)
                            {
                                
                                header('Content-Type: application/json');
                                if(($status=$model->verify())===EError::SUCCESS)
                                {
                                    $this->log['status']=LogFilter::SUCCESS;
                                    $transaction->commit();
                                    user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                                    $data['success']=user()->getFlash('success','注册成功',true);
                                }elseif($status===EError::DUPLICATE)
                                {
                                    $this->log['status']=LogFilter::FAILED;
                                    user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,请不要重复审核"));
                                    $data['error']=user()->getFlash('error','注册失败',true);
                                    $transaction->rollback();
                                }
                                elseif($status===EError::NOMONEY)
                                {
                                    
                                    $this->log['status']=LogFilter::FAILED;
                                    user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,币不足"));
                                    $data['error']=user()->getFlash('error','注册失败',true);
                                    $transaction->rollback();
                                }
                                elseif($status===EError::NOVERIFY_AGENT)
                                {
                                    $this->log['status']=LogFilter::FAILED;
                                    user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,代理中心未审核"));
                                    $data['error']=user()->getFlash('error','注册失败',true);
                                    $transaction->rollback();
                                }
                                elseif($status===EError::NOPARENT)
                                {
                                    $this->log['status']=LogFilter::FAILED;
                                    user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,接点人无效或未审核"));
                                    $data['error']=user()->getFlash('error','注册失败',true);
                                    $transaction->rollback();
                                }
                                elseif($status===EError::NORECOMMEND)
                                {
                                    $this->log['status']=LogFilter::FAILED;
                                    user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,推荐人无效或未审核"));
                                    $data['error']=user()->getFlash('error','注册失败',true);
                                    $transaction->rollback();
                                }
                                elseif($status instanceof Exception)
                                {
                                    //throw $status;
                                    $this->log['status']=LogFilter::FAILED;
                                    user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',$status->getMessage()));
                                    $data['error']=user()->getFlash('error','注册失败',true);
                                    $transaction->rollback();
                                }
                                else
                                {
                                    $this->log['status']=LogFilter::FAILED;
                                    user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
                                    $data['error']=user()->getFlash('error','注册失败',true);
                                    $transaction->rollback();
                                }
                                $this->log();
                                $viewModel=new Memberinfo('search');
                                $viewModel->unsetAttributes();
                                $viewModel->memberinfo_id=$model->memberinfo_id;
                                $data['memberinfo']=$viewModel->search()->getArrayData();
                                unset($data['memberinfo']['memberinfo_password']);
                                unset($data['memberinfo']['memberinfo_password2']);
                                echo CJSON::encode($data);
                                webapp()->end();
                            }
                            //无订单模式
                            $transaction->commit();
                            user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                            Sms::model()->sendRegister($model,$_POST['Memberinfo']['memberinfo_password'],$_POST['Memberinfo']['memberinfo_password2']);
                            //不激活
                          
                            if(webapp()->request->isAjaxRequest)
                            {
                                header('Content-Type: application/json');
                                if(user()->hasFlash('success'))
                                {
                                    $data['success'] = user()->getFlash('success', '成功', true);
                                    $viewModel=new Memberinfo('search');
                                    $viewModel->unsetAttributes();
                                    $viewModel->memberinfo_id=$model->memberinfo_id;
                                    $data['memberinfo']=$viewModel->search()->getArrayData();
                                    unset($data['memberinfo']['memberinfo_password']);
                                    unset($data['memberinfo']['memberinfo_password2']);
                                }
                                elseif(user()->hasFlash('error'))
                                    $data['error']=user()->getFlash('error','失败',true);
                                else{
                                    $data=array_merge($model->getErrors(),$model->membermap->getErrors());
                                }
                                echo CJSON::encode($data);
                                webapp()->end();
                            }
                            if(params('autoVerify'))
                            {
                                $this->redirect(['memberinfo/verify','id'=>$model->memberinfo_id]);
                            }
                            $this->render('view',array(
                                'model'=>$model,
                            ));
                            Yii::app()->end();
                        }

                    }

                    //$transaction->rollback();
                    //throw new EError($model->membermap->errors[0]);
                }
            }
            else
            {
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data=array_merge($model->getErrors(),$model->membermap->getErrors());
                    echo CJSON::encode($data);
                    webapp()->end();
                }
            }

        }

        if(params('accountType')==1)
        {
            $model->memberinfo_account=$model->genUsername();
        }
        if(isset($_POST['Membermap']))
        {
            $model->membermap->attributes=$_POST['Membermap'];
        }
    
        //$model->memberinfo_password=null;
        $model->memberinfo_password2=null;
        //$model->memberinfo_password_repeat=null;
        $model->memberinfo_password_repeat2=null;
        $model->membermap->membermap_recommend_id=user()->id;//默认设置
     
        $form = new Form('application.views.memberinfo.createForm', $model);
        if($is_orders)
            $form['orders']->model=$model_order;
        $form['membermap']->model=$model->membermap;

        if(user()->isAdmin())
            $this->render('create',array('model'=>$model,'form'=>$form,'financeType'=>FinanceType::model()->findAll()));
        else
            $this->render('create',array('model'=>$model,'form'=>$form,'financeType'=>FinanceType::model()->findAll()));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateShop()
	{
		$model=new Memberinfo('create');
		if(!MemberinfoItem::model()->itemVisible('memberinfo_account'))
		{
			$model->memberinfo_account=$model->genUsername();
		}
		if(!MemberinfoItem::model()->itemVisible('memberinfo_password'))
		{
			$model->memberinfo_password='111111';
		}
		if(!MemberinfoItem::model()->itemVisible('memberinfo_password2'))
		{
			$model->memberinfo_password='222222';
		}
		$this->performAjaxValidation([new Memberinfo('create')]);
		if(isset($_POST['Memberinfo']))
		{
			$transaction=webapp()->db->beginTransaction();
			$model->attributes=$_POST['Memberinfo'];
			$model->memberinfo_is_agent=2;

			if(!$model->save(true,array('memberinfo_account','memberinfo_password','memberinfo_password2','memberinfo_name','memberinfo_nickname','memberinfo_email','memberinfo_mobi','memberinfo_phone','memberinfo_qq','memberinfo_msn','memberinfo_sex','memberinfo_idcard_type','memberinfo_idcard','memberinfo_zipcode','memberinfo_birthday','memberinfo_address_provience','memberinfo_address_area','memberinfo_address_county','memberinfo_address_detail','memberinfo_bank_id','memberinfo_bank_name','memberinfo_bank_account','memberinfo_bank_provience','memberinfo_bank_area','memberinfo_bank_branch','memberinfo_question','memberinfo_answer','memberinfo_memo','memberinfo_is_enable','memberinfo_register_ip','memberinfo_last_ip','memberinfo_last_date','memberinfo_add_date','memberinfo_is_agent','memberinfo_agent_id')))
			{
				$transaction->rollback();
				throw new Error('保存数据失败');
			}
			$transaction->commit();
			$this->render('viewShop',array(
				'model'=>$model,
			));
			Yii::app()->end();
		}
		if(params('accountType')==1)
		{
			$model->memberinfo_account=$model->genUsername();
		}
		$model->memberinfo_password=null;
		$model->memberinfo_password2=null;
		$model->memberinfo_password_repeat=null;
		$model->memberinfo_password_repeat2=null;
		$form = new Form('application.views.memberinfo.createFormShop', $model);
		$this->render('create',array('model'=>$model,'form'=>$form,'financeType'=>FinanceType::model()->findAll()));
	}
	/**
	 * ajax生成账户名
	 */
	public function actionAutoAccount()
	{
		$model=new Memberinfo('create');
		$model->memberinfo_account=$model->genUsername();
		$output=CHtml::activeHiddenField($model,'memberinfo_account');
		$output.=CHtml::resolveValue($model,'memberinfo_account');
		echo $output;
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
public function actionUpdateName($id=null){
     
                if(isset($_POST['Memberinfo'])){
                        $transaction=webapp()->db->beginTransaction();
                    
                        $name=$_POST['Memberinfo']['memberinfo_name'];
                        $card=$_POST['Memberinfo']['memberinfo_idcard'];
                            $model=Memberinfo::model()->findByPk($id);
                            $model->memberinfo_id=$id;       
                            $model->memberinfo_name=$name;
                            $model->memberinfo_idcard=$card;
                            $model->memberinfo_mod_date=new CDbExpression('now()');

                            if($model->update(array('memberinfo_id','memberinfo_mod_date','memberinfo_idcard','memberinfo_name'))){
                                
                                    $transaction->commit();
                                    $this->log['status']=LogFilter::SUCCESS;
                                    $this->log();                        
                                    if(webapp()->request->isAjaxRequest)
                                    {
                                        header('Content-Type: application/json');
                                        $data['success']=user()->getFlash('success','修改成功',true);
                                        echo CJSON::encode($data);
                                        webapp()->end();
                                    }

                            }else{    

                                $transaction->rollback();
                                $this->log['status']=LogFilter::FAILED;
                                $this->log();                       
                                if(webapp()->request->isAjaxRequest)
                                {
                                    header('Content-Type: application/json');
                                    $data['error']=user()->getFlash('error','修改失败',true);
                                    echo CJSON::encode($data);
                                    webapp()->end();
                                }

                            }
                            
                }

             
    
    
   
}





	public function actionUpdate($id=null)
	{
   /*$a=$_POST['Memberinfo'];

         var_dump(  $a->memberinfo_nickname );
         die;*/
		if(!user()->isAdmin() && is_null($id))
			$id=user()->id;
		$model=$this->loadModel($id);
		if($id!=user()->id && !user()->isAdmin())
		{

			if($model->membermap->membermap_agent_id!=user()->id || $model->membermap->membermap_is_verify==1)
			{
				throw new Error(t('epmms','代理中心不能修改已审核会员'),403);
			}
		}
		if(isset($_POST['Membermap']))
		{

			if(isset($_POST['Membermap']['membermap_parent_id']))
				$_POST['Membermap']['membermap_parent_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_parent_id']);
			if(isset($_POST['Membermap']['membermap_recommend_id']))
				$_POST['Membermap']['membermap_recommend_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_recommend_id']);
			/*$agent_model=Agent::model()->findByAttributes(['agent_account'=>$_POST['Membermap']['membermap_agent_id']]);
			if(is_null($agent_model))
			{
				$agent_id=Memberinfo::name2id(@$_POST['Membermap']['membermap_agent_id']);
			}
			else
			{
				$agent_id = $agent_model->agent_memberinfo_id;
			}
			$_POST['Membermap']['membermap_agent_id']=$agent_id;*/
			if(MemberType::model()->count()<=1)
			{
				$_POST['Membermap']['membermap_membertype_level']=MemberType::model()->find()->membertype_level;;
			}
		}
		$model->scenario='update';

		$this->performAjaxValidation([$model,$model->membermap]);
		if(isset($_POST['Memberinfo']))
		{


			$model->attributes=$_POST['Memberinfo'];
			$model->memberinfo_mod_date=new CDbExpression('now()');
			$transaction=webapp()->db->beginTransaction();

			if($model->save(true,array('memberinfo_account','memberinfo_name','memberinfo_nickname','memberinfo_email','memberinfo_mobi','memberinfo_phone','memberinfo_qq','memberinfo_msn','memberinfo_sex','memberinfo_idcard_type','memberinfo_idcard','memberinfo_zipcode','memberinfo_birthday','memberinfo_address_provience','memberinfo_address_area','memberinfo_address_county','memberinfo_address_detail','memberinfo_bank_id','memberinfo_bank_name','memberinfo_bank_account','memberinfo_bank_provience','memberinfo_bank_area','memberinfo_bank_branch','memberinfo_question','memberinfo_answer','memberinfo_memo','memberinfo_is_enable','memberinfo_mod_date')))
			{

			    if(params('fecshop'))
                {
                    $customer=Customer::model()->findByPk($model->memberinfo_id);
                    $customer->email=$model->memberinfo_account;
                    $customer->firstname=$model->memberinfo_name;
                    $customer->lastname=$model->memberinfo_nickname;
                    $customer->save();
                }

				if($model->membermap->primaryKey==1)
					$model->membermap->scenario='root';
				if(isset($_POST['Membermap']))
				{
					$model->membermap->attributes=$_POST['Membermap'];
					//$membertype=MemberType::model()->findByPk($model->membermap->membermap_membertype_level);
					//$model->membermap->membermap_money=$membertype->membertype_money;
					if($model->membermap->save(true,['membermap_is_empty','membermap_level','membermap_membertype_level','membermap_money']))
					{
						$transaction->commit();
						$this->log['status']=LogFilter::SUCCESS;
						$this->log();
						user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
						$this->redirect(array('view','id'=>$model->memberinfo_id));
					}
					else
					{
						$transaction->rollback();
						$this->log['status']=LogFilter::FAILED;
						$this->log();
						user()->setFlash('error',"修改会员“{$model->showName}”参数" . t('epmms',"失败"));
					}
				}
				else
				{
					$transaction->commit();
					$this->log['status']=LogFilter::SUCCESS;
					$this->log();
					user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
					$this->redirect(array('view','id'=>$model->memberinfo_id));
				}
			}
			else
			{

				$transaction->rollback();
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
		}
       
       
		$form = new Form('application.views.memberinfo.updateFormVerify', $model);

		$form['membermap']->model=$model->membermap;
		$this->render('update',array(
			'model'=>$model,
			'form'=>$form
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateShop($id=null)
	{
		if(!user()->isAdmin() && is_null($id))
			$id=user()->id;
		$model=$this->loadModel($id);
		$model->scenario='update';

		$this->performAjaxValidation([$model,$model->membermap]);
		if(isset($_POST['Memberinfo']))
		{
			$model->attributes=$_POST['Memberinfo'];
			$model->memberinfo_mod_date=new CDbExpression('now()');
			$transaction=webapp()->db->beginTransaction();
			if($model->save(true,array('memberinfo_account','memberinfo_name','memberinfo_nickname','memberinfo_email','memberinfo_mobi','memberinfo_phone','memberinfo_qq','memberinfo_msn','memberinfo_sex','memberinfo_idcard_type','memberinfo_idcard','memberinfo_zipcode','memberinfo_birthday','memberinfo_address_provience','memberinfo_address_area','memberinfo_address_county','memberinfo_address_detail','memberinfo_bank_id','memberinfo_bank_name','memberinfo_bank_account','memberinfo_bank_provience','memberinfo_bank_area','memberinfo_bank_branch','memberinfo_question','memberinfo_answer','memberinfo_memo','memberinfo_is_enable','memberinfo_mod_date')))
			{
				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->memberinfo_id));
			}
			else
			{
				$transaction->rollback();
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
		}
		$form = new Form('application.views.memberinfo.updateFormVerify', $model);
		$this->render('updateShop',array(
			'model'=>$model,
			'form'=>$form
		));
	}
	/**
	 * 修改个人资料
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateMy($id=null)
	{

		if(!user()->isAdmin() && is_null($id))
			$id=user()->id;
		$model=$this->loadModel($id);
		if(isset($_POST['Membermap']))
		{
			$_POST['Membermap']['membermap_parent_id']=Memberinfo::name2id($_POST['Membermap']['membermap_parent_id']);
			$_POST['Membermap']['membermap_recommend_id']=Memberinfo::name2id($_POST['Membermap']['membermap_recommend_id']);
			//$_POST['Membermap']['membermap_agent_id']=Memberinfo::name2id($_POST['Membermap']['membermap_agent_id']);
		}
		$model->scenario='update';
		$this->performAjaxValidation([$model,$model->membermap]);
		if(isset($_POST['Memberinfo']))
		{

			$model->attributes=$_POST['Memberinfo'];
			$model->memberinfo_mod_date=new CDbExpression('now()');
			$transaction=webapp()->db->beginTransaction();
     
	if($model->save(false,array('memberinfo_bank_name','memberinfo_password','memberinfo_phone','memberinfo_idcard','memberinfo_address_provience','memberinfo_address_area','memberinfo_address_county','memberinfo_address_detail','memberinfo_name','memberinfo_bank_id','memberinfo_bank_branch','memberinfo_bank_name','memberinfo_bank_account')))
			{
               
			    if(params('fecshop'))
                {
                    $customer=Customer::model()->findByPk($model->memberinfo_id);
                    $customer->email=$model->memberinfo_account;
                    $customer->firstname=$model->memberinfo_name;
                    $customer->lastname=$model->memberinfo_nickname;
                    $customer->save();
                }

				if($model->membermap->primaryKey==1)
					$model->membermap->scenario='root';
/*				if(isset($_POST['Membermap']))
				{
					if($model->membermap->save())
					{
						$transaction->commit();
						$this->log['status']=LogFilter::SUCCESS;
						$this->log();
						user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
						$this->redirect(array('view','id'=>$model->memberinfo_id));
					}
				}*/
				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['success']=user()->getFlash('success','修改成功',true);
                    echo CJSON::encode($data);
                    webapp()->end();
                }
				$this->redirect(array('view','id'=>$model->memberinfo_id));
			}
          
          
		    $transaction->rollback();
			$this->log['status']=LogFilter::FAILED;
			$this->log();
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
            if(webapp()->request->isAjaxRequest)
            {
                header('Content-Type: application/json');
                $data['error']=user()->getFlash('error','修改失败',true);
                echo CJSON::encode($data);
                webapp()->end();
            }
		}
		$form = new Form('application.views.memberinfo.updatemy', $model);
		$this->render('update',array(
			'model'=>$model,
			'form'=>$form,
			'isMy'=>true
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
		if($id==1)
		{
			$this->log['status']=LogFilter::FAILED;
			$this->log['target']=$model->showName;
			$this->log['info']='该会员是根会员，不能删除';
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"该会员是根会员，不能删除"));
		}
		elseif($model->membermap->membermap_is_verify==1)
		{
			$this->log['status']=LogFilter::FAILED;
			$this->log['target']=$model->showName;
			$this->log['info']='该会员已审核，不能删除';
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"该会员已审核，不能删除"));
		}
		else
		{
			$transaction=webapp()->db->beginTransaction();
			try
			{

/*                $customer=Customer::model()->findByPk($model->memberinfo_id);
                if($customer)
                    $customer->delete();*/
				if($map=Membermap::model()->findByPk($id))
					$map->delete();

				$this->log['target']=$model->showName;
				$model->delete();
				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
                        $this->log();
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['error']=user()->getFlash('error','修改成功',true);
                    echo CJSON::encode($data);
                    webapp()->end();
                }

			}
			catch(Exception $e)
			{
				$transaction->rollback();
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
                        $this->log();
                if(webapp()->request->isAjaxRequest)
                {
                    header('Content-Type: application/json');
                    $data['error']=user()->getFlash('error',$e->getMessage(),true);
                    echo CJSON::encode($data);
                    webapp()->end();
                }

			}
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		else
			echo user()->getFlash('error');
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('Memberinfo');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($isVerifyType=0)
	{
		$model=new Memberinfo('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberinfoBank=new Bank('search');
		$model->membermap=new Membermap('search');
		$model->membermap->unsetAttributes();
		if(isset($_GET['Memberinfo']))
		{
			$model->attributes=$_GET['Memberinfo'];
			if(isset($_GET['Memberinfo']['memberinfoBank']))
				$model->memberinfoBank->attributes=$_GET['Memberinfo']['memberinfoBank'];
			if(isset($_GET['Memberinfo']['membermap']))
			{
				$_GET['Memberinfo']['membermap']['membermap_recommend_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_recommend_id']);
				$_GET['Memberinfo']['membermap']['membermap_parent_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_parent_id']);
				$_GET['Memberinfo']['membermap']['membermap_bond_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_bond_id']);
				$_GET['Memberinfo']['membermap']['membermap_agent_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_agent_id']);
				$model->membermap->attributes=$_GET['Memberinfo']['membermap'];
			}
		}
		$model->membermap->membermap_is_verify=(int)$isVerifyType;
		$model->memberinfo_is_verify='<2';
		if(user()->role=='member')
		{
			$model->memberinfo_id=user()->id;
		}
    
		$this->render('index',array(
			'model'=>$model,
			'isVerifyType'=>(int)$isVerifyType,
			'isAgent'=>0
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionIndexShop($isVerifyType=0)
	{
		$model=new Memberinfo('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberinfoBank=new Bank('search');
		if(isset($_GET['Memberinfo']))
		{
			$model->attributes=$_GET['Memberinfo'];
			if(isset($_GET['Memberinfo']['memberinfoBank']))
				$model->memberinfoBank->attributes=$_GET['Memberinfo']['memberinfoBank'];
		}
		$model->memberinfo_is_agent=2;
		$model->memberinfo_is_verify=$isVerifyType;
		if(!user()->isAdmin())
			$model->memberinfo_agent_id=user()->id;
		if(user()->role=='member')
		{
			$model->memberinfo_id=user()->id;
		}
		$this->render('indexShop',array(
			'model'=>$model,
			'isVerifyType'=>(int)$isVerifyType,
			'isAgent'=>2
		));
	}
	/**
	 * 代理中心会员管理
	 */
	public function actionIndexAgent($isVerifyType=0)
	{

		$model=new Memberinfo('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberinfoBank=new Bank('search');
		$model->membermap=new Membermap('search');
		$model->membermap->unsetAttributes();
		$items=new MemberinfoItem();

		if(isset($_GET['Memberinfo']))
		{

			$model->attributes=$_GET['Memberinfo'];
			if(isset($_GET['Memberinfo']['memberinfoBank']))
				$model->memberinfoBank->attributes=$_GET['Memberinfo']['memberinfoBank'];
			if(isset($_GET['Memberinfo']['membermap']))
			{
				$_GET['Memberinfo']['membermap']['membermap_recommend_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_recommend_id']);
				$_GET['Memberinfo']['membermap']['membermap_parent_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_parent_id']);
				$model->membermap->attributes=$_GET['Memberinfo']['membermap'];
			}
		}
		$model->membermap->membermap_is_verify=(int)$isVerifyType;
		if(user()->role=='member')
		{
			$model->memberinfo_id=user()->id;
		}
		if(!user()->isAdmin())
			$model->membermap->membermap_agent_id=user()->id;
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $model->membermap->membermap_is_verify='';
            $data['memberinfo']=$model->search()->getArrayData();                             
            $memberType=new MemberType('search');
            $memberType->unsetAttributes();
            $data['memberType']=$memberType->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
		$this->render('index',array(
			'model'=>$model,
			'item'=>$items,
			'isVerifyType'=>(int)$isVerifyType,
			'isAgent'=>true
		));
	}

    /**
     * 代理中心会员管理
     */
    public function actionIndexAgentRecommend($isVerifyType=0)
    {
        $model=new Memberinfo('search');
        $model->unsetAttributes();  // clear any default values
        $model->memberinfoBank=new Bank('search');
        $model->membermap=new Membermap('search');
        $model->membermap->unsetAttributes();
        $items=new MemberinfoItem();

        if(isset($_GET['Memberinfo']))
        {
            $model->attributes=$_GET['Memberinfo'];
            if(isset($_GET['Memberinfo']['memberinfoBank']))
                $model->memberinfoBank->attributes=$_GET['Memberinfo']['memberinfoBank'];
            if(isset($_GET['Memberinfo']['membermap']))
            {
                $_GET['Memberinfo']['membermap']['membermap_recommend_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_recommend_id']);
                $_GET['Memberinfo']['membermap']['membermap_parent_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_parent_id']);
                $model->membermap->attributes=$_GET['Memberinfo']['membermap'];
            }
        }
        $model->membermap->membermap_is_verify=(int)$isVerifyType;
        if(user()->role=='member')
        {
            //$model->memberinfo_id=user()->id;

        }
        if(!user()->isAdmin())
        {
            $model->membermap->membermap_recommend_path="'" . user()->map->membermap_recommend_path . '/%' . "'" ;
            $model->membermap->membermap_recommend_layer=user()->map->membermap_recommend_layer+2;
            //$model->membermap->membermap_agent_id=user()->id;
        }

        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['memberinfo']=$model->search()->getArrayData();
            $memberType=new MemberType('search');
            $memberType->unsetAttributes();
            $data['memberType']=$memberType->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
        $this->render('index',array(
            'model'=>$model,
            'item'=>$items,
            'isVerifyType'=>(int)$isVerifyType,
            'isAgent'=>true
        ));
    }
    /**
     * 代理中心会员管理
     */
    public function actionIndexAgentRecommend2($isVerifyType=0)
    {
        $model=new Memberinfo('search');
        $model->unsetAttributes();  // clear any default values
        $model->memberinfoBank=new Bank('search');
        $model->membermap=new Membermap('search');
        $model->membermap->unsetAttributes();
        $items=new MemberinfoItem();

        if(isset($_GET['Memberinfo']))
        {
            $model->attributes=$_GET['Memberinfo'];
            if(isset($_GET['Memberinfo']['memberinfoBank']))
                $model->memberinfoBank->attributes=$_GET['Memberinfo']['memberinfoBank'];
            if(isset($_GET['Memberinfo']['membermap']))
            {
                $_GET['Memberinfo']['membermap']['membermap_recommend_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_recommend_id']);
                $_GET['Memberinfo']['membermap']['membermap_parent_id']=@Memberinfo::name2id($_GET['Memberinfo']['membermap']['membermap_parent_id']);
                $model->membermap->attributes=$_GET['Memberinfo']['membermap'];
            }
        }
        $model->membermap->membermap_is_verify=(int)$isVerifyType;
        if(user()->role=='member')
        {
            //$model->memberinfo_id=user()->id;

        }
        if(!user()->isAdmin())
        {
            $model->membermap->membermap_recommend_path="'" . user()->map->membermap_recommend_path . '/%' . "'" ;
            $model->membermap->membermap_recommend_layer=user()->map->membermap_recommend_layer+1;
            //$model->membermap->membermap_agent_id=user()->id;
        }

        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['memberinfo']=$model->search()->getArrayData();
            $memberType=new MemberType('search');
            $memberType->unsetAttributes();
            $data['memberType']=$memberType->search()->getArrayData();
            echo CJSON::encode($data);
            webapp()->end();
        }
        $this->render('index',array(
            'model'=>$model,
            'item'=>$items,
            'isVerifyType'=>(int)$isVerifyType,
            'isAgent'=>true
        ));
    }
	/**
	 * 查看推荐关系.
	 */
	public function actionIndexRecommend()
	{
		$model=new Memberinfo('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberinfoBank=new Bank('search');
		$model->membermap=new Membermap('search');
		$model->membermap->unsetAttributes();
		$items=new MemberinfoItem();
		if(isset($_GET['Memberinfo']))
		{
			$model->attributes=$_GET['Memberinfo'];
			if(isset($_GET['Memberinfo']['memberinfoBank']))
				$model->memberinfoBank->attributes=$_GET['Memberinfo']['memberinfoBank'];

		}
		$model->membermap->membermap_is_verify=1;
		if(!user()->isAdmin())
		{
			$model->membermap->membermap_recommend_id=user()->id;
		}
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data['order']=$model->search()->getArrayData();
            echo CJSON::encode($data);
            return;
        }
		$this->render('indexRecommend',array(
			'model'=>$model,
			'item'=>$items
		));
	}
	/**
	 * 查看接点关系.
	 */
	public function actionIndexOrder()
	{
		$model=new Memberinfo('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberinfoBank=new Bank('search');
		$model->membermap=new Membermap('search');
		$model->membermap->unsetAttributes();
		$items=new MemberinfoItem();
		if(isset($_GET['Memberinfo']))
		{
			$model->attributes=$_GET['Memberinfo'];
			if(isset($_GET['Memberinfo']['memberinfoBank']))
				$model->memberinfoBank->attributes=$_GET['Memberinfo']['memberinfoBank'];
		}
		$model->membermap->membermap_is_verify=1;
		if(!user()->isAdmin())
		{
			$model->membermap->membermap_parent_id=user()->id;
		}
		$this->render('indexRecommend',array(
			'model'=>$model,
			'item'=>$items
		));
	}
	/**
	 * 审核会员
	 * @param mixed $id
	 */
	public function actionVerify($id)
	{
        
		$model=$this->loadModel($id);
        if(isset($_POST['Membermap'])){
          
            $model->membermap->membermap_membertype_level=$_POST['Membermap']['membermap_membertype_level'];
            $model->membermap->save(true,'membermap_membertype_level');
        }
       /* echo"<Pre>";
        var_dump($model);
        die;*/
		$this->log['target']=$model->showName;
		if(($status=$model->verify())===EError::SUCCESS)
		{
			$this->log['status']=LogFilter::SUCCESS;
             if(($model->membermap->membermap_membertype_level==2) && empty($model->membermap->membermap_bond_id)){
                 $jackpotModel = new ConfigJackpot();
                 $jackpotModel->updateJackpot();
                 $activationModel = new ActivationRecord();
                 $activationModel->activation_member_id = $model->membermap->membermap_id;
                 $activationModel->activation_add_time  = date('Y-m-d H:i:s',time());
                 $activationModel->save();
            }
			user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
		}
		elseif($status===EError::DUPLICATE)
		{
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,请不要重复审核"));
		}
		elseif($status===EError::NOMONEY)
		{
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,激活金不足"));
		}
		elseif($status===EError::NOVERIFY_AGENT)
		{
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,代理中心未审核"));
		}
		elseif($status===EError::NOPARENT)
		{
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,接点人无效或未审核"));
		}
		elseif($status===EError::NORECOMMEND)
		{
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,推荐人无效或未审核"));
		}
		elseif($status instanceof Exception)
		{
			//throw $status; 
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',$status->getMessage()));
		}
		else
		{
            throw new EError($status);
            
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
		}
		$this->log();
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(user()->hasFlash('success'))
                $data['success']=user()->getFlash('success','审核成功',true);
            if(user()->hasFlash('error'))
                $data['error']=user()->getFlash('error','审核失败',true);
            echo CJSON::encode($data);
            webapp()->end();
        }
		$this->redirect([user()->role=='agent'?'indexAgent':'index'],true);
	}
	/**
	 * 审核会员
	 * @param mixed $id
	 */
	public function actionVerifyShop($id)
	{
		$model=$this->loadModel($id);
		$this->log['target']=$model->showName;
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
		elseif($status instanceof Exception)
		{
			//throw $status;
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',$status->getMessage()));
		}
		else
		{
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
		}
		$this->log();
		$this->redirect(['indexShop'],true);
	}
	/**
	 * 审核所有会员
	 * @param mixed $id
	 */
	public function actionVerifyAll()
	{
		ignore_user_abort(true);
		ini_set("max_execution_time", 0);
		set_time_limit(0);
		$models=Memberinfo::model()->findAll(['condition'=>'membermap.membermap_is_verify=0','order'=>'membermap.membermap_verify_date asc,membermap.membermap_verify_seq asc','with'=>['membermap']]);
		foreach($models as $model)
		{
			if(($status=$model->verify(true))===EError::SUCCESS)
			{
				$this->log['target']='全部未审核会员';
				$this->log['status']=LogFilter::SUCCESS;
				user()->setFlash('success',"{$this->actionName}" . t('epmms',"成功"));
			}
			elseif($status===EError::DUPLICATE)
			{
				$this->log['target']=$model->showName;
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,请不要重复审核"));
				break;
			}
			elseif($status instanceof Exception)
			{
				$this->log['target']=$model->showName;
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',$status->getMessage()));
				break;
			}
			else
			{
				$this->log['target']=$model->showName;
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
				break;
			}
		}
		$this->log();
		$this->redirect([user()->role=='agent'?'indexAgent':'index'],true);
	}
	/**
	 * 重新结算奖金
	 * @param mixed $id
	 */
	public function actionReVerify()
	{
		ignore_user_abort(true);
		ini_set("max_execution_time", 0);
		set_time_limit(0);
		$transaction=webapp()->db->beginTransaction();
		$proc=new DbCall('epmms_clean_award');
		$proc->run();
		$models=Memberinfo::model()->findAll(['condition'=>'membermap.membermap_is_verify=1 and membermap.membermap_id!=1','order'=>'membermap.membermap_verify_date asc,membermap.membermap_verify_seq asc,membermap.membermap_seq asc','with'=>['membermap']]);
		foreach($models as $model)
		{
			if(($status=$model->verify(true,8))===EError::SUCCESS)
			{
				continue;
			}
			elseif($status===EError::DUPLICATE)
			{
				$transaction->rollback();
				$this->log['target']=$model->showName;
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,请不要重复审核"));
				$this->log();
				$this->redirect([user()->role=='agent'?'indexAgent':'index'],true);
				break;
			}
			elseif($status instanceof Exception)
			{
				$transaction->rollback();
				$this->log['target']=$model->showName;
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',$status->getMessage()));
				$this->log();
				$this->redirect([user()->role=='agent'?'indexAgent':'index'],true);
				break;
			}
			else
			{
				$transaction->rollback();
				$this->log['target']=$model->showName;
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败") . $status);
				$this->log();
				$this->redirect([user()->role=='agent'?'indexAgent':'index'],true);
				break;
			}
		}
		$transaction->commit();
		$this->log['target']='全部未审核会员';
		$this->log['status']=LogFilter::SUCCESS;
		user()->setFlash('success',"{$this->actionName}" . t('epmms',"成功"));
		$this->log();
		$this->redirect([user()->role=='agent'?'indexAgent':'index'],true);
	}
	public function actionGen()
	{
		$gen=new GenMember();
		$gen->root=Memberinfo::id2name(1);
		$this->performAjaxValidation($gen);
		if(isset($_POST['GenMember']))
		{
			$gen->attributes=$_POST['GenMember'];
			if($gen->validate())
			{
				ini_set("max_execution_time", 0);
				set_time_limit(0);
				$model=$this->loadModel(Memberinfo::name2id($gen->root));
				$cnt=0;
				if($cnt=$model->genMember($gen->count))
				{
					$this->log['target']='会员生成';
					$this->log['status']=LogFilter::SUCCESS;
					user()->setFlash('success',"生成{$cnt}个会员" . t('epmms',"成功"));
					$this->log();
					$this->redirect([user()->role=='agent'?'indexAgent':'index'],true);
				}
				else
				{
					$this->log['target']='会员生成';
					$this->log['status']=LogFilter::FAILED;
					user()->setFlash('error',"生成会员失败");
					$this->log();
				}
			}
		}
		$this->render('gen',['model'=>$gen]);
	}

	/**
	 * 清空奖金
	 */
	public function actionCleanAward()
	{
		//清空系统备份
		$bak=new Backup();
		//清空前备份
		$bak->autoBackup('清空奖金',webapp()->format->formatdatetime(time()));
		//清空数据
		$proc=new DbCall('epmms_clean_award2');
		$proc->run();
		user()->setFlash('success',"{$this->actionName}" . t('epmms',"成功"));
		$this->log['target']=null;
		$this->log['status']=LogFilter::SUCCESS;
		$this->log();
		$this->redirect(['index'],true);
	}
	/**
	 * 清空数据
	 * @param int $includeMember 是否包含会员
	 */
	public function actionClean($includeMember=0)
	{
		$model=new CleanData;
		$this->performAjaxValidation($model);

		if(isset($_POST['CleanData']))
		{
			$model->attributes=$_POST['CleanData'];
			$model->clean();
			user()->setFlash('success',"{$this->actionName}" . t('epmms',"成功"));
			$this->log['target']=null;
			$this->log['status']=LogFilter::SUCCESS;
			$this->log();
			$this->redirect(['clean'],true);
		}
		$this->render('cleanDataForm',['model'=>$model]);
	}
	public function actionUpdatePassword($id=null)
	{
        
		$isMy=false;
		if(is_null($id) && !user()->isAdmin())
		{
			$id=user()->id;
			$isMy=true;
		}
		$model=Memberinfo::model()->findByPk($id);

		$model->scenario = 'updatePassword';
		$model->memberinfo_password=null;
		$model->memberinfo_password2=null;
      
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Memberinfo']))
		{
           
			$model->attributes=$_POST['Memberinfo'];
            $model->memberinfo_init_password=$_POST['Memberinfo']['memberinfo_password'];
            $model->memberinfo_init_password2=$_POST['Memberinfo']['memberinfo_password2'];
			if($model->save(true,['memberinfo_password','memberinfo_password2','memberinfo_init_password','memberinfo_init_password2']))
			{
                // $customer=Customer::model()->findByPk($model->memberinfo_id);
                // $customer->password_hash=$model->memberinfo_password;
                // $customer->save();
				user()->setFlash('success',"修改密码" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->memberinfo_id),true);
			}
			else
				user()->setFlash('error',"修改密码" . t('epmms',"失败"));
		}

		$this->render('updatePassword',array(
			'model'=>$model,
			'isMy'=>$isMy
		));
	}
	public function actionUpdatePasswordMy()
	{

        $id=user()->id;
        $model=$model=Memberinfo::model()->findByPk($id);
        $model->scenario = 'updatePassword';
        $model->memberinfo_password=null;
        $model->memberinfo_password2=null;
        $model->memberinfo_password_repeat=null;
        $model->memberinfo_password_repeat2=null;
        $passwordAuth=new UpdatePasswordAuth();
        // Uncomment the following line if AJAX validation is needed

        $this->performAjaxValidation($model,$passwordAuth);

        if(isset($_POST['UpdatePasswordAuth']))
        {
            
            $passwordAuth->attributes=$_POST['UpdatePasswordAuth'];

            // try {

                if($passwordAuth->validate())
                {

                    if(isset($_POST['Memberinfo']))
                    {
                        $model->attributes=$_POST['Memberinfo'];
                        if(!empty($_POST['Memberinfo']['memberinfo_password']))
                        {
                            $attrs[]='memberinfo_password';
                            $attrs[]='memberinfo_init_password';
                           $model->memberinfo_init_password=$_POST['Memberinfo']['memberinfo_password'];
                        }
                        if(!empty($_POST['Memberinfo']['memberinfo_password2']))
                        {
                            $attrs[]='memberinfo_password2';
                            $attrs[]='memberinfo_init_password2';
                            $model->memberinfo_init_password2=$_POST['Memberinfo']['memberinfo_password2'];
                        }
                        if(empty($attrs))
                        {
                            if(webapp()->request->isAjaxRequest)
                            {
                                header('Content-Type: application/json');
                                $data['error']='修改密码失败,请输入密码';
                                echo CJSON::encode($data);
                                webapp()->end();
                            }
                            user()->setFlash('error',t('epmms',"修改密码失败,请输入密码"));

                        }else
                        {

                            if($model->save(true,$attrs))
                            {
                                user()->setFlash('success',"修改密码" . t('epmms',"成功"));
                                if(webapp()->request->isAjaxRequest)
                                {
                                    header('Content-Type: application/json');
                                    $data['success']=user()->getFlash('success','修改密码成功',true);
                                    echo CJSON::encode($data);
                                    webapp()->end();
                                }
                                $this->redirect(array('view','id'=>$model->memberinfo_id),true);
                            }
                            else
                            {
                                user()->setFlash('error',"修改密码" . t('epmms',"失败"));
                                if(webapp()->request->isAjaxRequest)
                                {
                                    header('Content-Type: application/json');
                                    $data=array_merge($model->getErrors(),$passwordAuth->getErrors());
                                    $data['error']=user()->getFlash('error','修改密码失败',true);
                                    echo CJSON::encode($data);
                                    webapp()->end();
                                }
                            }
                        }


                    }
                }
            // }catch(\Exception $e){
            //     print_r($e->getMessage());
            // }

        }
        if(webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $data=array_merge($model->getErrors(),$passwordAuth->getErrors());
            $data['error']='原密码输入错误';
            echo CJSON::encode($data);
            webapp()->end();
        }
        $this->render('updatePassword',array(
            'model'=>$model,
            'passwordAuth'=>$passwordAuth,
            'isMy'=>true
        ));
	}
	public function actionLeafs()
	{
		if(isset($_REQUEST['Membermap']['membermap_recommend_id']))
		{
			$account=$_REQUEST['Membermap']['membermap_recommend_id'];
			$id=Memberinfo::name2id($account);
			$ret=['leaf'=>'','area'=>''];
			if(!empty($id))
			{
				$ret['leaf']=MemberinfoItem::getLeafs($id);
				if(user()->id==$id)
				{
					$ret['area']='登录会员';
				}
				else if(Membermap::model()->exists("membermap_path like :cur_path || '/1%' and membermap_id=:id",[':cur_path'=>user()->map->membermap_path,':id'=>$id]))
				{
					$ret['area']='登录会员的A区';
				}
				else if(Membermap::model()->exists("membermap_path like :cur_path || '/2%' and membermap_id=:id",[':cur_path'=>user()->map->membermap_path,':id'=>$id]))
				{
					$ret['area']='登录会员的B区';
				}
			}
			header('Content-type: application/json');
			echo CJSON::encode($ret);
			Yii::app()->end();
		}
	}
	public function actionGetName($account)
    {

         $model=Memberinfo::model()->findByAttributes(['memberinfo_account'=>$account]);
        if($model){
             if(webapp()->request->isAjaxRequest){
                header('Content-Type: application/json');
                echo CJSON::encode($model->memberinfo_nickname);
                return;
            }
            echo $model->memberinfo_nickname;
            return;
        } else{
            echo '';
           return;
        }
    }
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
    // echo $id;
    // echo user()->id;
    // var_dump($model->membermap->membermap_agent_id);
    // die;
        $model=Memberinfo::model()->findByPk($id,array('with'=>'membermap'));
		if($model===null)
			throw new CHttpException(404,t('epmms','请求的页面不存在。'));
		if(!$model->hasRelated('membermap') || is_null($model->membermap))
			$model->membermap=new Membermap();
		if($model->membermap->primaryKey==1)
			$model->membermap->scenario='root';
		if(!user()->isAdmin() && $id!=user()->id)
		{
			if($model->membermap->membermap_agent_id!=user()->id)
			{
				throw new Error(t('epmms','没有权限'),403);
			}
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='memberinfo-form')
		{
            
       
			echo ActiveForm::validate($model);
           
			Yii::app()->end();
		}
	}
        public function actionGetLayer3()
    {
        if(true||webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            $sort=new Sort('Memberinfo');
            $sort->defaultOrder=array('memberinfo_id'=>Sort::SORT_ASC);
            $criteria=new CDbCriteria;
            $criteria->addCondition('"membermap".membermap_path like ' . "'" . user()->map->membermap_path . '/%'  . "'");
            $criteria->compare('"membermap".membermap_layer',user()->map->membermap_layer+1);
            $criteria->with=array('memberinfoBank',
                'membermap',
                'membermap.membermapParent',
                'membermap.membermapRecommend',
                'membermap.membermapAgent',
                'membermap.membermapbond',
                'membermap.membermapMembertypeLevel',
                'membermap.memberlevel');
            $page=0;
            $pageSize=20;
            if(isset($_GET['page']))
                $page=$_GET['page']-1;
            if(isset($_GET['limit']))
                $pageSize=$_GET['limit'];
            $search=new JSonActiveDataProvider(user()->info, array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                ),
                'includeDataProviderInformation'=>true,
                'relations'=>['memberinfoBank',
                    'memberinfoBank',
                    'membermap'=>['relations'=>[
                        'membermapParent'=>['relations'=>'memberinfo'],
                        'membermapRecommend'=>['relations'=>'memberinfo'],
                        'membermapAgent'=>['relations'=>'memberinfo'],
                        //'membermapbond'=>['relations'=>'memberinfo'],
                        'membermapMembertypeLevel',
                        'memberlevel'
                    ]]
                ]
            ));

            $criteria2=new CDbCriteria;
            $criteria2->addCondition('"membermap".membermap_path like ' . "'" . user()->map->membermap_path . '/%'  . "'");
            $criteria2->compare('"membermap".membermap_layer',user()->map->membermap_layer+2);
            $criteria2->with=array('memberinfoBank',
                'membermap',
                'membermap.membermapParent',
                'membermap.membermapRecommend',
                'membermap.membermapAgent',
                'membermap.membermapbond',
                'membermap.membermapMembertypeLevel',
                'membermap.memberlevel');
            $search2=new JSonActiveDataProvider(user()->info, array(
                'criteria'=>$criteria2,
                'sort'=>$sort,
                'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                ),
                'includeDataProviderInformation'=>true,
                'relations'=>['memberinfoBank',
                    'memberinfoBank',
                    'membermap'=>['relations'=>[
                        'membermapParent'=>['relations'=>'memberinfo'],
                        'membermapRecommend'=>['relations'=>'memberinfo'],
                        'membermapAgent'=>['relations'=>'memberinfo'],
                        //'membermapbond'=>['relations'=>'memberinfo'],
                        'membermapMembertypeLevel',
                        'memberlevel'
                    ]]
                ]
            ));
            $criteria3=new CDbCriteria;
            $criteria3->addCondition('"membermap".membermap_path like ' . "'" . user()->map->membermap_path . '/%'  . "'");
            $criteria3->compare('"membermap".membermap_layer',user()->map->membermap_layer+3);
            $criteria3->with=array('memberinfoBank',
                'membermap',
                'membermap.membermapParent',
                'membermap.membermapRecommend',
                'membermap.membermapAgent',
                'membermap.membermapbond',
                'membermap.membermapMembertypeLevel',
                'membermap.memberlevel');
            $search3=new JSonActiveDataProvider(user()->info, array(
                'criteria'=>$criteria3,
                'sort'=>$sort,
                'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                ),
                'includeDataProviderInformation'=>true,
                'relations'=>['memberinfoBank',
                    'memberinfoBank',
                    'membermap'=>['relations'=>[
                        'membermapParent'=>['relations'=>'memberinfo'],
                        'membermapRecommend'=>['relations'=>'memberinfo'],
                        'membermapAgent'=>['relations'=>'memberinfo'],
                        //'membermapbond'=>['relations'=>'memberinfo'],
                        'membermapMembertypeLevel',
                        'memberlevel'
                    ]]
                ]
            ));
            $data['layer1']=$search->getArrayData();
            $data['layer2']=$search2->getArrayData();
            $data['layer3']=$search3->getArrayData();
            $memberType=new MemberType('search');
            $memberType->unsetAttributes();
            $data['memberType']=$memberType->search()->getArrayData();
            echo CJSON::encode($data);
            return;
        }
    }
    public function actionTeam(){
        $parents = Membermap::model()->findByPk(user()->id);    
        $dat['a']=Membermap::model()->count("membermap_order=1 and membermap_path like '$parents->membermap_path%'");
        $dat['b']=Membermap::model()->count("membermap_order=2 and membermap_path like '$parents->membermap_path%'");       
        if($dat){
            if(webapp()->request->isAjaxRequest){
            header('Content-Type: application/json');
                $data['success']=true;
                $data['team']=$dat;
                echo CJSON::encode($data);
                return;
            }
        }     
    }
}