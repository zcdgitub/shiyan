<?php
Yii::import('ext.yii-easyui.base.EuiController');
Yii::import('ext.yii-easyui.base.EuiCrudController');
Yii::import('ext.yii-easyui.base.EuiJavaScript');

class CapConfigController extends EuiCrudController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $selIndex=0;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // rights rbac filter
			'postOnly + delete,delete,save,search', // 只能通过POST请求删除
			'authentic + index,config,save,delete,search',//需要二级密码
		);
	}
	/**
	* 封顶设置
	*/
	public function actionIndex($selTab=0)
	{
		$model=new AwardConfig('search');
		$model->unsetAttributes();  // clear any default values
		$model->awardConfigType=new AwardType('search');

		$config=AwardConfigTable::getLimitConfig();

		$this->render('index',array(
			'model'=>$model,
			'config'=>$config,
			'selTab'=>(int)$selTab
		));
	}

	/**
	 * Export all data in JSON format
	 */
	public function actionSearch()
	{
		$selTab=0;
		if (isset($_REQUEST['selIndex']))
			$selTab=$_REQUEST['selIndex'];
		$key = null;
		if (isset($_REQUEST['__filter']))
			$key = '__filter';

		else if (isset($_REQUEST['q']))
			$key = 'q';

		$value = ($key)? strtolower($_REQUEST[$key]) : null;
		$criteria = $this->getModel()->search($value);
		$total = $this->getModel()->count($criteria);

		$this->applyPagination($criteria);
		$this->applyFilter($criteria);

		$rows = $this->getModel()->findAll($criteria);
		foreach($rows as $row)
		{
			$row->selIndex=$selTab;
			$row->setConfig(AwardConfigTable::getLimitConfig());
			$this->afterFind($row);
		}

		if (isset($_REQUEST['__nototal']))
			echo CJSON::encode($rows);
		else
			echo $this->exportToJSONData($rows, $total);
	}
	protected function afterFind($model)
	{
		if($model->tableName()=='epmms_cap_sum')
		{
			if(!is_null($model->cap_sum_sum_type))
			{
				$model->cap_sum_sum_type=trim($model->cap_sum_sum_type,"{} \t\r\n\0");
			}
		}
		if($model->tableName()=='epmms_cap_member_sum')
		{
			if(!is_null($model->cap_member_sum_type))
			{
				$model->cap_member_sum_type=trim($model->cap_member_sum_type,"{} \t\r\n\0");
			}
		}
	}
	protected function beforeSave($model)
	{
		if($model->tableName()=='epmms_cap_sum')
		{
			if(!is_null($model->cap_sum_sum_type))
			{
				$model->cap_sum_sum_type= '{' . $model->cap_sum_sum_type . '}';
			}
		}
		if($model->tableName()=='epmms_cap_member_sum')
		{
			if(!is_null($model->cap_member_sum_type))
			{
				$model->cap_member_sum_type= '{' . $model->cap_member_sum_type . '}';
			}
		}
		return true;
	}
	/**
	 * Finds a single active record with the specified primary key and
	 * export data to JSON format
	 */
	public function actionLoad()
	{
		parent::actionLoad();
	}


	/**
	 * The record is inserted as a row into the database table
	 */
	public function actionSave()
	{
		parent::actionSave();
	}

	/**
	 * Deletes rows with the specified primary key
	 */
	public function actionDelete()
	{
		parent::actionDelete();
	}
	public function getModel()
	{
		$selTab=0;
		if (isset($_REQUEST['selIndex']))
			$selTab=$_REQUEST['selIndex'];
		$model=new AwardConfigTable('insert',$selTab,AwardConfigTable::getLimitConfig());
		switch($selTab)
		{
			case 0:
				$model->nameColumn='cap_award_id';
				break;
			case 1:
				$model->nameColumn='cap_member_id';
				break;
			case 2:
				$model->nameColumn='cap_sum_id';
				break;
			case 3:
				$model->nameColumn='cap_member_id';
		}

		return $model;
	}
	protected function loadModel()
	{
		$selTab=0;
		if (isset($_REQUEST['selIndex']))
			$selTab=$_REQUEST['selIndex'];
		$id =isset($_REQUEST[$this->getPkColumnName()])?$_REQUEST[$this->getPkColumnName()]:null;
		unset($_REQUEST[$this->getPkColumnName()]);
		if($id)
		{
			$model = $this->getModel()->findByPk((int)$id);
			$model->selIndex=$selTab;
			$model->setConfig(AwardConfigTable::getLimitConfig());
		}
		else
		{
			$model=$this->getModel();
		}

		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='award-config-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
