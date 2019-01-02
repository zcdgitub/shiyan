<?php

/**
 * This is the model class for table "{{map_edit}}".
 *
 * The followings are the available columns in table '{{map_edit}}':
 * @property integer $map_edit_id
 * @property integer $map_edit_src_member_id
 * @property integer $map_edit_dst_member_id
 * @property integer $map_edit_dst_order
 * @property integer $map_edit_dst_recommend_id
 * @property integer $map_edit_type
 * @property string $map_edit_add_date
 * @property string $map_edit_verify_date
 * @property integer $map_edit_operate
 * @property string $map_edit_info
 * @property integer $map_edit_is_verify
 *
 * The followings are the available model relations:
 * @property  * @property  */
class MapEdit extends Model
{
	//模型标题
	public $modelName='图谱编辑';
	//命名字段
	public $nameColumn='showName';
	public $cnt_members=0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{map_edit}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('map_edit_src_member_id, map_edit_dst_member_id, map_edit_add_date, map_edit_verify_date, map_edit_info, map_edit_is_verify', 'filter','filter'=>array($this,'empty2null')),
			array('map_edit_src_member_id, map_edit_dst_member_id, map_edit_type, map_edit_operate', 'required','on'=>'createMoveMap'),
			array('map_edit_src_member_id, map_edit_dst_member_id, map_edit_type, map_edit_operate', 'required','on'=>'createSwapMap'),
			array('map_edit_src_member_id, map_edit_operate', 'required','on'=>'createDeleteMap'),
			array('map_edit_src_member_id, map_edit_dst_member_id, map_edit_operate,map_edit_dst_recommend_id,map_edit_dst_order', 'required','on'=>'createAddMap'),
			array('map_edit_src_member_id, map_edit_operate,map_edit_dst_recommend_id', 'required','on'=>'createAddMap2'),
			array('map_edit_dst_order','ext.validators.EditArea','on'=>'createMoveMap,createAddMap'),
			array('map_edit_type, map_edit_operate, map_edit_is_verify,map_edit_dst_order', 'numerical', 'integerOnly'=>true),
			array('map_edit_src_member_id', 'ext.validators.Exist', 'className'=>'Membermap','attributeName'=>'membermap_id',
				'criteria'=>['condition'=>'membermap_is_delete=1 and membermap_is_verify=1 and membermap_id<>1'],'allowEmpty'=>false,'on'=>'createAddMap'),
			array('map_edit_dst_member_id, map_edit_dst_recommend_id', 'ext.validators.Exist', 'className'=>'Membermap','attributeName'=>'membermap_id',
				'criteria'=>['condition'=>'membermap_is_delete=0 and membermap_is_verify=1 and membermap_id<>1'],'allowEmpty'=>false,'on'=>'createAddMap'),
			array('map_edit_src_member_id, map_edit_dst_member_id', 'ext.validators.Exist', 'className'=>'Membermap','attributeName'=>'membermap_id',
				'criteria'=>['condition'=>'membermap_is_delete=0 and membermap_is_verify=1 and membermap_id<>1'],'allowEmpty'=>false,'on'=>'createSwapMap'),
			array(' map_edit_dst_member_id', 'ext.validators.Exist', 'className'=>'Membermap','attributeName'=>'membermap_id',
				'criteria'=>['condition'=>'membermap_is_delete=0 and membermap_is_verify=1'],'allowEmpty'=>false,'on'=>'createMoveMap'),
			array(' map_edit_src_member_id', 'ext.validators.Exist', 'className'=>'Membermap','attributeName'=>'membermap_id',
				'criteria'=>['condition'=>'membermap_is_delete=0 and membermap_is_verify=1 and membermap_id<>1'],'allowEmpty'=>false,'on'=>'createMoveMap'),
			array('map_edit_src_member_id', 'ext.validators.Exist', 'className'=>'Membermap','attributeName'=>'membermap_id',
				'criteria'=>['condition'=>'membermap_is_delete=0 and membermap_is_verify=1 and membermap_id<>1'],'allowEmpty'=>false,'on'=>'createDeleteMap'),
			array('map_edit_dst_member_id','ext.validators.AbleMove','on'=>'createMoveMap'),
			array('map_edit_src_member_id','ext.validators.AbleDelete','on'=>'createDeleteMap'),
			array('map_edit_add_date, map_edit_verify_date, map_edit_info', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('map_edit_id, map_edit_src_member_id, map_edit_dst_member_id, map_edit_type, map_edit_add_date, map_edit_verify_date, map_edit_operate, map_edit_info, map_edit_is_verify', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'mapEditSrcMember' => array(Model::BELONGS_TO, 'Membermap', 'map_edit_src_member_id'),
			'mapEditDstMember' => array(Model::BELONGS_TO, 'Membermap', 'map_edit_dst_member_id'),
			'mapEditDstRecommend' => array(Model::BELONGS_TO, 'Membermap', 'map_edit_dst_recommend_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'map_edit_id' => t('epmms','Map Edit'),
			'map_edit_src_member_id' => t('epmms','源会员'),
			'map_edit_dst_member_id' => t('epmms','目的会员'),
			'map_edit_dst_order' => t('epmms','目会员下滑接点位置'),
			'map_edit_type' => t('epmms','图谱类型'),
			'map_edit_add_date' => t('epmms','添加日期'),
			'map_edit_verify_date' => t('epmms','审核日期'),
			'map_edit_operate' => t('epmms','操作'),
			'map_edit_info' => t('epmms','备注'),
			'map_edit_is_verify' => t('epmms','审核状态'),
			'map_edit_dst_recommend_id' => t('emms','目的推荐人')
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$sort=new Sort('MapEdit');
		$sort->defaultOrder=array('map_edit_id'=>Sort::SORT_ASC);
		$criteria=new CDbCriteria;

		$criteria->compare('map_edit_id',$this->map_edit_id);
		$criteria->compare('map_edit_src_member_id',$this->map_edit_src_member_id);
		$criteria->compare('map_edit_dst_member_id',$this->map_edit_dst_member_id);
		$criteria->compare('map_edit_type',$this->map_edit_type);
		$criteria->compare('map_edit_add_date',$this->map_edit_add_date,true);
		$criteria->compare('map_edit_verify_date',$this->map_edit_verify_date,true);
		$criteria->compare('map_edit_operate',$this->map_edit_operate);
		$criteria->compare('map_edit_info',$this->map_edit_info,true);
		$criteria->compare('map_edit_is_verify',$this->map_edit_is_verify);
		$criteria->compare('"mapEditSrcMember".showName',@$this->mapEditSrcMember->showName);
		$criteria->compare('"mapEditDstMember".showName',@$this->mapEditDstMember->showName);
		$criteria->with=array('mapEditSrcMember','mapEditDstMember');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MapEdit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function getEditType()
	{
		if(MemberinfoItem::model()->itemVisible('membermap_parent_id'))
		{
			$etype[1]='接点关系';
		}
		$etype[2]='推荐关系';
		return $etype;
	}
	public function verify()
	{
		$this->map_edit_is_verify=1;
		$this->map_edit_verify_date=new CDbExpression('now()');
		$this->saveAttributes(['map_edit_is_verify','map_edit_verify_date']);
		switch($this->map_edit_operate)
		{
			case 1:
				return $this->del();
				break;
			case 2:
				return $this->add();
				break;
			case 3:
				return $this->move();
				break;
			case 4:
				return $this->swap();
				break;
		}
	}
	public function add()
	{
		$srcMember=Membermap::model()->findByPk($this->map_edit_src_member_id);
		$dstMember=Membermap::model()->findByPk($this->map_edit_dst_member_id);
		$srcMember->membermap_parent_id=$dstMember->membermap_parent_id;
		$srcMember->membermap_order=$dstMember->membermap_order;
		$dstMember->membermap_parent_id=$srcMember->membermap_id;
		$dstMember->membermap_order=$this->map_edit_dst_order;
		$srcMember->saveAttributes(['membermap_parent_id','membermap_order']);
		$dstMember->saveAttributes(['membermap_parent_id','membermap_order']);
		/*
		if($this->existChild($this->map_edit_dst_member_id,1,$this->map_edit_dst_order))
		{
			$child=Membermap::model()->find('membermap_parent_id=:id and membermap_order=:order',['id'=>$this->map_edit_dst_member_id,':order'=>$this->map_edit_dst_order]);

			$child->membermap_parent_id=$this->map_edit_src_member_id;
			$child->membermap_order=1;
			$child->membermap_layer=$srcMember->membermap_layer+1;
			$child->saveAttributes(['membermap_parent_id','membermap_order','membermap_layer']);
			$srcMember->membermap_parent_id=$this->map_edit_dst_member_id;
			$srcMember->membermap_order=$this->map_edit_dst_order;
			$srcMember->membermap_layer=$dstMember->membermap_layer+1;
			$srcMember->saveAttributes(['membermap_parent_id','membermap_order','membermap_layer']);
		}
		else
		{
			$srcMember->membermap_parent_id=$this->map_edit_dst_member_id;
			$srcMember->membermap_order=$this->map_edit_dst_order;
			$srcMember->membermap_layer=$dstMember->membermap_layer+1;
			$srcMember->saveAttributes(['membermap_parent_id','membermap_order','membermap_layer']);
		}
		*/
		$recommendMember=Membermap::model()->findByPk($this->map_edit_dst_recommend_id);
		$srcMember->membermap_recommend_id=$recommendMember->membermap_recommend_id;
		//$srcMember->membermap_recommend_layer=$recommendMember->membermap_recommend_layer+1;
		$srcMember->membermap_is_delete=0;
		$srcMember->saveAttributes(['membermap_recommend_id','membermap_is_delete']);
		$recommendMember->membermap_recommend_id=$srcMember->membermap_id;
		$recommendMember->saveAttributes(['membermap_recommend_d']);
		return EError::SUCCESS;
	}
	public function del()
	{
		if($this->existChild($this->map_edit_src_member_id))
		{
			return new Error('要删除的会员还有下级会员,请先删除或移除');
		}
		$info[]='原推荐人:' . $this->mapEditSrcMember->membermapRecommend->showName;
		if(MemberinfoItem::model()->itemVisible('membermap_parent_id'))
		{
			$info[]='原接点人:' . $this->mapEditSrcMember->membermapParent->showName;
			$info[]='原接点位置:' . webapp()->format->formatMapOrder($this->mapEditSrcMember->membermap_order);
		}
		$this->map_edit_info=implode(' ',$info) . ' ' . $this->map_edit_info;
		$this->saveAttributes(['map_edit_info']);
		$membermap=Membermap::model()->findByPk($this->map_edit_src_member_id);


		$models=Membermap::model()->findAll('membermap_recommend_id=:id',[':id'=>$this->map_edit_src_member_id]);
		$recommend=Membermap::model()->findByPk($membermap->membermap_recommend_id);
		foreach($models as $model)
		{
			$model->membermap_recommend_id=$membermap->membermap_recommend_id;
			$model->membermap_recommend_layer=$recommend->membermap_layer+1;
			$model->saveAttributes(['membermap_recommend_id','membermap_recommend_layer']);
		}
		$membermap->membermap_parent_id=null;
		$membermap->membermap_path=null;
		$membermap->membermap_order=null;
		$membermap->membermap_is_delete=1;
		$membermap->membermap_layer=null;
		$membermap->membermap_recommend_layer=null;
		$membermap->membermap_recommend_id=null;
		$membermap->membermap_recommend_path=null;
		$membermap->saveAttributes(['membermap_parent_id','membermap_order','membermap_recommend_id','membermap_recommend_path',
			'membermap_path','membermap_is_delete','membermap_layer','membermap_recommend_layer']);

		return EError::SUCCESS;
	}
	public function move()
	{
		if($this->map_edit_type==1)
		{

			if($this->existChild($this->map_edit_dst_member_id,1,$this->map_edit_dst_order))
			{
				throw new Error('目标位置非空,请先删除或移除');
			}

			if($this->isBelong($this->map_edit_src_member_id,$this->map_edit_dst_member_id))
			{
				throw new Error('不能向自己接点关系下面移动');
			}
			$info[]='原接点人:' . $this->mapEditSrcMember->membermapParent->showName;
			$info[]='原接点位置:' . webapp()->format->formatMapOrder($this->mapEditSrcMember->membermap_order);

			$this->map_edit_info=implode(' ',$info) . ' ' . $this->map_edit_info;
			$this->saveAttributes(['map_edit_info']);
			$srcMember=Membermap::model()->findByPk($this->map_edit_src_member_id);
			//$dstMember=Membermap::model()->findByPk($this->map_edit_dst_member_id);

			$srcMember->membermap_parent_id=$this->map_edit_dst_member_id;
			$srcMember->membermap_order=$this->map_edit_dst_order;
			$srcMember->saveAttributes(['membermap_parent_id','membermap_order']);
			return EError::SUCCESS;
		}
		else
		{

			if($this->isBelong_recommend($this->map_edit_src_member_id,$this->map_edit_dst_member_id))
			{
				throw new Error('不能向自己推荐关系下面移动');
			}
			$info[]='原推荐人:' . $this->mapEditSrcMember->membermapRecommend->showName;
			$this->map_edit_info=implode(' ',$info) . ' ' . $this->map_edit_info;
			$this->saveAttributes(['map_edit_info']);
			$srcMember=Membermap::model()->findByPk($this->map_edit_src_member_id);
			//$dstMember=Membermap::model()->findByPk($this->map_edit_dst_member_id);
			/*
			$layer_offset=$dstMember->membermap_recommend_layer+1-$srcMember->membermap_recommend_layer;
			$srcMember->updateAll(['membermap_recommend_layer'=>$srcMember->membermap_recommend_layer+$layer_offset],
				"membermap_recommend_path like :path || '/%' ",[':path'=>$srcMember->membermap_recommend_path]);
			$srcMember->membermap_recommend_layer=$srcMember->membermap_recommend_layer+$layer_offset;*/
			$srcMember->membermap_recommend_id=$this->map_edit_dst_member_id;
			$srcMember->saveAttributes(['membermap_recommend_id']);
			return EError::SUCCESS;
		}
	}
	public function swap()
	{
		$src=Membermap::model()->findByPk($this->map_edit_src_member_id);
		$swap= clone $src;
		$dst=Membermap::model()->findByPk($this->map_edit_dst_member_id);
		if($this->map_edit_type==1)
		{
			if($dst->membermap_parent_id==$src->membermap_id)
			{
				$src->membermap_parent_id=$dst->membermap_id;//上下级间交换
			}
			else
			{
				$src->membermap_parent_id = $dst->membermap_parent_id;//非上下级间交换
			}
			$src->membermap_order=$dst->membermap_order;
			//$src->membermap_layer=$dst->membermap_layer;
			$models_src=Membermap::model()->findAll('membermap_parent_id=:id',[':id'=>$this->map_edit_src_member_id]);
			foreach($models_src as $model)
			{
				if($model->membermap_id==$this->map_edit_dst_member_id)
					continue;
				$model->membermap_parent_id=$this->map_edit_dst_member_id;
				//$model->saveAttributes(['membermap_parent_id']);
			}
			if($swap->membermap_parent_id==$dst->membermap_id)
			{
				$dst->membermap_parent_id=$swap->membermap_id;
			}
			else
			{
				$dst->membermap_parent_id = $swap->membermap_parent_id;
			}
			$dst->membermap_order=$swap->membermap_order;
			//$dst->membermap_layer=$swap->membermap_layer;
			$models_dst=Membermap::model()->findAll('membermap_parent_id=:id',[':id'=>$this->map_edit_dst_member_id]);
			foreach($models_dst as $model)
			{
				if($model->membermap_id==$this->map_edit_src_member_id)
					continue;
				$model->membermap_parent_id=$this->map_edit_src_member_id;
				//$model->saveAttributes(['membermap_parent_id']);
			}
			foreach($models_src as $model)
				$model->saveAttributes(['membermap_parent_id']);
			foreach($models_dst as $model)
				$model->saveAttributes(['membermap_parent_id']);
			$src->saveAttributes(['membermap_parent_id','membermap_order']);
			$dst->saveAttributes(['membermap_parent_id','membermap_order']);
		}
		else
		{
			if($dst->membermap_recommend_id==$src->membermap_id)
				$src->membermap_recommend_id=$dst->membermap_id;//上下级间交换
			else
				$src->membermap_recommend_id=$dst->membermap_recommend_id;//非上下级间交换
			$src->membermap_recommend_layer=$dst->membermap_recommend_layer;
			$models_src=Membermap::model()->findAll('membermap_recommend_id=:id',[':id'=>$this->map_edit_src_member_id]);
			foreach($models_src as $model)
			{
				if($model->membermap_id==$this->map_edit_dst_member_id)
					continue;
				$model->membermap_recommend_id=$this->map_edit_dst_member_id;
				//$model->saveAttributes(['membermap_recommend_id']);
			}
			if($swap->membermap_recommend_id==$dst->membermap_id)
				$dst->membermap_recommend_id=$swap->membermap_id;
			else
				$dst->membermap_recommend_id=$swap->membermap_recommend_id;
			$dst->membermap_recommend_layer=$swap->membermap_recommend_layer;
			$models_dst=Membermap::model()->findAll('membermap_recommend_id=:id',[':id'=>$this->map_edit_dst_member_id]);
			foreach($models_dst as $model)
			{
				if($model->membermap_id==$this->map_edit_src_member_id)
					continue;
				$model->membermap_recommend_id=$this->map_edit_src_member_id;
				//$model->saveAttributes(['membermap_recommend_id']);
			}
			foreach($models_src as $model)
				$model->saveAttributes(['membermap_recommend_id']);
			foreach($models_dst as $model)
				$model->saveAttributes(['membermap_recommend_id']);
			$src->saveAttributes(['membermap_recommend_id','membermap_recommend_layer']);
			$dst->saveAttributes(['membermap_recommend_id','membermap_recommend_layer']);
		}
		return EError::SUCCESS;
	}
	public static function existChild($id,$type=1,$order=null)
	{
		if($type==1)
		{
			$map=new Membermap();
			if(is_null($order))
			{
				if($map->exists('membermap_parent_id=:id',[':id'=>$id]))
					return true;
				else
					return false;
			}
			else
			{
				if ($map->exists('membermap_parent_id=:id and membermap_order=:order', [':id' => $id, ':order' => $order]))
					return true;
				else
					return false;
			}
		}
		else
		{
			$map=new Membermap();
			if($map->exists('membermap_recommend_id=:id',[':id'=>$type]))
				return true;
			else
				return false;
		}
	}
	public static function isBelong($id_up,$id_down)
	{
		$member1=Membermap::model()->findByPk($id_up);
		if($member1->exists("membermap_path like :path || '/%' and membermap_id=:id2",[':path'=>$member1->membermap_path,':id2'=>$id_down]))
		{
			return true;
		}
		return false;
	}
	public static function isBelong_recommend($id_up,$id_down)
	{
		$member1=Membermap::model()->findByPk($id_up);
		if($member1->exists("membermap_recommend_path like :path || '/%' and membermap_id=:id2",[':path'=>$member1->membermap_recommend_path,':id2'=>$id_down]))
		{
			return true;
		}
		return false;
	}
	public function verifyAll()
	{
		$bak=new Backup();
		if(!$bak->autoBackup('图谱编辑' . webapp()->format->formatdatetime(time()) ,'审核时间：'.webapp()->format->formatdatetime(time())))
		{
			return new Error('备份失败');
		}
		$transaction=webapp()->db->beginTransaction();

		$models=MapEdit::model()->findAll(['condition'=>'map_edit_is_verify=0','order'=>'map_edit_id asc']);
		foreach($models as $model)
		{
			$ret=$model->verify();
			if($ret!=EError::SUCCESS)
			{
				$transaction->rollback();
				return $ret;
			}
		}
		$transaction->commit();
		return EError::SUCCESS;
	}
	public function remapAll()
	{
		$transaction=webapp()->db->beginTransaction();
		if(webapp()->name=='console')
			echo "init map... " .date('Y-m-d H:i:s') ."\r\n";
		Membermap::model()->updateAll(array('membermap_path'=>null,'membermap_recommend_path'=>null,'membermap_recommend_number'=>0,'membermap_recommend_under_number'=>0,
			'membermap_child_number'=>0,'membermap_sub_number'=>0,'membermap_sub_product_count'=>0,
			'membermap_recommend_under_product_count'=>0,'membermap_under_product_count'=>0,'membermap_under_number'=>0,
			'membermap_recommend_product_count'=>0),'membermap_id<>1');
		Membermap::model()->updateAll(array('membermap_recommend_number'=>0,'membermap_recommend_under_number'=>0,
			'membermap_child_number'=>0,'membermap_sub_number'=>0,'membermap_sub_product_count'=>0,
			'membermap_recommend_under_product_count'=>0,'membermap_under_product_count'=>0,'membermap_under_number'=>0,
			'membermap_recommend_product_count'=>0),'membermap_id=1');
		$left_right_cmd=webapp()->db->createCommand("update epmms_memberstatus set left_product_count=0,right_product_count=0,little_product_count=0;");
		$left_right_cmd->execute();
		$left_right_cmd=webapp()->db->createCommand("truncate table epmms_parent_relation;");
		$left_right_cmd->execute();
		$left_right_cmd=webapp()->db->createCommand("truncate table epmms_recommend_relation;");
		$left_right_cmd->execute();

        if(webapp()->name=='console')
            echo "recommend compute..." .date('Y-m-d H:i:s') ."\r\n";
        $this->cnt_members=0;
        if($this->traversal_mid_recommend(1)!=EError::SUCCESS)
        {

            $transaction->rollback();
            return EError::ERROR;
        }

		if(webapp()->name=='console')
			echo "parent compute..." .date('Y-m-d H:i:s') ."\r\n";
		if($this->traversal_mid_parent(1)!=EError::SUCCESS)
		{
			$transaction->rollback();
			return EError::ERROR;
		}

		$transaction->commit();
		return EError::SUCCESS;
	}
	public function traversal_mid_parent($id)
	{
		//中序遍历
		$sql_child="select membermap_id,membermap_is_verify from epmms_membermap where membermap_parent_id=:id order by membermap_order asc";
		$cmd_child=webapp()->db->createCommand($sql_child);
		$member_layer=$cmd_child->queryAll(true,[':id'=>$id]);
		foreach($member_layer as $member)
		{
			if($member['membermap_is_verify']==1)
			{
				$this->cnt_members++;
				if(webapp()->name=='console')
				{
					if($this->cnt_members%100==0)
						echo "parent verify {$this->cnt_members}th member\t time:" .date('Y-m-d H:i:s') . "\r\n";
				}
				//echo "verify ${member['membermap_id']} ...\r\n";
				$model = Membermap::model()->findByPk($member['membermap_id']);
				if (($status = $model->reMapParent()) != EError::SUCCESS)
				{
					return $status;
				}
			}
			$this->traversal_mid_parent($member['membermap_id']);
		}
		return EError::SUCCESS;
	}
	public function traversal_mid_recommend($id)
	{
		//中序遍历
		$sql_child="select membermap_id,membermap_is_verify from epmms_membermap where membermap_recommend_id=:id order by membermap_verify_seq asc";
		$cmd_child=webapp()->db->createCommand($sql_child);
		$member_layer=$cmd_child->queryAll(true,[':id'=>$id]);
		foreach($member_layer as $member)
		{
			if($member['membermap_is_verify']==1)
			{
				$this->cnt_members++;
				if(webapp()->name=='console')
				{
					if($this->cnt_members%100==0)
						echo "recommend verify {$this->cnt_members}th member\t time:" .date('Y-m-d H:i:s') . "\r\n";
				}
				//echo "verify ${member['membermap_id']} ...\r\n";
				$model = Membermap::model()->findByPk($member['membermap_id']);
				if (($status = $model->reMapRecommend()) != EError::SUCCESS)
				{
					return $status;
				}
			}
			$this->traversal_mid_recommend($member['membermap_id']);
		}
		return EError::SUCCESS;
	}
	public function getShowName()
	{
		return webapp()->format->formatMapOperate($this->map_edit_operate) . '"' . $this->mapEditSrcMember->showName  . '"';
	}
}
