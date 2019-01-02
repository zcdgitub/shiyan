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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new <?php echo $this->modelClass; ?>('create');

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
			$this->log['target']=$model-><?php echo $nameColumn?>;
			if($model->save(true,array(<?php echo $this->getAttributeColumns()?>)))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
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
		$model=$this->loadModel($id);
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
			$this->log['target']=$model-><?php echo $nameColumn?>;
			if($model->save(true,array(<?php echo $this->getAttributeColumns()?>)))
			{
				$this->log['status']=LogFilter::SUCCESS;
				$this->log();
				user()->setFlash('success',"{$this->actionName}“{$model->showName}”" . t('epmms',"成功"));
				$this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
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
		$dataProvider=new CActiveDataProvider('<?php echo $this->modelClass; ?>');
		$this->render('list',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($selTab=0)
	{
		$model=new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();  // clear any default values
<?php
		foreach($this->tableSchema->columns as $column)
		{
			if($column->isForeignKey)
			{
				$model=Model::model($this->modelClass);
				$relations=$model->relations();
				foreach($relations as $relationName=>$relation)
				{
					if($relation[2]==$column->name)
					{
						break;
					}
				}
				if(!empty($relation) && $relation[0]==Model::BELONGS_TO)
				{
					echo "\t\t\$model->{$relationName}=new {$relation[1]}('search');\n";
				}
			}
		}
?>
		
		if(isset($_GET['<?php echo $this->modelClass; ?>']))
		{
			$model->attributes=$_GET['<?php echo $this->modelClass; ?>'];
<?php
			foreach($this->tableSchema->columns as $column)
			{
				if($column->isForeignKey)
				{
					$model=Model::model($this->modelClass);
					$relations=$model->relations();
					foreach($relations as $relationName=>$relation)
					{
						if($relation[2]==$column->name)
						{
							break;
						}
					}
					if(!empty($relation) && $relation[0]==Model::BELONGS_TO)
					{
						echo "\t\t\tif(isset(\$_GET['{$this->modelClass}']['{$relationName}']))\n\t\t\t\t\$model->{$relationName}->attributes=\$_GET['{$this->modelClass}']['{$relationName}'];\n";
					}
				}
			}
?>
			
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
