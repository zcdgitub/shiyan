<?php
class ProductController extends Controller
{
	/**
	 *
	 * @var string the default layout for the views. Defaults to
	 *      '//layouts/column2', meaning
	 *      using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';
	
	/**
	 *
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
		    'cors',
			'closeSite',
			'rights',
			//'postOnly + delete,cropzoom',  // we only allow deletion via POST request
			'authentic + update,create,delete',//需要二级密码
		);
	}
	
	/**
	 * Displays a particular model.
	 * 
	 * @param integer $id
	 *        	the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		
        $model=$this->loadModel ( $id );
        if (webapp()->request->isAjaxRequest)
        {
            header('Content-Type: application/json');
            if(empty($model))
            {
                echo CJSON::encode(['success'=>false,'msg'=>'您还没有添加产品']);
                webapp()->end();
            }

            $data=$model->toArray();
            echo CJSON::encode($data);
            webapp()->end();
        }
		$this->render ( 'view', array(
				'model' => $model
		) );
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view'
	 * page.
	 */
	public function actionCreate()
	{

/*
    $p = new PhpStringParser();
		$menu_items = array();
		$menu = new MenuNav ();
		$parent_menu = $menu->findAll ( array(
			'select' => 'menu_id,menu_name,menu_url',
			'condition' => 'menu_pid is null',
			'order' => 'menu_order asc'
		) );

		foreach ( $parent_menu as $menu_item )
		{
			$child_menu=$menu->findAll(array(
					'select'=>'menu_id,menu_name,menu_url',
					'condition'=>'menu_pid=:menu_pid',
					'order'=>'menu_order asc',
					'params'=>array('menu_pid'=>$menu_item->menu_id)
				)
			);
		
			$child_menu_items=array();
			foreach($child_menu as $child_menu_item)
			{
				
			
				if(webapp()->user->checkAccess($this->parseUrl($child_menu_item->menu_url),['nav'=>true]))
				{

					$child_menu_items[]=array(
						'label'=>$p->parse($child_menu_item->menu_name),
						'url'=>array($child_menu_item->menu_url)
					);
				}
			}
		
			if($child_menu_items!==array())
			{
       
				if($menu_item->menu_name=='产品中心')
				{
					$all_class=ProductClass::model()->findAll();
				
					foreach($all_class as $one_class)
					{
						$child_menu_items[] = array('label'=>$p->parse($one_class->product_name),'url'=>array('product/index/' . $one_class->product_class_id));
					}
				}
				$menu_items [] = array(
					'label' => $p->parse($menu_item->menu_name),
					'items'=>$child_menu_items,
					'url'=>array($menu_item->menu_url)
				);
			}
		}

var_dump($menu_items);
die;*/
		$model = new Product ();

        $model1 = new ProductClass();
 
	     foreach (ProductClass::model()->findAll() as $key => $value) {
	     	$data[]=$value->toArray();
	     }
        $trees= $model1->tree($data,0,1);

	    foreach($trees as $k=> $v) {
	    	 if($v['product_parent_id']==0){
	    	   
	    	    $trees[$k]['product_name']=str_repeat('----',$v['lev']).$v['product_name'].str_repeat('--',$v['lev']); 
	    	  }
	    }
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if (isset ( $_POST ['Product'] ))
		{
			
			$model->attributes = $_POST ['Product'];
			if ($model->save ( true, array(
				'product_name',
				'product_title',
				'product_price',
				'product_info',
				'product_image_url',
				'product_stock',
				'product_sale_status',
				'product_class_id',
				'product_cost',
				'product_credit'
			) ))
				$this->redirect ( array(
						'view',
						'id' => $model->product_id 
				) );
		}
		/*print_r($model->getErrors());*/
		$this->render ( 'create', array(
				'model' => $model,
				/*'model1'=>$model1,*/
				'data'=>$trees
		) );
	}




	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view'
	 * page.
	 * 
	 * @param integer $id
	 *        	the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		
		$model = $this->loadModel ( $id );
		  $model1 = new ProductClass();
 
	     foreach (ProductClass::model()->findAll() as $key => $value) {
	     	$data[]=$value->toArray();
	     }
        $trees= $model1->tree($data,0,1);
	    foreach($trees as $k=> $v) {
	    	 if($v['product_parent_id']!=0){
	    	        continue;
	    	  }
	    	$trees[$k]['product_name']=str_repeat('----',$v['lev']).$v['product_name'].str_repeat('--',$v['lev']); 
	    }
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if (isset ( $_POST ['Product'] ))
		{
			$model->attributes = $_POST ['Product'];
			if ($model->save (true, array(
				'product_name',
				'product_title',
				'product_price',
				'product_info',
				'product_image_url',
				'product_stock',
				'product_sale_status',
				'product_cost',
				'product_credit',
				'product_class_id'
			)))
				$this->redirect ( array(
						'view',
						'id' => $model->product_id 
				) );
		}
		$this->render ( 'update', array(
				'model' => $model,
				'model1'=>$model1,
				'data'=>$trees

		) );
	}
	
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin'
	 * page.
	 * 
	 * @param integer $id
	 *        	the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{

		
		$this->loadModel ( $id )->delete ();
		
		// if AJAX request (triggered by deletion via admin grid view), we
		// should not redirect the browser
		if (! isset ( $_GET ['ajax'] ))
			$this->redirect ( isset ( $_POST ['returnUrl'] ) ? $_POST ['returnUrl'] : array(
					'admin' 
			) );
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex($class=null)
	{

	  	$page=0;
        $pageSize=10;
        if(isset($_GET['page']))
            $page=$_GET['page']-1;
         if(isset($_GET['Product_page']))
            $page=$_GET['Product_page']-1;
        if(isset($_GET['limit']))
            $pageSize=$_GET['limit'];
		$condition_class='';
		if(!is_null($class))
		{
			$condition_class=" and product_class_id=$class";
		}
		
        if(webapp()->request->isAjaxRequest)
        {
            
        
            $dataProvider = new JSonActiveDataProvider ( 'Product' , array(
                'criteria'=>array(
                    'condition'=>'product_sale_status=1' . $condition_class,
                    'order'=>'product_mod_date DESC'
                ),
                   'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                    'validateCurrentPage'=>false
                )

            ));
            $data['buy']=$dataProvider->getArrayData();
         /*   echo "<Pre>";
            var_dump(CJSON::encode($data));
            die;*/
            echo CJSON::encode($data);
            return;
        }
		$dataProvider = new CActiveDataProvider ( 'Product' , array(
			'criteria'=>array(
				'condition'=>'product_sale_status=1' . $condition_class,
				'order'=>'product_mod_date DESC'
			),
			 'pagination'=>array(
                    'currentPage'=>$page,
                    'pageSize'=>$pageSize,
                    'validateCurrentPage'=>false
                )
		));
 
		$this->render ( 'index', array(
				'dataProvider' => $dataProvider
			) );
	}
	
	public function actionShort(){
 
		

		$model = new Product ( 'search' );

		$model->productClass=new ProductClass('search');
		$model->unsetAttributes (); // clear any default values
		$model->productClass->unsetAttributes();
		if (isset ( $_GET ['Product'] )){

              
			$model->attributes = $_GET ['Product'];
			if(isset($_GET['Product']['productClass']))
			{
				$model->productClass->attributes=$_GET['Product']['productClass'];
			}
		
		  }
		    $model->product_stock="<=0";

		$this->render ( 'short', array(
				'model' => $model 
		) );
	}
	
	 
	public function actionAdmin()
	{
		$model = new Product ( 'search' );
	
		//$model->productClass=new ProductClass('search');
		$model->unsetAttributes (); // clear any default values
		$model->productClass=new ProductClass('search');
		//$model->productClass->unsetAttributes();
		if (isset ( $_GET ['Product'] ))
		{
			$model->attributes = $_GET ['Product'];
			if(isset($_GET['Product']['productClass']))
			{
				$model->productClass->attributes=$_GET['Product']['productClass'];
			}
		}
		
		$this->render ( 'admin', array(
				'model' => $model 
		) );
	}
	public    function  actionGetProduct(){
		
		
        if($_POST['Product']){      
                $name=trim($_POST['Product']['product']);

	      
	              $data=Product::model()->findAll(['condition'=>"product_title like '%' || :name || '%'",'params'=>[':name'=>$name]]);
	           
	            if($data){
	            	foreach ($data as $key => $value) {
	            	$dat[]=$value->toArray();
	            	}    
		          	if(webapp()->request->isAjaxRequest){
			            header('Content-Type: application/json');			            
			            echo CJSON::encode($dat);
			            return;
			        }
	            }else{
	            	if(webapp()->request->isAjaxRequest){
			             header('Content-Type: application/json');	
			             $data['success']=false;
			             $data['msg']='商品不存在';
			              echo CJSON::encode($data);
			              return;	             
          			}
          			echo "商品不存在";
          			return;
	            }
  
       }
          
			
	}
	/**
	 * Returns the data model based on the primary key given in the GET
	 * variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * 
	 * @param
	 *        	integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model = Product::model ()->findByPk ( $id );
		
		if ($model === null)
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		return $model;
	}
	
	/**
	 * Performs the AJAX validation.
	 * 
	 * @param
	 *        	CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset ( $_POST ['ajax'] ) && $_POST ['ajax'] === 'product-form')
		{
			echo CActiveForm::validate ( $model );
			Yii::app ()->end();
		}
	}
	/**
	 * 裁剪图片，把路径返回客户端
	 */
	public function actionCropZoom()
	{
		$_POST["imageSource"]=substr($_POST["imageSource"],1);
		Yii::import ( 'ext.cropzoom.JCropZoom' );
		$file=uniqid ( 'product_' ) . '.jpg';
		$filepath=params('product.image2') . $file;
		JCropZoom::getHandler ()->process ( $filepath);
		echo $file;
		webapp()->end();
	}
	/**
	 * FineUploader action
	 */
	public function actionUpload()
	{
	
		Yii::import("ext.EAjaxUpload.qqFileUploader");
        $folder=params('upload_tmp');
		$allowedExtensions = array("jpg","jpeg","png","gif");
		$sizeLimit = 8 * 1024 * 1024;
		
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		/*echo "nihao";
		die;*/
		$result = $uploader->handleUpload($folder);
		if(isset($result['error']))
			throw new Error($result['error']);

		$imgsize=getimagesize($result['filename']);
		$result['filename']='/' . path2url($result['filename']);
	

		$result['width']=$imgsize[0];
		$result['height']=$imgsize[1];
		// to pass data through iframe you will need to encode all html tags
		$ret = json_encode($result);
	
		
	
		echo $ret;
	
		return;
	}
	/**
	 * KindEditor upload action
	 */
	public function actionFileUpload()
	{
		Yii::import("ext.kindeditor.KindEditorPHP");
		$path=params('product.info2');
		$url=path2url(params('img_host') . params('product.info'));
		$upload=new KindEditorPHP($path,$url);
		$upload->file_upload();
	}
	/**
	 * KindEditor file manager action
	 */
	public function actionFileManager()
	{
		Yii::import("ext.kindeditor.KindEditorPHP");
		$path=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR . params('product.all');
		$url=params('img_host') . path2url(params('product.all'));
		$upload=new KindEditorPHP($path,$url);
		$upload->file_manager();
	}

	public function actionSetStar($product,$star)
	{
		$model=Star::model()->findByAttributes(['star_product_id'=>$product,'star_member_id'=>user()->id]);
		if(empty($star))
		{
			if(!is_null($model))
				$model->deleteAll();
		}
		else
		{
			if(is_null($model))
			{
				$model=new Star();
				$model->star_product_id=$product;
				$model->star_member_id=user()->id;
				$model->star_grade=$star;

				if($model->save())
				{
					echo '评价成功';
				}
				else
				{
					echo '评价失败';
				}
			}
			else
			{
				$model->star_grade=$star;
				$model->save(false);
			}
		}
		$update_product_star=webapp()->db->createCommand("update epmms_product set product_star=(select avg(star_grade) from epmms_star where star_product_id=:id) where product_id=:id;");
		$update_product_star->execute([':id'=>$product]);
	}
    
   

	
}
