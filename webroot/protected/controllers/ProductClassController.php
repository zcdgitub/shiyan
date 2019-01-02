<?php

class ProductClassController extends Controller
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	    $model=$this->loadModel($id);
        if (webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(empty($model))
            {
                echo CJSON::encode(['success'=>false,'msg'=>'您还没有添加产品分类']);
                return;
            }

            $data=$model->toArray();
            echo CJSON::encode($data);
            return;
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
		$model=new ProductClass('create');
        /*	$model=ProductClass::model()->findByAttributes(['product_parent_id'=>0]);*/
		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);


		if(isset($_POST['ProductClass']))
		{
			
		
			$model->attributes=$_POST['ProductClass'];
			$this->log['target']=$model->product_name;
		
			if($model->save(true,array('product_name','product_info','product_parent_id')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->product_class_id));
			}
			else
			{
				$this->log['status']=LogFilter::FAILED;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{

	
		  $res=ProductClass::model()->findByAttributes(['product_class_id'=>$id]);
         if($res->product_parent_id==0){
		     	 user()->setFlash('error','禁止编辑');
		     	 $this->redirect(['ProductClass/index']);
		     }


		$model=$this->loadModel($id);
		$model->scenario='update';
		// 如果需要AJAX验证反注释下面一行
		// $this->performAjaxValidation($model);

		if(isset($_POST['ProductClass']))
		{

			$model->attributes=$_POST['ProductClass'];
			$this->log['target']=$model->product_name;
			if($model->save(true,array('product_name','product_info','product_parent_id')))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model->product_class_id));
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		
        $res=ProductClass::model()->findByAttributes(['product_class_id'=>$id]);

		     if($res->product_parent_id==0){
		     	 user()->setFlash('error','禁止删除');

		     	 $this->redirect(['ProductClass/index']);
		     }
 

		$model=$this->loadModel($id);
		$this->log['target']=$model->showName;
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

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		
		$parent=Productclass::model()->findAll('product_parent_id=0');

		foreach ($parent as $key => $value) {
			    $w[]=$value->toArray();
		}
	
		foreach ($w as $key => $value) {
			    $w[$key]['son']=yii::app()->db->createCommand('select product_class_id,product_name,product_parent_id from epmms_product_class where product_parent_id='.$value['product_class_id'])->queryAll();
		}

	
		$dataProvider=new CActiveDataProvider('ProductClass');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		
	 // $data=yii::app()->db->createCommand("select p.* from epmms_membermap as p,epmms_membermap as my"->where("my.membermap_id=".user()->id ."and p.membermap_recommend_path like my.membermap_recommend_path || '/%'  and p.membermap_recommend_id= ".user()->id)->queryAll();

	 	// $data=yii::app()->db->createCommand("select p.* from epmms_membermap as p,epmms_membermap as my where  my.membermap_id=".user()->id ."and p.membermap_recommend_path like my.membermap_recommend_path || '/%'  and p.membermap_recommend_id= ".user()->id)->queryAll();
   //    echo "<pre>";
   //    var_dump($data);
   //    die;
		
	/*$dat=Yii::app()->db->createCommand()
                 ->select('epmms_product_class.*')
                 ->from('epmms_product_class')
                 ->where('product_parent_id=0')
                 ->queryAll();
		foreach ($dat as $key => $value) {
			    $date[$value['product_class_id']]=$value;
			    $date[$value['product_class_id']]['product']=yii::app()->db->createCommand('select*from epmms_product_class where product_parent_id='.$value['product_class_id'])->queryAll(); 
			foreach ($date[$value['product_class_id']]['product'] as $key => $val) {
				$date[$value['product_class_id']]['product'][$key]['products']=yii::app()->db->createCommand()->select('*,epmms_product.product_name as pname,epmms_product_class.product_name as pclsname')->from('epmms_product')->leftjoin('epmms_product_class','epmms_product.product_class_id=epmms_product_class.product_class_id')->where('epmms_product_class.product_class_id='.$val['product_class_id'])->queryAll();
			}

		}
		echo "<Pre>";
		var_dump($date);
		die;*/
		$model=new ProductClass('search');

	
		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['ProductClass']))
		{
			$model->attributes=$_GET['ProductClass'];
			
		}

        if(webapp()->request->isAjaxRequest)
        {
           
            header('Content-Type: application/json');
      
 		$dat=Yii::app()->db->createCommand()
                 ->select('epmms_product_class.*')
                 ->from('epmms_product_class')
                 ->where('product_parent_id=0')
                 ->queryAll();
		foreach ($dat as $key => $value) {
			    $date[$value['product_class_id']]=$value;
			    $date[$value['product_class_id']]['product']=yii::app()->db->createCommand('select*from epmms_product_class where product_parent_id='.$value['product_class_id'])->queryAll(); 
			foreach ($date[$value['product_class_id']]['product'] as $key => $val) {
				$date[$value['product_class_id']]['product'][$key]['products']=yii::app()->db->createCommand()->select('*')->from('epmms_product')->leftjoin('epmms_product_class','epmms_product.product_class_id=epmms_product_class.product_class_id')->where('epmms_product_class.product_class_id='.$val['product_class_id'])->queryAll();
			}

		}

            $data['buy']= $date;
           /* $productClass=ProductClass::model()->findAll();
            $arrayProductClass=[];
            foreach($productClass as $oClass)
            {
            	$arrayOClass=$oClass->toArray();
            	$arrayOClass['products']=[];
            	foreach(Product::model()->findAll('product_class_id=:id',[':id'=>$oClass->product_class_id]) as $oProduct)
            	{
            		$arrayOClass['products'][$oProduct->product_id]=$oProduct->toArray();
            	}
            	$arrayProductClass[$oClass->product_class_id]=$arrayOClass;

            }
            $data['productClass']=$arrayProductClass;*/
            echo CJSON::encode($data);
            return;
        }
		$this->render('index',array(
			'model'=>$model,
			'selTab'=>(int)$selTab
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ProductClass::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='product-class-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	
}
