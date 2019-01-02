<?php
Yii::import('ext.yii-easyui.base.EuiController');
Yii::import('ext.yii-easyui.base.EuiCrudController');
Yii::import('ext.yii-easyui.base.EuiJavaScript');

class AwardConfigController extends EuiCrudController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	public $selIndex=-1;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // rights rbac filter
			'postOnly + delete,save,delete,search', // 只能通过POST请求删除
			'authentic + index,config,save,delete,search',//需要二级密码
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=-1)
	{
		$model=new AwardConfig('search');
		$model->unsetAttributes();  // clear any default values
		$model->awardConfigType=new AwardType('search');

		$config=AwardConfigTable::getTableConfig();
		
		
		$this->render('index',array(
			'model'=>$model,
			'config'=>$config,
			'selTab'=>(int)$selTab
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionConfig($selTab=0)
	{
		$config=AwardConfigTable::getTableConfig();
		if(!isset($config[$selTab]))
			throw new CHttpException(404);
		$this->render('index',array(
			'config'=>$config,
			'selTab'=>(int)$selTab
		));
	}
	/**
	 * Export all data in JSON format
	 */
	public function actionSearch()
	{
		parent::actionSearch();
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
		$selIndex=-1;
		if (isset($_REQUEST['selIndex']))
			$selIndex=$_REQUEST['selIndex'];
		if($selIndex>=0)
		{
			$model=new AwardConfigTable(null,$selIndex,AwardConfigTable::getTableConfig());
			return $model;
		}
		else
			return AwardConfig::model();

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
