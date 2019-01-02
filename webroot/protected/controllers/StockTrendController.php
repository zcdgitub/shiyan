<?php

class StockTrendController extends Controller
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
			'rights', // rights rbac filter
			'postOnly + delete', // 只能通过POST请求删除
			//'authentic + update,create,delete',//需要二级密码
		);
	}





	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0,$datetype='period')
	{
		$model=new StockTrend('search');
		$model->unsetAttributes();  // clear any default values
		$model->stockTrendMemberinfo=new Memberinfo('search');
		
		if(isset($_GET['StockTrend']))
		{
			$model->attributes=$_GET['StockTrend'];
			if(isset($_GET['StockTrend']['stockTrendMemberinfo']))
				$model->stockTrendMemberinfo->attributes=$_GET['StockTrend']['stockTrendMemberinfo'];
			
		}
		$model->datetype=$datetype;
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
		$model=StockTrend::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='stock-trend-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
