<?php

class MembermapController extends Controller
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
			'rights', // rights rbac filter
			//'postOnly + delete', // 只能通过POST请求删除
			//'authentic + tree,orgMap,treeRecommend,orgMapRecommend,update,create,delete',//需要二级密码
		);
	}
	public function allowedActions()
	{
		return 'getMemberType';
	}
	/**
	 * 网络图ajax
	 *
	 */
	public function actionOrgMapJson($id=null,$levels=5,$type='jit',$dataType='parent')
	{
		header('Content-Type:application/json;charset=' . params('web_charset'));
		if(empty($id))
			return;
		if(is_null($id))
			return;
		if(empty($levels))
			return;
		$node=Membermap::model()->findByPk($id,['with'=>['membermap','membertype','membermapAgent','membermapVerifyMember']]);
		if(is_null($node))
		{
			webapp()->end();
		}

		if($type=='jit')
			$user_tree=Membermap::getTree($node,$levels-1,$dataType,true,config('map','branch'));
		else
			$user_tree=Membermap::getTree2($node,$levels,$dataType,true);
		echo json_encode($user_tree);
		webapp()->end();
	}

	/**
	 * 网络图页面
	 */
	public function actionOrgMap($dataType='parent')
	{


		if($dataType=='parent')
		{
			if(!webapp()->user->checkAccess('Membermap.OrgMap'))
			{
				$this->actionTree($dataType);
				return;
			}
		}
		else
		{
			if(!webapp()->user->checkAccess('Membermap.OrgMapRecommend'))
			{
				$this->actionTree($dataType);
				return;
			}
		}
		$levels=config('map','levels');
		$orientation=config('map','orientation');
		$model=new Membermap('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberinfo=new Memberinfo('search');
		$model->membermapParent=new Membermap('search');
		$model->membermapRecommend=new Membermap('search');
		$model->membermapMembertypeLevel=new MemberType('search');
		$model->membermapAgent=new Membermap('search');
		$model->membermapVerifyMember=new Memberinfo('search');

		if(isset($_GET['Membermap']))
		{


			$model->attributes=$_GET['Membermap'];
			if(isset($_GET['Membermap']['memberinfo']))
				$model->memberinfo->attributes=$_GET['Membermap']['memberinfo'];
			if(isset($_GET['Membermap']['membermapParent']))
				$model->membermapParent->attributes=$_GET['Membermap']['membermapParent'];
			if(isset($_GET['Membermap']['membermapRecommend']))
				$model->membermapRecommend->attributes=$_GET['Membermap']['membermapRecommend'];
			if(isset($_GET['Membermap']['membermapMembertypeLevel']))
				$model->membermapMembertypeLevel->attributes=$_GET['Membermap']['membermapMembertypeLevel'];
			if(isset($_GET['Membermap']['membermapAgent']))
				$model->membermapAgent->attributes=$_GET['Membermap']['membermapAgent'];
			if(isset($_GET['Membermap']['membermapVerifyMember']))
				$model->membermapVerifyMember->attributes=$_GET['Membermap']['membermapVerifyMember'];

		}

		if(user()->isAdmin())
		{

			$top_model=Membermap::model()->findByAttributes(['membermap_layer'=>1]);
		}

		if(is_null($model->memberinfo->memberinfo_account))
		{

			if(user()->isAdmin())
				$model->membermap_id=$top_model->membermap_id;
			else
				$model->memberinfo->memberinfo_account=user()->name;
		}

		$node=null;
		if(!user()->isAdmin())
		{
			$my_id=user()->id;
		}
		else
		{
			$my_id=$top_model->membermap_id;
		}

		if(isset($_GET['Membermap']['memberinfo']['memberinfo_account']))
		{
			$name=@$_GET['Membermap']['memberinfo']['memberinfo_account'];
			$search_id=Memberinfo::name2id($name);
		}
		else
		{
			if(user()->isAdmin())
				$search_id=$top_model->membermap_id;
			else
				$search_id=user()->id;
		}
		if($dataType=='parent')
		{

			if(Membermap::model()->exists(['join'=>',epmms_membermap as r','condition'=>"t.membermap_id=:my_id and r.membermap_id=:search_id and r.membermap_path like t.membermap_path || '%'",'params'=>[':my_id'=>$my_id,':search_id'=>$search_id]]))
			{
				$node=$model->findByPk($search_id);
			}

		}
		else
		{
			if(Membermap::model()->exists(['join'=>',epmms_membermap as r','condition'=>"t.membermap_id=:my_id and r.membermap_id=:search_id and r.membermap_recommend_path like t.membermap_recommend_path || '%'",'params'=>[':my_id'=>$my_id,':search_id'=>$search_id]]))
			{
				$node=$model->findByPk($search_id);
			}
		}
		if(is_null($node))
		{
			user()->setFlash('error',t('epmms',"搜索的帐号不存在"));
			if (webapp()->request->isAjaxRequest){
	              header('Content-Type: application/json');      
	              echo CJSON::encode(['success'=>false,'msg'=>'搜索的帐号不存在']);
	              return;                       
            }
		}
		$json_tree=is_null($node)?null:Membermap::getTree($node,$levels-1,$dataType,false,config('map','branch'));

			if(webapp()->request->isAjaxRequest){
	            header('Content-Type: application/json');
	            
		        $data=$json_tree;
	          
	            echo CJSON::encode( $data);
	            return;
	        }

		$this->render('orgmap',['model'=>is_null($node)?$model:$node,'json_tree'=>$json_tree,'dataType'=>$dataType,'levels'=>$levels,'orientation'=>$orientation]);
	}
	public function actionOrgMapRecommend()
	{
		$this->actionOrgMap('recommend');
	}
	public function actionTreeRecommend()
	{
		$this->actionTree('recommend');
	}
	/**
	 * 树形结构
	 */
	public function actionTree($dataType='parent')
	{
		$levels=config('map','tree_levels');
		$model=new Membermap('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberinfo=new Memberinfo('search');
		$model->membermapParent=new Membermap('search');
		$model->membermapRecommend=new Membermap('search');
		$model->membermapMembertypeLevel=new MemberType('search');
		$model->membermapAgent=new Membermap('search');
		$model->membermapVerifyMember=new Memberinfo('search');

		if(isset($_GET['Membermap']))
		{
			$model->attributes=$_GET['Membermap'];
			if(isset($_GET['Membermap']['memberinfo']))
				$model->memberinfo->attributes=$_GET['Membermap']['memberinfo'];
			if(isset($_GET['Membermap']['membermapParent']))
				$model->membermapParent->attributes=$_GET['Membermap']['membermapParent'];
			if(isset($_GET['Membermap']['membermapRecommend']))
				$model->membermapRecommend->attributes=$_GET['Membermap']['membermapRecommend'];
			if(isset($_GET['Membermap']['membermapMembertypeLevel']))
				$model->membermapMembertypeLevel->attributes=$_GET['Membermap']['membermapMembertypeLevel'];
			if(isset($_GET['Membermap']['membermapAgent']))
				$model->membermapAgent->attributes=$_GET['Membermap']['membermapAgent'];
			if(isset($_GET['Membermap']['membermapVerifyMember']))
				$model->membermapVerifyMember->attributes=$_GET['Membermap']['membermapVerifyMember'];

		}
		$node=null;
		if(!user()->isAdmin())
		{
			$my_id=user()->id;
		}
		else
		{
			$my_id=1;
		}

		if(isset($_GET['Membermap']['memberinfo']['memberinfo_account']))
		{
			$search_id=Memberinfo::name2id(@$_GET['Membermap']['memberinfo']['memberinfo_account']);
		}
		else
		{
			if(user()->isAdmin())
				$search_id=1;
			else
				$search_id=user()->id;
		}

		if($dataType=='parent')
		{
			if(Membermap::model()->exists(['join'=>',epmms_membermap as r','condition'=>"t.membermap_id=:my_id and r.membermap_id=:search_id and r.membermap_path like t.membermap_path || '%'",'params'=>[':my_id'=>$my_id,':search_id'=>$search_id]]))
			{
				$node=$model->findByPk($search_id);
			}

		}
		else
		{
			if(Membermap::model()->exists(['join'=>',epmms_membermap as r','condition'=>"t.membermap_id=:my_id and r.membermap_id=:search_id and r.membermap_recommend_path like t.membermap_recommend_path || '%'",'params'=>[':my_id'=>$my_id,':search_id'=>$search_id]]))
			{
				$node=$model->findByPk($search_id);
			}
		}

		if(is_null($node))
		{
			user()->setFlash('error',t('epmms',"搜索的帐号不存在"));
		}
		$json_tree=is_null($node)?null:Membermap::getTree2($node,$levels-1,$dataType,false);

		$this->render('tree',['model'=>is_null($node)?$model:$node,'json_tree'=>$json_tree,'dataType'=>$dataType]);
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id=null)
	{
		if(!user()->isAdmin() && is_null($id))
			$id=user()->id;
		 if (webapp()->request->isAjaxRequest){
            header('Content-Type: application/json');
             $model=$this->loadModel($id);
             $data=$model->toArray();
	            echo CJSON::encode($data);
	            return;
        }
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionIndex($isVerifyType=0)
	{

		$model=new Membermap('search');
		$model->unsetAttributes();  // clear any default values
		$model->memberinfo=new Memberinfo('search');
		if(isset($_GET['Membermap']))
		{
			$_GET['Membermap']['membermap_recommend_id']=@Memberinfo::name2id($_GET['Membermap']['membermap_recommend_id']);
			$_GET['Membermap']['membermap_parent_id']=@Memberinfo::name2id($_GET['Membermap']['membermap_parent_id']);
			$_GET['Membermap']['membermap_bond_id']=@Memberinfo::name2id($_GET['Membermap']['membermap_bond_id']);
			$_GET['Membermap']['membermap_agent_id']=@Memberinfo::name2id($_GET['Membermap']['membermap_agent_id']);
			$model->attributes=$_GET['Membermap'];
			if(isset($_GET['Membermap']['memberinfo']))
			{
				$model->memberinfo->attributes=$_GET['Membermap']['memberinfo'];
			}
		}
		$model->membermap_is_verify=(int)$isVerifyType;
		if(user()->role=='member')
		{
			$model->membermap_id=user()->id;
		}
		if(user()->role=='agent')
		{
			$model->membermap_agent_id=user()->id;
		}

		$this->render('index',array(
			'model'=>$model,
			'isVerifyType'=>(int)$isVerifyType,
			'isAgent'=>0
		));
	}

	public function actionLeafs($id)
	{
		$r_member=Membermap::model()->findbyPk($id);
		$leafs_member=[];
		$leafs_member2=[];
		$models=Membermap::model()->findAll(['condition'=>"(membermap_child_number=0 or (membermap_child_number=1 and membermap_recommend_number>=1)) and membermap_path like :r_path || '/1%' and membermap_path like :r_path2 || '%' ",'order'=>'membermap_layer asc','params'=>[':r_path'=>$r_member->membermap_path,':r_path2'=>user()->map->membermap_path]]);
		foreach($models as $model)
		{
			$leafs_member[]=$model->showName;
		}
		$models=null;
		$model=null;
		$models=Membermap::model()->findAll(['condition'=>"(membermap_child_number=0 or (membermap_child_number=1 and membermap_recommend_number>=1)) and membermap_path like :r_path || '/2%' and membermap_path like :r_path2 || '%' ",'order'=>'membermap_layer asc','params'=>[':r_path'=>$r_member->membermap_path,':r_path2'=>user()->map->membermap_path]]);
		foreach($models as $model)
		{
			$leafs_member2[]=$model->showName;
		}
		$this->render('leafs',['leafs_member'=>$leafs_member,'leafs_member2'=>$leafs_member2,'r_member'=>$r_member]);
	}
	public function actionGetMemberType($username)
	{
		$info=Memberinfo::model()->findByAttributes(['memberinfo_account'=>$username]);
		if(!empty($info))
			echo $info->membermap->membermap_membertype_level;
		else
			echo '-1';
	}
	public function actionAutoParent($name=null)
	{
		header('content-type:application/json;charset=utf8');
		if(empty($name))
		{
			echo json_encode(['parent' => null, 'order' => null]);
			return;
		}
		$id=Memberinfo::name2id($name);
		$sql_child="select c.membermap_id from epmms_membermap as my,epmms_membermap as c where my.membermap_id=:id  and c.membermap_path like my.membermap_path || '%' and c.membermap_child_number<2 order by c.membermap_layer asc,c.membermap_path asc";
		$cmd_child=webapp()->db->createCommand($sql_child);
		$member_id=$cmd_child->queryScalar([':id'=>$id]);
		$member=Membermap::model()->findByPk($member_id);
		if($member->exists("membermap_parent_id=:id and membermap_order=1",['id'=>$member_id]))
		{
			$order=2;
		}
		else
		{
			$order=1;
		}
		echo json_encode(['parent'=>Memberinfo::id2name($member_id),'order'=>$order]);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Membermap('create');
		if(isset($_POST['Membermap']))
		{
			$old_post=$_POST['Membermap'];

			$_POST['Membermap']['membermap_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_id']);
			$_POST['Membermap']['membermap_parent_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_parent_id']);
			$_POST['Membermap']['membermap_recommend_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_recommend_id']);

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

			//$_POST['Membermap']['membermap_bond_id']=Memberinfo::name2id(@$_POST['Membermap']['membermap_bond_id']);
			if(MemberType::model()->count()<=1)
			{
				$_POST['Membermap']['membermap_membertype_level']=MemberType::model()->find()->membertype_level;;
			}
		}

		$this->performAjaxValidation([new Membermap('create')]);

		if(isset($_POST['Membermap']))
		{
			$model->attributes=$_POST['Membermap'];
			$model->membermap_membertype_level_old=$model->membermap_membertype_level;
			$model->membermap_reg_member_id=user()->isAdmin()?null:user()->id;
             $model->membermap_buy_date=new CDbExpression('now()');
			/*
			if(!MemberinfoItem::model()->itemVisible('membermap_agent_id'))
			{
				$model->membermap_is_agent=1;
				if(user()->isAdmin())
					$model->membermap_agent_id=1;
				else
					$model->membermap_agent_id=user()->id;
			}
			*/
			$transaction=webapp()->db->beginTransaction();
			if($model->save())
			{
				$transaction->commit();
				$this->render('view',array(
					'model'=>$model,
				));
				Yii::app()->end();
			}
			$transaction->rollback();
		}
		$model->membermap_no=$model->genUsername();
		if(isset($_POST['Membermap']))
		{
			$model->attributes=$old_post;
		}
		$this->render('create',array('model'=>$model,'financeType'=>FinanceType::model()->findAll()));
	}
	/**
	 * 审核会员
	 * @param mixed $id
	 */
	public function actionVerify($id)
	{
		$model=$this->loadModel($id);
		$this->log['target']=$model->showName;
		if(($status=$model->verifyMap())===EError::SUCCESS)
		{
			$this->log['status']=LogFilter::SUCCESS;
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
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败,电子币不足"));
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
			$this->log['status']=LogFilter::FAILED;
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
		}
		$this->log();
		$this->redirect([user()->role=='agent'?'indexAgent':'index'],true);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{

		$model=$this->loadModel($id);
		if($id==1 || $id==2)
		{
			$this->log['status']=LogFilter::FAILED;
			$this->log['target']=$model->showName;
			$this->log['info']='该会员是根会员，不能删除';
			user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"该会员是根会员，不能删除"));
		}
		elseif($model->membermap_is_verify==1)
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
				if($map=Membermap::model()->findByPk($id))
					$map->delete();

				$this->log['target']=$model->showName;
				$model->delete();
				$transaction->commit();
				$this->log['status']=LogFilter::SUCCESS;
			}
			catch(Exception $e)
			{
				$transaction->rollback();
				$this->log['status']=LogFilter::FAILED;
				user()->setFlash('error',"{$this->actionName}“{$model->showName}”" . t('epmms',"失败"));
			}
		}
		$this->log();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		else
			echo user()->getFlash('error');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Membermap::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		/*if(!user()->isAdmin() && $model->membermap_agent_id!=user()->id)
		{
			throw new CHttpException(503,'没有权限执行此操作.');
		}*/
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='membermap-form')
		{
			echo CActiveForm::validate($model);
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
    public function actionGetLevelCount()
    {

    	$levelCount=[];

    		$levelCount[-1][0]=(string)new DbEvaluate("public.get_order_under_product_count_date2(:id,:order)",[':id'=>user()->id,':order'=>1]);
            $levelCount[-1][1]=(string)new DbEvaluate("public.get_order_under_product_count_date2(:id,:order)",[':id'=>user()->id,':order'=>2]);
            $levelCount[0][0]=(string)new DbEvaluate("public.get_order_under_product_count_date(:id,:order)",[':id'=>user()->id,':order'=>1]);
            $levelCount[0][1]=(string)new DbEvaluate("public.get_order_under_product_count_date(:id,:order)",[':id'=>user()->id,':order'=>2]);
    	for($i=1;$i<=7;$i++)
    	{
    		/*public.get_order_under_product_count_date(1,(get_config_award(612) || ' days')::interval,1)*/

    		$levelCount[$i][0]=(string)new DbEvaluate("award.get_level_count(:id,:order,:level)",[':id'=>user()->id,':order'=>1,':level'=>$i]);
    		$levelCount[$i][1]=(string)new DbEvaluate("award.get_level_count(:id,:order,:level)",[':id'=>user()->id,':order'=>2,':level'=>$i]);	
   
    	/*	$levelCount[$i][1]=(string)new DbEvaluate('award.get_level_count(:id,:date,:order,:level)',[':id'=>user()->id,':order'=>2,':level'=>$i]);*/
    	}
	       	/* 	echo "<Pre>";
    var_dump($levelCount);
    die;*/
       if(webapp()->request->isAjaxRequest){
	       	header("content-type:application/json");
	       	$data['success']=true;
	       	$data['level']=$levelCount;

	    	echo CJSON::encode($data);
	    	return;
       }
    }
}

