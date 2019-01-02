<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
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
			'authentic + index',//需要二级密码
		);
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionIndex($selTab=0)
	{
		$model=<?php echo $this->modelClass; ?>::model()->find();
		$model->scenario='update';
		<?php
		$model=Model::model($this->modelClass);
		$rules=$model->rules();
		$validators=array();
		foreach($rules as $rule)
			$validators[]=$rule[1];
		if(in_array('unique',$validators) || in_array('exist',$validators) || in_array('ext.validators.Exist',$validators))
		{
			echo '$this->performAjaxValidation($model);' . "\n";
		}
		else
		{
			echo '// 如果需要AJAX验证反注释下面一行' . "\n";
			echo '		// $this->performAjaxValidation($model);' . "\n";
		}
		?>

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_POST['<?php echo $this->modelClass; ?>'];
			$this->log['target']=null;
			if($model->save(true,array(<?php echo $this->getAttributeColumns()?>)))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',t('epmms',"修改“{$model->modelName}”" . "成功"));
			}
			else
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('error',t('epmms',"修改“{$model->modelName}”" . "失败"));
			}
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
		$model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='<?php echo $this->class2id($this->modelClass); ?>-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
